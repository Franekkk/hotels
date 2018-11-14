import os

from django.core.management import BaseCommand
from django.db import connection
from subprocess import call


class Command(BaseCommand):

    def handle(self, *args, **options):
        call('mysql -h $DATABASE_HOST -u root -p$DATABASE_PASSWORD -e "CREATE DATABASE $DATABASE_NAME"'.split(' '))
