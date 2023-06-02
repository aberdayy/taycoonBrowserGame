<?php

$hata_kodu = $_GET["hk"];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>HATA VAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body style="text-align: center;">
    <br>
    <br>
    <br>
    <br>

    <?php
    if ($hata_kodu == "tehlike") {
    ?>
        <div class="card mx-auto mb-3">
            <div class="card-image-top"><iframe src="https://giphy.com/embed/YOkrK8agZLEk2cXeLi" width="480" height="268" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>

                <div class="card-body">
                    <h2 class="card-header">Yapma</h2>
                    <p class="card-text">
                        <?php
                        echo "Sevgili kullanici lutfen bir daha linkler uzerinden islem yapmaya kalkisma.<br> Ip adresiniz = " . $_SERVER["REMOTE_ADDR"];

                    } else { ?>
                    <div class="card mx-auto mb-3">
                        <div class="card-image-top"><iframe src="https://giphy.com/embed/pynZagVcYxVUk" width="480" height="480" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
                            <div class="card-body">
                                <h2 class="card-header">Hata Yasandi</h2>
                                <p class="card-text">
                                <?php
                                echo $hata_kodu;
                            }
                                ?>
                                </p>
                                <a href="index.php" class="btn btn-primary">Anasayfaya Git</a>
                            </div>
                        </div>
                        <h1>
                        </h1>

                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
                        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>

</html>