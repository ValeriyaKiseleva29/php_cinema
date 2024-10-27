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

    // Показ страницы профиля
    public function show()
    {
        // Проверка на авторизацию пользователя
        if (!isset($_SESSION['user_id'])) {
            Route::redirect('/login');
            return;
        }
        // Получаем данные пользователя из базы по ID
        $sql = "SELECT * FROM users WHERE id = :id";
        $user = $this->db->fetch($sql, ['id' => $_SESSION['user_id']]);

        // Передаем данные пользователя в шаблон
        include __DIR__ . '/../../../resources/views/profile/show.php';
    }

    // Обновление почты и пароля
    public function update()
    {
        if (!isset($_SESSION['user_id'])) {
            Route::redirect('/login');
            return;
        }
        $errors = [];

        // Валидация нового email
        if (empty($_POST['email'])) {
            $errors['email'] = 'Электронная почта обязательна.';
        } elseif (!$this->emailValidator->validate($_POST['email'])) {
            $errors['email'] = 'Неверный формат электронной почты.';
        }

        // Валидация нового пароля
        if (empty($_POST['password'])) {
            $errors['password'] = 'Пароль обязателен.';
        } elseif (!$this->passwordValidator->validate($_POST['password'])) {
            $errors['password'] = 'Пароль должен содержать не менее 8 символов и хотя бы одну цифру.';
        }

        // Проверка на совпадение паролей
        if (empty($_POST['confirm_password'])) {
            $errors['confirm_password'] = 'Подтверждение пароля обязательно.';
        } elseif ($_POST['password'] !== $_POST['confirm_password']) {
            $errors['confirm_password'] = 'Пароли не совпадают.';
        }

        // Если есть ошибки, сохраняем их в сессии и возвращаем пользователя на форму профиля
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /profile');
            exit;
        }

        // Обновляем email и пароль
        $sql = "UPDATE users SET email = :email, password = :password WHERE id = :id";
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $this->db->execute($sql, [
            'email' => $_POST['email'],
            'password' => $hashedPassword,
            'id' => $_SESSION['user_id'],
        ]);

        // Уведомление об успешном обновлении
        $_SESSION['message'] = 'Профиль успешно обновлен.';

        // Очистка ошибок после обновления
        unset($_SESSION['errors']);

        header('Location: /profile');
        exit;
    }


    // Загрузка аватара
    public function uploadAvatar()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        // Проверяем, был ли загружен файл
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['errors']['avatar'] = 'Ошибка при загрузке файла.';
            header('Location: /profile');
            exit;
        }

        // Валидация файла
        $fileType = $_FILES['avatar']['type'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['errors']['avatar'] = 'Допустимы только файлы форматов jpeg, png или gif.';
            header('Location: /profile');
            exit;
        }

        // Генерация уникального имени для файла
        $avatarName = $_SESSION['user_id'] . '_avatar_' . time() . '.' . pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

        // Сохранение файла в папку public/avatars
        $destination = '/var/www/src/public/avatars/' . $avatarName;
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
            $_SESSION['errors']['avatar'] = 'Не удалось сохранить файл.';
            header('Location: /profile');
            exit;
        }

        // Обновляем путь к аватару в базе данных
        $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
        $this->db->execute($sql, ['avatar' => $avatarName, 'id' => $_SESSION['user_id']]);

        // Уведомление об успешной загрузке
        $_SESSION['message'] = 'Аватар успешно загружен.';
        header('Location: /profile');
        exit;
    }
}
