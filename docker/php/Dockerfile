FROM phpdockerio/php72-fpm:latest
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get install -y software-properties-common

# Install selected extensions and other stuff
RUN apt-add-repository ppa:ondrej/php -y && apt-get update
RUN apt-get -y --no-install-recommends install php7.2-mysql php-redis php-xdebug php7.2-bcmath php7.2-intl php7.2-mbstring php-yaml php7.2-xml php \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# For yii2
RUN composer global require "fxp/composer-asset-plugin:*"
