<?php
session_start();
if (!isset($_SESSION["odb"])) {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="pl" xmlns="http://www.w3.org/1999/html">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Car Rent</title>
    <meta name="description" content="Wypożyczalnia samochodów Car Rent">
    <meta name="keywords" content="wypożyczalnia samochodów, samochody, auta">
    <meta name="author" content="Car-Rent">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="img/car_min.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <link href="css/lightbox.css" rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-dark fixed-top navbar-expand-lg" id="mainNav">

        <div class="container">

            <a class="navbar-brand" href="index.php">CAR-RENT.PL</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainmenu">

                <ul class="navbar-nav ml-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php"> Start </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php#samochody"> Nasze samochody </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php#oferta"> Oferta </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php#o_nas"> O nas </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#kontakt_wynik"> Kontakt </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>
    <div class="container-fluid text-center bg-grey" style="padding: 6px">
        <h4><i>Już teraz wybierz co najlepsze dla Twojego pupila!</i> </h4>
    </div>
    <div class="container">

    </div>

    <section id="login">
        <div class="container">
            <?php if (isset($_SESSION["zalogowany"])) {
                echo '<a href="logout.php" class="btn btn-dark float-right" role="button">Wyloguj</a>';
            } else {
                echo '<a href="rejestracja.php" class="btn btn-dark float-right" role="button">Rejestracja</a>
        <a href="logowanie.php" class="btn btn-dark float-right" role="button">Logowanie</a> ';
            }
            ?>
        </div>
    </section>

    <header class="masthead text-white text-center" id="startwynik">
        <div class="container">
            <h1 class="text-uppercase">Znaleziono 6 samochodów</h1>
            <img class="img-fluid mb-5 d-block mx-auto" src="img/gwiazda_white.png" alt="">
            <h3> - Odbiór: <?php echo $_SESSION['dataodb'] . ' - ' . $_SESSION['odb'] ?> -</h3>
            <h3>- Zwrot: <?php echo $_SESSION['datazwr'] . ' - ' . $_SESSION['zwr'] ?> -</h3>
        </div>
    </header>


    <main>

        <section id="wyniki">
            <div class="container">
                <?php
                $conn = oci_connect('student', 'student', 'localhost:1521/kosmos');
                if (!$conn) {
                    $m = oci_error();
                    trigger_error(htmlentities($m['message']), E_USER_ERROR);
                }

                $dodb = $_SESSION['dataodb'];
                $dzwr = $_SESSION['datazwr'];

                $curs1 = oci_new_cursor($conn);
                $stid = oci_parse($conn, "begin wybor.dostauta(:dataodb, :datazwr, :dosteauta); end;");
                oci_bind_by_name($stid, ":dataodb", $dodb);
                oci_bind_by_name($stid, ":datazwr", $dzwr);

                oci_bind_by_name($stid, ":dosteauta", $curs1, -1, OCI_B_CURSOR);

                oci_execute($stid);

                oci_execute($curs1);

                while (($p = oci_fetch_array($curs1, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
                ?>
                    <div class="row wyniki_sam border">
                        <div class="col-sm-12 col-md-6 col-lg-5">
                            <h3><?php echo $p['MARKA'] . ' ' . $p['MODEL'] ?></h3>
                            <img src="img/samochody/sam_png/<?php echo $p['ZDJECIE'] ?>.png" alt="" class="w-100">
                        </div>

                        <div class="col-sm-6 col-md-4 wyniki_sam_list">
                            <img class="float-left" src="img/min/1.png" alt="">
                            <p><?php echo $p['MIEJSCA'] ?></p>
                            <img class="float-left" src="img/min/2.png" alt="">
                            <p><?php echo $p['PAKOWNOSC'] ?></p>
                            <img class="float-left" src="img/min/3.png" alt="">
                            <p><?php echo $p['DRZWI'] ?></p>
                            <img class="float-left" src="img/min/4.png" alt="">
                            <p><?php echo $p['SKRZYNIA'] ?></p>
                            <img class="float-left" src="img/min/5.png" alt="">
                            <p><?php echo $p['KLIMA'] ?></p>
                            <img class="float-left" src="img/min/6.png" alt="">
                            <p><?php echo $p['PALIWO'] ?></p>
                        </div>

                        <div class="col-sm-6 col-md-2 col-lg-3">
                            <h3><?php echo ((strtotime($_SESSION['datazwr']) - strtotime($_SESSION['dataodb'])) / (60 * 60 * 24) + 1) * $p['CENA'] ?>
                                PLN</h3>
                            <p>Kaucja zwrotna <?php echo $p['KAUCJA'] ?> PLN</p>
                            <form action="potwierdzenie.php" method="post">
                                <input name="id_sam" type="hidden" value="<?php echo $p['ID_SAMOCHOD'] ?>">
                                <button name="wybierz" class="btn btn-success">Wybierz</button>
                            </form>
                        </div>

                    </div>
                <?php
                }
                oci_free_statement($stid);
                oci_free_statement($curs1);
                oci_close($conn);
                ?>
            </div>
        </section>


        <section id="kontakt_wynik">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase">Kontakt</h4>
                        <p>Car Rent sp. z o. o.</p>
                        <p>tel. 000 000 000</p>
                        <p>ul. Ulicowa 5</p>
                        <p>00-000 Miastowo</p>
                    </div>
                    <div class="col-md-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase">W sieci</h4>
                        <a href="#"><img class="img-fluid" src="img/ikony/facebook.png" alt=""></a>
                        <a href="#"><img class="img-fluid" src="img/ikony/googleplus.png" alt=""></a>
                        <a href="#"><img class="img-fluid" src="img/ikony/twitter.png" alt=""></a>
                        <a href="#"><img class="img-fluid" src="img/ikony/instagram.png" alt=""></a>
                        <a href="#"><img class="img-fluid" src="img/ikony/youtube.png" alt=""></a>
                    </div>
                    <div class="col-md-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase">Lorem ipsum</h4>
                        <p>Ut suscipit in diam ac dignissim. Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit.</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="panel-footer text-center">
        <p>Sklep zoologiczny Pawiański©</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <script src="js/bootstrap.min.js"></script>

    <script src="js/js_galeria/lightbox-plus-jquery.min.js"></script>
    <script>
        lightbox.option({
            'alwaysShowNavOnTouchDevices': true
        })
    </script>

</body>

</html>