<?php

if ((isset($_GET['wyklad'])) and (isset($_GET['uczen'])))
{
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    $wyklad = $_GET['wyklad'];
    $uczen = $_GET['uczen'];
    
    // SPRAWDZAMY CZY TAKI WYKŁAD ISTNIEJE
    $zapytanie_wyklad = 'SELECT stan FROM wyklady WHERE id=' . $wyklad;
    $result_wyklad = $mysql->query($zapytanie_wyklad);
    
    if ($result_wyklad->num_rows == 1)
    {
        // SPRAWDZAMY CZY UCZEŃ BYŁ OBECNY NA WYKŁADZIE
        $zapytanie_obecnosc = 'SELECT id FROM obecnosc WHERE wyklad=' . $wyklad . ' AND uczen=' . $uczen;
        $result_obecnosc = $mysql->query($zapytanie_obecnosc);
        
        if ($result_obecnosc->num_rows == 1)
        {
        // USUWAMY OBECNOŚĆ
        $row_obecnosc = $result_obecnosc->fetch_array();
        $id = $row_obecnosc['id'];
        $delete_obecnosc = 'DELETE FROM obecnosc WHERE wyklad=' . $wyklad . ' AND uczen=' . $uczen . ' AND id=' . $id;
        $mysql->query($delete_obecnosc);
        
        header ("Location: ../page/add-wyklad.php?id=" . $wyklad);
        exit();
            
        } else
        {
            header ("Location: ../page/dashboard.php");
            exit();    
        }
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


?>