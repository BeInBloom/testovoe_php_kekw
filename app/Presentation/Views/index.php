<?php
/** @var App\Application\DTOs\NewsDetailDTO $latest */
/** @var App\Application\DTOs\NewsListDTO $list */

use App\Presentation\Security\OutputSanitizer;

$latestImage = OutputSanitizer::sanitizeImageName($latest->image);
$title = 'Галактический вестник';
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<section class="hero" style="background-image: url('/images/<?= OutputSanitizer::escape($latestImage) ?>');">
    <div class="hero-overlay">
        <div class="container hero-content">
            <h1><?= OutputSanitizer::escape($latest->title) ?></h1>
            <p><?= OutputSanitizer::escape(OutputSanitizer::plainText($latest->announce)) ?></p>
        </div>
    </div>
</section>

<section class="container news-section">
    <h2 class="section-title">Новости</h2>
    <div class="news-grid">
        <?php foreach ($list->news as $index => $news): ?>
            <article class="news-card">
                <span class="date-pill"><?= OutputSanitizer::escape($news->date) ?></span>
                <h3 class="news-card-title <?= $index === 0 ? 'is-accent' : '' ?>">
                    <a href="/news.php?id=<?= (int) $news->id ?>"><?= OutputSanitizer::escape($news->title) ?></a>
                </h3>
                <p class="news-card-text"><?= OutputSanitizer::escape(OutputSanitizer::plainText($news->announce)) ?></p>
                <a href="/news.php?id=<?= (int) $news->id ?>" class="outline-btn">подробнее <span aria-hidden="true">→</span></a>
            </article>
        <?php endforeach; ?>
    </div>

    <nav class="pagination" aria-label="Навигация по страницам">
        <?php for ($i = 1; $i <= $list->totalPages; $i++): ?>
            <a href="/?page=<?= (int) $i ?>" class="<?= $i === $list->currentPage ? 'active' : '' ?>">
                <?= (int) $i ?>
            </a>
        <?php endfor; ?>
    </nav>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
