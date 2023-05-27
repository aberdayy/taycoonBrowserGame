<?php 
require("./conn.php");

$yatirim = $_GET["yatirim"];
$yatirimci = $_GET["yatirimci"];

$yatirimCek = $_GET["yatirimCek"];


$query3 = $pdo->prepare("SELECT * FROM sirket WHERE sirket_id = ?");
$query3->execute([$yatirimci]);
foreach ($query3 as $varolan) {
    $kasa = $varolan["kasa"];
    
}
if ($kasa>$yatirim) {
    $sonuc2 = $kasa - $yatirim; 
    $query4 = $pdo->prepare("UPDATE sirket SET kasa = ? WHERE sirket_id = ?");
    $query4->execute([$sonuc2,$yatirimci]);

    $query1 = $pdo->prepare("SELECT * FROM yatirim WHERE yatirimci_sirket_id = ?");
    $query1->execute([$yatirimci]);
    foreach ($query1 as $varolan) {
        $deger = $varolan["miktar"];
        
    }
    $eklenecekYatirim = $deger + $yatirim;
    $query = $pdo->prepare("UPDATE yatirim SET miktar = ? WHERE yatirimci_sirket_id = ?");
    $query->execute([$eklenecekYatirim,$yatirimci]);
    header("Location:index.php"); // Guncelleme basarili
    exit();

}else{
    header("Location:index.php"); // kasa bos
    exit();

}


// Veritabanı bağlantısını kapat
$pdo = null;
?>