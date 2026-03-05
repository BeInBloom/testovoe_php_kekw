<?php
/** @var string $errorMessage */

$title = 'Error';
?>

<?php include __DIR__ . '/layouts/header.php'; ?>

<article class="news-detail">
    <h1>Request Error</h1>
    <p><?= App\Presentation\Security\OutputSanitizer::escape($errorMessage) ?></p>
</article>

<a href="/" class="btn">Back to news</a>

<?php include __DIR__ . '/layouts/footer.php'; ?>
