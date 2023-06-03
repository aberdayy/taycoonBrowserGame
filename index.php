<?php
require("./conn.php");
function gunBitir($kullanici_id, $gelir, $gider)
{
    global $pdo;
    global $kasam;
    $gun = $gelir - $gider;
    $banka_hesabi = $kasam + $gun;
    $query11 = $pdo->prepare("UPDATE sirket SET kasa = ?  WHERE kullanicinin_id = ?");
    $query11->execute([$banka_hesabi, $kullanici_id]);
}

#TO DO
/*
Eger kasa ekside ise ve kredi skoru minimum seviyede ise icraalik olursun ve oyun biter. //yapildi

Oyun icin dokumantasyon oyun klavuzu yapilacak. (Dokumantasyon icin=> Sirket icerisinde gun bitmeden yapilan her bir islem bir is gununu bitirir.)
*/

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ataberk Erday browser game</title>
    <link rel="stylesheet" href="main.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <style>
        .container {
            position: relative;
            text-align: center;
            color: white;
        }

        .bottom-left {
            position: absolute;
            bottom: 8px;
            left: 16px;
        }

        .top-left {
            position: absolute;
            top: 8px;
            left: 16px;
        }

        .top-right {
            position: absolute;
            top: 8px;
            right: 16px;
        }

        .bottom-right {
            position: absolute;
            bottom: 8px;
            right: 16px;
        }

        .centered {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        img {
            width: 100%;
            height: 100%;
            background-size: 100% 100%;
        }
    </style>
</head>

<body style="text-align:center; background-color: rgb(78, 78, 78);">
    <?php if (isset($_SESSION["username"])) {
        $myID = $_SESSION["userID"];
        $query = $pdo->prepare("SELECT * FROM sirket WHERE kullanicinin_id = ?");
        $query->execute([$myID]);
        $kasa = $query->fetch(PDO::FETCH_ASSOC);


        $query5 = $pdo->prepare("SELECT * FROM isci WHERE sahip_sirket_id = ?");
        $query5->execute([$myID]);
        $Iscisayi = $query5->rowCount();

        $query6 = $pdo->prepare("SELECT AVG(verimlilik) FROM isci WHERE sahip_sirket_id = ?");
        $query6->execute([$myID]);
        foreach ($query6 as $key) {
            $verimlilik =  $key["AVG(verimlilik)"];
        }
        $query9 = $pdo->prepare("SELECT * FROM yatirim WHERE yatirimci_sirket_id = ?");
        $query9->execute([$myID]);
        foreach ($query9 as $key) {
            $yatirim_miktari =  $key["miktar"];
        }
        $query8 = $pdo->prepare("SELECT * FROM banka WHERE borc_sirket_id = ?");
        $query8->execute([$myID]);
        foreach ($query8 as $key) {
            $kredi_skoru = $key["kredi_skoru"];
            $borc_miktari = $key["borc_miktari"];
        }
        $query1 = $pdo->prepare("SELECT * FROM sirket WHERE kullanicinin_id = ?");
        $query1->execute([$myID]);
        foreach ($query1 as $key) {
            $sirket_adi =  $key["sirket_adi"];
            $kasam = $key["kasa"];
        }
        $query10 = $pdo->prepare("SELECT SUM(maas) FROM isci WHERE sahip_sirket_id = ?");
        $query10->execute([$myID]);
        foreach ($query10 as $key) {
            $maas_gider =  ceil(($key["SUM(maas)"] / 30));
        }

        $query2 = $pdo->prepare("SELECT * FROM kullanici WHERE kullanici_id = ?");
        $query2->execute([$myID]);
        $isim = $query2->fetch(PDO::FETCH_ASSOC);

        $gunluk_gelir = ceil((((($yatirim_miktari * $verimlilik) / 100) * $Iscisayi) / 30));

        if (($kredi_skoru<=50)and($kasam<=0)and($maas_gider>$gunluk_gelir)) {
            $query13 = $pdo->prepare("UPDATE kullanici SET durumu = ?  WHERE kullanici_id = ?");
            $query13->execute([0, $myID]);
        }


        if ($isim["durumu"] == 0) {
    ?>
            <div class="card mx-auto mt-5">
                <div class="card-image-top">
                <iframe src="https://giphy.com/embed/1n4iuWZFnTeN6qvdpD" width="480" height="327" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
                    <div class="card-body">
                        <h2 class="card-header">IFLAS ETTIN</h2>
                        <p class="card-text">
                            Kredi kullandigin icin kredi skorun dustu ve daha fazla kredi cekemedin.<br> Banka hesabinda cok yuksek miktarda eksi bakiye oldugu icin artik calisan maaslarinin odeyemiyordun...<br> Calisanlarin seni terk etti ve en sonundan iflasini aciklayarak icralik oldun. Banka butun malvarligina el koydu.
                        </p>
                        <a href="index.php" class="btn btn-primary">Anasayfaya Git</a>
                    </div>
                </div>
            </div>

        <?php
            session_destroy();
            exit;
        } else {
        ?>

            <!--NAVBAR -->

            <nav class="navbar bg-dark navbar-expand-lg sticky-top justify-content-center" data-bs-theme="dark">
                <div class="navbar-nav ">
                    <p class="nav-link">
                    <h6 style="color: azure;">Oyun ici bir gun gercek dunyada 20 saniyedir.</h6>
                    </p>
                    <audio class="nav-link" controls loop autoplay>
                        <source src="music/Elevator Music.mp3" type="audio/mpeg">
                    </audio>
                    <p class="nav-link">hosgeldiniz sayin :
                        <?php echo $isim['kullanici_adi']; ?>
                    </p>
                    <p class="nav-link">Kasadaki para :
                        <?php echo $kasam; ?>
                    </p>
                    <p class="nav-link">Isci sayisi :
                        <?php echo $Iscisayi; ?>
                    </p>
                    <p class="nav-link">Verimlilik :
                        <?php
                        if ($Iscisayi > 0) {
                            echo $verimlilik;
                        } else {
                            echo 0;
                        }

                        if (($borc_miktari <= 0) and ($kredi_skoru >= 100) and ($verimlilik > 75) and ($kasam >= 20000)) {
                            $guncel_ks = $kredi_skoru + 5;
                            $query12 = $pdo->prepare("UPDATE banka SET kredi_skoru = ?  WHERE borc_sirket_id = ?");
                            $query12->execute([$guncel_ks, $myID]);
                        }
                        ?>
                    </p>
                    <a href="logout.php" class="nav-link">Cikis yap
                    </a>
                </div>
            </nav>
            <!--DURUM EKRANI -->
            <div class="container-fluid">
                <div class="card-container">
                    <div>
                        <div class="card status-card mt-4">
                            <div class="card-header">
                                <h5 class="card-title"><?php echo $sirket_adi; ?> Faaliyet Raporu</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text h5 mb-2"> Kasa : <?php echo $kasam; ?> TRY</p>
                                <p class="card-text h5 mb-2"> Gunluk Gider : <?php echo $maas_gider; ?> TRY</p>
                                <p class="card-text h5 mb-2"> Gunluk Gelir : <?php echo $gunluk_gelir ?> TRY</p>
                                <p class="card-text h5 mb-2"> Verimlilik : %<?php echo $verimlilik; ?></p>
                                <p class="card-text h5 mb-2"> Isci Sayisi : <?php echo $Iscisayi; ?></p>
                                <p class="card-text h5 mb-2"> Kredi Skoru : <?php echo $kredi_skoru; ?> PTS</p>
                                <p class="card-text h5 mb-2"> Banka Kredisi Toplami : <?php echo $borc_miktari; ?> TRY</p>
                                <p class="card-text h5 mb-2"> Yatirim Miktari <?php echo $yatirim_miktari; ?> TRY</p>
                            </div>
                        </div>
                        <!--BANKA-->

                        <div class="card status-card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">Banka - Toplam Borc : <?php echo $borc_miktari; ?> TRY</h5>
                            </div>
                            <form>
                                <div class="card-body">
                                    <p><b>Kredi Skoru : <?php echo $kredi_skoru; ?></b></p>

                                    <div class="button-group">
                                        <a href="banka.php?ui=<?php echo $myID . '&ck=1' . '&ks=' . $kredi_skoru; ?>" class="btn btn-success">Borç Al</a>
                                        <a href="banka.php?ui=<?php echo $myID . '&ck=0' . '&ks=' . $kredi_skoru; ?>" class="btn btn-danger">Borç Öde</a>
                                    </div>
                                    <p>Bankadan borc almak kredi skorunu -25 baz puan etkiler. </br> 50 baz puanin altina dusersen banka sana kredi vermek istemeyecektir.</p>

                                </div>
                        </div>
                        <form>
                    </div>

                    <!--YATIRIM EKRANI -->
                    <div class="card status-card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">Yatırım Ekrani</h5>
                        </div>
                        <div class="card-body">
                            <p><u><b>Yeni Bir Yatirim Yap</b></u></p>
                            <?php
                            echo "<a href=\"yatirim.php?yatirim=2500&yatirimci=" . $myID  . "\" class=\"btn btn-outline-success\"> " . "2500" . " TRY </a> ";
                            echo "<a href=\"yatirim.php?yatirim=5000&yatirimci=" . $myID  . "\" class=\"btn btn-outline-success\"> " . "5000" . " TRY </a> ";
                            echo "<a href=\"yatirim.php?yatirim=10000&yatirimci=" . $myID  . "\" class=\"btn btn-outline-success\"> " . "10000" . " TRY </a></br></br>";

                            ?>
                            <p><u><b>Hali Hazirda Yapilan Yatirim</b></u></p>
                            <?php
                            $query7 = $pdo->prepare("SELECT SUM(miktar) FROM yatirim WHERE yatirimci_sirket_id = ?");
                            $query7->execute([$myID]);
                            $sayi = $query7->rowCount();
                            if ($sayi > 0) {
                                foreach ($query7 as $yatirim) {
                                    $toplamYatirim = $yatirim["SUM(miktar)"];
                                }
                            ?>
                                <?php
                                echo "<button class=\"btn btn-success\"> " . $toplamYatirim . " TRY </button></br></br>";
                                ?>

                                <p><u><b>Yatirim havuzundan kasaya para cek</b></u></p>
                            <?php
                                echo "<a href=\"yatirimCek.php?yatirimCek=2500&yatirimci=" . $myID  . "\" class=\"btn btn-danger\"> " . "2500" . " TRY </a> ";
                                echo "<a href=\"yatirimCek.php?yatirimCek=5000&yatirimci=" . $myID  . "\" class=\"btn btn-danger\"> " . "5000" . " TRY </a> ";
                                echo "<a href=\"yatirimCek.php?yatirimCek=10000&yatirimci=" . $myID  . "\" class=\"btn btn-danger\"> " . "10000" . " TRY </a></br></br>";
                            } else { ?>
                                <li>
                                    Henuz hic yatiriminiz yok...
                                </li>
                            <?php } ?>

                        </div>
                    </div>
                    <div>
                        <!--ISCILERIM -->
                        <div>
                            <div class="card status-card mt-4">
                                <div class="card-header">
                                    <h5 class="card-title">İşçilerim</h5>
                                </div>

                                <div class="card-body">
                                    <ul>
                                        <?php
                                        $query3 = $pdo->prepare("SELECT * FROM isci WHERE sahip_sirket_id = ?");
                                        $query3->execute([$myID]);
                                        $sayi = $query3->rowCount();
                                        if ($sayi > 0) {
                                            foreach ($query3 as $isci) {
                                        ?>

                                                <li>
                                                    <?php
                                                    echo "<a href=\"isci.php?sirket=0&isci="
                                                        . $isci['isci_id'] . "\" class=\"btn btn-outline-danger\"> " . $isci["isci_adi"]  . " isimli isciyi KOV ! </a></br>
                             %" . $isci["verimlilik"]  . " verimlilik ile " . $isci["maas"] . " TRY Maas Alıyor </br> </br>"; ?>
                                                </li>

                                            <?php }
                                        } else { ?>
                                            <li>
                                                Henuz hic isciniz yok...
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--ISCI AL -->
                    <div>
                        <div class="card mt-4 status-card">
                            <div class="card-header">
                                <h5 class="card-title">İşçi Al</h5>
                            </div>
                            <div class="card-body justify-content-center">

                                <?php
                                $query4 = $pdo->prepare("SELECT * FROM isci WHERE sahip_sirket_id != ?");
                                $query4->execute([$myID]);
                                $sayi = $query4->rowCount();
                                $isciler = $query4->fetch(PDO::FETCH_ASSOC);
                                foreach ($query4 as $isci) { ?>
                                    <?php
                                    echo
                                    "<b>" . $isci['isci_adi'] . ":</b> bu iscinin verimliligi <b>"
                                        . $isci['verimlilik'] . "</b> ve gunluk maliyeti <b>"
                                        . $isci['maas'] . " </b><a href=\"isci.php?sirket="
                                        . $myID . "&isci="
                                        . $isci['isci_id'] . "\" class=\"btn btn-outline-primary\"> isciyi ise al </a></br></br>"; ?>
                                <?php } ?>


                            </div>
                        </div>


                    </div>
                </div>
            </div>

        <?php
        }
    } else { ?>
        <!--GIRIS YAP -->

        <div class="row justify-content-center  mt-5">
            <div class="col-sm-4 mt-5">
                <div class="card text-center ">
                    <div class="card-header">
                        Hosgeldin oyuncu
                    </div>
                    <div class="card-body ">
                        <h5 class="card-title">Giris yap</h5>
                        <form action="validate.php" method="POST">
                            <div class="form-group">
                                <label>Kullanıcı isimini yaz</label>
                                <input name="username" class="form-control" placeholder="Kullanıcı isimini yaz">
                            </div>
                            <div class="form-group">
                                <label>Sifre</label>
                                <input name="password" type="password" class="form-control" placeholder="Sifre">
                            </div><br>
                            <input type="submit" value="Gonder" class="btn btn-primary">
                        </form>
                    </div>
                    <div class="card-footer text-muted">
                        Dunyanin en muhtesem is oyununa hosgeldin
                    </div>
                </div>
            </div>
        </div>
        <!--Kayit Ol -->

        <div class="row justify-content-center  mt-5">
            <div class="col-sm-4 mt-5">
                <div class="card text-center ">
                    <div class="card-header">
                        Hosgeldin oyuncu
                    </div>
                    <div class="card-body ">
                        <h5 class="card-title">Kayit Ol</h5>
                        <form action="register.php" method="POST">
                            <div class="form-group">
                                <label><b>Sirketinin isimini yaz</b></label>
                                <input name="sirket_ismi" class="form-control" placeholder="Sirketinin isimini yaz">
                            </div>
                            <div class="form-group">
                                <label><b>Kendi isimini yaz</b></label>
                                <input name="username" class="form-control" placeholder="Kendi isimini yaz">
                            </div>
                            <div class="form-group">
                                <label><b>Email Adresini Yaz</b></label>
                                <input name="email_adresi" class="form-control" placeholder="Email Adresini Yaz">
                            </div>
                            <div class="form-group">
                                <label><b>Sifreni Yaz</b></label>
                                <input name="sifre" type="password" class="form-control" placeholder="Sifreni Yaz">
                            </div>
                            <div class="form-group">
                                <label><b>Sifreni tekrar et</b></label>
                                <input name="sifre_tekrar" type="password" class="form-control" placeholder="Sifreni tekrar et">
                            </div><br>
                            <input type="submit" value="Gonder" class="btn btn-primary">
                        </form>
                    </div>
                    <div class="card-footer text-muted">
                        Dunyanin en muhtesem is oyununa hosgeldin
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script>
        setTimeout(function() {
            window.location.reload(1);
            <?php
            gunBitir($myID, $gunluk_gelir, $maas_gider);
            ?>
        }, 20000);
    </script>
</body>

</html>