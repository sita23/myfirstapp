FROM alpine:latest

RUN apk update && apk upgrade

RUN apk --update add make autoconf g++ gcc libc-dev curl ca-certificates \
	&& apk --update add nginx git runit tzdata bash php7-dev file nginx-mod-http-headers-more

COPY tmp/cphalcon-3.4.4 /tmp/cphalcon-3.4.4

WORKDIR /tmp

#RUN git clone https://github.com/phalcon/cphalcon
RUN cd cphalcon-3.4.4/build/ \
	&& ./install \
	&& rm -rf /tmp/* \
	&& echo "extension=phalcon.so" > /etc/php7/conf.d/phalcon.ini

RUN apk --update add nginx runit tzdata nginx-mod-http-headers-more

RUN apk add --update \
	php7 \
	php7-fpm \
	php7-intl \
	php7-openssl \
	php7-dba \
	php7-sqlite3 \
	php7-pear \
	php7-tokenizer \
	php7-phpdbg \
	php7-litespeed \
	php7-gmp \
	php7-pdo_mysql \
	php7-pcntl \
	php7-common \
	php7-xsl \
	php7-imagick \
	php7-mysqlnd \
	php7-enchant \
	php7-pspell \
	php7-redis \
	php7-snmp \
	php7-doc \
	php7-fileinfo \
	php7-mbstring \
	php7-pear-mail_mime \
	php7-xmlrpc \
	php7-embed \
	php7-xmlreader \
	php7-pear-mdb2_driver_mysql \
	php7-pdo_sqlite \
	php7-pear-auth_sasl2 \
	php7-exif \
	php7-recode \
	php7-opcache \
	php7-ldap \
	php7-posix \
	php7-pear-net_socket \
	php7-session \
	php7-gd \
	php7-gettext \
	php7-mailparse \
	php7-json \
	php7-xml \
	php7-iconv \
	php7-sysvshm \
	php7-curl \
	php7-shmop \
	php7-odbc \
	php7-phar \
	php7-pdo_pgsql \
	php7-imap \
	php7-pear-mdb2_driver_pgsql \
	php7-pdo_dblib \
	php7-pgsql \
	php7-pdo_odbc \
	php7-xdebug \
	php7-zip \
	php7-ctype \
	php7-amqp \
	php7-mcrypt \
	php7-wddx \
	php7-pear-net_smtp \
	php7-bcmath \
	php7-calendar \
	php7-tidy \
	php7-dom \
	php7-sockets \
	php7-zmq \
	php7-memcached \
	php7-soap \
	php7-apcu \
	php7-sysvmsg \
	php7-zlib \
	php7-ftp \
	php7-sysvsem \
	php7-pear \
	php7-pdo \
	php7-pear-auth_sasl \
	php7-bz2 \
	php7-mysqli \
	php7-pear-net_smtp-doc \
	php7-simplexml \
	php7-xmlwriter

# Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

#RUN mkdir -p /run/nginx
#RUN mkdir -p /run/php

VOLUME /var/www/html

WORKDIR /var/www/html

COPY default.conf /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY service /etc/service
COPY php.ini /etc/php7/php.ini

RUN chmod +x -R /etc/service

RUN rm -rf /var/cache/*

EXPOSE 80

ENTRYPOINT runsvdir -P /etc/service