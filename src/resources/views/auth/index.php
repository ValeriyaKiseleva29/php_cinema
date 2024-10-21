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

    <form action="/auth" method="post" class="registration-form">
        <div class="form-group <?= isset($_SESSION['errors']['username']) ? 'has-error' : '' ?>">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" class="<?= isset($_SESSION['errors']['username']) ? 'error' : '' ?>" value="<?= $_SESSION['old']['username'] ?? '' ?>">
            <?php if (isset($_SESSION['errors']['username'])): ?>
                <span class="error"><?= $_SESSION['errors']['username'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group <?= isset($_SESSION['errors']['email']) ? 'has-error' : '' ?>">
            <label for="email">Электронная почта:</label>
            <input type="email" id="email" name="email" class="<?= isset($_SESSION['errors']['email']) ? 'error' : '' ?>" value="<?= $_SESSION['old']['email'] ?? '' ?>">
            <?php if (isset($_SESSION['errors']['email'])): ?>
                <span class="error"><?= $_SESSION['errors']['email'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group <?= isset($_SESSION['errors']['password']) ? 'has-error' : '' ?>">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" class="<?= isset($_SESSION['errors']['password']) ? 'error' : '' ?>">
            <?php if (isset($_SESSION['errors']['password'])): ?>
                <span class="error"><?= $_SESSION['errors']['password'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group <?= isset($_SESSION['errors']['confirm_password']) ? 'has-error' : '' ?>">
            <label for="confirm_password">Повторите пароль:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="<?= isset($_SESSION['errors']['confirm_password']) ? 'error' : '' ?>">
            <?php if (isset($_SESSION['errors']['confirm_password'])): ?>
                <span class="error"><?= $_SESSION['errors']['confirm_password'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group <?= isset($_SESSION['errors']['age']) ? 'has-error' : '' ?>">
            <label for="age">Возраст:</label>
            <input type="number" id="age" name="age" class="<?= isset($_SESSION['errors']['age']) ? 'error' : '' ?>" value="<?= $_SESSION['old']['age'] ?? '' ?>">
            <?php if (isset($_SESSION['errors']['age'])): ?>
                <span class="error"><?= $_SESSION['errors']['age'] ?></span>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="dob">Дата рождения:</label>
            <input type="date" id="dob" name="dob" class="<?= isset($_SESSION['errors']['dob']) ? 'error' : '' ?>" value="<?= $_SESSION['old']['dob'] ?? '' ?>">
            <?php if (isset($_SESSION['errors']['dob'])): ?>
                <span class="error"><?= $_SESSION['errors']['dob'] ?></span>
            <?php endif; ?>
        </div>


        <div class="form-group">
            <label for="gender">Пол:</label>
            <select id="gender" name="gender" class="<?= isset($_SESSION['errors']['gender']) ? 'error' : '' ?>">
                <option value="male" <?= ($_POST['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Мужской</option>
                <option value="female" <?= ($_POST['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Женский</option>
            </select>
        </div>

        <div class="form-group">
            <label for="interests">Интересы:</label>
            <select id="interests" name="interests[]" multiple>
                <option value="movies" <?= in_array('movies', $_POST['interests'] ?? []) ? 'selected' : '' ?>>Фильмы</option>
                <option value="music" <?= in_array('music', $_POST['interests'] ?? []) ? 'selected' : '' ?>>Музыка</option>
                <option value="sports" <?= in_array('sports', $_POST['interests'] ?? []) ? 'selected' : '' ?>>Спорт</option>
            </select>
        </div>

        <div class="form-group">
            <label for="remember">Запомнить меня:</label>
            <input type="checkbox" id="remember" name="remember" <?= isset($_POST['remember']) ? 'checked' : '' ?>>
        </div>

        <div class="form-group">
            <button type="submit">Зарегистрироваться</button>
        </div>

        <?php
        if (isset($_SESSION['old'])) {
            unset($_SESSION['old']);
        }
        if (isset($_SESSION['errors'])) {
            unset($_SESSION['errors']);
        }
        ?>
    </form>
</div>

<?php include __DIR__ . '/../film/partials/footer.php'; ?>
</body>
</html>
