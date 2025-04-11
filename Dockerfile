# Usa una imagen base oficial con PHP, Apache y extensiones necesarias
FROM php:8.2-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev \
    libpq-dev nano \
    libapache2-mod-security2 \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip \
    && apt-get install -y certbot python3-certbot-apache \
    && apt-get install -y openssl
    # Habilita los módulos de reescritura y SSL de Apache
    RUN a2enmod rewrite ssl headers security2

    # Configura ModSecurity con reglas básicas pero en modo menos restrictivo para desarrollo
    RUN cp /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf \
        && sed -i 's/SecRuleEngine DetectionOnly/SecRuleEngine On/' /etc/modsecurity/modsecurity.conf \
        && sed -i 's/SecAuditLog \/var\/log\/modsec_audit.log/SecAuditLog \/dev\/null/' /etc/modsecurity/modsecurity.conf

    # Descarga y configura OWASP Core Rule Set (versión estable)
    RUN cd /tmp && \
        curl -L -O https://github.com/coreruleset/coreruleset/archive/v3.3.2.tar.gz && \
        tar -xzvf v3.3.2.tar.gz && \
        mkdir -p /etc/apache2/modsecurity-crs && \
        mv coreruleset-3.3.2 /etc/apache2/modsecurity-crs/crs && \
        cd /etc/apache2/modsecurity-crs/crs && \
        cp crs-setup.conf.example crs-setup.conf && \
        # Configura un nivel de paranoia más bajo (1 )
        sed -i 's/setvar:tx.paranoia_level=1/setvar:tx.paranoia_level=1/' crs-setup.conf

    # Crea reglas de exclusión para la ruta /subscription
    RUN mkdir -p /etc/apache2/modsecurity-crs/rules-before && \
        echo 'SecRule REQUEST_URI "@beginsWith /subscription" "id:1000,phase:1,pass,nolog,ctl:ruleEngine=Off"' > /etc/apache2/modsecurity-crs/rules-before/whitelist.conf
        # Añade más exclusiones para rutas de edit y delete
        RUN echo 'SecRule REQUEST_URI "@contains /put" "id:1001,phase:1,pass,nolog,ctl:ruleEngine=Off"' >> /etc/apache2/modsecurity-crs/rules-before/whitelist.conf && \
            echo 'SecRule REQUEST_URI "@contains /delete" "id:1002,phase:1,pass,nolog,ctl:ruleEngine=Off"' >> /etc/apache2/modsecurity-crs/rules-before/whitelist.conf && \
            echo 'SecRule REQUEST_METHOD "@streq DELETE" "id:1003,phase:1,pass,nolog,ctl:ruleEngine=Off"' >> /etc/apache2/modsecurity-crs/rules-before/whitelist.conf && \
            echo 'SecRule REQUEST_URI "@rx .*/(update|destroy)($|/|\\?)" "id:1004,phase:1,pass,nolog,ctl:ruleEngine=Off"' >> /etc/apache2/modsecurity-crs/rules-before/whitelist.conf && \
            echo 'SecRule REQUEST_URI "@beginsWith /costs" "id:1005,phase:1,pass,nolog,ctl:ruleEngine=Off"' >> /etc/apache2/modsecurity-crs/rules-before/whitelist.conf && \
            echo 'SecRule REQUEST_URI "@beginsWith /budgets" "id:1006,phase:1,pass,nolog,ctl:ruleEngine=Off"' >> /etc/apache2/modsecurity-crs/rules-before/whitelist.conf && \
            echo 'SecRule REQUEST_URI "@beginsWith /clients" "id:1007,phase:1,pass,nolog,ctl:ruleEngine=Off"' >> /etc/apache2/modsecurity-crs/rules-before/whitelist.conf && \
            echo 'SecRule REQUEST_URI "@beginsWith /support" "id:1008,phase:1,pass,nolog,ctl:ruleEngine=Off"' >> /etc/apache2/modsecurity-crs/rules-before/whitelist.conf
    # Configura la inclusión de reglas de forma correcta
    RUN echo 'Include /etc/modsecurity/modsecurity.conf' > /etc/apache2/mods-enabled/security2.conf && \
        echo 'Include /etc/apache2/modsecurity-crs/rules-before/*.conf' >> /etc/apache2/mods-enabled/security2.conf && \
        echo 'Include /etc/apache2/modsecurity-crs/crs/crs-setup.conf' >> /etc/apache2/mods-enabled/security2.conf && \
        echo 'Include /etc/apache2/modsecurity-crs/crs/rules/*.conf' >> /etc/apache2/mods-enabled/security2.conf
    # Copia el código de la aplicación al contenedor
COPY . /var/www

# Configura el directorio html para que apunte a public
RUN rm -rf /var/www/html && ln -s /var/www/public /var/www/html

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Instala Composer (si no está instalado)
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer
# Instala dependencias de Composer
USER www-data
RUN composer install --no-dev --optimize-autoloader
USER root

# Configura el archivo de configuración de Apache
ENV APACHE_DOCUMENT_ROOT=/var/www

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copia el archivo de configuración SSL de Apache
COPY ./default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

# Habilita el sitio SSL
RUN a2ensite default-ssl

# Crea un certificado autofirmado (solo para pruebas)
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt \
    -subj "/C=ES/ST=State/L=Locality/O=Organization/OU=Unit/CN=localhost"

RUN php artisan route:cache && php artisan view:cache

# Exponer los puertos 80 y 443
EXPOSE 80 443

# Comando por defecto para iniciar el contenedor
CMD ["apache2-foreground"]


