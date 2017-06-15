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

// SPRAWDZAMY CZY UCZEŃ ISTNIEJE I CZY NIE MA PRYWATNEGO PROFILU    
$zapytanie_uczen = 'SELECT id FROM uczniowie WHERE uczniowie.id=' . $kursant . ' AND private_profil="1"';
$result_uczen = $mysql->query($zapytanie_uczen);

if ($result_uczen->num_rows == 1)
{
    // SPRAWDZAMY CZY UCZEŃ JUŻ NIE OBSERWUJE DRUGIEGO UCZNIA
    $zapytanie_friend = 'SELECT id FROM friends WHERE uczen="' . $uczen->id . '" AND obserwowany="' . $kursant . '"';
    $result_friend = $mysql->query($zapytanie_friend);
    
    if ($result_friend->num_rows == 0)
    {
        $insert = 'INSERT INTO friends (id,uczen,obserwowany,data) VALUES (null,"' . $uczen->id . '","' . $kursant . '",now())';
        $mysql->query($insert);
        header ("Location: ../page/uczen.php?id=" . $kursant);
        
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();    
    }
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}

?>