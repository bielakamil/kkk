<!DOCTYPE html>
<html lang="pl_PL">

<head>
 <?php
include ('../script/month.php');
include('../script/permissions.php');
admin_instruktor();
      
head ();      
?>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>
    tinymce.init({
  selector: 'textarea',
  height: 500,
  language: 'pl',
  language_url: '../vendor/tinymce/langs/pl.js',
  theme: 'modern',
  plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars  fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools '
  ],
  toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image print preview media | forecolor backcolor',
  image_advtab: true,
  templates: [
    { title: 'Test template 1', content: 'Test 1' },
    { title: 'Test template 2', content: 'Test 2' }
  ],
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css'
  ]
 });
    
</script>
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
                            
                            $admin->edit_temat_wyklad($_GET['id']);
                            
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