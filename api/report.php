<?php

declare(strict_types=1);
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');

$cacheFile = dirname(__DIR__) . '/cache/report.json';
$ttl = 600;
$started = microtime(true);

if (is_file($cacheFile) && time() - filemtime($cacheFile) < $ttl) {
    $rows = json_decode((string) file_get_contents($cacheFile), true);
    $source = 'файловий кеш';
} else {
    sleep(3);
    $names = ['Анна', 'Богдан', 'Вікторія', 'Дмитро', 'Ірина', 'Максим', 'Олена', 'Сергій'];
    $rows = [];
    for ($i = 1; $i <= 1000; $i++) {
        $rows[] = [
            'id' => $i,
            'name' => $names[array_rand($names)],
            'amount' => number_format(mt_rand(10000, 250000) / 100, 2, '.', ' '),
            'date' => date('d.m.Y', strtotime('-' . mt_rand(0, 365) . ' days'))
        ];
    }
    file_put_contents($cacheFile, json_encode($rows, JSON_UNESCAPED_UNICODE), LOCK_EX);
    $source = 'нова генерація';
}

echo json_encode([
    'success' => true,
    'source' => $source,
    'elapsed' => number_format(microtime(true) - $started, 4),
    'rows' => $rows
], JSON_UNESCAPED_UNICODE);
