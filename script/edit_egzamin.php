<?php

if (isset($_POST['id']))
{
    $id = $_POST['id'];
    $data = $_POST['data'] . ' ' . $_POST['time'] . ':00';
    $stan = $_POST['stan'];
    $notatka = $_POST['notatka'];
    $rodzaj = $_POST['rodzaj'];
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT data, stan, uczen, notatka,typ FROM egzamin WHERE id=' . $id;
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        
        if ($row['data'] != $data)
        {
            $update = 'UPDATE egzamin SET data="' . $data . '" WHERE id=' . $id;
            $result = $mysql->query($update);
        }
        
        if ($row['stan'] != $stan)
        {
            $update = 'UPDATE egzamin SET stan="' . $stan . '" WHERE id=' . $id;
            $result = $mysql->query($update);
        }
        
        if ($row['notatka'] != $notatka)
        {
            $update = 'UPDATE egzamin SET notatka="' . $notatka . '" WHERE id=' . $id;
            $result = $mysql->query($update);
        }
        
        if ($row['typ'] != $rodzaj)
        {
            $update = 'UPDATE egzamin SET typ="' . $rodzaj . '" WHERE id=' . $id;
            $result = $mysql->query($update);
        }
        
        
        
        header ("Location: ../page/egzamin.php?id=" . $id);
        
        
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