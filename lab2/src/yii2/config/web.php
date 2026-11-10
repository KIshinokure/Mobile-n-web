<?php
/**
 * ЛР 2, задание 3. Конфигурация веб-приложения Yii2 (на основе yii2-app-basic).
 * Главное здесь — компонент urlManager с маршрутами проекта.
 */
$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

return [
    'id'           => 'news-like-yii2',
    'name'         => 'Yii2 · Новости',
    'basePath'     => dirname(__DIR__),
    'language'     => 'ru-RU',
    'timeZone'     => 'Europe/Moscow',
    'bootstrap'    => ['log'],
    'defaultRoute' => 'news/index',
    'aliases'      => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'lab2-variant5-news-like-yii2-key',
        ],
        'cache' => ['class' => 'yii\caching\FileCache'],
        'formatter' => ['defaultTimeZone' => 'Europe/Moscow'],
        'errorHandler' => ['errorAction' => 'news/error'],
        'log' => [
            'traceLevel' => 0,
            'targets' => [['class' => 'yii\log\FileTarget', 'levels' => ['error', 'warning']]],
        ],
        'db' => $db,

        // Маршрутизация: «красивые» URL без index.php и ?r=
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => true,
            'rules' => [
                ''                                  => 'news/index',
                'news'                              => 'news/index',
                'news/<id:\d+>'                     => 'news/view',
                'POST news/<id:\d+>/like'           => 'news/like',
                'DELETE news/<id:\d+>/like'         => 'news/unlike',
                'GET news/<id:\d+>/likes'           => 'news/likes',
                'about'                             => 'news/about',
                'routes'                            => 'news/routes',
            ],
        ],
    ],
    'params' => $params,
];
