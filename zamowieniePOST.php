<?php
session_start();
$conn = oci_connect('student', 'student', 'localhost:1521/kosmos');
if (!$conn) {
    $m = oci_error();
    trigger_error(htmlentities($m['message']), E_USER_ERROR);
}
$ktolog =  $_SESSION['email'];
    
$didbv= $ktolog;
$sql = 'SELECT * FROM koszyk WHERE koszyk_id = :didbv ';
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ':didbv', $didbv);
oci_execute($stid);
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    $klient_id = $row['klient_id'];
    $pracownik_id = $row['pracownik_id'];
    $wartosc = $row['wartosc'];
    $produkty_inside = $row['produkty_inside'];
}


if (!isset($_SESSION["zlozzamowienie"])) {
    $_SESSION['bladzam'] = '<span class="text=centre" style="color:red">Wystąpił błąd przy składaniu zamówienia</span>';
    header('Location: koszyk.php');
    exit();
} else {
    $stid = oci_parse($conn, "begin create_zam(:klient_id, :pracownik_id, :wartosc, :produkty_inside); end;");
    oci_bind_by_name($stid, ":klient_id", $klient_id);
    oci_bind_by_name($stid, ":pracownik_id", $pracownik_id);
    oci_bind_by_name($stid, ":wartosc", $wartosc);
    oci_bind_by_name($stid, ":produkty_inside", $produkty_inside);
}
