<?php

namespace App\Http\Controllers;

use App\RMVC\Route\Route;
use App\RMVC\View\View;

class FavoriteController extends Controller {
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            Route::redirect('/login');
            return;
        }

        return View::view('film.favoriteIndex');
    }

}
