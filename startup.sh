#!/bin/bash

# Navega al directorio de la aplicación
cd Proyecto-Bisztry

# Instala dependencias de Composer
composer install --no-interaction --prefer-dist --optimize-autoloader

# Genera la clave de aplicación de Laravel
php artisan key:generate

# Ejecuta migraciones si es necesario (puedes quitar esta línea si no quieres migrar en cada despliegue)
# php artisan migrate --force

# Crea enlaces simbólicos al sistema de archivos (por ejemplo, para el almacenamiento)
php artisan storage:link

# Limpia y cachea configuraciones
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Copia los archivos de la carpeta public a /home/site/wwwroot (ruta usada por Azure para servir contenido)
cp -a public/. /home/site/wwwroot

# Importante: asegúrate de que el script tenga permisos de ejecución si corres esto en Linux.
