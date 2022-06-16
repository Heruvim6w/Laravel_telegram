FROM php:7.4.23-fpm-alpine3.14

RUN apk add --no-cache \
    npm \
    curl \
    bash \
    nano \
    $PHPIZE_DEPS \
    # libzip \
    libzip-dev \
    # libxml2 \
    libxml2-dev \
    icu \
    icu-dev \
    mysql-client
    
RUN apk add libpng-dev

#RUN mkdir /usr/lib/node_modules/pngquant-bin/vendor/pngquant
#RUN npm install -g pngquant-bin --allow-root  --unsafe-perm=true

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN docker-php-ext-install \
    gd \
    sockets \
    pdo \
    pdo_mysql \
    intl \
    zip \
    soap \
    bcmath \
    exif

#RUN pecl install xhprof && \
#    docker-php-ext-enable xhprof && \
#    docker-php-ext-install opcache

#RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#    echo "xdebug.profiler_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
#    echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
# после composer install можно это раскомментрровать, иначе контейнер не запустится
#RUN echo "xhprof.output_dir=/php/xhprof/data" >> /usr/local/etc/php/conf.d/docker-php-ext-xhprof.ini && \
#    echo $'\
#opcache.enable=1 \n\
#opcache.enable_cli=0 \n\
#opcache.memory_consumption=128 \n\
#opcache.interned_strings_buffer=8 \n\
#opcache.max_accelerated_files=4000 \n\
#opcache.revalidate_freq=60 \n\
#opcache.fast_shutdown=1 \n\
#opcache.preload=/var/www/html/my-project/preloader.php \n\
#opcache.preload_user=root \n\
#' > /usr/local/etc/php/conf.d/opcache.ini

RUN npm install -g npm
# RUN mkdir -p /home/basic
# RUN chown -R 1000:1000 /home/basic
#RUN mkdir -p /var/www/
#WORKDIR /var/www/html/my-project/public

