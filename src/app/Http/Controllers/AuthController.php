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

        // Перенаправляем пользователя после успешной регистрации
        $_SESSION['message'] = 'Регистрация успешна!';
        Route::redirect('/films');
    }
}
