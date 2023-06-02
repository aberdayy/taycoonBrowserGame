<?php 

require("./conn.php");

$aktivasyon_kodu = $_GET["AktivasyonKodu"];
$email = $_GET["Email"];

$query = $pdo->prepare("SELECT * FROM kullanici WHERE kullanici_email = ? and aktivasyon_kodu = ?");
$query->execute([$email,$aktivasyon_kodu]);
$kontrol = $query->rowCount();

if ($kontrol>0) {
    $query2= $pdo->prepare("UPDATE kullanici SET durumu = ?");
    $query2->execute([1]);
    $kontrol2 = $query2->rowCount();
    if ($kontrol2>0) {
        header("Location:index.php"); //aktivasyon basarili
    }else{
        header("Location:error.php?hk=aktivasyon%20basarisiz%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //aktivasyon basarisiz
    }
    
}else{
    header("Location:error.php?hk=email%20adresi%20ve%20aktivasyon%20kodu%20uyusmuyor%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //
}

?>
