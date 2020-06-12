<?php
session_start();
if ((!isset($_POST['email'])) || (!isset($_POST['haslo']))) {
    header('Location: index.php');
    exit();
}

$login = $_POST['email'];
$haslo = $_POST['haslo'];


$conn = oci_connect('student', 'student', 'localhost:1521/kosmos');
if (!$conn) {
    $m = oci_error();
    trigger_error(htmlentities($m['message']), E_USER_ERROR);
}

$curs1 = oci_new_cursor($conn);

$stid = oci_parse($conn, "begin loguj(:email, :haslo, :passy); end;");
oci_bind_by_name($stid, ":passy", $curs1, -1, OCI_B_CURSOR);
oci_bind_by_name($stid, ":email", $login);
oci_bind_by_name($stid, ":haslo", $haslo);

oci_execute($stid);
oci_execute($curs1);

while (($row = oci_fetch_array($curs1, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
    $sprawdz = $row['KLIENT_ID'];
}
if ($sprawdz > 0) {
    oci_free_statement($stid);
    oci_free_statement($curs1);
    oci_free_statement($curs2);
    oci_close($conn);
    $_SESSION['zalogowany'] = true;
    $_SESSION['mail'] = $login;
    $_SESSION['kto'] = $sprawdz;
     
    unset($_SESSION['blad']);
    header('Location: index.php');
} else {
    $curs1 = oci_new_cursor($conn);

    $stid = oci_parse($conn, "begin loginprac(:email, :haslo, :passy); end;");
    oci_bind_by_name($stid, ":passy", $curs1, -1, OCI_B_CURSOR);
    oci_bind_by_name($stid, ":email", $login);
    oci_bind_by_name($stid, ":haslo", $haslo);

    oci_execute($stid);

    oci_execute($curs1);

    while (($row = oci_fetch_array($curs1, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        $sprawdz = $row['PRACOWNIK_ID'];
        

    }
    if ($sprawdz > 0) {
        oci_free_statement($stid);
        oci_free_statement($curs1);
        oci_free_statement($curs2);
        oci_close($conn);
        $_SESSION['admin'] = true;
        $_SESSION['klog'] = 'SELECT pracownik_id FROM pracownik WHERE email=:email';
        unset($_SESSION['blad']);
        $_SESSION['kto'] = $sprawdz;
        
        header('Location: zamowienia.php');
    }else{
        oci_free_statement($stid);
        oci_free_statement($curs1);
        oci_free_statement($curs2);
        oci_close($conn);
        $_SESSION['blad'] = '<div class="container text-center"><span style="color:red">Nieprawidłowy login lub hasło!</span></div>';
        
        
        header('Location: logowanie.php');
        
    }
}


oci_free_statement($stid);
oci_free_statement($curs1);
oci_free_statement($curs2);
oci_close($conn);
