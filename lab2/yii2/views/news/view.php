<?php
/** @var app\models\News $item */
/** @var bool $liked */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $item->title;
?>
<p class="meta">Маршрут: <span class="route">news/<?= $item->id ?></span> → <span class="route">news/view?id=<?= $item->id ?></span></p>
<div class="card">
    <h2><?= Html::encode($item->title) ?></h2>
    <div class="meta"><?= Html::encode($item->author) ?> · <?= Yii::$app->formatter->asDatetime($item->created_at, 'php:d.m.Y H:i') ?></div>
    <p><?= Html::encode($item->body) ?></p>
    <button class="like <?= $liked ? 'liked' : '' ?>" data-like="<?= Url::to(['news/like', 'id' => $item->id]) ?>">
        <?= $liked ? '♥ Вам нравится' : '♡ Нравится' ?>
    </button><span class="count"><?= (int) $item->likes_count ?></span>
</div>
<p><a href="<?= Url::to(['news/index']) ?>">← ко всем новостям</a></p>
