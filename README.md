# Shopify Reviews Manager

## Introduction

COPY config/autoload/local.php.dist to config/autoload/local.php

### Build Docker containers
docker-compose up --build

### Start Docker containers to coding
docker-compose up

### INSTALL PHP DEPENDENCIES
docker-compose exec --user www-data app composer install

### ENABLE DEVELOPMENT ENVIRONMENT
docker-compose exec --user www-data app php vendor/bin/zf-development-mode enable

### LOAD PRODUCTS
docker-compose exec --user www-data app php public/index.php sync load-apps
### RUN CRON
docker-compose exec --user www-data app php public/index.php sync run-cron