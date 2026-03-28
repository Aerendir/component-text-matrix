FROM php:8.4-cli AS component-text-matrix-84

# PHP extensions installer
# https://github.com/mlocati/docker-php-extension-installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        git \
        unzip && \
    rm -rf /var/lib/apt/lists/*

#RUN mv "$PHP_INI_DIR/php.ini" "$PHP_INI_DIR/php.ini-production";
#RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini";

COPY --link .docker/php/8.4/conf.d/lib.dev.ini $PHP_INI_DIR/conf.d/

RUN set -eux; \
    install-php-extensions \
        ast \
        xdebug \
        zip \
    ;

RUN rm $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini
COPY --link .docker/php/8.4/conf.d/docker-php-ext-xdebug.ini $PHP_INI_DIR/conf.d/

# INstall composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /project

COPY . .

COPY --link .docker/php/8.4/docker-php-entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

ENTRYPOINT ["docker-php-entrypoint"]
# CMD ["/bin/sh"]
