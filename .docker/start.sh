#!/bin/sh

cd /application

if [ ! -f ./.env.local ]; then
    cp ./.env ./.env.local
fi

composer install

chmod 0777 -R var

./bin/console doctrine:database:create --env=dev --if-not-exists
./bin/console doctrine:database:create --env=test --if-not-exists

./bin/console doctrine:migrations:migrate --env=dev
./bin/console doctrine:migrations:migrate --env=test

./bin/console doctrine:fixtures:load --env=dev

/usr/bin/supervisord -n
