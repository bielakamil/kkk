<?php


    include ('../script/mysql.php');
    
@$mysql = connect_mysql();

     if ($mysql->connect_errno)
        {
            header ("Location: login.php");
        } 

    http_response_code (404);
?>

<html>
<head>
    <title> MojePrawko.pl - strona tymczasowo nie działa </title>
    <link href="../style/style%20-%20logowanie.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8">
</head>
<body onload="wyrownaj();">
<?php include_once("../script/analyticstracking.php") ?>  

<div id="logo">

</div>
<div id="logowanie">
   Nie udało się połączyć z bazą danych. 
</div>

</body>
</html>