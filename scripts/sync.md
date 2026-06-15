# How to sync this dev VPS with prod

Project runs in Docker. Pull code first, then use one script for rebuild, dependency install, container restart, Laravel cache/migrations, Chromium check, tests when available, smoke checks, and generated CV cache verification. The script uses plain `docker` when possible and asks for `sudo` only if Docker requires it.

## Test on this VPS first

```bash
cd /var/www/gan4x4
git pull --ff-only
./sync
```

Direct CV test URL after deployment:

```text
https://162.245.191.118:8443/cv/download
```

## Update prod

```bash
ssh tor@161788
cd /var/www/gan4x4
git pull --ff-only
./sync
```

The script runs:

```text
preflight checks
docker build -t gan4x4-app:latest .
composer install inside the new image
recreate gan4x4-app
php artisan optimize:clear
php artisan migrate --force
php artisan config:cache
php artisan test when available
smoke checks for /, /projects, /experience, /video, /cv/download
generated CV PDF cache check
```

## Optional DB sync

Copy `database/database.sqlite` from dev to prod only when content changed locally and was not edited on prod.
