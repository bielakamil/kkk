<?php

if (isset($_GET['kategoria']))
{

// DOŁĄCZENIE BAZY DANYCH    
include ('../script/mysql.php');
$mysql = connect_mysql();
    
// PRZYPISANIE ZMIENNYCH
$kategoria = $_GET['kategoria'];
    
$insert = 'INSERT INTO kategorie_wyklad (id,nazwa) VALUES (null,"' . $kategoria . '")';
$mysql->query($insert);
    
$select = 'SELECT id FROM kategorie_wyklad ORDER BY id DESC LIMIT 1';
$result = $mysql->query($select);
$row = $result->fetch_array();
header ("Location: ../page/kategoria-wyklad.php?id=" . $row['id']);
exit();
    

} else
{
    header ("Location: ../page/add-kategoria.php");
    exit();
}







?>