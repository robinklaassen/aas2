#!/bin/bash

ssh anderwijsnl@ftp.anderwijs.nl << EOF

cd ~/domains/anderwijs.nl/htdocs/aas2
php72 composer.phar self-update
php72 composer.phar install
php72 artisan clear-compiled
php72 artisan migrate --force
php72 artisan optimize
php72 artisan up

EOF