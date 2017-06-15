<?php

if ((isset($_POST['nazwa'])) and (isset($_POST['dzielnica'])))
{
    
    $nazwa = $_POST['nazwa'];
    $dzielnica = $_POST['dzielnica'];
    echo $nazwa;
    echo $dzielnica;
        
    // POŁĄCZENIE Z BAZĄ DANYCH
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $insert = 'INSERT INTO miejsca (id,nazwa,dzielnica) VALUES (null,"' . $nazwa . '","' . $dzielnica . '")';
    $mysql->query($insert);
    
    $zapytanie_last = 'SELECT id FROM miejsca ORDER BY id DESC LIMIT 1';
    $result_last = $mysql->query($zapytanie_last);
    $row_last = $result_last->fetch_array();
    
    header ("Location: ../page/miejsce.php?id=" . $row_last['id']);
    exit();    
               
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


?>