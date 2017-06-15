<html lang="pl_PL">

<head>
    <?php
    include ('../script/month.php');
    include('../script/permissions.php');
    admin_instruktor_uczen();
   
    ?>
    <?php head ();   ?>
    <title> MojePrawko.pl  </title>
  </head>
  <body>
   
   <div id="loader-wrapper">
        <div class="load">
              <hr/><hr/><hr/><hr/>
        </div>
    </div>
   
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
} elseif ($status == 3) {
    include("../script/uczen.php"); // Dołączenie pliku z klasą uczeń
    $uczen = new uczen; // Utworzenie obiektu uczeń
}

?>      
    
   
    <div class="container-fluid"> 
        <div class="row row-eq-height ">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 hidden-xs hidden-sm menu" id="menu">
                <div class="row">
                        <?php
                    if ($status == 1) {
                        echo $admin->menu();
                    } elseif ($status == 2) {
                        echo $instruktor->menu();
                    } elseif ($status == 3) {
                        echo $uczen->menu();
                    }
                ?>
                    </div>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 head" id="head">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 top">
                            <?php
                            if ($status == 1) {
                                echo $admin->top();
                            } elseif ($status == 2) {
                                echo $instruktor->top();
                            } elseif ($status == 3) {
                                echo $uczen->top();
                            }
                        ?>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom"> 
                        
                        <?php
                            if ($status == 1) {
                                echo $admin->dashboard();
                            } elseif ($status == 2) {
                                echo $instruktor->dashboard();
                            } elseif ($status == 3) {
                                echo $uczen->dashboard();
                            }
                        ?>
                        
                    </div>
                </div>
                
                
            </div>        
        </div>
    </div>
    
    
    
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