# Docker Runtime (App Only)

This setup runs only Laravel PHP-FPM in Docker. Host `nginx` stays on VPS.

## 1) Build image

```bash
cd /var/www/gan4x4
./scripts/docker/build_app_image.sh
```

## 2) Run container

```bash
cd /var/www/gan4x4
./scripts/docker/run_app_container.sh
```

Defaults:
- container: `gan4x4-app`
- image: `gan4x4-app:latest`
- FPM port: `127.0.0.1:9001`
- bind mount (single source of truth):
  - `/var/www/gan4x4 -> /var/www/html`

## 3) Host nginx

Use `docker/nginx-host-site.example.conf` as template.

Important:
- `root` stays host path: `/var/www/gan4x4/public`
- `fastcgi_pass` points to container port: `127.0.0.1:9001`
- `SCRIPT_FILENAME`/`DOCUMENT_ROOT` must use container path: `/var/www/html/public`

## 4) Operations

```bash
# logs
./scripts/docker/logs_app_container.sh

# stop/remove
./scripts/docker/stop_app_container.sh
```

## 5) Notes

- Image is runtime-only (PHP + extensions + composer), app code is mounted from host.
- After code changes, restart container; rebuild image only when runtime deps change.
- Entry point runs migrations by default (`RUN_MIGRATIONS=1`).
- SQLite DB path is forced inside container to `/var/www/html/database/database.sqlite`.
- Uploaded images are persisted in `storage/app/public/design` (inside mounted project folder).
