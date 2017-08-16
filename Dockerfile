FROM debian:9.1

MAINTAINER Vitaliy Zhuk <v.zhukv@fivelab.org>

ENV LANGUAGE en_US.UTF-8
ENV LANG en_US.UTF-8
ENV LC_ALL en_US.UTF-8

# Install common packages
RUN \
    apt-get clean && \
    apt-get update && \
    apt-get install -y locales && \
    apt-get install -y zip unzip

# Solve problem with locales
RUN \
    locale-gen en_US.UTF-8 && \
    dpkg-reconfigure locales && \
    echo "LANG=en_US.UTF-8" > /etc/default/locale && \
    echo "LANG=en_US.UTF-8" > /etc/environment

# Install cURL with SSL and HTTP 2.0
RUN apt-get -y install build-essential nghttp2 libnghttp2-dev libssl-dev wget && \
    mkdir -p /tmp/install-curl && \
    cd /tmp/install-curl && \
    wget https://curl.haxx.se/download/curl-7.54.0.tar.bz2 && \
    tar -xvjf curl-7.54.0.tar.bz2 && \
    cd curl-7.54.0 && \
    ./configure --with-nghttp2 --with-ssl --prefix=/usr/local && \
    make && \
    make install && \
    ldconfig

# Add repo for install PHP 7.1
RUN apt-get update && \
    apt-get -y install apt-transport-https lsb-release ca-certificates && \
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list && \
    apt-get update

# Install PHP and dependenc packages
RUN apt-get -y install php7.1 php7.1-xml php7.1-mbstring php7.1-curl php7.1-gmp php7.1-xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
