#!/usr/bin/env bash

if [[ ! -f /db-volume/ib_buffer_pool ]]; then
    chmod -R 0777 /root/db-starter;
    cp -R /root/db-starter/* /db-volume/;
fi

chmod -R 0777 /db-volume;

chmod -R 0777 /app/public/uploads;

while true; do
    chmod -R 0777 /cache-volume;
    rsync -av /cache-volume/ /app/storage --delete;
    sleep 2;
done
