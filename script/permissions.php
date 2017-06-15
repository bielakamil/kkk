<?php

function admin () // Uprawnienie do przeglądania strony ma tylko administrator
{
    session_start();
    if (isset($_SESSION['status'])) // Sprawdzenie czy użytkownik jest zalogowany
    {
        $status = $_SESSION['status']; // Skrócenie zmiennej
        
        if ($status == 1) // Sprawdzenie czy zalogowany użytkownik to administrator
        {
            //include ("../script/admin.php"); // Dołączenie pliku z klasą administratora
           // $admin = new admin(); // Utworzenie obiektu administratora
            if (isset($_COOKIE['time']))
            {
            $int = $_COOKIE['time'];
            setcookie("time",$int,time() + $int,"/");     
            } else
            {
                session_destroy();
                header ("Location: ../page/login.php");
            }
        } else // Jeśli zalogowany użytkownik nie ma uprawnień to zostaje przekierowany na stronę główną
        {
            header ("Location: ../page/dashboard.php");
        }
    } else
    {
        header ("Location: ../page/login.php"); // jeśli użytkownik nie jest zalogowany do zostaje przekierowany na stronę logowania
    }
}

function admin_instruktor () // Uprawnienia do przeglądania strony ma administrator i instruktor
{
    session_start();    
    if (isset($_SESSION['status'])) // Sprawdzenie czy użytkownik jest zalogowany
    {
        $status = $_SESSION['status']; // Skrócenie zmiennej
        
        if ($status == 1) // Sprawdzenie czy zalogowany użytkownik to administrator
        {
            //include ("../script/admin.php"); // Dołączenie pliku z klasą administratora
            //$admin = new admin(); // Utworzenie obiektu administratora
            if (isset($_COOKIE['time']))
            {
            $int = $_COOKIE['time'];
            setcookie("time",$int,time() + $int,"/");     
            } else
            {
                session_destroy();
                header ("Location: ../page/login.php");
            }
        } elseif ($status == 2)
        {
            //include ("../script/instruktor.php"); // Dołaczenie pliku z klasą instruktor
            //$instruktor = new instruktor(); // Utworzenie obiektu instruktor
            if (isset($_COOKIE['time']))
            {
            $int = $_COOKIE['time'];
            setcookie("time",$int,time() + $int,"/");     
            } else
            {
                session_destroy();
                header ("Location: ../page/login.php");
            }
        } else // Jeśli zalogowany użytkownik nie ma uprawnień to zostaje przekierowany na stronę główną
        {
            header ("Location: ../page/dashboard.php");
        }     
    } else  
    {
        header ("Location: ../page/login.php"); // jeśli użytkownik nie jest zalogowany do zostaje przekierowany na stronę logowania
    }
}

function admin_instruktor_uczen () // Uprawnienia do przeglądania strony ma administrator, instruktor i uczeń
{
    session_start(); 
    if (isset($_SESSION['status'])) // Sprawdzenie czy użytkownik jest zalogowany
    {
        $status = $_SESSION['status']; // Skrócenie zmiennej
        
        if ($status == 1) // Sprawdzenie czy zalogowany użytkownik to administrator
        {
            //include ("../script/admin.php"); // Dołączenie pliku z klasą administratora
            //$admin = new admin(); // Utworzenie obiektu administratora
            if (isset($_COOKIE['time']))
            {
            $int = $_COOKIE['time'];
            setcookie("time",$int,time() + $int,"/");     
            } else
            {
                session_destroy();
                header ("Location: ../page/login.php");
            }
        } elseif ($status == 2)
        {
            //include ("../script/instruktor.php"); // Dołaczenie pliku z klasą instruktor
            //$instruktor = new instruktor(); // Utworzenie obiektu instruktor
            if (isset($_COOKIE['time']))
            {
            $int = $_COOKIE['time'];
            setcookie("time",$int,time() + $int,"/");     
            } else
            {
                session_destroy();
                header ("Location: ../page/login.php");
            }
        } elseif ($status == 3)
        {
            //include ("../script/uczen.php"); // Dołączenie pliku z klasą uczeń
            //$uczen = new uczen; // Utworzenie obiektu uczeń
            
            if (isset($_COOKIE['time']))
            {
            $int = $_COOKIE['time'];
            setcookie("time",$int,time() + $int,"/");     
            } else
            {
                session_destroy();
                header ("Location: ../page/login.php");
            }
        } else // Jeśli zalogowany użytkownik nie ma uprawnień to zostaje przekierowany na stronę główną
        {
            header ("Location: ../page/dashboard.php");
        }     
    } else  
    {
        header ("Location: ../page/login.php"); // jeśli użytkownik nie jest zalogowany do zostaje przekierowany na stronę logowania
    }
}

?>