<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<?php include __DIR__ . '/../film/partials/header.php'; ?>

<div class="profile-container">
    <!-- Сообщение об успешном обновлении профиля -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert-success">
            <?= $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); // Удаляем сообщение после вывода ?>
    <?php endif; ?>

    <!-- Секция профиля -->
    <div class="section-wrapper">
        <h2>Профиль пользователя</h2>
        <form class="profile-form" action="/profile" method="POST">
            <div class="form-group <?= isset($_SESSION['errors']['email']) ? 'has-error' : '' ?>">
                <label for="email">Новый email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email'] ?? ''); ?>" required>
                <?php if (isset($_SESSION['errors']['email'])): ?>
                    <span class="error"><?= $_SESSION['errors']['email']; ?></span>
                    <?php unset($_SESSION['errors']['email']); // Удаляем ошибку после вывода ?>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($_SESSION['errors']['password']) ? 'has-error' : '' ?>">
                <label for="password">Новый пароль:</label>
                <input type="password" name="password" id="password" required>
                <?php if (isset($_SESSION['errors']['password'])): ?>
                    <span class="error"><?= $_SESSION['errors']['password']; ?></span>
                    <?php unset($_SESSION['errors']['password']); // Удаляем ошибку после вывода ?>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($_SESSION['errors']['confirm_password']) ? 'has-error' : '' ?>">
                <label for="confirm_password">Подтверждение пароля:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <?php if (isset($_SESSION['errors']['confirm_password'])): ?>
                    <span class="error"><?= $_SESSION['errors']['confirm_password']; ?></span>
                    <?php unset($_SESSION['errors']['confirm_password']); // Удаляем ошибку после вывода ?>
                <?php endif; ?>
            </div>

            <button type="submit">Обновить профиль</button>
        </form>
    </div>

    <!-- Секция аватара -->
    <div class="section-wrapper">
        <h2>Загрузить аватар</h2>
        <form class="avatar-form" action="/profile/avatar" method="POST" enctype="multipart/form-data">
            <div class="form-group <?= isset($_SESSION['errors']['avatar']) ? 'has-error' : '' ?>">
                <label for="avatar">Выберите аватар:</label>
                <input type="file" name="avatar" id="avatar" required>
                <?php if (isset($_SESSION['errors']['avatar'])): ?>
                    <span class="error"><?= $_SESSION['errors']['avatar']; ?></span>
                    <?php unset($_SESSION['errors']['avatar']); // Удаляем ошибку после вывода ?>
                <?php endif; ?>
            </div>

            <button type="submit">Загрузить аватар</button>
        </form>

        <!-- Отображение текущего аватара -->
        <?php if (!empty($user['avatar'])): ?>
            <h3>Текущий аватар</h3>
            <img src="/avatars/<?= htmlspecialchars($user['avatar']); ?>" alt="Avatar">
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../film/partials/footer.php'; ?>
</body>
</html>
