<?php

if (isset($_POST['id']))
{
    session_start();
    $status = $_SESSION['status'];
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    @$id = $_POST['id'];
    @$data = $_POST['data'];
    @$instruktor = $_POST['instruktor'];
    @$wplata = $_POST['kasa'];
    $zapytanie = 'SELECT instruktor, kasa, data FROM wplaty WHERE id=' . $id;
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        if ($status == 1) {
            
            if ($row['kasa'] != $wplata)
            {
                $update = 'UPDATE wplaty SET kasa="' . $wplata . '" WHERE id=' . $id;
                $mysql->query($update);
            }
            
            if ($row['data'] != $data)
            {
                $update = 'UPDATE wplaty SET data="' . $data . '" WHERE id=' . $id;
                $mysql->query($update); 
            }
            
            if ($row['instruktor'] != $instruktor)
            {
                $update = 'UPDATE wplaty SET instruktor="' . $instruktor . '" WHERE id=' . $id;
                $mysql->query($update); 
            }
            
        } elseif ($status == 2) {
            
            if ($row['kasa'] != $wplata)
            {
                $update = 'UPDATE wplaty SET kasa=' . $wplata . ' WHERE id=' . $id;
                $mysql->query($update);
            }
        }
        header ("Location: ../page/wplata.php?id=" . $id);
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}





?>