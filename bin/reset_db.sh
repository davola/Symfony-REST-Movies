#!/bin/sh

ENV=dev
if [ $1 ] && [ -n $1 ]; then
	ENV=$1
fi;

echo "environment --env="$ENV;

echo "Resetting app database..."
php bin/console doctrine:database:drop --env=$ENV --force --if-exists
php bin/console doctrine:database:create --env=$ENV
php bin/console doctrine:migrations:migrate --env=$ENV --no-interaction
echo "Database reset successfully."
