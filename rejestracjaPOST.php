<?php
session_start();
if (!isset($_POST['rej'])) {
    header('Location: index.php');
    exit();
}

$nazwisko = $_POST['nazwisko'];
$imie = $_POST['imie'];
$nrtel = $_POST['numertel'];
$email = $_POST['email'];
$haslo = $_POST['password'];
$haslo2 = $_POST['password2'];
$ulica = $_POST['ulica'];
$nr_domu = $_POST['nr_domu'];
$nr_lokalu = $_POST['nr_lokalu'];
$miejscowosc = $_POST['miejscowosc'];
$kod_pocztowy = $_POST['kod_pocztowy'];


$conn = oci_connect('student', 'student', 'localhost:1521/kosmos');
if (!$conn) {
    $m = oci_error();
    trigger_error(htmlentities($m['message']), E_USER_ERROR);
}


$cursor1 = oci_new_cursor($conn);
/*$stid = oci_parse($conn, "begin :r := czy_powt(:email); end;");
oci_bind_by_name($stid, ":email", $email);

oci_bind_by_name($stid, ":r", $r);

oci_execute($stid);*/



$stid = oci_parse($conn, "begin czy_powt(:email_IN, :wartosc); end;");
oci_bind_by_name($stid, ":wartosc", $cursor1, -1, OCI_B_CURSOR);
oci_bind_by_name($stid, ":email_IN", $email);
oci_execute($stid);
oci_execute($cursor1);
$liczba = 0;
while (($row = oci_fetch_array($cursor1, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
    if ($row['EMAIL'] == $email) {
        $liczba = 1;
    }
}

if ($liczba == 0) {
    if ($haslo == $haslo2) {

        $stid = oci_parse($conn, "begin rejestruj(:nazwisko, :imie, :telefon, :email, :haslo, :ulica, :nr_domu, :nr_lokalu, :miejscowosc, :kod_pocztowy); end;");
        oci_bind_by_name($stid, ":nazwisko", $nazwisko);
        oci_bind_by_name($stid, ":imie", $imie);
        oci_bind_by_name($stid, ":telefon", $nrtel);
        oci_bind_by_name($stid, ":email", $email);
        oci_bind_by_name($stid, ":haslo", $haslo);
        oci_bind_by_name($stid, ":ulica", $ulica);
        oci_bind_by_name($stid, ":nr_domu", $nr_domu);
        oci_bind_by_name($stid, ":nr_lokalu", $nr_lokalu);
        oci_bind_by_name($stid, ":miejscowosc", $miejscowosc);
        oci_bind_by_name($stid, ":kod_pocztowy", $kod_pocztowy);

        oci_execute($stid);
        
        

        unset($_SESSION['bladtworzenia']);
        unset($_SESSION['bladhasla']);
        unset($_SESSION['bladmaila']);
        unset($_SESSION['blad']);
        header('Location: zarejestrowano.php');
    } else {
        $_SESSION['bladhasla'] = '<span style="color:red">Hasła się różnią!</span>';
        header('Location: rejestracja.php');
    }
} else {
    $_SESSION['bladmaila'] = '<span class="text=centre" style="color:red">Podany email jest już zajęty.</span>';
    header('Location: rejestracja.php');
}


oci_free_statement($stid);
oci_close($conn);
