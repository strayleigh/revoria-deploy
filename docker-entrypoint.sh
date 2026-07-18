#!/bin/sh
# Ganti port Apache sesuai $PORT yang diberikan Railway (default: 8080)
PORT=${PORT:-8080}

sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-enabled/*.conf

exec apache2-foreground
