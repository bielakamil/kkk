<?php

include ('../script/mysql.php');
$mysql = connect_mysql();


$zapytanie = 'SELECT id, stan FROM uczniowie WHERE (uczniowie.stan!=6 AND uczniowie.stan!=5)';

$result = $mysql->query($zapytanie);

if ($result->num_rows > 0)
{
    while ($row = $result->fetch_array())
    {
        $id = $row['id'];
        $stan = $row['stan'];
        
        $zapytanie_zadanie = 'SELECT waga,ocena FROM zadania_uczen, zadania_wyzwania WHERE zadania_uczen.zadanie=zadania_wyzwania.id AND zadania_uczen.uczen=' . $id;
        $result_zadanie = $mysql->query($zapytanie_zadanie);
        
        if ($result_zadanie->num_rows == 0)
        {
            $update = 'UPDATE uczniowie SET procent=100 WHERE id=' . $id;
            $mysql->query($update);
        } else
        {
            $suma_ocen = 0;
            $suma_wag = 0;
            while ($row_zadanie = $result_zadanie->fetch_array())
            {
                $suma_ocen = $suma_ocen+($row_zadanie['ocena']*$row_zadanie['waga']);
                $suma_wag = $suma_wag+$row_zadanie['waga'];
            }
            $procent = $suma_ocen/$suma_wag*100;
            echo 'Uczeń: ' . $id . '<br>';
            echo $suma_ocen . '<br>';
            echo $suma_wag . '<br>';
            echo $procent;
            echo '<br> <br>';
            

            $update = 'UPDATE uczniowie SET procent=' . $procent . ' WHERE id=' . $id;
            //$mysql->query($update);
        }
        
        
    }
} else
{
    exit();
}

exit();




?>