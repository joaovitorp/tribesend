ARG PHP_VERSION
FROM php:8.4-fpm-bullseye

## Diretório da aplicação
ARG APP_DIR=/var/www/app

## Versão da Lib do Redis para PHP
ARG REDIS_LIB_VERSION=6.1.0

### apt-utils é um extensão de recursos do gerenciador de pacotes APT
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    apt-utils \
    supervisor

# dependências recomendadas de desenvolvido para ambiente linux
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libpng-dev \
    libpq-dev \
    libxml2-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql pgsql session

# habilita instalação do Redis - usando a versão mais recente compatível
RUN pecl channel-update pecl.php.net \
    && (pecl install redis-${REDIS_LIB_VERSION} || pecl install redis) \
    && docker-php-ext-enable redis

RUN docker-php-ext-install zip iconv simplexml pcntl gd fileinfo

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Node.js 20.x installation
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Verify Node.js and npm installation
RUN node --version && npm --version

COPY ./docker/api/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
# COPY ./docker/supervisord/conf /etc/supervisord.d/
### Supervisor permite monitorar e controlar vários processos (LINUX)
### Bastante utilizado para manter processos em Daemon, ou seja, executando em segundo plano

COPY ./docker/api/php/extra-php.ini "$PHP_INI_DIR/99_extra.ini"
COPY ./docker/api/php/extra-php-fpm.conf /etc/php8/php-fpm.d/www.conf

WORKDIR $APP_DIR
RUN cd $APP_DIR
RUN chown www-data:www-data $APP_DIR

COPY --chown=www-data:www-data . .
RUN rm -rf vendor
RUN composer install --no-interaction

# Configurar permissões corretas para Laravel
RUN chown -R www-data:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache
RUN chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

# Copiar e configurar script de entrada
COPY ./docker/api/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN apt-get install nginx -y
RUN rm -rf /etc/nginx/sites-enabled/* && rm -rf /etc/nginx/sites-available/*
COPY ./docker/api/nginx/sites.conf /etc/nginx/sites-enabled/default.conf
COPY ./docker/api/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/api/nginx/error.html /var/www/html/error.html

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN apt update -y && apt install nano -y

# Instalar dependências node 
RUN npm install & npm run build

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
