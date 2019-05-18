#!/bin/bash

ssh anderwijs-nl@ssh.pcextreme.nl << EOF

alias php=php72
cd domains/anderwijs.nl/htdocs/aas2
php artisan down
git pull origin master
php composer.phar self-update
php composer.phar update
php artisan clear-compiled
php artisan migrate --force
php artisan optimize
php artisan up

EOF