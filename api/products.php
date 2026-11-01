<?php

declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

$ttl = 600;
$started = microtime(true);
$isValid = isset($_SESSION['products'], $_SESSION['products_cached_at'])
    && time() - $_SESSION['products_cached_at'] < $ttl;

if ($isValid) {
    $products = $_SESSION['products'];
    $source = 'кеш сесії';
} else {
    sleep(2);
    $names = ['Ноутбук', 'Монітор', 'Клавіатура', 'Миша', 'Навушники', 'SSD', 'Вебкамера', 'Маршрутизатор'];
    $products = [];
    foreach ($names as $index => $name) {
        $products[] = ['id' => $index + 1, 'name' => $name, 'price' => mt_rand(500, 50000)];
    }
    $_SESSION['products'] = $products;
    $_SESSION['products_cached_at'] = time();
    $source = 'нова генерація';
}

echo json_encode([
    'success' => true,
    'source' => $source,
    'elapsed' => number_format(microtime(true) - $started, 4),
    'products' => $products
], JSON_UNESCAPED_UNICODE);
