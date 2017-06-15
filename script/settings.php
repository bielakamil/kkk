<?php
session_start ();
include ('../script/mysql.php');
$mysql = connect_mysql();
$status = $_SESSION['status'];
$pesel = $_SESSION['pesel'];
$time = $_POST['time'];
$show_car = $_POST['show_car'];
$alias = $_POST['alias'];
if ($status == 1)
{
$zapytanie = 'SELECT administratorzy.czas_wygasniecia FROM administratorzy WHERE administratorzy.pesel="' . $pesel . '"'; 
$result = $mysql->query($zapytanie);
$row = $result->fetch_array();
if ($time != $row['czas_wygasniecia'])
{
    $zapytanie_time = 'UPDATE administratorzy SET czas_wygasniecia=' . $time . ' WHERE pesel="' . $pesel . '"';
    $mysql->query($zapytanie_time);
}
    
// SPRAWDZAMY CZY NIE ISTNIEJE JUŻ TAKI LOGIN
$zapytanie_sprawdz = 'SELECT pesel FROM password WHERE (pesel="' . $alias . '" OR login="' . $alias . '")';
$result_sprawdz = $mysql->query($zapytanie_sprawdz);

if ($result_sprawdz->num_rows != 0)
{
    header ("Location: ../page/dashboard.php");
    exit();
} else
{
    $update = 'UPDATE password SET login="' . $alias . '" WHERE pesel="' . $pesel . '"';
    $mysql->query($update);
}
    
} elseif ($status == 2)
{
$zapytanie = 'SELECT instruktorzy.czas_wygasniecia, instruktorzy.czas_jazdy, instruktorzy.show_car FROM instruktorzy WHERE instruktorzy.pesel="' . $pesel . '"'; 
$result = $mysql->query($zapytanie);
$row = $result->fetch_array();    
if ($time != $row['czas_wygasniecia'])
{
    $zapytanie_time = 'UPDATE instruktorzy SET czas_wygasniecia=' . $time . ' WHERE pesel="' . $pesel . '"';
    $mysql->query($zapytanie_time);
}

$insert_data = $_POST['czas_jazdy'];
    
if ($insert_data != $row['czas_jazdy'])
{
    $zapytanie_jazda = 'UPDATE instruktorzy SET czas_jazdy="' . $insert_data . '" WHERE pesel="' . $pesel . '"';
    $mysql->query($zapytanie_jazda);
}

if ($row['show_car'] != $show_car)
{
    $zapytanie_show_car = 'UPDATE instruktorzy SET show_car="' . $show_car . '" WHERE pesel="' . $pesel . '"';
    $mysql->query($zapytanie_show_car);
}
    
// SPRAWDZAMY CZY NIE ISTNIEJE JUŻ TAKI LOGIN
$zapytanie_sprawdz = 'SELECT pesel FROM password WHERE (pesel="' . $alias . '" OR login="' . $alias . '")';
$result_sprawdz = $mysql->query($zapytanie_sprawdz);

if ($result_sprawdz->num_rows != 0)
{
    header ("Location: ../page/settings.php");
    exit();
} else
{
    $update = 'UPDATE password SET login="' . $alias . '" WHERE pesel="' . $pesel . '"';
    $mysql->query($update);
}    
  
    
} elseif ($status == 3)
{
  
$private_profil = $_POST['private_profil'];
    
$zapytanie = 'SELECT private_profil FROM uczniowie WHERE pesel="' . $pesel  . '"';
$result = $mysql->query($zapytanie);
    
if ($result->num_rows == 1)
{
    $row = $result->fetch_array();
    
    if ($private_profil != $row['private_profil'])
    {
        $update = 'UPDATE uczniowie SET private_profil="' . $private_profil . '" WHERE pesel="' . $pesel . '"';
        $result = $mysql->query($update);
    }
    
    
    
} else
{
    header ("Location: ../page/settings.php");
}
    
}


header ("Location: ../page/settings.php");
?>