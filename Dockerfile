FROM ubuntu:16.04

MAINTAINER Chris Jenkinson

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_NZ.UTF-8

ENV LANG en_NZ.UTF-8
ENV LANGUAGE en_NZ:en
ENV LC_ALL en_NZ.UTF-8

RUN apt-get update \
    && apt-get install -y zip unzip git software-properties-common

RUN add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php7.1-cli php7.1-mbstring php7.1-xml php7.1-xdebug

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN apt-get remove -y --purge software-properties-common \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY docker/xdebug.ini /etc/php/7.1/mods-available/xdebug.ini
