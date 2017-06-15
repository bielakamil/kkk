<?php

if (isset($_GET['id']))
{
    $id = $_GET['id'];
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT active FROM kategoria_prawo_jazdy WHERE id=' . $id;
    $result = $mysql->query($zapytanie);
    $row = $result->fetch_array();
    
    if ($row['active'] == 1)
    {
        $update = 'UPDATE kategoria_prawo_jazdy SET active=2 WHERE id=' . $id;
    } else
    {
        $update = 'UPDATE kategoria_prawo_jazdy SET active=1 WHERE id=' . $id;
    }
    
    $result = $mysql->query($update);
    
    header ("Location: ../page/settings-kursanci.php");
    
} else
{
header ("Location: ../page/dashboard.php");
}



?>