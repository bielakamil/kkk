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
    $zapytanie_wyklad = 'SELECT id FROM tematy_wyklad WHERE temat="Nie wybrano"';
    $result_wyklad = $mysql->query($zapytanie_wyklad);
    
    if ($result_wyklad->num_rows == 1)
    {
        $row_wyklad = $result_wyklad->fetch_array();
        $zapytanie = 'UPDATE wyklady SET temat="' . $row_wyklad['id'] . '" WHERE temat="' . $id . '"';
        $result  = $mysql->query($zapytanie);
        
        $delete = 'DELETE FROM tematy_wyklad WHERE id=' . $id;
        $mysql->query($delete);
        
        header ("Location: ../page/tematy-wyklad.php");
        exit();
        
    } else
    {
        header ("Location: ../page/tematy-wyklad.php");
        exit();
    }
    
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}



?>