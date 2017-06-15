<?php


// SPRAWDZENIE CZY FORMULARZ ZOSTAŁ PRZESŁANY
if ((isset($_POST['kwota'])) and (isset($_POST['sposob'])) and (isset($_POST['instruktor'])))
{

// UTWORZENIE ZMIENNYCH    
$kwota = $_POST['kwota'];
$sposob = $_POST['sposob'];
$instruktor = $_POST['instruktor'];
$data = date ('Y-m-d H:i:s');

// DOŁĄCZENIE BAZY DANYCH
include ('../script/mysql.php');
$mysql = connect_mysql();
    
// SPRAWDZENIE CZY INSTRUKTOR ISTNIEJE 
$zapytanie_instruktor = 'SELECT id FROM instruktorzy WHERE id=' . $instruktor;
$result_instruktor = $mysql->query($zapytanie_instruktor);
if ($result_instruktor->num_rows == 0)
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    
// POBRANIE ID ADMINA
session_start();
if ((isset($_SESSION['status'])) and ($_SESSION['status'] == 1))
{
    $admin = $_SESSION['pesel'];
    $zapytanie_admin = 'SELECT id FROM administratorzy WHERE pesel="' . $admin . '"';
    $result_admin = $mysql->query($zapytanie_admin);
    if ($result_admin->num_rows == 1)
    {
        $row_admin = $result_admin->fetch_array();
        $id_admin = $row_admin['id'];  
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
    
// POBRANIE AKTUALNEJ KWOTU INSTRUKTORA
$zapytanie_portfel = 'SELECT aktualnie FROM budzet WHERE instruktor=' . $instruktor . ' ORDER BY data DESC LIMIT 1';
$result_portfel = $mysql->query($zapytanie_portfel);
if ($result_portfel->num_rows == 0)
{
    $portfel=0;
} else
{
    $row_portfel = $result_portfel->fetch_array();
    $portfel = $row_portfel['aktualnie'];
}

// SPRAWDZENIE CZY TO WPŁATA CZY WYPŁATA I DODANIE/ODEJMOWANIE KWOTY    
if ($sposob === '1')
{
    // WPŁATA KWOTY
    $przyszly_portfel = $portfel+$kwota;
    $kod=2;
} else
{
    // WYPŁATA KWOTY
    $przyszly_portfel = $portfel-$kwota;
    $kod=102;
}
    
$insert = 'INSERT INTO budzet (id,instruktor,data,kwota,wczesniej,aktualnie,status,id_status) VALUES (null,"' . $instruktor . '","' . $data . '","' . $kwota . '","' . $portfel . '","' . $przyszly_portfel . '","' . $kod . '","' . $id_admin . '")';
$mysql->query($insert);
    
header ("Location: ../page/show_portfel.php?id=" . $instruktor);
exit();
    
    
} else
{
    // PRZEKIEROWANIE JEŚLI FORMULARZ ZOSTAŁ WYSŁANY NIEPOPRAWNIE
    header ("Location: ../page/dashboard.php");
    exit();
}
?>