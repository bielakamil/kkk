<?php
include ('../script/month.php');
        include('../script/permissions.php');
        admin_instruktor();
?>
<html>

<head>
    <title> MojePrawko.pl - przebieg samochodu </title>
    <?php head (); ?>
</head>

<body onload="wyrownaj();">

<?php include_once("../script/analyticstracking.php") ?>
        <?php


        $status = $_SESSION['status'];

        
        if ($status == 1) {
            // Sprawdzenie czy zalogowany użytkownik to administrator
            include("../script/admin.php"); // Dołączenie pliku z klasą administratora
            $admin = new admin(); // Utworzenie obiektu administratora
        } elseif ($status == 2) {
            include("../script/instruktor.php"); // Dołaczenie pliku z klasą instruktor
            $instruktor = new instruktor(); // Utworzenie obiektu instruktor
        } 
    
        ?>  


    <nav id="menu" class="all">
                <?php
                    if ($status == 1) {
                        echo $admin->menu();
                    } elseif ($status == 2) {
                        echo $instruktor->menu();
                    } elseif ($status == 3) {
                        echo $uczen->menu();
                    }
                ?>
    </nav>
    <script src="../script/powiadomienia.js">
        
    </script>
    <header class="all" id="head">
        <div id="top">            <i class="material-icons" onclick="show_menu()"> menu </i>
                       <div id="search-input"> 
                <input type="search" id="search-input-text" oninput="search()">
                <ol style="display: none" id="search_value">                 
                </ol>
            </div>

            <div class="pow-text" id="pow-text" style="display:none">
       
                <?php
                    if ($status == 1) {
                        echo $admin->ile_powiadomien();
                    } elseif ($status == 2) {
                        echo $instruktor->showPowiadomienia();
                    } elseif ($status == 3) {
                        echo $uczen->showPowiadomienia();
                    }
                ?>
            </div>
            <div id="profile">
                                <i class="material-icons" id="powiadomienia" onclick="showPowiadomienia()">notifications</i>
                <i class="material-icons" id="news">chat_bubble</i>
                <span>
           
                    <?php

                    if ($status == 1) {
                        echo '<a href="../page/admin.php">';
                        echo '<div class="hexagon">';
                            echo '<div class="hexTop"> </div>';
                            echo '<div class="hexBottom"> </div>';
                        echo '</div>';
                            echo  $admin->nazwisko . ' ' . $admin->imie;
                        echo '</a>';
                    } elseif ($status == 2) {
                        echo '<a href="../page/instruktor.php">';
                        echo '<div class="hexagon">';
                            echo '<div class="hexTop"> </div>';
                            echo '<div class="hexBottom"> </div>';
                        echo '</div>';
                        echo '<span>';
                            echo  $instruktor->nazwisko . ' ' . $instruktor->imie;
                        echo '</sppan>';
                        echo '</a>';
                    } elseif ($status == 3) {
                        echo '<a href="../page/uczen.php">';
                        echo '<div class="hexagon">';
                            echo '<div class="hexTop"> </div>';
                            echo '<div class="hexBottom"> </div>';
                        echo '</div>';
                        echo '<span>';
                            echo $uczen->nazwisko . ' ' . $uczen->imie;
                        echo '</span>';
                        echo '</a>';
                    }
                    
                    ?>

                </span>
                <i class="material-icons" onclick="showMenu()"> keyboard_arrow_down </i>
                
                <div id="profile_menu" style="display: none" >
                   <?php
                    if ($status == 1) {
                        echo $admin->small_menu();
                    } elseif ($status == 2) {
                        echo $instruktor->small_menu();
                    } elseif ($status == 3) {
                        echo $uczen->small_menu();
                    }
                   ?>
                </div>
            </div>
        </div>


          <h1 class="add"> Przebieg samochodu </h1>

       
       <?php
        
        if ($_SESSION['status'] == 3)
        {
            echo '<a href="../page/add-przebieg.php?id=' . $_GET['id'] . '">';
                echo '<div id="button_add">';
                    echo 'Dodaj przebieg';
                echo '</div>';
            echo '</a>'; 
        } elseif ($_SESSION['status'] == 2)
        {
            echo '<a href="../page/add-przebieg.php?id=' . $_GET['id'] . '">';
                echo '<div id="button_add">';
                    echo 'Dodaj przebieg';
                echo '</div>';
            echo '</a>'; 
        }
        
        
        ?>
       
        <div id="text" class="all-page show">
                <?php

                    if ($status == 1) {
                        echo $admin->show_car_przebieg($_GET['id']);
                    } elseif ($status == 2) {
                        $instruktor->show_car_przebieg($_GET['id']);
                    } 
            
                ?>
        </div>
   
    </header>
<div class="notifications">
    <?php
        if ($status == 1) {
            echo $admin->showPowiadomienia();
        } elseif ($status == 2) {
            echo $instruktor->showPowiadomienia();
        } elseif ($status == 3) {
            echo $uczen->showPowiadomienia();
        }
    ?>  
</div>
  
<div class="news">
    <?php
        if ($status == 1) {
            echo $admin->news();
        } elseif ($status == 2) {
            echo $instruktor->news();
        } elseif ($status == 3) {
            echo $uczen->news();
        }
    ?>  
</div>
   
<div class="small_menu">
     <?php
        if ($status == 1) {
            echo $admin->small_menu();
        } elseif ($status == 2) {
            echo $instruktor->small_menu();
        } elseif ($status == 3) {
            echo $uczen->small_menu();
        }
    ?>  
</div>
</body>

</html>