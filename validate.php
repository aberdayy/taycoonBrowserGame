<?php 
require("./conn.php");
ob_start();
// Kullanıcı adı ve şifre doğrulaması yapılacak
$username = $_POST['username'];
$password = $_POST['password'];

// Kullanıcı doğrulama sorgusu
$query = $pdo->prepare("SELECT * FROM kullanici WHERE kullanici_adi = ? and kullanici_sifre = ? ");
$query->execute([$username,$password]);
$tf = $query->rowCount();
$userRec = $query->fetch(PDO::FETCH_ASSOC);
if ($tf > 0) {
            $_SESSION["username"] = $username;
            $_SESSION["userID"] = $userRec['kullanici_id'];
            header("Location:index.php"); // Giris basarili
            exit();
    }else{
        header("Location:hata.php"); //basarisiz hatasi
        exit();
    
    }



// Veritabanı bağlantısını kapat
$pdo = null;
?>
