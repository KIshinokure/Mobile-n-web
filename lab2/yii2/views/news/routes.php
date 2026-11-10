<?php
/** @var yii\web\UrlRuleInterface[] $rules */

use yii\helpers\Html;

$this->title = 'Маршруты';
?>
<h1>Правила urlManager</h1>
<table>
    <tr><th>HTTP-методы</th><th>Шаблон URL</th><th>Маршрут (контроллер/действие)</th></tr>
    <?php foreach ($rules as $rule): ?>
        <tr>
            <td><?= $rule->verb ? Html::encode(implode(', ', $rule->verb)) : 'любой' ?></td>
            <td>/<?= Html::encode($rule->name) ?></td>
            <td><?= Html::encode($rule->route) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
