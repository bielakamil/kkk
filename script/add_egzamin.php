<?php

if (isset($_GET['uczen']))
{
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $date = $_GET['data'];
    $time = $_GET['time'];
    $uczen = $_GET['uczen'];
    $stan = $_GET['stan'];
    $notatka = $_GET['notatka'];
    $rodzaj = $_GET['rodzaj'];
    
    $data = $date . ' ' . $time;
    
    $insert = 'INSERT INTO egzamin (id,data,stan,uczen,notatka,typ) VALUES (null,"' . $data . '","' . $stan . '","' . $uczen . '","' . $notatka . '","' . $rodzaj . '")';
    $mysql->query($insert);
    
    
    $zapytanie_last = 'SELECT id FROM egzamin WHERE uczen=' . $uczen . ' ORDER BY data DESC';
    $result_last = $mysql->query($zapytanie_last);
    $row_last = $result_last->fetch_array();
    
    header ("Location: ../page/egzamin.php?id=" . $row_last['id']);
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}

?>
