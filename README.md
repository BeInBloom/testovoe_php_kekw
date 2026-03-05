# News Site

PHP MVC новостной сайт с использованием SOLID принципов, Docker, CI/CD и полным тестированием.

## Требования

- Docker & Docker Compose
- PHP 8.3
- Composer

## Установка

1. Клонировать репозиторий
2. Скопировать `.env.example` в `.env`
3. Запустить Docker контейнеры:

```bash
docker compose up -d
```

## Структура проекта

```
php/
├── app/
│   ├── Domain/              # Сущности и Value Objects
│   ├── Application/         # Сервисы и DTOs
│   ├── Infrastructure/      # Репозитории и логирование
│   └── Presentation/         # Контроллеры и Views
├── public/                  # Публичные файлы
├── tests/                   # Тесты
├── docker/                  # Docker конфигурация
└── config/                  # Конфиги
```

## Команды

### Запуск тестов
```bash
composer test
composer test:coverage
```

### Статический анализ
```bash
composer analyse
```

### Линтинг
```bash
composer cs-fix      # Автоисправление
composer cs-check    # Проверка
```

### Coverage в Docker
Если локально не включен `xdebug`/`pcov`, запустите coverage внутри контейнера:

```bash
docker compose run --rm -e XDEBUG_MODE=coverage php composer test:coverage
```

## Smoke endpoint

- `GET /health` возвращает JSON-статус приложения:

```json
{"status":"ok","timestamp":"..."}
```

## Git Hooks

Lefthook хуки автоматически запускают:
- **pre-commit**: CS Fixer + PHPStan
- **pre-push**: PHPUnit

## CI/CD

GitHub Actions автоматически:
- Устанавливает зависимости
- Запускает PHP CS Fixer
- Запускает PHPStan
- Запускает PHPUnit
- Деплоит на production (main ветка)
