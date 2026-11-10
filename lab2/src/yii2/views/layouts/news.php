<?php
/** @var yii\web\View $this */
/** @var string $content */

use yii\helpers\Html;
use yii\helpers\Url;

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> — Yii2</title>
    <style>
        body { margin: 0; font-family: "Segoe UI", "DejaVu Sans", Arial, sans-serif; background: #f2f6f2; color: #222; }
        nav { background: #2c7a3f; padding: 14px 28px; display: flex; gap: 20px; }
        nav a { color: #fff; text-decoration: none; font-weight: 600; }
        nav .brand { margin-right: auto; font-size: 18px; }
        main { max-width: 760px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 10px; padding: 16px 20px; margin-bottom: 14px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .card h2 { margin: 0 0 4px; font-size: 19px; }
        .card h2 a { color: #222; text-decoration: none; }
        .meta { color: #777; font-size: 13px; }
        .like { border: 1px solid #bbb; background: #fff; border-radius: 18px; padding: 5px 14px; cursor: pointer; }
        .like.liked { background: #e3f5e7; border-color: #2c7a3f; color: #1e5c2e; }
        .count { font-weight: 700; margin-left: 8px; }
        .route { font-family: monospace; background: #e7ece7; padding: 2px 6px; border-radius: 4px; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        td, th { border: 1px solid #ccd; padding: 6px 10px; text-align: left; font-family: monospace; }
    </style>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<nav>
    <a class="brand" href="<?= Url::to(['news/index']) ?>">Yii2 · Новости</a>
    <a href="<?= Url::to(['news/index']) ?>">Лента</a>
    <a href="<?= Url::to(['news/routes']) ?>">Маршруты</a>
    <a href="<?= Url::to(['news/about']) ?>">О проекте</a>
</nav>
<main><?= $content ?></main>
<script>
    document.querySelectorAll('[data-like]').forEach(btn => {
        btn.addEventListener('click', async () => {
            const liked = btn.classList.contains('liked');
            const resp = await fetch(btn.dataset.like, {
                method: liked ? 'DELETE' : 'POST',
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name=csrf-token]').content,
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
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
