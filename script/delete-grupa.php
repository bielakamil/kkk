<?php

if (isset($_GET['id']))
{
    $id = $_GET['id'];
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();

    $delete = 'DELETE FROM wyklad_grupa WHERE grupa=' . $id;
    $mysql->query($delete);
    
    $delete = 'DELETE grupa FROM WHERE id=' . $id;
    $mysql->query($delete);
    
} else
{
    header ("Location: ../page/grupy.php");
    exit();
}

?>