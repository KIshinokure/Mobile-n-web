@extends('layout')

@section('title', 'Страница не найдена')

@section('content')
    <div class="card">
        <h2>404 — такой страницы нет</h2>
        <p>Сработал маршрут <span class="route">Route::fallback()</span>.</p>
    </div>
@endsection
