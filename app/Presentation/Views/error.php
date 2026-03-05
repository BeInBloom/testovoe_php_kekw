<?php
/** @var string $errorMessage */

$title = 'Ошибка';
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<section class="container detail-page">
    <article class="detail-content">
        <h1 class="detail-title">Ошибка запроса</h1>
        <p class="detail-lead"><?= App\Presentation\Security\OutputSanitizer::escape($errorMessage) ?></p>
        <div class="detail-actions">
            <a href="/" class="outline-btn">← назад к новостям</a>
        </div>
    </article>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
