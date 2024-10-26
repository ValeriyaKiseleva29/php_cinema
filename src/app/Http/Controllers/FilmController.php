<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;
use App\RMVC\Database\DB;

class FilmController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function index()
    {
        // Проверяем, есть ли параметры фильтрации или поиска
        $searchTerm = $_GET['query'] ?? '';
        $sort = $_GET['sort'] ?? null; // Сортировка не задана по умолчанию

        // Если сортировка не задана, используем порядок по умолчанию (как загружено в БД)
        $orderBy = '';
        if ($sort === 'title') {
            $orderBy = 'CASE
                        WHEN title REGEXP "^[А-Яа-я]" THEN 1
                        WHEN title REGEXP "^[A-Za-z]" THEN 2
                        ELSE 3
                    END, title ASC';
        } elseif ($sort === 'year') {
            $orderBy = 'year DESC';
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $filmsPerPage = 16;
        $offset = ($page - 1) * $filmsPerPage;

        $partialSearchTerm = '%' . $searchTerm . '%';

        // Выполняем запрос с сортировкой, если она задана
        $query = "SELECT * FROM movies WHERE title LIKE :searchTerm";
        if ($orderBy) {
            $query .= " ORDER BY $orderBy";
        }
        $query .= " LIMIT $filmsPerPage OFFSET $offset";

        $films = $this->db->fetchAll($query, ['searchTerm' => $partialSearchTerm]);

        // Подсчет общего количества фильмов для пагинации
        $totalFilms = $this->db->fetchColumn("SELECT COUNT(*) FROM movies WHERE title LIKE :searchTerm", ['searchTerm' => $partialSearchTerm]);
        $totalPages = ceil($totalFilms / $filmsPerPage);

        return View::view('film.filmIndex', [
            'films' => $films,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'query' => $searchTerm,
            'sort' => $sort
        ]);
    }




    public function show($filmId)
    {
        // Получаем фильм по ID
        $film = $this->db->fetch("SELECT * FROM movies WHERE id = :id", ['id' => $filmId]);

        // Если фильм не найден, перенаправляем пользователя
        if (!$film) {
            Route::redirect('/films');
            return;
        }

        // Проверка, авторизован ли пользователь
        $user_id = $_SESSION['user_id'] ?? null;
        $is_favorite = false;

        // Если пользователь авторизован, проверяем, добавлен ли фильм в избранное
        if ($user_id) {
            $sql = "SELECT COUNT(*) FROM favorites WHERE user_id = :user_id AND movie_id = :movie_id";
            $is_favorite = $this->db->fetchColumn($sql, [
                    'user_id' => $user_id,
                    'movie_id' => $filmId
                ]) > 0;
        }

        // Передаем данные о фильме и флаг is_favorite в представление
        return View::view('film.filmShow', compact('film', 'is_favorite'));
    }
}
