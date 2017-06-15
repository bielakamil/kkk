<?php

if (isset($_GET['id']))
{
    session_start();
    $id_jazd = $_GET['id'];
    $status = $_SESSION['status'];
    $pesel = $_SESSION['pesel'];
    

    include ('../script/mysql.php');
    
    $mysql = connect_mysql();
    
    
    if ($status == 3)
    {
        $zapytanie = 'SELECT jazdy.stan,instruktorzy.pesel FROM jazdy, uczniowie, instruktorzy WHERE jazdy.id=' . $id_jazd . ' AND jazdy.uczen=uczniowie.id AND jazdy.instruktor=instruktorzy.id AND uczniowie.pesel="' . $pesel . '"';
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows != 1)
        {
            header ("Location: ../page/dashboard.php");
        } else
        {
            $row = $result->fetch_array();
            $stan_jazd = $row['stan'];

            if ($stan_jazd == 1)
            {
            
            $zapytanie_update = 'UPDATE jazdy SET jazdy.stan =3  WHERE jazdy.id=' . $id_jazd;
            $result = $mysql->query($zapytanie_update);
            
            $pesel_instruktor = $row['pesel'];

                
            $zapytanie_powiadomienia = 'INSERT INTO powiadomienia (id,data,nadawca,odbiorca,stan,numer,id_tresc,tresc) VALUES (null, now(), "' . $pesel . '", "' . $pesel_instruktor . '",1, 1,' . $id_jazd . ',null)';
            $mysql->query($zapytanie_powiadomienia);
            header ("Location: ../page/jazda.php?id=" . $id_jazd);    
            } else
            {
            header ("Location: ../page/dashboard.php");
            }
        }
    }
    
    
    if ($status == 2)
    {
        $zapytanie = 'SELECT jazdy.stan,uczniowie.pesel FROM jazdy, uczniowie, instruktorzy WHERE jazdy.id=' . $id_jazd . ' AND jazdy.uczen=uczniowie.id AND jazdy.instruktor=instruktorzy.id AND instruktorzy.pesel="' . $pesel . '"';
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows != 1)
        {
            header ("Location: ../page/dashboard.php");
        } else
        {
            $row = $result->fetch_array();
            $stan_jazd = $row['stan'];

            if ($stan_jazd == 1)
            {
            
            $zapytanie_update = 'UPDATE jazdy SET jazdy.stan =4  WHERE jazdy.id=' . $id_jazd;
            $result = $mysql->query($zapytanie_update);
            
            $pesel_instruktor = $row['pesel'];

                
            $zapytanie_powiadomienia = 'INSERT INTO powiadomienia (id,data,nadawca,odbiorca,stan,numer,id_tresc,tresc) VALUES (null, now(), "' . $pesel . '", "' . $pesel_instruktor . '",1, 1,' . $id_jazd . ',null)';
            $mysql->query($zapytanie_powiadomienia);
            header ("Location: ../page/jazda.php?id=" . $id_jazd);    
            } else
            {
            header ("Location: ../page/dashboard.php");
            }
        }
    }

    
} else
{
    header ("Location: ../page/dashboard.php"); // Jeśli nie ma zmiennej GET przekieruj na stronę główną
}


?>