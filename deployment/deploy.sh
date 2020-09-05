#!/bin/bash

ssh anderwijsnl@ftp.anderwijs.nl << EOF

cd ~/domains/anderwijs.nl/htdocs/aas2
git pull origin master

EOF

rsync -r $TRAVIS_BUILD_DIR/public/js \
         $TRAVIS_BUILD_DIR/public/css \
         $TRAVIS_BUILD_DIR/public/fonts \
    anderwijsnl@ftp.anderwijs.nl:~/domains/anderwijs.nl/htdocs/aas2/public