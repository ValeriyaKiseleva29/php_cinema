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
}
