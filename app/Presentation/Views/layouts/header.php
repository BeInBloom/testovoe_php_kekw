<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= App\Presentation\Security\OutputSanitizer::escape($title ?? 'News Site') ?></title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container site-header-inner">
            <a href="/" class="brand" aria-label="На главную">
                <span class="brand-mark" aria-hidden="true"></span>
                <span class="brand-text">
                    <span>ГАЛАКТИЧЕСКИЙ</span>
                    <span>ВЕСТНИК</span>
                </span>
            </a>
        </div>
    </header>
    <main class="site-main">
