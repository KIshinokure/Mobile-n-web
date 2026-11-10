<?php
/**
 * AJAX-обработчик счётчика «Like».
 *   POST news_id=<id>&action=like    — увеличить счётчик
 *   POST news_id=<id>&action=unlike  — уменьшить счётчик
 * Ответ: JSON {"ok":true,"news_id":1,"likes":5,"liked":true}
 */
require __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

function reply(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    reply(405, ['ok' => false, 'error' => 'Разрешён только метод POST']);
}

$newsId  = filter_input(INPUT_POST, 'news_id', FILTER_VALIDATE_INT);
$action  = $_POST['action'] ?? '';
$visitor = visitor_id();

if (!$newsId || !in_array($action, ['like', 'unlike'], true)) {
    reply(400, ['ok' => false, 'error' => 'Неверные параметры запроса']);
}

$link = db();
mysqli_begin_transaction($link);

try {
    // Блокируем строку новости, чтобы параллельные клики не испортили счётчик
    $stmt = mysqli_prepare($link, 'SELECT id FROM news WHERE id = ? FOR UPDATE');
    mysqli_stmt_bind_param($stmt, 'i', $newsId);
    mysqli_stmt_execute($stmt);
    if (!mysqli_stmt_get_result($stmt)->fetch_assoc()) {
        mysqli_rollback($link);
        reply(404, ['ok' => false, 'error' => 'Новость не найдена']);
    }

    if ($action === 'like') {
        // INSERT IGNORE: повторный лайк того же посетителя ничего не изменит
        $stmt = mysqli_prepare($link, 'INSERT IGNORE INTO news_likes (news_id, visitor_id) VALUES (?, ?)');
        mysqli_stmt_bind_param($stmt, 'is', $newsId, $visitor);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) === 1) {
            $stmt = mysqli_prepare($link, 'UPDATE news SET likes_count = likes_count + 1 WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'i', $newsId);
            mysqli_stmt_execute($stmt);
        }
    } else {
        $stmt = mysqli_prepare($link, 'DELETE FROM news_likes WHERE news_id = ? AND visitor_id = ?');
        mysqli_stmt_bind_param($stmt, 'is', $newsId, $visitor);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) === 1) {
            $stmt = mysqli_prepare($link, 'UPDATE news SET likes_count = likes_count - 1 WHERE id = ? AND likes_count > 0');
            mysqli_stmt_bind_param($stmt, 'i', $newsId);
            mysqli_stmt_execute($stmt);
        }
    }

    $stmt = mysqli_prepare($link, 'SELECT likes_count FROM news WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $newsId);
    mysqli_stmt_execute($stmt);
    $likes = (int) mysqli_stmt_get_result($stmt)->fetch_assoc()['likes_count'];

    mysqli_commit($link);
    reply(200, ['ok' => true, 'news_id' => $newsId, 'likes' => $likes, 'liked' => $action === 'like']);
} catch (Throwable $e) {
    mysqli_rollback($link);
    reply(500, ['ok' => false, 'error' => 'Ошибка БД: ' . $e->getMessage()]);
}
