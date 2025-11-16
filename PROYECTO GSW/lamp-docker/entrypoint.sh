#!/bin/bash
set -e

# Si no existe el proyecto Laravel, instalarlo
if [ ! -f artisan ]; then
    echo "Instalando Laravel..."
    composer create-project laravel/laravel .
fi

# Instalar dependencias Node automáticamente
if [ ! -d node_modules ]; then
    echo "Instalando dependencias de Node..."
    npm install
   # npm run build
fi

php artisan key:generate || true

apache2-foreground
