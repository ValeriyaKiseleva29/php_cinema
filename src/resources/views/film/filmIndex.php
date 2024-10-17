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
<div class="movie-announcement">
    <h2>Анонсы фильмов</h2>

    <img src="https://kino-teatr.ua/public/main/films/2024-08/x2_poster_66c431c34656b.jpg" alt="Анонс фильма 1">
    <img src="https://kino-teatr.ua/public/main/films/2024-06/x2_poster_6680e377895ee.jpg" alt="Анонс фильма 2">
    <img src="https://kino-teatr.ua/public/main/films/2024-10/x2_poster_67022412bde90.jpg" alt="Анонс фильма 3">
    <img src="https://kino-teatr.ua/public/main/films/2024-08/x2_poster_66d29d669cec5.jpg" alt="Анонс фильма 4">
    <img src="https://kino-teatr.ua/public/main/films/2024-09/x2_poster_66f5494be801a.jpg" alt="Анонс фильма 5">
</div>


<div class="movies-grid">
    <!-- Фильм 1 -->
    <div class="movie-card">
        <img src="path_to_movie_image_1.jpg" alt="Фильм 1">
        <h3>Название фильма 1</h3>
    </div>

    <!-- Фильм 2 -->
    <div class="movie-card">
        <img src="path_to_movie_image_2.jpg" alt="Фильм 2">
        <h3>Название фильма 2</h3>
    </div>

    <!-- Фильм 3 -->
    <div class="movie-card">
        <img src="path_to_movie_image_3.jpg" alt="Фильм 3">
        <h3>Название фильма 3</h3>
    </div>

    <!-- Фильм 4 -->
    <div class="movie-card">
        <img src="path_to_movie_image_4.jpg" alt="Фильм 4">
        <h3>Название фильма 4</h3>
    </div>
</div>
<?php include 'partials/footer.php'; ?>
</body>
</html>
