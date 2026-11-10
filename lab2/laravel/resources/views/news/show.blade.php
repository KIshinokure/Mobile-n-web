@extends('layout')

@section('title', $item->title)

@section('content')
    <p class="meta">Маршрут: <span class="route">GET /news/{{ $item->id }}</span> → NewsController@show</p>
    <div class="card">
        <h2>{{ $item->title }}</h2>
        <div class="meta">{{ $item->author }} · {{ $item->created_at->format('d.m.Y H:i') }}</div>
        <p>{{ $item->body }}</p>
        <button class="like {{ $liked ? 'liked' : '' }}" data-like="{{ route('news.like', $item) }}">
            {{ $liked ? '♥ Вам нравится' : '♡ Нравится' }}
        </button><span class="count">{{ $item->likes_count }}</span>
    </div>
    <p><a href="{{ route('news.index') }}">← ко всем новостям</a></p>
@endsection
