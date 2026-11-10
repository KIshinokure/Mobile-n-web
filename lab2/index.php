<?php
require __DIR__ . '/db.php';

$visitor = visitor_id();
$news    = fetch_news($visitor);

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Новости — ЛР 2, вариант 5</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="top">
    <h1>Новости факультета</h1>
    <p>Лабораторная работа 2 · вариант 5 · счётчик «Like» на AJAX</p>
</header>

<main class="feed">
    <?php foreach ($news as $item): ?>
        <article class="news" data-id="<?= (int) $item['id'] ?>">
            <h2><?= e($item['title']) ?></h2>
            <div class="meta"><?= e($item['author']) ?> · <?= date('d.m.Y H:i', strtotime($item['created_at'])) ?></div>
            <p><?= e($item['body']) ?></p>
            <div class="like-bar">
                <button type="button"
                        class="like-btn<?= $item['liked'] ? ' liked' : '' ?>"
                        aria-pressed="<?= $item['liked'] ? 'true' : 'false' ?>">
                    <span class="heart">♥</span>
                    <span class="label"><?= $item['liked'] ? 'Вам нравится' : 'Нравится' ?></span>
                </button>
                <span class="count" title="Количество лайков"><?= (int) $item['likes_count'] ?></span>
            </div>
        </article>
    <?php endforeach; ?>
</main>

<div id="toast" class="toast" hidden></div>
<script src="app.js"></script>
</body>
</html>
