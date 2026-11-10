@extends('layout')

@section('title', 'О проекте')

@section('content')
    <div class="card">
        <h2>О проекте</h2>
        <p>Страница с новостями и счётчиком «Like» (ЛР 2, вариант 5), перенесённая на Laravel.</p>
        <p>Маршруты проекта:</p>
        <ul>
            <li><span class="route">GET /</span> — перенаправление на ленту</li>
            <li><span class="route">GET /news</span> — лента новостей</li>
            <li><span class="route">GET /news/{id}</span> — одна новость</li>
            <li><span class="route">POST /news/{id}/like</span> — поставить лайк (AJAX)</li>
            <li><span class="route">DELETE /news/{id}/like</span> — снять лайк (AJAX)</li>
            <li><span class="route">GET /news/{id}/likes</span> — текущее значение счётчика (JSON)</li>
            <li><span class="route">GET /about</span> — эта страница</li>
        </ul>
    </div>
@endsection
