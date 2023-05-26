FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
      supervisor

RUN docker-php-ext-install pdo pdo_mysql

RUN mkdir -p "/etc/supervisor/logs"

COPY /_docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

CMD ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisor/supervisord.conf"]
