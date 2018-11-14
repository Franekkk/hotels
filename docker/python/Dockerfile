FROM python:3.7.0-alpine
RUN apk update && apk add mysql-dev build-base mysql-client
RUN pip install --upgrade pip setuptools pipenv

ENV PROJECT_DIRECTORY=/srv/app

RUN mkdir $PROJECT_DIRECTORY
WORKDIR $PROJECT_DIRECTORY

COPY . .

RUN pipenv install --skip-lock
#COPY ./docker-entrypoint.sh /
#
#ENTRYPOINT ["/docker-entrypoint.sh"]
EXPOSE 8080
CMD ["ls", "-la"]
CMD ["pipenv", "run", "./manage.py", "runserver", "0:8080"]
