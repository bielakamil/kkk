<?php

if (isset($_GET['id']))
{
    
    include('../script/permissions.php');
    admin();
    $status = $_SESSION['status'];
    $id = $_GET['id'];
        
    if ($status == 1) {
        // Sprawdzenie czy zalogowany użytkownik to administrator
        include("../script/admin.php"); // Dołączenie pliku z klasą administratora
        $admin = new admin; // Utworzenie obiektu administratora
    } 
    $mysql = connect_mysql();
    
    $delete_obecnosc = 'DELETE FROM obecnosc WHERE wyklad=' . $id;
    $mysql->query($delete_obecnosc);
    
    $delete_wyklad = 'DELETE FROM wyklady WHERE id=' . $id;
    $mysql->query($delete_wyklad);
    
    header("Location: ../page/wyklady.php");
    exit();
    
    
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


?>