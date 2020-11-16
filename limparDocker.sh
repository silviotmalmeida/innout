#!/bin/bash

LISTA_CONTAINERS=$(docker ps -a -q)
LISTA_IMAGENS=$(docker images -a -q)

docker stop $LISTA_CONTAINERS

docker rm $LISTA_CONTAINERS

docker rmi $LISTA_IMAGENS -f