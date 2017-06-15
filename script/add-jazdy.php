<?php


if (isset($_POST['id']))
{

// PRZYPISUJEMY DO ZMIENNYCH
$uczen = $_POST['id'];   
$option = $_POST['option'];
$instruktor = $_POST['instruktor'];
$samochod = $_POST['samochod']; 
$data = date ('Y-m-d H:i:s');
$data_jazd = $_POST['data'];
$start_czas = $_POST['start_jazda'];    
$end_czas = $_POST['end_jazda'];    
$miejsce_spotkania = $_POST['miejsce_spotkania'];
    
if ($start_czas>$end_czas)
{
    header ("Location: ../page/add-jazdy.php");
}
    
    
$data_start = $data_jazd . ' ' . $start_czas;
$data_end = $data_jazd . ' ' . $end_czas;   
    
if ($_POST['notatka'] == null)
{
    $notatka = null;
} else
{
    $notatka = $_POST['notatka'];    
}

session_start();
 
if ($samochod == 0)
{
$samochod = null;
}

    
include ('../script/mysql.php');
$mysql = connect_mysql ();
    
$zapytanie_uczen = 'SELECT stan FROM uczniowie WHERE id=' . $uczen;   
$result_uczen = $mysql->query($zapytanie_uczen);
if ($result_uczen->num_rows != 1)
{
   header ("Location: ../page/dashboard.php");
} else
{   
    $row_uczen = $result_uczen->fetch_array();  
    $stan = $row_uczen['stan'];
    
    $zapytanie_insert = 'INSERT INTO jazdy (id,uczen,instruktor,data_zapisu,data_rozpoczecia,data_zakonczenia,samochod,stan,notatka,miejsce_spotkania) VALUES (null,"' . $uczen . '","' . $instruktor . '","'  . $data . '","' . $data_start . '","' . $data_end . '","' . $samochod . '","' . $option . '","' . $notatka . '","' . $miejsce_spotkania . '")';     
    
    $result = $mysql->query($zapytanie_insert);
    
    $zapytanie_pesel_uczen = 'SELECT pesel FROM uczniowie WHERE id=' . $uczen;
    $result_pesel_uczen = $mysql->query($zapytanie_pesel_uczen);
    $row_pesel_uczen = $result_pesel_uczen->fetch_array();
    $pesel_uczen = $row_pesel_uczen['pesel'];

    $zapytanie_id = 'SELECT id FROM jazdy ORDER BY id DESC LIMIT 1';
    $result_id = $mysql->query($zapytanie_id);
    $row_id = $result_id->fetch_array();
    $jazdy_id = $row_id['id'];
    $pesel_instruktor = $_SESSION['pesel'];
    
    if ($stan == 1)
    {
        $update = 'UPDATE uczniowie SET stan=2 WHERE id=' . $uczen;
        $mysql->query($update);
    }
    

    $zapytanie_powiadomienia = 'INSERT INTO powiadomienia (id,data,nadawca,odbiorca,stan,numer,ID_tresc) VALUES (null,"' . $data . '","' . $pesel_instruktor . '","' . $pesel_uczen . '","1","2","' . $jazdy_id  .'")';
    $result_powiadomienia = $mysql->query($zapytanie_powiadomienia);
    echo $zapytanie_insert;
header ("Location: ../page/jazda.php?id=" . $jazdy_id);
}
} else
{
    header ("Location: ../page/dashboard.php");
    
}



?>