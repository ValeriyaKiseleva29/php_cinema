<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie in Che - Главная</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<?php include 'partials/header.php'; ?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="message">
        <?= $_SESSION['message']; ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="movie-announcement img">
    <h2>Анонсы фильмов</h2>
    <img src="https://static.rozetked.me/imager/main/images/uploads/LPOzw16fGLgH.webp" alt="Анонс фильма 1">
    <img src="https://www.indiewire.com/wp-content/uploads/2018/12/Screen-Shot-2018-12-06-at-4.52.45-PM.png?w=674" alt="Анонс фильма 2">
</div>

<div class="movies-grid">
    <?php if (isset($films) && is_array($films) && count($films) > 0): ?>
        <?php foreach ($films as $film): ?>
            <div class="movie-card">
                <?php if (!empty($film['img_link'])): ?>
                    <img src="<?= htmlspecialchars($film['img_link']); ?>" alt="<?= htmlspecialchars($film['title']); ?>">
                <?php else: ?>
                    <img src="/path/to/default/poster.jpg" alt="Постер не найден">
                <?php endif; ?>
                <h3><?= htmlspecialchars($film['title']); ?></h3>
                <a href="/films/<?= $film['id']; ?>">Смотреть онлайн</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Фильмы не найдены.</p>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="/films?page=<?= $currentPage - 1; ?>&query=<?= htmlspecialchars($query ?? ''); ?>">Предыдущая</a>
    <?php endif; ?>

    <span>Страница <?= $currentPage; ?> из <?= $totalPages; ?></span>

    <?php if ($currentPage < $totalPages): ?>
        <a href="/films?page=<?= $currentPage + 1; ?>&query=<?= htmlspecialchars($query ?? ''); ?>">Следующая</a>
    <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>

</body>
</html>
