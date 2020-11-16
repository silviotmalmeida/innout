#!/bin/bash

docker-compose up -d --remove-orphans

sleep 5

docker container exec innout chmod 777 /opt/lampp/htdocs/innout/app

docker container exec innout /opt/lampp/lampp start