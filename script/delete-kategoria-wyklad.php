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
    
    
    $zapytanie_wyklad = 'SELECT id FROM kategorie_wyklad WHERE nazwa="Nie wybrano"';
    $result_wyklad = $mysql->query($zapytanie_wyklad);
    
    if ($result_wyklad->num_rows == 1)
    {
        $row_wyklad = $result_wyklad->fetch_array();
        $zapytanie = 'UPDATE tematy_wyklad SET kategoria="' . $row_wyklad['id'] . '" WHERE kategoria="' . $id . '"';
        $result  = $mysql->query($zapytanie);
        
        $delete = 'DELETE FROM kategorie_wyklad WHERE id=' . $id;
        $mysql->query($delete);
        
        header ("Location: ../page/kategorie-wyklad.php");
        exit();
        
    } else
    {
        header ("Location: ../page/kategorie-wyklad.php");
        exit();
    }
    
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


?>