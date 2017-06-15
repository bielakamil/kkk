<?php

if ((isset($_POST['passwd1'])) and (isset($_POST['passwd2'])) and (isset($_POST['pesel'])))
{

    $passwd1 = $_POST['passwd1'];
    $passwd2 = $_POST['passwd2'];
    $pesel = $_POST['pesel'];
    $key = $_POST['key'];
    
    if ($passwd1 != $passwd2)
    {
       header ("Location: ../page/new_password.php?key=" . $key);
    }
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    $hash = password_hash($passwd1,PASSWORD_DEFAULT);
    $zapytanie_update = 'UPDATE password SET haslo="' . $hash . '" WHERE pesel="' . $pesel . '"';
    
    $result_update = $mysql->query($zapytanie_update);
    header ("Location: ../page/new_password_new.php");
} else
{
    header ("Location: ../page/login.php");
}

?>