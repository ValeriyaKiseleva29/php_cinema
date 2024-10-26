<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;
use App\RMVC\Database\DB;

class FavoriteController extends Controller {
    public function index()
    {
        // Проверка на авторизацию пользователя
        if (!isset($_SESSION['user_id'])) {
            Route::redirect('/login');
            return;
        }

        $user_id = $_SESSION['user_id'];

        // SQL-запрос для получения избранных фильмов пользователя
        $sql = "SELECT movies.* FROM favorites 
            JOIN movies ON favorites.movie_id = movies.id
            WHERE favorites.user_id = :user_id";

        // Выполняем запрос через метод fetchAll вашего класса DB
        $db = new DB(); // создаем экземпляр класса DB
        $favorites = $db->fetchAll($sql, ['user_id' => $user_id]);

        return View::view('film.favoriteIndex', compact('favorites'));
    }


    public function add()
    {
        $user_id = $_SESSION['user_id'];
        $movie_id = $_POST['film_id'] ?? null;

        if (!$movie_id) {
            // Если нет movie_id, выводим сообщение об ошибке
            $_SESSION['message'] = 'Не удалось добавить фильм в избранное. Попробуйте позже.';
            Route::redirect('/films');
            return;
        }

        // Проверяем, что фильм еще не добавлен в избранное
        $sql = "SELECT COUNT(*) FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id";
        $db = new DB();
        $exists = $db->fetchColumn($sql, ['user_id' => $user_id, 'movie_id' => $movie_id]);

        if ($exists == 0) {
            // Добавляем фильм в избранное
            $sql = "INSERT INTO favorites (user_id, movie_id, created_at) VALUES (:user_id, :movie_id, NOW())";
            $db->execute($sql, ['user_id' => $user_id, 'movie_id' => $movie_id]);
        }

        Route::redirect("/films/$movie_id");
    }
    public function remove()
    {
        $user_id = $_SESSION['user_id'];
        $movie_id = $_POST['film_id'] ?? null;

        if (!$movie_id) {
            // Если нет movie_id, выводим сообщение об ошибке
            $_SESSION['message'] = 'Не удалось удалить фильм из избранного. Попробуйте позже.';
            Route::redirect('/films');
            return;
        }

        // Удаляем фильм из избранного
        $sql = "DELETE FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id";
        $db = new DB();
        $db->execute($sql, ['user_id' => $user_id, 'movie_id' => $movie_id]);

//        $_SESSION['message'] = 'Фильм удален из избранного.';
        Route::redirect("/films/$movie_id");
    }

}
