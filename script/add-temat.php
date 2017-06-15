<?php

if ((isset($_GET['temat'])) and (isset($_GET['kategoria'])))
{
    
    $temat = $_GET['temat'];
    $kategoria = $_GET['kategoria'];
    $notatka = $_GET['notatka'];
    $czas =  $_GET['czas'];
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    echo 'Temat: ' . $temat . '<br>';
    echo 'Kategoria: ' . $kategoria . '<br>';
    
    
    $insert = 'INSERT INTO tematy_wyklad (id,temat,kategoria,notatka,czas) VALUES (null,"' . $temat . '","' . $kategoria . '","' . $notatka . '","' . $czas . '")';
    $mysql->query($insert);
    
    $zapytanie = 'SELECT id FROM tematy_wyklad ORDER BY id DESC LIMIT 1';
    $result =  $mysql->query($zapytanie);
    $row = $result->fetch_array();
    header ("Location: ../page/temat-wyklad.php?id=" . $row['id']);
    exit();
    
} else
{
    header ("Location: ../page/add-temat.php");
    exit();
}

















?>