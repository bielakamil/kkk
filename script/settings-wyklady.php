<?php

include ('../script/mysql.php');

$mysql = connect_mysql();

if (isset($_POST['what']))
{
    $what = $_POST['what'];
    
    echo $what;
    
    switch ($what)
    {
        case 1:
            {
            $miejsce = $_POST['miejsce_wykladow'];
            
            $insert = 'INSERT INTO wyklady_miejsce (id,nazwa) VALUES (null,"' . $miejsce . '")';
            $mysql->query($insert);
        
            $zapytanie_last = 'SELECT id FROM wyklady_miejsce ORDER BY id DESC LIMIT 1';
            $result_last = $mysql->query($zapytanie_last);
            $row_last = $result_last->fetch_array();
            
            header ("Location: ../page/miejsce-wyklad.php?id=" . $row_last['id']);
            exit();
        
        
            break;
            }
        case 2:
            {
        
            $temat = $_POST['temat'];
            $kategoria = $_POST['kategoria_wyklad'];
            $notatka = $_POST['notatka'];
            $czas = $_POST['czas'];
            $insert = 'INSERT INTO tematy_wyklad (id,temat,kategoria,notatka,czas) VALUES (null,"' . $temat . '","' . $kategoria . '","' . $notatka . '","' . $czas . '")';
            $mysql->query($insert);
        
            $zapytanie_last = 'SELECT id FROM tematy_wyklad ORDER BY id DESC LIMIT 1';
            $result_last = $mysql->query($zapytanie_last);
            $row_last = $result_last->fetch_array();
            
            header ("Location: ../page/temat-wyklad.php?id=" . $row_last['id']);
            
            break;
            }
        case 3:
            {
                
            $kategoria = $_POST['kategoria_nazwa'];
        
            $insert = 'INSERT INTO kategorie_wyklad (id,nazwa) VALUES (null,"' . $kategoria . '")';
            $mysql->query($insert);
        
            $zapytanie_last = 'SELECT id FROM kategorie_wyklad ORDER BY id DESC LIMIT 1';
            $result_last = $mysql->query($zapytanie_last);
            $row_last = $result_last->fetch_array();
            
            header ("Location: ../page/kategoria-wyklad.php?id=" . $row_last['id']);
            }
    }
    
    //header ("Location: ../page/dashboard.php");
    exit();

} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}



?>