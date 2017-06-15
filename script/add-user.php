<?php

if (isset ($_POST['pesel']))
{
    session_start();
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    $pesel = $_POST['pesel'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $data_urodzenia = $_POST['data_urodzenia'];
    $plec = $_POST['plec'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];
    $kurs = $_POST['kurs'];
    $instruktor_id = $_POST['instruktor'];
    $pkk = $_POST['pkk'];
    $stopien = $_POST['stopien'];
    $wplata = $_POST['wplata'];
    $data_zapisu = $_POST['data_zapisu'];
    $notatka = $_POST['notatka'];
    $kategoria = $_POST['kategoria'];
    $grupa = $_POST['grupa'];
    
    $telefon = str_replace(' ', '', $telefon);
    $pesel = str_replace(' ', '', $pesel);
    $pkk = str_replace(' ', '', $pkk);
    
    if (empty($_POST['miejsce']))
    {
        $miejsce = 'Nie ustalono';
    } else
    {
        $miejsce = $_POST['miejsce'];
    }

    
    $_SESSION['add_pesel'] = $pesel;
    $_SESSION['add_imie'] = $imie;
    $_SESSION['add_nazwisko'] = $nazwisko;
    $_SESSION['add_data_urodzenia'] = $data_urodzenia;
    $_SESSION['add_plec'] = $plec;
    $_SESSION['add_telefon'] = $telefon;
    $_SESSION['add_email'] = $email;
    $_SESSION['add_kurs'] = $kurs;
    $_SESSION['add_instruktor'] = $instruktor_id;
    $_SESSION['add_pkk'] = $pkk;
    
    
    if ($instruktor_id == 0)
    {

        $zapytanie_ile = 'SELECT instruktor, count(*) AS ile FROM uczniowie WHERE stan=1 OR stan=2 OR stan=3 GROUP BY instruktor ORDER BY ile LIMIT 1';
        
        $result_ile = $mysql->query($zapytanie_ile);
   
        $row_ile = $result_ile->fetch_array();
        
        $instruktor = $row_ile['instruktor'];
              
    } else
    {
       
        $instruktor = $instruktor_id;
    }
    
    
    if ($kurs  == 0) 
    {
        $nazwa_kurs = "Niestandardowy";
        $cena = $_POST['cena'];
        $jazdy = $_POST['jazdy'];
    } elseif ($kurs == -1)
    {
        $nazwa_kurs = "Dodatek";
        $cena = $_POST['cena'];
        $jazdy = $_POST['jazdy'];  

    } else
    {
        $zapytanie_kurs = 'SELECT nazwa,cena,jazdy FROM kursy WHERE id=' . $kurs;
        $result_kurs = $mysql->query($zapytanie_kurs);
        $row_kurs = $result_kurs->fetch_array();
        $nazwa_kurs = $row_kurs['nazwa'];
        $cena = $row_kurs['cena'];
        $jazdy = $row_kurs['jazdy'];
    }
    
    $czas_wygasniecia = 10;
            
    $passwd = $_POST['passwd'];
//    $liczby = '1234567890';
//    for($i=0; $i<8; $i++)
//    {
//        $passwd.=$liczby{rand(0,strlen($liczby)-1)};
//    }
//    

    
    
    $zapytanie = 'SELECT id FROM uczniowie WHERE pesel="' . $pesel . '"';
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows != 0)
    {
        $row = $result->fetch_array();
        header ("Location: ../page/uczen.php?id=" . $row['id']);
    }
    
    $ile = 0;
    
    if (!preg_match('/^[0-9]{11}$/',$pesel)) //sprawdzamy czy pesel ma 11 cyfr
	{
        $_SESSION['log_pesel'] = 1; 
		$ile++;
	}  
    

    
    
    if ($ile > 0)
    {
        $_SESSION['add_true'] = true;
        header ("Location: ../page/add-user.php");
    } else
    {
    
    
    
    $zapytanie = 'INSERT INTO uczniowie (id,pesel,imie,nazwisko,data_urodzenia,email,plec,stan,telefon,kurs,cena,jazdy,data_zapisu, czas_wygasniecia, instruktor,pkk,miejsce_spotkan,notatka,kategoria) VALUES (null,"' . $pesel . '", "' . $imie . '","' . $nazwisko . '","' . $data_urodzenia . '","' . $email . '",' . $plec . ',"' . $stopien . '","' . $telefon .'","' . $nazwa_kurs . '","' . $cena . '","' . $jazdy . '","' . $data_zapisu . '","' . $czas_wygasniecia . '","' .  $instruktor . '","' . $pkk . '","' . $miejsce . '","' . $notatka . '","' . $kategoria . '")';
    
    $mysql->query($zapytanie);
    $hash = password_hash($passwd,PASSWORD_DEFAULT);
    $zapytanie = 'INSERT INTO password (PESEL,HASLO) VALUES ("' . $pesel . '", "' . $hash . '")';
    $result  = $mysql->query($zapytanie);
    
    $zapytanie = 'SELECT id FROM uczniowie ORDER BY id DESC LIMIT 1 ';
    
    $result = $mysql->query($zapytanie);
    $row = $result->fetch_array();
    $id = $row['id'];
        
   
    // USUWANIE ZMIENNYCH SESYJNYCH
        
    unset($_SESSION['add_pesel']);
    unset($_SESSION['add_imie']);
    unset($_SESSION['add_nazwisko']);
    unset($_SESSION['add_data_urodzenia']);
    unset($_SESSION['add_plec']);
    unset($_SESSION['add_telefon']);
    unset($_SESSION['add_email']);
    unset($_SESSION['add_kurs']);
    unset($_SESSION['add_instruktor']);
    unset($_SESSION['add_true']);  
    unset($_SESSION['add_pkk']);  
        
        
    $uczen = $imie . ' ' . $nazwisko;
    // WYSYŁANIE E-MAILA    
    include ('../script/szablon_email.php');
    include ('../script/email.php');    
    $user = new e_new_uczen ($passwd,$uczen);
    send_mail($email,$user);    
    
        
        
// Powiadomienie 
    
        
$zapytanie_instruktor = 'SELECT instruktorzy.pesel FROM instruktorzy WHERE id=' . $instruktor;
        
$result_instruktor = $mysql->query($zapytanie_instruktor);
$row_instruktor = $result_instruktor->fetch_array();


        
$insert_powiadomienie = 'INSERT INTO powiadomienia (id,data,nadawca,odbiorca,stan,numer,ID_tresc) VALUES (null,"' . $data_zapisu . '","' . $_SESSION['pesel'] . '","' . $row_instruktor['pesel'] . '","1","2","' . $id . '")';      
$mysql->query($insert_powiadomienie);
        
 // DODWANIE KURSANTA DO GRUPY
$insert_grupa = 'INSERT INTO kursanci_grupa VALUES (null,"' . $id . '","' . $grupa . '")';    
$mysql->query($insert_grupa);                
        
// DODAWANIE MIEJSC DO ODWIEDZENIE PODCZAS JAZD
$select_miejsca = 'SELECT id FROM miejsca';
$result_miejsca = $mysql->query($select_miejsca);
    
if ($result_miejsca->num_rows != 0)
{
    while ($row_miejsca = $result_miejsca->fetch_array())
    {
        $id_miejsca = $row_miejsca['id'];
        $insert_zadanie = 'INSERT INTO zadania_miejsca VALUES (null,"' . $id_miejsca . '","' . $id . '",0,null)';
        $mysql->query($insert_zadanie);
    }
}

// DODAWANIE ZALICZKI        
if ($wplata > 0)
{
    $insert_zaliczka = 'INSERT INTO wplaty (id,uczen,instruktor,kasa,data) VALUES (null,"' . $id . '","' . $instruktor . '","' . $wplata . '","' . $data_zapisu . '")';
    $mysql->query($insert_zaliczka);
}
        
    
header ("Location: ../page/uczen.php?id=" . $id);    
    }
    
} else 
{
   header ("Location: ../page/dashboard.php");
}



?>