FROM debian:9.1

MAINTAINER Vitaliy Zhuk <v.zhukv@fivelab.org>

ENV LC_ALL en_US.UTF-8
ENV LANGUAGE en_US:en

# Install common packages and fix locales
RUN \
    apt-get update && \
    apt-get -y --no-install-recommends install locales apt-utils && \
    echo "en_US.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen en_US.UTF-8 && \
    /usr/sbin/update-locale LANG=en_US.UTF-8

# Install cURL with SSL and HTTP 2.0
RUN \
    apt-get -y --no-install-recommends install \
        build-essential \
        nghttp2 \
        libnghttp2-dev \
        libssl-dev \
        ca-certificates \
        zip \
        unzip \
        wget \
        apt-transport-https \
        lsb-release && \

    mkdir -p /tmp/install-curl && \
    cd /tmp/install-curl && \
    wget https://curl.haxx.se/download/curl-7.54.0.tar.bz2 && \
    tar -xvjf curl-7.54.0.tar.bz2 && \
    cd curl-7.54.0 && \
    ./configure --with-nghttp2 --with-ssl --prefix=/usr/local && \
    make && \
    make install && \
    ldconfig

RUN \
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list && \
    apt-get update

# Install PHP and depends packages
RUN \
    apt-get -y --no-install-recommends install \
        php7.1 \
        php7.1-xml \
        php7.1-mbstring \
        php7.1-curl \
        php7.1-gmp \
        php7.1-xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
