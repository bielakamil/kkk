<?php

if (isset($_GET['id']))
{
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    $delete = 'DELETE FROM kursy WHERE id=' . $_GET['id'];
    
    $result = $mysql->query($delete);
    
    header ("Location: ../page/settings-kursanci.php#kursy");
    exit();
    
    
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


?>