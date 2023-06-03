<?php
session_start();

$host = 'localhost';
$db   = 'u117094580_ata12';
$user = 'u117094580_484508';
$pass = 'Thebeetle12';
$charset = 'utf8mb4';

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




// Veritabanına bağlandıktan sonra PDO nesnesini kullanarak sorguları gerçekleştirebilirsiniz.
