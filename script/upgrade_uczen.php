<?php

if (isset($_GET['id']))
{
    
$id = $_GET['id'];
    
include ('../script/mysql.php');
$mysql = connect_mysql();
    
$zapytanie_uczen = 'SELECT pesel, stan FROM uczniowie WHERE id=' . $id;
    
$result_uczen = $mysql->query($zapytanie_uczen);
    
if ($result_uczen->num_rows != 1)
{
    header ("Location: ../page/dashboard.php");
}
    
$row_uczen = $result_uczen->fetch_array();
    
$stan = $row_uczen['stan'];
$pesel = $row_uczen['pesel'];
    
switch ($stan)
{
    case 1:
        {
            // CHODZI NA WYKŁADY
            
            $update_uczen = 'UPDATE uczniowie SET stan="2" WHERE id=' . $id;
            
            $mysql->query($update_uczen);
            
            header ("Location: ../page/uczen.php?id=" . $id);
            break;
        }
    case 2:
        {
            // OCZEKUJE NA JAZDE
    
            $update_uczen = 'UPDATE uczniowie SET stan="3" WHERE id=' . $id;
            
            $zapytanie_uczen = 'SELECT instruktor FROM uczniowie WHERE id=' . $id;
            $result_uczen = $mysql->query($zapytanie_uczen);
            $row_uczen = $result_uczen->fetch_array();
            $instruktor = $row_uczen['instruktor'];
    
            $zapytanie_zadanie = 'SELECT id FROM zadania_wyzwania';
            $result_zadanie = $mysql->query($zapytanie_zadanie);
            
            while ($row_zadanie = $result_zadanie->fetch_array())
            {
                $insert = 'INSERT INTO zadania_uczen (id,zadanie,uczen,ocena) VALUES (null,"' . $row_zadanie['id'] . '","' . $id . ', ","0")';
                echo $insert;
                $mysql->query($insert);
            }
        
            $mysql->query($update_uczen);
            header ("Location: ../page/uczen.php?id=" . $id);
            break;
        }
    case 3:
        {
            // CHODZI NA JAZDY
            $update_uczen = 'UPDATE uczniowie SET stan="4" WHERE id=' . $id;
            $mysql->query($update_uczen);
    
            header ("Location: ../page/uczen.php?id=" . $id);
            break;
        }
    case 4:
        {
            // OCZEKUJE NA EGZAMIN
    
            $update_uczen = 'UPDATE uczniowie SET stan="5" WHERE id=' . $id;
            
            $mysql->query($update_uczen);
            header ("Location: ../page/uczen.php?id=" . $id);
            break;
        }
    case 5:
        {
            header ("Location: ../page/dashboard.php");
            break;
        }
    case 6:
        {
            // ZREZYGNOWAŁ
            header ("Location: ../page/dashboard.php");
            break;
        }
}
    
    
    
} else
{
header ("Location: ../page/dashboard.php");    
}



?>