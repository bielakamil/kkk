
<?php

// PRZYPISUJEMY ZMIENNE WYSŁANE PRZEZ FORMULARZ
session_start();

$instruktor = $_POST['instruktor'];
$stan = $_POST['stan'];
$id = $_POST['id'];
$temat = $_POST['temat'];
$miejsce = $_POST['miejsce'];
$data = $_POST['data'];
$czas = $_POST['time'];
$grupa = $_POST['grupa'];
$data = $data . ' ' . $czas;
// DOŁĄCZAMY PLIK Z BAZĄ MYSQL I ŁACZYMY SIĘ Z NIĄ
include ('../script/mysql.php');
$mysql = connect_mysql();

// TWORZYMY ZAPYTANIE ZWRACAJĄCE DOTYCHCZASOWE INFORMACJE O WYKŁADZIE
$zapytanie = 'SELECT data,instruktor, stan, temat,miejsce, wyklad_grupa.grupa FROM wyklady, wyklad_grupa WHERE wyklady.id=wyklad_grupa.wyklad AND wyklady.id=' . $id;
$result = $mysql->query($zapytanie);

if ($result->num_rows != 1)  // JEŚLI NIE MA TAKIEGO WYKŁADU PRZENOSZENI JESTEŚMY NA STRONE GŁÓWNĄ 
{
    header ("Location: ../page/dashboard.php");
} else  // JEŚLI ISTNIEJE TAKI WYKŁAD SPRAWDZONE SĄ INFORMACJE WYSŁANE Z INFORMACJAMI DOTYCHCZASOWYMI I W RAZIE RÓŻNICY SĄ ZAMIENIANE
{

// ZAPISYWAE SĄ INFORMACJE DO ZMIENNEJ 
$row = $result->fetch_array(); 

// SPRAWDZAMY CZY INFORMACJE Z INSTRUKTOREM SIĘ ZMIENIŁY, JEŚLI TAK TO AKTUALIZUJEMY     
if ($row['instruktor'] != $instruktor)
{
    $update_instruktor = 'UPDATE wyklady SET instruktor="' . $instruktor . '" WHERE id=' . $id;
    $mysql->query($update_instruktor);
}

// SPRAWDZAMY CZY INFORMACJE Z STANEM SIĘ ZMIENIŁY, JEŚLI TAK TO AKTUALIZUJEMY    
if ($row['stan'] != $stan)
{
    $update_stan = 'UPDATE wyklady SET stan="' . $stan . '" WHERE id=' . $id;
    $mysql->query($update_stan);
}  
// SPRAWDZAMY CZY INFORMACJE Z STANEM SIĘ ZMIENIŁY, JEŚLI TAK TO AKTUALIZUJEMY    
if ($row['data'] != $data)
{
    $update_stan = 'UPDATE wyklady SET data="' . $data . '" WHERE id=' . $id;
    $mysql->query($update_stan);
}  

// SPRAWDZAMY CZY TEMAT RÓŻNI SIĘ OD WCZEŚNIEJSZEGO   
if ($row['temat'] != $temat)
{
    $update_temat = 'UPDATE wyklady SET temat="' . $temat . '" WHERE id=' . $id;
    $mysql->query($update_temat);
}
 
// SPRAWDZAMY CZY MIEJSCE SIĘ RÓŻNIĄ    
if ($row['miejsce'] != $miejsce)
{
    $update_temat = 'UPDATE wyklady SET miejsce="' . $miejsce . '" WHERE id=' . $id;
    $mysql->query($update_temat);
}  
    
// SPRAWDZAMY CZY GRUPA SIĘ RÓŻNIĄ    
if ($row['grupa'] != $grupa)
{
    $update_temat = 'UPDATE wyklad_grupa SET grupa="' . $grupa . '" WHERE wyklad=' . $id;
    $mysql->query($update_temat);
}    
    
    
// PĘTLA Z ILOŚCIA DODANYCH UCZNIÓW
foreach ($_POST['uczen'] as $id_uczen)
{
    // SPRAWDZAMY CZY DANY UCZEŃ NIE JEST JUŻ DODANY
    $zapytanie_obecnosc = 'SELECT id FROM obecnosc WHERE uczen="' . $id_uczen . '" AND wyklad="' . $id . '"'; 
    $result_obecnosc = $mysql->query($zapytanie_obecnosc);
    
    if ($result_obecnosc->num_rows == 0)
    {
        // DODAJEMY JEŚLI NIE BYŁO TAKIEGO UCZNIA
        $insert_obecnosc = 'INSERT INTO obecnosc VALUES (null,"' . $id . '","' . $id_uczen . '")';
        $mysql->query($insert_obecnosc);
        
        // Powiadomienie dla ucznia
        
        $zapytanie_uczen = 'SELECT uczniowie.pesel FROM uczniowie WHERE uczniowie.id=' . $id_uczen;
        $data = date ('Y-m-d H:i:s');
        $result_uczen = $mysql->query($zapytanie_uczen);
        $row_uczen = $result_uczen->fetch_array();
        
        $insert_pow = 'INSERT INTO powiadomienia VALUES (null,"' . $data . '","' . $_SESSION['pesel'] . '","' . $row_uczen['pesel'] . '","1","3","' . $id . '",null)';
        
        $mysql->query($insert_pow);  
    }

}
}

// PRZENOSIMY NA STRONĘ WYKŁADU    
header ("Location: ../page/wyklad.php?id=" . $id); 


?>    