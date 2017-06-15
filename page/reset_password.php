<?php

    session_start(); // Start sesji
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
<body>
<?php include_once("../script/analyticstracking.php") ?>

<!--
<div id="logo">
    <a href="login.php"> <img src="../img/logo.png" width="100%" height="100%"> </a>
</div>
<div id="logowanie">
   <form method="post" action="../script/reset_password.php">
        <input type="text" name="pesel" placeholder='PESEL'> <br>
        <input type="email" name="email" placeholder="E-mail"> <br>
        <input class="reset_password" type="submit" value="Wyślij na e-mail">
        

  </form>
</div>
-->
    
<div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center logo">
                    <a href="../page/login.php">
                        <h1> <b>MOJE</b>PRAWKO.PL </h1>
                    </a>
                </div>
            </div>
<?php
    
           if (((isset($_SESSION['error']))) and ($_SESSION['error'] == 1)) {
?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center error"> Nie ma takiego użytkownika </div>
            </div>
<?php
           $_SESSION['error'] = null;
       }   
?>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-11 col-xs-11 col-centered input">
                    <form role="form" method="post" action="../script/reset_password.php">
                        
                        <div class="form-group">
                            <div class="inner-addon left-addon">
                                <i class="glyphicon glyphicon-user"></i>
                                <input type="text" name="pesel" placeholder="Pesel" class="form-control" />    
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <div class="inner-addon left-addon">
                                <i class="fa fa-envelope"></i>
                                <input type="email" name="email" placeholder="E-mail" class="form-control" />
                            </div>           
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-lg"> Wyślij </button>
                        </div>
                    </form>
                </div>
            </div>
</div>    
    
    
    

</body>
</html>