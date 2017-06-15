<?php



if ((isset($_GET['id'])) and (isset($_GET['ocena'])))
{
    $id = $_GET['id'];
    $ocena = $_GET['ocena'];
    
    include ('../script/mysql.php');
    
    $mysql = connect_mysql();
    
    $update = 'UPDATE zadania_uczen SET ocena=' . $ocena . ' WHERE id=' . $id;
    $mysql->query($update);    
}

if ((isset($_GET['id'])) and (isset($_GET['start'])))
{
     $id = $_GET['id'];  
}

?>