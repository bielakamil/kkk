<?php

include ('../script/mysql.php');
$mysql = connect_mysql();

$zapytanie = 'SELECT * FROM password';
$result = $mysql->query($zapytanie);

while ($row = $result->fetch_array())
{
    $pesel = $row['PESEL'];
    $haslo = 'admin';
    
    $hash = password_hash($haslo,PASSWORD_DEFAULT);
    echo $pesel . '<br>';
    var_dump(password_verify($haslo,$row['HASLO']));
    echo $hash . '<br>';
    echo $row['HASLO'];
    echo '<br>';
    echo '<br>';
    $update = 'UPDATE password SET haslo="' . $hash . '" WHERE pesel="' . $pesel . '"';
    $mysql->query($update);
    
}


?>