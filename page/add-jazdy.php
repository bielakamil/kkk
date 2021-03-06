<!DOCTYPE html>
<html lang="pl_PL">

<head>
    <?php
include ('../script/month.php');
include('../script/permissions.php');
admin_instruktor();
      
head ();      
?>
<title> MojePrawko.pl - dodaj jazdę </title>
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
                    } elseif ($status == 2)
                    {
                        $instruktor->menu();
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
                                } elseif ($status == 2)
                                {
                                    $instruktor->top();
                                }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom">
                            <?php
                            if ($status == 1) {
                            if (isset($_GET['uczen']))
                            {
                                $uczen = $_GET['uczen'];
                            } else
                            {
                                $uczen = 0;
                            } 
                            $admin->add_jazda($uczen);
                            } elseif ($status == 2)
                            {
                                if (isset($_GET['uczen']))
                                {
                                    $uczen = $_GET['uczen'];
                                } else
                                {
                                    $uczen = 0;
                                } 
                                $instruktor->add_jazda($uczen);
                            }
                    ?>
                        </div>
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




<script type="text/javascript">

    $('#add_jazda_kursant').change( function ()
        {
            miejsce_spotkania();
        })
    
    miejsce_spotkania();
    
    function miejsce_spotkania ()
    {
        var kursant = $('#add_jazda_kursant').val();
        var miejsce_spotkania = $('#add_jazda_m-' + kursant).val();
        $('#add_jazda_miejsce_spotkania').val(miejsce_spotkania);
    }
    
    
    
</script>


</body>

</html>