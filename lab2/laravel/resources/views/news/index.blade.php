@extends('layout')

@section('title', 'Лента новостей')

@section('content')
    <h1>Лента новостей</h1>
    <p class="meta">Маршрут: <span class="route">GET /news</span> → NewsController@index</p>

    @foreach ($news as $item)
        <div class="card">
            <h2><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h2>
            <div class="meta">{{ $item->author }} · {{ $item->created_at->format('d.m.Y H:i') }}</div>
            <p>{{ $item->body }}</p>
            @php($isLiked = in_array($item->id, $liked))
            <button class="like {{ $isLiked ? 'liked' : '' }}" data-like="{{ route('news.like', $item) }}">
                {{ $isLiked ? '♥ Вам нравится' : '♡ Нравится' }}
            </button><span class="count">{{ $item->likes_count }}</span>
        </div>
    @endforeach
@endsection
