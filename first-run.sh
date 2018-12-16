#!/bin/bash

init_service() {
    docker-compose exec $1 php init;
    docker-compose exec $1 composer update;
    docker-compose exec $1 composer install;
}

migrations() {
    docker-compose exec $1 php yii migrate/up --interactive=0
}

# there is no need to run init_service for other hotels as they share the same code
init_service hotel_krakow
#docker-compose exec hotel_krakow php yii_test migrate/up --interactive=0

init_service worker
init_service agent

migrations hotel_krakow
migrations hotel_wroclaw
migrations hotel_gdansk
