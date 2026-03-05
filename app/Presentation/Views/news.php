<?php
/** @var App\Application\DTOs\NewsDetailDTO $news */

use App\Presentation\Security\OutputSanitizer;

$imageName = OutputSanitizer::sanitizeImageName($news->image);
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<article class="news-detail">
    <h1><?= OutputSanitizer::escape($news->title) ?></h1>
    <p class="date"><?= OutputSanitizer::escape($news->date) ?></p>
    
    <?php if ($imageName !== ''): ?>
        <img src="/images/<?= OutputSanitizer::escape($imageName) ?>" alt="<?= OutputSanitizer::escape($news->title) ?>" class="news-image">
    <?php endif; ?>
    
    <div class="news-content">
        <?= OutputSanitizer::sanitizeRichText($news->content) ?>
    </div>
</article>

<a href="/" class="btn">Back to news</a>

<?php include __DIR__ . '/layouts/footer.php'; ?>
