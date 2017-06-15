<?php
    ob_start();
?>
<html>
<head>
    <title> MojePrawko.pl - nowe hasło </title>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="../style/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../style/style-logowanie.css" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body>
<?php include_once("../script/analyticstracking.php") ?>
<?php

    session_start(); // Start sesji
    if (isset($_SESSION['status'])) {
        // Jeśli użytkownik jest zalogowany automatycznie zostanie przekierowany na dashboard
        header("Location: ../page/dashboard.php");
    }
        
    if (isset($_GET['key']))
    {
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    $key = $_GET['key'];
     
    $zapytanie_key = 'SELECT pesel, data FROM resetpassword WHERE klucz="' . $key . '"';
    $result_key = $mysql->query($zapytanie_key);
    
    if ($result_key->num_rows != 1)
    {
        header ("Location: ../page/login.php");
    } else
    {
        $row_key = $result_key->fetch_array();
        $data = $row_key['data'];
        $now = date ("Y-m-d G:i:s");
        $d1 = strtotime($now);
        $d2 = strtotime($data);
        $d = $d2-$d1;
        
        if ($d < 0)
        {
            header ("Location: ../page/login.php");
        } else
        {
            $pesel = $row_key['pesel'];
            $stan = true;
        }     
    }
    } else
    {
        header ("Location: ../page/login.php");
    }
    
    
?>


    
    <?php
    
    
    session_start(); // Start sesji
    if (isset($_SESSION['status'])) {
        // Jeśli użytkownik jest zalogowany automatycznie zostanie przekierowany na dashboard

        header("Location: ../page/dashboard.php");
    }
?>
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
                <div class="col-lg-3 col-md-3 col-sm-11 col-xs-11 col-centered input">
                    <form role="form" method="post" action="../script/new_password.php">
                        <?php
                            if ($stan == true)
                            {           
                                echo '<input type="hidden" name="pesel" value="' . $pesel . '">';
                                echo '<input type="hidden" name="key" value="' . $key . '">';
                            }
                        ?>   
                        
                        <div class="form-group">
                            <div class="inner-addon left-addon">
                                <i class="glyphicon glyphicon-lock"></i>
                                <input type="password" name="passwd1" placeholder="Hasło" class="form-control" />
                            </div>  
                            <div class="inner-addon left-addon">
                                <i class="glyphicon glyphicon-lock"></i>
                                <input type="password" name="passwd2" placeholder="Powtórz hasło" class="form-control" />
                            </div>           
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-lg"> Zmień hasło </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>    
    
    
    

</body>
</html>