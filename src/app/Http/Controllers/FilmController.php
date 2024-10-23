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
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $filmsPerPage = 16;
        $offset = ($page - 1) * $filmsPerPage;

        $films = $this->db->fetchAll(
            "SELECT * FROM movies LIMIT $filmsPerPage OFFSET $offset"
        );
        $totalFilms = $this->db->fetch("SELECT COUNT(*) as total FROM movies")['total'];
        $totalPages = ceil($totalFilms / $filmsPerPage);

        return View::view('film.filmIndex', [
            'films' => $films,
            'currentPage' => $page,
            'totalPages' => $totalPages

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
