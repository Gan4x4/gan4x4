# How to sync this(dev) VPS with prod

Project hosted in docker 

1. `git commit` on dev
2. `git push` on dev
3. `ssh` to prod VPS
4. `cd /var/www/gan4x4`
5. `git pull`
6. `sudo docker exec -it gan4x4-app composer install --no-dev --optimize-autoloader --no-interaction`
    
7. `sudo docker exec -it gan4x4-app php artisan migrate --force`
8. `sudo docker exec -it gan4x4-app php artisan optimize:clear && sudo docker exec -it gan4x4-app php artisan config:cache`
9. Copy DB file: `database/database.sqlite` from dev to prod (if needed)
