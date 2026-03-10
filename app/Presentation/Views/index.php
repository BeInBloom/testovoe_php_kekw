<?php
/** @var App\Application\DTOs\NewsDetailDTO|null $latest */
/** @var App\Application\DTOs\NewsListDTO $list */

use App\Presentation\Security\OutputSanitizer;

$latestImagePath = $latest !== null ? OutputSanitizer::resolvePublicImagePath($latest->image) : null;
$title           = 'Галактический вестник';
$windowSize      = 3;
$windowStart     = max(1, min($list->currentPage - 1, $list->totalPages - $windowSize + 1));
$windowEnd       = min($list->totalPages, $windowStart + $windowSize - 1);
$hasPreviousPage = $list->totalPages > 0 && $list->currentPage > 1;
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<section
    class="hero <?= $latestImagePath === null ? 'hero-no-image' : '' ?>"
    <?php if ($latestImagePath !== null): ?>
        style="background-image: url('<?= OutputSanitizer::escape($latestImagePath) ?>');"
    <?php endif; ?>
>
    <div class="hero-overlay">
        <div class="container">
            <div class="hero-content">
                <?php if ($latest !== null): ?>
                    <h1><?= OutputSanitizer::escape($latest->title) ?></h1>
                    <?php $latestAnnounce = OutputSanitizer::plainText($latest->announce); ?>
                    <?php if ($latestAnnounce !== ''): ?>
                        <p><?= OutputSanitizer::escape($latestAnnounce) ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <h1>Новости</h1>
                    <p>В базе пока нет опубликованных новостей.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="container news-section">
    <h2 class="section-title">Новости</h2>
    <?php if ($list->news === []): ?>
        <div class="empty-state">
            <p>Новостей пока нет. Когда в базе появятся записи, список заполнится автоматически.</p>
        </div>
    <?php else: ?>
        <div class="news-grid">
            <?php foreach ($list->news as $index => $news): ?>
                <?php $announce = OutputSanitizer::plainText($news->announce); ?>
                <article class="news-card">
                    <span class="date-pill"><?= OutputSanitizer::escape(OutputSanitizer::formatDate($news->date)) ?></span>
                    <h3 class="news-card-title <?= $index === 0 ? 'is-accent' : '' ?>">
                        <a href="/news.php?id=<?= (int) $news->id ?>"><?= OutputSanitizer::escape($news->title) ?></a>
                    </h3>
                    <?php if ($announce !== ''): ?>
                        <p class="news-card-text"><?= OutputSanitizer::escape($announce) ?></p>
                    <?php endif; ?>
                    <a href="/news.php?id=<?= (int) $news->id ?>" class="outline-btn">подробнее <span aria-hidden="true">→</span></a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($list->totalPages > 1): ?>
        <nav class="pagination" aria-label="Навигация по страницам">
            <?php if ($hasPreviousPage): ?>
                <a href="/?page=<?= (int) ($list->currentPage - 1) ?>" aria-label="Предыдущая страница">←</a>
            <?php endif; ?>
            <?php for ($i = $windowStart; $i <= $windowEnd; $i++): ?>
                <a href="/?page=<?= (int) $i ?>" class="<?= $i === $list->currentPage ? 'active' : '' ?>">
                    <?= (int) $i ?>
                </a>
            <?php endfor; ?>
            <?php if ($list->hasNextPage): ?>
                <a href="/?page=<?= (int) ($list->currentPage + 1) ?>" aria-label="Следующая страница">→</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
