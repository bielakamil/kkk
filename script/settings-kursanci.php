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
            // DODAWANIE KURSU
        
            $nazwa = $_POST['nazwa'];
            $cena = $_POST['cena'];
            $jazdy = $_POST['jazdy'];
        
            $insert = 'INSERT INTO kursy (nazwa,cena,jazdy) VALUES ("' . $nazwa . '","' . $cena . '","' . $jazdy . '")';
        
            $result = $mysql->query($insert);
            
            $zapytanie = 'SELECT id FROM kursy ORDER BY id DESC LIMIT 1';
            $result = $mysql->query($zapytanie);
            $row = $result->fetch_array();
        
            header ("Location: ../page/kurs.php?id=" . $row['id']);
            exit();
        
            break;
            }
        case 2:
            {

            
        
            
            break;
            }
        case 3:
            {
                
           
            break;
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