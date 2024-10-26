<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php htmlspecialchars($film['title']); ?></title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include 'partials/header.php'; ?>

<div class="movie-detail">
    <div class="movie-info">
        <h1><?= htmlspecialchars($film['title']); ?></h1>
        <p><strong>Год:</strong> <?= htmlspecialchars($film['year']); ?></p>
        <?php if (!empty($film['iframe_src'])): ?>
            <iframe src="<?= htmlspecialchars($film['iframe_src']); ?>" allowfullscreen></iframe>
        <?php else: ?>
            <p>Видео недоступно</p>
        <input type="hidden" name="film" value="<?php $film['id'] ?>">

        <?php endif; ?>

        <!-- Если фильм в избранном, показываем кнопку "Удалить из избранного" -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($is_favorite): ?>
                <form action="/favorites/remove" method="POST" class="favorite-form">
                    <input type="hidden" name="film_id" value="<?= htmlspecialchars($film['id']); ?>">
                    <button type="submit" class="favorite-btn">Удалить из избранного</button>
                </form>
            <?php else: ?>
                <form action="/favorites/add" method="POST" class="favorite-form">
                    <input type="hidden" name="film_id" value="<?= htmlspecialchars($film['id']); ?>">
                    <button type="submit" class="favorite-btn">Добавить в избранное</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>



    </div>
</div>


<?php include 'partials/footer.php'; ?>
</body>
</html>
