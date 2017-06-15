<?php


include ('../script/mysql.php');

$mysql = connect_mysql();


$what = $_POST['what'];


switch ($what)
{
    case 1:
        {
        $nazwa = $_POST['nazwa'];
        $facebook = $_POST['facebook'];
        $instagram = $_POST['instagram'];
        $www = $_POST['www'];
        $twitter= $_POST['twitter'];
        $youtube = $_POST['youtube'];
    
        $zapytanie = 'SELECT nazwa,facebook,instagram,www,logo,twitter,youtube FROM firma';
        $result = $mysql->query($zapytanie);
        
        $row = $result->fetch_array();
        
        if ($row['nazwa'] != $nazwa)
        {
            $update = 'UPDATE firma SET nazwa="' . $nazwa . '"';
            $mysql->query($update);
        }
        if ($row['facebook'] != $facebook)
        {
            $update = 'UPDATE firma SET facebook="' . $facebook . '"';
            $mysql->query($update);
        }
        if ($row['instagram'] != $instagram)
        {
            $update = 'UPDATE firma SET instagram="' . $instagram . '"';
            $mysql->query($update);
        }
        if ($row['www'] != $www)
        {
            $update = 'UPDATE firma SET www="' . $www . '"';
            $mysql->query($update);
        }
        if ($row['twitter'] != $twitter)
        {
            $update = 'UPDATE firma SET twitter="' . $twitter . '"';
            $mysql->query($update);
        }
        if ($row['youtube'] != $youtube)
        {
            $update = 'UPDATE firma SET youtube="' . $youtube . '"';
            $mysql->query($update);
        }
    
        break;
        }
}


header ("Location: ../page/settings-firma.php");
exit();


?>