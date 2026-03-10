<?php
/** @var App\Application\DTOs\NewsDetailDTO $news */

use App\Presentation\Security\OutputSanitizer;

$imagePath = OutputSanitizer::resolvePublicImagePath($news->image);
$title     = $news->title;
$announce  = OutputSanitizer::plainText($news->announce);
$content   = OutputSanitizer::sanitizeRichText($news->content);
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
        <?php if ($imagePath !== null): ?>
            <div class="detail-image-float">
                <img src="<?= OutputSanitizer::escape($imagePath) ?>" alt="<?= OutputSanitizer::escape($news->title) ?>" class="news-image">
            </div>
        <?php endif; ?>
        <?php if ($announce !== ''): ?>
            <p class="detail-lead"><?= OutputSanitizer::escape($announce) ?></p>
        <?php endif; ?>

        <div class="news-content">
            <?php if (OutputSanitizer::plainText($content) !== ''): ?>
                <?= $content ?>
            <?php else: ?>
                <p>Полный текст новости пока недоступен.</p>
            <?php endif; ?>
        </div>
        <div class="detail-actions">
            <a href="/" class="outline-btn">← назад к новостям</a>
        </div>
    </article>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
