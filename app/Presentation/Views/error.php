<?php
/** @var string $errorMessage */
/** @var int $statusCode */

$heading = match ($statusCode) {
    400     => 'Некорректный запрос',
    404     => 'Страница не найдена',
    default => 'Внутренняя ошибка',
};

$title = $heading;
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<section class="container detail-page">
    <article class="detail-content">
        <h1 class="detail-title"><?= App\Presentation\Security\OutputSanitizer::escape($heading) ?></h1>
        <p class="detail-lead"><?= App\Presentation\Security\OutputSanitizer::escape($errorMessage) ?></p>
        <div class="detail-actions">
            <a href="/" class="outline-btn">← назад к новостям</a>
        </div>
    </article>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
