#!/bin/bash
php ./init;
composer install;
./yii migrate/up --interactive=0
./yii reservation-handler