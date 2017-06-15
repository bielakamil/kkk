<?php

if (isset($_GET['id']))
{
    $id = $_GET['id'];
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $zapytanie = 'DELETE FROM uczniowie_www WHERE id=' . $id;
    $result = $mysql->query($zapytanie);
    
    header ("Location: ../page/show_all_user_www.php");
    exit();
    
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}

?>