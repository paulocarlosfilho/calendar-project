# Usa uma imagem base que já tem Apache e PHP 8.0 instalados
FROM php:8.0-apache

# Define o diretório de trabalho dentro do contêiner
WORKDIR /var/www/html

# 1. Instala extensões PHP necessárias para MySQLi
# 'docker-php-ext-install' é o comando oficial para isso.
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli

# 2. Remove o index.html padrão do Apache
RUN rm /var/www/html/index.html

# 3. Copia TODO o código do seu projeto (calendar-project) para o diretório raiz do Apache
# O '.' representa o diretório atual do seu Dockerfile
COPY . /var/www/html/

# O Apache é executado automaticamente pelo comando padrão desta imagem