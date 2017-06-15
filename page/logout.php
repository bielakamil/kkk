<?php


session_start();
$status = $_SESSION['status'];


if ($status == 1) {
    // Sprawdzenie czy zalogowany użytkownik to administrator

    include("../script/admin.php"); // Dołączenie pliku z klasą administratora
    $admin = new admin(); // Utworzenie obiektu administratora
    $mysql = connect_mysql();
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
    }
    $zapytanie_log = 'INSERT INTO log_admin_logowanie VALUES (null,now(),"' . $admin->id . '", INET_ATON("' . $ipAddress . '"),"logout")';
    $mysql->query($zapytanie_log);
    
    
    
} elseif ($status == 2) {
    include("../script/instruktor.php"); // Dołaczenie pliku z klasą instruktor
    $instruktor = new instruktor(); // Utworzenie obiektu instruktor
    $mysql = connect_mysql();
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
    }
    $zapytanie_log = 'INSERT INTO log_instruktor_logowanie VALUES (null,now(),"' . $instruktor->id . '", INET_ATON("' . $ipAddress . '"),"logout")';
    $mysql->query($zapytanie_log);
    
} elseif ($status == 3) {
    include("../script/uczen.php"); // Dołączenie pliku z klasą uczeń
    $uczen = new uczen; // Utworzenie obiektu uczeń
    $mysql = connect_mysql();
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
    }
    $zapytanie_log = 'INSERT INTO log_user_logowanie VALUES (null,now(),"' . $uczen->id . '", INET_ATON("' . $ipAddress . '"),"logout")';
    $mysql->query($zapytanie_log);
}




setcookie("time",$int,time() - 1,"/");
session_start();
session_destroy();
header("Location: ../page/login.php");
