<?php
session_start();
$conn = oci_connect('student', 'student', 'localhost:1521/kosmos');
if (!$conn) {
    $m = oci_error();
    trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$wartosc = 0;

if (isset($_SESSION["kto"])) {
    $klog = $_SESSION["kto"];
}


if (isset($_SESSION["utwkoszyk"])) {
   // $nazwa = $_SESSION['nazwa'];
  //  $cena = $_SESSION['cena'];


    $sql = 'UPDATE TABLE KOSZYK SET wartosc = wartosc+:cena';
    $stid = oci_parse($conn, $sql);
    //  $stid = oci_parse($conn, "begin koszyk_edit(:wartosc); end;");
    //oci_bind_by_name($stid, ":war", $cursor1, -1, OCI_B_CURSOR);
    //oci_bind_by_name($stid, ":wartosc", $cena);
    //oci_execute($stid);
    //oci_execute($cursor1);
} else {
    $nazwa = $_SESSION['nazwa'];
    $cena = $_SESSION['cena'];
    $wartosc = $cena;

    $sql = "INSERT INTO TABLE KOSZYK VALUES (koszyk_seq.nextval, :klog, pracownikZamow_seq.nextval, :cena, koszprod_seq.nextval)";
    $stid = oci_parse($conn, $sql);
    // $stid = oci_parse($conn, "begin koszyk_proc(:klient_id, :wartosc); end;");
   // oci_bind_by_name($stid, ":war", $cursor1, -1, OCI_B_CURSOR);

    oci_bind_by_name($stid, ":klient_id", $klog);
    oci_bind_by_name($stid, ":wartosc", $wartosc);
    oci_execute($stid);
    oci_execute($cursor1);
    $_SESSION['utwkoszyk'] = true;
}

?>
<html lang="pl">

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
    <div class="container">
        <?php


        ?>

    </div>
    <div class="container bg-grey">
        
                <?php
                // pobieranie kosztyka z bazy
                    $sql = "SELECT * from koszyk WHERE klient_id=".$klog."";
                    $stid = oci_parse($conn, $sql);

                    oci_execute($stid);
                    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {                    
                        $koszyk_id = $row['KOSZYK_ID'];
                    }

                    //pobieranie produktow koszyka
                    $sql = '  SELECT p.nazwa, p.cena FROM produkty_koszyk pk LEFT JOIN produkt p ON p.produkt_id = pk.produkt_id WHERE pk.koszyk_id ='.$koszyk_id;
                    $stid = oci_parse($conn, $sql);

       // oci_bind_by_name($stid, ':didbv', $didbv);
                    oci_execute($stid);
                    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                        echo  " <div class='container-fluid'>" . $row['NAZWA'] . "Cena: " . $row['CENA'] . "zł </div>";
                        $wartosc += $row['CENA'];
                    }
                    echo  " <div class='container-fluid'><br/> WARTOŚĆ: " . $wartosc . " zl </div>";
                    echo"<br/><div class='col-md-12'>
                    <button id='log' name='log' class='btn btn-success'>Złóż zamówienie</button>
                </div>
            </div></div><div class='break'></div></form>";
                     ?>
    
    </div>





    <div class="container-fluid text-centre bg-grey" id="Kontakt">
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



    <footer class="panel-footer text-center">
        <p>Sklep zoologiczny Pawiański©</p>
    </footer>
</body>

</html>