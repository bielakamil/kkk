<?php


if (isset($_GET['id']))
{
    
    $id = $_GET['id'];
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $zapytanie = 'UPDATE uczniowie SET stan=6 WHERE id=' . $id;
    
    $result = $mysql->query($zapytanie);
    
    header ("Location: ../page/uczen.php?id=" . $id);
    
} else
{
    header ("Location: ../page/dashboard.php");
}

?>