#!/bin/sh
set -e

# Render fournit la variable PORT : Apache doit écouter dessus
sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT:-80}>/" /etc/apache2/sites-available/000-default.conf

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan migrate --force

exec apache2-foreground
