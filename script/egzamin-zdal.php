<?php

if ((isset($_GET['id'])) and (isset($_GET['stan'])))
{
    
    $egzamin = $_GET['id'];
    $stan = $_GET['stan'];
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT stan,uczen FROM egzamin WHERE id=' . $egzamin;
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        
        if ($row['stan'] == 1)
        {
            if ($stan == 1)
            {
                $update = 'UPDATE egzamin SET stan=2 WHERE id=' . $egzamin;
                $mysql->query($update);
                $update_uczen = 'UPDATE uczniowie SET stan=5 WHERE id=' . $row['uczen'];
                $mysql->query($update_uczen);
            } elseif ($stan == 2)
            {
                $update = 'UPDATE egzamin SET stan=3 WHERE id=' . $egzamin;
                $mysql->query($update);
            } else
            {
                header ("Location: ../page/egzamin.php?id=" . $egzamin);
                exit();    
            }   
        } else
        {
            header ("Location: ../page/egzamin.php?id=" . $egzamin);
            exit();
        }
        
        header ("Location: ../page/egzamin.php?id=" . $egzamin);
        
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