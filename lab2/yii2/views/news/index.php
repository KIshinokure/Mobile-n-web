<?php
/** @var app\models\News[] $news */
/** @var int[] $liked */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Лента новостей';
?>
<h1>Лента новостей</h1>
<p class="meta">Маршрут: <span class="route">news</span> → <span class="route">news/index</span></p>

<?php foreach ($news as $item): $isLiked = in_array($item->id, $liked); ?>
    <div class="card">
        <h2><a href="<?= Url::to(['news/view', 'id' => $item->id]) ?>"><?= Html::encode($item->title) ?></a></h2>
        <div class="meta"><?= Html::encode($item->author) ?> · <?= Yii::$app->formatter->asDatetime($item->created_at, 'php:d.m.Y H:i') ?></div>
        <p><?= Html::encode($item->body) ?></p>
        <button class="like <?= $isLiked ? 'liked' : '' ?>" data-like="<?= Url::to(['news/like', 'id' => $item->id]) ?>">
            <?= $isLiked ? '♥ Вам нравится' : '♡ Нравится' ?>
        </button><span class="count"><?= (int) $item->likes_count ?></span>
    </div>
<?php endforeach; ?>
