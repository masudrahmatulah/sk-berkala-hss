FROM php:8.2-apache

# Install dependencies & ekstensi MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Install ekstensi PHP yang dibutuhkan
RUN docker-php-ext-install pdo pdo_mysql

# Aktifkan modul Apache rewrite (opsional, untuk framework nanti)
RUN a2enmod rewrite

# Set timezone (opsional)
ENV TZ=Asia/Jakarta
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Bersihkan cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*