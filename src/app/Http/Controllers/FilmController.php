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

        // Получаем все комментарии для этого фильма
        $comments = $this->db->fetchAll("SELECT comments.id, comments.comment, comments.created_at, users.username, users.avatar, comments.user_id 
                                 FROM comments 
                                 JOIN users ON comments.user_id = users.id 
                                 WHERE movie_id = :movie_id 
                                 ORDER BY comments.created_at DESC",
            ['movie_id' => $filmId]);



        // Передаем данные о фильме, избранном и комментариях в представление
        return View::view('film.filmShow', compact('film', 'is_favorite', 'comments'));
    }


    public function addComment()
    {
        // Проверка на авторизацию пользователя
        if (!isset($_SESSION['user_id'])) {
            Route::redirect('/login');
            return;
        }

        // Получаем данные из формы
        $userId = $_POST['user_id'];
        $filmId = $_POST['film_id'];
        $commentText = isset($_POST['comment']) ? htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8') : '';

        // Проверяем, что комментарий не пустой
        if (empty($commentText)) {
            Route::redirect('/films/' . $filmId);
            return;
        }

        // Вставляем комментарий в базу данных
        $sql = "INSERT INTO comments (user_id, movie_id, comment, created_at) 
            VALUES (:user_id, :film_id, :comment, NOW())";

        $params = [
            'user_id' => $userId,
            'film_id' => $filmId,
            'comment' => $commentText
        ];

        $this->db->execute($sql, $params);

        // Перенаправляем обратно на страницу фильма
        Route::redirect('/films/' . $filmId);
    }
    public function deleteComment()
    {
        // Проверка на авторизацию пользователя
        if (!isset($_SESSION['user_id'])) {
            Route::redirect('/login');
            return;
        }

        $comment_id = $_POST['comment_id'];

        // Удаляем комментарий из базы данных
        $sql = "DELETE FROM comments WHERE id = :id AND user_id = :user_id";
        $this->db->execute($sql, ['id' => $comment_id, 'user_id' => $_SESSION['user_id']]);

        // Возвращаем успешный ответ
        echo json_encode(['success' => true]);
        exit;
    }
}
