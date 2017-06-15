
<?php


// SKRÓCENIE ZMIENNYCH
$rejestracja = $_POST['rejestracja'];
$model = $_POST['model'];
$marka = $_POST['marka'];
$data_produkcji = $_POST['data_produkcji'];
$silnik = $_POST['silnik'];
$skrzynia = $_POST['skrzynia'];
$paliwo= $_POST['paliwo'];
$nazwa = $_POST['nazwa'];

$ile = 0;

if ((strlen($rejestracja)<3) or (strlen($rejestracja)>50))
{
    $ile++;
}

if ((strlen($marka)<3) or (strlen($marka)>50))
{
    $ile++;
}

if ((strlen($model)<3) or (strlen($model)>50))
{
    $ile++;
}

if ($data_produkcji == null)
{
    $ile++;
}

if ($silnik == null)
{
    $ile++;
}

if ($skrzynia == null)
{
    $ile++;
}

if ($paliwo == null)
{
    $ile++;
}

if ($ile != 0)
{
    header ("Location: ../page/add-samochod.php");
    exit();
}



// DOŁĄCZENIE BAZY DANYCH
include ('../script/mysql.php');
$mysql  = connect_mysql();

// SPRAWDZAMY CZY NIE MA JUŻ TAKIEJ REJESTRACJI

$zapytanie = 'SELECT id FROM samochody WHERE rejestracja="' . $rejestracja . '"';
$result = $mysql->query($zapytanie);

if ($result->num_rows == 1)
{
    $row = $result->fetch_array();
    header ("Location: ../page/samochod.php?id=" . $row['id']);
} else
{
    
    $insert = 'INSERT INTO samochody (id,nazwa,rejestracja, marka, model, data_produkcji, silnik, skrzynia, paliwo) VALUES (null,"' . $nazwa . '","' . $rejestracja . '","' . $marka . '","' . $model . '","' . $data_produkcji . '","' . $silnik . '","' . $skrzynia . '","' . $paliwo . '")';
    
    $mysql->query($insert);
    
    //PRZEKIEROWANIE NA DODANY SAMOCHÓD
    
    $zapytanie = 'SELECT id FROM samochody ORDER BY id DESC';
    $result = $mysql->query($zapytanie);
    $row = $result->fetch_array();
    
    header ("Location: ../page/samochod.php?id=" . $row['id']);
    
    
    
}


?>