<?php

if ((isset($_POST['id'])) and (isset($_POST['pakiet'])))
{
 
    $uczen = $_POST['id'];
    $pakiet = $_POST['pakiet'];
    $data = date ('Y-m-d H:i:s');
    session_start();
    include ('../script/mysql.php');
    $mysql = connect_mysql ();
    
    // SPRAWDZAMY CZY PAKIET JEST STANDARDOWY CZY NIE
    if ($pakiet == 0)
    {
        $kasa = $_POST['cena'];
        $jazdy = $_POST['jazdy'];
    } else
    {
        $zapytanie_pakiet = 'SELECT cena,godziny FROM pakiety WHERE id=' . $pakiet;
        $result_pakiet = $mysql->query($zapytanie_pakiet);
        if ($result_pakiet->num_rows == 0)
        {
            header ("Location: ../page/dashboard.php");
            exit();
        } else
        {
            $row_pakiet = $result_pakiet->fetch_array();
            $kasa = $row_pakiet['cena'];
            $jazdy = $row_pakiet['godziny'];
        }
    }
    
    
    
    // SPRAWDZAMY CZY UCZEŃ NIE ZDAŁ JUŻ PRAWA JAZDY BĄDŹ NIE ZREZYGNOWAŁ
    $zapytanie_uczen = 'SELECT stan,pesel FROM uczniowie WHERE id=' . $uczen;
    $result_uczen = $mysql->query($zapytanie_uczen);
    
    if ($result_uczen->num_rows != 1)
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    $row_uczen = $result_uczen->fetch_array();
    $pesel_uczen = $row_uczen['pesel'];
    if (($row_uczen['stan'] == 5) or ($row_uczen['stan'] == 6))
    {
        header ("Location: ../page/uczen.php?id=" . $uczen);
        exit();
    }
    
    $insert_pakiet = 'INSERT INTO dodatkowe_jazdy (id,uczen,data,jazdy,cena) VALUES (null,"' . $uczen . '","' . $data . '","' . $jazdy . '","' . $kasa . '")';
    
    $result = $mysql->query($insert_pakiet);
    // DODAWANIE POWIADOMIENIA 
    
    $zapytanie_pakiet = 'SELECT id FROM dodatkowe_jazdy ORDER BY id DESC LIMIT 1';
    $result_pakiet = $mysql->query($zapytanie_pakiet);
    $row_pakiet = $result_pakiet->fetch_array();
    $jazdy_id = $row_pakiet['id'];
    
    $insert_powiadomienie = 'INSERT INTO powiadomienia (id,data,nadawca,odbiorca,stan,numer,ID_tresc) VALUES (null,"' . $data . '","' . $_SESSION['pesel'] . '","' . $pesel_uczen . '","1","5","' . $jazdy_id . '")';
    
    $mysql->query($insert_powiadomienie);
    
    header ("Location: ../page/uczen.php?id=" . $uczen);
    exit();
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


?>