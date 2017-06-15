<?php


if (isset($_GET['id']))
{
    $id = $_GET['id'];
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    $zapytanie = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE id=' . $id;

    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 0)
    {
        header ("Location: ../page/jazda.php?id=" . $id);
        exit();
    }
    
    $row = $result->fetch_array();
    $start = $row['data_rozpoczecia'];
    $koniec = $row['data_zakonczenia'];

    $update = 'UPDATE jazdy SET stan=2 WHERE id=' . $id;
    $mysql->query($update);
    header ("Location: ../page/jazda.php?id=" . $id);
    exit();
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}















?>