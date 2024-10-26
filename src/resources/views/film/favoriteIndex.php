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

<div class="favorites-grid"> <!-- Изменяем класс с movies-grid на favorites-grid -->
    <?php if (!empty($favorites)): ?>
        <?php foreach ($favorites as $favorite): ?>
            <div class="movie-card">
                <!-- Проверяем наличие изображения и подставляем изображение по умолчанию, если оно отсутствует -->
                <img src="<?= htmlspecialchars($favorite['img_link'] ?? '/path/to/default/poster.jpg'); ?>" alt="<?= htmlspecialchars($favorite['title'] ?? 'Без названия'); ?>">

                <!-- Проверяем наличие заголовка -->
                <h2><?= htmlspecialchars($favorite['title'] ?? 'Без названия'); ?></h2>

                <!-- Проверяем наличие года, если год не указан, показываем прочерк -->
                <p><strong>Год:</strong> <?= htmlspecialchars($favorite['year'] ?? '—'); ?></p>

                <!-- Ссылка для просмотра фильма -->
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
