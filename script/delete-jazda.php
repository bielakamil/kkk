<?php

// USUWANIE JAZDY 

if (isset($_GET['id']))
{
    
    $id = $_GET['id'];
    echo $id;
    
    // DOŁĄCZAMY BAZĘ DANYCH
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    
    // USUWAMY PODANĄ JAZDĘ 
    $delete = 'DELETE FROM jazdy WHERE id=' . $id;
    $mysql->query($delete);
    
    header ("Location: ../page/jazdy.php");
    exit();
    
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


?>