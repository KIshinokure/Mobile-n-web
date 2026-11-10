<?php
/**
 * Подключение к MySQL через расширение mysqli (процедурный стиль, как в методичке).
 * Параметры можно переопределить переменными окружения.
 */
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'news_app';
$DB_PASS = getenv('DB_PASS') ?: 'news_app_pass';
$DB_NAME = getenv('DB_NAME') ?: 'news_portal';

function db(): mysqli
{
    static $link = null;
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;

    if ($link === null) {
        mysqli_report(MYSQLI_REPORT_OFF);
        /* 1. Подключение к серверу MySQL */
        $link = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

        /* 2. Проверка, что подключение прошло успешно */
        if (!$link) {
            http_response_code(500);
            exit('Ошибка подключения к базе данных. Код ошибки: ' . mysqli_connect_error());
        }

        /* 3. Сразу задаём кодировку обмена данными, иначе кириллица превратится в «???» */
        mysqli_set_charset($link, 'utf8mb4');
    }
    return $link;
}

/**
 * Идентификатор посетителя: хранится в cookie, нужен, чтобы один человек
 * не мог поставить новости больше одного лайка.
 */
function visitor_id(): string
{
    if (!empty($_COOKIE['visitor_id']) && preg_match('/^[a-f0-9]{32}$/', $_COOKIE['visitor_id'])) {
        return $_COOKIE['visitor_id'];
    }
    $id = bin2hex(random_bytes(16));
    setcookie('visitor_id', $id, ['expires' => time() + 365 * 24 * 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
    $_COOKIE['visitor_id'] = $id;
    return $id;
}

/** Все новости + флаг «лайкнул ли текущий посетитель» */
function fetch_news(string $visitor): array
{
    $sql = 'SELECT n.id, n.title, n.body, n.author, n.created_at, n.likes_count,
                   (l.visitor_id IS NOT NULL) AS liked
              FROM news n
         LEFT JOIN news_likes l ON l.news_id = n.id AND l.visitor_id = ?
          ORDER BY n.created_at DESC';
    $stmt = mysqli_prepare(db(), $sql);
    mysqli_stmt_bind_param($stmt, 's', $visitor);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    /* mysqli_fetch_all сразу возвращает двумерный массив всех записей */
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $rows;
}
