<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение выхода</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>

<div class="confirmation">
    <h2>Вы действительно хотите выйти?</h2>
    <a href="/logout">Да</a>  <!-- Изменение пути для выхода -->
    <a href="/">Нет</a>
</div>
<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>

