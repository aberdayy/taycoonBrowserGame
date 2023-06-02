<?php
require("./conn.php");

$kullanici = $_GET["ui"];
$talep = $_GET["ck"];
$kredi_skoru = $_GET["ks"];


$kredi = 10000;
if ($talep == 1) {
    #kredi cek
    $query3 = $pdo->prepare("SELECT * FROM banka WHERE borc_sirket_id = ?");
    $query3->execute([$kullanici]);
    foreach ($query3 as $key) {
        $kredi_skoru_reel = $key["kredi_skoru"];
        $guncel_borc = $key["borc_miktari"];
    }
    //Eger kullanicinin kredi skoru get ile gelen kredi skoruna esitse ve 50 den buyukse kredi ver.
    if (($kredi_skoru == $kredi_skoru_reel) and ($kredi_skoru_reel > 50)) {
        $toplam_borc = $guncel_borc + $kredi;

        $kredi_skoru_reel_sonuc = $kredi_skoru_reel - 25;

        $query4 = $pdo->prepare("UPDATE banka SET borc_miktari = ? , kredi_skoru = ? WHERE borc_sirket_id = ?");
        $query4->execute([$toplam_borc, $kredi_skoru_reel_sonuc, $kullanici]);
        $islem = $query4->rowCount();


        if ($islem > 0) {
            $query6 = $pdo->prepare("SELECT * FROM sirket WHERE kullanicinin_id = ?");
            $query6->execute([$kullanici]);
            foreach ($query6 as $key) {
                $kasa = $key["kasa"];
            }
            $kasaSon = $kasa + $kredi;
            $query5 = $pdo->prepare("UPDATE sirket SET kasa = ?  WHERE kullanicinin_id = ?");
            $query5->execute([$kasaSon, $kullanici]);
            $sonuc = $query5->rowCount();
            if ($sonuc > 0) {
                header("Location:index.php"); // Guncelleme basarili
            } else {
                header("Location:error.php?hk=kasa%20guncelleme%20basarisiz%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //aktivasyon basarisiz
            }
        } else {
            header("Location:error.php?hk=kredi%20verilirken%20hata%20gerceklesti%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
        }
    } else {
        header("Location:error.php?hk=kredi%20skorunuz%20islem%20icin%20yetersiz%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
    }
} elseif ($talep == 0) {
    # kredi ode
    $query111 = $pdo->prepare("SELECT * FROM banka WHERE borc_sirket_id = ?");
    $query111->execute([$kullanici]);
    foreach ($query111 as $key) {
        $kredi_skoru_reel = $key["kredi_skoru"];
        $guncel_borc = $key["borc_miktari"];
    }

    if ($guncel_borc>0) {
    $query7 = $pdo->prepare("SELECT * FROM sirket WHERE kullanicinin_id = ?");
    $query7->execute([$kullanici]);
    foreach ($query7 as $key) {
        $kasam = $key["kasa"];
    }
    if ($kasam >= $kredi) {
        $kasaSon = $kasam - $kredi;
        $query8 = $pdo->prepare("UPDATE sirket SET kasa = ?  WHERE kullanicinin_id = ?");
        $query8->execute([$kasaSon, $kullanici]);
        $sonuc = $query8->rowCount();
        if ($sonuc > 0) {
            $query10 = $pdo->prepare("SELECT * FROM banka WHERE borc_sirket_id = ?");
            $query10->execute([$kullanici]);
            foreach ($query10 as $key) {
                $guncel_ks = $key["kredi_skoru"];
                $guncel_borc = $key["borc_miktari"];
            }
            $son_ks = $guncel_ks + 25;
            $son_borc = $guncel_borc - $kredi;
            $query9 = $pdo->prepare("UPDATE banka SET borc_miktari = ? , kredi_skoru = ? WHERE borc_sirket_id = ?");
            $query9->execute([$son_borc, $son_ks, $kullanici]);
            $islem = $query9->rowCount();
            if ($islem > 0) {
                header("Location:index.php"); // islemler basarili 
            } else {
                header("Location:error.php?hk=borc%20odeme%20islemi%20gerceklesemedi%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
            }
        } else {
            header("Location:error.php?hk=kasadan%20para%20cekilemedi%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
        }
    } else {
        header("Location:error.php?hk=borc%20odemek%20icin%20yetersiz%20bakiye%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
    }
}else{
    header("Location:error.php?hk=borcunuz%20bulunmamaktadir%20bankamizi%20kullandiginiz%20icin%20tesekkur%20ederiz%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
}
    
} else {
    # hata
    header("Location:error.php?hk=tehlike"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
}

// Veritabanı bağlantısını kapat
$pdo = null;
