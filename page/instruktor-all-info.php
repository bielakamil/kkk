<?php
include ('../script/month.php');
include('../script/permissions.php');
admin_instruktor_uczen();
?>
<html>

<head>
    <title> MojePrawko.pl - wszyscy uczniowie </title>
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
        } elseif ($status == 3) {
            include("../script/uczen.php"); // Dołączenie pliku z klasą uczeń
            $uczen = new uczen; // Utworzenie obiektu uczeń
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


          <h1>  
                <?php
              
                switch ($status)
                {
                case 1:
                    {
                        $mysql = connect_mysql();
                        $zapytanie = 'SELECT imie, nazwisko FROM instruktorzy WHERE id=' . $_GET['id'];
                        $result = $mysql->query($zapytanie);
                        $row = $result->fetch_array();
                        echo 'Instruktor - ' . $row['imie'] . ' ' . $row['nazwisko'];
                        break;
                    }
                case 2: 
                    {
                        $mysql = connect_mysql();
                        if (!isset($_GET['id']))
                        {
                            header ("Location: ../page/dashboard.php");
                        }
                        $zapytanie = 'SELECT imie, nazwisko FROM instruktorzy WHERE id=' . $_GET['id'];
                        $result = $mysql->query($zapytanie);
                        $row = $result->fetch_array();
                        echo 'Instruktor - ' . $row['imie'] . ' ' . $row['nazwisko'] . ' - informacje';
                        break;
                    }

                default:
                    {
                        echo 'Profil';
                        break;
                    }
                }
              
                ?>
          </h1>

        <div id="text" >
            
                    <?php

            
            if (isset($_GET['id']))
            {
                $id = $_GET['id'];
            } else
            {
                $id = 0;
            }
            
            switch ($status)
            {
                case 1:
                    {
                        echo $admin->show_profil_instruktor_all($id);
                        break;
                    }
                case 2: 
                    {
                        echo $instruktor->show_profil_instruktor_all($id);
                        break;
                    }
                default:
                    {
                        header ("Location: ../page/login.php");
                        break;
                    }
                    
            }
            
    
                    
                    ?>               

        </div>
            <?php

            if ($status == 1) {
                echo $admin->show_profil_instruktor_all_kafel();
            } elseif ($status == 2) {
                echo $instruktor->show_profil_instruktor_all_kafel();
            } 
            ?>

   
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