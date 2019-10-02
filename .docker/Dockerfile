FROM ubuntu:18.04
MAINTAINER Bart Rozycki "bartlomiej.rozycki@impactsoft.pl"

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get -y install net-tools nginx software-properties-common curl supervisor
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get -y install php7.3-fpm php7.3-mbstring php7.3-xml php7.3-curl php7.3-mysql git unzip

COPY ./nginx.conf /etc/nginx/sites-available/default
COPY ./supervisord.conf /etc/supervisor/conf.d/nginx-php-fpm.conf

RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
	ln -sf /dev/stderr /var/log/nginx/error.log

RUN mkdir /run/php && chown www-data /run/php
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"  && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

RUN apt-get update
RUN apt-get install -y php7.3-dev php-pear && \
    pecl install xdebug && \
    echo "zend_extension=/usr/lib/php/20180731/xdebug.so" > /etc/php/7.3/fpm/conf.d/xdebug.ini && \
    echo "xdebug.default_enable=1" >> /etc/php/7.3/fpm/conf.d/xdebug.ini && \
    echo "xdebug.remote_enable=1" >> /etc/php/7.3/fpm/conf.d/xdebug.ini && \
    echo "xdebug.remote_autostart=1" >> /etc/php/7.3/fpm/conf.d/xdebug.ini && \
    echo "xdebug.remote_connect_back=0" >> /etc/php/7.3/fpm/conf.d/xdebug.ini && \
    echo "xdebug.remote_handler=dbgp" >> /etc/php/7.3/fpm/conf.d/xdebug.ini && \
    echo "xdebug.remote_port=9000" >> /etc/php/7.3/fpm/conf.d/xdebug.ini && \
    echo "xdebug.remote_host=docker.for.mac.host.internal" >> /etc/php/7.3/fpm/conf.d/xdebug.ini

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

ENTRYPOINT sh /root/.docker/start.sh

WORKDIR /application

EXPOSE 80
EXPOSE 443

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/nginx-php-fpm.conf"]

