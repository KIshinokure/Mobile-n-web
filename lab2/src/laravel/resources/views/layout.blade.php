<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Новости') — Laravel</title>
    <style>
        body { margin: 0; font-family: "Segoe UI", "DejaVu Sans", Arial, sans-serif; background: #f4f4f6; color: #222; }
        nav { background: #f05340; padding: 14px 28px; display: flex; gap: 20px; align-items: center; }
        nav a { color: #fff; text-decoration: none; font-weight: 600; }
        nav .brand { font-size: 18px; margin-right: auto; }
        main { max-width: 760px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 10px; padding: 16px 20px; margin-bottom: 14px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .card h2 { margin: 0 0 4px; font-size: 19px; }
        .card h2 a { color: #222; text-decoration: none; }
        .meta { color: #777; font-size: 13px; }
        .like { border: 1px solid #ccc; background: #fff; border-radius: 18px; padding: 5px 14px; cursor: pointer; }
        .like.liked { background: #ffe8e4; border-color: #f05340; color: #c0321f; }
        .count { font-weight: 700; margin-left: 8px; }
        .route { font-family: monospace; background: #eee; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
<nav>
    <a class="brand" href="{{ route('home') }}">Laravel · Новости</a>
    <a href="{{ route('news.index') }}">Лента</a>
    <a href="{{ route('about') }}">О проекте</a>
</nav>
<main>
    @yield('content')
</main>
<script>
    // AJAX: POST /news/{id}/like — поставить лайк, DELETE /news/{id}/like — снять
    document.querySelectorAll('[data-like]').forEach(btn => {
        btn.addEventListener('click', async () => {
            const liked = btn.classList.contains('liked');
            const resp = await fetch(btn.dataset.like, {
                method: liked ? 'DELETE' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                }
            });
            const data = await resp.json();
            btn.classList.toggle('liked', data.liked);
            btn.textContent = data.liked ? '♥ Вам нравится' : '♡ Нравится';
            btn.nextElementSibling.textContent = data.likes;
        });
    });
</script>
</body>
</html>
