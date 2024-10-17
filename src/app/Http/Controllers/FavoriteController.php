<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;

class FavoriteController extends Controller {
    public function index() {
        return View::view('film.favoriteIndex');

    }
}
