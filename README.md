# Guest-app

Микросервис для работы с гостями на Laravel.

## Техническое задание
Написать микросервис работы с гостями используя язык программирования на выбор PHP или Go, можно пользоваться любыми opensource пакетами, также возможно реализовать с использованием фреймворков или без них. БД также любая на выбор, использующая SQL в качестве языка запросов.

Микросервис реализует API для CRUD операций над гостем. То есть принимает данные для создания, изменения, получения, удаления записей гостей хранящихся в выбранной базе данных.

Сущность "Гость" Имя, фамилия и телефон – обязательные поля. А поля телефон и email уникальны. В итоге у гостя должны быть следующие атрибуты: идентификатор, имя, фамилия, email, телефон, страна. Если страна не указана то доставать страну из номера телефона +7 - Россия и т.д.

Правила валидации нужно придумать и реализовать самостоятельно. Микросервис должен запускаться в Docker.

## Установка и настройка
После скачивания репозитория необходимо запустить команду:

```bash
docker-compose up --build
```
После установки и запуска контейнеров необходимо (если файла .env ещё нет) создать файл .env из файла .env.example.

```bash
cp .env.example .env
```

> Вообще, данные для БД и кэширования предустановлены, но на всякий случай они были добавлены и в файл .env.example, поэтому можно взять оттуда.

Дальше необходимо зайти в контейнер командой:

```bash
docker-compose exec app bash
```

И выполнить команды:

```bash
php artisan key:generate
```

```bash
php artisan migrate
```

```bash
php artisan app:generate-token
```

>Вообще, команда установки зависимостей "composer install" зашита в докер при билде, но могут быть проблемы из-за которых зависимости в контейнере не появятся, рекомендую проверить этот момент зайдя в контейнер и проверить наличие папки vendor

После выполнения команды app:generate-token в файл .env пропишется API_TOKEN, который необходимо использовать для bearer_auth при запросах. 

## Описание API

Методы:

* POST /guests - Создать запись гостя.
* GET /guests/{id} - Получить информацию о госте по идентификатору. 
* GET /guests - Получить список гостей.
* PUT /guests/{id} - Обновить информацию о госте.
* DELETE /guests/{id} - Удалить запись гостя.

Для все документации описаны api через пакет scribe.

Для того, чтобы сгенерировать документацию для api, необходимо выполнить следующие команды:

```bash
php artisan vendor:publish --tag=scribe-config
```

```bash
php artisan scribe:generate
```

После этого документация по методам будет доступна по адресу: http://localhost:8000/docs

Так же в гите проекта лежит файл postman-коллекции, в которой тоже описаны все параметры и роуты, которые используются для API.

## Примечание

Данный микросервис был разработан как тестовое задание, к нему был написан файл докера, который, в силу некоторых особенностей среды разработки, может не всем подойти.

Поэтому, если будут проблемы с работой докера, параметры для контейнеров mysql и redis были добавлены в файл .env.example.

В проекте использовались следующие контейнеры:
nginx, mysql, php8.3, redis.
