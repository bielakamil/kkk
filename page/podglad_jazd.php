<!DOCTYPE html>
<html lang="pl_PL">

<head>
    <?php
include ('../script/month.php');
include('../script/permissions.php');
admin_instruktor_uczen();
      
head ();

?>
<title> MojePrawko.pl - egzaminy </title>

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
} elseif ($status == 3)
{
    include ("../script/uczen.php");
    $uczen = new uczen();
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
                    } elseif ($status == 3)
                    {
                        $uczen->menu();
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
                                } elseif ($status == 3)
                                {
                                    $uczen->top();
                                }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bottom">
                            <?php
                        if ($status == 1) {
                            
                            $admin->podglad_jazd_uczen($_GET['id']);
                          
                        } elseif ($status == 2) {
                            
                            
                            $instruktor->podglad_jazd_uczen($_GET['id']);
                        }  
                    ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
         
        <!-- Add in any FooTable dependencies we may need -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
        <script src="../vendor/footable/js/footable.min.js"></script>

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
<script type="text/javascript">
$('.footable').footable();


$('.range-button').click(function()
{
    var id = $(this).attr('id');
    console.log(id);
    var type = id.slice(0,1);
    var input_id =  id.slice(2);
    console.log(input_id);
    console.log(type);
    var input = $('#' + input_id)
    var input_value = input.val();
    console.log(input_value);

    if (type == "p")
    {
        console.log("Dodaj 1");
        input_value++;
        input.val(input_value);

    } else
    {
        console.log("Odejmij 1");    
        input_value--;
        input.val(input_value);
    }
    
    ajax_update_zadanie(input)


    
});
    
    
</script>
</html>