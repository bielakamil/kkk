<?php

if (isset($_GET['imie']))
{

include ('../script/mysql.php');

$mysql = connect_mysql();

$pesel = $_GET['pesel'];
$imie = $_GET['imie'];
$nazwisko = $_GET['nazwisko'];
$email = $_GET['email'];    
$telefon = $_GET['telefon'];
$data_urodzenia = $_GET['data_urodzenia'];
$kurs = $_GET['kurs'];    
$pkk = $_GET['pkk'];  
//SPRAWDZAMY CZY UCZNIA O DANYM PESELU JUŻ NIE MA W BAZIE
$zapytanie_pesel = 'SELECT pesel FROM password WHERE pesel="' . $pesel . '"';
$result_pesel = $mysql->query($zapytanie_pesel);

if ($result_pesel->num_rows == 1)
{
header ("Location: http://www.blog.mojeprawko.pl/uzytkownik-juz-istnieje/");
exit();
 
$zapytanie_kurs = 'SELECT nazwa,cena,jazdy FROM kursy WHERE id=' . $kurs;   
$result_kurs = $mysql->query($zapytanie_kurs);
    
if ($result_kurs->num_rows == 1)
{
    $row_kurs = $result_kurs->fetch_array();
    $insert = 'INSERT INTO uczniowie_www (id,pesel,imie,nazwisko,email,telefon,data_urodzenia,kurs,cena,jazdy,data,pkk) VALUES (null,"' . $pesel . '","' . $imie . '","' . $nazwisko . '","' . $email . '","' . $telefon . '","' . $data_urodzenia . '","' . $row_kurs['nazwa'] . '","' . $row_kurs['cena'] . '","' . $row_kurs['jazdy'] . '",now(),"' . $pkk . '")';
    $mysql->query($insert);
    echo $insert;
    header ("Location: http://www.blog.mojeprawko.pl/dolaczyles/");
    exit();
    
    
} else
{
    header ("Location: http://www.blog.mojeprawko.pl/zapisz-sie/");
    exit();  
}
} else
{
    header ("Location: http://www.blog.mojeprawko.pl/zapisz-sie/");
    exit();
}




?>