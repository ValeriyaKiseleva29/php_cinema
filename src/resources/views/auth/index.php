<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация нового пользователя</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include __DIR__ . '/../film/partials/header.php'; ?>

<div class="registration-container">
    <h2>Регистрация нового пользователя</h2>
    <p>У меня уже есть аккаунт, я хочу <a href="/login">войти</a></p>
    <?php if (isset($_SESSION['errors'])): ?>
        <ul class="error-list">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li class="error"><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    <form action="/auth" method="post" class="registration-form">
        <div class="form-group">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" value="<?= $_POST['username'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label for="email">Электронная почта:</label>
            <input type="email" id="email" name="email" value="<?= $_POST['email'] ?? '' ?>">
        </div>

        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="confirm_password">Повторите пароль:</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>

        <div class="form-group">
            <label for="dob">Дата рождения:</label>
            <input type="date" id="dob" name="dob">
        </div>

        <div class="form-group">
            <label for="age">Возраст:</label>
            <input type="number" id="age" name="age">
        </div>

        <div class="form-group">
            <label for="gender">Пол:</label>
            <select id="gender" name="gender">
                <option value="male">Мужской</option>
                <option value="female">Женский</option>
            </select>
        </div>

        <div class="form-group">
            <label for="interests">Интересы:</label>
            <select id="interests" name="interests[]" multiple>
                <option value="movies">Фильмы</option>
                <option value="music">Музыка</option>
                <option value="sports">Спорт</option>
            </select>
        </div>

        <div class="form-group">
            <label for="remember">Запомнить меня:</label>
            <input type="checkbox" id="remember" name="remember">
        </div>

        <div class="form-group">
            <label>Как часто Вы смотрите фильмы/сериалы:</label>
            <label class="radio-label" for="standard">
                <input type="radio" id="standard" name="user_type" value="standard" checked>Каждый день
            </label>
            <label class="radio-label" for="premium">
                <input type="radio" id="premium" name="user_type" value="premium">Через день
            </label>
            <label class="radio-label" for="premium">
                <input type="radio" id="premium" name="user_type" value="premium">Только на выходных
            </label>
            <label class="radio-label" for="premium">
                <input type="radio" id="premium" name="user_type" value="premium">Раз в месяц
            </label>
            <label class="radio-label" for="premium">
                <input type="radio" id="premium" name="user_type" value="premium">Вообще не смотрю, я тут случайно
            </label>
        </div>

        <div class="form-group">
            <button type="submit">Зарегистрироваться</button>
        </div>
    </form>
</div>


<?php include __DIR__ . '/../film/partials/footer.php'; ?>
</body>
</html>
