<?php


$month[1] = 'styczeń';
$month[2] = 'luty';
$month[3] = 'marzec';
$month[4] = 'kwiecień';
$month[5] = 'maj';
$month[6] = 'czerwiec';
$month[7] = 'lipiec';
$month[8] = 'sierpień';
$month[9] = 'wrzesień';
$month[10] = 'październik';
$month[11] = 'listopad';
$month[12] = 'grudzień';

$etap[1] = 'Chodzi na wykłady';
$etap[2] = 'Oczekuje na jazdy';
$etap[3] = 'Chodzi na jazdy';
$etap[4] = 'Oczekuje na egzamin';
$etap[5] = 'Posiada uprawnienia';
$etap[6] = 'Zrezygnował';

$sex[1] = 'Mężczyzna';
$sex[2] = 'Kobieta';


$etap_wyklad[1] = 'Zaplanowane';
$etap_wyklad[2] = 'Odbyły się';
$etap_wyklad[3] = 'Odwołane';

$etap_jazdy[1] = 'Zaplanowane';
$etap_jazdy[2] = 'Odbyły się';
$etap_jazdy[3] = 'Odwołane przez ucznia';
$etap_jazdy[4] = 'Odwołane przez instruktora';
$etap_jazdy[5] = 'Trwają';


$dzien_tyg[1] = 'poniedziałek';
$dzien_tyg[2] = 'wtorek';
$dzien_tyg[3] = 'środa';
$dzien_tyg[4] = 'czwartek';
$dzien_tyg[5] = 'piątek';
$dzien_tyg[6] = 'sobota';
$dzien_tyg[7] = 'niedziela';

$egzamin_stan[1] = 'Zaplanowane';
$egzamin_stan[2] = 'Pozytywny';
$egzamin_stan[3] = 'Negatywny';


$egzamin_typ[1] = 'Egzamin wewnętrzny - teoria';
$egzamin_typ[2] = 'Egzamin wewnętrzny - praktyka';
$egzamin_typ[3] = 'Egzamin państwowy - teoria';
$egzamin_typ[4] = 'Egzamin państwowy - praktyka';

$car_skrzynia[1] = 'Manual';
$car_skrzynia[2] = 'Automat';

if (!function_exists('head')) {

function head ()
{
    
    
    echo '<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'; 
    echo '<meta charset="utf-8">';
    echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<link href="../style/style-bootstrap.css" rel="stylesheet" type="text/css">';
    echo '<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">';
    echo '<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">';
    echo '<link href="../style/style_media_query.css" rel="stylesheet" type="text/css">';
    echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
    echo '<link href="../style/bootstrap/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="../vendor/footable/css/footable.bootstrap.min.css" rel="stylesheet">';
    echo '<script src="../script/show_menu.js" type="text/javascript">';
    echo '</script>';
    echo '<script src="../script/jquery-3.1.1.min.js"> </script>';
    echo '<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
    echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';
    echo '<link href="../vendor/select2-4.0.3/dist/css/select2.min.css" rel="stylesheet" type="text/css">';
    echo '<script src="../vendor/select2-4.0.3/dist/js/select2.min.js" type="text/javascript"> </script>'; 
    echo '<script src="../script/powiadomienia.js" type="text/javascript"></script>';

}
}

?>