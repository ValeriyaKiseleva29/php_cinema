<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;
use App\Validation\StringValidator;
use App\Validation\NumberValidator;
use App\Validation\EmailValidator;
use App\Validation\PasswordValidator;
use App\RMVC\Database\DB;


class AuthController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        return $this->render('auth.index');
    }

    public function showLoginForm()
    {
        return $this->render('auth.login');
    }
    public function showLogoutConfirmation()
    {
        return $this->render('film.partials.logout_confirmation');
    }


    public function store()
    {
        $errors = [];
        $stringValidator = new StringValidator();
        $emailValidator = new EmailValidator();
        $passwordValidator = new PasswordValidator();
        $numberValidator = new NumberValidator();


        if (empty($_POST['username'])) {
            $errors['username'] = 'Имя пользователя обязательно.';
        } elseif (!$stringValidator->validate($_POST['username'])) {
            $errors['username'] = 'Неверный формат имени пользователя. Имя не должно состоять только из цифр';
        }


        if (empty($_POST['email'])) {
            $errors['email'] = 'Электронная почта обязательна.';
        } elseif (!$emailValidator->validate($_POST['email'])) {
            $errors['email'] = 'Неверный формат электронной почты.';
        }


        if (empty($_POST['password'])) {
            $errors['password'] = 'Пароль обязателен.';
        } elseif (!$passwordValidator->validate($_POST['password'])) {
            $errors['password'] = 'Пароль должен содержать не менее 8 символов и хотя бы одну цифру.';
        }


        if (empty($_POST['confirm_password'])) {
            $errors['confirm_password'] = 'Подтвердите пароль.';
        } elseif ($_POST['password'] !== $_POST['confirm_password']) {
            $errors['confirm_password'] = 'Пароли не совпадают.';
        }


        if (empty($_POST['age'])) {
            $errors['age'] = 'Возраст обязателен.';
        } elseif (!$numberValidator->validate($_POST['age'])) {
            $errors['age'] = 'Неверный формат возраста. (Мы не верим, что Вам больше 100 лет)';
        }


        $dob = isset($_POST['dob']) && !empty($_POST['dob']) ? $_POST['dob'] : null;


        $gender = isset($_POST['gender']) && !empty($_POST['gender']) ? $_POST['gender'] : null;


        $interests = isset($_POST['interests']) && !empty($_POST['interests']) ? json_encode($_POST['interests']) : null;

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            Route::redirect('/auth');
            return;
        }


        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);


        $sql = "INSERT INTO users (username, email, password, dob, age, gender, interests) 
            VALUES (:username, :email, :password, :dob, :age, :gender, :interests)";

        $this->db->execute($sql, [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $hashedPassword,
            'dob' => $dob,
            'age' => $_POST['age'],
            'gender' => $gender,
            'interests' => $interests,
        ]);

        $user_id = $this->db->lastInsertId();


        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $_POST['username'];


        if (isset($_POST['remember'])) {
            $rememberMeToken = bin2hex(random_bytes(16));
            setcookie('remember_me', $rememberMeToken, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']),
                'samesite' => 'Strict',
            ]);


            $sql = "UPDATE users SET remember_me_token = :token WHERE id = :id";
            $this->db->execute($sql, [
                'token' => $rememberMeToken,
                'id' => $user_id
            ]);
        }


        $_SESSION['message'] = 'Вы успешно зарегистрировались. Добро пожаловать на сайт!';
        Route::redirect('/films');
    }


    public function login()
    {

        if (isset($_COOKIE['remember_me'])) {
            $sql = "SELECT * FROM users WHERE remember_me_token = :token";
            $user = $this->db->fetch($sql, ['token' => $_COOKIE['remember_me']]);

            if ($user) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                Route::redirect('/films');
                return;
            }
        }


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


        $sql = "SELECT * FROM users WHERE email = :email";
        $user = $this->db->fetch($sql, ['email' => $_POST['email']]);


        if (!$user) {
            $_SESSION['errors']['email'] = 'Пользователь с таким email не найден.';
            Route::redirect('/login');
            return;
        }


        if (!password_verify($_POST['password'], $user['password'])) {
            $_SESSION['errors']['password'] = 'Неверный пароль.';
            Route::redirect('/login');
            return;
        }


        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];


        if (isset($_POST['remember'])) {
            $rememberMeToken = bin2hex(random_bytes(16));
            setcookie('remember_me', $rememberMeToken, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']),
                'samesite' => 'Strict',
            ]);

            // Обновляем токен в базе данных
            $sql = "UPDATE users SET remember_me_token = :token WHERE id = :id";
            $this->db->execute($sql, [
                'token' => $rememberMeToken,
                'id' => $user['id']
            ]);
        }


        $_SESSION['message'] = 'Вы успешно вошли. Добро пожаловать на сайт!';

        Route::redirect('/films');

    }


    public function logout()
    {

        if (isset($_COOKIE['remember_me'])) {
            $sql = "UPDATE users SET remember_me_token = NULL WHERE remember_me_token = :token";
            $this->db->execute($sql, ['token' => $_COOKIE['remember_me']]);
        }


        session_destroy();


        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, "/");
        }


        Route::redirect('/');
    }


}

