<?php


if ($_SERVER['SERVER_ADDR'] == '::1')
{

    DEFINE ("DB_HOST" ,"localhost"); 
    DEFINE ("DB_USER" ,"root"); 
    DEFINE ("DB_PASSWORD" ,""); 
    DEFINE ("DB_DATABASE" ,"mojeprawko");  

} else
{
    DEFINE ("DB_HOST" ,"sql.bielakamil.nazwa.pl"); 
    DEFINE ("DB_USER" ,"bielakamil"); 
    DEFINE ("DB_PASSWORD" ,"gh9gwomBUx"); 
    DEFINE ("DB_DATABASE" ,"bielakamil");      
}



function connect_mysql ()
{
    @$db = new mysqli (DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);
    
    
     if (mysqli_connect_errno())
        {
            header ("Location: mysql-no-connect.php");
        } 
    $db->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`");
    return $db;
}


?>
