<?php

if ((isset($_GET['id'])) and (isset($_GET['ocena'])))
{
    update_ocena();  
}


function update_ocena ()
{
    $id = $_GET['id'];
    $ocena = $_GET['ocena'];
    
    include ('../script/mysql.php');
    
    $mysql = connect_mysql();
    
    $update = 'UPDATE zadania_uczen SET ocena=' . $ocena . ', data=NOW() WHERE id=' . $id;
    $mysql->query($update);       
}






?>