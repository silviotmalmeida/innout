#!/bin/bash

docker-compose up -d --remove-orphans

sleep 10

docker container exec ubuntu-xampp /opt/lampp/lampp start