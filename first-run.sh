#!/bin/bash

docker-compose up -d hotel_krakow

init_service() {
    docker-compose exec $1 php init;
    docker-compose exec $1 composer install;
}

init_service hotel_krakow
init_service hotel_wroclaw
init_service hotel_gdansk

docker-compose up -d
