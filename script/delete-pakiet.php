<?php

if (isset($_GET['id']))
{
    $id = $_GET['id'];
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $delete = 'DELETE FROM pakiety WHERE id=' . $id;
    $result = $mysql->query($delete);
    
    header ("Location: ../page/settings-kursanci.php");
    exit();
    
    
    
    
} else
{
header ("Location: ../page/dashboard.php");
exit();
}



?>