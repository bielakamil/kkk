<?php


session_start ();

if (!isset($_SESSION['pesel']))
{
header ("Location: ../page/login.php");
} else
{
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $pesel = $_SESSION['pesel'];
    
    $zapytanie = 'UPDATE powiadomienia SET stan=2 WHERE odbiorca="' . $pesel . '"';
    
    $result = $mysql->query($zapytanie);
    $stan = $_GET['stan'];
    
    if ($stan == 1)
    {
    $zapytanie_ile = 'SELECT count(odbiorca) as ile FROM powiadomienia WHERE odbiorca="' . $pesel . '" AND stan=1';
        
    $result = $mysql->query($zapytanie_ile);
    
    $row = $result->fetch_array();
    $ile = $row['ile'];
    echo $ile;
    } 
}


?>