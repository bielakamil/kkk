<?php

if (isset($_GET['id']))
{
    
// SPRAWDZAMY CZY JEST ZALOGOWANY UCZEŃ
include('../script/permissions.php');
admin_instruktor_uczen();
$status = $_SESSION['status'];


if ($status == 3)
{
    include("../script/uczen.php"); // Dołączenie pliku z klasą uczeń
    $uczen = new uczen; // Utworzenie obiektu uczeń
}

$kursant = $_GET['id'];
// DOŁĄCZAMY BAZĘ DANYCH    
$mysql = connect_mysql();    


$delete = 'DELETE FROM friends WHERE uczen="' . $uczen->id . '" AND obserwowany="' . $kursant . '"';
$mysql->query($delete);
header ("Location: ../page/uczen.php?id=" . $kursant);
exit();

    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


?>