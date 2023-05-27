<?php 
require("./conn.php");

$sirket = $_GET["sirket"];
$isci = $_GET["isci"];



// Isci alma eylemi 
$query = $pdo->prepare("UPDATE isci SET sahip_sirket_id = ? WHERE isci_id = ?");
$query->execute([$sirket,$isci]);
header("Location:index.php"); // Guncelleme basarili
exit();



// Veritabanı bağlantısını kapat
$pdo = null;
?>