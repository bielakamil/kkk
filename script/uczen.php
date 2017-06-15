<?php

class uczen 
{
var $pesel;
var $imie;
var $nazwisko;

public function __construct () // Funkcja uruchamia się podczas tworzenie nowego obiektu, czyli co stronę
{
    include ('../script/mysql.php'); // Dołączenie pliku mysql.php
    $mysql = connect_mysql(); // Połączenie z bazą mysql
    $zapytanie = 'SELECT uczniowie.id,uczniowie.imie, uczniowie.nazwisko,uczniowie.stan, uczniowie.plec FROM uczniowie WHERE pesel="' . $_SESSION['pesel'] . '"'; // Zapytanie zwracające imie i nazwisko ucznia
    $result = $mysql->query($zapytanie); // Wysłanie zapytania
    
    if ($result->num_rows != 1) // Jeśli jest tylko jeden rekord to istnieje podany uczeń
    {
        header ("Location: ../page/login.php");
    }
    
    $row = $result->fetch_array(); // Pobranie wyniku
    
    // Przypisanie zmiennych
    $this->pesel = $_SESSION['pesel'];
    $this->imie = $row['imie'];
    $this->nazwisko = $row['nazwisko'];  
    $this->id = $row['id'];  
    $this->stan = $row['stan'];  
    $this->plec = $row['plec'];
}
    
public function top ()
{
    
    echo '<div class="row">';
        echo '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-1" id="search">';
            echo '<select id="input-search" class="input-sm form-control">';
            echo '</select>';
        echo '</div>';
        echo '<div class="col-lg-8 col-md-8 col-sm-8 col-xs-11 text-right icons" id="icons">';
            echo '<i class="fa fa-bell fa-2x fa-fw" id="notifications-open"> </i>';
            if ($this->nieprzeczytane_wiadomosci() > 0)
            {
                $active = 'active';
            } else
            {
                $active = '';
            }
            echo '<i class="fa fa-comments fa-2x fa-fw ' . $active . '" id="news-open"> </i>';
            echo '<a href="../page/uczen.php">';
                echo $this->imie . ' ' . $this->nazwisko;
            echo '</a>';
            echo '<i class="fa fa-angle-down fa-2x fa-fw hidden-xs hidden-sm" id="small-menu-open"> </i>';
            echo '<i class="fa fa-bars fa-2x fa-fw hidden-lg hidden-md" id="mobile-menu-open"> </i>';
        echo '</div>';
    echo '</div>';
     
}    
    
public function nieprzeczytane_wiadomosci ()
{
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT count(*) AS ile FROM message_user WHERE reading=0 AND receiver=' . $this->id;
    $result = $mysql->query($zapytanie);
    $row = $result->fetch_array();
    $ilosc = $row['ile'];
    
    
    return $ilosc;
}
    
public function dashboard ()
{
    
$mysql = connect_mysql();    
include ('../script/month.php');    

$zapytanie_instruktor = 'SELECT instruktorzy.id, instruktorzy.imie, instruktorzy.nazwisko, instruktorzy.email, instruktorzy.telefon, uczniowie.stan, uczniowie.jazdy, uczniowie.cena FROM instruktorzy, uczniowie WHERE uczniowie.instruktor=instruktorzy.id AND uczniowie.id=' . $this->id;
$result_instruktor = $mysql->query($zapytanie_instruktor);
$row_instruktor = $result_instruktor->fetch_array();    
    
$zapytanie_wyklady = 'SELECT count(*) AS ile FROM obecnosc WHERE uczen=' . $this->id;
$result_wyklady = $mysql->query($zapytanie_wyklady);
$row_wyklady = $result_wyklady->fetch_array();
$ile_wykladow = $row_wyklady['ile'];
$zapytanie_dodatkowe = 'SELECT dodatkowe_jazdy.jazdy, dodatkowe_jazdy.cena FROM dodatkowe_jazdy WHERE uczen=' . $this->id;
$result_dodatkowe = $mysql->query($zapytanie_dodatkowe);
$caly_czas = 0;
$caly_koszt = 0;
while ($row_dodatkowe = $result_dodatkowe->fetch_array())
{
    $caly_czas += $row_dodatkowe['jazdy'];
    $caly_koszt += $row_dodatkowe['cena'];
}
$zapytanie_pieniadze = 'SELECT wplaty.kasa FROM wplaty,uczniowie WHERE uczniowie.id=wplaty.uczen AND uczniowie.pesel="' . $this->pesel . '"';
$result_pieniadze = $mysql->query($zapytanie_pieniadze);
$wplacone_pieniadze =0;
while ($row_pieniadze = $result_pieniadze->fetch_array())
{
    $wplacone_pieniadze += $row_pieniadze['kasa'];
}
$caly_koszt += $row_instruktor['cena'];
$zapytanie_jazdy = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE stan=2 AND uczen="' . $this->id . '"';
$result_jazdy = $mysql->query($zapytanie_jazdy);
$wynik = 0;
while ($row_jazdy = $result_jazdy->fetch_array())
{
    $start = strtotime($row_jazdy['data_rozpoczecia']);
    $koniec = strtotime($row_jazdy['data_zakonczenia']);
    $roznica = $koniec-$start;
    $wynik +=  $roznica;
}
$caly_czas += $row_instruktor['jazdy'];
$godzina = ($wynik-(floor($wynik%3600)))/3600;
$minuta = ($wynik-($godzina*3600))/60;
if ($minuta == 0)
{
    $czas = $godzina;  
} else
{
    if ($minuta < 10)
    {
        $minuta = '0' . $minuta;
    }
    $czas = $godzina . ':' . $minuta;
}    
    
    
$zapytanie_kara = 'SELECT odwolane_jazdy.cena FROM odwolane_jazdy WHERE uczen=' . $this->id;
$result_kara = $mysql->query($zapytanie_kara);
    
while ($row_kara = $result_kara->fetch_array())
{
    $caly_koszt += $row_kara['cena'];
}
    
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered">';
    echo '<div class="row">';
        echo '<h4 class="text-center info-test col-lg-12 col-md-12 col-xs-12 col-sm-12" style="margin-bottom:40px"> Serwis w fazie testów. Informacje w nim zawarte mogą różnić się od rzeczywistych. Zachęcamy do zgłaszania błędów na adres: krzysiek@4-kolka.pl </h4>'; 
        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 calendary ">';
            $this->agenda();
        echo '</div>';
        echo '<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 col-lg-offset-1">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dashboard_info">';
                echo '<h4> MÓJ KURS </h4>';
                echo '<span> Etap: ' . $etap[$row_instruktor['stan']]  . '</span>';
                echo '<span> <a href="../page/wyklady.php"> Wykłady: ' . $ile_wykladow . ' </a></span>';
                echo '<span> <a href="../page/jazdy.php"> Jazdy: ' . $czas . '/' . $caly_czas . ' </a></span>';
                echo '<span> <a href="../page/wplaty.php"> Finanse: ' . $wplacone_pieniadze  . '/' . $caly_koszt . ' </a></span>';
                echo '<h4> MÓJ EGZAMIN </h4>';
                $egzamin_typ[1] = 'Wewnętrzny - TEORIA';
                $egzamin_typ[2] = 'Wewnętrzny - PRAKTYKA';
                $egzamin_typ[3] = 'Państwowy - TEORIA';
                $egzamin_typ[4] = 'Państwowy - PRAKTYKA';
                for ($x=1;$x<=count($egzamin_typ);$x++)
                {
                    $zapytanie_egzamin = 'SELECT data,stan FROM egzamin WHERE uczen=' . $this->id . ' AND typ=' . $x;
                    $result_egzamin = $mysql->query($zapytanie_egzamin);
                    if ($result_egzamin->num_rows == 0)
                    {
                        echo '<span>' . $egzamin_typ[$x] . ': Brak informacji </span>';
                    } else
                    {
                        $zaliczone = false;
                        while ($row_egzamin = $result_egzamin->fetch_array())
                        {
                            if ($row_egzamin['stan'] == 2)
                            {
                                $zaliczone = true;
                            }
                        }
                        
                        
                        
                        if ($zaliczone == true)
                        {
                            echo '<span>' . $egzamin_typ[$x] . ': Zaliczone </span>';
                        } else
                        {
                            echo '<span>' . $egzamin_typ[$x] . ': Nie zaliczono </span>';
                        }
                    }
                }
                echo '<h4> MÓJ INSTRUKTOR </h4>';
                echo '<span>' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</span>';
                $telefon = $row_instruktor['telefon'];
                $t1 = substr($telefon,0,3);
                $t2 = substr($telefon,3,3);
                $t3 = substr($telefon,6,3);
                $telefon = $t1 . ' ' . $t2 . ' ' . $t3;
                echo '<span> Telefon: ' . $telefon . '</span>';
                echo '<span> E-mail: ' . $row_instruktor['email'] . '</span>';
            echo '</div>';
//            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  dashboard_friends">';
//                echo '<h4> Możesz znać </h4>';
//    
//                $people = $this->youknow(4);
//    
//                for ($x=0;$x<count($people);$x++)
//                {
//                    if ($x == 0)
//                    {
//                    echo '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-8  people">';
//                    } else
//                    {
//                    echo '<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs people">';
//                    }             
//                        echo '<a href="../page/uczen.php?id=' . $people[$x]['id'] . '"> <img src="../img/prifile-pic.png"> </a>';
//                        echo '<span> <a href="../page/uczen.php?id=' . $people[$x]['id'] . '">' . $people[$x]['name'] . ' </a> </span>';
//                    echo '</div>';
//                }
//            echo '</div>';
        echo '</div>';
    echo '</div>';
echo '</div>';
echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 osiagniecia">';
    echo 'W budowie';
echo '</div>';
echo '<a href="#">';
    echo '<div class="col-lg-2 col-lg-offset-10 col-md-3 col-md-offset-9 col-sm-3 col-sm-offset-9 col-xs-8 col-xs-offset-4 moje_osiagniecia">';
        echo 'Moje osiągnięcia';
    echo '</div>';
echo '</a>';
//echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 title">';
//    echo '<h3> Znajomi </h3>';
//echo '</div>';
//echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12  friends">';
//    $this->friends($this->id); 
//echo '</div>';    

}
    
public function news ()
{
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT message_user.id, message_user.title, message_user.text, message_user.reading, message_user.sender FROM message_user WHERE message_user.receiver=' . $this->id . ' ORDER BY message_user.date DESC LIMIT 5';
    
    
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 0)
    {
        echo '<span> Nie masz żadnej wiadomośći <span>';
    }
    
    while ($row = $result->fetch_array())
    {
        if ($row['reading'] ==  false)
        {
            $active = 'active';
        } else
        {
            $active = '';
        }
        
        echo '<a href="../page/message.php?id=' . $row['id'] . '">';
            echo '<div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12 message-obj ' . $active . '">';
                
                if ($row['sender'] == 0)
                {
                    $nadawca = 'Administracja';
                } else
                {
                    $zapytanie_autor = 'SELECT instruktorzy.imie, instruktorzy.nazwisko FROM instruktorzy WHERE instruktorzy.id=' . $row['sender'];
                    $result_autor = $mysql->query($zapytanie_autor);
                    
                    if ($result_autor->num_rows == 0)
                    {
                        $nadawca = 'Nie wiadomo kto wysłał wiadomość. Zgłoś to Administracji';
                    } else
                    {
                        $row_autor = $result_autor->fetch_array();
                        $nadawca = $row_autor['imie'] . ' ' . $row_autor['nazwisko'];
                    }
                }
        
                echo '<h5>' . $row['title'] . ' <span class="message-autor"> ~ ' . $nadawca . ' </span> </h5>';
                echo '<p>' . substr($row['text'],0,200) . '...</p>';
            echo '</div>';
        echo '</a>';
    }
    
    
    
    
}
    
public function message ($id)
{
    
    $mysql = connect_mysql();    
    include ('../script/month.php');   

    $zapytanie = 'SELECT message_user.id, message_user.title, message_user.text, message_user.reading, message_user.sender, message_user.date FROM message_user WHERE message_user.receiver=' . $this->id . ' AND message_user.id=' . $id;
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
        
    $row = $result->fetch_array();
    
    if ($row['reading'] == false)
    {
        $update = 'UPDATE message_user SET message_user.reading=true WHERE message_user.id=' . $id;
        $mysql->query($update);
    }


    echo '<h3 class="col-lg-11 col-lg-offset-1 col-md-11 col-md-offset-1 col-sm-11 col-sm-offset-1 col-xs-11 col-xs-offset-1 temat-wykladu-title">' . $row['title'] . '</h3>';
    
    echo '<span class="col-lg-11 col-lg-offset-1 col-md-11 col-md-offset-1 col-sm-11 col-sm-offset-1 col-xs-11 col-xs-offset-1 temat-wykladu-info">';
        echo '<b> Autor: </b>';
            if ($row['sender'] == 0)
            {
                echo 'Administracja';
            } else
            {
                $zapytanie_autor = 'SELECT instruktorzy.imie, instruktorzy.nazwisko FROM instruktorzy WHERE instruktorzy.id=' . $row['sender'];
                $result_autor = $mysql->query($zapytanie_autor);
                
                if ($result_autor->num_rows == 0)
                {
                    echo 'Nie wiadomo kto wysłał wiadomość. Zgłoś to Administracji';
                } else
                {
                    $row_autor = $result_autor->fetch_array();
                    echo '<a href="../page/instruktor.php?id=' . $row['sender'] . '">' . $row_autor['imie'] . ' ' . $row_autor['nazwisko'] . '</a>';
                }
                
            }
        echo '<br>';
        echo '<b> Data: </b>';
        include ('../script/month.php');
        $dzien  = date("d", strtotime($row['date'])) . ' '  . $month[date("n", strtotime($row['date']))] . ' ' . date("Y", strtotime($row['date']));
        $czas = date("H", strtotime($row['date'])) . ':' . date("i", strtotime($row['date']));
        echo $dzien . ' ' . $czas;
    echo '</span>';    
   
    
    echo '<div class="col-lg-10 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 temat-wykladu-notatka">';
        echo $row['text'];
    echo '</div>';
    
}
    
public function youknow ($ile)
{
    $mysql = connect_mysql();
    $zapytanie_uczen = 'SELECT instruktor FROM uczniowie WHERE id=' . $this->id;
    $result_uczen = $mysql->query($zapytanie_uczen);
    $row_uczen = $result_uczen->fetch_array();
    $instruktor = $row_uczen['instruktor'];
    
    
    $x = 0;
    $y = 0;
    $people = [];
    
    while (count($people) < $ile)
    {
        $zapytanie_uczniowie = 'SELECT id,imie,nazwisko FROM uczniowie WHERE instruktor=' . $instruktor . ' ORDER BY id LIMIT ' . $y . ',1';
        $result_uczniowie = $mysql->query($zapytanie_uczniowie);
        
        $row_uczniowie = $result_uczniowie->fetch_array();
        $zapytanie_follow = 'SELECT id FROM friends WHERE uczen=' . $this->id . ' AND obserwowany=' . $row_uczniowie['id'];
        $result_follow = $mysql->query($zapytanie_follow);
        
        if ($result_follow->num_rows == 0)
        {
            $people[$x]['id'] = $row_uczniowie['id'];
            $people[$x]['name'] = $row_uczniowie['imie'] . ' ' . $row_uczniowie['nazwisko'];  
            $x++;
        }
        $y++;    
    }
    
        
    
    return $people;
}
    
public function menu ()
{
    $mysql = connect_mysql();
    
    // LOGO
    echo '<a href="../page/dashboard.php">';
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 logo text-center">';
            echo '<b>MOJE</b>PRAWKO.PL';
        echo '</div>';
    echo '</a>';
    echo '<div class="hidden-lg hidden-md col-sm-2 col-sm-offset-10 col-xs-2 col-xs-offset-10 text-right close_menu">';
        echo '<i class="fa fa-times fa-fw fa-2x"  id="mobile-menu-close"></i>';
    echo '</div>';
    
    // WYKŁADY
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="material-icons"> work </i>';
            echo '<span> Wykłady </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
        
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                $zapytanie_wyklad = 'SELECT count(id) AS ile FROM wyklady WHERE stan=1';
                $result_wyklad = $mysql->query($zapytanie_wyklad);
                $row_wyklad = $result_wyklad->fetch_array();
                echo '<a href="../page/wyklady.php?new=on"> Najbliższe wykłady [' . $row_wyklad['ile'] . ']  </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                $zapytanie_wyklad = 'SELECT count(wyklady.id) AS ile FROM obecnosc,wyklady WHERE obecnosc.wyklad=wyklady.id AND wyklady.stan=2 AND obecnosc.uczen=' . $this->id;
                $result_wyklad = $mysql->query($zapytanie_wyklad);
                $row_wyklad = $result_wyklad->fetch_array();
                echo '<a href="../page/wyklady.php"> Moje wykłady [' . $row_wyklad['ile'] . '] </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/tematy-wyklad.php"> Lista tematów </a>';
            echo '</div>'; 
         echo '</div>';
    echo '</div>';
    
    // JAZDY
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="material-icons"> directions </i>';
            echo '<span> Jazdy </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                $zapytanie_jazdy = 'SELECT count(id) AS ile FROM jazdy WHERE stan=1 AND uczen=' . $this->id;
                $result_jazdy = $mysql->query($zapytanie_jazdy);
                $row_jazdy = $result_jazdy->fetch_array();
                echo '<a href="../page/jazdy.php?new=on"> Najbliższe jazdy [' . $row_jazdy['ile'] . '] </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                $zapytanie_jazdy = 'SELECT count(id) AS ile FROM jazdy WHERE stan=2 AND uczen=' . $this->id;
                $result_jazdy = $mysql->query($zapytanie_jazdy);
                $row_jazdy = $result_jazdy->fetch_array();
                echo '<a href="../page/jazdy.php"> Moje jazdy [' . $row_jazdy['ile'] . '] </a>';
            echo '</div>';
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/jazdy.php?all=on"> Wszystkie  </a>';
            echo '</div>'; 
        echo '</div>';
    echo '</div>';   
    
//    // EGZAMINY 
//    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
//        echo '<a href="#">';
//            echo '<i class="glyphicon glyphicon-list-alt"></i>';
//            echo '<span> Egzaminy </span>';
//        echo '</a>';
//        echo '<div class="row subsubmenu">'; 
//            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
//                echo '<a href="../page/add-egzamin.php"> Dodaj egzamin </a>';
//            echo '</div>'; 
//            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
//                $zapytanie_egzamin = 'SELECT count(egzamin.id) as ile FROM egzamin WHERE uczen=' . $this->id;
//                $result_egzamin = $mysql->query($zapytanie_egzamin);
//                $row_egzamin = $result_egzamin->fetch_array();
//                echo '<a href="../page/egzaminy.php"> Moje egzaminy [' . $row_egzamin['ile'] . ']  </a>';
//            echo '</div>'; 
//        echo '</div>';
//    echo '</div>';

    // WYLOGUJ
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="../page/logout.php">';
            echo '<i class="material-icons"> input </i>';
            echo '<span> Wyloguj </span>';
        echo '</a>';
    echo '</div>';
}
    
public function PokazWszystkieEgzaminy ()
{
    
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie egzaminy </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-egzamin.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj egzamin';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT egzamin.id, egzamin.data, egzamin.stan, egzamin.typ FROM uczniowie,egzamin WHERE egzamin.uczen=uczniowie.id AND uczniowie.id=' . $this->id;
        $result = $mysql->query($zapytanie);
    
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th>  </th>';
                        echo '<th data-breakpoints="xs sm"> Rodzaj </th>';
                        echo '<th data-breakpoints="xs sm"> Stan </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            $dzien  = date("d", strtotime($row['data'])) . ' '  . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                            $czas = date("H", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
                            echo '<td>' . $dzien . '</td>';
                            echo '<td>' . $czas . '</td>';
                            echo '<td>' . $egzamin_typ[$row['typ']] . '</td>';
                            echo '<td>' . $egzamin_stan[$row['stan']] . '</td>';
                            echo '<td> <a href="../page/egzamin.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';    
            echo '</table>';
        } else
        {
            echo '<span> Nie zaplanowano żadnych egzaminów </span>';
        }
    
    echo '</div>';
}    
    
public function egzamin ($id)
{
    
    
    $mysql = connect_mysql();    
    include ('../script/month.php');   

    // TYTUŁ    
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Egzamin </h3>';
    echo '</div>';

    echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
        // KARTKA
        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';   
            $zapytanie = 'SELECT egzamin.data, egzamin.stan,egzamin.notatka, egzamin.typ, uczniowie.id, uczniowie.imie, uczniowie.nazwisko FROM uczniowie, egzamin WHERE egzamin.uczen=uczniowie.id AND uczniowie.id=' . $this->id . ' AND egzamin.id=' . $id;
            $result = $mysql->query($zapytanie);
            if ($result->num_rows == 0)
            {
                header ("Location: ../page/egzaminy.php");
                exit();
            }
            $row = $result->fetch_array();
            echo '<table class="table">';
                echo '<tr>';
                    echo '<td> Data </td>';
                    $data  = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data'])) . ', ' . date("G:i", strtotime($row['data']));
                    echo '<td>' . $data . '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td> Stan </td>';
                    echo '<td> ' . $egzamin_stan[$row['stan']] . '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td> Rodzaj </td>';
                    echo '<td> ' . $egzamin_typ[$row['typ']] . '</td>';
                echo '</tr>';
            echo '</table>';
            echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
                echo '<h3> Opis egzaminu </h3>';        
            echo '</div>'; 
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                echo '<p>' . $row['notatka'] . '</p>';
            echo '</div>';
        echo '</div>';
        // PRZYCISKI
        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';
        // TYP: BUTTON LUB NORMAL
        // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
        $button[0]['typ'] = 'normal';
        $button[0]['cat_icon'] = 'material-icons';
        $button[0]['icon'] = 'mode_edit';
        $button[0]['text'] = 'Edytuj';
        $button[0]['href'] = '../page/edit-egzamin.php?id=' . $id;
    
        $button[1]['typ'] = 'clear';
        $button[1]['cat_icon'] = '';
        $button[1]['icon'] = '';
        $button[1]['text'] = '';
        $button[1]['href'] = '';
    
        $this->buttons($button);
        echo '</div>';  
    echo '</div>'; 
    
    

}
       
    
    
public function PokazWszystkieTematyWykladow()
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Lista tematów </h3>';
    echo '</div>';
    
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie_kategoria = 'SELECT kategorie_wyklad.id,kategorie_wyklad.nazwa FROM kategorie_wyklad ORDER BY kategorie_wyklad.id';
        $result_kategoria = $mysql->query($zapytanie_kategoria);
        if ($result_kategoria->num_rows > 0)
        {
            while ($row_kategoria = $result_kategoria->fetch_array())
            {
                if ($row_kategoria['nazwa'] != 'Nie wybrano')
                {
                    $zapytanie_temat = 'SELECT tematy_wyklad.id, tematy_wyklad.temat FROM tematy_wyklad WHERE tematy_wyklad.kategoria=' . $row_kategoria['id'] . ' ORDER BY tematy_wyklad.temat';
                    $result_temat = $mysql->query($zapytanie_temat);
                    echo '<h3><a href="../page/kategoria-wyklad.php?id=' . $row_kategoria['id'] . '">' . $row_kategoria['nazwa'] . '</h3>';
                    if ($result_temat->num_rows > 0)
                    {
                        echo '<table class="table">';
                            while ($row_temat = $result_temat->fetch_array())
                            {
                                echo '<tr>';
                                    echo '<td><a href="../page/temat-wyklad.php?id=' . $row_temat['id'] . '">' . $row_temat['temat'] . '</a> </td>';
                                echo '</tr>';
                            }
                        echo '</table>';
                    } else
                    {
                        echo '<span> Nie dodano żadnego tematu </span>';
                    }
                }   
            }
        } else
        {
            echo '<span> Nie dodano żadnej kategorii i tematu </span>';
        }
}
    
public function temat_wyklad($id)
{
    
$mysql = connect_mysql();
    
$zapytanie = 'SELECT tematy_wyklad.temat, tematy_wyklad.czas, kategorie_wyklad.id, kategorie_wyklad.nazwa, tematy_wyklad.notatka FROM kategorie_wyklad, tematy_wyklad WHERE kategorie_wyklad.id=tematy_wyklad.kategoria AND tematy_wyklad.id=' . $id;
    
$result = $mysql->query($zapytanie);
    
if ($result->num_rows == 0)
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    
$row = $result->fetch_array();
    
echo '<h3 class="col-lg-11 col-lg-offset-1 col-md-11 col-md-offset-1 col-sm-11 col-sm-offset-1 col-xs-11 col-xs-offset-1 temat-wykladu-title">' . $row['temat'] . '</h3>';
    
echo '<span class="col-lg-11 col-lg-offset-1 col-md-11 col-md-offset-1 col-sm-11 col-sm-offset-1 col-xs-11 col-xs-offset-1 temat-wykladu-info">';
    echo '<b> Kategoria: </b>';
    echo '<a href="../page/kategoria-wyklad.php?id=' . $row['id'] . '">' . $row['nazwa'] . '</a>'; 
    echo '<br>';
    echo '<b> Czas trwania: </b>';
    $czas = date("G", strtotime($row['czas'])) . 'h ' . date("i", strtotime($row['czas'])) . 'min';
    echo $czas;
echo '</span>';    

echo '<h4 class="col-lg-11 col-lg-offset-1 col-md-11 col-md-offset-1 col-sm-11 col-sm-offset-1 col-xs-11 col-xs-offset-1 temat-wykladu-subtitle"> Opis </h4>';    
    
echo '<div class="col-lg-10 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 temat-wykladu-notatka">';
    echo $row['notatka'];
echo '</div>';
     
}

    
public function kategoria_wyklad($id)
{
    
    if (isset($_GET['id']))
    {
    $mysql = connect_mysql();
    $zapytanie = 'SELECT kategorie_wyklad.nazwa FROM kategorie_wyklad WHERE kategorie_wyklad.id=' . $id;
    $result = $mysql->query($zapytanie);
    if ($result->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();  
    }
        
    $row = $result->fetch_array();
        
    $mysql = connect_mysql();    
    include ('../script/month.php');   

    // TYTUŁ    
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> ' . $row['nazwa'] . ' </h3>';
    echo '</div>';

    echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
        // KARTKA
        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';   
            $zapytanie_tematy = 'SELECT tematy_wyklad.id, tematy_wyklad.temat FROM tematy_wyklad WHERE tematy_wyklad.kategoria=' . $id;
            $result_tematy = $mysql->query($zapytanie_tematy);    
            if ($result_tematy->num_rows == 0)
            {
                '<span> Nie ma żadnego tematu </span>';
            } else
            {
                echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
                    echo '<h3> Wszystkie tematy </h3>';        
                echo '</div>'; 
                echo '<table class="table">';
                    while ($row_temat = $result_tematy->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td><a href="../page/temat-wyklad.php?id=' . $row_temat['id'] . '">' . $row_temat['temat'] . '</a> </td>';
                        echo '</tr>';
                    }
                echo '</table>';
            }
        echo '</div>';      
    echo '</div>';    
    // PRZYCISKI
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';
    
        // TYP: BUTTON LUB NORMAL
        // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
        $button[0]['typ'] = 'clear';
        $button[0]['cat_icon'] = 'material-icons';
        $button[0]['icon'] = '';
        $button[0]['text'] = '';
        $button[0]['href'] = '';
    
        $button[1]['typ'] = 'clear';
        $button[1]['cat_icon'] = 'material-icons';
        $button[1]['icon'] = '';
        $button[1]['text'] = '';
        $button[1]['href'] = '';
    
        $this->buttons($button);
            
    
    echo '</div>';
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
}
   
    
public function PokazWszystkieNajblizszeWyklady()
{
    
     // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Najbliższe wykłady </h3>';
    echo '</div>';
    
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';        
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan,wyklady.instruktor,tematy_wyklad.temat, wyklady_miejsce.nazwa FROM tematy_wyklad,wyklady, wyklady_miejsce WHERE tematy_wyklad.id=wyklady.temat AND wyklady.miejsce=wyklady_miejsce.id AND wyklady.stan=1 ORDER BY wyklady.data';
        $result= $mysql->query($zapytanie);

        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> </th>';
                        echo '<th data-breakpoints="xs sm"> Wykładowca </th>';
                        echo '<th data-breakpoints="xs sm"> Temat </th>';
                        echo '<th data-breakpoints="xs sm"> Miejsce </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            $data = date("d", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . $data_rok = date("Y", strtotime($row['data']));
                            $godzina = date("G", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
                            echo '<td>' . $data . '</td>';
                            echo '<td>' . $godzina . '</td>';
                        
                            $zapytanie_instruktor = 'SELECT imie, nazwisko FROM instruktorzy WHERE id=' . $row['instruktor'];
                            $result_instruktor = $mysql->query($zapytanie_instruktor);
                            if ($result_instruktor->num_rows == 1)
                            {      
                                $row_instruktor = $result_instruktor->fetch_array();
                                $instruktor =  $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'];
                            } else
                            {
                                $instruktor = 'Nie wybrano';
                            }
                            echo '<td>' . $instruktor . '</td>';
                            echo '<td>' . $row['temat'] . '</td>';
                            echo '<td>' . $row['nazwa'] . '</td>';
                            echo '<td> <a href="../page/wyklad.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';                      
                        echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else
        {
            echo '<span> Nie zaplanowano najbliższych wykładów </span>';
        }

    echo '</div>';

}  
    
public function ile_powiadomien ()
{

    $mysql = connect_mysql(); // Połączenie z bazą mysql
    
    $zapytanie = 'SELECT count(odbiorca) as ile FROM powiadomienia WHERE odbiorca="' . $this->pesel . '" AND stan=1';
    
    $result = $mysql->query($zapytanie);
    
    $row = $result->fetch_array();
    $ile = $row['ile'];
    return $ile;
}
    
public function agenda ()
{
    $mysql = connect_mysql();

    $ilosc_dni = 31;
    $date = date('Y-m-d');
    $ilosc = 0;
    for ($x=0;$x<$ilosc_dni;$x++)
    {
        $today = date('Y-m-d', strtotime($date. ' + ' . $x . ' days'));    
        $ilosc += $this->today($today);
    }
    if ($ilosc == 0)
    {
        echo '<span> Nie zaplanowano żadnych rzeczy </span>';
    }
    
    
}
    
public function today ($date)
{
    $mysql = connect_mysql();
    
    /*
    1 - WYKŁAD 
    2 - JAZDY
    3 - EGZAMIN
    */
        
    $c = 0;
    $a = 0;
    
    $zapytanie_wyklad = 'SELECT wyklady.id,wyklady.data, wyklady_miejsce.nazwa, tematy_wyklad.id as temat, instruktorzy.imie, instruktorzy.nazwisko FROM wyklady, wyklady_miejsce, tematy_wyklad, kursanci_grupa, wyklad_grupa, instruktorzy  WHERE instruktorzy.id=wyklady.instruktor  AND kursanci_grupa.kursant=' . $this->id . ' AND kursanci_grupa.grupa=wyklad_grupa.grupa AND wyklady.id=wyklad_grupa.wyklad  AND  wyklady.temat=tematy_wyklad.id AND wyklady_miejsce.id=wyklady.miejsce AND date(data)="' . $date . '" ORDER BY data';
    $result_wyklad = $mysql->query($zapytanie_wyklad); 
    while ($row_wyklad = $result_wyklad->fetch_array())
    {
        $wyklad[$c]['id'] = $row_wyklad['id'];
        $wyklad[$c]['data'] = $row_wyklad['data'];
        $wyklad[$c]['miejsce'] = $row_wyklad['nazwa'];
        $wyklad[$c]['wykladowca'] = $row_wyklad['imie'] . ' ' . $row_wyklad['nazwisko'];
        $wyklad[$c]['temat'] = $row_wyklad['temat'];
        $today[$a]['data'] = $row_wyklad['data'];
        $today[$a]['id'] = $c;
        $today[$a]['what'] = 1;
        $c++;
        $a++;
    }
    
    $zapytanie_jazdy = 'SELECT instruktorzy.id,instruktorzy.imie, instruktorzy.nazwisko, jazdy.data_rozpoczecia,jazdy.data_zakonczenia FROM jazdy,uczniowie,instruktorzy WHERE jazdy.uczen=uczniowie.id AND uczniowie.instruktor=instruktorzy.id AND uczniowie.id=' . $this->id . ' AND date(data_rozpoczecia)="' . $date . '" ORDER BY data_rozpoczecia'; 
    $result_jazdy = $mysql->query($zapytanie_jazdy);
    $c = 0;
    while ($row_jazdy = $result_jazdy->fetch_array())
    {
        $jazdy[$c]['id'] = $row_jazdy['id'];
        $jazdy[$c]['data'] = $row_jazdy['data_rozpoczecia'];
        $jazdy[$c]['koniec'] = date("H:i", strtotime($row_jazdy['data_zakonczenia'])); 
        $jazdy[$c]['instruktor'] = $row_jazdy['imie'] . ' ' . $row_jazdy['nazwisko'];
        $today[$a]['data'] = $row_jazdy['data_rozpoczecia'];
        $today[$a]['id'] = $c;
        $today[$a]['what'] = 2;
        $a++;
        $c++;
    }
    
    
    
    
    $zapytanie_egzamin = 'SELECT egzamin.id, egzamin.data, uczniowie.imie, uczniowie.nazwisko FROM uczniowie, egzamin WHERE egzamin.uczen=uczniowie.id AND uczniowie.id=' . $this->id . ' AND date(egzamin.data)="' . $date . '" ORDER BY egzamin.data';
    $result_egzamin = $mysql->query($zapytanie_egzamin);
    $c = 0;
    while ($row_egzamin = $result_egzamin->fetch_array())
    {
        $egzamin[$c]['id'] = $row_egzamin['id'];
        $egzamin[$c]['data'] = $row_egzamin['data'];
        $egzamin[$c]['uczen'] = $row_egzamin['imie'] . ' ' . $row_egzamin['nazwisko'];      
        $today[$a]['data'] = $row_egzamin['data'];
        $today[$a]['id'] = $c;
        $today[$a]['what'] = 3;
        $a++;
        $c++;
    }
    
    
    
    if ($a > 0)
    {
    usort($today, function($a1, $a2) 
    {
        $v1 = strtotime($a1['data']);
        $v2 = strtotime($a2['data']);
        return $v1 - $v2; // $v2 - $v1 to reverse direction
    });  
    include ('../script/month.php');
    $dzien = date("d", strtotime($date));        
    $dzien_tygodnia = $dzien_tyg[date("N", strtotime($date))];        
    $miesiac = $month[date("n", strtotime($date))];        
    $rok = date("Y", strtotime($date));  
    $current_data = $dzien . ' ' . $miesiac . ' ' . $rok; 
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 day">';
    echo '<h4>' . $current_data . ' (' . $dzien_tygodnia . ') </h4>';
    echo '<table class="table col-lg-11 col-md-11 col-sm-11 col-xs-11 col-centered">';
    for ($v = 0;$v<count($today);$v++)
    {
        echo '<tr>';
        if (($v!=0) and ($today[$v]['data'] == $today[$v-1]['data']))
        {
            echo '<td class="text-center"> </td>';
        } else
        {
            $hour = date("H:i", strtotime($today[$v]['data'])); 
            echo '<td class="text-center">' . $hour . '</td>';
            
        }
        
            echo '<td>';
            switch ($today[$v]['what'])
            {
                case 1:
                    {
                    $id = $today[$v]['id'];
                    echo '<a href="../page/temat-wyklad.php?id=' . $wyklad[$id]['temat'] . '">';
                    echo 'Wykład, prowadzi: ' . $wyklad[$id]['wykladowca'];
                
                    echo '</a>';
                    break;
                    }
                case 2:
                    {
                    $id = $today[$v]['id'];
                    echo '<a href="../page/instruktor.php?id=' . $jazdy[$id]['id'] . '">';
                    //echo 'Jazdy - ' . $jazdy[$id]['koniec'];
                    echo ' - ' . $jazdy[$id]['koniec'] . ' - Jazdy z ' . $jazdy[$id]['instruktor'];
                    echo '</a>';
                    break;
                    }
                case 3:
                    {
                    $id = $today[$v]['id'];
                    echo '<a href="../page/egzamin.php?id=' . $egzamin[$id]['id'] . '">';
                    echo 'Egzamin - ' . $egzamin[$id]['uczen'];
                
                    echo '</a>';
                    break;
                    }
            }
        echo '</td>';
        echo '<tr>';
     
    }    
    echo '</table>';
    echo '</div>';
        return $a;
    } else
    {
        return 0;
    }
    
     
    
}    
     
public function modern_ui ()
{
    
    /*
    [][0] - klasa ikony
    [][1] - podpis
    [][2] - strona
    [][3] - kolor
    */
    
    $cube[0][0] = 'people';
    $cube[0][1] = 'Profil';
    $cube[0][2] = '../page/uczen.php'; 
    $cube[0][3] = '#5fafe4'; 
    
    $cube[1][0] = 'work';
    $cube[1][1] = 'Wykłady';
    $cube[1][2] = '../page/wyklady.php';  
    $cube[1][3] = '#6eb4e5';  
    
    $cube[2][0] = 'directions';
    $cube[2][1] = 'Jazdy';
    $cube[2][2] = '../page/jazdy.php';  
    $cube[2][3] = '#7dbde9';  
    
    $cube[3][0] = 'domain';
    $cube[3][1] = 'Egzamin';
    $cube[3][2] = '#';    
    $cube[3][3] = '#8cc3ea';    
    
    $cube[4][0] = 'input';
    $cube[4][1] = 'Wyloguj';
    $cube[4][2] = '../page/logout.php';  
    $cube[4][3] = '#99cbee';  
  
    echo '<div id="cube">';
    for ($x=0; $x<=4;$x++)
    {             
        echo '<a href="' . $cube[$x][2] . '">';
            echo '<div class="ikona" style="background-color: ' . $cube[$x][3] . '">';    
                echo '<i class="material-icons">' . $cube[$x][0] . '</i>';
                echo '<span>' . $cube[$x][1] . '</span>';
            echo '</div>';
        echo '</a>';
    }
    echo '</div>';
}
    
public function show_profil_uczen()
{
    
include ('../script/month.php');    
        
$mysql = connect_mysql();
$zapytanie = 'SELECT uczniowie.imie, uczniowie.nazwisko, uczniowie.data_urodzenia,uczniowie.instruktor, uczniowie.cena,uczniowie.jazdy, uczniowie.stan, kategoria_prawo_jazdy.skrot FROM uczniowie, kategoria_prawo_jazdy WHERE kategoria_prawo_jazdy.id=uczniowie.kategoria AND uczniowie.id=' . $this->id;   
$result = $mysql->query($zapytanie);    
    
if ($result->num_rows == 1)
{
$row = $result->fetch_array();    
    
echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-centered profile">';
    echo '<div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 profile_info">';
        echo '<div class="col-lg-7 col-md-5 col-sm-5 col-xs-12 profile_name" >' . $row['imie'] . ' ' . $row['nazwisko'] . '</div>';
//        echo '<div class="col-lg-3 col-md-4 col-sm-4 col-xs-7 profile_status" >';
//            echo '<img src="../img/stopien' . $row['stan'] . '.png" width="100%" height="100%">';
//        echo '</div>';
        echo '<a href="../page/uczen-all.php">';
            echo '<div class="col-lg-2 col-lg-offset-3 col-md-3 col-md-offset-4 col-sm-3 col-sm-offset-4 col-xs-5 profile_button" > Szczegóły </div>';
        echo '</a>';
        $date1 = $row['data_urodzenia'];
        $date2 = date('Y-m-d');
        $diff = abs(strtotime($date2) - strtotime($date1));
        $wiek = floor($diff / (365*60*60*24));
        echo '<div class="col-lg-12 col-md-12 col-xs-12 profile_details"> Status: ' . $etap[$row['stan']] . '</div>';
        echo '<div class="col-lg-12 col-md-12 col-xs-12 profile_details"> Wiek: ' . $wiek . ' lat </div>';
        $zapytanie_instruktor = 'SELECT id,imie, nazwisko FROM instruktorzy WHERE id=' . $row['instruktor'];
        $result_instruktor = $mysql->query($zapytanie_instruktor);
        if ($result_instruktor->num_rows == 0)
        {
            $instruktor = 'Nie wybrano';
        } else
        {
            $row_instruktor = $result_instruktor->fetch_array();
            $instruktor = '<a href="../page/instruktor.php?id=' . $row_instruktor['id'] . '">' . $row_instruktor ['imie'] . ' ' . $row_instruktor['nazwisko'] . '</a>'; 
        }
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_details"> Instruktor: ' . $instruktor . ' </div>';
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_details"> Kategoria: ' . $row['skrot'] . '</div>';
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_achievements">';
            echo 'W budowie';
        echo '</div>';
    echo '</div>';
    echo '<div class="col-lg-3 col-md-4 col-sm-4 hidden-xs profile_photo square">';
        echo '<img src="../img/prifile-pic.png" width="100%" height="100%">';
    echo '</div>';
//    $zapytanie_jazdy = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE stan=2 AND uczen="' . $this->id . '"';
//    $result_jazdy = $mysql->query($zapytanie_jazdy);
//
//    $wynik = 0;
//    while ($row_jazdy = $result_jazdy->fetch_array())
//    {
//        $start = strtotime($row_jazdy['data_rozpoczecia']);
//        $koniec = strtotime($row_jazdy['data_zakonczenia']);
//        $roznica = $koniec-$start;
//        $wynik +=  $roznica;
//
//    }
//
//    $czas = $wynik/3600;
//
//    $zapytanie_wyklady = 'SELECT count(*) AS ile FROM obecnosc WHERE uczen=' . $this->id;
//    $result_wyklady = $mysql->query($zapytanie_wyklady);
//    $row_wyklady = $result_wyklady->fetch_array();
//    $wyklady = $row_wyklady['ile'];
//
//    $zapytanie_jazdy = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE stan=2 AND uczen="' . $this->id . '"';
//    $result_jazdy = $mysql->query($zapytanie_jazdy);
//
//    $wynik = 0;
//    while ($row_jazdy = $result_jazdy->fetch_array())
//    {
//        $start = strtotime($row_jazdy['data_rozpoczecia']);
//        $koniec = strtotime($row_jazdy['data_zakonczenia']);
//        $roznica = $koniec-$start;
//        $wynik +=  $roznica;
//
//    }
//
//    $godzina = ($wynik-(floor($wynik%3600)))/3600;
//    $minuta = ($wynik-($godzina*3600))/60;
//
//    if ($minuta == 0)
//    {
//        $czas = $godzina;  
//    } else
//    {
//        if ($minuta < 10)
//        {
//            $minuta = '0' . $minuta;
//        }
//        $czas = $godzina . ':' . $minuta;
//    }
//    $caly_czas = $row['jazdy'];
//    $caly_koszt= $row['cena'];
//
//
//    $zapytanie_dodatkowe = 'SELECT dodatkowe_jazdy.cena, dodatkowe_jazdy.jazdy FROM dodatkowe_jazdy WHERE  dodatkowe_jazdy.uczen=' . $this->id;
//    $result_dodatkowe = $mysql->query($zapytanie_dodatkowe);
//
//    while ($row_dodatkowe = $result_dodatkowe->fetch_array())
//    {
//        $caly_czas += $row_dodatkowe['jazdy'];
//        $caly_koszt += $row_dodatkowe['cena'];
//    }
//
//    $zapytanie_pieniadze = 'SELECT kasa FROM wplaty WHERE uczen=' . $this->id;
//    $result_pieniadze = $mysql->query($zapytanie_pieniadze);
//    $wplacone_pieniadze =0;
//    while ($row_pieniadze = $result_pieniadze->fetch_array())
//    {
//        $wplacone_pieniadze += $row_pieniadze['kasa'];
//    }
//    
//    echo '<a href="../page/wyklady.php">';
//        echo '<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 profile_telebin">';
//            echo '<i> ' . $wyklady . ' </i>';
//            echo '<span> Wykłady </span>';
//        echo '</div>';
//    echo '</a>';
//    echo '<a href="../page/wplaty.php">';
//        echo '<div class="col-lg-4 col-md-12 col-lg-offset-1 col-sm-12 col-xs-12 profile_telebin">';
//            echo '<i>' . $wplacone_pieniadze . '/' . $caly_koszt . '</i>';
//            echo '<span> Finanse </span>';
//        echo '</div>';
//    echo '</a>';
//    echo '<a href="../page/jazdy.php">';
//        echo '<div class="col-lg-3 col-md-12 col-lg-offset-1 col-sm-12 col-xs-12 profile_telebin">';
//            echo '<i>' . $czas . '/' . $caly_czas . '</i>';
//            echo '<span> Jazdy </span>';
//        echo '</div>';
//    echo '</a>';
//
    echo '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 profile_h3"> Znajomi </div>';
    echo '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 profile_friends">';
        $this->friends($this->id);
    echo '</div>';
echo '</div>';   
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


    

}   
    
function wplata ($id)
{

$mysql = connect_mysql();
    
$zapytanie = 'SELECT instruktorzy.imie AS instruktor_imie, instruktorzy.nazwisko AS instruktor_nazwisko, uczniowie.imie AS uczen_imie, uczniowie.nazwisko AS uczen_nazwisko, wplaty.instruktor, wplaty.kasa, wplaty.data FROM instruktorzy,wplaty,uczniowie WHERE wplaty.instruktor=instruktorzy.id AND uczniowie.id=wplaty.uczen AND wplaty.id=' . $id . ' AND wplaty.uczen=' . $this->id;
    
$result = $mysql->query($zapytanie);
    
if ($result->num_rows == 1)
{
    $row = $result->fetch_array();
    
    include ('../script/month.php');
    $date = $row['data'];
    $data_dzien = date("d", strtotime($date));
    $data_miesiac = $month[date("n", strtotime($date))];
    $data_rok = date("Y", strtotime($date));
    $data_godzina = date("G", strtotime($date));
    $data_minuta = date("i", strtotime($date));
    $data = $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . ' ' . $data_godzina . ':' . $data_minuta;
    $instruktor = $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'];
    $uczen = $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'];
    echo '<table>';
        echo '<tr>';
            echo '<td> Data </td>';
            echo '<td>' . $data . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Instruktor </td>';
            echo '<td><a href="../page/instruktor.php?id=' . $row['instruktor'] . '">' . $instruktor . '</a></td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Uczeń </td>';
            echo '<td><a href="../page/uczen.php">' . $uczen . '</a></td>';
        echo '</tr>';
    
        echo '<tr>';
            echo '<td> Wpłata </td>';
            echo '<td>' . $row['kasa'] . ' PLN</td>';
        echo '</tr>';
    
    echo '</table>';
} else
{
    header ("Location: ../page/dashboard.php");
}
    
    

}
    
function wplata_kafel()
{
    
    $cube[1][0] = '';
    $cube[1][1] = '';
    $cube[1][2] = '#';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = '';
    $cube[2][1] = '';
    $cube[2][2] = '#';
    $cube[2][3] = '#6eb4e5';
    
    $cube[3][0] = '';
    $cube[3][1] = '';
    $cube[3][2] = '#';
    $cube[3][3] = '#7dbde9';
    
    $cube[4][0] = '';
    $cube[4][1] = '';
    $cube[4][2] = '#';
    $cube[4][3] = '#8cc3ea';
    
    
    $cube[5][0] = '';
    $cube[5][1] = '';
    $cube[5][2] = '#';
    $cube[5][3] = '#99cbee';
    
    $cube[6][0] = '';
    $cube[6][1] = '';
    $cube[6][2] = '#';
    $cube[6][3] = '#90CAF2';
    

        for ($x=1;$x<=6;$x++)
    {
        
if ($x%2 == 0)
        {   
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel ostatnikafel" ostatnikafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            echo '</div>';
        } else
        {
            echo '<div class="label">';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';            
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            
        }
    }    
}
    
function friends ($id)
{

$mysql = connect_mysql();    
    
$zapytanie_znajomi = 'SELECT uczniowie.id,uczniowie.imie, uczniowie.nazwisko, uczniowie.stan FROM uczniowie,friends WHERE uczniowie.id=friends.obserwowany AND friends.uczen=' . $id . ' AND friends.obserwowany!=' . $this->id;
    
$result_znajomi = $mysql->query($zapytanie_znajomi);    
    
if ($result_znajomi->num_rows > 0)
{
    while ($row_znajomi = $result_znajomi->fetch_array())
    {
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            echo '<a href="../page/uczen.php?id=' . $row_znajomi['id'] . '">';
                echo $row_znajomi['imie'] . ' ' . $row_znajomi['nazwisko'];
            echo '</a>';
        echo '</div>';    
//        echo '<div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">';
//            echo '<img src="../img/stopien' . $row_znajomi['stan'] .'.png" width="100%" height="25px">';
//        echo '</div>';
//        echo '<div class="col-lg-10 col-md-10 col-sm-9 hidden-xs">';
//                        
//        echo '</div>';
    }
} else
{
    echo '<span> Nie obserwuje nikogo </span>';
}
}
    
public function show_profil_uczen_inny($id)
{
    
        
$mysql = connect_mysql();
$zapytanie = 'SELECT uczniowie.imie, uczniowie.nazwisko, uczniowie.data_urodzenia,uczniowie.instruktor, uczniowie.cena,uczniowie.jazdy, uczniowie.stan, kategoria_prawo_jazdy.skrot FROM uczniowie, kategoria_prawo_jazdy WHERE kategoria_prawo_jazdy.id=uczniowie.kategoria AND uczniowie.id=' . $id;
$result = $mysql->query($zapytanie);    
    
if ($result->num_rows == 1)
{
$row = $result->fetch_array();    
    
echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-centered profile">';
    echo '<div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 profile_info">';
        echo '<div class="col-lg-7 col-md-5 col-sm-5 col-xs-12 profile_name" >' . $row['imie'] . ' ' . $row['nazwisko'] . '</div>';
        echo '<div class="col-lg-3 col-md-4 col-sm-4 col-xs-7 profile_status" >';
            echo '<img src="../img/stopien' . $row['stan'] . '.png" width="100%" height="100%">';
        echo '</div>';
    
        $zapytanie_follow = 'SELECT id FROM friends WHERE uczen=' . $this->id . ' AND obserwowany=' . $id;
        $result_follow = $mysql->query($zapytanie_follow);
        if ($result_follow->num_rows == 0)
        {
            echo '<a href="../script/add_friend.php?id='. $id .'">';
                echo '<div class="col-lg-2 col-md-3 col-sm-3 col-xs-5 profile_button" > Obserwuj </div>';
            echo '</a>';  
        } else
        {
            echo '<a href="../script/delete_friend.php?id='. $id .'">';
                echo '<div class="col-lg-2 col-md-3 col-sm-3 col-xs-5 profile_button" > Nie obserwuj </div>';
            echo '</a>';
        }
        
        
        $date1 = $row['data_urodzenia'];
        $date2 = date('Y-m-d');
        $diff = abs(strtotime($date2) - strtotime($date1));
        $wiek = floor($diff / (365*60*60*24));
        echo '<div class="col-lg-12 col-md-12 col-xs-12 profile_details"> Wiek: ' . $wiek . ' lat </div>';
        $zapytanie_instruktor = 'SELECT id,imie, nazwisko FROM instruktorzy WHERE id=' . $row['instruktor'];
        $result_instruktor = $mysql->query($zapytanie_instruktor);
        if ($result_instruktor->num_rows == 0)
        {
            $instruktor = 'Nie wybrano';
        } else
        {
            $row_instruktor = $result_instruktor->fetch_array();
            $instruktor = '<a href="../page/instruktor.php?id=' . $row_instruktor['id'] . '">' . $row_instruktor ['imie'] . ' ' . $row_instruktor['nazwisko'] . '</a>'; 
        }
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_details"> Instruktor: ' . $instruktor . ' </div>';
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_achievements">';
            echo 'W budowie';
        echo '</div>';
    echo '</div>';
    echo '<div class="col-lg-3 col-md-4 col-sm-4 hidden-xs profile_photo square">';
        echo '<img src="../img/prifile-pic.png" width="100%" height="100%">';
    echo '</div>';

    echo '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 profile_h3"> Znajomi </div>';
    echo '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 profile_friends">';
        $this->friends($id);
    echo '</div>';
echo '</div>';   
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


    

}   
    

    
public function edit_egzamin ($id)
{
    
    
    $mysql = connect_mysql ();
    include ('../script/month.php');

    $zapytanie = 'SELECT egzamin.data, egzamin.stan,egzamin.typ, egzamin.notatka,uczniowie.id, uczniowie.imie, uczniowie.nazwisko FROM uczniowie,egzamin WHERE uczniowie.id=egzamin.uczen AND uczniowie.id=' . $this->id . ' AND egzamin.id=' . $id;
    $result = $mysql->query($zapytanie);
    $row = $result->fetch_array();
    $date = date("Y-m-d", strtotime($row['data']));
    $start_czas = date("H:i", strtotime($row['data']));   


    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Edytuj egzamin </h3>';
    echo '</div>';
    
    echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
            echo '<form class="form-horizontal" method="POST" action="../script/edit_egzamin.php">';   
                echo '<input type="hidden" value="' . $id . '" name="id">';        
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                echo '</div>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Kursant';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="text" disabled class="input-sm form-control" value="' . $row['imie'] . ' ' . $row['nazwisko'] . '">';
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Data';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input name="data" type="date" class="input-sm form-control" value="' . $date . '">';
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Godzina';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="time" name="time" class="input-sm form-control" value="' . $start_czas . '">';
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Stan';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<select name="stan" class="form-control input-sm">';
                                for ($x=1;$x<=count($egzamin_stan);$x++)
                                {
                                    if ($row['stan'] == $x)
                                    {
                                        echo '<option selected value="' . $x . '">' . $egzamin_stan[$x] . '</option>';
                                    } else
                                    {
                                        echo '<option value="' . $x . '">' . $egzamin_stan[$x] . '</option>';
                                    }   
                                }
                            echo '</select>';
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Rodzaj';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<select name="rodzaj" class="form-control input-sm">';
                                for ($x=3;$x<=count($egzamin_typ);$x++)
                                {
                                    if ($row['typ'] == $x)
                                    {
                                        echo '<option selected value="' . $x . '">' . $egzamin_typ[$x] . '</option>';
                                    } else
                                    {
                                        echo '<option value="' . $x . '">' . $egzamin_typ[$x] . '</option>';
                                    }   
                                }
                            echo '</select>';
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                        echo 'Opis egzaminu';
                    echo '</div>';
                    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';   
                        echo '<p style="font-size:13px"> Jak wrażenia? W tym miejscu możesz napisać jak Ci poszło. Co Cię zaskoczyło i z czym miałeś/aś problem. Podziel się z nami gdzie jechaliście, jak poszło parkowanie czy manewr zawracania. Opisz samego egzaminatora i samochód. </p>';
                        echo '<textarea name="notatka" style="width:100%;height:200px">' . $row['notatka'] . ' </textarea>';
                    echo '</div>';
        echo '</div>';
    echo '</div>';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';

    // TYP: BUTTON LUB NORMAL
    // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
    $button[0]['typ'] = 'button';
    $button[0]['cat_icon'] = 'material-icons';
    $button[0]['icon'] = 'done';
    $button[0]['text'] = 'Akceptuj';
    $button[0]['href'] = '#';

    $button[1]['typ'] = 'normal';
    $button[1]['cat_icon'] = 'material-icons';
    $button[1]['icon'] = 'clear';
    $button[1]['text'] = 'Anuluj';
    $button[1]['href'] = '../page/egzamin.php?id=' . $id;

    $this->buttons($button);


    echo '</div>';    
    
}    
     
public function edit_profil_uczen ()
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
$mysql = connect_mysql();    
$zapytanie = 'SELECT uczniowie.pesel,uczniowie.notatka, uczniowie.imie, uczniowie.nazwisko, uczniowie.data_zapisu, uczniowie.plec,uczniowie.miejsce_spotkan, uczniowie.data_urodzenia, uczniowie.telefon, uczniowie.email, uczniowie.stan, uczniowie.pkk, uczniowie.kurs, uczniowie.jazdy, uczniowie.cena, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, kategoria_prawo_jazdy.skrot FROM uczniowie, instruktorzy, kategoria_prawo_jazdy WHERE instruktorzy.id=uczniowie.instruktor AND kategoria_prawo_jazdy.id=uczniowie.kategoria AND uczniowie.id="' . $this->id . '"';
$result = $mysql->query($zapytanie);
    
if ($result->num_rows == 1)
{
$row = $result->fetch_array();    

echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Edytuj ucznia </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/edit-uczen.php">';   
            echo '<input type="hidden" name="id" value="' . $this->id . '">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                echo 'Dane osobowe';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Pesel';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" id="pesel" value="' . $row['pesel'] . '" disabled="disabled" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Imię';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" disabled value="' . $row['imie'] . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Nazwisko';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" disabled value="' . $row['nazwisko'] . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data urodzenia';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" disabled value="' . $row['data_urodzenia'] . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Płeć';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select disabled class="form-control input-sm">';
                            for ($x=1;$x<=count($sex);$x++)
                            {
                                if ($x == $row['plec'])
                                {
                                    echo '<option selected value="' . $x . '">' . $sex[$x] . '</option>'; 
                                } else
                                {
                                    echo '<option value="' . $x . '">' . $sex[$x] . '</option>';
                                }                                
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                    echo 'Dane kontaktowe';
                echo '</div>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Telefon';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="text" id="phone" value="' . $row['telefon'] . '" name="phone" class="form-control input-sm">';
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'E-mail';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="email"  value="' . $row['email'] . '" name="email" class="form-control input-sm">';
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Miejsce spotkań';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="text"  value="' . $row['miejsce_spotkan'] . '" disabled class="form-control input-sm">';
                        echo '</div>';
                    echo '</div>'; 
                echo '</div>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                    echo 'Kurs';
                echo '</div>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Numer PKK';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="text" id="pkk" value="' . $row['pkk'] . '" disabled class="form-control input-sm">';
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Data zapisu';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="date" value="' . $row['data_zapisu'] . '" disabled class="form-control input-sm">';
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Etap';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<select disabled class="input-sm form-control">';
                                for ($a=1;$a<=count($etap);$a++)
                                {
                                    if ($a == $row['stan'])
                                    {
                                        echo '<option selected="selected" value="' . $a . '">' . $etap[$a] . '</option>';  
                                    } else
                                    {
                                        echo '<option value="' . $a . '">' . $etap[$a] . '</option>';
                                    }

                                }
                            echo '</select>';
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Kategoria';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            $zapytanie_kategoria = 'SELECT id, skrot FROM kategoria_prawo_jazdy ORDER BY skrot';
                            $result_kategoria = $mysql->query($zapytanie_kategoria);
                            echo '<select disabled class="input-sm form-control">';
                                while ($row_kategoria = $result_kategoria->fetch_array())
                                {
                                    if ($row_kategoria['id'] == $row['kategoria'])
                                    {
                                        echo '<option selected="selected" value="' . $row_kategoria['id'] . '">' . $row_kategoria['skrot'] . '</option>';
                                    } else
                                    {
                                        echo '<option value="' . $row_kategoria['id'] . '">' . $row_kategoria['skrot'] . '</option>';  
                                    }     
                                }
                            echo '</select>';
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Kurs';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            $zapytanie_kurs = 'SELECT id, nazwa, cena, jazdy FROM kursy ORDER BY cena';
                            $result_kurs = $mysql->query($zapytanie_kurs);

                            while ($row_kurs =  $result_kurs->fetch_array())
                            {
                                echo '<input type="hidden" id="jazda-' . $row_kurs['id'] . '" value="' . $row_kurs['jazdy'] . '">';
                                echo '<input type="hidden" id="cena-' . $row_kurs['id'] . '" value="' . $row_kurs['cena'] . '">';

                            }

                                echo '<input type="hidden" id="jazda-0" value="' . $row['jazdy'] . '">';
                                echo '<input type="hidden" id="cena-0" value="' . $row['cena'] . '">';

                                echo '<input type="hidden" id="jazda--1" value="' . $row['jazdy'] . '">';
                                echo '<input type="hidden" id="cena--1" value="' . $row['cena'] . '">';


                            mysqli_data_seek( $result_kurs, 0 );

                            echo '<select disabled class="form-control input-sm" onchange="edit_uczen_kurs()" id="kurs">';

                                while ($row_kurs = $result_kurs->fetch_array())
                                {
                                    if (($row_kurs['nazwa'] == $row['kurs']) or ($row['kurs'] == 'Niestandardowy') or ($row['kurs'] == 'Dodatkowy'))
                                    {
                                        echo '<option selected value="' . $row_kurs['id'] . '">' . $row_kurs['nazwa'] . '</option>';
                                    } elseif ($row['kurs'])
                                    {
                                        echo '<option value="' . $row_kurs['id'] . '">' . $row_kurs['nazwa'] . '</option>';
                                    }
                                }

                                if ($row['kurs'] == 'Niestandardowy')
                                    {
                                        echo '<option selected value="0"> Niestandardowy </option>';
                                    } else
                                    {
                                        echo '<option value="0"> Niestandardowy </option>';
                                    }

                                    if ($row['kurs'] == 'Dodatkowy')
                                    {
                                        echo '<option selected value="-1"> Dodatkowy </option>';
                                    } else
                                    {
                                        echo '<option value="-1"> Dodatkowy </option>';
                                    }
                            echo '</select>';
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Godzin jazd';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            if (($row['kurs'] == 'Niestandardowy') or ($row['kurs'] == 'Dodatkowy'))
                            {
                                echo '<td> <input class="input-sm form-control"  disabled type="number" id="jazdy" name="jazdy" value="' . $row['jazdy'] . '"> </td>';  
                            } else
                            {
                                echo '<td> <input class="input-sm form-control" disabled="disabled"  type="number" id="jazdy"  value="' . $row['jazdy'] . '"> </td>';
                            }
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Cena';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            if (($row['kurs'] == 'Niestandardowy') or ($row['kurs'] == 'Dodatkowy'))
                            {
                                echo '<td> <input class="input-sm form-control" type="number" id="cena" disabled value="' . $row['cena'] . '"> </td>';
                            } else
                            {
                                echo '<td> <input class="input-sm form-control" disabled="disabled" type="number" id="cena"  value="' . $row['cena'] . '"> </td>';   
                            }
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Instruktor';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input disabled="disabled" class="input-sm form-control" type="text" value="' . $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'] . '">';
                        echo '</div>';
                    echo '</div>'; 
                echo '</div>';
            echo '</div>';
    echo '</div>';
echo '</div>';
echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';

    // TYP: BUTTON LUB NORMAL
    // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
    $button[0]['typ'] = 'button';
    $button[0]['cat_icon'] = 'material-icons';
    $button[0]['icon'] = 'done';
    $button[0]['text'] = 'Akceptuj';
    $button[0]['href'] = '#';

    $button[1]['typ'] = 'normal';
    $button[1]['cat_icon'] = 'material-icons';
    $button[1]['icon'] = 'clear';
    $button[1]['text'] = 'Anuluj';
    $button[1]['href'] = '../page/uczen-all.php';

    $this->buttons($button);


echo '</div>';       

} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    



}
    
public function show_profil_uczen_all()
{
    include ('../script/month.php');
    $mysql = connect_mysql();
    $zapytanie = 'SELECT uczniowie.pesel,uczniowie.notatka, uczniowie.pkk, uczniowie.imie, uczniowie.nazwisko, uczniowie.plec, uczniowie.data_urodzenia,uczniowie.cena, uczniowie.jazdy, uczniowie.telefon,uczniowie.data_zapisu, uczniowie.email,uczniowie.stan,uczniowie.data_zapisu, uczniowie.kurs, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, uczniowie.miejsce_spotkan, kategoria_prawo_jazdy.skrot FROM uczniowie, instruktorzy, kategoria_prawo_jazdy WHERE kategoria_prawo_jazdy.id=uczniowie.kategoria AND uczniowie.instruktor=instruktorzy.id  AND uczniowie.id=' . $this->id;
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        
        // TYTUŁ    
        echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
            echo '<h3> Uczeń - ' . $row['imie'] . ' ' . $row['nazwisko'] . '</h3>';
        echo '</div>';

        echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
            // KARTKA
            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';    
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                    echo 'Dane osobowe';
                echo '</div>';
                echo '<table class="table">';
                    echo '<tr>';
                        echo '<td> Pesel </td>';
                        echo '<td>' . $row['pesel'] . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Imię </td>';
                        echo '<td>' . $row['imie'] . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Nazwisko </td>';
                        echo '<td>' . $row['nazwisko'] . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Data urodzenia </td>';
                        $data_dzien = date("j", strtotime($row['data_urodzenia']));
                        $data_miesiac = $month[date("n", strtotime($row['data_urodzenia']))];
                        $data_rok = date("Y", strtotime($row['data_urodzenia']));
                        echo '<td>' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Płeć </td>';
                        echo '<td>' . $sex[$row['plec']] . '</td>';
                    echo '</tr>';
                echo '</table>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                    echo 'Dane kontaktowe';
                echo '</div>';
                echo '<table class="table">';
                    echo '<tr>';
                        echo '<td> Telefon </td>';
                        $telefon  = $row['telefon'];
                        $t1 = substr($telefon,0,3);
                        $t2 = substr($telefon,3,3);
                        $t3 = substr($telefon,6,3);
                        echo '<td>' . $t1 . ' ' . $t2 . ' ' . $t3 . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> E-mail </td>';
                        echo '<td>' . $row['email'] . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Miejsce spotkań </td>';
                        echo '<td>' . $row['miejsce_spotkan'] . '</td>';
                    echo '</tr>'; 
                echo '</table>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                    echo 'Kurs';
                echo '</div>';
                echo '<table class="table">';
                    echo '<tr>';
                        echo '<td> Numer PKK </td>';
                        $pkk = $row['pkk'];
                        $t1 = substr($pkk,0,5);
                        $t2 = substr($pkk,5,5);
                        $t3 = substr($pkk,10,5);
                        $t4 = substr($pkk,15,5);
                        echo '<td>' . $t1 . ' ' . $t2 . ' ' . $t3 . ' ' . $t4 . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Data zapisu </td>';
                        $data_dzien = date("j", strtotime($row['data_zapisu']));
                        $data_miesiac = $month[date("n", strtotime($row['data_zapisu']))];
                        $data_rok = date("Y", strtotime($row['data_zapisu']));
                        echo '<td>' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Etap </td>';
                        echo '<td>' . $etap[$row['stan']] . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Kategoria </td>';
                        echo '<td>' . $row['skrot'] . '</td>';
                    echo '</tr>'; 
                    echo '<tr>';
                        echo '<td> Kurs </td>';
                        echo '<td>' . $row['kurs'] . '</td>';
                    echo '</tr>'; 
                    echo '<tr>';
                        echo '<td> Godzin jazd </td>';
                        echo '<td>' . $row['jazdy'] . ' godzin' . '</td>';
                    echo '</tr>'; 
                    echo '<tr>';
                        echo '<td> Cena </td>';
                        echo '<td>' . $row['cena'] . '</td>';
                    echo '</tr>'; 
                    echo '<tr>';
                        echo '<td> Instruktor </td>';
                        echo '<td>' . $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'] . '</td>';
                    echo '</tr>'; 
                echo '</table>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                    echo 'Ustalenia przy zapisie';
                echo '</div>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    echo '<p>' . $row['notatka'] . '</p>';
                echo '</div>';
                // PRZYCISKI
            echo '</div>';
            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';

                // TYP: BUTTON LUB NORMAL
                // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
                $button[0]['typ'] = 'normal';
                $button[0]['cat_icon'] = 'material-icons';
                $button[0]['icon'] = 'mode_edit';
                $button[0]['text'] = 'Edytuj';
                $button[0]['href'] = '../page/edit-uczen.php';

                $button[1]['typ'] = 'clear';
                $button[1]['cat_icon'] = '';
                $button[1]['icon'] = '';
                $button[1]['text'] = '';
                $button[1]['href'] = '';

                $this->buttons($button);
            echo '</div>';
        echo '</div>'; 
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
}    
    
public function edit_profil_uczen_kafel ()
{

    /*
    [][0] - klasa ikony
    [][1] - podpis
    [][2] - strona
    [][3] - kolor
    */    
    
    $cube[1][0] = 'done';
    $cube[1][1] = 'Akceptuj';
    $cube[1][2] = '../page/edit-uczen.php';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = 'clear';
    $cube[2][1] = 'Anuluj';
    $cube[2][2] = '../page/uczen.php';
    $cube[2][3] = '#6eb4e5';
    
    $cube[3][0] = '';
    $cube[3][1] = '';
    $cube[3][2] = '#';
    $cube[3][3] = '#7dbde9';
    
    $cube[4][0] = '';
    $cube[4][1] = '';
    $cube[4][2] = '#';
    $cube[4][3] = '#8cc3ea';
    
    
    $cube[5][0] = '';
    $cube[5][1] = '';
    $cube[5][2] = '#';
    $cube[5][3] = '#99cbee';
    
    $cube[6][0] = '';
    $cube[6][1] = '';
    $cube[6][2] = '#';
    $cube[6][3] = '#90CAF2';
    

    for ($x=1;$x<=6;$x++)
    {
        
        if ($x == 1)
        {
            echo '<div class="label">';
            echo '<div class="kafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<input type="submit" class="submit-kafel">';    
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';   
            echo '</div>';
        } elseif ($x%2 == 0)
        {   
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel ostatnikafel" ostatnikafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            echo '</div>';
        } else
        {
            echo '<div class="label">';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';            
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            
        }
    }    
    
}
       
public function PokazWszystkieJazdy()
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 title">';
        echo '<h3> Moje jazdy </h3>';
    echo '</div>';
    
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia, jazdy.miejsce_spotkania, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, instruktorzy.imie, instruktorzy.nazwisko FROM jazdy, uczniowie, instruktorzy WHERE jazdy.uczen=uczniowie.id AND  instruktorzy.id=jazdy.instruktor AND uczniowie.pesel="' . $this->pesel . '" AND jazdy.stan=2';
        $result= $mysql->query($zapytanie);
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>  </th>';         
                        echo '<th> Data </th>';                            
                        echo '<th>  </th>';                            
                        echo '<th data-breakpoints="xs sm"> Czas trwania </th>';         
                        echo '<th data-breakpoints="xs sm"> Instruktor </th>';
//                        echo '<th data-breakpoints="xs sm"> Miejsce spotkania </th>';
//                        echo '<th data-breakpoints="xs sm"> Samochód </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    $x = $result->num_rows;
                    $wynik = 0;
                    while ($row = $result->fetch_array())
                    {
                    echo '<tr>';
                        $dzien  = date("d", strtotime($row['data_rozpoczecia'])) . ' '  . $month[date("n", strtotime($row['data_rozpoczecia']))] . ' ' . date("Y", strtotime($row['data_rozpoczecia']));
                        $czas = date("H", strtotime($row['data_rozpoczecia'])) . ':' . date("i", strtotime($row['data_rozpoczecia'])) . ' - ' . date("H", strtotime($row['data_zakonczenia'])) . ':' . date("i", strtotime($row['data_zakonczenia']));                
                     
                        echo '<td data-value="' . $x . '">';                         
                            echo $x;
                            $x--;
                        echo '</td>';
                        echo '<td>';
                            echo $dzien;
                        echo '</td>';
                        echo '<td>';
                            echo $czas;
                        echo '</td>';
                        echo '<td>';
                            $diff = abs(strtotime($row['data_zakonczenia']) - strtotime($row['data_rozpoczecia']));
                            $mod = $diff%3600;
                            $wynik += $diff;
                            $hour = ($diff-$mod)/3600;
                            $min = $mod/60;

                            if ($min < 10)
                            {
                                $min = '0' . $min;
                            }

                            echo $hour . ':' . $min . 'h';
                        echo '</td>';
                        echo '<td>';
                            echo $row['imie'] . ' ' . $row['nazwisko'];
                        echo '</td>';
//                        echo '<td>';
//                            echo $row['miejsce_spotkania'];
//                        echo '</td>';
//                        echo '<td>';
//                            $samochod_id = $row['samochod'];
//                            if ($samochod_id == 0)
//                            {
//                                $samochod = 'Nie wybrano';
//                             } else
//                            {
//                            $zapytanie_samochod = 'SELECT rejestracja FROM samochody WHERE id=' . $samochod_id;
//                            $result_samochod = $mysql->query($zapytanie_samochod);
//                            $row_samochod = $result_samochod->fetch_array();
//                            $samochod = $row_samochod['rejestracja'];
//                            }
//                            echo $samochod;
//                        echo '</td>';
                        echo '<td> <a href="../page/jazda.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                    echo '</tr>';    
                    }
                echo '</tbody>';
                echo '<tfoot>';
                    echo '<tr>';
                        $mod =  $wynik%3600;
                        $hour = ($wynik-$mod)/3600;
                        $min = $mod/60;
                    
                        $zapytanie_uczen = 'SELECT jazdy FROM uczniowie WHERE id=' . $this->id;
                        $result_uczen = $mysql->query($zapytanie_uczen);
                        $row_uczen = $result_uczen->fetch_array();
                        $all = $row_uczen['jazdy'];
                        $zapytanie_dodatkowe = 'SELECT jazdy FROM dodatkowe_jazdy WHERE uczen=' . $this->id;
                        $result_dodatkowe = $mysql->query($zapytanie_dodatkowe);
            
                        while ($row_dodatkowe = $result_dodatkowe->fetch_array())
                        {
                            $all += $row_dodatkowe['jazdy'];
                        }

                        $sall = $all*3600; 
                        $diff = $sall-$wynik;
                        $all_mod =  $diff%3600;
                        $all_hour = ($diff-$all_mod)/3600;
                        $all_min = $all_mod/60;
            
                        if ($min < 10)
                        {
                            $min = '0' . $min;
                        }
                        if ($this->plec == 2)
                        {
                            $byles =  'byłaś';
                        } else
                        {
                            $byles = 'byłeś';                        
                        }
                        echo '<td colspan="7"> Do tej pory ' . $byles . ' na <b> ' . $result->num_rows . ' </b> jazdach. Mamy za sobą <b> ' . $hour . ':' . $min . ' </b> godzin. Do końca pozostało nam <b> ' . $all_hour . ' godzin i  ' . $all_min . ' minut </b> . </td>';
                    echo '</tr>';
                echo '</tfoot>';
            echo '</table>';
        } else
        {
            echo '<span> Nie masz żadnych jazd </span>';
        }
    
    echo '</div>';
    
    
    
    
    
    
    
    
}
  
public function PokazWszystkieOdwolaneJazdy()
{
    $mysql = connect_mysql(); 
    
    $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, instruktorzy.imie, instruktorzy.nazwisko FROM jazdy, uczniowie, instruktorzy WHERE jazdy.uczen=uczniowie.id AND  instruktorzy.id=jazdy.instruktor AND uczniowie.pesel="' . $this->pesel . '" AND (jazdy.stan=3 OR jazdy.stan=4)';
    $samochod = 'Nie wybrano';
    $result= $mysql->query($zapytanie);
    
    if ($result->num_rows > 0)
    {
        echo '<table>';
            echo '<thead>';
                echo '<tr>';
                    echo '<td> Data </td>';
                    echo '<td> Samochód </td>';
                    echo '<td> Instruktor </td>';
                    echo '<td> Stan </td>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                while ($row = $result->fetch_array())
                {
                echo '<tr style="cursor:default">';
                include ('../script/month.php');
                $start = $row['data_rozpoczecia'];
                $koniec = $row['data_zakonczenia'];
                $start_dzien = date("d", strtotime($start));
                $start_miesiac = $month[date("n", strtotime($start))];
                $start_rok = date("Y", strtotime($start));
                $start_godzina = date("G", strtotime($start));
                $start_minuta = date("i", strtotime($start));

                $koniec_dzien = date("d", strtotime($koniec));
                $koniec_miesiac = $month[date("n", strtotime($koniec))];
                $koniec_rok = date("Y", strtotime($koniec));
                $koniec_godzina = date("G", strtotime($koniec));
                $koniec_minuta = date("i", strtotime($koniec));
                    
                    echo '<td>';
                        echo $start_dzien . ' ' . $start_miesiac . ' ' . $start_rok . ' ' . $start_godzina . ':' . $start_minuta . ' - ' . $koniec_godzina . ':' . $koniec_minuta;
                    echo '</td>';
                    echo '<td>';
                        $samochod_id = $row['samochod'];
                        if ($samochod_id == 0)
                        {
                            $samochod = 'Nie wybrano';
                         } else
                        {
                        $zapytanie_samochod = 'SELECT rejestracja FROM samochody WHERE id=' . $samochod_id;
                        $result_samochod = $mysql->query($zapytanie_samochod);
                        $row_samochod = $result_samochod->fetch_array();
                        $samochod = $row_samochod['rejestracja'];
                        }
                        echo $samochod;
                    echo '</td>';
                    echo '<td>';
                        echo $row['imie'] . ' ' . $row['nazwisko'];
                    echo '</td>';
                    echo '<td>';
                        switch ($row['stan'])
                        {
                            case 1:
                                {
                                    $stan = 'Zaplanowane';
                                    break;
                                }
                            case 2:
                                {
                                    $stan = 'Odbyły się';
                                    break;
                                }  
                            case 3:
                                {
                                    $stan = 'Odwołane przez ucznia';
                                    break;
                                }
                            case 4:
                                {
                                    $stan = 'Odwołane prez instruktora';
                                    break;
                                }
                        }
                        echo $stan;
                    echo '</td>';
                echo '</tr>';    
                }
            echo '</tbody>';
        echo '</table>';
    } else
    {
        echo '<span> Nie masz żadnych jazd </span>';
    }
}    
 
public function PokazWszystkieJazdyUcznia()
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 title">';
        echo '<h3> Wszystkie jazdy </h3>';
    echo '</div>';
    
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, instruktorzy.imie, instruktorzy.nazwisko, jazdy.miejsce_spotkania FROM jazdy, instruktorzy WHERE instruktorzy.id=jazdy.instruktor AND jazdy.uczen=' . $this->id . ' ORDER BY jazdy.data_rozpoczecia DESC';
        $result= $mysql->query($zapytanie);
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th>  </th>';
                        echo '<th data-breakpoints="xs sm"> Czas trwania  </th>';
//                        echo '<th data-breakpoints="xs sm"> Miejsce spotkania  </th>';
                        echo '<th data-breakpoints="xs sm"> Instruktor </th>';
                        echo '<th data-breakpoints="xs sm"> Stan </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    $x = $result->num_rows;
                    $wynik = 0;
                    $ile = 0;
                        $anulowano = 0;                        

                    while ($row = $result->fetch_array())
                    {              
                        if (($row['stan'] == 3) or ($row['stan'] ==4))
                        {
                           echo '<tr class="danger">'; 
                        } elseif ($row['stan'] == 1)
                        {
                            echo '<tr class="success">';
                        } elseif ($row['stan'] == 5)
                        {
                            echo '<tr class="info">';
                        } else
                        {
                            echo '<tr>';
                        }
                                $dzien  = date("d", strtotime($row['data_rozpoczecia'])) . ' '  . $month[date("n", strtotime($row['data_rozpoczecia']))] . ' ' . date("Y", strtotime($row['data_rozpoczecia']));
                        
                                $czas = date("H", strtotime($row['data_rozpoczecia'])) . ':' . date("i", strtotime($row['data_rozpoczecia'])) . ' - ' . date("H", strtotime($row['data_zakonczenia'])) . ':' . date("i", strtotime($row['data_zakonczenia']));                


                                echo '<td>';
                                    echo $dzien;
                                echo '</td>';
                                echo '<td>';
                                    echo $czas;
                                echo '</td>';
                                echo '<td>';
                                    $diff = abs(strtotime($row['data_zakonczenia']) - strtotime($row['data_rozpoczecia']));
                                    $mod = $diff%3600;
                                    
                                    if (($row['stan'] == 2) or ($row['stan'] == 1))
                                    {
                                        $ile++;
                                        $wynik += $diff;
                                    } else
                                    {
                                        $anulowano++;
                                    }
                                    
                                    $hour = ($diff-$mod)/3600;
                                    $min = $mod/60;

                                    if ($min < 10)
                                    {
                                        $min = '0' . $min;
                                    }

                                    echo $hour . ':' . $min . 'h';
                                echo '</td>';
//                                echo '<td>';
//                                    echo $row['miejsce_spotkania'];
//                                echo '</td>';
                                echo '<td>';
                                    echo $row['imie'] . ' ' . $row['nazwisko'];
                                echo '</td>';

                                echo '<td>';
                                    echo $etap_jazdy[$row['stan']];
                                echo '</td>';
                                echo '<td> <a href="../page/jazda.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';    
                    }
                echo '</tbody>';
                echo '<tfoot>';
                    echo '<tr>';
                        $mod =  $wynik%3600;
                        $hour = ($wynik-$mod)/3600;
                        $min = $mod/60;
                        
                        $zapytanie_uczen = 'SELECT jazdy FROM uczniowie WHERE id=' . $this->id;
                        $result_uczen = $mysql->query($zapytanie_uczen);
                        $row_uczen = $result_uczen->fetch_array();
                        $all = $row_uczen['jazdy'];
                        $zapytanie_dodatkowe = 'SELECT jazdy FROM dodatkowe_jazdy WHERE uczen=' . $this->id;
                        $result_dodatkowe = $mysql->query($zapytanie_dodatkowe);
            
                        while ($row_dodatkowe = $result_dodatkowe->fetch_array())
                        {
                            $all += $row_dodatkowe['jazdy'];
                        }

                        $sall = $all*3600; 
                        $diff = $sall-$wynik;
                        $all_mod =  $diff%3600;
                        $all_hour = ($diff-$all_mod)/3600;
                        $all_min = $all_mod/60;
            
                        if ($min < 10)
                        {
                            $min = '0' . $min;
                        }
                        if ($this->plec == 2)
                        {
                            $byles =  'byłaś';
                        } else
                        {
                            $byles = 'byłeś';                        
                        }
                        echo '<td colspan="7"> W sumie rozpisano <b> ' . $hour . ':' . $min . ' </b> godzin. Do końca pozostanie nam <b> ' . $all_hour . ' godzin i  ' . $all_min . ' minut </b>. Anulowano ' . $anulowano . ' spotkań </td>';
            
            
                    echo '</tr>';
                echo '</tfoot>';
                echo '</table>';
        } else
        {
            echo '<span> Nie zaplanowano żadnych jazd </span>';
        }
    echo '</div>';
    

}        
    
public function PokazWszystkieNajblizszeJazdy()
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 title">';
        echo '<h3> Najbliższe jazdy </h3>';
    echo '</div>';
    
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia,jazdy.miejsce_spotkania, jazdy.data_zakonczenia, jazdy.stan, jazdy.samochod, instruktorzy.imie, instruktorzy.nazwisko FROM jazdy, uczniowie, instruktorzy WHERE jazdy.uczen=uczniowie.id AND instruktorzy.id=jazdy.instruktor AND jazdy.stan=1 AND uczniowie.id="' . $this->id . '"';
        $samochod = 'Nie wybrano';
        $result= $mysql->query($zapytanie);
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th colspan="2"> Data </th>';
                        echo '<th> Czas trwania  </th>';
                        echo '<th> Instruktor </th>';
                        echo '<th data-breakpoints="xs smd md"> Miejsce spotkania </th>';
//                        echo '<th data-breakpoints="xs sm md"> Samochód </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>'; 
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            $dzien  = date("d", strtotime($row['data_rozpoczecia'])) . ' '  . $month[date("n", strtotime($row['data_rozpoczecia']))] . ' ' . date("Y", strtotime($row['data_rozpoczecia']));
                        
                            $czas = date("H", strtotime($row['data_rozpoczecia'])) . ':' . date("i", strtotime($row['data_rozpoczecia'])) . ' - ' . date("H", strtotime($row['data_zakonczenia'])) . ':' . date("i", strtotime($row['data_zakonczenia']));                  
                            echo '<td>';
                                echo $dzien;
                            echo '</td>';
                            echo '<td>';
                                echo $czas;
                            echo '</td>';
                            echo '<td>';
                                $diff = abs(strtotime($row['data_zakonczenia']) - strtotime($row['data_rozpoczecia']));
                                $mod = $diff%3600;

                                $hour = ($diff-$mod)/3600;
                                $min = $mod/60;

                                if ($min < 10)
                                {
                                    $min = '0' . $min;
                                }

                                echo $hour . ':' . $min . 'h';
                            echo '</td>';
                            echo '<td>';
                                echo $row['imie'] . ' ' . $row['nazwisko'];
                            echo '</td>';
                            echo '<td>';
                                echo $row['miejsce_spotkania'];
                            echo '</td>';
//                            echo '<td>';
//                                $zapytanie_samochod = 'SELECT samochody.nazwa FROM samochody WHERE samochody.id=' . $row['samochod'];
//                                $result_samochod = $mysql->query($zapytanie_samochod);
//                        
//                                if ($result_samochod->num_rows == 1)
//                                {
//                                    $row_samochod = $result_samochod->fetch_array();
//                                    echo $rwo_samochod['nazwa'];
//                                } else
//                                {
//                                    echo 'Nie wybrano';
//                                }
//                        
//                            echo '</td>';
                            echo '<td> <a href="../page/jazda.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';    
                    }
                echo '</tbody>';
        echo '</table>';
        } else
        {
            echo '<span> Nie zaplanowano żadnych jazd </span>';
        }
    echo '</div>';
    
    
    
    
                
}    
    
    
public function jazda ($id)
{
    
    
$mysql = connect_mysql(); 
include ('../script/month.php');    

    
// TYTUŁ    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Jazda </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    // KARTKA
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';    

        $zapytanie = 'SELECT jazdy.notatka,jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia, jazdy.stan, uczniowie.imie, uczniowie.nazwisko,uczniowie.id as uczen_id, instruktorzy.id as instruktor_id, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, jazdy.samochod, jazdy.miejsce_spotkania FROM jazdy, uczniowie, instruktorzy WHERE jazdy.uczen=uczniowie.id AND  instruktorzy.id=jazdy.instruktor AND jazdy.id=' . $id . ' AND uczniowie.pesel="' . $this->pesel . '"';
        $result = $mysql->query($zapytanie);
        if ($result->num_rows == 0)
        {
            header ("Location: ../page/dashboard.php");
            exit();
        }
    
        $row = $result->fetch_array();
        echo '<table class="table">';
            echo '<tr>';
                echo '<td> Data </td>';
                echo '<td>';
                    $data = date("j", strtotime($row['data_rozpoczecia'])) . ' ' . $month[date("n", strtotime($row['data_rozpoczecia']))] . ' ' . date("Y", strtotime($row['data_rozpoczecia']));
                    $czas = date("G", strtotime($row['data_rozpoczecia'])) . ':' . date("i", strtotime($row['data_rozpoczecia'])) . ' - ' .  date("G", strtotime($row['data_zakonczenia'])) . ':' . date("i", strtotime($row['data_zakonczenia']));
                    $diff = abs(strtotime($row['data_zakonczenia']) - strtotime($row['data_rozpoczecia']));
                    $mod = $diff%3600;
                    $hour = ($diff-$mod)/3600;
                    $min = $mod/60;

                    if ($min < 10)
                    {
                        $min = '0' . $min;
                    }
                    $czas_trwania = $hour . ':' . $min;
                    echo $data . ', ' . $czas . ', ' . $czas_trwania . 'h';
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Stan </td>';
                echo '<td>' . $etap_jazdy[$row['stan']] . '</td>';
            echo '</tr>';  
    
            if ($row['stan'] == 3)
            {
                $zapytanie_kara = 'SELECT odwolane_jazdy.cena FROM odwolane_jazdy WHERE jazda=' . $id;
                $result_kara = $mysql->query($zapytanie_kara);
                
                if ($result_kara->num_rows == 1)
                {
                    $row_kara = $result_kara->fetch_array();
                    echo '<tr>';
                        echo '<td> Kara </td>';
                        echo '<td>' . $row_kara['cena'] . 'zł</td>';
                    echo '</tr>';
                }
            }
    
            echo '<tr>';
                echo '<td> Instruktor </td>';
                echo '<td>' . $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'] . '</td>';
            echo '<tr>';
            echo '<tr>';
                echo '<td> Samochód </td>';
                    $samochod_id = $row['samochod'];
                    if ($samochod_id == 0)
                    {
                        $samochod = 'Nie wybrano';
                     } else
                    {
                    $zapytanie_samochod = 'SELECT rejestracja,nazwa FROM samochody WHERE id=' . $samochod_id;
                    $result_samochod = $mysql->query($zapytanie_samochod);
                    $row_samochod = $result_samochod->fetch_array();
                    $samochod = $row_samochod['nazwa'];   
                    }
                echo '<td>' . $samochod . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Miejsce spotkania </td>';
                echo '<td>' . $row['miejsce_spotkania'] . '</td>';
            echo '</tr>';
        echo '</table>';
        echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
            echo '<h3> Opis jazd </h3>';        
        echo '</div>'; 
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            echo '<p>' . $row['notatka'] . '</p>';
        echo '</div>';
    echo '</div>';
    // PRZYCISKI
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';
        
        // TYP: BUTTON LUB NORMAL
        // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
//        $button[0]['typ'] = 'clear';
//        $button[0]['cat_icon'] = '';
//        $button[0]['icon'] = '';
//        $button[0]['text'] = '';
//        $button[0]['href'] = '';
//    
//        
//    
//        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>'; 
    
    
}
       
function showPowiadomienia ()
{
$mysql = connect_mysql(); 

    
$zapytanie = 'SELECT powiadomienia.nadawca, powiadomienia.numer, powiadomienia.id_tresc FROM powiadomienia WHERE powiadomienia.stan=1 AND powiadomienia.odbiorca="' . $this->pesel . '"';
    
$result = $mysql->query($zapytanie);

if ($result->num_rows == 0)
{
    echo '<span> Nie ma żadnych powiadomień </span>';
    return;
} 
        
echo '<table class="table table-hover">';
while ($row = $result->fetch_array())
{
echo '<tr>';
$status = $row['numer'];
$nadawca = $row['nadawca'];  
$tresc = $row['id_tresc'];
switch ($status)
{
    case 1:
        {     
            $zapytanie_instruktor = 'SELECT instruktorzy.imie, instruktorzy.nazwisko FROM instruktorzy WHERE instruktorzy.pesel="' . $nadawca . '"';
            echo '<td>';
            $result_instruktor = $mysql->query($zapytanie_instruktor);
            
            if ($result_instruktor->num_rows == 1)
            {
                $row_instruktor = $result_instruktor->fetch_array();
                echo 'Instruktor ' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'];
            } else
            {
                $zapytanie_admin = 'SELECT administratorzy.imie, administratorzy.nazwisko FROM administratorzy WHERE administratorzy.pesel="' . $nadawca . '"';
                
                $result_admin = $mysql->query($zapytanie_admin);
                $row_admin = $result_admin->fetch_array();
                echo 'Administrator ' . $row_admin['imie'] . ' ' . $row_admin['nazwisko'];
            }
            
            $zapytanie_data = 'SELECT data_rozpoczecia FROM jazdy WHERE id=' . $tresc;
            $result_data = $mysql->query($zapytanie_data);
            $row_data = $result_data->fetch_array();
            $data = $row_data['data_rozpoczecia'];
            include ('../script/month.php');
            $data_dzien = date("d", strtotime($data));
            $data_miesiac = $month[date("n", strtotime($data))];
            $data_rok = date("Y", strtotime($data));
            $data_godzina = date("G", strtotime($data));
            $data_minuta = date("i", strtotime($data));
            echo ' odwołał jazdy w dniu ' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . ' roku o godzinie ' . $data_godzina . ':' . $data_minuta;
            echo '</td>';
            break;
        }
    case 2:
        {
            
            $zapytanie_jazdy = 'SELECT data_rozpoczecia, stan FROM jazdy WHERE id=' . $tresc;
            $result_jazdy = $mysql->query($zapytanie_jazdy);
            $row_jazdy = $result_jazdy->fetch_array();
            $data = $row_jazdy['data_rozpoczecia'];
            include ('../script/month.php');
            $data_dzien = date("d", strtotime($data));
            $data_miesiac = $month[date("n", strtotime($data))];
            $data_rok = date("Y", strtotime($data));
            $data_godzina = date("G", strtotime($data));
            $data_minuta = date("i", strtotime($data));
            
            echo '<td>';
                echo "Dodano jazdy w dniu $data_dzien $data_miesiac $data_rok roku o godzinie $data_godzina:$data_minuta";
            echo '</td>';
            break;
        }
    case 3:
        {
            // DODANO WYKŁAD
            
            $zapytanie_wyklad = 'SELECT data FROM wyklady WHERE id=' . $tresc;
            $result_wyklad = $mysql->query($zapytanie_wyklad);
            $row_wyklad = $result_wyklad->fetch_array();
            $data = $row_wyklad['data'];
            
            include ('../script/month.php');
            $data_dzien = date("d", strtotime($data));
            $data_miesiac = $month[date("n", strtotime($data))];
            $data_rok = date("Y", strtotime($data));
            $data_godzina = date("G", strtotime($data));
            $data_minuta = date("i", strtotime($data));
    
            echo '<td>';
                echo "Dodano wyklad w dniu $data_dzien $data_miesiac $data_rok roku o godzinie $data_godzina:$data_minuta";
            echo '</td>';
            break;
        }
    case 4:
        {    
            $zapytanie_wplata = 'SELECT instruktorzy.imie, instruktorzy.nazwisko, wplaty.kasa, wplaty.data FROM instruktorzy,wplaty WHERE instruktorzy.id=wplaty.instruktor AND wplaty.id=' . $tresc;
            $result_wplata = $mysql->query($zapytanie_wplata);
            $row_wplata = $result_wplata->fetch_array();
            $data = $row_wplata['data'];
            $wplata = $row_wplata['kasa'];
            $imie = $row_wplata['imie'];
            $nazwisko = $row_wplata['nazwisko'];
            include ('../script/month.php');
            $data_dzien = date("d", strtotime($data));
            $data_miesiac = $month[date("n", strtotime($data))];
            $data_rok = date("Y", strtotime($data));

            echo '<td>';
                echo "Instruktor $imie $nazwisko dodał wpłatę w wysokości $wplata zł. w dniu $data_dzien $data_miesiac $data_rok";
            echo '</td>';
            
            break;
        }
    case 5:
        {
            
            $zapytanie_jazdy = 'SELECT pakiety.cena,pakiety.godziny FROM pakiety,dodatkowe_jazdy WHERE dodatkowe_jazdy.id=' . $tresc;
            $result_jazdy = $mysql->query($zapytanie_jazdy);
            if ($result_jazdy->num_rows != 1)
            {
            $row_jazdy = $result_jazdy->fetch_array();
            echo '<td>';
                echo 'Dodano dodatkowe jazdy - ' . $row_jazdy['godziny'] . ' godzina za ' . $row_jazdy['cena'] . 'zł.';
            echo '</td>';
            }
        }
}
echo '</tr>';
}
echo '</table>';
}

public function small_menu ()
{ 
$mysql = connect_mysql();    
echo '<li> <a href="../page/uczen.php"> Mój profil </a> </li>';
$zapytanie_instruktor = 'SELECT instruktor FROM uczniowie WHERE id=' . $this->id;
$result_instruktor = $mysql->query($zapytanie_instruktor);
$row_instruktor = $result_instruktor->fetch_array();    
//echo '<li> <a href="../page/instruktor.php?id=' . $row_instruktor['instruktor'] . '"> Mój instruktor </a> </li>';
//echo '<li> <a href="#"> Moja szkoła </a> </li>';
//echo '<li> <a href="#"> Baza wiedzy </a> </li>';
//echo '<li> <a href="#"> Pomoc </a> </li>';
//echo '<li> <a href="#"> Zgłoś problem </a> </li>';
echo '<li> <a href="../page/settings.php"> Ustawienia </a> </li>';
echo '<li> <a href="../page/logout.php"> Wyloguj </a> </li>';
}    
    
 public function show_profil_instruktor($id)
{
    
if ($id == 0)
{
header ("Location: ../page/dashboard.php");
} else
{
$zapytanie = 'SELECT instruktorzy.pesel, instruktorzy.imie, instruktorzy.nazwisko, instruktorzy.plec, instruktorzy.data_urodzenia, instruktorzy.telefon, instruktorzy.email FROM instruktorzy WHERE instruktorzy.id="' . $id . '"';    
}
$mysql = connect_mysql();    
    
$result = $mysql->query($zapytanie);

if ($result->num_rows != 1) // Jeśli zapytanie zwróci 0 to nie ma takiego ucznia
{
header ("Location: ../page/dashboard.php"); // Przekieruj do strony informującej o błędzie    
} else
{
    $row =  $result->fetch_array(); // Przypisanie do tablicy
    
    include ('../script/month.php');
    echo '<table>';
        echo '<tr>';
            echo '<td> Pesel </td>';
            echo '<td>' . $row['pesel'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Imię </td>';
            echo '<td>' . $row['imie'] . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Nazwisko </td>';
            echo '<td>' . $row['nazwisko'] . '</td>';
        echo '</tr>';
            echo '<td> Płeć </td>';
            echo '<td>' . $sex[$row['plec']] . '</td>';
        echo '</tr>';
        echo '<tr>';
            $telefon  = $row['telefon'];
            $t1 = substr($telefon,0,3);
            $t2 = substr($telefon,3,3);
            $t3 = substr($telefon,6,3);
            echo '<td> Telefon </td>';
            echo '<td>' . $t1 . ' ' . $t2 . ' ' . $t3 . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> E-mail </td>';
            echo '<td>' . $row['email'] . '</td>';
        echo '</tr>';
    echo '</table>';
}
    
}    
    
    
public function show_profil_instruktor_kafel ()
{
    $cube[1][0] = '';
    $cube[1][1] = '';
    $cube[1][2] = '';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = '';
    $cube[2][1] = '';
    $cube[2][2] = '#';
    $cube[2][3] = '#6eb4e5';
    
    $cube[3][0] = '';
    $cube[3][1] = '';
    $cube[3][2] = '#';
    $cube[3][3] = '#7dbde9';
    
    $cube[4][0] = '';
    $cube[4][1] = '';
    $cube[4][2] = '#';
    $cube[4][3] = '#8cc3ea';
    
    
    $cube[5][0] = '';
    $cube[5][1] = '';
    $cube[5][2] = '#';
    $cube[5][3] = '#99cbee';
    
    $cube[6][0] = '';
    $cube[6][1] = '';
    $cube[6][2] = '#';
    $cube[6][3] = '#90CAF2';        


    for ($x=1;$x<=6;$x++)
    {
        if ($x%2 == 0)
        {   
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel ostatnikafel" ostatnikafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="' . $cube[$x][0] . '"> </i>';
                echo '<span>' . $cube[$x][1] . '</span>';
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>'; 
            }   
            echo '</div>';
        } else
        {
            echo '<div class="label">';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="' . $cube[$x][0] . '"> </i>';
                echo '<span>' . $cube[$x][1] . '</span>';            
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>'; 
            }    
        }
    }    
    
}
    
public function PokazWszystkieWyklady()
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Moje wykłady </h3>';
    echo '</div>';
    
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';        
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.instruktor, wyklady_miejsce.nazwa, tematy_wyklad.temat, tematy_wyklad.id as temat_id FROM tematy_wyklad,wyklady, instruktorzy, obecnosc, wyklady_miejsce WHERE tematy_wyklad.id=wyklady.temat AND wyklady.instruktor=instruktorzy.id AND obecnosc.wyklad=wyklady.id AND wyklady.stan=2 AND wyklady.miejsce=wyklady_miejsce.id AND obecnosc.uczen="' . $this->id . '" ORDER BY wyklady.data DESC';
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>  </th>';
                        echo '<th> Data </th>';
                        echo '<th>  </th>';
                        echo '<th data-breakpoints="xs sm"> Wykładowca </th>';
                        echo '<th data-breakpoints="xs sm"> Miejsce </th>';
                        echo '<th data-breakpoints="xs sm"> Temat </th>';
                        echo '<th data-type="html"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    $x = $result->num_rows;
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td data-value="' . $x . '">' . $x . '.</td>';
                            $x--;
                            $data = date("d", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . $data_rok = date("Y", strtotime($row['data']));
                            $godzina = date("G", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
                            echo '<td>' . $data . '</td>';
                            echo '<td>' . $godzina . '</td>';
                            $zapytanie_instruktor = 'SELECT imie, nazwisko FROM instruktorzy WHERE id=' . $row['instruktor'];  
                            $result_instruktor = $mysql->query($zapytanie_instruktor);
                            if ($result_instruktor->num_rows == 1)
                            {
                                $row_instruktor = $result_instruktor->fetch_array();
                                $instruktor = $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'];
                            }    else
                            {
                                $instruktor = 'Nie wybrano';
                            }
                            echo '<td>' . $instruktor . '</td>';
                            echo '<td>' . $row['nazwa'] . '</td>';
                            echo '<td>' . $row['temat'] . '</td>';
                            echo '<td> <a href="../page/wyklad.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
                echo '<tfoot>';
                    echo '<tr>';
                        if ($this->plec == 2)
                        {
                            $byles =  'byłaś';
                        } else
                        {
                            $byles = 'byłeś';                        
                        }
                        echo '<td colspan="7"> Do tej pory ' . $byles . ' na <b> ' . $result->num_rows . ' </b> wykładach </td>';
                    echo '</tr>';
                echo '</tfoot>';
            
                
            echo '</table>';
        } else
        {
            echo '<span> Nie zaplanowano wykładów </span>';
        }
    echo '</div>';    
}     
    
//public function PokazWszystkieWyklady() 
//{
//$mysql = connect_mysql(); 
//    
//    
//    $result= $mysql->query($zapytanie);
//    
//    if ($result->num_rows > 0)
//    {
//        echo '<table class="center">';
//            echo '<thead>';
//                echo '<tr>';
//                    echo '<td> Data </td>';
//                    echo '<td> Wykładowca </td>';
//                    echo '<td> Miejsce </td>';
//                    echo '<td> Temat </td>';
//                echo '</tr>';
//            echo '</thead>';
//            echo '<tbody>';
//                while ($row = $result->fetch_array())
//                {
//                $file = '../page/temat-wyklad.php?id=' . $row['temat_id']; 
//                echo '<tr onclick="document.location =\'' . $file . '\';">';
//                    
//                        $start = $row['data'];
//                        $start_dzien = date("d", strtotime($start));
//                        include ('../script/month.php');
//                        $start_miesiac = $month[date("n", strtotime($start))];
//                        $start_rok = date("Y", strtotime($start));
//                        $start_godzina = date("G", strtotime($start));
//                        $start_minuta = date("i", strtotime($start));
//
//                  
//                    echo '<td>';
//                        echo $start_dzien . ' ' . $start_miesiac . ' ' . $start_rok . ' ' . $start_godzina . ':' . $start_minuta;
//                    echo '</td>';
//                    echo '<td>';
//                        echo $row['imie'] . ' ' . $row['nazwisko'];
//                    echo '</td>';
//                    echo '<td>';
//                        echo $row['nazwa'];
//                    echo '</td>';
//                    echo '<td>';
//                        echo $row['temat'];
//                    echo '</td>';
//                echo '</tr>';    
//                }
//            echo '</tbody>';
//        echo '</table>';
//    } else
//    {
//        echo '<span> Nie masz żadnych wykładów </span>';
//    }    
//}
    
public function wyklad($id)
{
     
$mysql = connect_mysql();    
include ('../script/month.php');   
    
// TYTUŁ    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Podgląd wykładu </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    // KARTKA
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';   
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan, instruktorzy.id AS instruktor_id,instruktorzy.imie, instruktorzy.nazwisko, tematy_wyklad.temat, kategorie_wyklad.nazwa,wyklady_miejsce.id AS wyklad_id,wyklady_miejsce.nazwa AS miejsce, tematy_wyklad.id AS temat_id, kategorie_wyklad.id AS kategoria_id FROM wyklady, instruktorzy, tematy_wyklad, kategorie_wyklad, wyklady_miejsce WHERE wyklady_miejsce.id=wyklady.miejsce AND wyklady.instruktor=instruktorzy.id AND wyklady.temat=tematy_wyklad.id AND tematy_wyklad.kategoria=kategorie_wyklad.id  AND wyklady.id="' . $id . '"'; 
        $result = $mysql->query($zapytanie);    

        if ($result->num_rows == 0)
        {
            header ("Location: ../page/dashboard.php");
            exit();
        }

        $row = $result->fetch_array();
        $data = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
        $czas = date("G", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
    
        echo '<table class="table">';
            echo '<tr>';
                echo '<td> Data </td>';
                echo '<td>' . $data . ',' . $czas . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Wykładowca </td>';
                echo '<td>' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Stan </td>';
                echo '<td>' . $etap_wyklad[$row['stan']] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Temat </td>';
                echo '<td><a href="../page/temat-wyklad.php?id=' . $row['temat_id'] . '">' . $row['temat']  . '</a> </td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Kategoria </td>';
                echo '<td><a href="../page/kategoria-wyklad.php?id=' . $row['kategoria_id'] . '">' . $row['nazwa']  . '</a> </td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Miejsce </td>';
                echo '<td>' . $row['miejsce']  . '</td>';
            echo '</tr>';
        echo '</table>';
    echo '</div>';
    // PRZYCISKI
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';
    
        // TYP: BUTTON LUB NORMAL
        // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
        $button[0]['typ'] = 'clear';
        $button[0]['cat_icon'] = '';
        $button[0]['icon'] = '';
        $button[0]['text'] = '';
        $button[0]['href'] = '';
    
        $button[1]['typ'] = 'clear';
        $button[1]['cat_icon'] = '';
        $button[1]['icon'] = '';
        $button[1]['text'] = '';
        $button[1]['href'] = '';
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';        
}  
    
public function buttons ($array)
{
    for ($x=0;$x<count($array);$x++)
    {
        if ($array[$x]['typ'] == 'normal')
        {
        echo '<a href="'. $array[$x]['href'] . '">';
        }
            if ($x%2 == 0)
            {
                echo '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 col-lg-offset-1 col-md-offset-1 button">';
            } else
            {
                echo '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 col-sm-offset-2 col-lg-offset-1 col-md-offset-1 button">';
            }
            
                if ($array[$x]['typ'] == 'button')
                 {       
                echo '<button type="submit" class="kafel-submit"> </button>';
                }
                switch ($array[$x]['cat_icon'])
                {
                    case 'material-icons':
                        {
                            echo '<i class="material-icons">' . $array[$x]['icon'] . '</i>';
                            break;
                        }
                    case 'fa':
                        {
                            echo '<i class="fa ' . $array[$x]['icon'] . '"></i>';
                            break;
                        }
                    case 'glyphicon':
                        {
                            echo '<i class="glyphicon ' . $array[$x]['icon'] . '"></i>';
                            break;
                        }
                }
                echo '<span>' . $array[$x]['text'] . '</span>';
                
        
            echo '</div>';
        if ($array[$x]['typ'] == 'normal')
        {
        echo '</a>';
        }
        
    }
}    
    
    
public function settings ()
{
    $mysql = connect_mysql();
    $zapytanie = 'SELECT private_profil FROM uczniowie WHERE id=' . $this->id;
    $result = $mysql->query($zapytanie);
    $row = $result->fetch_array();
    $private_profil = $row['private_profil'];
    echo '<form method="POST" action="../script/settings.php">';
    echo '<table>';
    echo '<tr>';
        echo '<td> Prywatny profil </td>';
        echo '<td>';
            echo '<select name="private_profil">';
                switch ($private_profil)
                {
                    case 0:
                        {
                            echo '<option selected value="0"> Tak </option>';
                            echo '<option value="1"> Nie </option>';
                            break;
                        }
                    case 1:
                        {
                            echo '<option value="0"> Tak </option>';
                            echo '<option selected value="1"> Nie </option>';
                            break;
                        }
                }
            echo '</select>';
        echo '</td>';
    echo '</tr>';
    
    
    echo '</table>';
}
    
public function settings_kafel()
{
    $cube[1][0] = 'done';
    $cube[1][1] = 'Akceptuj';
    $cube[1][2] = '#';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = 'clear';
    $cube[2][1] = 'Anuluj';
    $cube[2][2] = '../page/dashboard.php';
    $cube[2][3] = '#6eb4e5';
    
    $cube[3][0] = '';
    $cube[3][1] = '';
    $cube[3][2] = '#';
    $cube[3][3] = '#7dbde9';
    
    $cube[4][0] = '';
    $cube[4][1] = '';
    $cube[4][2] = '#';
    $cube[4][3] = '#8cc3ea';
    
    
    $cube[5][0] = '';
    $cube[5][1] = '';
    $cube[5][2] = '#';
    $cube[5][3] = '#99cbee';
    
    $cube[6][0] = '';
    $cube[6][1] = '';
    $cube[6][2] = '#';
    $cube[6][3] = '#90CAF2';
    

    for ($x=1;$x<=count($cube);$x++)
    {
        
        if ($x == 1)
        {
            echo '<div class="label">';
            echo '<div class="kafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<input type="submit" class="submit-kafel">';    
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';   
            echo '</div>';
        } elseif ($x%2 == 0)
        {   
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel ostatnikafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            echo '</div>';
        } else
        {
            echo '<div class="label">';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';            
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            
        }
    }    
}
    
    
public function PokazWszystkieWplaty ()
{

    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie wpłaty </h3>';
    echo '</div>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';    
        $zapytanie = 'SELECT wplaty.id, wplaty.kasa, wplaty.kasa, instruktorzy.imie, instruktorzy.nazwisko, wplaty.data FROM instruktorzy,wplaty,uczniowie WHERE wplaty.instruktor=instruktorzy.id AND wplaty.uczen=uczniowie.id AND uczniowie.id=' . $this->id . ' ORDER BY data DESC';
        $result = $mysql->query($zapytanie);
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Wpłata </th>';
                        echo '<th> Instruktor </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            $data = date("d", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                            echo '<td>' . $data . '</td>';
                            echo '<td>' . $row['kasa'] . '</td>';
                            echo '<td>' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else
        {
            echo '<span> Nie wpłaciłeś jeszcze żadnych pieniędzy </span>';
            exit();
        } 
    echo '</div>';
}
    
    
public function add_egzamin ()
{
    
$mysql = connect_mysql ();
include ('../script/month.php');

echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj egzamin </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form method="GET" class="form-horizontal" action="../script/add_egzamin.php">';
            echo '<input type="hidden" name="uczen" value="' . $this->id . '">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" name="data" value="' . date("Y-m-d") . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Godzina';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" name="time" value="10:00" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Stan';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="stan" class="input-sm form-control">';
                            for ($x=1;$x<=count($egzamin_stan);$x++)
                            {
                                echo '<option value="' . $x . '">' . $egzamin_stan[$x] . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Rodzaj';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="rodzaj" class="input-sm form-control">';
                            for ($x=3;$x<=count($egzamin_typ);$x++)
                            {
                                echo '<option value="' . $x . '">' . $egzamin_typ[$x] . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                echo 'Opis egzaminu';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';   
                echo '<p style="font-size:13px"> Jak wrażenia? W tym miejscu możesz napisać jak Ci poszło. Co Cię zaskoczyło i z czym miałeś/aś problem. Podziel się z nami gdzie jechaliście, jak poszło parkowanie czy manewr zawracania. Opisz samego egzaminatora i samochód. </p>';
                echo '<textarea name="notatka" style="width:100%;height:200px"></textarea>';
            echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';
    
        // TYP: BUTTON LUB NORMAL
        // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
        $button[0]['typ'] = 'button';
        $button[0]['cat_icon'] = 'material-icons';
        $button[0]['icon'] = 'done';
        $button[0]['text'] = 'Akceptuj';
        $button[0]['href'] = '#';
    
        $button[1]['typ'] = 'normal';
        $button[1]['cat_icon'] = 'material-icons';
        $button[1]['icon'] = 'clear';
        $button[1]['text'] = 'Anuluj';
        $button[1]['href'] = '../page/egzaminy.php';
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';     
}
    
public function samochod ($id)
{
   
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT rejestracja, marka, model, data_produkcji, silnik, skrzynia, paliwo FROM samochody WHERE id=' . $id;
    
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
    } else
    {
        $row = $result->fetch_array();
        
        echo '<table>';
            echo '<tr>';
                echo '<td> Rejestracja </td>';
                echo '<td>' . $row['rejestracja'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Marka </td>';
                echo '<td>' . $row['marka'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Model </td>';
                echo '<td>' . $row['model'] . '</td>';
            echo '</tr>';
                echo '<td> Data produkcji </td>';
                        $data = $row['data_produkcji'];
                        $data_dzien = date("d", strtotime($data));
                        include ('../script/month.php');
                        $data_miesiac = $month[date("n", strtotime($data))];
                        $data_rok = date("Y", strtotime($data));
                echo "<td> $data_dzien $data_miesiac $data_rok </td>";
            echo '</tr>';
            echo '<tr>';
                echo '<td> Silnik </td>';
                echo '<td>' . $row['silnik'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Skrzynia </td>';
                echo '<td>';
                        switch ($row['skrzynia'])
                        {
                            case 1:
                                {
                                    echo 'Manual';
                                    break;
                                }
                            case 2:
                                {
                                    echo 'Automat';
                                    break;
                                }
                        }
                echo '</td>';
            echo '<tr>';
                echo '<td> Paliwo </td>';
                echo '<td>' . $row['paliwo'] . '</td>';
            echo '</tr>';
            echo '</tr>';
        
        echo '</table>';
    }
    
    
}
    
public function samochod_kafel()
{
  /*
    [][0] - klasa ikony
    [][1] - podpis
    [][2] - strona
    [][3] - kolor
    */   

    $cube[1][0] = '';
    $cube[1][1] = '';
    $cube[1][2] = '#';
    $cube[1][3] = '#5fafe4';

    $cube[2][0] = '';
    $cube[2][1] = '';
    $cube[2][2] = '#';
    $cube[2][3] = '#6eb4e5';
    
    $cube[3][0] = '';
    $cube[3][1] = '';
    $cube[3][2] = '#';
    $cube[3][3] = '#7dbde9';
    
    $cube[4][0] = '';
    $cube[4][1] = '';
    $cube[4][2] = '#';
    $cube[4][3] = '#8cc3ea';
    
    
    $cube[5][0] = '';
    $cube[5][1] = '';
    $cube[5][2] = '#';
    $cube[5][3] = '#99cbee';
    
    $cube[6][0] = '';
    $cube[6][1] = '';
    $cube[6][2] = '#';
    $cube[6][3] = '#90CAF2';
    

        for ($x=1;$x<=count($cube);$x++)
    {
        
if ($x%2 == 0)
        {   
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel ostatnikafel" ostatnikafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            echo '</div>';
        } else
        {
            echo '<div class="label">';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';            
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            
        }
    } 
      
}    
    
public function miejsce_wyklad($id)
{
$mysql = connect_mysql();
    
$zapytanie = 'SELECT wyklady_miejsce.nazwa FROM wyklady_miejsce WHERE wyklady_miejsce.id=' . $id;
$result = $mysql->query($zapytanie);
    
if ($result->num_rows == 1)
{
    $row = $result->fetch_array();
    echo '<h2>' . $row['nazwa'] . '</h2>';
    echo '<table>';
        echo '<tr>';
            echo '<td> Miejsce </td>';
            echo '<td>' . $row['nazwa'] . '</td>';
        echo '</tr>';
    echo '</table>';
    
    echo '<h2> Ostatnie 5 wykładów </h2>';
    
    $zapytanie_last = 'SELECT wyklady.id, wyklady.data FROM wyklady WHERE miejsce=' . $id . ' ORDER BY wyklady.data DESC LIMIT 5';
    $result_last = $mysql->query($zapytanie_last);
    
    if ($result_last->num_rows > 0)
    {
        echo '<table>';
            while ($row_last = $result_last->fetch_array())
            {
                include ('../script/month.php');
                $data = $row_last['data'];
                $data_dzien = date("d", strtotime($data));
                $data_miesiac = $month[date("n", strtotime($data))];
                $data_rok = date("Y", strtotime($data));
                $data_godzina = date("G", strtotime($data));
                $data_minuta = date("i", strtotime($data));
                $date = $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . ' ' . $data_godzina . ':' . $data_minuta;
                $file = '../page/wyklad.php?id=' . $row_last['id'];     
                echo '<tr style="cursor:pointer;" onclick="document.location =\'' . $file . '\';">';
                    echo '<td>' . $date . '</td>';
                echo '</tr>';
            }
        echo '</table>';
    } else
    {
        echo '<span> Nie ma żadnych wykładów </span>';
    }
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    
    
}    
    
public function miejsce_wyklad_kafel ()
{
    $cube[1][0] = '';
    $cube[1][1] = '';
    $cube[1][2] = '';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = '';
    $cube[2][1] = '';
    $cube[2][2] = '';
    $cube[2][3] = '#6eb4e5';
    
    $cube[3][0] = '';
    $cube[3][1] = '';
    $cube[3][2] = '#';
    $cube[3][3] = '#7dbde9';
    
    $cube[4][0] = '';
    $cube[4][1] = '';
    $cube[4][2] = '#';
    $cube[4][3] = '#8cc3ea';
    
    
    $cube[5][0] = '';
    $cube[5][1] = '';
    $cube[5][2] = '#';
    $cube[5][3] = '#99cbee';
    
    $cube[6][0] = '';
    $cube[6][1] = '';
    $cube[6][2] = '#';
    $cube[6][3] = '#90CAF2';        


        for ($x=1;$x<=count($cube);$x++)
    {
        
if ($x%2 == 0)
        {   
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel ostatnikafel" ostatnikafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            echo '</div>';
        } else
        {
            echo '<div class="label">';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '<a href="' . $cube[$x][2] . '">';   
            }
            echo '<div class="kafel" style="background-color:' . $cube[$x][3] . '">';
                echo '<i class="material-icons"> ' . $cube[$x][0] . ' </i>';
                echo '<span>' . $cube[$x][1] . '</span>';            
            echo '</div>';
            if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
            {
            echo '</a>';   
            }
            
        }
    } 
    }      


    
}
?>