<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;

class FilmController extends Controller {
    public function index() {
        return View::view('film.filmIndex');

    }

    public function show($film) {
        return View::view('film.filmShow', compact('film'));
    }

}
