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
                header("Location:kasayaeklenemedi.php");
            }
        } else {
            header("Location:borcYapilamadi.php");
        }
    } else {
        header("Location:krediskoruhatasi.php");
    }
} elseif ($talep == 0) {
    # kredi ode
    header("Location:krediode.php");

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
                header("Location:borcKapanmadi.php");
            }
        } else {
            header("Location:kasaGuncellenemedi.php");
        }
    } else {
        header("Location:paraYok.php");
    }
} else {
    # hata
    header("Location:genelhatasi.php");
}

// Veritabanı bağlantısını kapat
$pdo = null;
