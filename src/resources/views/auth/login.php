<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в аккаунт</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include __DIR__ . '/../film/partials/header.php'; ?>

<div class="registration-container">
    <h2>Вход в аккаунт</h2>
    <?php if (isset($_SESSION['errors'])): ?>
        <ul class="error-list">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li class="error"><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form action="/login" method="POST" class="registration-form">
        <div class="form-group">
            <label for="email">Электронная почта:</label>
            <input type="email" id="email" name="email" required value="<?= $_POST['email'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="remember">Запомнить меня:</label>
            <input type="checkbox" id="remember" name="remember" <?= isset($_POST['remember']) ? 'checked' : '' ?>>
        </div>

        <div class="form-group">
            <button type="submit">Войти</button>
        </div>
    </form>


    <div class="form-group">
        <p>Нет аккаунта? Тогда <a href="/auth">зарегистрируйтесь</a>.</p>
    </div>
</div>

<?php include __DIR__ . '/../film/partials/footer.php'; ?>
</body>
</html>
