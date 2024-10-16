<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie in Che - Фильм</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include 'partials/header.php'; ?>
<div class="movie-detail">
    <!-- Постер фильма -->
    <div class="movie-poster">
        <img src="path_to_movie_poster.jpg" alt="Постер фильма">
    </div>

    <!-- Информация о фильме -->
    <div class="movie-info">
        <h1>Название фильма</h1>
        <p>Описание фильма. Здесь будет краткое описание сюжета, главные события и другие интересные факты о фильме.</p>
        <p><strong>Жанры:</strong> Драма, Комедия, Приключения</p>
        <p><strong>Рейтинг:</strong> 8.2/10</p>
    </div>
</div>
<?php include 'partials/footer.php'; ?>

</body>
</html>
