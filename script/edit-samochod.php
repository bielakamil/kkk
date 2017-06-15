<?php

if (isset($_POST['id']))
{
    $id = $_POST['id'];
    $nazwa = $_POST['nazwa'];
    $rejestracja = $_POST['rejestracja'];
    $marka = $_POST['marka'];
    $model = $_POST['model'];
    $data_produkcji = $_POST['data_produkcji'];
    $silnik = $_POST['silnik'];
    $skrzynia = $_POST['skrzynia'];
    $paliwo = $_POST['paliwo'];
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT nazwa,rejestracja, marka, model, data_produkcji, silnik, skrzynia, paliwo FROM samochody WHERE id=' . $id;
    $result = $mysql->query($zapytanie);
    if ($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        
        if ($row['nazwa'] != $nazwa)
        {
            $update = 'UPDATE samochody SET nazwa="' . $nazwa . '" WHERE id=' . $id;
            $mysql->query($update);
        }
        
        if ($row['rejestracja'] != $rejestracja)
        {
            $update = 'UPDATE samochody SET rejestracja="' . $rejestracja . '" WHERE id=' . $id;
            $mysql->query($update);
        }       
        
        if ($row['marka'] != $marka)
        {
            $update = 'UPDATE samochody SET marka="' . $marka . '" WHERE id=' . $id;
            $mysql->query($update);
        }     
        
        if ($row['model'] != $model)
        {
            $update = 'UPDATE samochody SET model="' . $model . '" WHERE id=' . $id;
            $mysql->query($update);
        }
        
        if ($row['data_produkcji'] != $data_produkcji)
        {
            $update = 'UPDATE samochody SET data_produkcji="' . $data_produkcji . '" WHERE id=' . $id;
            $mysql->query($update);
        }
        
        if ($row['silnik'] != $silnik)
        {
            $update = 'UPDATE samochody SET silnik="' . $silnik . '" WHERE id=' . $id;
            $mysql->query($update);
        }
        
        if ($row['skrzynia'] != $skrzynia)
        {
            $update = 'UPDATE samochody SET skrzynia="' . $skrzynia . '" WHERE id=' . $id;
            $mysql->query($update);
        }
        
        if ($row['paliwo'] != $paliwo)
        {
            $update = 'UPDATE samochody SET paliwo="' . $paliwo . '" WHERE id=' . $id;
            $mysql->query($update);
        }
        
        
        header ("Location: ../page/samochod.php?id=" . $id);
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


?>