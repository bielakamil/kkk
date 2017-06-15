<!DOCTYPE html>
<html lang="pl_PL">

<head>
    <?php
include ('../script/month.php');
include('../script/permissions.php');
admin_instruktor();
      
head ();

?>
<title> MojePrawko.pl -  wszyscy uczniowie</title>

</head>
<body>
    <?php 
include_once("../script/analyticstracking.php");
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
        <div class="container-fluid height-full">
            <div class="row row-eq-height ">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 hidden-xs hidden-sm menu" id="menu">
                    <div class="row">
                        <?php
                    if ($status == 1) {
                        echo $admin->menu();
                    } elseif ($status == 2) {
                        echo $instruktor->menu();
                    } 
                ?>
                    </div>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 head" id="head">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 top">
                            <?php
                                if ($status == 1) {
                                    $admin->top();
                                } elseif ($status == 2) {
                                    $instruktor->top();
                                } 
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom">
                            <?php
                        if ($status == 1) {
                            
                            if (isset($_GET['new']))
                            {
                                $admin->PokazWszystkichNowychUczniow();
                            } elseif (isset($_GET['all']))
                            {
                                $admin->PokazWszystkichUczniow();
                            } elseif (isset($_GET['instruktor']))
                            {
                                $admin->PokazWszystkichUczniowInstruktora($_GET['instruktor']);
                            } elseif (isset($_GET['www']))
                            {
                                $admin->PokazWszystkichUczniowZapisanychPrzezWWW();  
                            } else
                            {
                                $admin->PokazWszystkichUczniow();
                            }         
                          
                        } elseif ($status == 2) {
                            
                            if (isset($_GET['new']))
                            {
                                $instruktor->PokazWszystkichNowychUczniow();
                            } elseif (isset($_GET['all']))
                            {
                                $instruktor->PokazWszystkichKursantowWSzkole();
                            } else
                            {
                                $instruktor->PokazWszystkichUczniow();
                            }
                            
                        } 
                    ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
        <script src="../vendor/footable/js/footable.min.js"></script>
        <script>
                        jQuery(function ($) {
                $('.footable').footable();
            });
        </script><div class="notifications">
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