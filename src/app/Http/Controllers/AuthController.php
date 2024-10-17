<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;

class AuthController extends Controller
{
    public function index()
    {
        return View::view('auth.index');
    }
    public function showLoginForm()
    {
        return View::view('auth.login');
    }



    public function store()
    {
        $errors = [];


        if (empty($_POST['username'])) {
            $errors[] = 'Имя пользователя обязательно.';
        }


        if (empty($_POST['email'])) {
            $errors[] = 'Электронная почта обязательна.';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Неверный формат электронной почты.';
        }


        if (empty($_POST['password'])) {
            $errors[] = 'Пароль обязателен.';
        } elseif (strlen($_POST['password']) < 6) {
            $errors[] = 'Пароль должен содержать не менее 6 символов.';
        }


        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            Route::redirect('/auth');
            return;
        }


        $_SESSION['user'] = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
        ];

        $_SESSION['message'] = 'Регистрация успешна!';
        Route::redirect('/auth');
    }
    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $errors = [];

        // Пример валидации email
        if (empty($email)) {
            $errors[] = 'Email обязателен.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Неверный формат email.';
        }

        // Пример валидации пароля
        if (empty($password)) {
            $errors[] = 'Пароль обязателен.';
        }

        // Проверка, если ошибок нет, выполняем логику входа
        if (empty($errors)) {
            // Ваша логика для проверки email и пароля
            // Здесь можно подключить базу данных, чтобы найти пользователя и проверить пароль

            // Например, проверим данные для демонстрации:
            if ($email === 'user@example.com' && $password === 'password123') {
                $_SESSION['user'] = ['email' => $email];
                Route::redirect('/');  // Успешный вход, перенаправление на главную страницу
            } else {
                $errors[] = 'Неверный email или пароль.';
            }
        }

        // Если есть ошибки, сохраняем их и возвращаем пользователя на страницу логина
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            Route::redirect('/login');
        }
    }


}
