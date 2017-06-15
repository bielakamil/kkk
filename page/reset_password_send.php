<?php
    ob_start();
    if (isset($_SESSION['status'])) {
        // Jeśli użytkownik jest zalogowany automatycznie zostanie przekierowany na dashboard

        header("Location: ../page/dashboard.php");
    }
?>

<html>
<head>
    <title> MojePrawko.pl - zapomniałem hasła </title>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="../style/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../style/style-logowanie.css" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body onload="wyrownaj();">
<?php include_once("../script/analyticstracking.php") ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center logo">
            <a href="../page/login.php">
                <h1> <b>MOJE</b>PRAWKO.PL </h1>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <h4> Link do nowego hasła został wysłany na podany e-mail </h4>
        </div>
        
    
    </div>

</div>        


</body>
</html>