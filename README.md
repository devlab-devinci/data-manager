# data-manager - DEVLAB

## Configure Server
- Change .env/.test to local test
- Change .env to connect with your Database

## Installation
- git clone 
- composer install
- curl install
- version de php >= 7.1

## Run this project
- php bin/console server:run

## Création et update de la BDD
- php bin/console doctrine:database:create
- php bin/console doctrine:schema:update --force

## Affichage du site en local
http://127.0.0.1:8000/