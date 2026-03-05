<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= App\Presentation\Security\OutputSanitizer::escape($title ?? 'News Site') ?></title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <header>
        <h1>News Site</h1>
    </header>
    <main>
