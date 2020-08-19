#!/bin/bash

ssh anderwijsnl@ftp.anderwijs.nl << EOF

cd ~/domains/anderwijs.nl/htdocs/aas2
git pull origin master

EOF

rsync -a --include-from=deployment/file-list.txt anderwijsnl@ftp.anderwijs.nl:~/domains/anderwijs.nl/htdocs/public 