FROM php:8.3-apache

RUN apt-get -y update \
    && apt-get -y install git zlib1g-dev libzip-dev unzip \
    && a2enmod rewrite \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && pecl install xdebug-3.3.1 \
    && docker-php-ext-enable xdebug
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN groupadd -r app -g 1000 && useradd -u 1000 -r -g app -m -d /app -s /sbin/nologin -c "App user" app && \
    chmod 755 /var/www/html

USER app


USER root

COPY /conf/000-default.conf /etc/apache2/sites-enabled/000-default.conf

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

WORKDIR /var/www