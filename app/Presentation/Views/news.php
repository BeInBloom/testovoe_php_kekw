<?php
/** @var App\Application\DTOs\NewsDetailDTO $news */

use App\Presentation\Security\OutputSanitizer;

$imageName = OutputSanitizer::sanitizeImageName($news->image);
$title = $news->title;
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<section class="container detail-page">
    <nav class="breadcrumbs" aria-label="Навигация">
        <a href="/">Главная</a>
        <span>/</span>
        <span><?= OutputSanitizer::escape($news->title) ?></span>
    </nav>

    <h1 class="detail-title"><?= OutputSanitizer::escape($news->title) ?></h1>

    <article class="detail-content">
        <span class="date-pill"><?= OutputSanitizer::escape(OutputSanitizer::formatDate($news->date)) ?></span>
        <p class="detail-lead"><?= OutputSanitizer::escape(OutputSanitizer::plainText($news->announce)) ?></p>
        <?php if ($imageName !== ''): ?>
            <div class="detail-image-float">
                <img src="/images/<?= OutputSanitizer::escape($imageName) ?>" alt="<?= OutputSanitizer::escape($news->title) ?>" class="news-image">
            </div>
        <?php endif; ?>

        <div class="news-content">
            <?= OutputSanitizer::sanitizeRichText($news->content) ?>
        </div>
        <div class="detail-actions">
            <a href="/" class="outline-btn">← назад к новостям</a>
        </div>
    </article>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
