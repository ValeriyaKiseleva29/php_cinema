<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;
use App\Validation\StringValidator;
use App\Validation\NumberValidator;
use App\Validation\EmailValidator;
use App\Validation\PasswordValidator;
use App\RMVC\Database\DB;


class AuthController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        return View::view('auth.index');
    }

    public function showLoginForm()
    {
        return View::view('auth.login');
    }
    public function showLogoutConfirmation()
    {
        return View::view('film.partials.logout_confirmation');
    }


    public function store()
    {
        $errors = [];

        // Инициализация валидаторов
        $stringValidator = new StringValidator();
        $emailValidator = new EmailValidator();
        $passwordValidator = new PasswordValidator();
        $numberValidator = new NumberValidator();

        // Валидация имени пользователя (строка)
        if (empty($_POST['username'])) {
            $errors['username'] = 'Имя пользователя обязательно.';
        } elseif (!$stringValidator->validate($_POST['username'])) {
            $errors['username'] = 'Неверный формат имени пользователя.';
        }

        // Валидация email
        if (empty($_POST['email'])) {
            $errors['email'] = 'Электронная почта обязательна.';
        } elseif (!$emailValidator->validate($_POST['email'])) {
            $errors['email'] = 'Неверный формат электронной почты.';
        }

        // Валидация пароля
        if (empty($_POST['password'])) {
            $errors['password'] = 'Пароль обязателен.';
        } elseif (!$passwordValidator->validate($_POST['password'])) {
            $errors['password'] = 'Пароль должен содержать не менее 8 символов и хотя бы одну цифру.';
        }

        // Проверка на совпадение паролей
        if (empty($_POST['confirm_password'])) {
            $errors['confirm_password'] = 'Подтвердите пароль.';
        } elseif ($_POST['password'] !== $_POST['confirm_password']) {
            $errors['confirm_password'] = 'Пароли не совпадают.';
        }

        // Валидация возраста (целое число)
        if (empty($_POST['age'])) {
            $errors['age'] = 'Возраст обязателен.';
        } elseif (!$numberValidator->validate($_POST['age'])) {
            $errors['age'] = 'Неверный формат возраста.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST; // Сохраняем введенные данные
            Route::redirect('/auth');  // Перенаправляем обратно на форму регистрации
            return;
        }

        // Хэшируем пароль перед сохранением
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Сохраняем пользователя в базу данных
        $sql = "INSERT INTO users (username, email, password, dob, age, gender, interests) 
        VALUES (:username, :email, :password, :dob, :age, :gender, :interests)";

        $this->db->execute($sql, [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $hashedPassword,
            'dob' => $_POST['dob'],
            'age' => $_POST['age'],
            'gender' => $_POST['gender'],
            'interests' => json_encode($_POST['interests']), // сохраняем интересы как JSON
        ]);

        $user_id = $this->db->lastInsertId();

        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $_POST['username'];

        // Проверка "Запомнить меня" и установка куки
        if (isset($_POST['remember'])) {
            $rememberMeToken = bin2hex(random_bytes(16)); // Генерация уникального токена
            setcookie('remember_me', $rememberMeToken, [
                'expires' => time() + (86400 * 30), // Кука на 30 дней
                'path' => '/',
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']),
                'samesite' => 'Strict',
            ]);

            // Сохраняем токен в базу данных
            $sql = "UPDATE users SET remember_me_token = :token WHERE id = :id";
            $this->db->execute($sql, [
                'token' => $rememberMeToken,
                'id' => $user_id
            ]);
        }

        // Перенаправляем пользователя после успешной регистрации
        $_SESSION['message'] = 'Вы успешно зарегистрировались. Добро пожаловать на сайт!';

        Route::redirect('/films');
    }

    public function login()
    {
        // Проверяем наличие куки "remember_me"
        if (isset($_COOKIE['remember_me'])) {
            $sql = "SELECT * FROM users WHERE remember_me_token = :token";
            $user = $this->db->fetch($sql, ['token' => $_COOKIE['remember_me']]);

            if ($user) {
                // Если пользователь найден по токену, создаем сессию и перенаправляем на страницу фильмов
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                Route::redirect('/films');
                return;
            }
        }

        // Проверка на ошибки в email и пароле
        $errors = [];

        if (empty($_POST['email'])) {
            $errors['email'] = 'Электронная почта обязательна.';
        }

        if (empty($_POST['password'])) {
            $errors['password'] = 'Пароль обязателен.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            Route::redirect('/login');
            return;
        }

        // Ищем пользователя в базе данных по email
        $sql = "SELECT * FROM users WHERE email = :email";
        $user = $this->db->fetch($sql, ['email' => $_POST['email']]);

        // Если пользователь не найден
        if (!$user) {
            $_SESSION['errors']['email'] = 'Пользователь с таким email не найден.';
            Route::redirect('/login');
            return;
        }

        // Проверка пароля
        if (!password_verify($_POST['password'], $user['password'])) {
            $_SESSION['errors']['password'] = 'Неверный пароль.';
            Route::redirect('/login');
            return;
        }

        // Если пользователь успешно авторизован, создаем сессию
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Если выбрана опция "Запомнить меня", создаем новый токен и устанавливаем куки
        if (isset($_POST['remember'])) {
            $rememberMeToken = bin2hex(random_bytes(16)); // Генерация уникального токена
            setcookie('remember_me', $rememberMeToken, [
                'expires' => time() + (86400 * 30), // Кука на 30 дней
                'path' => '/',
                'httponly' => true,       // Кука недоступна через JavaScript
                'secure' => isset($_SERVER['HTTPS']), // Только по HTTPS
                'samesite' => 'Strict',   // Защита от CSRF-атак
            ]);

            // Обновляем токен в базе данных
            $sql = "UPDATE users SET remember_me_token = :token WHERE id = :id";
            $this->db->execute($sql, [
                'token' => $rememberMeToken,
                'id' => $user['id']
            ]);
        }

        // Перенаправляем пользователя на главную страницу
        $_SESSION['message'] = 'Вы успешно вошли. Добро пожаловать на сайт!';

        Route::redirect('/films');

    }


    public function logout()
    {
        // Удаляем токен из базы данных, если куки "remember_me" существуют
        if (isset($_COOKIE['remember_me'])) {
            $sql = "UPDATE users SET remember_me_token = NULL WHERE remember_me_token = :token";
            $this->db->execute($sql, ['token' => $_COOKIE['remember_me']]);
        }

        // Завершаем сессию
        session_destroy();

        // Удаляем куки
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, "/");
        }

        // Перенаправляем на главную страницу без фильтрации
        Route::redirect('/');
    }


}

