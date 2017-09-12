#!/bin/bash
#
# Install Laravel on a Docker Container
#
# Usage: ./install.sh
# -----------------------------------------------------------------------------

cp .env.example .env

git clone https://github.com/Laradock/laradock.git
cd laradock
cp env-example .env
sed -i -e 's/PHP_VERSION=71/PHP_VERSION=70/g' .env
cd mysql
sed -i -e 's/MYSQL_VERSION=8.0/MYSQL_VERSION=5.7/g' Dockerfile
cd ../
docker-compose up -d nginx mysql redis php-fpm php-worker
docker-compose exec workspace bash
cd /var/www