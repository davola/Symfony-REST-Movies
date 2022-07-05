#!/bin/sh

ENV=dev
if [ $1 ] && [ -n $1 ]; then
	ENV=$1
fi;

echo -e "\nEnvironment $ENV detected."

echo -e "\nChecking/initializing lexik JWT keypair"
php bin/console lexik:jwt:generate-keypair --skip-if-exists

echo "Resetting app database..."
php bin/console doctrine:database:drop --env=$ENV --force --if-exists
php bin/console doctrine:database:create --env=$ENV
php bin/console doctrine:migrations:migrate --env=$ENV --no-interaction
echo "Database reset successfully."

echo -e "\nLoading fixtures"
php bin/console hautelook:fixtures:load --env=$ENV --no-interaction --no-bundles
echo -e "Done loading fixtures.\n"
