<?php

include ('../script/permissions.php');

admin_instruktor();
$status = $_SESSION['status'];
if ($status == 1) {
        header ("Location: ../page/dashboard.php");
} elseif ($status == 2) {
    include("../script/instruktor.php"); // Dołaczenie pliku z klasą instruktor
    $instruktor = new instruktor(); // Utworzenie obiektu instruktor
}

$mysql = connect_mysql();

$dodaj = 'INSERT INTO zadania_wyzwania VALUES (null,"' . $_POST['nazwa'] . '","' . $_POST['zakres'] . '","' . $_POST['kategoria'] . '",2)';
$mysql->query($dodaj);


header ("Location: ../page/zadania_instruktor.php");

?>