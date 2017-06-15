<?php

if (isset($_GET['id']))
{

    $id = $_GET['id'];
    
    include ('../script/mysql.php');
    
    $mysql = connect_mysql();
    
    $delete = 'DELETE FROM zadania_wyzwania WHERE id=' . $id;
    
    $mysql->query($delete);
    
    header ("Location: ../page/zadania_instruktor.php");
    exit();
    
    
    
}

?>