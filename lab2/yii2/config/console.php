<?php
/** Консольное приложение Yii2 (команды php yii migrate и т.п.) */
$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

return [
    'id'                  => 'news-like-yii2-console',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases'             => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => ['class' => 'yii\caching\FileCache'],
        'log'   => ['targets' => [['class' => 'yii\log\FileTarget', 'levels' => ['error', 'warning']]]],
        'db'    => $db,
    ],
    'params' => $params,
];
