<?php


$id = $_POST['id'];
$stan = $_POST['stan'];
$car = $_POST['car'];
$data = $_POST['date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$start = $data . ' '  . $start_time;
$koniec = $data . ' ' . $end_time;
$notatka = $_POST['notatka'];
$kara = $_POST['kara'];


include ('../script/mysql.php');

$mysql = connect_mysql();

if ($start_time > $end_time)
{
    header ("Location: ../page/jazda.php?id=" . $id);
    exit();
}

$zapytanie = 'SELECT * FROM jazdy WHERE id=' . $id;

$result = $mysql->query($zapytanie);

if ($result->num_rows == 1)
{
 
$row = $result->fetch_array();   
    

$st = $row['data_rozpoczecia'];
$en = $row['data_zakonczenia'];


$akt_start = strftime('%Y-%m-%dT%H:%M', strtotime($st));    
$edit_start = strftime('%Y-%m-%dT%H:%M', strtotime($start));    

$akt_koniec = strftime('%Y-%m-%dT%H:%M', strtotime($en));    
$edit_koniec = strftime('%Y-%m-%dT%H:%M', strtotime($koniec));      
  
if (($akt_start != $edit_start) or ($stan != $row['stan']))
{
    $dot = strftime('%Y-%m-%d %H:%M:%S', strtotime($start));   
    $update = 'UPDATE jazdy SET data_rozpoczecia="' . $dot . '" WHERE id=' . $id;
    $mysql->query($update);  
}
    
if (($akt_koniec != $edit_koniec) or ($stan != $row['stan']))
{
    $dot = strftime('%Y-%m-%d %H:%M:%S', strtotime($koniec));   
    $update = 'UPDATE jazdy SET data_zakonczenia="' . $dot . '" WHERE id=' . $id;
    $mysql->query($update);  
}    
    
    
if ($row['stan'] != $stan)
{
    $update = 'UPDATE jazdy SET stan="' . $stan . '" WHERE id=' . $id;
    $mysql->query($update);
}    
    
$delete_kara = 'DELETE FROM odwolane_jazdy WHERE jazda=' . $id;
$mysql->query($delete_kara);
    
if (($stan == 3) and ($kara > 0))
{
    // JEST KARA
    
    $zapytanie_kara = 'INSERT INTO odwolane_jazdy (id,uczen,jazda,cena) VALUES (null,' . $row['uczen'] . ',' . $id . ',' . $kara . ')';
    $mysql->query($zapytanie_kara); 
}
   
if ($row['samochod'] != $car)
{
    $update = 'UPDATE jazdy SET samochod="' . $car . '" WHERE id=' . $id;
    $mysql->query($update);
}
    
if ($row['notatka'] != $notatka)
{
    $update = 'UPDATE jazdy SET notatka="' . $notatka . '" WHERE id=' . $id;
    $mysql->query($update);
}    
    
$update_all = 'UPDATE zadania_miejsca SET stan=0, jazdy=null WHERE jazdy=' . $id;
$mysql->query($update_all);

    
    
    
    
header ("Location: ../page/jazda.php?id=" . $id);
    
    
} else
{
    header ("Location: ../page/dashboard.php");
}

?>