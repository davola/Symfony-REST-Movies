#!/bin/sh

echo "Resetting app database..."
php bin/console doctrine:database:drop --force --if-exists
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
echo "Database reset successfully."
