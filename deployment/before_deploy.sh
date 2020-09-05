#!/bin/bash

ssh anderwijsnl@ftp.anderwijs.nl << EOF

cd ~/domains/anderwijs.nl/htdocs/aas2
php72 artisan down

EOF