FROM php:8.4-alpine

RUN apk update && apk add --no-cache \
    bash \
    git \
    nano \
    htop \
    fish \
    libpq-dev \
    postgresql-client \
    zip \
    wget \
    supervisor \
    && rm -rf /var/cache/apk/*

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    pcntl \
    swoole \
    opcache \
    @composer

COPY --chown=laravel:laravel php.ini /usr/local/etc/php/conf.d/swoole.ini

RUN addgroup -g 1000 -S laravel && \
    adduser -u 1000 -S laravel -G laravel && \
    mkdir -p /var/www /var/log/supervisor && \
    chown -R laravel:laravel /var/www /var/log/supervisor

WORKDIR /var/www

COPY --chown=laravel:laravel composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY --chown=laravel:laravel . .

# Настройка прав доступа
RUN chown -R laravel:laravel /var/www && \
    chmod -R 755 /var/www/storage /var/www/bootstrap/cache

COPY --chown=laravel:laravel ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost:8000/health || exit 1

USER laravel

EXPOSE 8000 9001

# graceful shutdown script
COPY --chown=laravel:laravel docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
