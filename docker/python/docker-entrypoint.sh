#!/bin/bash
set -e
pipenv install

exec "$@"
