#!/bin/bash

init_service() {
    docker-compose exec $1 php init;
    docker-compose exec $1 composer update;
    docker-compose exec $1 composer install;
}

# there is no need to run init_service for other hotels as they share the same code
init_service hotel_krakow

init_service hotel_worker
init_service hotel_agent
