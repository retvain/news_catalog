## Установка

- cp .emv-example .env (скопировать содержимое env_example в новый .env файл)
- composer install
- ./vendor/bin/sail up -d
- ./vendor/bin/sail artisan migrate:refresh --seed --force
- ./vendor/bin/sail artisan search:reindex (для обновления индексов поиска)


./vendor/bin/sail artisan optimize в случае проблем


Настройка xDebug - https://www.youtube.com/watch?v=iHad9TH9mOA
