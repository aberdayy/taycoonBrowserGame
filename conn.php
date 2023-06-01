<?php
session_start();

$host = 'localhost';
$db   = 'game';
$user = 'root';
$pass = 'admin';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
function aktivasyonKoduUret($uzunluk = 16) {
    $karakterler = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $kod = '';

    for ($i = 0; $i < $uzunluk; $i++) {
        $rastgeleIndex = rand(0, strlen($karakterler) - 1);
        $kod .= $karakterler[$rastgeleIndex];
    }

    return $kod;
}




// Veritabanına bağlandıktan sonra PDO nesnesini kullanarak sorguları gerçekleştirebilirsiniz.
