# How to sync this(dev) VPS with prod


1. git commit on dev
2. git push on dev
3. ssh to prod VPS
4. cd /var/www/gan4x4
5. git pull
6. composer install --no-dev --optimize-autoloader
7. php artisan migrate --force
8. php artisan optimize:clear
9. php artisan config:cache
10. check site and logs
11. Copy DB file: database/database.sqlite from dev to prod (if needed)

