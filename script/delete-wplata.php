<?php

if (isset($_GET['id']))
{
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    $id = $_GET['id'];

    $zapytanie = 'SELECT uczen FROM wplaty WHERE id=' . $id;
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    $row = $result->fetch_array();
    $uczen = $row['uczen'];
    
    $delete = 'DELETE FROM wplaty WHERE id=' . $id;
    $mysql->query($delete);
    
    header ("Location: ../page/wplaty.php?uczen=" . $uczen);
    exit();
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


?>