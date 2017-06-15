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


$zapytanie = 'SELECT zadania_instruktor_kategorie.id, zadania_instruktor_kategorie.level, zadania_kategoria.nazwa FROM zadania_instruktor_kategorie, zadania_kategoria WHERE zadania_instruktor_kategorie.kategoria=zadania_kategoria.id AND zadania_instruktor_kategorie.instruktor=' . $instruktor->id . ' ORDER BY zadania_instruktor_kategorie.level';

$mysql = connect_mysql();

$result = $mysql->query($zapytanie);

while ($row = $result->fetch_array())
{
    $nazwa_kategori = $_POST['category-' . $row['id']];
    if ($nazwa_kategori != $row['nazwa'])
    {
        $update = 'UPDATE zadania_kategoria SET nazwa="' . $nazwa_kategori . '" WHERE id=' . $row['id'];
        $mysql->query($update);
    }
    
    
    $zapytanie_zadania = 'SELECT zadania_wyzwania.id, zadania_wyzwania.nazwa, zadania_wyzwania.zakres FROM zadania_wyzwania WHERE zadania_wyzwania.kategoria=' . $row['id'];
    $result_zadania = $mysql->query($zapytanie_zadania);
            
    while ($row_zadania = $result_zadania->fetch_array())
    {
        $nazwa_zadania = $_POST['task-' . $row_zadania['id'] . '-name'];
        $zakres_zadania = $_POST['task-' . $row_zadania['id'] . '-range'];
        if ($nazwa_zadania != $row_zadania['nazwa'])
        {
            $update = 'UPDATE zadania_wyzwania SET nazwa="'. $nazwa_zadania . '" WHERE id=' . $row_zadania['id'];
            $mysql->query($update);
        }
        
        if ($zakres_zadania != $row_zadania['zakres'])
        {
            $update = 'UPDATE zadania_wyzwania SET zakres="'. $zakres_zadania . '" WHERE id=' . $row_zadania['id'];
            $mysql->query($update);
        }
    }
    
}

header ("Location: ../page/zadania_instruktor.php");


?>