<?php

declare(strict_types=1);

$title = 'Страница не найдена';
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<section class="container not-found">
    <div class="not-found-content">
        <h1 class="not-found-title">404 не найдено</h1>
        <p class="not-found-text">Запрошенная страница отсутствует или больше недоступна.</p>
        <a href="/" class="outline-btn">← назад к новостям</a>
    </div>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
