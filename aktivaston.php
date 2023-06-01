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
        header("Location:xxxxxxx.php"); //aktivasyon basarisiz
    }
    
}else{
    header("Location:xxxxx.php"); //uyusmayan mail ve aktivasyon kodu    
}

?>
