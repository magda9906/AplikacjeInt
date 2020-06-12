<?php
session_start();
if (!isset($_SESSION["zalogowany"]) || !isset($_SESSION["sukces"])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sklep zoologiczny">
    <meta name="keywords" content="kot, pies, akcesoria, karma">
    <meta name="author" content="M Czechura">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/c1748c61e8.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="ikona.ico" />
    <title>Sklep zoologiczny</title>

</head>

<body>

    <nav class="navbar navbar-expand-lg">

        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <img src="img/menu.png" width="24" height="24" alt="Menu">
                </button>

                <a class="navbar-brand" href="index.php"><i class="fas fa-paw">Pawiański</i></a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <ul class="dropdown-menu">
                            <li><a href="koty.php">Koty</a></li>
                            <li><a href="psy.php">Psy</a></li>
                            <li><a href="chomiki.php">Małe zwierzęta</a></li>
                            <li><a href="akwarium.php">Akwarystyka</a></li>
                            <li><a href="ptaki.php">Ptaki</a></li>
                        </ul>
                    </li>
                    <li><a href="koty.php">Koty</a></li>
                    <li><a href="psy.php">Psy</a></li>
                    <li><a href="chomiki.php">Małe zwierzęta</a></li>
                    <li><a href="akwarium.php">Akwarystyka</a></li>
                    <li><a href="ptaki.php">Ptaki</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">

                    <?php if (isset($_SESSION["zalogowany"])) {
                        echo '<li><a href="logout.php">Wyloguj</a></li>';
                    } else {
                        echo '<li><a href="rejestracja.php">Rejestracja</a></li>
                    <li><a href="logowanie.php"><span class="glyphicon glyphicon-log-in"></span>Zaloguj</a> </li>';
                    }
                    ?>
                    <li><a href="koszyk.php"><i class="fas fa-shopping-cart"></i>Koszyk</a></li>
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
            <h1 class="text-uppercase">Dziękujemy za zakupy!</h1>
            <img class="img-fluid mb-5 d-block mx-auto" src="img/gwiazda_white.png" alt="">
        </div>
    </header>


    <main>

        <section id="potwierdzenie">
            <div class="container text-center">
                <h3>Twoje zamówienie trafiło do realizacji!</h3>
                <h3 class="font-weight-bold"><?php echo $_SESSION['sam']; ?></h3>
                <h3> - Odbiór: <?php echo $_SESSION['dataodb'] . ' - ' . $_SESSION['odb'] ?> -</h3>
                <h3>- Zwrot: <?php echo $_SESSION['datazwr'] . ' - ' . $_SESSION['zwr'] ?> -</h3>
                <h3 class="font-weight-bold">Potwierdzenie zamówienia zostało wysłane na Twoją skrzynkę mailową</h3>
            </div>
        </section>


        <div class="container-fluid bg-grey" id="Kontakt">
            <h4 class="text-center">KONTAKT</h4>
            <div class="row">
                <div class="text-center">
                    <p><span class="glyphicon glyphicon-map-marker"></span> ul. Wesoła 5<br />90-007 Łódź</p>
                    <p><span class="glyphicon glyphicon-phone"></span> +48 234 234 123</p>
                    <p><span class="glyphicon glyphicon-envelope"></span> skleppawianski@gmail.com</p>
                    <p><a href="https://www.facebook.com/"><i class="fab fa-facebook-square"></i></a>
                        <a href="https://www.instagram.com/?hl=pl"><i class="fab fa-instagram"></i></a></p>
                </div>
            </div>
        </div>

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
<?php
$mail = $_SESSION['mail'];
session_unset();
$_SESSION['mail'] = $mail;
$_SESSION['zalogowany'] = true;
?>