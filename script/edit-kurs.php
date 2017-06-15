<?php


if (isset($_POST['id'])){

    include ('../script/mysql.php');
    $mysql = connect_mysql();
    $id = $_POST['id'];
    $nazwa = $_POST['nazwa'];
    $cena = $_POST['cena'];
    $jazdy = $_POST['jazdy'];
    
    $zapytanie = 'SELECT nazwa, cena, jazdy FROM kursy WHERE id=' . $id;
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        
        if ($nazwa != $row['nazwa'])
        {
            $update = 'UPDATE kursy SET nazwa="' . $nazwa . '" WHERE id=' . $id;
            $result = $mysql->query($update);
        }
        
        if ($cena != $row['cena'])
        {
            $update = 'UPDATE kursy SET cena="' . $cena . '" WHERE id=' . $id;
            $result = $mysql->query($update);
        }
        
        if ($jazdy != $row['jazdy'])
        {
            $update = 'UPDATE kursy SET jazdy="' . $jazdy . '" WHERE id=' . $id;
            $result = $mysql->query($update);
        }
        
        header ("Location: ../page/kurs.php?id=" . $id);
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