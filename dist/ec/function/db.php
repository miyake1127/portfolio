<?php

const DB_HOST = 'mysql:dbname=LAA1436007-ec1;host=mysql206.phy.lolipop.lan';
const DB_USER = 'LAA1436007';
const DB_PASSWORD = 'kantan1127';

try {
    $pdo = new PDO(DB_HOST,DB_USER,DB_PASSWORD,[
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES =>false
    ]);
} catch (PDOException $e) {
    echo '接続失敗'.$e->getMessage()."\n";
    exit();
}