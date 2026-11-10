<?php

/*
|--------------------------------------------------------------------------
| ЛР 2, задание 2. Маршруты проекта «Новости со счётчиком Like» на Laravel
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Главная страница -> лента новостей
Route::redirect('/', '/news')->name('home');

// Лента новостей и отдельная новость
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])
    ->whereNumber('news')
    ->name('news.show');

// AJAX: поставить / снять лайк, получить текущее значение счётчика
Route::post('/news/{news}/like', [NewsController::class, 'like'])
    ->whereNumber('news')
    ->name('news.like');
Route::delete('/news/{news}/like', [NewsController::class, 'unlike'])
    ->whereNumber('news')
    ->name('news.unlike');
Route::get('/news/{news}/likes', [NewsController::class, 'likes'])
    ->whereNumber('news')
    ->name('news.likes');

// Статическая страница «О проекте»
Route::get('/about', [PageController::class, 'about'])->name('about');

// Любой неизвестный адрес -> 404 со своей страницей
Route::fallback([PageController::class, 'notFound']);
