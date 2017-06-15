<?php


include('../script/permissions.php');

echo '<pre>';
var_dump($_POST);
echo '</pre>';


admin_instruktor();
$status = $_SESSION['status'];



if ($status == 1) {
    // Sprawdzenie czy zalogowany użytkownik to administrator
    include("../script/admin.php"); // Dołączenie pliku z klasą administratora
    $admin = new admin(); // Utworzenie obiektu administratora
    $nadawca = 0;
} elseif ($status == 2) {
    include("../script/instruktor.php"); // Dołaczenie pliku z klasą instruktor
    $instruktor = new instruktor(); // Utworzenie obiektu instruktor
    $nadawca = $instruktor->id;
}

$mysql = connect_mysql();

$odbiorca = [];

if (isset($_POST['temat']))
{
    $temat = $_POST['temat'];
    $tresc = $_POST['tresc'];
    
    foreach ($_POST['odbiorca'] as $odb)
    {
        $od = explode ("-",$odb);
        
        if ($od[0] == 'ku')
        {
            // KURSANT
            
            $string = (string)$od[1];

            if (array_search($string,$odbiorca) == 0)
            {
                $odbiorca[] = $string;
            }
            
        } elseif ($od[0] == 'gr')
        {
            // GRUPA
            $zapytanie_grupa = 'SELECT kursanci_grupa.kursant FROM kursanci_grupa WHERE kursanci_grupa.grupa=' . $od[1];
            
            $result_grupa = $mysql->query($zapytanie_grupa);
            
            while ($row_grupa = $result_grupa->fetch_array())
            {

                $string = (string)$row_grupa['kursant'];

                if (array_search($string,$odbiorca) == 0)
                {
                    $odbiorca[] = $string;
                }
            }
            
        } elseif ($od[0] == 'st')
        {
            // STOPIEŃ 
            $zapytanie_stopien = 'SELECT uczniowie.id FROM uczniowie WHERE uczniowie.stan=' . $od[1];
            $result_stopien = $mysql->query($zapytanie_stopien);
            while ($row_stopien = $result_stopien->fetch_array())
            {
                $string = (string)$row_stopien['id'];

                if (array_search($string,$odbiorca) == 0)
                {
                    $odbiorca[] = $string;
                }
            }
        }

    }
    
    var_dump($odbiorca);

    foreach ($odbiorca as $odb)
    {
        $insert = 'INSERT INTO message_user (id, sender, receiver, title, text) VALUES (null,' . $nadawca . ',' . $odb . ',"' . $temat . '","' . $tresc . '")';
        $mysql->query($insert);
    }
    
    $result = $mysql->query('SELECT id FROM message_user ORDER BY id DESC LIMIT 1');
    $row = $result->fetch_array();
    
    header ("Location: ../page/message.php?id=" . $row['id']);
    
    
    
} else
{
    header ("Location: ../page/dashboard.php");
}

?>