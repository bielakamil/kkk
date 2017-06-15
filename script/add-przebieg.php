<?php

// SPRAWDZAMY CZY ISTNIEJE ZMIENNE PRZESŁANE PRZEZ FORMULARZ
if ((isset($_GET['car'])) and (isset($_GET['date'])) and (isset($_GET['time'])) and (isset($_GET['przebieg'])))
{
    // ZAPISUJEMY DO ZMIENNYCH
    $car = $_GET['car'];
    $data = $_GET['date'];
    $time = $_GET['time'];
    $przebieg = $_GET['przebieg'];
    $czas = $data . ' ' . $time;
    
    // ŁĄCZYMY Z BAZĄ DANYCH
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    // SPRAWDZAMY CZY ISTNIEJE PODANY SAMOCHÓD
    $zapytanie_car = 'SELECT id FROM samochody WHERE id=' . $car;
    $result_car = $mysql->query($zapytanie_car);
    
    if ($result_car->num_rows  == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();    
    }
    
    // POBIERAMY ID INSTRUKTORA LUB ADMINISTRATORA
    session_start();
    if (isset($_SESSION['status']))
    {
        echo $_SESSION['status'];
        if ($_SESSION['status'] == 1)
        {
            $instruktor == null;
        } else
        {
            $zapytanie_instruktor = 'SELECT id FROM instruktorzy WHERE pesel="' . $_SESSION['pesel'] . '"';
            $result_instruktor = $mysql->query($zapytanie_instruktor);
            if ($result_instruktor->num_rows == 0)
            {
                header ("Location: ../page/dashboard.php");
                exit();
            }
            $row_instruktor = $result_instruktor->fetch_array();
            $instruktor = $row_instruktor['id'];
        }
    }
    
    // DODAJEMY PRZEBIEG
    $insert_przebieg = 'INSERT INTO przebieg (id,data,samochod,instruktor,przebieg) VALUES (null,"' . $czas . '","' . $car . '","' . $instruktor . '","' . $przebieg . '")';
    $mysql->query($insert_przebieg);
    
    header ("Location: ../page/samochod.php?id=" . $car);
    exit();
} else
{
    // PRZEKIEROWUJEMY NA STRONĘ GŁÓWNĄ
    header ("Location: ../page/dashboard.php");
    exit();
}









?>