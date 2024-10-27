# Dockerfile для Laravel
FROM php:8.3-fpm

# Установка необходимых расширений
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libonig-dev \
    zip \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    sudo

RUN useradd -ms /bin/bash myuser && \
    echo "myuser:password" | chpasswd && \
    adduser myuser sudo
# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка зависимостей
WORKDIR /var/www/app
COPY ./backend .

RUN composer install

# Установка прав на папки

EXPOSE 9000
CMD ["php-fpm"]
