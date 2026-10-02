# Deploying Leafline (DigitalOcean + GitHub Actions)

Same setup as Evercrest: one Ubuntu droplet, one Linux user per site, code deploys on `git push`.
Full background and the server bootstrap live in Evercrest's `self-hosting.md` (Steps 1–5).

**Code** (`themes/leafline`) deploys from git. **Content** (pages, posts, images, enquiries)
lives on the server and is never touched by a deploy.

| Placeholder | Means |
|---|---|
| `SERVER_IP` | the droplet's IP |
| `leafline.example.com` | the domain or subdomain for this site |
| `you` | your sudo login on the droplet |

## 1. Server (once)

- **Reusing the Evercrest droplet:** the stack and the `new-site` script are already there. Skip to step 2.
- **New droplet:** Ubuntu 24.04, 2 GB RAM, add your SSH key, then follow Evercrest `self-hosting.md` Steps 2–5.

## 2. Create the site

DNS: an A record for `leafline.example.com` → `SERVER_IP` (not needed if a wildcard `*` record already exists).

```bash
sudo new-site leafline leafline.example.com
```

## 3. Move the Studio site up (once)

On your PC (PowerShell):

```powershell
cd C:\Users\ocamp\Studio\leafline
studio export leafline.sql --mode db
C:\Windows\System32\tar.exe -czf leafline-files.tgz -C wp-content themes/leafline uploads
scp leafline.sql leafline-files.tgz you@SERVER_IP:/tmp/
```

Never upload `db.php`, `database/` or `mu-plugins/`: they're Studio's SQLite setup and would break MySQL.

On the server:

```bash
sed -i 's/utf8mb4_0900_ai_ci/utf8mb4_unicode_ci/g' /tmp/leafline.sql
sudo -u leafline -H bash -c '
  cd ~/public
  tar -xzf /tmp/leafline-files.tgz -C wp-content
  wp db import /tmp/leafline.sql
  wp search-replace "http://localhost:8889" "https://leafline.example.com" --all-tables
  wp theme activate leafline
  wp rewrite flush
'
rm /tmp/leafline.sql /tmp/leafline-files.tgz
sudo -u leafline -H wp user update admin --prompt=user_pass --path=/home/leafline/public   # Studio's password is known locally
```

## 4. CI/CD (once)

1. Allow the deploy key for the `leafline` user only (key pair is `~/.ssh/leafline_actions` on the PC):
   ```powershell
   scp $env:USERPROFILE\.ssh\leafline_actions.pub you@SERVER_IP:/tmp/
   ```
   ```bash
   sudo -u leafline -H bash -c 'mkdir -p ~/.ssh && chmod 700 ~/.ssh && cat /tmp/leafline_actions.pub >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys'
   rm /tmp/leafline_actions.pub
   ```
2. GitHub → repo **Settings → Secrets and variables → Actions**:
   - **Secrets** tab → `DEPLOY_KEY`: full contents of `~/.ssh/leafline_actions` (the private file, not `.pub`)
   - **Secrets** tab → `SERVER_IP`: the droplet IP
3. **Actions** tab → **Deploy Leafline** → **Run workflow** to test.

✅ The run goes green, and a small change pushed to `main` shows up on the live site.
