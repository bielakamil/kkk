<?php

if ((isset($_POST['pesel'])) and (isset($_POST['password']))) // Sprawdzenie czy istnieją zmienne przesłane przez formularz
{
include ('../script/mysql.php');  // Dołączanie pliku z funkcją mysql
$mysql = connect_mysql(); // Połączenie z bazą danych
session_start (); // Start sesji
// Stworzenie krótkich zmiennych
$pesel = $_POST['pesel'];
$password = $_POST['password'];
$error = 0; // Zmienna przechowująca ilość błędów
    
if (strlen($pesel) != 11) // Sprawdzenie czy login składa się z 11 znaków
{
    $error += 1;
}

if (strlen($password) <= 3) // Sprawdzenie czy hasło jest dłuższe niż 3 znaki
{
    $error += 1;
}
    
if ($error != 0) // Jeśli pojawił się jakiś błąd to zmienna sesji error przyjmuje wartość 1 i przekierowanie jest na stronę logowania
{
    $_SESSION['error'] = 1;
    header   ("Location: ../page/login.php");
} 
    
$zapytanie = 'SELECT PESEL,HASLO FROM password WHERE (PESEL="' . $pesel  . '" OR LOGIN="' . $pesel . '")'; // Zapytanie zwracające czy istnieje użytkownik o podanym peselu
echo $zapytanie;    
$result = $mysql->query($zapytanie); // Wysłanie zapytania
    
if ($result->num_rows == 1) // Jeśli użytkownik nie istnieje to przekieruje na stronę logowania
{
$row = $result->fetch_array();
$pesel = $row['PESEL'];
$hash = $row['HASLO'];    
if (password_verify($password,$hash))
{

 $zapytanie_admin = 'SELECT id, czas_wygasniecia FROM administratorzy WHERE pesel="' . $pesel . '"'; // Zapytanie czy istnieje podany administrator    
$result_admin = $mysql->query($zapytanie_admin); // Wysłanie zapytania
if ($result_admin->num_rows == 1) // Jeśli administrator istnieje to tworzenie są zmienne status przechowująca wartość 1 i zmienna pesel przechowująca pesel administratora i przekierowywanie jest na stronę główna
{
    $row_admin = $result_admin->fetch_array();
    $int = $row_admin['czas_wygasniecia'] * 60 ;
    setcookie("time",$int,time() + $int,"/");
    $_SESSION['status'] = 1;
    $_SESSION['pesel'] = $pesel;
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
    }
    
    $zapytanie_log = 'INSERT INTO log_admin_logowanie VALUES (null,now(),"' . $row_admin['id'] . '", INET_ATON("' . $ipAddress . '"),"true")';
    $mysql->query($zapytanie_log);
    
    
   header   ("Location: ../page/dashboard.php");
} 
    

$zapytanie_instruktor = 'SELECT id, czas_wygasniecia FROM instruktorzy WHERE pesel="' . $pesel . '"'; // Zapytanie czy istnieje podany instruktor  
$result_instruktor = $mysql->query($zapytanie_instruktor); // Wysłanie zapytania
    
if ($result_instruktor->num_rows == 1) // Jeśli instruktor istnieje to tworzone są zmienne status przechowująca wartość 2 i zmienna pesel przechowująca pesel instruktora i przekierowywanie jest na stronę główna
{
    $row_instruktorzy = $result_instruktor->fetch_array();
    $int = $row_instruktorzy['czas_wygasniecia'] * 60 ;
    setcookie("time",$int,time() + $int,"/");
    $_SESSION['status'] = 2;
    $_SESSION['pesel'] = $pesel;

    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
    }
    
    $zapytanie_log = 'INSERT INTO log_instruktor_logowanie VALUES (null,now(),"' . $row_instruktorzy['id'] . '", INET_ATON("' . $ipAddress . '"),"true")';
    $mysql->query($zapytanie_log);
    
    header   ("Location: ../page/dashboard.php");    
}
echo $pesel;    
$zapytanie_uczen = 'SELECT id,pesel, czas_wygasniecia FROM uczniowie WHERE pesel="' . $pesel . '"'; // Zapytanie czy istnieje podany uczeń
$result_uczen = $mysql->query($zapytanie_uczen); // Wysłanie zapytania

if ($result_uczen->num_rows == 1) // Jeśli uczeń istnieje to tworzone są zmienne status przechowująca wartość 2 i zmienna pesel przechowująca pesel ucznia i przekierowywanie jest na stronę główna
{
    $row_uczen = $result_uczen->fetch_array();
    $int = $row_uczen['czas_wygasniecia'] * 60 ;
    setcookie("time",$int,time() + $int,"/");
    $_SESSION['status'] = 3;
    $_SESSION['pesel'] = $pesel;
    
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
    }
    
    $zapytanie_log = 'INSERT INTO log_user_logowanie VALUES (null,now(),"' . $row_uczen['id'] . '", INET_ATON("' . $ipAddress . '"),"true")';
    $mysql->query($zapytanie_log);
    
    header  ("Location: ../page/dashboard.php");     
}   
    
    header ("Location: ../page/login.php");
} else
{
      $_SESSION['error'] = 1;
      header  ("Location: ../page/login.php");  
}
    
    


    
} else
{
      $_SESSION['error'] = 1;
      header  ("Location: ../page/login.php");  
}
    


} else 
{
   header  ("Location: ../page/login.php"); // Przekierowanie na stronę logowania
}

?>