<?php

use App\Http\Controllers\Auth\Google\GoogleCallbackController;
use App\Http\Controllers\Auth\Google\GoogleRedirectController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\GalleryPageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'gallery')->name('home');
Route::get('/gallery', GalleryPageController::class)->name('gallery');
Route::view('/projects', 'pages.projects')->name('projects');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'pages.auth.login')->name('login');

    Route::get('/auth/google', GoogleRedirectController::class)->name('auth.google');
    Route::get('/auth/google/callback', GoogleCallbackController::class)->name('auth.google.callback');
});

Route::middleware(['auth'])->group(function () {
    Route::any('logout', LogoutController::class);

    Route::redirect('/admin', '/admin/images')->name('admin');
    Route::view('/admin/images', 'pages.admin.images')->name('admin.images');
    Route::view('/admin/projects', 'pages.admin.projects')->name('admin.projects');
    Route::view('/admin/stories', 'pages.admin.stories')->name('admin.stories');
});

require __DIR__.'/settings.php';
