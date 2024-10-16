<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
</head>
<body>
<h2>Регистрация</h2>


<form action="/auth" method="post">
    <div>
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username">
    </div>
    <div>
        <label for="email">Электронная почта:</label>
        <input type="email" id="email" name="email">
    </div>
    <div>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password">
    </div>
    <div>
        <button type="submit">Зарегистрироваться</button>
    </div>
</form>

</body>
</html>

