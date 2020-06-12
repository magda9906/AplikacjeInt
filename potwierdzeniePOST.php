<?php
session_start();
if ((!isset($_POST['rezerwuj']))) {
    header('Location: index.php');
    exit();
}

$id_sam = $_POST['id_sam'];
$email = $_SESSION['email'];

$conn = oci_connect('student', 'student', 'localhost:1521/kosmos');
if (!$conn) {
    $m = oci_error();
    trigger_error(htmlentities($m['message']), E_USER_ERROR);
}

$curs1 = oci_new_cursor($conn);

$stid = oci_parse($conn, "begin rezerwacja.uzytkownik(:email, :idklient); end;");

oci_bind_by_name($stid, ":email", $email);

oci_bind_by_name($stid, ":idklient", $curs1, -1, OCI_B_CURSOR);

oci_execute($stid);

oci_execute($curs1);

while (($row = oci_fetch_array($curs1, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
    $id_klient = $row['ID_KLIENT'];
}

$idodb = $_SESSION['idodb'];
$idzwr = $_SESSION['idzwr'];
$dodb = $_SESSION['dataodb'];
$dzwr = $_SESSION['datazwr'];


$stid = oci_parse($conn, "begin rezerwacja.rezerwuj(:id_klient, :id_samochod, :id_miejsce_odb, :id_miejsce_zwr, :data_odb, :data_zwr); end;");
oci_bind_by_name($stid, ":id_klient", $id_klient);
oci_bind_by_name($stid, ":id_samochod", $id_sam);
oci_bind_by_name($stid, ":id_miejsce_odb", $idodb);
oci_bind_by_name($stid, ":id_miejsce_zwr", $idzwr);
oci_bind_by_name($stid, ":data_odb", $dodb);
oci_bind_by_name($stid, ":data_zwr", $dzwr);


oci_execute($stid);

oci_free_statement($stid);
oci_free_statement($curs1);
oci_close($conn);

$_SESSION['sukces'] = true;
header('Location: sukces.php');

?>