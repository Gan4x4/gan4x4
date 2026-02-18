#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/html"
cd "${APP_DIR}"

mkdir -p \
  storage/framework/cache \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  storage/app/public/design/projects \
  storage/app/public/design/work \
  storage/app/public/design/video \
  bootstrap/cache \
  database

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
  DB_PATH="${DB_DATABASE:-${APP_DIR}/database/database.sqlite}"
  mkdir -p "$(dirname "${DB_PATH}")"
  [ -f "${DB_PATH}" ] || touch "${DB_PATH}"
fi

chown -R www-data:www-data storage bootstrap/cache database || true
chmod -R ug+rwX storage bootstrap/cache database || true

# Prevent stale cached manifests from host from breaking container boot.
rm -f bootstrap/cache/*.php || true
php artisan package:discover --ansi --no-interaction

if [ "${RUN_MIGRATIONS:-1}" = "1" ]; then
  php artisan migrate --force
fi

exec "$@"
