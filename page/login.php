<!DOCTYPE html>
<html lang="pl">
    <head>
        <title> MojePrawko.pl </title>
        <meta charset="utf-8">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href="../style/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="../style/style-logowanie.css" rel="stylesheet" type="text/css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    </head>
    <body>
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
<?php
    
           if (((isset($_SESSION['error']))) and ($_SESSION['error'] == 1)) {
?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center error"> Podałeś zły login, bądź hasło </div>
            </div>
<?php
           $_SESSION['error'] = null;
       }   
?>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-11 col-xs-11 col-centered input">
                    <form role="form" method="post" action="../script/login.php">
                    
                        <div class="form-group">
                            <div class="inner-addon left-addon">
                                <i class="glyphicon glyphicon-user"></i>
                                <input type="text" name="pesel" placeholder="Pesel" class="form-control" />  
<!--
                                <?php
                                
                                    echo '<select name="pesel" class="form-control">';
                                        include ('../script/mysql.php');
                                        $mysql = connect_mysql();
                                
                                        $zapytanie_admin = 'SELECT pesel, imie, nazwisko FROM administratorzy';
                                        $result_admin = $mysql->query($zapytanie_admin);
                                        echo '<optgroup label="Instruktorzy">';
                                            while ($row_admin = $result_admin->fetch_array())
                                            {
                                                echo '<option value="' . $row_admin['pesel']  . '">' . $row_admin['imie'] . ' ' . $row_admin['nazwisko'] . '</option>';
                                            }
                                        echo '</optgroup>';
                                
                                        $zapytanie_instruktorzy = 'SELECT pesel, imie, nazwisko FROM instruktorzy';
                                        $result_instruktorzy = $mysql->query($zapytanie_instruktorzy);
                                        echo '<optgroup label="Instruktorzy">';
                                            while ($row_instruktorzy = $result_instruktorzy->fetch_array())
                                            {
                                                echo '<option value="' . $row_instruktorzy['pesel']  . '">' . $row_instruktorzy['imie'] . ' ' . $row_instruktorzy['nazwisko'] . '</option>';
                                            }
                                        echo '</optgroup>';
                                
                                        $zapytanie_kursanci = 'SELECT pesel, imie, nazwisko FROM uczniowie';
                                        $result_kursanci = $mysql->query($zapytanie_kursanci);
                                        echo '<optgroup label="Kursanci">';
                                            while ($row_kursanci = $result_kursanci->fetch_array())
                                            {
                                                echo '<option value="' . $row_kursanci['pesel']  . '">' . $row_kursanci['imie'] . ' ' . $row_kursanci['nazwisko'] . '</option>';
                                            }
                                        echo '</optgroup>';
                                        
                                    
                                    echo '</select>';
                                    
                                ?>
-->
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <div class="inner-addon left-addon">
                                <i class="glyphicon glyphicon-lock"></i>
                                <input type="password" name="password" value="admin" placeholder="Hasło" class="form-control" />
                            </div>           
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-lg"> Zaloguj </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center reset">
                    <a href="../page/reset_password.php"> Zapomniałem hasła </a>
                </div>
            </div>
        </div>
    </body>
</html>