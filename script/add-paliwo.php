<?php


if ((isset($_GET['samochod'])) and (isset($_GET['data'])) and (isset($_GET['time'])) and (isset($_GET['paliwo'])) and (isset($_GET['cena'])))
{
    // ŁĄCZYMY Z BAZĄ DANYCH
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    // UTWORZENIE ZMIENNYCH
    $samochod = $_GET['samochod'];
    $data = $_GET['data'];
    $time = $_GET['time'];
    $paliwo = $_GET['paliwo'];
    $cena = $_GET['cena'];
    $czas = $data . ' ' . $time;
    $data = date ('Y-m-d H:i:s');
    // SPRAWDZENIE CZY ISTNIEJE SAMOCHÓD
    $zapytanie_samochod = 'SELECT id FROM samochody WHERE id=' . $samochod;
    $result_samochod = $mysql->query($zapytanie_samochod);
    if ($result_samochod->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();    
    }
    
    // POBRANIE ID INSTRUKTORA
    session_start();
    if ($_SESSION['status'] == 1)
    {
        $instruktor = null;    
    } elseif ($_SESSION['status'] == 2)
    {
        $zapytanie_instruktor = 'SELECT id FROM instruktorzy WHERE pesel="' . $_SESSION['pesel'] . '"';
        $result_instruktor = $mysql->query($zapytanie_instruktor);
        if ($result_instruktor->num_rows == 1)
        {
            $row_instruktor = $result_instruktor->fetch_array();
            $instruktor = $row_instruktor['id'];
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
    
    
    // DODANIE PALIWA DO BAZY
    $insert = 'INSERT INTO paliwo (id,samochod,data,paliwo,cena,instruktor) VALUES (null,"' . $samochod . '","' . $czas . '","' . $paliwo . '","' . $cena . '","' . $instruktor . '")';
    $result = $mysql->query($insert);
    
    // POBIERANIE ID DODANEGO REKORDU
    $zapytanie_paliwo = 'SELECT id FROM paliwo WHERE data="' . $czas . '" ORDER BY id DESC LIMIT 1';
    $result_paliwo = $mysql->query($zapytanie_paliwo);
    $row_paliwo = $result_paliwo->fetch_array();
    $id_paliwo = $row_paliwo['id'];
    
    // ODEJMOWANIE OD PORTFELA
    if (($_SESSION['status'] == 2) and (!isset($_GET['karta'])))
    {
    $zapytanie_portfel = 'SELECT aktualnie FROM budzet WHERE instruktor=' . $instruktor . ' ORDER BY data DESC LIMIT 1';
    $result_portfel = $mysql->query($zapytanie_portfel);
    if ($result_portfel->num_rows == 0)
    {
        $aktualny_portfel = 0;
    } else
    {
        $row_portfel = $result_portfel->fetch_array();
        $aktualny_portfel = $row_portfel['aktualnie'];
    }
    $przyszly_portfel = $aktualny_portfel-$cena;
    $insert_portfel = 'INSERT INTO budzet (id,instruktor,data,kwota,wczesniej,aktualnie,status,id_status) VALUES (null,"' . $instruktor . '","' . $data . '","' . $cena . '","' . $aktualny_portfel . '","' . $przyszly_portfel . '","100","' . $id_paliwo . '")';
    $mysql->query($insert_portfel);              
    }
    
    header ("Location: ../page/samochod.php?id=" . $samochod);
    exit();
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}







?>