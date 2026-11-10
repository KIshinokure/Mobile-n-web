<?php
/**
 * AJAX: актуальные значения счётчиков всех новостей (страница опрашивает его,
 * чтобы видеть лайки других посетителей без перезагрузки).
 */
require __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

$rows = fetch_news(visitor_id());
$out = array_map(fn($r) => [
    'id'    => (int) $r['id'],
    'likes' => (int) $r['likes_count'],
    'liked' => (bool) $r['liked'],
], $rows);

echo json_encode(['ok' => true, 'news' => $out], JSON_UNESCAPED_UNICODE);
