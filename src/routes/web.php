<?php


use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\PostController;
use App\RMVC\Route\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;

//Route::get('/posts', [PostController::class, 'index'])->name('posts.index')->middleware('auth');
//Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
//Route::get('/posts/{post}/', [PostController::class, 'show'])->name('posts.show')->middleware('auth');

Route::get('/films', [FilmController::class, 'index'])->name('films.index');
//Route::get('/search', [FilmController::class, 'search'])->name('films.index');
Route::get('/films/{film}', [FilmController::class, 'show'])->name('films.show');
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index')->middleware('auth');;
Route::post('/favorites/add', [FavoriteController::class, 'add'])->middleware('auth');
// Маршрут для удаления фильма из избранного
Route::post('/favorites/remove', [FavoriteController::class, 'remove'])->middleware('auth');



Route::get('/auth', [AuthController::class, 'index'])->name('auth.index');
Route::post('/auth', [AuthController::class, 'store'])->name('auth.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/logout_confirmation', [AuthController::class, 'showLogoutConfirmation'])->name('logout.confirmation');

Route::get('/update-movies', [Controller::class, 'updateMovies']);

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.uploadAvatar');

//Route::get('/import-films', [Controller::class, 'getAllFilms']);

