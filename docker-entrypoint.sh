#!/bin/sh
set -e

# Render fournit la variable PORT : Apache doit écouter dessus
sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT:-80}>/" /etc/apache2/sites-available/000-default.conf

php artisan config:clear
php artisan config:cache

# Crée / met à jour les tables à chaque démarrage (n'efface rien)
php artisan migrate --force

# Données de démonstration : à activer avec RUN_SEED=true (le seeder ne fait rien si la base contient déjà des utilisateurs)
if [ "${RUN_SEED}" = "true" ]; then
  php artisan db:seed --force
fi

exec apache2-foreground
