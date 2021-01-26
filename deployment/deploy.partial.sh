#!/bin/bash

ssh anderwijsnl@ftp.anderwijs.nl << EOF

cd ~/domains/anderwijs.nl/htdocs/aas2
${PHP_EXECUTABLE} artisan down
git pull origin master

EOF

rsync -r $TRAVIS_BUILD_DIR/public/js \
         $TRAVIS_BUILD_DIR/public/css \
         $TRAVIS_BUILD_DIR/public/fonts \
    anderwijsnl@ftp.anderwijs.nl:~/domains/anderwijs.nl/htdocs/aas2/public

ssh anderwijsnl@ftp.anderwijs.nl << EOF

cd ~/domains/anderwijs.nl/htdocs/aas2
${PHP_EXECUTABLE} composer.phar self-update
${PHP_EXECUTABLE} composer.phar install
${PHP_EXECUTABLE} composer dump-autoload
${PHP_EXECUTABLE} artisan clear-compiled
${PHP_EXECUTABLE} artisan optimize:clear
${PHP_EXECUTABLE} artisan migrate --force
${PHP_EXECUTABLE} artisan optimize
${PHP_EXECUTABLE} artisan up

EOF
