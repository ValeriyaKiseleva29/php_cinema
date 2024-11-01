<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\Validation\EmailValidator;
use App\Validation\PasswordValidator;
use App\RMVC\Database\DB;

class ProfileController extends Controller
{
    protected $db;
    protected $emailValidator;
    protected $passwordValidator;

    public function __construct()
    {
        $this->db = new DB();
        $this->emailValidator = new EmailValidator();
        $this->passwordValidator = new PasswordValidator();
    }


    public function show()
    {

        if (!isset($_SESSION['user_id'])) {
            Route::redirect('/login');
            return;
        }

        $sql = "SELECT * FROM users WHERE id = :id";
        $user = $this->db->fetch($sql, ['id' => $_SESSION['user_id']]);

        include __DIR__ . '/../../../resources/views/profile/show.php';
    }


    public function update()
    {
        if (!isset($_SESSION['user_id'])) {
            Route::redirect('/login');
            return;
        }
        $errors = [];


        if (empty($_POST['email'])) {
            $errors['email'] = 'Электронная почта обязательна.';
        } elseif (!$this->emailValidator->validate($_POST['email'])) {
            $errors['email'] = 'Неверный формат электронной почты.';
        }


        if (empty($_POST['password'])) {
            $errors['password'] = 'Пароль обязателен.';
        } elseif (!$this->passwordValidator->validate($_POST['password'])) {
            $errors['password'] = 'Пароль должен содержать не менее 8 символов и хотя бы одну цифру.';
        }


        if (empty($_POST['confirm_password'])) {
            $errors['confirm_password'] = 'Подтверждение пароля обязательно.';
        } elseif ($_POST['password'] !== $_POST['confirm_password']) {
            $errors['confirm_password'] = 'Пароли не совпадают.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /profile');
            exit;
        }


        $sql = "UPDATE users SET email = :email, password = :password WHERE id = :id";
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $this->db->execute($sql, [
            'email' => $_POST['email'],
            'password' => $hashedPassword,
            'id' => $_SESSION['user_id'],
        ]);


        $_SESSION['message'] = 'Профиль успешно обновлен.';


        unset($_SESSION['errors']);

        header('Location: /profile');
        exit;
    }



    public function uploadAvatar()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['errors']['avatar'] = 'Ошибка при загрузке файла.';
            header('Location: /profile');
            exit;
        }

        $fileType = $_FILES['avatar']['type'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['errors']['avatar'] = 'Допустимы только файлы форматов jpeg, png или gif.';
            header('Location: /profile');
            exit;
        }

        $avatarName = $_SESSION['user_id'] . '_avatar_' . time() . '.' . pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);


        $destination = '/var/www/src/public/avatars/' . $avatarName;
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
            $_SESSION['errors']['avatar'] = 'Не удалось сохранить файл.';
            header('Location: /profile');
            exit;
        }

        $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
        $this->db->execute($sql, ['avatar' => $avatarName, 'id' => $_SESSION['user_id']]);


        $_SESSION['message'] = 'Аватар успешно загружен.';
        header('Location: /profile');
        exit;
    }
}
