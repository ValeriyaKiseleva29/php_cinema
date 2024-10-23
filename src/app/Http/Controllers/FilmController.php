<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;
use App\RMVC\Database\DB;

class FilmController extends Controller {
    protected $db;

    public function __construct()
    {
        $this->db = new DB();
    }
    public function index()
    {
        $searchTerm = $_GET['query'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $filmsPerPage = 16;
        $offset = ($page - 1) * $filmsPerPage;

        $partialSearchTerm = '%' . $searchTerm . '%';

        $query = "SELECT * FROM movies WHERE title LIKE :searchTerm LIMIT $filmsPerPage OFFSET $offset";

        $films = $this->db->fetchAll($query, [
            'searchTerm' => $partialSearchTerm
        ]);

        $totalFilms = $this->db->fetchColumn("SELECT COUNT(*) FROM movies WHERE title LIKE :searchTerm", ['searchTerm' => $partialSearchTerm]);
        $totalPages = ceil($totalFilms / $filmsPerPage);

        return View::view('film.filmIndex', [
            'films' => $films,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'query' => $searchTerm
        ]);
    }

    public function show($filmId) {

        $film = $this->db->fetch("SELECT * FROM movies WHERE id = :id", ['id' => $filmId]);

        if (!$film) {
            Route::redirect('/films');
            return;
        }
        return View::view('film.filmShow', compact('film'));
    }

}
