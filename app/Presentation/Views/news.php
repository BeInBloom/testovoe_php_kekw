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

    <div class="detail-grid">
        <article class="detail-content">
            <span class="date-pill"><?= OutputSanitizer::escape($news->date) ?></span>
            <p class="detail-lead"><?= OutputSanitizer::escape(OutputSanitizer::plainText($news->announce)) ?></p>
            <div class="news-content">
                <?= OutputSanitizer::sanitizeRichText($news->content) ?>
            </div>
            <a href="/" class="outline-btn">← назад к новостям</a>
        </article>

        <?php if ($imageName !== ''): ?>
            <div class="detail-image-wrap">
                <img src="/images/<?= OutputSanitizer::escape($imageName) ?>" alt="<?= OutputSanitizer::escape($news->title) ?>" class="news-image">
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
