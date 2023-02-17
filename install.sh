#!/usr/bin/env bash

cp .env.example .env && docker-compose up -d laravel.test && docker-compose exec laravel.test composer install && docker-compose down && ./vendor/bin/sail up -d && ./vendor/bin/sail artisan migrate:refresh --seed --force && ./vendor/bin/sail exec frontend cp .env-example .env && ./vendor/bin/sail exec frontend npm install && ./vendor/bin/sail exec frontend npm install && ./vendor/bin/sail artisan search:reindex && ./vendor/bin/sail exec frontend npm run serve
