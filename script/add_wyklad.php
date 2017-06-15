<?php


// POŁĄCZENIE Z BAZĄ DANYCH
include ('../script/mysql.php');
$mysql = connect_mysql();

// PRZYPISANIE WYNIKÓW DO ZMIENNYCH
$data = $_POST['date'];
$time = $_POST['time'];
$wykladowca = $_POST['wykladowca'];
$stan = $_POST['stan'];
$temat = $_POST['temat'];
$miejsce = $_POST['miejsce'];
$date = $data . ' ' . $time;
$uczen = $_POST['uczen'];
$grupa = $_POST['grupa'];


// DODANIE WYKŁADU DO BAZY DANYCH
$insert = 'INSERT INTO wyklady (id,data,instruktor,stan, temat,miejsce) VALUES (null,"' . $date . '","' . $wykladowca . '","' . $stan . '","' . $temat . '","' . $miejsce  . '")';
$mysql->query($insert);

// ZAPYTANIE O ID DODANEGO WYKŁADU
$select = 'SELECT id FROM wyklady ORDER BY id DESC LIMIT 1';
$result = $mysql->query($select);
$row = $result->fetch_array();
$id_wyklad = $row['id'];

//DODAWANIE WYKŁADU DO GRUPY
$insert_grupa = 'INSERT INTO wyklad_grupa VALUES (null,"' . $id_wyklad . '","' . $grupa . '")';
$mysql->query($insert_grupa);


foreach ($_POST['uczen'] as $id_uczen)
{
    $insert_obecnosc = 'INSERT INTO obecnosc (id,wyklad,uczen) VALUES (null,"' . $id_wyklad . '","' . $id_uczen . '")';
    $mysql->query($insert_obecnosc);   
}



header ("Location: ../page/wyklad.php?id=" . $id_wyklad);


?>