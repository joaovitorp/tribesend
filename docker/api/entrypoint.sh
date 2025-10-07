#!/bin/bash
set -e

# Garantir permissões corretas para Laravel
echo "Configurando permissões do Laravel..."
chown -R www-data:www-data /var/www/app/storage /var/www/app/bootstrap/cache
chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache

# Limpar caches se necessário
if [ "$APP_ENV" = "local" ] || [ "$APP_ENV" = "development" ]; then
    echo "Limpando caches de desenvolvimento..."
    php artisan config:clear || true
    php artisan cache:clear || true
    php artisan view:clear || true
    php artisan route:clear || true
fi

echo "Starting Migrations..."
php artisan migrate --force --no-interaction

# Instalar dependências node 
echo "Installing Node Dependencies..."
npm install & npm run build

# Executar o comando original
exec "$@"
