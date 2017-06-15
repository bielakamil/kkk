<?php

if ((isset($_POST['wplata'])) and (isset($_POST['id'])))  // Jeśli formularz nie został pomyślnie przesłany to przenoszeni jesteśmy na stronę główną
{
 
    session_start(); // Rozpoczęcie sesji 
    
    // UTWORZENIE ZMIENNYCH
    $wplata = $_POST['wplata'];
    $uczen = $_POST['id'];
    $instruktor = $_SESSION['pesel'];
    $data = date ('Y-m-d H:i:s');
    
    
    // DOŁĄCZENIE PLIKU Z BAZĄ DANYCH I POŁĄCZENIE SIĘ
    include ('../script/mysql.php');
    $mysql = connect_mysql ();
    
    // POBRANIA ID INSTRUKTORA Z BAZY DANYCH
    $zapytanie_instruktor = 'SELECT id FROM instruktorzy WHERE pesel="' . $instruktor . '"';
    $result_instruktor = $mysql->query($zapytanie_instruktor);
    $row_instruktor = $result_instruktor->fetch_array();
    $id_instruktor = $row_instruktor['id']; // ID INSTRUKTORA
    
    
    // POBRANIE PESELU UCZNIA Z BAZY DANYCH
    $zapytanie_uczen = 'SELECT pesel FROM uczniowie WHERE id=' . $uczen;
    $result_uczen = $mysql->query($zapytanie_uczen);
    $row_uczen = $result_uczen->fetch_array();
    $pesel_uczen = $row_uczen['pesel'];
    
    
    
    if ($wplata <= 0) // JEŚLI WPŁATA BĘDZIE PONIŻEJ, BĄDŹ RÓWNA 0 TO PRZENOSZENI JESTEŚMY NA STRONĘ Z WPŁATĄ
    {
//        header ("Location: ../page/add-wplata.php?id=" . $uczen);
        exit();
    } else
    {
        // DODANIE WPŁATY DO BAZY DANYCH
        $insert_wplata = 'INSERT INTO wplaty (id,uczen,instruktor,kasa,data) VALUES (null,"' . $uczen . '","' . $id_instruktor . '","' . $wplata . '","' . $data . '")';
        
        $mysql->query($insert_wplata);
        
        // OSTATNIA DODANA WPŁATA
        
        $zapytanie_wplata = 'SELECT id FROM wplaty ORDER BY id DESC LIMIT 1';
        $result_wplata = $mysql->query($zapytanie_wplata);
        $row_wplata = $result_wplata->fetch_array();
        $id_wplata= $row_wplata['id'];
        
        // DODANIE POWIADOMIENIA DLA UŻYTKOWNIKA
        $insert_powiadomienie = 'INSERT INTO powiadomienia (id,data,nadawca,odbiorca,stan,numer,ID_tresc) VALUES (null,"' . $data . '","' . $instruktor . '","' . $pesel_uczen . '","1","4","' . $id_wplata . '")';
        
        $mysql->query($insert_powiadomienie);
        
        // DODANIE PIENIĘDZY DO PORTFELA INSTRUKTORA JEŚLI PIENIĄDZE DODAWANE SĄ PRZEZ INSTRUKTORA I PIENIĄDZE SĄ PRZEKAZYWANE DO RĘKI INSTRUKTORA
        if (!isset($_POST['konto']))
        {
            $zapytanie_portfel = 'SELECT aktualnie FROM budzet WHERE instruktor=' . $id_instruktor . ' ORDER BY data DESC LIMIT 1';
            $result_portfel = $mysql->query($zapytanie_portfel);
            if ($result_portfel->num_rows == 0)
            {
                $aktualny_portfel = 0;
            } else
            {
                $row_portfel = $result_portfel->fetch_array();
                $aktualny_portfel = $row_portfel['aktualnie'];
            }
            $przyszly_portfel = $aktualny_portfel+$wplata;
            $insert_portfel = 'INSERT INTO budzet (id,instruktor,data,kwota,wczesniej,aktualnie,status,id_status) VALUES (null,"' . $id_instruktor . '","' . $data . '","' . $wplata . '","' . $aktualny_portfel . '","' . $przyszly_portfel . '","1","' . $id_wplata . '")';
            $mysql->query($insert_portfel);
            
        }
        
        header ("Location: ../page/uczen.php?id=" . $uczen);
    }
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}



?>