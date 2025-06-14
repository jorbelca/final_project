# Usa una imagen base oficial con PHP, Apache y extensiones necesarias
FROM php:8.2-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev \
    libpq-dev nano \
    libapache2-mod-security2 \
    fail2ban \
    certbot python3-certbot-apache \
    openssl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

    # Habilita los módulos de reescritura y SSL de Apache
RUN a2enmod rewrite ssl headers security2

    # Configura ModSecurity con reglas básicas pero en modo menos restrictivo para desarrollo
RUN cp /etc/modsecurity/modsecurity.conf-recommended /etc/modsecurity/modsecurity.conf \
      && sed -i 's/SecRuleEngine DetectionOnly/SecRuleEngine On/' /etc/modsecurity/modsecurity.conf \
      && sed -i 's/SecAuditLog \/var\/log\/modsec_audit.log/SecAuditLog \/var\/log\/apache2\/modsec_audit.log/' /etc/modsecurity/modsecurity.conf


# Regla Fail2Ban: filtro para ModSecurity
    RUN mkdir -p /etc/fail2ban/filter.d/ && \
        echo '[Definition]' > /etc/fail2ban/filter.d/modsecurity.conf && \
        echo 'failregex = .*\[client <HOST>\].*ModSecurity:.*\[msg ".*"\]' >> /etc/fail2ban/filter.d/modsecurity.conf && \
        echo 'ignoreregex =' >> /etc/fail2ban/filter.d/modsecurity.conf

    # Configura Fail2Ban para ModSecurity
    RUN echo '[modsecurity]' > /etc/fail2ban/jail.d/modsecurity.conf && \
    echo 'enabled = true' >> /etc/fail2ban/jail.d/modsecurity.conf && \
    echo 'filter = modsecurity' >> /etc/fail2ban/jail.d/modsecurity.conf && \
    echo 'logpath = /var/log/apache2/modsec_audit.log' >> /etc/fail2ban/jail.d/modsecurity.conf && \
    echo 'maxretry = 3' >> /etc/fail2ban/jail.d/modsecurity.conf && \
    echo 'bantime = 48*3600' >> /etc/fail2ban/jail.d/modsecurity.conf && \
    echo 'findtime = 3600' >> /etc/fail2ban/jail.d/modsecurity.conf && \
    echo 'action = iptables-allports' >> /etc/fail2ban/jail.d/modsecurity.conf

#Crear jail.local
RUN echo "[DEFAULT]" > /etc/fail2ban/jail.local && \
    echo "ignoreip = 127.0.0.1/8" >> /etc/fail2ban/jail.local && \
    echo "bantime = 48*3600" >> /etc/fail2ban/jail.local && \
    echo "findtime = 600" >> /etc/fail2ban/jail.local && \
    echo "maxretry = 3" >> /etc/fail2ban/jail.local && \
    echo "backend = auto" >> /etc/fail2ban/jail.local && \
    echo "[sshd]" >> /etc/fail2ban/jail.local && \
    echo "enabled = false" >> /etc/fail2ban/jail.local && \
    echo "[modsecurity]" >> /etc/fail2ban/jail.local && \
    echo "enabled = true" >> /etc/fail2ban/jail.local && \
    echo "filter = modsecurity" >> /etc/fail2ban/jail.local && \
    echo "logpath = /var/log/apache2/modsec_audit.log" >> /etc/fail2ban/jail.local && \
    echo "action = iptables-allports" >> /etc/fail2ban/jail.local


    # Descarga y configura OWASP Core Rule Set (versión estable)
RUN curl -L -o /tmp/crs.tar.gz https://github.com/coreruleset/coreruleset/archive/v3.3.2.tar.gz && \
    mkdir -p /etc/apache2/modsecurity-crs && \
    tar -xzf /tmp/crs.tar.gz -C /etc/apache2/modsecurity-crs && \
    mv /etc/apache2/modsecurity-crs/coreruleset-3.3.2 /etc/apache2/modsecurity-crs/crs && \
    cp /etc/apache2/modsecurity-crs/crs/crs-setup.conf.example /etc/apache2/modsecurity-crs/crs/crs-setup.conf && \
        # Configura un nivel de paranoia más bajo (1 )
    sed -i 's/setvar:tx.paranoia_level=1/setvar:tx.paranoia_level=1/' /etc/apache2/modsecurity-crs/crs/crs-setup.conf

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
    # Configura la inclusión de reglas
    RUN echo 'Include /etc/modsecurity/modsecurity.conf' > /etc/apache2/mods-enabled/security2.conf && \
        echo 'Include /etc/apache2/modsecurity-crs/rules-before/*.conf' >> /etc/apache2/mods-enabled/security2.conf && \
        echo 'Include /etc/apache2/modsecurity-crs/crs/crs-setup.conf' >> /etc/apache2/mods-enabled/security2.conf && \
        echo 'Include /etc/apache2/modsecurity-crs/crs/rules/*.conf' >> /etc/apache2/mods-enabled/security2.conf
    # Copia el código de la aplicación al contenedor
COPY . /var/www

# Configura el directorio html para que apunte a public
RUN rm -rf /var/www/html && ln -s /var/www/public /var/www/html

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Instala Composer (si no está instalado)
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Composer install y Laravel setup
RUN composer install --no-dev --optimize-autoloader && \
    php artisan key:generate && \
    php artisan route:cache && \
    mkdir -p /var/www/resources/views/vendor/laravelpwa && \
    php artisan view:cache && \
    php artisan storage:link

# Configura el archivo de configuración de Apache
ENV APACHE_DOCUMENT_ROOT=/var/www

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copia el archivo de configuración SSL de Apache
COPY ./default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

# Habilita el sitio SSL
RUN a2ensite default-ssl

# Crea un certificado autofirmado (solo para pruebas)
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt \
    -subj "/C=ES/ST=State/L=Locality/O=Organization/OU=Unit/CN=localhost"


# # Configura https
# # Crea el archivo de configuración del sitio SSL
# RUN echo '<IfModule mod_ssl.c>' > /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '  <VirtualHost *:443>' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    ServerName budgetapp.software' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    ServerAdmin webmaster@localhost' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    DocumentRoot /var/www/public' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    SSLEngine on' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    SSLCertificateFile /etc/ssl/sectigo-certs/2412215709repl_1.crt' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    SSLCertificateKeyFile /etc/ssl/budgetapp.key' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    SSLCertificateChainFile /etc/ssl/sectigo-certs/chain.crt' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    <Directory /var/www/public>' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '      Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '      AllowOverride All' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '      Require all granted' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    </Directory>' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    ErrorLog ${APACHE_LOG_DIR}/budgetapp-error.log' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '    CustomLog ${APACHE_LOG_DIR}/budgetapp-access.log combined' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '  </VirtualHost>' >> /etc/apache2/sites-available/budgetapp-ssl.conf && \
#     echo '</IfModule>' >> /etc/apache2/sites-available/budgetapp-ssl.conf

#  # Redirecciona HTTP a HTTPS
# RUN echo '<VirtualHost *:80>' > /etc/apache2/sites-available/budgetapp.conf && \
#     echo '    ServerName budgetapp.software' >> /etc/apache2/sites-available/budgetapp.conf && \
#     echo '    Redirect permanent / https://budgetapp.software/' >> /etc/apache2/sites-available/budgetapp.conf && \
#     echo '</VirtualHost>' >> /etc/apache2/sites-available/budgetapp.conf

# # Habilita el sitio SSL personalizado
# RUN a2ensite budgetapp-ssl budgetapp.conf


# Exponer los puertos 80 y 443
EXPOSE 80 443

# Crea un script de inicio para iniciar Apache y Fail2Ban
COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]


