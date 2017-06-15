<?php

if (isset($_GET['id']))
{

    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $id = $_GET['id'];
    $delete = 'DELETE FROM egzamin WHERE id=' . $id;
    $mysql->query($delete);
    
    header ("Location: ../page/egzaminy.php");
    exit();
    
    
} else
{
    header ("Location: ../page/dashboard.php");
}



?>