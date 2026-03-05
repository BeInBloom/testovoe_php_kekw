<?php
/** @var App\Application\DTOs\NewsDetailDTO $latest */
/** @var App\Application\DTOs\NewsListDTO $list */

use App\Presentation\Security\OutputSanitizer;

$latestImage = OutputSanitizer::sanitizeImageName($latest->image);
$title = 'Галактический вестник';
$windowSize = 3;
$windowStart = max(1, min($list->currentPage - 1, $list->totalPages - $windowSize + 1));
$windowEnd = min($list->totalPages, $windowStart + $windowSize - 1);
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<section class="hero" style="background-image: url('/images/<?= OutputSanitizer::escape($latestImage) ?>');">
    <div class="hero-overlay">
        <div class="container">
            <div class="hero-content">
                <h1><?= OutputSanitizer::escape($latest->title) ?></h1>
                <p><?= OutputSanitizer::escape(OutputSanitizer::plainText($latest->announce)) ?></p>
            </div>
        </div>
    </div>
</section>

<section class="container news-section">
    <h2 class="section-title">Новости</h2>
    <div class="news-grid">
        <?php foreach ($list->news as $index => $news): ?>
            <article class="news-card">
                <span class="date-pill"><?= OutputSanitizer::escape(OutputSanitizer::formatDate($news->date)) ?></span>
                <h3 class="news-card-title <?= $index === 0 ? 'is-accent' : '' ?>">
                    <a href="/news.php?id=<?= (int) $news->id ?>"><?= OutputSanitizer::escape($news->title) ?></a>
                </h3>
                <p class="news-card-text"><?= OutputSanitizer::escape(OutputSanitizer::plainText($news->announce)) ?></p>
                <a href="/news.php?id=<?= (int) $news->id ?>" class="outline-btn">подробнее <span aria-hidden="true">→</span></a>
            </article>
        <?php endforeach; ?>
    </div>

    <nav class="pagination" aria-label="Навигация по страницам">
        <?php for ($i = $windowStart; $i <= $windowEnd; $i++): ?>
            <a href="/?page=<?= (int) $i ?>" class="<?= $i === $list->currentPage ? 'active' : '' ?>">
                <?= (int) $i ?>
            </a>
        <?php endfor; ?>
        <?php if ($list->hasNextPage): ?>
            <a href="/?page=<?= (int) ($list->currentPage + 1) ?>" aria-label="Следующая страница">→</a>
        <?php endif; ?>
    </nav>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
