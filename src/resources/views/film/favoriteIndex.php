<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie in Che - Избранное</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include 'partials/header.php'; ?>

<div class="favorites-grid">
    <?php if (!empty($favorites)): ?>
        <?php foreach ($favorites as $favorite): ?>
            <div class="movie-card">
                <img src="<?= htmlspecialchars($favorite['img_link'] ?? '/path/to/default/poster.jpg'); ?>" alt="<?= htmlspecialchars($favorite['title'] ?? 'Без названия'); ?>">
                <h2><?= htmlspecialchars($favorite['title'] ?? 'Без названия'); ?></h2>
                <p><strong>Год:</strong> <?= htmlspecialchars($favorite['year'] ?? '—'); ?></p>
                <?php if (!empty($favorite['id'])): ?>
                    <a href="/films/<?= htmlspecialchars($favorite['id']); ?>">Смотреть онлайн</a>
                <?php else: ?>
                    <p>Фильм недоступен для просмотра.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>У вас пока нет избранных фильмов.</p>
    <?php endif; ?>
</div>

<?php include 'partials/footer.php'; ?>
</body>
</html>
