FROM python:3.7.0-alpine
RUN apk update && apk add mysql-dev build-base mysql-client
RUN pip install --upgrade pip setuptools pipenv django mysqlclient
ENV PROJECT_DIRECTORY=/srv/app
RUN mkdir $PROJECT_DIRECTORY
WORKDIR $PROJECT_DIRECTORY
COPY . .
COPY docker-entrypoint.sh /
RUN pipenv install --skip-lock
ENTRYPOINT ["sh", "/docker-entrypoint.sh"]
EXPOSE 8080
CMD ["pipenv", "run", "./manage.py", "runserver", "0:8080"]
