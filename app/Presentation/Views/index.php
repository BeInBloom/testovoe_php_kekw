<?php
/** @var App\Application\DTOs\NewsDetailDTO $latest */
/** @var App\Application\DTOs\NewsListDTO $list */

use App\Presentation\Security\OutputSanitizer;

$latestImage = OutputSanitizer::sanitizeImageName($latest->image);
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<section class="latest-news" style="background-image: url('/images/<?= OutputSanitizer::escape($latestImage) ?>');">
    <div class="latest-news-content">
        <h2><?= OutputSanitizer::escape($latest->title) ?></h2>
        <p class="date"><?= OutputSanitizer::escape($latest->date) ?></p>
        <div class="announce"><?= OutputSanitizer::sanitizeRichText($latest->announce) ?></div>
        <a href="/news.php?id=<?= (int) $latest->id ?>" class="btn">Read more</a>
    </div>
</section>

<section class="news-list">
    <?php foreach ($list->news as $news): ?>
        <article class="news-item">
            <h3><a href="/news.php?id=<?= (int) $news->id ?>"><?= OutputSanitizer::escape($news->title) ?></a></h3>
            <p class="date"><?= OutputSanitizer::escape($news->date) ?></p>
            <div class="announce"><?= OutputSanitizer::sanitizeRichText($news->announce) ?></div>
        </article>
    <?php endforeach; ?>
</section>

<nav class="pagination">
    <?php for ($i = 1; $i <= $list->totalPages; $i++): ?>
        <a href="/?page=<?= (int) $i ?>" class="<?= $i === $list->currentPage ? 'active' : '' ?>">
            <?= (int) $i ?>
        </a>
    <?php endfor; ?>
</nav>

<?php include __DIR__ . '/layouts/footer.php'; ?>
