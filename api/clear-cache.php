<?php

declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

$cacheFile = dirname(__DIR__) . '/cache/report.json';
$fileCleared = !is_file($cacheFile) || unlink($cacheFile);
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

echo json_encode([
    'success' => $fileCleared,
    'message' => $fileCleared ? 'Файловий кеш і сесію очищено.' : 'Сесію очищено, але файл кешу видалити не вдалося.'
], JSON_UNESCAPED_UNICODE);
