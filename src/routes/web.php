<?php
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FilmController;
use App\RMVC\Route\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;


Route::get('/films', [FilmController::class, 'index'])->name('films.index');
Route::get('/films/{film}', [FilmController::class, 'show'])->name('films.show');
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index')->middleware('auth');;
Route::post('/favorites/add', [FavoriteController::class, 'add'])->middleware('auth');
Route::post('/favorites/remove', [FavoriteController::class, 'remove'])->middleware('auth');
Route::get('/auth', [AuthController::class, 'index'])->name('auth.index');
Route::post('/auth', [AuthController::class, 'store'])->name('auth.store');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout_confirmation', [AuthController::class, 'showLogoutConfirmation'])->name('logout.confirmation');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show')->middleware('auth');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.uploadAvatar')->middleware('auth');
Route::post('/comments/add', [FilmController::class, 'addComment'])->middleware('auth');
Route::post('/comments/delete', [FilmController::class, 'deleteComment']);



