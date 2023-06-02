<?php
require("./conn.php");
ob_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "src\Exception.php"; // dosyayi kurdugunuz alan 
require "src\PHPMailer.php"; // dosyayi kurdugunuz alan 
require "src\SMTP.php"; // dosyayi kurdugunuz alan 

// Kullanıcı adı ve şifre doğrulaması yapılacak
$sirket_ismi = $_POST['sirket_ismi'];
$username = $_POST['username'];
$email_adresi = $_POST['email_adresi'];
$sifre = $_POST['sifre'];

// Kullanıcı doğrulama sorgusu
if (isset($_POST["sirket_ismi"])) {
    $sirket_ismi     =    $_POST["sirket_ismi"];
} else {
    $sirket_ismi     =    "";
}

if (isset($_POST["username"])) {
    $username           =    $_POST["username"];
} else {
    $username           =    "";
}

if (isset($_POST["email_adresi"])) {
    $email_adresi           =    $_POST["email_adresi"];
} else {
    $email_adresi           =    "";
}

if (isset($_POST["sifre"])) {
    $sifre         =    $_POST["sifre"];
} else {
    $sifre         =    "";
}
if (isset($_POST["sifre_tekrar"])) {
    $sifre_tekrar         =    $_POST["sifre_tekrar"];
} else {
    $sifre_tekrar         =    "";
}
$AktivasyonKodu         =   aktivasyonKoduUret(); // Database uzerinde sakladigimiz ve kullaniciya gonderecek oldugumuz mailden get yontemiyle aktivasyon durumunu degistirecegimiz aktivasyon kodu.

if (($sirket_ismi != "") and ($username != "") and ($email_adresi != "") and ($sifre != "") and ($sifre_tekrar != "")) {
    if ($sifre != $sifre_tekrar) {
        header("Location:error.php?hk=Sifreler%20uyusmuyor"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
        exit();
    } else {
        $query = $pdo->prepare("SELECT * FROM kullanici WHERE kullanici_email = ? ");
        $query->execute([$email_adresi]);
        $KontrolSayisi = $query->rowCount();
        if ($KontrolSayisi > 0) {
            header("Location:error.php?hk=Bu%20mail%20adresi%20ile%20hali%20hazirda%20bir%20uye%20mevcut"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
            exit();
        } else {
            $query2 = $pdo->prepare("INSERT INTO kullanici (kullanici_adi,kullanici_sifre,kullanici_email,durumu,aktivasyon_kodu) values (?,?,?,?,?)");
            $query2->execute([$username, $sifre, $email_adresi, 0, $AktivasyonKodu]);
            $kayit_kontrol = $query2->rowCount();
            if ($kayit_kontrol > 0) {
                // $mail = new PHPMailer(true);
                // $MailIcerigiHazirla     =   "Merhaba Sayin " . $username . "<br /><br /> Sitemize yapmis oldugunuz uyelik kaydini tamamlamak icin lutfen 
                // <a href='" . "http://localhost/dosyalar/mis104final/" . "/aktivasyon.php?AktivasyonKodu=" . $AktivasyonKodu . "&Email=" . $email_adresi . "'>BURAYA TIKLAYINIZ.</a><br /><br />Saygilarimizla, Iyi Gunler...<br />";
                // try {
                //     $mail->SMTPDebug            = 0;                                                                               //Enable verbose debug output
                //     $mail->isSMTP();                                   //Send using SMTP
                //     $mail->Host                 = 'smtp.hostinger.com';//Set the SMTP server to send through
                //     $mail->SMTPAuth             = true;                                   //Enable SMTP authentication
                //     $mail->CharSet              = "UTF-8";                                //Enable SMTP authentication
                //     $mail->Username             = 'info@ataberkerday.com';//SMTP username
                //     $mail->Password             = 'Dbx12yAta12!';//SMTP password
                //     $mail->SMTPSecure           = 'tls';                                   //Enable implicit TLS encryption
                //     $mail->Port                 = 465;     //587                                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                //     $mail->SMTPOptions          = array(
                //         'ssl' => array(
                //             'verify_peer' => false,
                //             'verify_peer_name' => false,
                //             'allow_self_signed' => true,
                //         )
                //     );
                //     $mail->setFrom("example@example.com");
                //     $mail->addAddress($email_adresi, " ataberk erday");//Add a recipient
                //     // $mail->addReplyTo("noreply@ataberk.com", " ataberk ");
                //     // $mail->addCC("ataberkerrday@gmail.com"," --ataberk CC-- ");
                //     $mail->isHTML(true);                                                 //Set email format to HTML
                //     $mail->Subject =  "Aktivasyon Kodu";
                //     $mail->msgHTML($MailIcerigiHazirla);
                //     $mail->send();
                //     header("Location:xxxxxgonderildi"); // Mail gonderildi
                // } catch (Exception $e) {
                //         echo $e->getMessage(); // mail gonderilemedi
                //     exit();
                // }
                $query6 = $pdo->prepare("SELECT * FROM kullanici WHERE kullanici_adi = ? and kullanici_sifre = ? ");
                $query6->execute([$username, $sifre]);
                $KontrolSayisi6 = $query6->rowCount();
                foreach ($query6 as $key) {
                    $myId = $key["kullanici_id"];
                }
                $query3 = $pdo->prepare("INSERT INTO sirket (sirket_adi,kullanicinin_id) VALUES (?,?) ");
                $query3->execute([$sirket_ismi, $myId]);
                $kayitKontrol2 = $query3->rowCount();

                $query4 = $pdo->prepare("INSERT INTO banka (borc_sirket_id) VALUES (?)");
                $query4->execute([$myId]);
                $kayitKontrol3 = $query4->rowCount();

                $query5 = $pdo->prepare("INSERT INTO yatirim (yatirimci_sirket_id) VALUES (?)");
                $query5->execute([$myId]);
                $kayitKontrol4 = $query5->rowCount();

                if (($KontrolSayisi6 > 0) and ($kayitKontrol2 > 0) and ($kayitKontrol3 > 0) and ($kayitKontrol4 > 0)) {
                    $_SESSION["username"] = $username;
                    $_SESSION["userID"] = $myId;
                    header("Location:index.php"); // Giris basarili
                    exit();
                } else {
                    header("Location:error.php?hk=Kritik%20hata%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
                    exit();
                }
            } else {
                header("Location:error.php?hk=Suanda%20islem%20gerceklestirilemedi%20lutfen%20daha%20sonra%20tekrar%20deneyiniz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
                exit();
            }
        }
    }
} else {
    header("Location:error.php?hk=lutfen%20butun%20alanlari%20doldurdugunuzdan%20emin%20olunuz"); //%20lutfen%20daha%20sonra%20tekrar%20deneyiniz aktivasyon basarisiz
}




// Veritabanı bağlantısını kapat
$pdo = null;
