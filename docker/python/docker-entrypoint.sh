#!/bin/bash
set -e
pipenv install
pipenv run /srv/app/manage.py runserver 0:8080

exec "$@"
