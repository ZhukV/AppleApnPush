FROM php:7.2-cli

MAINTAINER Vitaliy Zhuk <v.zhukv@fivelab.org>

RUN \
	apt-get update && \
	apt-get install -y --no-install-recommends \
		libgmp-dev \
		zip unzip \
		git && \
	docker-php-ext-install gmp

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
