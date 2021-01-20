FROM php: 7.2-fpm-alphine

RUN docker-php-ext-install pdo pdo_mysql