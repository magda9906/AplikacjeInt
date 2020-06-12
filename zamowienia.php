<?php
session_start();

if (!isset($_SESSION['admin'])) {
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
                    <img src="img/menu.png" width="24" height="24" alt="Menu" />
                </button>

                <a class="navbar-brand"><i class="fas fa-paw">Pawiański</i></a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">

                </ul>
                <ul class="nav navbar-nav navbar-right">

                    <?php if (isset($_SESSION["admin"])) {
                        echo '<li><a href="logout.php">Wyloguj</a></li>';
                    } else {
                        echo '<li><a href="rejestracja.php">Rejestracja</a></li>
                    <li><a href="logowanie.php"><span class="glyphicon glyphicon-log-in"></span>Zaloguj</a> </li>
                    <li><a href="koszyk.php"><i class="fas fa-shopping-cart"></i>Koszyk</a></li>';
                    }
                    ?>

                </ul>
            </div>
        </div>

    </nav>

    <div class="container-fluid text-center bg-grey" style="padding: 6px">
        <h4><i>Zamówienia do realizacji</i> </h4>
    </div>


    <?php
    $conn = oci_connect('student', 'student', 'localhost:1521/kosmos');
    if (!$conn) {
        $m = oci_error();
        trigger_error(htmlentities($m['message']), E_USER_ERROR);
    }
    if (isset($_SESSION["kto"])) {
        $klog = $_SESSION["kto"];
    }
    $curs1 = oci_new_cursor($conn);


    $didbv = $klog;

    $sql = 'SELECT * FROM zamowienie WHERE pracownik_id = :didbv';
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ':didbv', $didbv);
    oci_execute($stid);
     if(($row = oci_fetch_array($stid, OCI_ASSOC)) != true){
        echo"<div class='container-fluid text-center bg-grey'><span><h3><b>Brak zamówień</b></h3></span></div>";
    }
    while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
        echo  " <form class='form-horizontal' method='post' action='koszyk.php'>
        <div class='col-md-12 text-centre bg-grey container-fluid prod '
        S style='margin:10px'><b>" . $row['NAZWA'] . "</b><br>\n";
        echo " <input type='hidden' name='cena' value='int'>Cena: " . $row['CENA'] . "zł<br>\n";
        echo $row['OPIS'] . "<br>\n <div class='form-group'>
        <label class='col-md-12 control-label' for='log'></label>
        <div class='col-md-12'>
            <button id='log' name='log' class='btn btn-success'>Dodaj do koszyka</button>
        </div>
    </div></div><div class='break'></div></form>";
    }
    


    echo "<footer class='panel-footer text-center'>
        <p>Sklep zoologiczny Pawiański©</p>
    </footer>";
    oci_close($conn);
    ?>
</body>

</html>