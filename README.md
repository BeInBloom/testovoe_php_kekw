# News Site

Мини-сайт на PHP / MySQL / HTML / CSS без фреймворков и CMS.

Что реализовано:

- список новостей с сортировкой по `date DESC`
- детальная страница новости
- пагинация по 4 новости на страницу
- hero-блок с последней новостью из всей базы
- безопасная обработка `page` / `id`, экранирование вывода и корректные 400/404

## Требования

- Docker + Docker Compose
- PHP 8.3+ для локального запуска вне Docker
- Composer

## Запуск

1. Скопировать переменные окружения:

```bash
cp .env.example .env
```

2. Поднять контейнеры:

```bash
docker compose up -d --build
```

3. Открыть сайт:

- главная: `http://127.0.0.1/`
- список, страница 2: `http://127.0.0.1/?page=2`
- детальная страница новости `id=3`: `http://127.0.0.1/news.php?id=3`
- health-check: `http://127.0.0.1/health`

Примечание: MySQL seed из [`database/news.sql`](/home/needmoredoggos/code/testovoe/php/database/news.sql) импортируется автоматически при первом старте контейнера `mysql`.

## Проверка качества

```bash
composer test
composer analyse
composer cs-check
```

## Структура

- [`app/Application`](/home/needmoredoggos/code/testovoe/php/app/Application) — DTO и прикладные сервисы
- [`app/Domain`](/home/needmoredoggos/code/testovoe/php/app/Domain) — сущности, value objects, контракты и доменные исключения
- [`app/Infrastructure`](/home/needmoredoggos/code/testovoe/php/app/Infrastructure) — контейнер, логирование, PDO/MySQL
- [`app/Presentation`](/home/needmoredoggos/code/testovoe/php/app/Presentation) — controllers, HTTP helpers и views
- [`public`](/home/needmoredoggos/code/testovoe/php/public) — публичные entrypoint-файлы, CSS и изображения
- [`tests`](/home/needmoredoggos/code/testovoe/php/tests) — unit и smoke tests
