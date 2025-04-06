FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    apt-utils nano wget dialog vim \
    build-essential \
    curl \
    zip \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pcntl exif bcmath ctype \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Postgre PDO
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install NPM
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -
    RUN apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # Habilitar mod_rewrite de Apache para Laravel
# RUN a2enmod rewrite

# Copiar los archivos de tu aplicación al contenedor
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto
EXPOSE 80
