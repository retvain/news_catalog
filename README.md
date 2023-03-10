## Простое backend приложение, реализующее функционал работы с новостями.

## Установка

автоматическая (linux \ WSL):
>- `sudo chmod +x install.sh`
>- `./install.sh`

или в ручном режиме

>бэк:
>- `cp .emv-example .env` (скопировать содержимое env_example в новый .env файл)
>- `docker-compose up -d laravel.test`
>- `docker-compose exec laravel.test composer install && docker-compose down`
>- `./vendor/bin/sail up -d`
>- `./vendor/bin/sail artisan migrate:refresh --seed --force`
>- `./vendor/bin/sail artisan search:reindex` (для обновления индексов поиска) `./vendor/bin/sail artisan search:reindex-daemon &` - в виде службы
>
>фронт:
>- `./vendor/bin/sail exec frontend cp .env-example .env`
>- `./vendor/bin/sail exec frontend npm install`
>- `./vendor/bin/sail exec frontend npm run serve`

- открыть приложение по адресу localhost:8080


## Тесты
- `./vendor/bin/sail php vendor/bin/phpunit`

## В случае проблем

`./vendor/bin/sail artisan optimize`

Настройка xDebug - https://www.youtube.com/watch?v=iHad9TH9mOA

## Описание

#### При реализации приложения использовался компонентный подход.
Данный подход предполагает реализацию каждой бизнес-сущности в виде отдельного компонента. Компоненты должны быть
изолированы друг от друга. Ни один компонент не должен вызывать функционал других компонентов. Все классы компонентов
должны быть спроектированы для работы только с данной бизнес-сущностью, компоненты не должны содержать функционал,
предназначенный для общего использования. Компоненты могут использовать стандартный функционал Laravel
и подключаемые зависимости. Классы внутри компонента могут наследоваться от классов определенных в app\BaseClasses и
app\Common.
Маршруты компонента содержатся в файле route.php, при установке компонента этот файл
нужно зарегистрировать в App/Providers/RouteServiceProvider
#### Controllers
В контроллер передается экземпляр запроса (Requests) и доп. параметры, контроллеры не содержат логику,
контроллеры возвращают экземпляр ресурса или коллекции (Resources).
###Requests
Запросы, служат в основном для валидации данных, можно также преобразовывать входные данные,
если в этом есть потребность.
#### Models
Реализация ActiveRecords, каждая модель представляет собой единичную таблицу, модели не должны содержать логику,
в моделях могут использоваться Scopes для сужения набора данных согласно определенным критериям. Модели должны
поддерживать функционал мягкого удаления через поле <table_name>_is_del, удобнее всего это реализовать
через глобальный scope. Модели должны поддерживать инкремент поля <table_name>_vers при любом изменении строки.
Модели могут содержать отношения.
#### Resources
Используются для формирования JSON ответа в ожидаемом формате.
Ответ может быть как набором данных, так и информацией о результате выполнения той или иной операции.
#### BusinessLayer
Слой, в котором содержится бизнес-логика компонента.

#### Используемые паттерны
- [Builder](https://habr.com/ru/company/otus/blog/552412/) упрощенная реализация в классах с чтением данных бизнес слоя.
- [Dependency Injection](https://habr.com/ru/post/166287/) пример реализации в контроллерах, в качестве демонстрации реализовано через интерфейсы, что позволит в случае необходимости подменять реализацию на лету. Пример - мок тесты.
- [Service Provider](https://ru.wikipedia.org/wiki/%D0%9B%D0%BE%D0%BA%D0%B0%D1%82%D0%BE%D1%80_%D1%81%D0%BB%D1%83%D0%B6%D0%B1)
