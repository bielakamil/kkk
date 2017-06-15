<?php
if (isset($_POST['id']))
{
include ('../script/mysql.php');  // Dołączanie pliku z funkcją mysql
$mysql = connect_mysql(); // Połączenie z bazą danych
session_start (); // Start sesji
$pesel = $_POST['pesel'];  
$id = $_POST['id'];    
if ($_SESSION['status'] != 3)
{
$imie = $_POST['imie'];
$nazwisko = $_POST['name'];
$miejsce_spotkan = $_POST['miejsce_spotkan'];
$data_urodzenia = $_POST['data_urodzenia'];
$data_zapisu = $_POST['data_zapisu'];
$instruktor = $_POST['instruktor'];
$etap = $_POST['etap'];
$kategoria = $_POST['kategoria'];
$grupy = $_POST['grupa'];    
    

if (isset($_POST['notatka']))
{
 $notatka = $_POST['notatka'];      
}
if (isset($_POST['pkk']))
{
$pkk = $_POST['pkk']; 
}
    
    
$plec = $_POST['sex'];    



}

$telefon = $_POST['phone'];
$telefon = str_replace(' ', '', $telefon);    
$pkk = str_replace(' ', '', $pkk);    
$pesel = str_replace(' ', '', $pesel);
$email = $_POST['email'];
 
$kurs = $_POST['kurs'];
    
$zapytanie_kurs = 'SELECT nazwa,cena,jazdy FROM kursy WHERE id=' . $kurs;
$result_kurs = $mysql->query($zapytanie_kurs);
    
if ($result_kurs->num_rows == 1)
{
    $row_kurs = $result_kurs->fetch_array();
    $kurs = $row_kurs['nazwa'];
    $jazdy = $row_kurs['jazdy'];
    $cena = $row_kurs['cena'];
} elseif ($kurs == -1)
{
    $kurs = 'Dodatkowy';
    $cena = $_POST['cena'];
    $jazdy = $_POST['jazdy'];
} else
{
    $kurs = 'Niestandardowy';
    $cena = $_POST['cena'];
    $jazdy = $_POST['jazdy'];
}
    
$zapytanie = 'SELECT id,pesel, imie, nazwisko,telefon,data_urodzenia,email,data_zapisu,plec,miejsce_spotkan, kurs, notatka,pkk, jazdy,cena, instruktor,stan, kategoria FROM uczniowie WHERE id="' . $id . '"';
    
// WALIDACJA     
$ile=0;   

    
    
if ($ile > 0)
{
    header ("Location: ../page/edit-uczen.php");
}

    
if (isset($_POST['id']))
{
$id = $_POST['id'];
} else
{
$id = false;
}
    
$result = $mysql->query($zapytanie);
if ($result->num_rows != 1)
{
    // Błąd nie ma takiego użytkownika
    //header ("Location: ../page/error/niematakiegouzytkownika.php");
} else
{
    $row = $result->fetch_array();   

    
    if ($_SESSION['status'] != 3)
    {
    if (($row['imie'] != $imie) and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET imie="' . $imie . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }
    if (($row['pesel'] != $pesel) and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
        $zapytanie_pesel = 'SELECT pesel FROM password WHERE pesel="' . $pesel . '"';
        $result_pesel =  $mysql->query($zapytanie_pesel);
        
        if ($result_pesel->num_rows == 0)
        {
            $zapytanie = 'UPDATE uczniowie SET pesel="' . $pesel . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
            $zapytanie = 'UPDATE password SET pesel="' . $pesel . '" WHERE pesel="' . $row['pesel'] . '"';
            $mysql->query($zapytanie);
            $zapytanie = 'UPDATE powiadomienia SET nadawca="' . $pesel . '" WHERE nadawca="' . $row['pesel'] . '"';
            $mysql->query($zapytanie);
            $zapytanie = 'UPDATE powiadomienia SET odbiorca="' . $pesel . '" WHERE odbiorca="' . $row['pesel'] . '"';
            $mysql->query($zapytanie);
        } 
    }
    
    if (($row['nazwisko'] != $nazwisko)  and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET nazwisko="' . $nazwisko . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }
        
    if (($row['data_urodzenia'] != $data_urodzenia)  and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET data_urodzenia="' . $data_urodzenia . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    } 
        
    if (($row['data_zapisu'] != $data_zapisu)  and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET data_zapisu="' . $data_zapisu . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }
        
    if (($row['stan'] != $etap)  and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET stan="' . $etap . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }
        
    if (($row['pkk'] != $imie) and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET pkk="' . $pkk . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }    
        
    if (($row['instruktor'] != $instruktor) and ($ile == 0) and ($_SESSION['status'] == 1)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET instruktor="' . $instruktor . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }  
    if (($row['kurs'] != $kurs) and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET kurs="' . $kurs . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }    
    
    if (($row['plec'] != $plec) and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {

            $zapytanie = 'UPDATE uczniowie SET plec="' . $plec . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }   
        
    if (($row['miejsce_spotkan'] != $miejsce_spotkan) and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {

            $zapytanie = 'UPDATE uczniowie SET miejsce_spotkan="' . $miejsce_spotkan . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }  
        
        
    if ($row['notatka'] != $notatka)
        {
        $zapytanie = 'UPDATE uczniowie SET notatka="' . $notatka . '" WHERE id="' . $id . '"';
        $mysql->query($zapytanie);
        }
        
    if ($row['cena'] != $cena)
        {
        $zapytanie = 'UPDATE uczniowie SET cena="' . $cena . '" WHERE id="' . $id . '"';
        $mysql->query($zapytanie);
        }
        
    if ($row['jazdy'] != $jazdy)
        {
        $zapytanie = 'UPDATE uczniowie SET jazdy="' . $jazdy . '" WHERE id="' . $id . '"';
        $mysql->query($zapytanie);
        }    
    
    if (($row['kategoria'] != $kategoria) and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET kategoria="' . $kategoria . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }    
        
    }
    
    
    if (($row['telefon'] != $telefon) and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET telefon="' . $telefon . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }
    
    if (($row['email'] != $email) and ($ile == 0)) // Jeśli podane imię i imię w bazie danych są różne to dokonywana jest zamiana w bazie 
    {
            $zapytanie = 'UPDATE uczniowie SET email="' . $email . '" WHERE id="' . $id . '"';
            $mysql->query($zapytanie);
    }
    
    $delete_grupy = 'DELETE FROM kursanci_grupa WHERE kursant=' . $id;
    $mysql->query($delete_grupy);
    
    foreach ($grupy as $grupa)
    {
        $insert_grupa = 'INSERT INTO kursanci_grupa (id, kursant, grupa) VALUES (null,' . $id  . ',' . $grupa . ')';
        $mysql->query($insert_grupa);
        
    }

    
    
}

if ($id == false)
{
header ('Location: ../page/uczen.php'); // Na sam koniec przekierowywanie na stronę profilu   
} else
{
header ('Location: ../page/uczen-all.php?id=' . $id); // Na sam koniec przekierowywanie na stronę profilu    
}   
    
} else
{
    header ("Location: ../page/dashboard.php");
}



?>