<?php

if (isset($_POST['nazwa']))
{
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $nazwa = $_POST['nazwa'];
    
    $wstawianie = 'INSERT INTO grupy (id,nazwa) VALUES (null,"' . $nazwa . '")';
    $mysql->query($wstawianie);
    
    $zapytanie = 'SELECT id FROM grupy ORDER BY id DESC';
    $result = $mysql->query($zapytanie);
    $row = $result->fetch_array();
    
    header ("Location: ../page/grupa.php?id=" . $row['id']);
    exit();
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}

?>