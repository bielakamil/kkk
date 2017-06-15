<?php
if (isset($_POST['pesel']))
{
include ('../script/mysql.php');  // Dołączanie pliku z funkcją mysql
$mysql = connect_mysql(); // Połączenie z bazą danych
session_start (); // Start sesji
$pesel = $_POST['pesel'];   
$imie = $_POST['imie'];
$nazwisko = $_POST['name'];
$plec = $_POST['sex'];
$telefon = $_POST['phone'];
$email = $_POST['email'];
$uprawnienia = $_POST['uprawnienia'];
$data_urodzenia = $_POST['data_urodzenia'];
    
if (isset($_POST['id']))
{
$id = $_POST['id'];   
} else
{
$id = null;
}

$zapytanie = 'SELECT id, imie, nazwisko,telefon,email, plec, nr_uprawnien, data_urodzenia FROM instruktorzy WHERE pesel="' . $pesel . '"';

$result = $mysql->query($zapytanie);
if ($result->num_rows != 1)
{
    header ("Location: ../page/error/niematakiegouzytkownika.php");
} else
{
    $row = $result->fetch_array();   
    if (($row['imie'] != $imie) and ($_SESSION['status'] == 1)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE instruktorzy SET imie="' . $imie . '" WHERE pesel="' . $pesel . '"';
            $mysql->query($zapytanie);
    }
    
    if (($row['nazwisko'] != $nazwisko) and ($_SESSION['status'] == 1)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE instruktorzy SET nazwisko="' . $nazwisko . '" WHERE pesel="' . $pesel . '"';
            $mysql->query($zapytanie);
    }
    
    if (($row['plec'] != $plec) and ($_SESSION['status'] == 1)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {

            $zapytanie = 'UPDATE instruktorzy SET plec="' . $plec . '" WHERE pesel="' . $pesel . '"';
            $mysql->query($zapytanie);
    }
    
    if ($row['telefon'] != $telefon) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE instruktorzy SET telefon="' . $telefon . '" WHERE pesel="' . $pesel . '"';
            $mysql->query($zapytanie);
    }
    
    if ($row['email'] != $email) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE instruktorzy SET email="' . $email . '" WHERE pesel="' . $pesel . '"';
            $mysql->query($zapytanie);
    }
    
    if ($row['nr_uprawnien'] != $uprawnienia) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE instruktorzy SET nr_uprawnien="' . $uprawnienia . '" WHERE pesel="' . $pesel . '"';
            $mysql->query($zapytanie);
    }
    
    if ($row['data_urodzenia'] != $data_urodzenia) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE instruktorzy SET data_urodzenia="' . $data_urodzenia . '" WHERE pesel="' . $pesel . '"';
            $mysql->query($zapytanie);
    }
}
}


if (($id == null) and ($_SESSION['status']) == 2)
{
header ('Location: ../page/instruktor-all-info.php'); // Na sam koniec przekierowywanie na stronę profilu   
} else
{
header ('Location: ../page/instruktor-all-info.php?id=' . $row['id']); // Na sam koniec przekierowywanie na stronę profilu    
}


?>