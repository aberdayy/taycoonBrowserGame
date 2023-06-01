<?php
require("./conn.php");
ob_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "src\Exception.php"; // dosyayi kurdugunuz alan 
require "src\PHPMailer.php";// dosyayi kurdugunuz alan 
require "src\SMTP.php";// dosyayi kurdugunuz alan 

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
            header("Location:UYUSMAYAN");// UYUSMAYAN SIFRE
            exit();
        } else {
            $query = $pdo->prepare("SELECT * FROM kullanici WHERE kullanici_adi = ? and kullanici_sifre = ? ");
            $query->execute([$username, $sifre]);
            $KontrolSayisi = $query->rowCount();
            if ($KontrolSayisi > 0) {
                header("Location:TEKRARLANAN"); // TEKRARLANAN UYE KAYDI
                exit();
            } else {
                $query2 = $pdo->prepare("INSERT INTO kullanici (kullanici_adi,kullanici_sifre,kullanici_email,durumu,aktivasyon_kodu) values (?,?,?,?,?)");
                $query2->execute([$username,$sifre,$email_adresi,0,$AktivasyonKodu]);
                $kayit_kontrol = $query2->rowCount();
                
                if ($kayit_kontrol>0) {
                    $mail = new PHPMailer(true);
                    $MailIcerigiHazirla     =   "Merhaba Sayin " . $username . "<br /><br /> Sitemize yapmis oldugunuz uyelik kaydini tamamlamak icin lutfen 
                    <a href='" . "http://localhost/dosyalar/mis104final/" . "/aktivasyon.php?AktivasyonKodu=" . $AktivasyonKodu . "&Email=" . $email_adresi . "'>BURAYA TIKLAYINIZ.</a><br /><br />Saygilarimizla, Iyi Gunler...<br />";
                    try {
                        $mail->SMTPDebug            = 0;                                                                               //Enable verbose debug output
                        $mail->isSMTP();                                   //Send using SMTP
                        $mail->Host                 = 'smtp.example.com';//Set the SMTP server to send through
                        $mail->SMTPAuth             = true;                                   //Enable SMTP authentication
                        $mail->CharSet              = "UTF-8";                                //Enable SMTP authentication
                        $mail->Username             = 'user@example.com';//SMTP username
                        $mail->Password             = 'email_password';//SMTP password
                        $mail->SMTPSecure           = 'tls';                                   //Enable implicit TLS encryption
                        $mail->Port                 = 465;     //587                                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                        $mail->SMTPOptions          = array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true,
                            )
                        );
                        $mail->setFrom("example@example.com");
                        $mail->addAddress($email_adresi, " ataberk erday");//Add a recipient
                        $mail->addReplyTo("noreply@ataberk.com", " ataberk ");
                        $mail->addCC("ataberkerrday@gmail.com"," --ataberk CC-- ");
                        $mail->isHTML(true);                                                 //Set email format to HTML
                        $mail->Subject =  "Aktivasyon Kodu";
                        $mail->msgHTML($MailIcerigiHazirla);
                        $mail->send();
                        header("Location:xxxxxgonderildi"); // Mail gonderildi
                    } catch (Exception $e) {
                            echo $e->getMessage(); // mail gonderilemedi
                        exit();
                    }

                    header("Location:index.php"); //islem basarili
                    exit();
                } else {
                    header("Location:xxxxxxxxxx"); //islem basarisiz
                    exit();
                }
                
                
                }

            }
        
}




// Veritabanı bağlantısını kapat
$pdo = null;
