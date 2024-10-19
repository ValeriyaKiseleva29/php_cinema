<?php


use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\PostController;
use App\RMVC\Route\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;

//Route::get('/posts', [PostController::class, 'index'])->name('posts.index')->middleware('auth');
//Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
//Route::get('/posts/{post}/', [PostController::class, 'show'])->name('posts.show')->middleware('auth');

Route::get('/films', [FilmController::class, 'index'])->name('films.index')->middleware('auth');;
Route::get('/films/{film}', [FilmController::class, 'show'])->name('films.show')->middleware('auth');;
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index')->middleware('auth');;

Route::get('/auth', [AuthController::class, 'index'])->name('auth.index');
Route::post('/auth', [AuthController::class, 'store'])->name('auth.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

//Route::get('/import-films', [Controller::class, 'getAllFilms']);
