#!/bin/sh

cd /application

if [[ ! -f ./.env.local ]]; then
    cp ./.env ./.env.local
fi

composer install

chmod 0777 -R var

/usr/bin/supervisord -n
