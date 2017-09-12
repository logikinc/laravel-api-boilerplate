#!/bin/bash
#
# Install API Boilerplate
#
# Usage: ./install.sh
# -----------------------------------------------------------------------------

#
# VARIABLES
#

echo "Hello $USER, Let's do this!"

echo "What is the name your API?: \c "
    read api_name

echo "What is the name of the database you will be using?: \c "
    read  db_name

echo "What database user will you be using?: \c "
    read db_user

echo "What is the password for this user? Leave an empty string if blank: \c "
    read db_pass


# Remove GIT references and initialize a new repository
# -----------------------------------------------------------------------------

echo "Removing GIT"

rm -rf .git
git init
# -----------------------------------------------------------------------------


# Install and provision the backend code
# -----------------------------------------------------------------------------

echo "Setting up Laravel API"

cp .env.example .env
composer update
php artisan key:generate

output=`php artisan jwt:generate`
secret="${output#*[}"
secret="${secret%%]*}"
php artisan env:set JWT_SECRET $secret

php artisan env:set APP_NAME $api_name
php artisan env:set DB_DATABASE $db_name
php artisan env:set DB_USERNAME $db_user
if [ $db_pass ]; then
    php artisan env:set DB_PASSWORD $db_pass
fi

`echo create database $db_name | mysql -u root`
php artisan migrate --seed


# Install and compile the frontend code
# -----------------------------------------------------------------------------

yarn
yarn run dev


# Run Tests To ensure all is working
# -----------------------------------------------------------------------------

vendor/bin/phpunit