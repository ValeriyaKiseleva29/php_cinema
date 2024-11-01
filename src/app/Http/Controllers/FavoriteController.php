<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;
use App\RMVC\Database\DB;

class FavoriteController extends Controller {
    public function index()
    {

        if (!isset($_SESSION['user_id'])) {
            Route::redirect('/login');
            return;
        }

        $user_id = $_SESSION['user_id'];


        $sql = "SELECT movies.* FROM favorites 
            JOIN movies ON favorites.movie_id = movies.id
            WHERE favorites.user_id = :user_id";


        $db = new DB();
        $favorites = $db->fetchAll($sql, ['user_id' => $user_id]);

        return View::view('film.favoriteIndex', compact('favorites'));
    }


    public function add()
    {
        $user_id = $_SESSION['user_id'];
        $movie_id = $_POST['film_id'] ?? null;

        if (!$movie_id) {

            $_SESSION['message'] = 'Не удалось добавить фильм в избранное. Попробуйте позже.';
            Route::redirect('/films');
            return;
        }


        $sql = "SELECT COUNT(*) FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id";
        $db = new DB();
        $exists = $db->fetchColumn($sql, ['user_id' => $user_id, 'movie_id' => $movie_id]);

        if ($exists == 0) {
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

            $_SESSION['message'] = 'Не удалось удалить фильм из избранного. Попробуйте позже.';
            Route::redirect('/films');
            return;
        }


        $sql = "DELETE FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id";
        $db = new DB();
        $db->execute($sql, ['user_id' => $user_id, 'movie_id' => $movie_id]);

        Route::redirect("/films/$movie_id");
    }

}
