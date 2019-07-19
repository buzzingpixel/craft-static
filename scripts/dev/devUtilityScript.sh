#!/usr/bin/env bash

if [[ ! -f /db-volume/ib_buffer_pool ]]; then
    chmod -R 0777 /root/db-starter;
    cp -R /root/db-starter/* /db-volume/;
fi

chmod -R 0777 /db-volume;

while true; do
    chmod -R 0777 /cache-volume;
    rsync -av /cache-volume/ /app/testenvironment/storage --delete;
    sleep 2;
done
