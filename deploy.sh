#!/bin/bash
set -e


# Instalar rsync si no está instalado
which rsync &>/dev/null || { echo "Instalando rsync"; apt-get -qq update && apt-get -qq install rsync; }

# Configuración de SSH para evitar confirmaciones interactivas
echo "Configurando SSH..."
mkdir -p ~/.ssh
echo -e "Host *\n\tStrictHostKeyChecking no\n\n" > ~/.ssh/config

# Desplegar con rsync
echo "Iniciando despliegue con rsync..."
rsync -avz --delete --exclude='.git/' \
  --exclude='.env' \
  --exclude='node_modules/' \
  --exclude='vendor/' \
  --exclude='TEST_API.http' \
  --exclude='uml.puml' \
  --exclude='costes.csv' \
  --exclude='costes.pdf' \
  --exclude='1a entrega.docx' \
  --exclude='costes_minimos.csv' \
  --exclude='costes_minimos.pdf' \
  --exclude='Mockups.pdf' \
  --exclude='esquema_reduit.pdf' \
  --exclude='.env.cypress' \
  --exclude='.env.travis' \
  --exclude='.env.example' \
  --exclude='mkt_images.html' \
  --exclude='README.md' \
  -e "ssh -p ${SSH_PORT}" \
  ./ ${DEPLOY_SERVER}:${DEPLOY_PATH}

# Ejecutar comandos en el contenedor después de la sincronización
echo "Ejecutando comandos post-deploy en el contenedor Docker..."
ssh -p ${SSH_PORT} ${DEPLOY_SERVER} "cd ${DEPLOY_PATH} && \
  docker exec ${CONTAINER_NAME} bash -c \"cd /var/www && \
  composer install --no-dev --optimize-autoloader && \
  chown -R www-data:www-data storage bootstrap/cache && \
  chmod -R 775 storage bootstrap/cache && \
  service fail2ban restart\""

echo "Despliegue completado con éxito."
