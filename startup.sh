#!/bin/bash

cd Proyecto-Bisztry

# Configura permisos, instala dependencias, ejecuta migraciones, etc.
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
