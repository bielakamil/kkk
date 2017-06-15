<?php

if (isset($_GET['id']))
{
    include ('../script/mysql.php');
    $mysql = connect_mysql();

    $id = $_GET['id'];
    
    $zapytanie_zadanie = 'SELECT id FROM zadania_wyzwania';
    $result_zadanie = $mysql->query($zapytanie_zadanie);

    while ($row_zadanie = $result_zadanie->fetch_array())
    {
        $insert = 'INSERT INTO zadania_uczen (id,zadanie,uczen,ocena,data) VALUES (null,"' . $row_zadanie['id'] . '","' . $id . '","0",NOW())';
        echo $insert;
        echo '<br>';
        $mysql->query($insert);
    }
    
    header ("Location: ../page/podglad_jazd.php?id=" . $id);
    exit();
    
} else
{
    header ("Location: ../page/dashboard.php");
}

?>