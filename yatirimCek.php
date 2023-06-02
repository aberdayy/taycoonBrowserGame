<?php 
require("./conn.php");

$yatirimci = $_GET["yatirimci"];
$yatirimCek = $_GET["yatirimCek"];

$query1 = $pdo->prepare("SELECT * FROM yatirim WHERE yatirimci_sirket_id = ?");
$query1->execute([$yatirimci]);
foreach ($query1 as $varolan) {
    $deger = $varolan["miktar"];
    
}
$query3 = $pdo->prepare("SELECT * FROM sirket WHERE kullanicinin_id = ?");
$query3->execute([$yatirimci]);
foreach ($query3 as $varolan) {
    $deger2 = $varolan["kasa"];
    
}


if ($deger>=$yatirimCek) {
    $sonuc = $deger - $yatirimCek;
    $query2 = $pdo->prepare("UPDATE yatirim SET miktar = ? WHERE yatirimci_sirket_id = ?");
    $query2->execute([$sonuc,$yatirimci]);

    $sonuc2 = $deger2 + $yatirimCek; 
    $query4 = $pdo->prepare("UPDATE sirket SET kasa = ? WHERE kullanicinin_id = ?");
    $query4->execute([$sonuc2,$yatirimci]);
    
    header("Location:index.php"); // Guncelleme basarili
    exit();


}else {
    header("Location:error.php?hk=Bu%20miktarda%20parayi%20suanda%20cekemezsiniz%20lutfen%20daha%20kucuk%20mebla%20deneyiniz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
    exit();

}

// Veritabanı bağlantısını kapat
$pdo = null;
