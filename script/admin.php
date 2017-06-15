<?php

class admin 
{
var $pesel;
var $imie;
var $nazwisko;   
    
public function __construct () // Funkcja uruchamia się podczas tworzenie nowego obiektu, czyli co stronę
{
    include ('../script/mysql.php'); // Dołączenie pliku mysql.php
    $mysql = connect_mysql(); // Połączenie z bazą mysql
    $zapytanie = 'SELECT administratorzy.id,administratorzy.imie, administratorzy.nazwisko FROM administratorzy WHERE pesel="' . $_SESSION['pesel'] . '"'; // Zapytanie zwracające imie i nazwisko administratora
    $result = $mysql->query($zapytanie); // Wysłanie zapytania
    
    if ($result->num_rows != 1) // Jeśli jest tylko jeden rekord to istnieje podany administrator
    {
        header ("Location: ../page/login.php");
    }
    
    $row = $result->fetch_array(); // Pobranie wyniku
    
    // Przypisanie zmiennych
    $this->pesel = $_SESSION['pesel'];
    $this->id = $row['id'];
    $this->imie = $row['imie'];
    $this->nazwisko = $row['nazwisko'];  
}  
   
public function dashboard ()
{

    
    // AGENDA
    echo '<div class="row">';
        echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-1 col-lg-offset-1 agenda shadow">';
            echo '<div class="row">';
                $this->agenda();
            echo '</div>';
        echo '</div>';
    echo '</div>';
    
    // NOTATKI
    echo '<div class="row">';
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 title">';
            echo '<h3> Moje notatki </h3>';
        echo '</div>';  
        echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 note shadow">';
            echo 'W budowie';   
        echo '</div>';  
    echo '</div>';


    
    
}   
    
public function agenda ()
{
    $mysql = connect_mysql();
    
    $ilosc_dni = 7;
    $Date = date('Y-m-d');
    for ($x=0;$x<$ilosc_dni;$x++)
    {
        $today = date('Y-m-d', strtotime($Date. ' + ' . $x . ' days'));
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 day">';
            include ('../script/month.php');
            $dzien = date("d", strtotime($today));        
            $dzien_tygodnia = $dzien_tyg[date("N", strtotime($today))];        
            $miesiac = $month[date("n", strtotime($today))];        
            $rok = date("Y", strtotime($today));  
            $current_data = $dzien . ' ' . $miesiac . ' ' . $rok; 
            echo '<h4>' . $current_data . ' (' . $dzien_tygodnia . ') </h4>';
            $this->today($today);
        echo '</div>';
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
    include ('../script/month.php');
    $zapytanie_wyklad = 'SELECT wyklady.id,wyklady.data,wyklady.stan, wyklady_miejsce.nazwa, instruktorzy.imie, instruktorzy.nazwisko FROM wyklady, wyklady_miejsce, instruktorzy WHERE wyklady_miejsce.id=wyklady.miejsce AND instruktorzy.id=wyklady.instruktor AND date(data)="' . $date . '" ORDER BY data';
    $result_wyklad = $mysql->query($zapytanie_wyklad); 
    while ($row_wyklad = $result_wyklad->fetch_array())
    {
        $wyklad[$c]['id'] = $row_wyklad['id'];
        $wyklad[$c]['data'] = $row_wyklad['data'];
        $wyklad[$c]['miejsce'] = $row_wyklad['nazwa'];
        $wyklad[$c]['instruktor'] = $row_wyklad['imie'] . ' ' . $row_wyklad['nazwisko'];
        $wyklad[$c]['stan'] = $row_wyklad['stan'];
        $today[$a]['data'] = $row_wyklad['data'];
        $today[$a]['id'] = $c;
        $today[$a]['what'] = 1;
        $c++;
        $a++;
    }
    
    $zapytanie_jazdy = 'SELECT uczniowie.id,jazdy.stan, jazdy.data_rozpoczecia, uczniowie.imie, uczniowie.nazwisko, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko FROM jazdy,uczniowie,instruktorzy WHERE instruktorzy.id=jazdy.instruktor AND jazdy.uczen=uczniowie.id AND date(data_rozpoczecia)="' . $date . '" ORDER BY data_rozpoczecia'; 
    $result_jazdy = $mysql->query($zapytanie_jazdy);
    $c = 0;
    while ($row_jazdy = $result_jazdy->fetch_array())
    {
        $jazdy[$c]['id'] = $row_jazdy['id'];
        $jazdy[$c]['stan'] = $row_jazdy['stan'];
        $jazdy[$c]['data'] = $row_jazdy['data_rozpoczecia'];
        $jazdy[$c]['uczen'] = $row_jazdy['imie'] . ' ' . $row_jazdy['nazwisko'];
        $jazdy[$c]['instruktor'] = $row_jazdy['instruktor_imie'] . ' ' . $row_jazdy['instruktor_nazwisko'];
        $today[$a]['data'] = $row_jazdy['data_rozpoczecia'];
        $today[$a]['id'] = $c;
        $today[$a]['what'] = 2;
        $a++;
        $c++;
    }
    
    
    
    
    $zapytanie_egzamin = 'SELECT egzamin.id, egzamin.data, uczniowie.imie, uczniowie.nazwisko FROM uczniowie, egzamin WHERE egzamin.uczen=uczniowie.id AND date(egzamin.data)="' . $date . '" ORDER BY egzamin.data';
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
        
    echo '<table class="table borderless" style="margin-bottom:0;">';
    for ($v = 0;$v<count($today);$v++)
    {
        echo '<tr>';
        if (($v!=0) and ($today[$v]['data'] == $today[$v-1]['data']))
        {
            echo '<td class="col-lg-1 col-md-1 col-sm-1 col-xs-1"> </td>';
        } else
        {
            $hour = date("H:i", strtotime($today[$v]['data'])); 
            echo '<td class="col-lg-1 col-md-1 col-sm-1 col-xs-1">' . $hour . '</td>';
        }
        
                
        
        echo '<td class="col-lg-11 col-md-11 col-sm-11 col-xs-11">';
            switch ($today[$v]['what'])
            {
                case 1:
                    {
                    $id = $today[$v]['id'];
                    echo '<a class="non-href" href="../page/wyklad.php?id=' . $wyklad[$id]['id'] . '">';
                        echo 'Wykłady - ' . $wyklad[$id]['miejsce'] . ' - ' . $wyklad[$id]['instruktor'] . ' - ' . $etap_wyklad[$wyklad[$id]['stan']];
                    echo '</a>';
                    break;
                    }
                case 2:
                    {
                    $id = $today[$v]['id'];
                    echo '<a class="non-href" href="../page/uczen.php?id=' . $jazdy[$id]['id'] . '">';
                        echo 'Jazdy - ' . $jazdy[$id]['uczen'] . ' - ' . $jazdy[$id]['instruktor'] . ' - ' . $etap_jazdy[$jazdy[$id]['stan']];
                
                    echo '</a>';
                    break;
                    }
                case 3:
                    {
                    $id = $today[$v]['id'];
                    echo '<a class="non-href" href="../page/egzamin.php?id=' . $egzamin[$id]['id'] . '">';
                        echo 'Egzamin - ' . $egzamin[$id]['uczen'];
                    echo '</a>';
                    break;
                    }
            }
        echo '</td>';
        echo '<tr>';
     
    }    
    echo '</table>';
        
        
        
    } else
    {
        echo '<span> Nie zaplanowano żadnych rzeczy </span>';
        return 0;
    }
    
     
    
} 
    
function friends ($id)
{

$mysql = connect_mysql();    
    
$zapytanie_znajomi = 'SELECT uczniowie.id,uczniowie.imie, uczniowie.nazwisko, uczniowie.stan FROM uczniowie,friends WHERE uczniowie.id=friends.obserwowany AND friends.uczen=' . $id;
    
$result_znajomi = $mysql->query($zapytanie_znajomi);    
    
if ($result_znajomi->num_rows > 0)
{
    echo '<table>';
    while ($row_znajomi = $result_znajomi->fetch_array())
    {
            
            echo '<tr>';
            echo '<td><a href="../page/uczen.php?id=' . $row_znajomi['id'] . '">'  . $row_znajomi['imie'] . ' ' . $row_znajomi['nazwisko'] . '</a> </td>';
            echo '<td> <img src="../img/stopien' . $row_znajomi['stan'] . '.png" style="width:100px"> </td>';
            echo '<td> <img src="../img/achievement.png"><img src="../img/achievement.png"><img src="../img/achievement.png"><img src="../img/achievement.png"><img src="../img/achievement.png"> </td>';
        echo '</tr>';
    }
    echo '</table>';    
    
}
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
    echo '<div class="hidden-lg hidden-md col-sm-12 col-xs-12 text-right close_menu">';
        echo '<i class="fa fa-times fa-fw fa-2x"  onclick="menu_close()"></i>';
    echo '</div>';
    
    // KURSANCI
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="fa fa-users fa-fw"></i>';
            echo '<span> Kursanci </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/add-user.php"> Dodaj kursanta </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/show_all_user.php?new=on"> Nowi kursanci  </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/show_all_user.php?all=on"> Wszyscy kursanci  </a>';
            echo '</div>';
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                $zapytanie_kursanci = 'SELECT count(id) AS ile FROM uczniowie_www';
                $result_kursanci = $mysql->query($zapytanie_kursanci);
                $row_kursanci = $result_kursanci->fetch_array();
                echo '<a href="../page/show_all_user.php?www=on"> Przez internet [' . $row_kursanci['ile'] . ']  </a>';
            echo '</div>';
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/grupy.php"> Grupy </a>';
            echo '</div>'; 
        echo '</div>';
    echo '</div>';
    
    // WYKŁADY
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="material-icons"> work </i>';
            echo '<span> Wykłady </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/add-wyklad.php"> Dodaj wykład </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                $zapytanie_wyklad = 'SELECT count(id) AS ile FROM wyklady WHERE stan=1';
                $result_wyklad = $mysql->query($zapytanie_wyklad);
                $row_wyklad = $result_wyklad->fetch_array();
                echo '<a href="../page/wyklady.php?new=on""> Najbliższe wykłady [' . $row_wyklad['ile'] . '] </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/wyklady.php"> Historia </a>';
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
                echo '<a href="../page/add-jazdy.php"> Dodaj jazdy </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                $zapytanie_jazdy = 'SELECT count(id) AS ile FROM jazdy WHERE stan=1';
                $result_jazdy = $mysql->query($zapytanie_jazdy);
                $row_jazdy = $result_jazdy->fetch_array();
                echo '<a href="../page/jazdy.php?new=on"> Najbliższe jazdy [' . $row_jazdy['ile'] . '] </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/jazdy.php"> Wszystkie jazdy </a>';
            echo '</div>'; 
    
        echo '</div>';
    echo '</div>';
    
    // EGZAMINY 
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="glyphicon glyphicon-list-alt"></i>';
            echo '<span> Egzaminy </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/add-egzamin.php"> Dodaj egzamin </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                $zapytanie_egzamin = 'SELECT count(egzamin.id) as ile FROM egzamin,uczniowie WHERE egzamin.uczen=uczniowie.id AND egzamin.stan=1';
                $result_egzamin = $mysql->query($zapytanie_egzamin);
                $row_egzamin = $result_egzamin->fetch_array();
                echo '<a href="../page/egzaminy.php?new=on"> Najbliższe egzaminy [' . $row_egzamin['ile'] . ']  </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/egzaminy.php"> Wszystkie egzaminy </a>';
            echo '</div>'; 
        echo '</div>';
    echo '</div>';
    
    // SAMOCHODY
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="material-icons"> directions_car </i>';
            echo '<span> Samochody </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/samochody.php"> Wszystkie samochody </a>';
            echo '</div>';
        echo '</div>';
    echo '</div>';

    // INSTRUKTORZY
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="material-icons"> person </i>';
            echo '<span> Instruktorzy </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/add-instruktor.php"> Dodaj instruktora </a>';
            echo '</div>';
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/instruktorzy.php"> Wszyscy instruktorzy </a>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
    
    // USTAWIENIA
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="material-icons"> settings </i>';
            echo '<span> Ustawienia </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/settings.php"> Ustawienia </a>';
            echo '</div>';
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/settings-kursanci.php"> Kursy </a>';
            echo '</div>';
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/settings-firma.php"> Firma </a>';
            echo '</div>';
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/settings-wyklady.php"> Wykłady </a>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
    
    // WYLOGUJ
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="../page/logout.php">';
            echo '<i class="material-icons"> input </i>';
            echo '<span> Wyloguj </span>';
        echo '</a>';
    echo '</div>';

    
    
    
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
            echo '<i class="fa fa-comments fa-2x fa-fw" id="news-open"> </i>';
            echo '<a href="../page/admin.php">';
                echo $this->imie . ' ' . $this->nazwisko;
            echo '</a>';
            echo '<i class="fa fa-angle-down fa-2x fa-fw hidden-xs hidden-sm" id="small-menu-open"> </i>';
            echo '<i class="fa fa-bars fa-2x fa-fw hidden-lg hidden-md" onclick="menu_open()"> </i>';
        echo '</div>';
    echo '</div>';
} 
    
public function news ()
{
    
}
    
public function PokazWszystkichUczniowZapisanychPrzezWWW()
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszyscy kursanci zapisani przez internet </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-user.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj kursanta';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT id, pesel, imie, nazwisko, email, telefon, data,kurs FROM uczniowie_www ORDER BY id';
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20" data-filtering="true" data-filter-placeholder="Szukaj" data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>'; 
                        echo '<th> Imię i nazwisko </td>';
                        echo '<th> Data zapisania </td>';
                        echo '<th> Telefon </td>';
                        echo '<th> E-mail </td>';
                        echo '<th> Kurs </td>';
                        echo '<th data-type="html"> </th>';
                        echo '<th data-type="html"> </th>';
                    echo '</tr>';
                echo '</thead>';    
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
                            echo '<td>' . $row['data'] . '</td>';
                            echo '<td>' . $row['telefon'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo '<td>' . $row['kurs'] . '</td>';
                            echo '<td> <a href="../page/add-user.php?id=' . $row['id'] . '" style="color:green;font-weight:bold;"> Dodaj </a> </td>';
                            echo '<td> <a href="../script/delete_user_www.php?id=' . $row['id'] . '" style="color:red;font-weight:bold;"> Usuń </a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else
        {
            echo '<span> Nikt nie zapisał się przez internet </span>';
        }     
    echo '</div>';
    

}
    
public function PokazWszystkieGrupy()
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie grupy </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-grupa.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj grupę';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT grupy.id, grupy.nazwa FROM grupy ORDER BY grupy.id';
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows > 0)
        {
            echo '<table class="table">';
                echo '<thead>';
                    echo '<tr>'; 
                        echo '<th> Nazwa </th>';
                        echo '<th> Ilość kursantów </th>';
                        echo '<th>  </th>';
                    echo '</tr>';
                echo '</thead>';    
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        $zapytanie_ile = 'SELECT count(*) as ile FROM kursanci_grupa WHERE grupa=' . $row['id'];
                        $result_ile = $mysql->query($zapytanie_ile);
                        $row_ile = $result_ile->fetch_array();
                        
                        echo '<tr>';
                            echo '<td>' . $row['nazwa'] . '</td>';
                            echo '<td>' . $row_ile['ile'] . '</td>';
                            echo '<td> <a href="../page/grupa.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else
        {
            echo '<span> Nie dodano jeszcze żadnych grup </span>';
        }     
    echo '</div>';
    

} 
    
public function grupa ($id)
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    $zapytanie = 'SELECT nazwa FROM grupy WHERE id=' . $id;
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    $row = $result->fetch_array();
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Grupa - ' . $row['nazwa'] . ' </h3>';
    echo '</div>';
    
    // PRZYCISK
//    echo '<a href="../page/add-jazdy.php">';
//        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
//            echo 'Dodaj jazdy';
//        echo '</div>';
//    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie_uczniowie = 'SELECT uczniowie.imie, uczniowie.nazwisko FROM uczniowie, kursanci_grupa WHERE uczniowie.id=kursanci_grupa.kursant AND kursanci_grupa.grupa=' . $id;
    
        $result_uczniowie = $mysql->query($zapytanie_uczniowie);
    
        echo '<table class="table">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th> Kursant </th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                while ($row_uczniowie = $result_uczniowie->fetch_array())
                {
                    echo '<tr>';
                        echo '<td>' . $row_uczniowie['imie'] . ' ' . $row_uczniowie['nazwisko'] . '</td>';
                    echo '</tr>';
                }
            echo '</tbody>';
        echo '</table>';
    
    
    echo '</div>';
}    
    
public function wplata ($id)
{
    
$mysql = connect_mysql();    
include ('../script/month.php');   
    
// TYTUŁ    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Wpłata </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    // KARTKA
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';    
        $zapytanie = 'SELECT instruktorzy.imie AS instruktor_imie, instruktorzy.nazwisko AS instruktor_nazwisko, uczniowie.imie AS uczen_imie, uczniowie.nazwisko AS uczen_nazwisko,uczniowie.id AS uczen_id, wplaty.instruktor, wplaty.kasa, wplaty.data FROM instruktorzy,wplaty,uczniowie WHERE wplaty.instruktor=instruktorzy.id AND uczniowie.id=wplaty.uczen AND wplaty.id=' . $id;    
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
                    $data = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                    $czas = date("G", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
                    echo $data . ' ' . $czas;
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Instruktor </td>';
                echo '<td><a href="../page/instruktor.php?id=' . $row['instruktor'] . '">' . $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'] . '</a></td>';
            echo '</tr>'; 
            echo '<tr>';
                echo '<td> Uczeń </td>';
                echo '<td><a href="../page/uczen.php?id=' . $row['uczen_id'] . '">' . $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'] . '</a></td>';
            echo '</tr>'; 
            echo '<tr>';
                echo '<td> Wpłata </td>';
                echo '<td>' . $row['kasa'] . ' PLN</td>';
            echo '</tr>';
            
        echo '</table>';
    echo '</div>';
    // PRZYCISKI
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';
    
        // TYP: BUTTON LUB NORMAL
        // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
        $button[0]['typ'] = 'normal';
        $button[0]['cat_icon'] = 'material-icons';
        $button[0]['icon'] = 'mode_edit';
        $button[0]['text'] = 'Edytuj';
        $button[0]['href'] = '../page/edit-wplata.php?id=' . $id;
    
        $button[1]['typ'] = 'clear';
        $button[1]['cat_icon'] = '';
        $button[1]['icon'] = '';
        $button[1]['text'] = '';
        $button[1]['href'] = '';
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';     

}
    
    
public function edit_wplata($id)
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    

$mysql = connect_mysql();
$zapytanie = 'SELECT instruktorzy.imie AS instruktor_imie, instruktorzy.nazwisko AS instruktor_nazwisko, uczniowie.imie AS uczen_imie, uczniowie.nazwisko AS uczen_nazwisko,uczniowie.id AS uczen_id, wplaty.instruktor, wplaty.kasa, wplaty.data FROM instruktorzy,wplaty,uczniowie WHERE wplaty.instruktor=instruktorzy.id AND uczniowie.id=wplaty.uczen AND wplaty.id=' . $id;
    
$result = $mysql->query($zapytanie);
if ($result->num_rows == 0)
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    
$row = $result->fetch_array();    
$data = date("Y-n-j", strtotime($row['data']));   
$time = date("H:i", strtotime($row['data']));    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Edytuj wpłatę </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/edit-wplata.php">';   
            echo '<input type="hidden" value="' . $id . '" name="id">';        
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input name="date" type="date" class="input-sm form-control" value="' . $data. '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Czas';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" name="start_time" class="input-sm form-control" value="' . $time . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Kursant';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" disabled class="input-sm form-control" value="' . $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'] . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Instruktor';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" name="instruktor">';
                            $zapytanie_instruktor = 'SELECT id, imie, nazwisko FROM instruktorzy ORDER BY nazwisko';
                            $result_instruktor = $mysql->query($zapytanie_instruktor);
                                while ($row_instruktor = $result_instruktor->fetch_array())
                                {
                                    if ($row_instruktor['id'] == $row['instruktor'])
                                    {
                                        $select = 'selected="selected"';
                                    } else
                                    {
                                        $select = '';
                                    }
                                    echo '<option ' . $select . ' value="' . $row_instruktor['id'] . '"> ' . $row_instruktor['nazwisko'] . ' ' . $row_instruktor['imie'] . '</option>';
                                }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wpłata';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="number" min="0" name="kasa" class="input-sm form-control" value="' . $row['kasa'] . '">';
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
    $button[1]['href'] = '../page/wplata.php?id=' . $id;
    
    $button[2]['typ'] = 'normal';
    $button[2]['cat_icon'] = 'material-icons';
    $button[2]['icon'] = 'delete_forever';
    $button[2]['text'] = 'Usuń';
    $button[2]['href'] = '../script/delete-wplata.php?id=' . $id;

    $this->buttons($button);


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
    

public function small_menu ()
{ 
echo '<li> <a href="../page/admin.php"> Twój profil </a> </li>';
echo '<li> <a href="../page/settings.php"> Ustawienia </a> </li>';
echo '<li> <a href="../page/logout.php"> Wyloguj </a> </li>';
}    
  
public function show_profil_uczen($id)
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
        echo '<a href="../page/uczen-all.php?id=' . $id . '">';
            echo '<div class="col-lg-2 col-md-3 col-sm-3 col-xs-5 profile_button" > Szczegóły </div>';
        echo '</a>';
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
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_details"> Kategoria: ' . $row['skrot'] . '</div>';
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_achievements">';
            echo 'W budowie';
        echo '</div>';
    echo '</div>';
    echo '<div class="col-lg-3 col-md-4 col-sm-4 hidden-xs profile_photo square">';
        echo '<img src="../img/prifile-pic.png" width="100%" height="100%">';
    echo '</div>';
    $zapytanie_jazdy = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE stan=2 AND uczen="' . $id . '"';
    $result_jazdy = $mysql->query($zapytanie_jazdy);

    $wynik = 0;
    while ($row_jazdy = $result_jazdy->fetch_array())
    {
        $start = strtotime($row_jazdy['data_rozpoczecia']);
        $koniec = strtotime($row_jazdy['data_zakonczenia']);
        $roznica = $koniec-$start;
        $wynik +=  $roznica;

    }

    $czas = $wynik/3600;

    $zapytanie_wyklady = 'SELECT count(*) AS ile FROM obecnosc WHERE uczen=' . $id;
    $result_wyklady = $mysql->query($zapytanie_wyklady);
    $row_wyklady = $result_wyklady->fetch_array();
    $wyklady = $row_wyklady['ile'];

    $zapytanie_jazdy = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE stan=2 AND uczen="' . $id . '"';
    $result_jazdy = $mysql->query($zapytanie_jazdy);

    $wynik = 0;
    while ($row_jazdy = $result_jazdy->fetch_array())
    {
        $start = strtotime($row_jazdy['data_rozpoczecia']);
        $koniec = strtotime($row_jazdy['data_zakonczenia']);
        $roznica = $koniec-$start;
        $wynik +=  $roznica;

    }

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
    $caly_czas = $row['jazdy'];
    $caly_koszt= $row['cena'];


    $zapytanie_dodatkowe = 'SELECT dodatkowe_jazdy.cena, dodatkowe_jazdy.jazdy FROM dodatkowe_jazdy WHERE  dodatkowe_jazdy.uczen=' . $id;
    $result_dodatkowe = $mysql->query($zapytanie_dodatkowe);

    while ($row_dodatkowe = $result_dodatkowe->fetch_array())
    {
        $caly_czas += $row_dodatkowe['jazdy'];
        $caly_koszt += $row_dodatkowe['cena'];
    }

    $zapytanie_pieniadze = 'SELECT kasa FROM wplaty WHERE uczen=' . $id;
    $result_pieniadze = $mysql->query($zapytanie_pieniadze);
    $wplacone_pieniadze =0;
    while ($row_pieniadze = $result_pieniadze->fetch_array())
    {
        $wplacone_pieniadze += $row_pieniadze['kasa'];
    }
    
    echo '<a href="../page/wyklady.php?uczen=' . $id . '">';
        echo '<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 profile_telebin">';
            echo '<i> ' . $wyklady . ' </i>';
            echo '<span> Wykłady </span>';
        echo '</div>';
    echo '</a>';
    echo '<a href="../page/wplaty.php?uczen=' . $id . '">';
        echo '<div class="col-lg-4 col-md-12 col-lg-offset-1 col-sm-12 col-xs-12 profile_telebin">';
            echo '<i>' . $wplacone_pieniadze . '/' . $caly_koszt . '</i>';
            echo '<span> Finanse </span>';
        echo '</div>';
    echo '</a>';
    echo '<a href="../page/jazdy.php?uczen=' . $id . '">';
        echo '<div class="col-lg-3 col-md-12 col-lg-offset-1 col-sm-12 col-xs-12 profile_telebin">';
            echo '<i>' . $czas . '/' . $caly_czas . '</i>';
            echo '<span> Jazdy </span>';
        echo '</div>';
    echo '</a>';
    
    if ($caly_czas == 0)
    {
    $szerokosc = 0;    
    } else
    {
    $szerokosc = 100-($czas/$caly_czas*100);     
    }    
    
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_postep">';
        echo '<div class="postep" style="width:5%"> 0% </div>';
        echo '<div class="time" style="width:' . $szerokosc .  '%"> </div>';
    echo '</div>';
    echo '<a href="../page/add-dodatkowe-jazdy.php?id=' . $id . '">';
        echo '<div class="col-lg-2 col-lg-offset-10 col-md-2 col-md-offset-10 col-sm-2 col-sm-offset-10 col-xs-8 col-xs-offset-4 profile_upgrade"> Powiększ szkolenie </div>';
    echo '</a>';
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
    
public function PokazWszystkieNajblizszeWyklady()
{
    
     // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Najbliższe wykłady </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-wyklad.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj wykład';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';        
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan,wyklady.instruktor,tematy_wyklad.temat, wyklady_miejsce.nazwa FROM tematy_wyklad,wyklady, wyklady_miejsce WHERE tematy_wyklad.id=wyklady.temat AND wyklady.miejsce=wyklady_miejsce.id AND wyklady.stan=1 ORDER BY wyklady.data';
        $result= $mysql->query($zapytanie);

        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20" data-filtering="true" data-filter-placeholder="Szukaj" data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Wykładowca </th>';
                        echo '<th> Temat </th>';
                        echo '<th> Miejsce </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>' . $row['data'] . '</td>';
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
    
    
public function PokazWszystkieEgzaminyUcznia ($id)
{
 
// ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        $zapytanie_uczen = 'SELECT id,imie, nazwisko FROM uczniowie WHERE id=' . $id;
        $result_uczen = $mysql->query($zapytanie_uczen);
        if ($result_uczen->num_rows == 0)
        {
            header ("Location: ../page/dashboard.php");
            exit();
        } else
        {
            $row_uczen = $result_uczen->fetch_array();
        }
        echo '<h3> Wszystkie egzaminy - uczeń ' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'] .  '</h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-egzamin.php?uczen=' . $row_uczen['id']  . '">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj egzamin';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT egzamin.id, egzamin.data, egzamin.stan,egzamin.typ, uczniowie.imie, uczniowie.nazwisko FROM uczniowie,egzamin WHERE egzamin.uczen=uczniowie.id AND uczniowie.id=' . $id;
        $result = $mysql->query($zapytanie);
    
        if ($result->num_rows > 0)
        {
            echo '<table class="table footer">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Stan </th>';
                        echo '<th> Rodzaj </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>' . $row['data'] . '</td>';
                            echo '<td>' . $egzamin_stan[$row['stan']] . '</td>';
                            echo '<td>' . $egzamin_typ[$row['typ']] . '</td>';
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

public function PokazWszystkieEgzaminyInstruktora ($id)
{ 
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        $zapytanie_instruktor = 'SELECT imie, nazwisko FROM instruktorzy WHERE id=' . $id;
        $result_instruktor = $mysql->query($zapytanie_instruktor);
        if ($result_instruktor->num_rows == 0)
        {
            header ("Location: ../page/dashboard.php");
            exit();
        } else
        {
            $row_instruktor = $result_instruktor->fetch_array();
        }
        echo '<h3> Wszystkie egzaminy - instruktor ' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] .  '</h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-egzamin.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj egzamin';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
         $zapytanie = 'SELECT egzamin.id, egzamin.data, egzamin.stan,egzamin.typ, uczniowie.imie, uczniowie.nazwisko FROM uczniowie,egzamin WHERE egzamin.uczen=uczniowie.id AND uczniowie.instruktor=' . $id;
        $result = $mysql->query($zapytanie);
    
        if ($result->num_rows > 0)
        {
            echo '<table class="table footer">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Kursant </th>';
                        echo '<th> Stan </th>';
                        echo '<th> Rodzaj </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>' . $row['data'] . '</td>';
                            echo '<td>' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
                            echo '<td>' . $egzamin_stan[$row['stan']] . '</td>';
                            echo '<td>' . $egzamin_typ[$row['typ']] . '</td>';
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
    
public function PokazWszystkieNajblizszeEgzaminy ()
{
        
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Zaplanowane egzaminy </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-egzamin.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj egzamin';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT egzamin.id, egzamin.data, egzamin.stan,egzamin.typ, uczniowie.imie, uczniowie.nazwisko FROM uczniowie,egzamin WHERE egzamin.uczen=uczniowie.id AND egzamin.stan=1';
        $result = $mysql->query($zapytanie);
    
        if ($result->num_rows > 0)
        {
            echo '<table class="table footer">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th data-type="date" data-format-string="DD MMMM YYYY HH:mm"> Data </th>';
                        echo '<th> Kursant </th>';
                        echo '<th> Rodzaj </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            $dzien = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                            $czas = date("H", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
                            echo '<td>' . $dzien . ' '. $czas . '</td>';
                            echo '<td>' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
                            echo '<td>' . $egzamin_typ[$row['typ']] . '</td>';
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
        $zapytanie = 'SELECT egzamin.id, egzamin.data, egzamin.stan, egzamin.typ, uczniowie.imie, uczniowie.nazwisko FROM uczniowie,egzamin WHERE egzamin.uczen=uczniowie.id';
        $result = $mysql->query($zapytanie);
    
        if ($result->num_rows > 0)
        {
            echo '<table class="table footer">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Kursant </th>';
                        echo '<th> Stan </th>';
                        echo '<th> Rodzaj </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>' . $row['data'] . '</td>';
                            echo '<td>' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
                            echo '<td>' . $egzamin_stan[$row['stan']] . '</td>';
                            echo '<td>' . $egzamin_typ[$row['typ']] . '</td>';
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
            $zapytanie = 'SELECT egzamin.data, egzamin.stan,egzamin.notatka, egzamin.typ, uczniowie.id, uczniowie.imie, uczniowie.nazwisko FROM uczniowie, egzamin WHERE egzamin.uczen=uczniowie.id AND egzamin.id=' . $id;
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
                    $data  = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data'])) . ' ' . date("G:i", strtotime($row['data']));
                    echo '<td>' . $data . '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td> Kursant </td>';
                    echo '<td><a href="../page/uczen.php?id=' . $row['id'] . '">' . $row['imie'] . ' ' . $row['nazwisko'] . '</a></td>';
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
                echo '<h3> Notatka </h3>';        
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
    
        $button[1]['typ'] = 'normal';
        $button[1]['cat_icon'] = 'material-icons';
        $button[1]['icon'] = 'delete_forever';
        $button[1]['text'] = 'Usuń';
        $button[1]['href'] = '../script/delete-egzamin.php?id=' . $id;
    
        $this->buttons($button);
        echo '</div>';  
    echo '</div>'; 
    
    

}
    
public function edit_egzamin ($id)
{
    
    
    $mysql = connect_mysql ();
    include ('../script/month.php');

    $zapytanie = 'SELECT egzamin.data, egzamin.stan,egzamin.typ, egzamin.notatka,uczniowie.id, uczniowie.imie, uczniowie.nazwisko FROM uczniowie,egzamin WHERE uczniowie.id=egzamin.uczen AND egzamin.id=' . $id;
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
                                for ($x=1;$x<=count($egzamin_typ);$x++)
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
                        echo 'Notatka';
                    echo '</div>';
                    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';   
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
        
    
public function add_egzamin ($id)
{
    
$mysql = connect_mysql ();
include ('../script/month.php');

echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj egzamin </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form method="GET" class="form-horizontal" action="../script/add_egzamin.php">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Uczeń';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="uczen" class="input-sm form-control">';
                            $zapytanie_uczen = 'SELECT id, imie, nazwisko FROM uczniowie WHERE stan!=5 AND stan!=6';
                            $result_uczen = $mysql->query($zapytanie_uczen);
                            while ($row_uczen = $result_uczen->fetch_array())
                            {
                                if ($id == $row_uczen['id'])
                                {
                                    echo '<option selected value="' . $row_uczen['id'] . '">' . $row_uczen['imie'] . ' ' .  $row_uczen['nazwisko'] . '</option>';   
                                } else
                                {
                                    echo '<option value="' . $row_uczen['id'] . '">' . $row_uczen['imie'] . ' ' .  $row_uczen['nazwisko'] . '</option>';                                    
                                }      
                            }
                        echo '<select>';
                    echo '</div>';
                echo '</div>';    
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
                        echo '<input type="time" name="time" value="' . date("H:i") . '" class="form-control input-sm">';
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
                            for ($x=1;$x<=count($egzamin_typ);$x++)
                            {
                                echo '<option value="' . $x . '">' . $egzamin_typ[$x] . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                echo 'Notatka';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';   
                echo '<textarea name="notatka" style="width:100%;height:200px"> </textarea>';
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
    
        if ($id != 0)
        {
            $button[1]['href'] = '../page/uczen.php?id=' . $id;  
        } else
        {
            $button[1]['href'] = '../page/dashboard.php';
        }
        $button[1]['typ'] = 'normal';
        $button[1]['cat_icon'] = 'material-icons';
        $button[1]['icon'] = 'clear';
        $button[1]['text'] = 'Anuluj';
        
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';     
}
        
      

    
public function edit_profil_uczen ($id)
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
$mysql = connect_mysql();    
$zapytanie = 'SELECT uczniowie.pesel,uczniowie.notatka, uczniowie.imie, uczniowie.nazwisko, uczniowie.data_zapisu, uczniowie.plec,uczniowie.miejsce_spotkan, uczniowie.data_urodzenia, uczniowie.telefon, uczniowie.instruktor, uczniowie.email, uczniowie.stan, uczniowie.pkk, uczniowie.kurs, uczniowie.jazdy, uczniowie.cena, uczniowie.kategoria, kategoria_prawo_jazdy.skrot FROM uczniowie,  kategoria_prawo_jazdy WHERE  kategoria_prawo_jazdy.id=uczniowie.kategoria AND uczniowie.id="' . $id . '"'; 
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
            echo '<input type="hidden" name="id" value="' . $id . '">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                echo 'Dane osobowe';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Pesel';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" id="pesel" value="' . $row['pesel'] . '" name="pesel" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Imię';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="imie" value="' . $row['imie'] . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Nazwisko';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="name" value="' . $row['nazwisko'] . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data urodzenia';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" name="data_urodzenia" value="' . $row['data_urodzenia'] . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Płeć';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="sex" class="form-control input-sm">';
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
                            echo '<input type="text"  value="' . $row['miejsce_spotkan'] . '" name="miejsce_spotkan" class="form-control input-sm">';
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
                            echo '<input type="text" id="pkk" value="' . $row['pkk'] . '" name="pkk" class="form-control input-sm">';
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Data zapisu';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="date" value="' . $row['data_zapisu'] . '" name="data_zapisu" class="form-control input-sm">';
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Etap';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<select name="etap" class="input-sm form-control">';
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
                            $zapytanie_kategoria = 'SELECT id, skrot FROM kategoria_prawo_jazdy WHERE active=1 ORDER BY skrot';
                            $result_kategoria = $mysql->query($zapytanie_kategoria);
                            echo '<select name="kategoria" class="input-sm form-control">';
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

                            echo '<select name="kurs" class="form-control input-sm" onchange="edit_uczen_kurs()" id="kurs">';

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
                                echo '<td> <input class="input-sm form-control"  name="jazdy" type="number" id="jazdy" name="jazdy" value="' . $row['jazdy'] . '"> </td>';  
                            } else
                            {
                                echo '<td> <input class="input-sm form-control" disabled="disabled" name="jazdy" type="number" id="jazdy"  value="' . $row['jazdy'] . '"> </td>';
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
                                echo '<td> <input class="input-sm form-control" type="number" id="cena" name="cena" value="' . $row['cena'] . '"> </td>';
                            } else
                            {
                                echo '<td> <input class="input-sm form-control" disabled="disabled" type="number" id="cena" name="cena" value="' . $row['cena'] . '"> </td>';   
                            }
                        echo '</div>';
                    echo '</div>'; 
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Instruktor';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<select name="instruktor" class="input-sm form-control">';
                                $zapytanie_instruktor = 'SELECT id,imie,nazwisko FROM instruktorzy ORDER BY nazwisko';
                                $result_instruktor = $mysql->query($zapytanie_instruktor);
                                while ($row_instruktor = $result_instruktor->fetch_array())
                                {
                                    if ($row_instruktor['id'] == $row['instruktor'])
                                    {
                                        echo '<option selected value="' . $row_instruktor['id'] . '">' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</option>';   
                                    } else
                                    {
                                        echo '<option value="' . $row_instruktor['id'] . '">' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</option>';   
                                    }
                                     
                                }
                            echo '</select>';
                        echo '</div>';
                    echo '</div>'; 
                echo '</div>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                    echo 'Notatka';
                echo '</div>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';   
                    echo '<textarea name="notatka" style="width:100%;height:200px">' . $row['notatka'] . ' </textarea>';
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
    $button[1]['href'] = '../page/uczen-all.php?id=' . $id;

    $this->buttons($button);


echo '</div>';       

} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    



}  
    
public function PokazWszystkichUczniow() 
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszyscy kursanci </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-user.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj kursanta';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20" data-filtering="true" data-filter-placeholder="Szukaj" data-paging-count-format="{CP} z {TP}">';
            $zapytanie = 'SELECT uczniowie.id, uczniowie.imie as uczen_imie, uczniowie.nazwisko as uczen_nazwisko, uczniowie.pesel, uczniowie.data_zapisu, uczniowie.stan, uczniowie.kurs, uczniowie.jazdy, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, uczniowie.procent, kategoria_prawo_jazdy.skrot FROM uczniowie, instruktorzy, kategoria_prawo_jazdy WHERE uczniowie.instruktor=instruktorzy.id AND kategoria_prawo_jazdy.id=uczniowie.kategoria ORDER BY uczniowie.data_zapisu DESC';
            $result= $mysql->query($zapytanie);
    
            echo '<thead>';
                echo '<tr>';
                    echo '<th> Kursant </th>';
                    echo '<th data-breakpoints="xs "> Instruktor </th>';
                    echo '<th data-breakpoints="xs sm md"> Data zapisu </th>';
                    echo '<th data-breakpoints="xs sm md"> Kategoria </th>';
                    echo '<th data-breakpoints="xs sm md"> Kurs </th>';
                    echo '<th data-breakpoints="xs sm "> Stan </th>';
                    echo '<th data-breakpoints="xs" data-type="number" > Wykłady </th>'; 
                    echo '<th data-breakpoints="xs  "> Jazdy </th>';
                    echo '<th data-breakpoints="xs sm md"> % </th>';
                    echo '<th  data-type="html"> </th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                while ($row = $result->fetch_array())
                {
                    echo '<tr>';
                        echo '<td>' . $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'] . '</td>';
                        echo '<td>' . $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'] . '</td>';
                        echo '<td>' . $row['data_zapisu'] . '</td>';
                        echo '<td>' . $row['skrot'] . '</td>';
                        echo '<td>' . $row['kurs'] . '</td>';
                        echo '<td>' . $etap[$row['stan']] . '</td>';
                        echo '<td>';
                            $id = $row['id'];
                            $zapytanie_wyklady = 'SELECT wyklady.id,wyklady.data,wyklady.stan FROM obecnosc, wyklady WHERE wyklady.id=obecnosc.wyklad AND obecnosc.uczen=' . $id;
                            $result_wyklady = $mysql->query($zapytanie_wyklady);
                            $ile_wykladow = $result_wyklady->num_rows;
                            echo $ile_wykladow;
                        echo '</td>';
                        $zapytanie_jazdy = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE stan=2 AND uczen="' . $id . '"';
                        $result_jazdy = $mysql->query($zapytanie_jazdy);
                        $wynik = 0;
                        while ($row_jazdy = $result_jazdy->fetch_array())
                        {   
                            $start = strtotime($row_jazdy['data_rozpoczecia']);
                            $koniec = strtotime($row_jazdy['data_zakonczenia']);
                            $roznica = $koniec-$start;
                            $wynik +=  $roznica;
                        }

                        $czas = $wynik/3600;
                        echo '<td data-sort-value="' . $czas  . '">';                    
                        echo round($czas) . '/' . date("H", strtotime($row['jazdy']));
                        echo '</td>';
                        echo '<td>';
                            echo $row['procent'];
                        echo '</td>';
                        echo '<td> <a href="../page/uczen.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                    echo '</tr>';
                }
            echo '</tbody>';
        echo '</table>';
    echo '</div>';
    
    
    
}   
    
public function PokazWszystkichUczniowInstruktora($id) 
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    $zapytanie_instruktor = 'SELECT instruktorzy.imie, instruktorzy.nazwisko FROM instruktorzy WHERE instruktorzy.id=' . $id;
    $result_instruktor = $mysql->query($zapytanie_instruktor);
    if ($result_instruktor->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
    } 
    $row_instruktor = $result_instruktor->fetch_array();
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszyscy kursanci - instruktor ' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] .' </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-user.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj kursanta';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20" data-filtering="true" data-filter-placeholder="Szukaj" data-paging-count-format="{CP} z {TP}">';
            $zapytanie = 'SELECT uczniowie.id, uczniowie.imie as uczen_imie, uczniowie.nazwisko as uczen_nazwisko, uczniowie.pesel, uczniowie.data_zapisu, uczniowie.stan, uczniowie.kurs, uczniowie.jazdy, uczniowie.procent, kategoria_prawo_jazdy.skrot FROM uczniowie, instruktorzy, kategoria_prawo_jazdy WHERE uczniowie.instruktor=instruktorzy.id AND kategoria_prawo_jazdy.id=uczniowie.kategoria AND instruktorzy.id=' . $id . ' ORDER BY uczniowie.data_zapisu DESC';
            $result= $mysql->query($zapytanie);
    
            echo '<thead>';
                echo '<tr>';
                    echo '<th> Kursant </th>';
                    echo '<th data-breakpoints="xs sm md"> Data zapisu </th>';
                    echo '<th data-breakpoints="xs sm md"> Kategoria </th>';
                    echo '<th data-breakpoints="xs sm md"> Kurs </th>';
                    echo '<th data-breakpoints="xs sm "> Stan </th>';
                    echo '<th data-breakpoints="xs" data-type="number" > Wykłady </th>'; 
                    echo '<th data-breakpoints="xs  "> Jazdy </th>';
                    echo '<th data-breakpoints="xs sm md"> % </th>';
                    echo '<th  data-type="html"> </th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                while ($row = $result->fetch_array())
                {
                    echo '<tr>';
                        echo '<td>' . $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'] . '</td>';
                        echo '<td>' . $row['data_zapisu'] . '</td>';
                        echo '<td>' . $row['skrot'] . '</td>';
                        echo '<td>' . $row['kurs'] . '</td>';
                        echo '<td>' . $etap[$row['stan']] . '</td>';
                        echo '<td>';
                            $id = $row['id'];
                            $zapytanie_wyklady = 'SELECT wyklady.id,wyklady.data,wyklady.stan FROM obecnosc, wyklady WHERE wyklady.id=obecnosc.wyklad AND obecnosc.uczen=' . $id;
                            $result_wyklady = $mysql->query($zapytanie_wyklady);
                            $ile_wykladow = $result_wyklady->num_rows;
                            echo $ile_wykladow;
                        echo '</td>';
                        $zapytanie_jazdy = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE stan=2 AND uczen="' . $id . '"';
                        $result_jazdy = $mysql->query($zapytanie_jazdy);
                        $wynik = 0;
                        while ($row_jazdy = $result_jazdy->fetch_array())
                        {   
                            $start = strtotime($row_jazdy['data_rozpoczecia']);
                            $koniec = strtotime($row_jazdy['data_zakonczenia']);
                            $roznica = $koniec-$start;
                            $wynik +=  $roznica;
                        }

                        $czas = $wynik/3600;
                        echo '<td data-sort-value="' . $czas  . '">';                    
                            echo round($czas) . '/' . $row['jazdy'];
                        echo '</td>';
                        echo '<td>';
                            echo $row['procent'];
                        echo '</td>';
                        echo '<td> <a href="../page/uczen.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                    echo '</tr>';
                }
            echo '</tbody>';
        echo '</table>';
    echo '</div>';
    
    
    
}     
    
public function PokazWszystkichNowychUczniow ()
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Nowi kursanci </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-user.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj kursanta';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
    
    
    $zapytanie_dzien = 'SELECT date(data_zapisu) AS data, count(*) as ile FROM uczniowie GROUP BY data ORDER BY data DESC';
    $result_dzien = $mysql->query($zapytanie_dzien);
    
    while ($row_dzien = $result_dzien->fetch_array())
    {
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $data = $row_dzien['data'];
            $data_miesiac = $month[date("n", strtotime($data))];
            $data_rok = date("Y", strtotime($data));
            $data_dzien = date("d", strtotime($data));
            echo '<h3>' . $data_dzien  . ' '. $data_miesiac . ' ' . $data_rok . '</h3>';
        echo '</div>';
        
        $zapytanie = 'SELECT uczniowie.id, uczniowie.imie,uczniowie.pkk, uczniowie.nazwisko, uczniowie.pesel, uczniowie.data_zapisu, uczniowie.stan, uczniowie.kurs, uczniowie.jazdy, kategoria_prawo_jazdy.skrot FROM uczniowie, kategoria_prawo_jazdy WHERE date(uczniowie.data_zapisu)="' . $data . '" AND kategoria_prawo_jazdy.id=uczniowie.kategoria ORDER BY uczniowie.data_zapisu DESC';
        $result = $mysql->query($zapytanie);
        echo '<table class="table footable"  data-sorting="true">';
            while ($row = $result->fetch_array())
            {
                echo '<tr>';
                    echo '<td class="col-lg-2">' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
                    echo '<td class="col-lg-1">' . $row['skrot'] . '</td>';
                    echo '<td class="col-lg-3">' . $row['kurs'] . '</td>';
                    echo '<td class="col-lg-3">' . $row['pkk'] . '</td>';
                    echo '<td class="col-lg-2">' . $row['pesel'] . '</td>';
                    echo '<td class="col-lg-1" data-type="html"> <a href="../page/uczen.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                echo '</tr>';
            }   
        echo '</table>';
    }
    echo '</div>';
}
    
    
public function PokazWszystkichInstruktorow ()
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszyscy instruktorzy </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-instruktor.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj instruktora';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        
            $zapytanie = 'SELECT instruktorzy.imie, instruktorzy.nazwisko, instruktorzy.id, instruktorzy.telefon, instruktorzy.stan FROM instruktorzy ORDER BY data_zatrudnienia';
            $result= $mysql->query($zapytanie);
            if ($result->num_rows > 0)
            {
                echo '<table class="table footer">';
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th> Imie i nazwisko </th>';
                            echo '<th> Telefon </th>';
                            echo '<th> Portfel </th>';
                            echo '<th data-type="html">  </th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                        while ($row = $result->fetch_array())
                        {  
                            echo '<tr>'; 
                                echo '<td>';
                                    echo $row['imie'] . ' ' . $row['nazwisko'];
                                echo '</td>';
                                echo '<td>';
                                    $telefon  = $row['telefon'];
                                    $t1 = substr($telefon,0,3);
                                    $t2 = substr($telefon,3,3);
                                    $t3 = substr($telefon,6,3);
                                    echo  $t1 . ' ' . $t2 . ' ' . $t3;
                                echo '</td>';
                                $zapytanie_portfel = 'SELECT aktualnie FROM budzet WHERE instruktor=' . $row['id'] . ' ORDER BY data DESC LIMIT 1';
                                $result_portfel = $mysql->query($zapytanie_portfel);
                                if ($result_portfel->num_rows == 0)
                                {
                                    $aktualny_portfel = 0;
                                } else
                                {
                                    $row_portfel = $result_portfel->fetch_array();
                                    $aktualny_portfel = $row_portfel['aktualnie'];
                                }    
                                echo '<td>' . $aktualny_portfel . 'zł </td>';
                                echo '<td> <a href="../page/instruktor.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                            echo '</tr>';                      
                        }
                    echo '</tbody>';
                echo '</table>';
            } else
            {
                echo '<span> Nie dodano żadnego instruktora </span>';
            }
    echo '</div>';            
}       
    
public function PokazWszystkieWyklady()
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie wykłady </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-wyklad.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj wykład';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';        
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan,wyklady.instruktor, wyklady_miejsce.nazwa FROM wyklady, wyklady_miejsce WHERE wyklady.miejsce=wyklady_miejsce.id AND (wyklady.stan=2 OR wyklady.stan=3) ORDER BY wyklady.data DESC';   
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Stan </th>';
                        echo '<th> Instruktor </th>';
                        echo '<th> Miejsce </th>';
                        echo '<th data-type="html"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>' . $row['data'] . '</td>';
                            echo '<td>' . $etap_wyklad[$row['stan']] . '</td>';
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
                            echo '<td> <a href="../page/wyklad.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else
        {
            echo '<span> Nie zaplanowano wykładów </span>';
        }
    echo '</div>';    
}   
    
public function PokazWszystkieWykladyInstruktora($id)
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    $zapytanie_instruktor = 'SELECT imie, nazwisko FROM instruktorzy WHERE id=' . $id;
    $result_instruktor = $mysql->query($zapytanie_instruktor);
    if ($result_instruktor->num_rows == 1)
    {
        $row_instruktor = $result_instruktor->fetch_array();
        $instruktor = $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'];
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie wykłady - instruktor ' . $instruktor . ' </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-wyklad.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj wykład';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';        
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan, wyklady_miejsce.nazwa FROM wyklady, wyklady_miejsce WHERE wyklady.miejsce=wyklady_miejsce.id AND wyklady.instruktor="' . $id . '" ORDER BY wyklady.data DESC';
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Stan </th>';
                        echo '<th> Miejsce </th>';
                        echo '<th data-type="html"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>' . $row['data'] . '</td>';
                            echo '<td>' . $etap_wyklad[$row['stan']] . '</td>';
                            echo '<td>' . $row['nazwa'] . '</td>';
                            echo '<td> <a href="../page/wyklad.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else
        {
            echo '<span> Nie zaplanowano wykładów </span>';
        }
    echo '</div>';
   
}   
    
 public function PokazWszystkieWykladyUcznia($id)
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    $zapytanie_uczen = 'SELECT imie, nazwisko FROM uczniowie WHERE id=' . $id;
    $result_uczen = $mysql->query($zapytanie_uczen);
    if ($result_uczen->num_rows  == 1)
    {
        $row_uczen = $result_uczen->fetch_array();
        $uczen = $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'];
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie wykłady - uczeń ' . $uczen . ' </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-wyklad.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj wykład';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';        
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan, wyklady_miejsce.nazwa, wyklady.instruktor FROM wyklady, obecnosc, uczniowie, wyklady_miejsce WHERE wyklady.miejsce=wyklady_miejsce.id AND uczniowie.id=obecnosc.uczen AND obecnosc.wyklad=wyklady.id AND uczniowie.id="' . $id . '" ORDER BY wyklady.data DESC';
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Stan </th>';
                        echo '<th> Instruktor </th>';
                        echo '<th> Miejsce </th>';
                        echo '<th data-type="html"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>' . $row['data'] . '</td>';
                            echo '<td>' . $etap_wyklad[$row['stan']] . '</td>';
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
                            echo '<td> <a href="../page/wyklad.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else
        {
            echo '<span> Nie zaplanowano wykładów </span>';
        }
    echo '</div>';

}     
    
public function PokazWszystkieNajblizszeJazdy()
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Najbliższe jazdy  </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-jazdy.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj jazdy';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie_dzien = 'SELECT date(data_rozpoczecia) AS dzien, count(*) AS ilosc FROM jazdy WHERE jazdy.stan=1 GROUP BY dzien ORDER BY dzien';
        $result_dzien = $mysql->query($zapytanie_dzien);
        if ($result_dzien->num_rows == 0)
        {
            echo '<span> Nie zaplanowano żadnych jazd </span>';
        } else
        {
            while ($row_dzien = $result_dzien->fetch_array())
            {
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    $data = $row_dzien['dzien'];
                    $data_miesiac = $month[date("n", strtotime($data))];
                    $data_rok = date("Y", strtotime($data));
                    $data_dzien = date("d", strtotime($data));
                    echo '<h3>' . $data_dzien  . ' '. $data_miesiac . ' ' . $data_rok . '</h3>';
                echo '</div>';
                echo '<table class="table footable">';
                    $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia,jazdy.notatka, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, uczniowie.imie as uczen_imie, uczniowie.nazwisko as uczen_nazwisko, instruktorzy.show_car, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko FROM jazdy,uczniowie,instruktorzy WHERE jazdy.uczen=uczniowie.id AND jazdy.stan=1 AND instruktorzy.id=jazdy.instruktor AND date(jazdy.data_rozpoczecia)="' . $row_dzien['dzien'] . '" ORDER BY jazdy.data_rozpoczecia ASC, jazdy.id ASC';
                    $result= $mysql->query($zapytanie);
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>';
                                $start = date("H", strtotime($row['data_rozpoczecia'])) . ':' . date("i", strtotime($row['data_rozpoczecia']));
                                $koniec = date("H", strtotime($row['data_zakonczenia'])) . ':' . date("i", strtotime($row['data_zakonczenia']));

                                echo $start . ' - ' . $koniec;
                            echo '</td>';
                            echo '<td>' . $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'] . '</td>';
                            echo '<td>' . $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'] . '</td>';
                            echo '<td>';
                                $samochod_id = $row['samochod'];
                                if ($samochod_id == 0)
                                {
                                    $samochod = 'Nie wybrano';
                                 } else
                                {
                                $zapytanie_samochod = 'SELECT rejestracja,nazwa FROM samochody WHERE id=' . $samochod_id;
                                $result_samochod = $mysql->query($zapytanie_samochod);
                                $row_samochod = $result_samochod->fetch_array();
                                if ($row['show_car'] == 0)
                                {
                                $samochod = $row_samochod['rejestracja'];
                                } else
                                {
                                $samochod = $row_samochod['nazwa'];   
                                }
                                }
                                echo $samochod;
                            echo '</td>';
                            echo '<td data-type="html"> <a href="../page/jazda.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</table>';
            }
        }
    echo '</div>';
}   
    
public function PokazWszystkieJazdyInstruktora($id)
{
    
    
    $mysql = connect_mysql(); 
    $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, uczniowie.imie, uczniowie.nazwisko FROM jazdy,uczniowie WHERE jazdy.uczen=uczniowie.id AND jazdy.instruktor="' . $id . '" ORDER BY jazdy.data_rozpoczecia DESC';
    $result= $mysql->query($zapytanie);
    
    if ($result->num_rows > 0)
    {
        echo '<table id="all_jazdy">';
            echo '<thead>';
                echo '<tr>';
                    echo '<td style="width:150px"> Data </td>';
                    echo '<td> </td>';
                    echo '<td> Samochód </td>';
                    echo '<td> Uczeń </td>';
                    echo '<td> Stan </td>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody >';
                while ($row = $result->fetch_array())
                {
                $file = '../page/jazda.php?id=' . $row['id'];     
                echo '<tr onclick="document.location =\'' . $file . '\';">';

                        include ('../script/month.php');
                        $start = $row['data_rozpoczecia'];
                        $koniec = $row['data_zakonczenia'];
                        $start_dzien = date("d", strtotime($start));
                        $start_miesiac = $month[date("n", strtotime($start))];
                        $start_rok = date("Y", strtotime($start));
                        $start_godzina = date("H", strtotime($start));
                        $start_minuta = date("i", strtotime($start));
                        
                        $koniec_dzien = date("j", strtotime($koniec));
                        $koniec_miesiac = $month[date("n", strtotime($koniec))];
                        $koniec_rok = date("Y", strtotime($koniec));
                        $koniec_godzina = date("G", strtotime($koniec));
                        $koniec_minuta = date("i", strtotime($koniec));
                    
                    echo '<td>';
                        echo  $start_dzien . ' ' . $start_miesiac . ' ' . $start_rok;
                    echo '</td>';
                    echo '<td>';
                        echo $start_godzina . ':' . $start_minuta . ' - ' . $koniec_godzina . ':' . $koniec_minuta ;
                    echo '</td>';
                    echo '<td>';
                        $samochod_id = $row['samochod'];
                        if ($samochod_id == 0)
                        {
                            $samochod = 'Nie wybrano';
                         } else
                        {
                        $zapytanie_samochod = 'SELECT rejestracja,nazwa FROM samochody WHERE id=' . $samochod_id;
                        $result_samochod = $mysql->query($zapytanie_samochod);
                        $row_samochod = $result_samochod->fetch_array();
                        if ($row['show_car'] == 0)
                        {
                        $samochod = $row_samochod['rejestracja'];
                        } else
                        {
                        $samochod = $row_samochod['nazwa'];   
                        }
                        }
                        echo $samochod;
                    echo '</td>';
                    echo '<td>';
                        echo $row['imie'] . ' ' . $row['nazwisko'];
                    echo '</td>';
                    echo '<td>';
                        echo $etap_jazdy[$row['stan']];
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
    
public function PokazWszystkieJazdy()
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie jazdy </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-jazdy.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj jazdy';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, uczniowie.imie as uczen_imie, uczniowie.nazwisko as uczen_nazwisko, instruktorzy.show_car, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko FROM jazdy,uczniowie,instruktorzy WHERE jazdy.uczen=uczniowie.id AND instruktorzy.id=jazdy.instruktor  ORDER BY jazdy.data_rozpoczecia DESC, jazdy.id ASC';
        $result = $mysql->query($zapytanie);
        $wynik = 0;
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20" data-filtering="true" data-filter-placeholder="Szukaj" data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Godzina </th>';
                        echo '<th> Uczeń </th>';
                        echo '<th> Instruktor </th>';
                        echo '<th> Samochód </th>';
                        echo '<th> Stan </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>';
                                $dzien = date("j", strtotime($row['data_rozpoczecia'])) . ' ' . $month[date("n", strtotime($row['data_rozpoczecia']))] . ' ' . date("Y", strtotime($row['data_rozpoczecia']));
                                echo $dzien;
                            echo '</td>';
                            echo '<td>';
                                $czas = date("H", strtotime($row['data_rozpoczecia'])) . ':' . date("i", strtotime($row['data_rozpoczecia'])) . ' - ' . date("H", strtotime($row['data_zakonczenia'])) . ':' . date("i", strtotime($row['data_zakonczenia']));
                                $start = strtotime($row['data_rozpoczecia']);
                                $koniec = strtotime($row['data_zakonczenia']);
                                $roznica = $koniec-$start;
                                $wynik +=  $roznica;  
                                echo $czas;
                            echo '</td>';
                            echo '<td>';
                                echo $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'];
                            echo '</td>';
                            echo '<td>';
                                echo $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'];
                            echo '</td>';
                            echo '<td>';
                                $samochod_id = $row['samochod'];
                                if ($samochod_id == 0)
                                {
                                    $samochod = 'Nie wybrano';
                                 } else
                                {
                                $zapytanie_samochod = 'SELECT rejestracja,nazwa FROM samochody WHERE id=' . $samochod_id;
                                $result_samochod = $mysql->query($zapytanie_samochod);
                                $row_samochod = $result_samochod->fetch_array();
                                if ($row['show_car'] == 0)
                                {
                                $samochod = $row_samochod['rejestracja'];
                                } else
                                {
                                $samochod = $row_samochod['nazwa'];   
                                }
                                }
                                echo $samochod;
                            echo '</td>';
                            echo '<td>';
                                echo $etap_jazdy[$row['stan']];
                            echo '</td>';
                            echo '<td> <a href="../page/jazda.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
            echo '</table>';
        } else
        {
            echo '<span> Nie ma żadnych jazd </span>';
        }
    echo '</div>';

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
                $data_dzien = date("j", strtotime($data));
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
                echo '<td>' . $data . ' ' . $czas . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Wykładowca </td>';
                echo '<td><a href="../page/instruktor.php?id=' . $row['instruktor_id'] . '">' . $row['imie'] . ' ' . $row['nazwisko'] . '</a> </td>';
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
                echo '<td><a href="../page/miejsce-wyklad.php?id=' . $row['wyklad_id'] . '">' . $row['miejsce']  . '</a> </td>';
            echo '</tr>';
        echo '</table>';
        echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
            echo '<h3> Lista obecności </h3>';        
        echo '</div>'; 
        echo '<table class="table">';
            $zapytanie_obecnosc = 'SELECT uczniowie.id, uczniowie.imie, uczniowie.nazwisko FROM wyklady, obecnosc, uczniowie WHERE obecnosc.uczen=uczniowie.id AND wyklady.id=obecnosc.wyklad AND wyklady.id=' . $id;
            $result_obecnosc = $mysql->query($zapytanie_obecnosc);
            while ($row_obecnosc = $result_obecnosc->fetch_array())
            {
                echo '<tr>';
                    echo '<td><a href="../page/uczen.php?id=' . $row_obecnosc['id'] . '">' . $row_obecnosc['imie'] . ' ' . $row_obecnosc['nazwisko'] . '</a></td>';
                echo '</tr>';
            }  
        echo '</table>';
    echo '</div>';
    // PRZYCISKI
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';
    
        // TYP: BUTTON LUB NORMAL
        // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
        $button[0]['typ'] = 'normal';
        $button[0]['cat_icon'] = 'material-icons';
        $button[0]['icon'] = 'mode_edit';
        $button[0]['text'] = 'Edytuj';
        $button[0]['href'] = '../page/edit-wyklad.php?id=' . $id;
    
        $button[1]['typ'] = 'normal';
        $button[1]['cat_icon'] = 'material-icons';
        $button[1]['icon'] = 'delete_forever';
        $button[1]['text'] = 'Usuń wykład';
        $button[1]['href'] = '../script/delete-wyklad.php?id=' . $id;
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';        
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
    
public function edit_wyklad($id)
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
$zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan, wyklady.instruktor, wyklady.temat, instruktorzy.imie, instruktorzy.nazwisko,wyklady.miejsce, tematy_wyklad.kategoria  FROM wyklady,instruktorzy, tematy_wyklad WHERE wyklady.instruktor=instruktorzy.id AND wyklady.temat=tematy_wyklad.id AND wyklady.id=' . $id; 

$result = $mysql->query($zapytanie);

if ($result->num_rows == 0)
{
    //header('Location: ../page/dashboard.php', true, false ? 301 : 302);
    exit();
} 
   
$row = $result->fetch_array();    
$data = $row['data'];
$date = date("Y-m-d", strtotime($data));
$data_czas = date("H:i", strtotime($data));   
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Edytuj wykład </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/edit-wyklad.php">';   
            echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" value="' . $date . '" name="data" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Godzina';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" name="time" value="' . $data_czas . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wykładowca';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="instruktor" class="form-control input-sm">';
                            $zapytanie_instruktor = 'SELECT id, imie, nazwisko FROM instruktorzy ORDER BY nazwisko';
                            $result_instruktor = $mysql->query($zapytanie_instruktor);
                            while ($row_instruktor =  $result_instruktor->fetch_array())
                            {
                                if ($row_instruktor['id'] ==  $row['instruktor'])
                                {
                                    echo '<option selected="selected" value="' . $row_instruktor['id'] . '">' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</option>';    
                                } else
                                {
                                    echo '<option value="' . $row_instruktor['id'] . '">' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</option>';
                                }
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Stan';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" name="stan">';
                            for ($x=1;$x<=count($etap_wyklad);$x++)
                            {
                                if ($row['stan'] == $x)
                                {
                                    echo '<option selected="selected" value="' . $x . '">' . $etap_wyklad[$x] . '</option>';  
                                } else
                                {
                                    echo '<option value="' . $x . '">' . $etap_wyklad[$x] . '</option>';  
                                }
                                 
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Temat';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" name="temat">';
                            $zapytanie_kategoria = 'SELECT id, nazwa FROM kategorie_wyklad';
                            $result_kategoria = $mysql->query($zapytanie_kategoria);
                            while ($row_kategoria = $result_kategoria->fetch_array())
                            {
                                $zapytanie_temat = 'SELECT id, temat FROM tematy_wyklad WHERE kategoria=' . $row_kategoria['id'];
                                $result_temat = $mysql->query($zapytanie_temat);

                                if ($result_temat->num_rows > 0)
                                {
                                    echo '<optgroup label="' . $row_kategoria['nazwa'] . '">';
                                    while ($row_temat = $result_temat->fetch_array())
                                    {
                                        if ($row_temat['id'] == $row['temat'])
                                        {
                                            echo '<option selected value="' . $row_temat['id'] . '">' . $row_temat['temat'] . '</option>';    
                                        } else
                                        {
                                            echo '<option value="' . $row_temat['id'] . '">' . $row_temat['temat'] . '</option>';       
                                        }

                                    }
                                    echo '</optgroup>';
                                }
                            }    
                        echo '</select>';
                    echo '</div>';
                echo '</div>';   
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Miejsce';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" name="miejsce">';
                            $zapytanie_miejsce = 'SELECT id,nazwa FROM wyklady_miejsce';
                            $result_miejsce = $mysql->query($zapytanie_miejsce);
                            while ($row_miejsce = $result_miejsce->fetch_array())
                            {
                                if ($row['miejsce'] == $row_miejsce['id'])
                                {
                                    echo '<option selected="selected" value="' . $row_miejsce['id'] . '">' . $row_miejsce['nazwa'] . '</option>';   
                                } else
                                {
                                    echo '<option value="' . $row_miejsce['id'] . '">' . $row_miejsce['nazwa'] . '</option>';
                                }
                                
                            }    
                        echo '</select>';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                    echo 'Lista obecności';
                echo '</div>';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-widget">';
                        echo '<select class="select2 form-control input-sm" name="uczen[]" multiple="multiple">';
                            $zapytanie_uczen = 'SELECT id,imie,nazwisko FROM uczniowie';
                            $result_uczen = $mysql->query($zapytanie_uczen);
                            $zapytanie_obecnosc = 'SELECT uczen FROM obecnosc WHERE wyklad=' . $id;
                            $result_obecnosc = $mysql->query($zapytanie_obecnosc);
                            while ($row_obecnosc = $result_obecnosc->fetch_array())
                            {
                                $obecnosc[] =  $row_obecnosc['uczen'];
                            }
    
                            while ($row_uczen = $result_uczen->fetch_array())
                            {
                                if (in_array($row_uczen['id'],$obecnosc))
                                {
                                    echo '<option selected value="' . $row_uczen['id'] . '">' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'] . '</option>';   
                                } else
                                {
                                    echo '<option value="' . $row_uczen['id'] . '">' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'] . '</option>';
                                }
                            }    
                        echo '</select>';
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
        $button[1]['href'] = '../page/wyklad.php?id=' . $id;
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';       
              
} 
   
 
public function add_wyklad ()
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj wykład </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/add_wyklad.php">';   
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" value="' . date('Y-m-d') . '" name="date" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Godzina';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" name="time" value="' . date('H:i') . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wykładowca';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="wykladowca" class="form-control input-sm">';
                            $zapytanie_instruktor = 'SELECT id, imie, nazwisko FROM instruktorzy ORDER BY nazwisko';
                            $result_instruktor = $mysql->query($zapytanie_instruktor);
                            while ($row_instruktor =  $result_instruktor->fetch_array())
                            {
                                echo '<option value="' . $row_instruktor['id'] . '">' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Stan';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" name="stan">';
                            for ($x=1;$x<=count($etap_wyklad);$x++)
                            {
                                echo '<option value="' . $x . '">' . $etap_wyklad[$x] . '</option>';   
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Temat';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" name="temat">';
                            $zapytanie_kategoria = 'SELECT id, nazwa FROM kategorie_wyklad';
                            $result_kategoria = $mysql->query($zapytanie_kategoria);
                            while ($row_kategoria = $result_kategoria->fetch_array())
                            {
                                $zapytanie_temat = 'SELECT id, temat FROM tematy_wyklad WHERE kategoria=' . $row_kategoria['id'];
                                $result_temat = $mysql->query($zapytanie_temat);

                                if ($result_temat->num_rows > 0)
                                {
                                    echo '<optgroup label="' . $row_kategoria['nazwa'] . '">';
                                    while ($row_temat = $result_temat->fetch_array())
                                    {
                                        echo '<option value="' . $row_temat['id'] . '">' . $row_temat['temat'] . '</option>'; 
                                    }
                                    echo '</optgroup>';
                                }
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';   
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Miejsce';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" name="miejsce">';
                            $zapytanie_miejsce = 'SELECT id,nazwa FROM wyklady_miejsce';
                            $result_miejsce = $mysql->query($zapytanie_miejsce);
                            while ($row_miejsce = $result_miejsce->fetch_array())
                            {
                                echo '<option value="' . $row_miejsce['id'] . '">' . $row_miejsce['nazwa'] . '</option>';
                            }    
                        echo '</select>';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                    echo 'Lista obecności';
                echo '</div>';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ui-widget">';
                        echo '<select class="select2 form-control input-sm" name="uczen[]" multiple="multiple">';
                            $zapytanie_uczen = 'SELECT id,imie,nazwisko FROM uczniowie';
                            $result_uczen = $mysql->query($zapytanie_uczen);
                            
                            while ($row_uczen = $result_uczen->fetch_array())
                            {
                                echo '<option value="' . $row_uczen['id'] . '">' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'] . '</option>';
                            }    
        
                        echo '</select>';
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
        $button[1]['href'] = '../page/dashboard.php';
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';        
     
}    
      

public function add_grupa ()
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj grupę </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/add_grupa.php">';   
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Nazwa';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text"  name="nazwa" class="form-control input-sm">';
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
        $button[1]['href'] = '../page/grupy.php';
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';        
     
}     
    
    
public function show_profil_admin ()
{
$mysql = connect_mysql();    
$zapytanie = 'SELECT pesel, imie, nazwisko, telefon, email FROM administratorzy WHERE administratorzy.pesel="' . $this->pesel . '"';
    
$result = $mysql->query($zapytanie);

if ($result->num_rows ==0) // Jeśli zapytanie zwróci 0 to nie ma takiego ucznia
{
header ("Location: ../page/dashboard.php"); // Przekieruj do strony informującej o błędzie    
} else
{
    $row =  $result->fetch_array(); // Przypisanie do tablicy


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
    
public function show_profil_admin_kafel()
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


    for ($x=1;$x<=count($cube);$x++)
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
    
public function settings ()
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
$zapytanie = 'SELECT administratorzy.czas_wygasniecia, password.login FROM administratorzy,password WHERE administratorzy.pesel=password.pesel AND administratorzy.pesel="' . $this->pesel . '"';
$result = $mysql->query($zapytanie);    
    
if ($result->num_rows != 1)
{
    header ("Location: ../page/dashboard.php");
} else
{    
$row = $result->fetch_array();
$time = $row['czas_wygasniecia'];    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Ustawienia </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/settings.php">';    
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Czas wygaśnięcia';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="time" class="input-sm form-control">';
                            switch ($time)
                            {
                                case 5:
                                    {
                                        echo '<option selected value="5"> 5 minut </option>';
                                        echo '<option value="15"> 15 minut </option>';
                                        echo '<option value="60"> 1 godzina </option>';
                                        echo '<option value="120"> 2 godziny </option>';
                                        break;
                                    }
                                case 15:
                                    {
                                        echo '<option value="5"> 5 minut </option>';
                                        echo '<option selected value="15"> 15 minut </option>';
                                        echo '<option value="60"> 1 godzina </option>';
                                        echo '<option value="120"> 2 godziny </option>';
                                        break;
                                    } 
                                case 60:
                                    {
                                        echo '<option value="5"> 5 minut </option>';
                                        echo '<option value="15"> 15 minut </option>';
                                        echo '<option selected value="60"> 1 godzina </option>';
                                        echo '<option value="120"> 2 godziny </option>';
                                        break;
                                    }  
                                case 120:
                                    {
                                        echo '<option value="5"> 5 minut </option>';
                                        echo '<option value="15"> 15 minut </option>';
                                        echo '<option value="60"> 1 godzina </option>';
                                        echo '<option selected value="120"> 2 godziny </option>';
                                        break;
                                    }
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Alias loginu';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" value="' . $row['login'] . '" name="alias" class="input-sm form-control">';
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
        $button[1]['href'] = '../page/dashboard.php';
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';  
    
}
}
    
    
public function settings_firma ()
{
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT nazwa, facebook, instagram, www, twitter,youtube FROM firma';
    $result = $mysql->query($zapytanie);
    $row = $result->fetch_array();
    
    echo '<div style="width: 50%; float: left;">';
    echo '<h2> O firmie </h2>';
    echo '<form method="POST" action="../script/settings-firma.php">';
    echo '<input type="hidden" name="what" value="1">';
    echo '<table>';
        echo '<tr>';
            echo '<td> Nazwa </td>';
            echo '<td> <input type="text" placeholder="Nazwa firmy" name="nazwa" value="' . $row['nazwa'] . '"> </td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Facebook </td>';
            echo '<td> <input type="text" placeholder="Facebook" name="facebook" value="' . $row['facebook'] . '"> </td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Instagram </td>';
            echo '<td> <input type="text" placeholder="Instagram" name="instagram" value="' . $row['instagram'] . '"> </td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> WWW </td>';
            echo '<td> <input type="text" placeholder="WWW" name="www" value="' . $row['www'] . '"> </td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Twitter </td>';
            echo '<td> <input type="text" placeholder="Twitter" name="twitter" value="' . $row['twitter'] . '"> </td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> YouTube </td>';
            echo '<td> <input type="text" placeholder="YouTube" name="youtube" value="' . $row['youtube'] . '"> </td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td colspan="2" style="text-align:center"> <input type="submit" value="Zapisz" style="height:auto; margin-top:10px;"> </td>';
        echo '</tr>';
    echo '</table>';
    echo '</form>';
    echo '</div>';

        
}
    
public function PokazWszystkieMiejscaWykladow ()
{
    $mysql = connect_mysql();
    $zapytanie = 'SELECT id, nazwa FROM wyklady_miejsce ORDER BY nazwa';
    
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows > 0)
    {
        echo '<table>';
        echo '<thead>';
            echo '<tr>';
                echo '<td> Nazwa </td>';
            echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($row = $result->fetch_array())
        {
            $file = '../page/miejsce-wyklad.php?id=' . $row['id'];     
            echo '<tr onclick="document.location =\'' . $file . '\';">';
                echo '<td>';
                    echo $row['nazwa'];
                echo '</td>';
            
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else
    {
        echo '<span> Nie dodano miejsc wykładów </span>';
    }
    
    
}
    
public function settings_kursanci()
{
    $mysql = connect_mysql();
    
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Ustawienia kursantów </h3>';
    echo '</div>';
    
    
    echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo 'Kursy';
            echo '</div>';
            $zapytanie_kursy = 'SELECT id, nazwa, cena, jazdy FROM kursy ORDER BY cena';
            $result_kursy = $mysql->query($zapytanie_kursy);

            if ($result_kursy->num_rows > 0)
            {
                echo '<table class="table">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Nazwa </th>';
                        echo '<th> Cena </th>';
                        echo '<th> Jazdy </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row_kursy = $result_kursy->fetch_array())
                {     
                    echo '<tr>';
                        echo '<td>' . $row_kursy['nazwa'] . '</td>';
                        echo '<td>' . $row_kursy['cena'] . ' PLN</td>';
                        echo '<td>' . $row_kursy['jazdy'] . 'h</td>';
                        echo '<td> <a href="../page/kurs.php?id=' . $row_kursy['id'] . '"> <i class="fa fa-info"> </i> </a> </td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else
            {
                echo '<span> Nie dodano żadnych kursów </span>';
            }
    
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo 'Dodatkowe kursy';
            echo '</div>';
            $zapytanie_kursy = 'SELECT id, cena, godziny FROM pakiety ORDER BY cena';
            $result_kursy = $mysql->query($zapytanie_kursy);

            if ($result_kursy->num_rows > 0)
            {
                echo '<table class="table">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Cena </th>';
                        echo '<th> Jazdy </th>';
                        echo '<th colspan="2"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row_kursy = $result_kursy->fetch_array())
                {     
                    echo '<tr>';
                        echo '<td>' . $row_kursy['cena'] . ' PLN</td>';
                        echo '<td>' . $row_kursy['godziny'] . 'h</td>';
                        echo '<td> <a href="../page/edit-pakiet.php?id=' . $row_kursy['id'] . '"> Edytuj  </a> </td>';
                        echo '<td> <a href="../script/delete-pakiet.php?id=' . $row_kursy['id'] . '"> Usuń  </a></td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else
            {
                echo '<span> Nie dodano żadnych dodatkowych kursów </span>';
            }
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo 'Kategorie';
            echo '</div>';
            $zapytanie_kategoria = 'SELECT id, skrot, nazwa, active FROM kategoria_prawo_jazdy ORDER BY skrot';
            $result_kategoria = $mysql->query($zapytanie_kategoria);

            if ($result_kategoria->num_rows > 0)
            {
                echo '<table class="table">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Skrót </th>';
                        echo '<th> Nazwa </th>';
                        echo '<th colspan="2"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                while ($row_kategoria = $result_kategoria->fetch_array())
                {     
                    echo '<tr>';
                        echo '<td>' . $row_kategoria['skrot'] . ' </td>';
                        echo '<td>' . $row_kategoria['nazwa'] . '</td>';
                        echo '<td> <a href="../page/edit-pakiet.php?id=' . $row_kategoria['id'] . '"> Edytuj  </a> </td>';
                        if ($row_kategoria['active'] == 1)
                        {
                            echo '<td> <a href="../script/active-kategoria.php?id=' . $row_kategoria['id'] . '"> Wyłącz kategorię  </a></td>';
                        } else
                        {
                            echo '<td> <a href="../script/active-kategoria.php?id=' . $row_kategoria['id'] . '"> Włącz kategorię  </a></td>';
                        }
                        
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else
            {
                echo '<span> Nie dodano żadnych dodatkowych kursów </span>';
            }
    
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo 'Dodaj kurs';
            echo '</div>';
            echo '<form method="POST" class="form-horizontal" action="../script/settings-kursanci.php">';
                echo '<input type="hidden" value="1" name="what">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-1 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Nazwa';
                    echo '</label>';
                    echo '<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="nazwa" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-1 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Cena';
                    echo '</label>';
                    echo '<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="number" name="cena" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-1 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Jazdy';
                    echo '</label>';
                    echo '<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="number" name="jazdy" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input value="Dodaj" type="submit" class="form-control input-sm btn-primary">';
                    echo '</div>';
                echo '</div>';
            echo '<form>';
        echo '</div>';  
    echo '</div>';
    
    
}
    
public function kurs ($id)
{
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT kursy.nazwa, kursy.cena, kursy.jazdy FROM kursy WHERE kursy.id=' . $id;
    $result = $mysql->query($zapytanie);
    if ($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        
        // TYTUŁ    
        echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
            echo '<h3> Kurs - ' . $row['nazwa'] . '</h3>';
        echo '</div>';
        
        echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
            // KARTKA
            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
                echo '<table class="table">';
                    echo '<tr>';
                        echo '<td> Nazwa </td>';
                        echo '<td>' . $row['nazwa'] . '</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Cena </td>';
                        echo '<td>' . $row['cena'] . 'PLN</td>';
                    echo '</tr>';
                    echo '<tr>';
                        echo '<td> Jazdy </td>';
                        echo '<td>' . $row['jazdy'] . 'h</td>';
                    echo '</tr>';
                echo '</table>';
            echo '</div>';
            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';

                // TYP: BUTTON LUB NORMAL
                // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
                $button[0]['typ'] = 'normal';
                $button[0]['cat_icon'] = 'material-icons';
                $button[0]['icon'] = 'mode_edit';
                $button[0]['text'] = 'Edytuj';
                $button[0]['href'] = '../page/edit-kurs.php?id=' . $id;

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
    }
        
        
    
    
}
    
public function edit_kurs($id)
{
    $mysql = connect_mysql();
    $zapytanie = 'SELECT id,nazwa, cena, jazdy FROM kursy WHERE id=' . $id;
    $result = $mysql->query($zapytanie);

    if ($result->num_rows == 1)
    {
        $row = $result->fetch_array();

        
        echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
            echo '<h3> Edytuj kurs </h3>';
        echo '</div>';
        
        echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
            echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
                echo '<form class="form-horizontal" method="POST" action="../script/edit-kurs.php">';  
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                        echo '</div>';
                        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                            echo '<div class="form-group">';
                                echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                                    echo 'Nazwa';
                                echo '</label>';
                                echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                                    echo '<input name="nazwa" type="text" class="input-sm form-control" value="' . $row['nazwa'] . '">';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="form-group">';
                                echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                                    echo 'Cena';
                                echo '</label>';
                                echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                                    echo '<input name="cena" type="text" class="input-sm form-control" value="' . $row['cena'] . '">';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="form-group">';
                                echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                                    echo 'Jazdy';
                                echo '</label>';
                                echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                                    echo '<input name="jazdy" type="text" class="input-sm form-control" value="' . $row['jazdy'] . '">';
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
            $button[1]['href'] = '../page/kurs.php?id=' . $id;

            $this->buttons($button);


        echo '</div>';
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
        
        
        
    
    
}
     
public function settings_wyklady ()
{
    $mysql = connect_mysql();
    
    
    echo '<div style="width: 50%; float:left;">';
        echo '<h2> Miejsca wykładów </h2>';
        $zapytanie_miejsca = 'SELECT id, nazwa FROM wyklady_miejsce ORDER BY nazwa';
        $result_miejsca = $mysql->query($zapytanie_miejsca);
        
        if ($result_miejsca->num_rows > 0)
        {
        echo '<table>';
            while($row_miejsca = $result_miejsca->fetch_array())
            {
            echo '<tr>';
                echo '<td><a href="../page/miejsce-wyklad.php?id=' . $row_miejsca['id'] . '">' . $row_miejsca['nazwa'] . '</a></td>';
            echo '</tr>';
            }
        echo '</table>';    
        }
    echo '</div>';    
    
    echo '<div style="width: 50%; float:left;">';
    echo '</div>';
    

    
    echo '<div class="clear"> </div>';
    

    echo '<div style="width: 50%; float:left;">';
    echo '<h2> <a href="../page/tematy-wyklad.php"> Tematy wykładów </a> </h2>';
    $zapytanie_tematy = 'SELECT id,temat, kategoria FROM tematy_wyklad ORDER BY temat';
    $result_tematy = $mysql->query($zapytanie_tematy);
    if ($result_tematy->num_rows > 0)
    {
    echo '<table>';
        while ($row_tematy = $result_tematy->fetch_array())
        {
        echo '<tr>';
            echo '<td><a href="../page/temat-wyklad.php?id=' . $row_tematy['id'] . '">' . $row_tematy['temat'] . '</a></td>';       
        echo '</tr>';    
        }
    echo '</table>';
    }
    echo '</div>';
    
    echo '<div style="width: 50%; float:left;">';
    echo '</div>';
    echo '<div class="clear"> </div>';
    
    echo '<div style="width: 50%; float:left;">';
    echo '<h2><a href="../page/kategorie-wyklad.php"> Kategoria tematów </a> </h2>';
    $zapytanie_kategoria = 'SELECT id,nazwa FROM kategorie_wyklad ORDER BY nazwa';
    $result_kategoria = $mysql->query($zapytanie_kategoria);
    if ($result_kategoria->num_rows > 0)
    {
    echo '<table>';
        while ($row_kategoria = $result_kategoria->fetch_array())
        {
        echo '<tr>';
            echo '<td><a href="../page/kategoria-wyklad.php?id=' . $row_kategoria['id'] . '">' . $row_kategoria['nazwa'] . '</a></td>';       
        echo '</tr>';    
        }
    echo '</table>';
    }
    echo '</div>';
    
    echo '<div style="width: 50%; float:left;">';
    echo '<h2> Dodaj kategorię wykładów </h2>';
    echo '<form method="POST" action="../script/settings-wyklady.php">';
    echo '<input type="hidden" name="what" value="3">';
        echo '<table style="text-align:center; margin-left:0;">';
            echo '<tr>';
                echo '<td> Nazwa </td>';
                echo '<td> <input type="text" name="kategoria_nazwa"> </td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td colspan="2" style="text-align:center"> <input type="submit" value="Dodaj" style="height:auto; margin-top:10px;"> </td>';
        echo '</tr>';
        echo '</table>';
    echo '</form>';
    echo '</div>';
    
    
    
    
    
}    
    
public function add_user ()
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj kursanta </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/add-user.php">';    
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo 'Dane osobowe';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Pesel<sup>*</sup>';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" oninput="add_uczen_pesel()" id="pesel" name="pesel" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Imię';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="imie" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Nazwisko';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="nazwisko" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data urodzenia';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" onchange="wiek()" name="data_urodzenia" id="data_urodzenia" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wiek';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" disabled id="age" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Płeć';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" id="plec" name="plec">';
                            echo '<option value="1">' . $sex[1] . '</option>';
                            echo '<option value="2">' . $sex[2] . '</option>';
                        echo '</select>';
                    echo '</div>';
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
                        echo '<input type="text" name="telefon" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'E-mail';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="email" name="email" autocomplete="off" class="form-control input-sm">';
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
                        echo '<input type="text" name="pkk" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Kategoria';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="kategoria" class="form-control input-sm">';
                            $zapytanie_kategoria = 'SELECT id, nazwa FROM kategoria_prawo_jazdy WHERE active=1 ORDER BY nazwa';
                            $result_kategoria = $mysql->query($zapytanie_kategoria);
                            while ($row_kategoria = $result_kategoria->fetch_array()) 
                            {
                                if ($row_kategoria['nazwa'] == 'B')
                                {
                                    echo '<option selected value="' . $row_kategoria['id'] . '">' . $row_kategoria['nazwa'] . ' </option>';
                                } else
                                {
                                    echo '<option value="' . $row_kategoria['id'] . '">' . $row_kategoria['nazwa'] . ' </option>';
                                }
                                
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Grupa';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="grupa" class="form-control input-sm">';
                            $zapytanie_grupy = 'SELECT id, nazwa FROM grupy ORDER BY nazwa';
                            $result_grupy = $mysql->query($zapytanie_grupy);
                            while ($row_grupy = $result_grupy->fetch_array()) 
                            {
                                echo '<option value="' . $row_grupy['id'] . '">' . $row_grupy['nazwa'] . ' </option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Rodzaj kursu';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="kurs" onchange="add_user_kurs()" class="form-control input-sm" id="kurs">>';
                            $zapytanie_kurs = 'SELECT id, nazwa, cena, jazdy FROM kursy ORDER BY cena';
                            $result_kurs = $mysql->query($zapytanie_kurs);
                            while ($row_kurs = $result_kurs->fetch_array()) 
                            {
                                echo '<option value="' . $row_kurs['id'] . '">' . $row_kurs['nazwa'] . ' za ' . $row_kurs['cena'] . 'zł </option>';
                            }
                            echo '<option value="0"> Niestandardowy </option>';
                            echo '<option value="-1"> Dodatkowy </option>';
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                    mysqli_data_seek ($result_kurs,0);
                    $x = 0;
                    while ($row_kurs = $result_kurs->fetch_array()) 
                    {
                        if ($x == 0)
                        {
                            $cena = $row_kurs['cena'];
                            $jazdy = $row_kurs['jazdy'];
                        }
                        $x++;
                        echo '<input type="hidden" value="' . $row_kurs['cena'] . '" id="cena-' . $row_kurs['id'] . '">';
                        echo '<input type="hidden" value="' . $row_kurs['jazdy'] . '" id="jazdy-' . $row_kurs['id'] . '">';
                    }
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Jazdy';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" min="0" max="100" name="jazdy" id="jazdy" disabled value="' . $jazdy . '" class="input-sm form-control">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Cena';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" id="cena" name="cena" disabled value="' . $cena . '" class="input-sm form-control">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Etap kursu';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="stopien" class="form-control input-sm" id="etap">';
                            for ($x=1;$x < count($etap);$x++)
                            {
                                echo '<option value="' . $x . '">' . $etap[$x] . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data zapisu';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" name="data_zapisu" value="' . date('Y-m-d') . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Instruktor';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="instruktor" class="form-control input-sm">';
                            $zapytanie_instruktorzy = 'SELECT id, imie, nazwisko FROM instruktorzy ORDER BY nazwisko';
                            $result_instruktorzy = $mysql->query($zapytanie_instruktorzy);
                            while ($row_instruktorzy = $result_instruktorzy->fetch_array())
                            {
                                echo '<option value="' . $row_instruktorzy['id'] . '">' . $row_instruktorzy['imie'] . ' ' . $row_instruktorzy['nazwisko'] . ' </option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wpłata';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="number" min="0" name="wplata" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Miejsce spotkań';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="miejsce" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
            echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Hasło';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="passwd" value="admin" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo 'Ustalenia przy zapisie';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group col-md-11 col-centered">';
                    echo '<textarea class="form-control" name="notatki"> </textarea>';
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
        $button[1]['href'] = '../page/dashboard.php';
    
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
    
public function  add_wplata ($id)
{
    
$mysql = connect_mysql ();
$zapytanie_kurs = 'SELECT uczniowie.cena FROM uczniowie WHERE  uczniowie.id=' . $id;
$result_kurs = $mysql->query($zapytanie_kurs);

$row_kurs = $result_kurs->fetch_array();

$calkowity_koszt = $row_kurs['cena'];   
$zapytanie_dodatkowe = 'SELECT dodatkowe_jazdy.cena FROM dodatkowe_jazdy WHERE dodatkowe_jazdy.uczen=' . $id;

$result_dodatkowe = $mysql->query($zapytanie_dodatkowe);

if ($result_dodatkowe->num_rows > 0)
{
    while ($row_dodatkowe= $result_dodatkowe->fetch_array())
    {
        $calkowity_koszt += $row_dodatkowe['cena'];
    }    
}   
$zapytanie_wplata = 'SELECT kasa FROM wplaty WHERE uczen=' .$id;
$result_wplata = $mysql->query($zapytanie_wplata);
$wplata = 0;
if ($result_wplata->num_rows > 0)
{
    while ($row_wplata = $result_wplata->fetch_array())
    {
        $wplata += $row_wplata['kasa'];
    }
}    
include ('../script/month.php');

echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj wpłatę </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/add-wplata.php">'; 
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                echo 'Wpłacono: ' . $wplata . '/' . $calkowity_koszt;
            echo '</div>';
            echo '<input type="hidden" name="id" value="' . $id  . '">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wpłata';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="number" min="0" name="wplata" class="input-sm form-control">'; 
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Przelew';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="checkbox" name="konto" id="1" class="input-sm form-control">'; 
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
        $button[1]['href'] = '../page/wplaty.php?uczen=' . $id;
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';      

}    
    
    
public function add_user_www ($id)
{
$mysql = connect_mysql();    

$zapytanie = 'SELECT pesel, imie, nazwisko, email, telefon, data_urodzenia, kurs, cena, jazdy, data FROM uczniowie_www WHERE id=' . $id;
$result = $mysql->query($zapytanie);
    
if ($result->num_rows == 0)
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    
$row = $result->fetch_array();    
    
echo '<form method="POST" action="../script/add-user.php">';
    
echo '<h2> Dane osobowe </h2>';  
echo '<table>';
    echo '<tr>';
            echo '<td>';
                echo 'Pesel<sup>*</sup> ';
            echo '</td>';
            echo '<td>';
                    echo '<input onchange="add_uczen_pesel()" oninput="val_pesel(this)" id="pesel" autocomplete="off" type="text" name="pesel" value="' . $row['pesel'] . '">';  
            echo '</td>';
    echo '</tr>';    
    echo '<tr>';
        echo '<td>';
            echo 'Imię';
        echo '</td>';
        echo '<td>';
                echo '<input type="text" oninput="val_name(this)" autocomplete="off" name="imie" value="' . $row['imie'] . '">';  
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td>';
            echo 'Nazwisko';
        echo '</td>';
        echo '<td>';
                echo '<input type="text" oninput="val_name(this)" autocomplete="off" name="nazwisko" value="' . $row['nazwisko'] . '">';  
        echo '</td>';
    echo '</tr>';   
    echo '<tr>';
        echo '<td>';
            echo 'Data urodzenia';
        echo '</td>';
        echo '<td>';
                echo '<input type="date" autocomplete="off" onchange="wiek()" id="data_urodzenia" name="data_urodzenia" value="' . $row['data_urodzenia'] . '">'; 
            echo '<span style="display:none" id="age">  </span>';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td>';
            echo 'Płeć';
        echo '</td>';
        echo '<td>';
            echo '<select name="plec" id="plec">';
                if (isset($_SESSION['add_plec']))
                {       
                    if ($_SESSION['add_plec'] == 1)
                    {
                        echo '<option value="1" selected="selected"> Mężczyzna </option>';
                        echo '<option value="2"> Kobieta </option>'; 
                    } else
                    {
                        echo '<option value="1"> Mężczyzna </option>';
                        echo '<option value="2" selected="selected"> Kobieta </option>';
                    }      
                } else
                {
                    echo '<option value="1"> Mężczyzna </option>';
                    echo '<option value="2"> Kobieta </option>';
                }
            echo '</select>';
        echo '</td>';
    echo '</tr>';
echo '</table>';   
    
echo '<h2> Dane kontaktowe </h2>';  
echo '<table>';
    echo '<tr>';
        echo '<td>';
            echo 'Telefon';
        echo '</td>';
        echo '<td>';
                echo '<input type="text" autocomplete="off" oninput="val_phone(this)" name="telefon" id="phone" value="' . $row['telefon']  . '">';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td>';
            echo 'E-mail';
        echo '</td>';
        echo '<td>';    
                echo '<input type="email" oninput="val_email(this)" autocomplete="off" name="email" value="' . $row['email'] . '">';
        echo '</td>';
    echo '</tr>';   
echo '</table>';    
    

    
    
    
echo '<h2> Kurs </h2>';  
echo '<table>';
    echo '<tr>';
            echo '<td>';
                echo 'Numer PKK';
            echo '</td>';
            echo '<td>';
                if (isset($_SESSION['add_pkk']))
                {
                    echo '<input type="text" id="pkk" name="pkk" autocomplete="off" value="' . $_SESSION['add_pkk']  .'">';
                } else
                {
                    echo '<input type="text" id="pkk" autocomplete="off" name="pkk">';  
                }
            echo '</td>';
    echo '</tr>';    
   echo '<tr>';
        echo '<td>';
            echo 'Rodzaj kursu';
        echo '</td>';
        echo '<td>';
            echo '<select name="kurs" id="kurs" onchange="add_user_kurs()">';
                
                $zapytanie = 'SELECT id, nazwa, cena FROM kursy';
                $result = $mysql->query($zapytanie);
                while ($row = $result->fetch_array())
                { 
                    if ((isset($_SESSION['add_kurs'])) and ($_SESSION['add_kurs'] == $row['id']))
                    {
                        echo '<option selected="selected" value="' . $row['id'] . '">' . $row['nazwa'] . ' za ' . $row['cena'] . 'zł' . '</option>';    
                    } else
                    {
                        echo '<option value="' . $row['id'] . '">' . $row['nazwa'] . ' za ' . $row['cena'] . 'zł' . '</option>';  
                    }
                }
                echo '<option value="0"> Niestandardowy </option>';
                echo '<option value="-1"> Dodatkowy </option>';
            echo '</select>';
        echo '</td>';
    echo '</tr>';
    echo '<tr id="niestandardowy_kurs" style="display:none">';
        echo '<td> </td>';
        echo '<td>';
            echo 'Cena <input type="number" name="cena"> <br>';
            echo 'Jazdy <input type="number" name="jazdy">';
        echo '</td>';
    echo '</tr>'; 
        echo '<tr>';
        echo '<td> Etap </td>';
        echo '<td>';
            echo '<select name="stopien" id="select_stopien">';
                echo '<option value="1" selected> Chodzi na wykłady </option>';
                echo '<option value="2"> Oczekuje na jazdy </option>';
                echo '<option value="3"> Chodzi na jazdy </option>';
                echo '<option value="4"> Oczekuje na egzamin </option>';
            echo '</select>';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td> Data zapisu </td>';
        echo '<td> <input type="date" name="data_zapisu" value="' . date('Y-m-d') . '"> </td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td> Instruktor </td>';
        echo '<td>';
            echo '<select name="instruktor">';
                $zapytanie_instruktor = 'SELECT id, imie, nazwisko FROM instruktorzy';
                $result_instruktor = $mysql->query($zapytanie_instruktor);
                
                while ($row_instruktor = $result_instruktor->fetch_array())
                { 
                    if ((isset($_SESSION['add_instruktor'])) and ($_SESSION['add_instruktor'] == $row_instruktor['id']))
                    {
                        echo '<option selected="selected" value="' . $row_instruktor['id'] . '">' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</option>';  
                    } else
                    {
                        echo '<option value="' . $row_instruktor['id'] . '">' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</option>';
                    }
                }
                
            echo '</select>';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td> Wpłata </td>';
        echo '<td> <input type="number" min="0" name="wplata"> </td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td> Miejsce spotkań </td>';
        echo '<td> <input type="text" name="miejsce"> </td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td> Hasło </td>';
        $liczby = '1234567890';
        $passwd ='';
        for($i=0; $i<8; $i++)
        {
            $passwd.=$liczby{rand(0,strlen($liczby)-1)};
        }
    
        $passwd = 'admin';
        echo '<td>' . $passwd . '</td>';
        echo '<input type="hidden" name="passwd" value="' . $passwd . '">';
    echo '</tr>';
echo '</table>';    
    
echo '<h2> Ustalenia przy zapisie </h2>';  
echo '<textarea name="notatka">';
    
echo '</textarea>';
    
    
}    
    
    
public function PokazJazdyUcznia ($id)
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    $zapytanie_uczen = 'SELECT imie, nazwisko, jazdy FROM uczniowie WHERE id=' . $id;
    $result_uczen = $mysql->query($zapytanie_uczen);
    if ($result_uczen->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    $row_uczen = $result_uczen->fetch_array();
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie jazdy - kursant ' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'] . ' </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-jazdy.php?uczen=' . $id . '">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj jazdy';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, instruktorzy.imie, instruktorzy.nazwisko FROM jazdy,uczniowie,instruktorzy WHERE jazdy.uczen=uczniowie.id AND instruktorzy.id=jazdy.instruktor AND uczniowie.id="' . $id . '" ORDER BY jazdy.data_rozpoczecia DESC';
        $result = $mysql->query($zapytanie);
        $wynik = 0;
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true">';
                echo '<thead>';
                    echo '<th> Data </th>';
                    echo '<th> Godzina </th>';
                    echo '<th> Samochód </th>';
                    echo '<th> Instruktor </th>';
                    echo '<th> Stan </th>';
                    echo '<th data-type="html"> </th>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>';
                                $dzien = date("j", strtotime($row['data_rozpoczecia'])) . ' ' . $month[date("n", strtotime($row['data_rozpoczecia']))] . ' ' . date("Y", strtotime($row['data_rozpoczecia']));
                                echo $dzien;
                            echo '</td>';
                            echo '<td>';
                                $czas = date("H", strtotime($row['data_rozpoczecia'])) . ':' . date("i", strtotime($row['data_rozpoczecia'])) . ' - ' . date("H", strtotime($row['data_zakonczenia'])) . ':' . date("i", strtotime($row['data_zakonczenia']));
                                $start = strtotime($row['data_rozpoczecia']);
                                $koniec = strtotime($row['data_zakonczenia']);
                                $roznica = $koniec-$start;
                                $wynik +=  $roznica;  
                                echo $czas;
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
                                echo $etap_jazdy[$row['stan']];
                            echo '</td>';
                            echo '<td> <a href="../page/jazda.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
                echo '<tfoot>';
                    echo '<tr>';
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
                        echo '<td colspan="6"> Suma: ' . $czas . '/' . $row_uczen['jazdy'] . '</td>';
                    echo '</tr>';
                echo '</tfoot>';
            echo '</table>';
        } else
        {
           echo '<span> Nie ma żadnych jazd </span>';
        }
    echo '</div>';
      
}    
    
public function show_profil_instruktor($id)
{
    
        
$mysql = connect_mysql();
$zapytanie = 'SELECT imie, nazwisko, data_urodzenia, telefon, email FROM instruktorzy WHERE id="' . $id . '"';   
$result = $mysql->query($zapytanie);    
    
if ($result->num_rows == 1)
{
$row = $result->fetch_array();    
    
echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-centered profile">';
    echo '<div class="col-lg-9 col-md-8 col-sm-8 col-xs-12 profile_info">';
        echo '<div class="col-lg-10 col-md-9 col-sm-9 col-xs-12 profile_name" >' . $row['imie'] . ' ' . $row['nazwisko'] . '</div>';
        echo '<a href="../page/instruktor-all-info.php?id=' . $id . '">';
            echo '<div class="col-lg-2 col-md-3 col-sm-3 col-xs-5 profile_button" > Szczegóły </div>';
        echo '</a>';
        $date1 = $row['data_urodzenia'];
        $date2 = date('Y-m-d');
        $diff = abs(strtotime($date2) - strtotime($date1));
        $wiek = floor($diff / (365*60*60*24));
        echo '<div class="col-lg-12 col-md-12 col-xs-12 profile_details"> E-mail: ' . $row['email'] . ' </div>';
        echo '<div class="col-lg-12 col-md-12 col-xs-12 profile_details"> Telefon: ' . $row['telefon'] . ' </div>';
    echo '</div>';
    echo '<div class="col-lg-3 col-md-4 col-sm-4 hidden-xs profile_photo square">';
        echo '<img src="../img/prifile-pic.png" width="100%" height="100%">';
    echo '</div>';
echo '</div>';   
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


    

}    
    
public function show_profil_instruktor_all ($id)
{
    if ($id == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT instruktorzy.pesel, instruktorzy.imie, instruktorzy.nazwisko, instruktorzy.data_urodzenia, instruktorzy.plec, instruktorzy.telefon, instruktorzy.email, instruktorzy.nr_uprawnien FROM instruktorzy WHERE id=' . $id;
    
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 1)
    {
        $row = $result->fetch_array();
        include ('../script/month.php');    
        echo '<table>';
            echo '<tr>';
                echo '<td> Imię </td>';
                echo '<td>' . $row['imie'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Nazwisko </td>';
                echo '<td>' . $row['nazwisko'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Pesel </td>';
                echo '<td>' . $row['pesel'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Płeć </td>';
                echo '<td>' . $sex[$row['plec']] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Data urodzenia </td>';
                echo '<td>' . $row['data_urodzenia'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Telefon </td>';
                echo '<td>' . $row['telefon'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> E-mail </td>';
                echo '<td>' . $row['email'] . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Numer uprawnień </td>';
                echo '<td>' . $row['nr_uprawnien'] . '</td>';
            echo '</tr>';
        
        echo '</table>';
        
        
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
}
    
    

    
public function showPowiadomienia ()
{
    
}
    
public function add_instruktor_pieniadze ($id)
{
if (!isset($id))
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    
$mysql = connect_mysql();    

$zapytanie = 'SELECT aktualnie FROM budzet WHERE instruktor=' . $id . ' ORDER BY data DESC LIMIT 1';
$result = $mysql->query($zapytanie);
if ($result->num_rows == 0)
{
    $portfel = 0;
} else
{
    $row = $result->fetch_array();
    $portfel = $row['aktualnie'];
}
    
echo '<h2> Aktualnie: ' . $portfel . 'zł </h2>';    

echo '<form method="POST" action="../script/add-pieniadze.php">';
echo '<table>';
    echo '<tr>';
        echo '<td> Wpłata </td>';
        echo '<td> <input style="width:auto; height:auto;" type="radio" name="sposob" value="1" checked>  </td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td> Wypłata </td>';
        echo '<td> <input style="width:auto; height:auto;" type="radio" name="sposob" value="2">  </td>';
    echo '</tr>';

    echo '<tr>';
        echo '<td> Instruktor </td>';
        $zapytanie_instruktor = 'SELECT imie, nazwisko FROM instruktorzy WHERE id=' . $id;
        $result_instruktor = $mysql->query($zapytanie_instruktor);
        if ($result_instruktor->num_rows == 0)
        {
            header ("Location: ../page/dashboard.php");
            exit();
        } else
        {
            $row_instruktor = $result_instruktor->fetch_array();
            $instruktor = $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'];
        }
    
        echo '<td>' . $instruktor . '</td>';
        echo '<input type="hidden" name="instruktor" value="' . $id . '">';
    echo '</tr>';
    
    echo '<tr>';
        echo '<td> Kwota </td>';
        echo '<td> <input type="number" step="0.01" name="kwota">  </td>';
    echo '</tr>';
echo '</table>';    
    
    
}
    
public function add_instruktor_pieniadze_kafel()
{
$cube[1][0] = 'done';
    $cube[1][1] = 'Akceptuj';
    $cube[1][2] = '#';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = 'clear';
    $cube[2][1] = 'Anuluj';
    $cube[2][2] = '../page/instruktor.php?id=' . $_GET['id'];
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
    
public function add_miejsce_jazd()
{
    $mysql = connect_mysql();
    echo '<form method="POST" action="../script/add-miejsce.php">';
    echo '<table>';
        echo '<tr>';
            echo '<td> Miejsce </td>';
            echo '<td> <input type="text" name="nazwa"> </td>'; 
        echo '</tr>';
        echo '<tr>';
            echo '<td> Dzielnica </td>';
            echo '<td>';
                echo '<select name="dzielnica">';
                $zapytanie_dzielnica = 'SELECT id, nazwa FROM dzielnica';
                $result_dzielnica = $mysql->query($zapytanie_dzielnica);
                while ($row_dzielnica = $result_dzielnica->fetch_array())
                {
                    echo '<option value="' . $row_dzielnica['id'] . '">' . $row_dzielnica['nazwa'] . '</option>';
                }
                echo '</select>';
            echo '</td>';
        echo '</tr>';
    echo '</table>';
    
}

public function add_miejsce_jazd_kafel()
{
$cube[1][0] = 'done';
    $cube[1][1] = 'Akceptuj';
    $cube[1][2] = '#';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = 'clear';
    $cube[2][1] = 'Anuluj';
    $cube[2][2] = '../page/miejsca.php';
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
    
   
public function show_profil_instruktor_all_kafel ()
{
    

    
    /*
    [][0] - klasa ikony
    [][1] - podpis
    [][2] - strona
    [][3] - kolor
    */    

    $cube[1][0] = 'mode_edit';
    $cube[1][1] = 'Edytuj';
    $cube[1][2] = '../page/edit-instruktor.php?id=' . $_GET['id'];
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = '';
    $cube[2][1] = '';
    $cube[2][2] = '#';
    $cube[2][3] = '#6eb4e5';
    
    $cube[3][0] = 'work';
    $cube[3][1] = 'Pokaż wykłady';
    $cube[3][2] = '../page/wyklady.php?instruktor=' . $_GET['id'];
    $cube[3][3] = '#7dbde9';
    
    $cube[4][0] = 'map';
    $cube[4][1] = 'Pokaż jazdy';
    $cube[4][2] = '../page/jazdy.php?instruktor=' . $_GET['id'];
    $cube[4][3] = '#8cc3ea';
    
    
    $cube[5][0] = 'account_balance_wallet';
    $cube[5][1] = 'Pokaż wydatki';
    $cube[5][2] = '../page/show_portfel.php?id=' . $_GET['id'];
    $cube[5][3] = '#99cbee';
    
    $cube[6][0] = 'library_add';
    $cube[6][1] = 'Dodaj/Usuń pieniadze';
    $cube[6][2] = '../page/add-portfel.php?id=' . $_GET['id'];
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
    
public function edit_profil_instruktor($id)
{
$mysql = connect_mysql();    
$zapytanie = 'SELECT instruktorzy.pesel, instruktorzy.imie, instruktorzy.nazwisko, instruktorzy.plec, instruktorzy.data_urodzenia, instruktorzy.telefon, instruktorzy.email, instruktorzy.nr_uprawnien FROM instruktorzy WHERE instruktorzy.id="' . $id . '"';
    
$result = $mysql->query($zapytanie);    
    
if ($result->num_rows == 1)
{
    
$row = $result->fetch_array();
    
    echo '<form method="POST" action="../script/edit-instruktor.php">';
        echo '<table>';
            echo '<tr>';
                echo '<td> Pesel </td>';
                echo '<td>' . $row['pesel'] . '</td>';
                echo '<input type="hidden" name="pesel" value="' . $row['pesel'] . '">';
            echo '</tr>'; 
            echo '<tr>';
                echo '<td> Imię </td>';
                echo '<td> <input type="text" name="imie" value="' . $row['imie'] . '"> </td>';
            echo '</tr>';   
            echo '<tr>';
                echo '<td> Nazwisko </td>';
                echo '<td> <input type="text" name="name" value="' . $row['nazwisko'] . '"> </td>';
            echo '</tr>';   
            echo '<tr>';
                echo '<td> Płeć </td>';
                echo '<td>';
                        echo '<select name="sex">';
                            if ($row['plec'] ==1)
                            {
                                echo '<option selected value="1"> Mężczyzna </option>';
                                echo '<option value="2"> Kobieta </option>';
                            } else
                            {
                                echo '<option value="1"> Mężczyzna </option>';
                                echo '<option selected value="2"> Kobieta </option>';
                            }
                        echo '</select>';
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Data urodzenia </td>';
                echo '<td> <input type="date" name="data_urodzenia" value="' . $row['data_urodzenia'] . '"> </td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Telefon </td>';
                echo '<td> <input type="text" name="phone" value="' . $row['telefon'] . '"> </td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> E-mail </td>';
                echo '<td> <input type="email" name="email" value="' . $row['email'] . '"> </td>';
            echo '</tr>';   
            echo '<tr>';
                echo '<td> Numer uprawnień </td>';
                echo '<td> <input type="text" name="uprawnienia" value="' . $row['nr_uprawnien'] . '"> </td>';
            echo '</tr>';  
        echo '</table>';   
}    
}
    
public function edit_profil_instruktor_kafel()
{
    /*
    [][0] - klasa ikony
    [][1] - podpis
    [][2] - strona
    [][3] - kolor
    */    
    
    $cube[1][0] = 'done';
    $cube[1][1] = 'Akceptuj';
    $cube[1][2] = '#';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = 'clear';
    $cube[2][1] = 'Anuluj';
    $cube[2][2] = '../page/instruktor-all-info.php?id=' . $_GET['id'];
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
    
public function show_uczen_wplaty ($id)
{

    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie wpłaty </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-wplata.php?id=' . $id . '">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj wpłate';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';    
        $zapytanie = 'SELECT wplaty.id, wplaty.kasa, wplaty.kasa, instruktorzy.imie, instruktorzy.nazwisko, wplaty.data FROM instruktorzy,wplaty,uczniowie WHERE wplaty.instruktor=instruktorzy.id AND wplaty.uczen=uczniowie.id AND uczniowie.id=' . $id . ' ORDER BY data DESC';
        $result = $mysql->query($zapytanie);
        if ($result->num_rows > 0)
        {
            echo '<table class="footer table">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Wpłata </th>';
                        echo '<th> Instruktor </th>';
                        echo '<th data-type="html"> </th>';
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
                            echo '<td> <a href="../page/wplata.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';  
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
    
public function show_portfel($id)
{
    if (isset($_GET['id']))
    {
    
        $mysql = connect_mysql();
        
        $zapytanie_budzet = 'SELECT date(data) AS dzien, count(*) AS ile FROM budzet WHERE instruktor="' . $id . '" GROUP BY dzien ORDER BY dzien DESC';
        
        $result_budzet = $mysql->query($zapytanie_budzet);
        
        if ($result_budzet->num_rows == 0)
        {
            echo '<span> Nie ma żadnych aktywności portfela </span>';
        } else
        {
            while ($row_budzet = $result_budzet->fetch_array())
            {
                include ('../script/month.php');
                $data = $row_budzet['dzien'];
                $data_dzien = date("j", strtotime($data));
                $data_miesiac = $month[date("n", strtotime($data))];
                $data_rok = date("Y", strtotime($data));
                $data_pelna = $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok;
                echo '<h2>' . $data_pelna . '</h2>';
                $zapytanie_dzien = 'SELECT id,data,kwota,wczesniej,aktualnie,status,id_status FROM budzet WHERE instruktor="' . $id . '" AND date(data)="' . $data . '" ORDER BY data DESC';
                $result_dzien = $mysql->query($zapytanie_dzien);
                if ($result_dzien->num_rows != 0)
                {
                    echo '<table>';
                        while ($row_dzien = $result_dzien->fetch_array())
                        {
                            echo '<tr>';
                            echo '<td>';
                            $id_portfel = $row_dzien['id'];
                            $data = $row_dzien['data'];
                            $kwota = $row_dzien['kwota'];
                            $wczesniej = $row_dzien['wczesniej'];
                            $aktualnie = $row_dzien['aktualnie'];
                            $status = $row_dzien['status'];
                            $id_status = $row_dzien['id_status'];
                            switch ($status)
                            {
                                case 1:
                                    {
                                    // WPŁATA UCZNIA
                                    $zapytanie_uczen = 'SELECT imie, nazwisko FROM uczniowie WHERE id=' . $id_status;
                                    $result_uczen = $mysql->query($zapytanie_uczen);
                                    $row_uczen = $result_uczen->fetch_array();
                                    $uczen = $row_uczen['imie'] . ' ' . $row_uczen['nazwisko']; 
                                    echo 'Uczen ' . $uczen . ' wpłacił ' . $kwota . 'zł ';
                                    break;
                                    }
                                case 2:
                                    {
                                    // WPŁATA PRZEZ ADMINA
                                    $zapytanie_admin = 'SELECT imie, nazwisko FROM administratorzy WHERE id=' . $id_status;
                                    $result_admin = $mysql->query($zapytanie_admin);
                                    $row_admin = $result_admin->fetch_array();
                                    $admin = $row_admin['imie'] . ' ' . $row_admin['nazwisko'];
                                    echo 'Administrator ' . $admin . ' wpłacił ' . $kwota . 'zł';
                                    break;
                                    }
                                case 100:
                                    {
                                    // ZAPŁATA ZA PALIWO
                                    $zapytanie_samochod = 'SELECT samochody.rejestracja, samochody.marka, samochody.model FROM samochody, paliwo WHERE samochody.id=paliwo.samochod AND paliwo.id=' . $id_status;
                                    $result_samochod = $mysql->query($zapytanie_samochod);
                                    $row_samochod = $result_samochod->fetch_array();
                                    $samochod = $row_samochod['rejestracja'];
                                    echo 'Zatankował ' . $samochod  . ' za ' . $kwota . 'zł';
                                    break;
                                    }
                                case 102:
                                    {
                                    // WPŁACENIE ADMINOWI
                                    $zapytanie_admin = 'SELECT imie, nazwisko FROM administratorzy WHERE id=' . $id_status;
                                    $result_admin = $mysql->query($zapytanie_admin);
                                    $row_admin = $result_admin->fetch_array();
                                    $admin = $row_admin['imie'] . ' ' . $row_admin['nazwisko'];
                                    echo 'Przekazał ' . $kwota . 'zł administratorowi ' . $admin;
                                    break;
                                    }
                            }
                            
                            echo '</td>';
                            echo '</tr>';
                        }
                    echo '</table>';
                }
            }
        }
        
    } else
    {
    header ("Location: ../page/dashboard.php");
    exit();
    }
}
    
        
    
public function add_jazda ($id)
{
     
$mysql = connect_mysql ();
include ('../script/month.php');

echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj jazdę </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/add-jazdy.php">';   
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Opcja';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="option" class="input-sm form-control">';
                            for ($x=1;$x<=count($etap_jazdy);$x++)
                            {
                                echo '<option value="' . $x . '">' . $etap_jazdy[$x] . '</option>';
                            }
    
                        echo '</select>';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" value="' . date('Y-m-d') . '" name="data" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Godzina - start';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" name="start_jazda" value="' . date('H:i') . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Godzina - koniec';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" name="end_jazda" value="' . date('H:i') . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Kursant';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="id" class="form-control input-sm">';
                            $zapytanie_uczen = 'SELECT id, imie, nazwisko FROM uczniowie ORDER BY nazwisko';
                            $result_uczen = $mysql->query($zapytanie_uczen);
                            while ($row_uczen =  $result_uczen->fetch_array())
                            {
                                if ($id == $row_uczen['id'])
                                {
                                    echo '<option selected value="' . $row_uczen['id'] . '">' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'] . '</option>';   
                                } else
                                {
                                    echo '<option value="' . $row_uczen['id'] . '">' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'] . '</option>';
                                }    
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Instruktor';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="instruktor" class="form-control input-sm">';
                            if ($id != 0)
                            {
                                $zapytanie_uczen = 'SELECT instruktorzy WHERE id=' . $id;
                                $resutl_uczen = $mysql->query($zapytanie_uczen);
                                if ($result_uczen->num_rows == 0)
                                {
                                    header ("Location: ../page/add-jazdy.php");
                                    exit();
                                }
                                $ow_uczen = $result_uczen->fetch_array();
                                
                            }
                            $zapytanie_instruktor = 'SELECT id, imie, nazwisko FROM instruktorzy ORDER BY nazwisko';
                            $result_instruktor = $mysql->query($zapytanie_instruktor);
                            while ($row_instruktor =  $result_instruktor->fetch_array())
                            {
                                if ($row_uczen['instruktorzy'] == $row_instruktorzy['id'])
                                {
                                    echo '<option selected value="' . $row_instruktor['id'] . '">' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</option>';    
                                } else
                                {
                                    echo '<option value="' . $row_instruktor['id'] . '">' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'] . '</option>';
                                }
                                
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Samochód';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="samochod" class="form-control input-sm">';
                            echo '<option value="0"> Nie wybrałem samochodu </option>';
                            $zapytanie_car = 'SELECT id, rejestracja, marka, model FROM samochody';
                            $result_car = $mysql->query($zapytanie_car);
                            while ($row_car = $result_car->fetch_array())
                            {
                                echo '<option value="' . $row_car['id'] . '"> ' . $row_car['marka'] . ' ' . $row_car['model'] . ' - ' . $row_car['rejestracja'] . ' </option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                echo 'Notatka';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';   
                echo '<textarea name="notatka" style="width:100%;height:200px"> </textarea>';
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
        $button[1]['href'] = '../page/dashboard.php';
    
        $this->buttons($button);
            
    
    echo '</div>';
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
        $zapytanie = 'SELECT jazdy.notatka,jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia, jazdy.stan, uczniowie.imie, uczniowie.nazwisko,uczniowie.id as uczen_id, instruktorzy.id as instruktor_id, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, jazdy.samochod FROM jazdy, uczniowie, instruktorzy WHERE jazdy.uczen=uczniowie.id AND  instruktorzy.id=jazdy.instruktor AND jazdy.id=' . $id;
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
                    echo $data . ' ' . $czas;
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td> Stan </td>';
                echo '<td>' . $etap_jazdy[$row['stan']] . '</td>';
            echo '</tr>';      
            echo '<tr>';
                echo '<td> Uczeń </td>';
                echo '<td> <a href="../page/uczen.php?id=' . $row['uczen_id'] . '">' . $row['imie'] . ' ' . $row['nazwisko'] . '</a></td>';
            echo '<tr>';     
            echo '<tr>';
                echo '<td> Instruktor </td>';
                echo '<td> <a href="../page/instruktor.php?id=' . $row['instruktor_id'] . '">' . $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'] . '</a></td>';
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
                echo '<td> <a href="../page/samochod.php?id=">' . $samochod . ' </a> </td>';
            echo '</tr>';
        echo '</table>';
        echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
            echo '<h3> Notatka </h3>';        
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
        $button[0]['href'] = '../page/edit-jazda.php?id=' . $id;
    
        $button[1]['typ'] = 'normal';
        $button[1]['cat_icon'] = 'material-icons';
        $button[1]['icon'] = 'delete_forever';
        $button[1]['text'] = 'Usuń jazdę';
        $button[1]['href'] = '../script/delete-jazda.php?id=' . $id;
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>'; 
    
} 
      
    
public function edit_jazda ($id)
{
   
$mysql = connect_mysql ();
include ('../script/month.php');
    
$zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia,jazdy.notatka, jazdy.data_zakonczenia, jazdy.stan,uczniowie.id as uczen_id, uczniowie.imie, uczniowie.nazwisko, instruktorzy.show_car, jazdy.samochod FROM jazdy, uczniowie, instruktorzy WHERE jazdy.uczen=uczniowie.id AND  instruktorzy.id=jazdy.instruktor AND jazdy.id=' . $id;
$result = $mysql->query($zapytanie);
$row = $result->fetch_array();
$date = date("Y-m-d", strtotime($row['data_rozpoczecia']));
$start_czas = date("H:i", strtotime($row['data_rozpoczecia']));   
$koniec_czas = date("H:i", strtotime($row['data_zakonczenia']));   
    

echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Edytuj jazdę </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/edit-jazda.php">';   
            echo '<input type="hidden" value="' . $row['id'] . '" name="id">';        
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input name="date" type="date" class="input-sm form-control" value="' . $date . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Godzina - start';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" name="start_time" class="input-sm form-control" value="' . $start_czas . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Godzina - koniec';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" name="end_time" class="input-sm form-control" value="' . $koniec_czas . '">';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Stan';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="stan" class="form-control input-sm">';
                            for ($x=1;$x<=count($etap_jazdy);$x++)
                            {
                                if ($x == $row['stan'])
                                {
                                    echo '<option selected value="' . $x . '">' . $etap_jazdy[$x] . '</option>';    
                                } else
                                {
                                    echo '<option value="' . $x . '">' . $etap_jazdy[$x] . '</option>';
                                }
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Kursant';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo $row['imie'] . ' ' . $row['nazwisko'];
                    echo '</div>';
                echo '</div>';   
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Samochód';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="car" class="input-sm form-control">';
                            $zapytanie_car = 'SELECT id,nazwa, rejestracja, marka, model FROM samochody';
                            $result_car = $mysql->query($zapytanie_car);    
                            echo '<option value="0"> Nie wybrano </option>';
                            while ($row_car = $result_car->fetch_array())
                            {
                                if ($row['show_car'] == 0)
                                {
                                    $samochod = $row_car['rejestacja'];
                                } else
                                {
                                    $samochod = $row_car['nazwa'];
                                }
                                if ($row_car['id'] == $row['samochod'])
                                {
                                    echo '<option selected value="' . $row_car['id'] . '">' . $samochod . '</option>';
                                } else
                                {
                                    echo '<option value="' . $row_car['id'] . '">' . $samochod . '</option>';
                                }        
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                    echo 'Notatka';
                echo '</div>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';   
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
    $button[1]['href'] = '../page/jazda.php?id=' . $id;

    $this->buttons($button);


echo '</div>';    
     

    
    
    
}
         
    
public function PokazWszystkieSamochody ()
{
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT samochody.id,samochody.nazwa, samochody.rejestracja, samochody.marka, samochody.model, samochody.skrzynia FROM samochody';
    
    $result = $mysql->query($zapytanie);
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie samochody </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-samochod.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj samochód';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Rejestracja </th>';
                        echo '<th> Nazwa </th>';
                        echo '<th> Marka </th>';
                        echo '<th> Model </th>';
                        echo '<th> Skrzynia </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td>';
                                echo $row['rejestracja'];
                            echo '</td>';
                            echo '<td>';
                                echo $row['nazwa'];
                            echo '</td>';
                            echo '<td>';
                                echo $row['marka'];
                            echo '</td>';
                            echo '<td>';
                                echo $row['model'];
                            echo '</td>';
                            echo '<td>';
                                echo $car_skrzynia[$row['skrzynia']];
                            echo '</td>';  
                            echo '<td> <a href="../page/samochod.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';            
                        echo '</tr>';    
                    }
            
                echo '</tbody>';
            echo '</table>';
        } else
        {
            echo '<span> Nie dodano żadnego samochodu </span>';
            exit();
        }
    echo '</div>';

}
    
public function samochod ($id)
{

    $mysql = connect_mysql();    
    include ('../script/month.php');   
    
    $zapytanie = 'SELECT nazwa,rejestracja, marka, model, data_produkcji, silnik, skrzynia, paliwo FROM samochody WHERE id=' . $id;
    $result = $mysql->query($zapytanie);
    
    
    if ($result->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
    } else
    {
    $row = $result->fetch_array();
    // TYTUŁ    
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Samochód </h3>';
    echo '</div>';

    echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
        // KARTKA
        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';    
            echo '<table class="table">';
                echo '<tr>';
                    echo '<td> Nazwa </td>';
                    echo '<td>' . $row['nazwa'] . '</td>';
                echo '</tr>';
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
                            $data_dzien = date("j", strtotime($data));
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
                        echo $car_skrzynia[$row['skrzynia']];
                    echo '</td>';
                echo '<tr>';
                    echo '<td> Paliwo </td>';
                    echo '<td>' . $row['paliwo'] . '</td>';
                echo '</tr>';
            echo '</table>';
        echo '</div>';
        // PRZYCISKI
        echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 buttons">';
            // TYP: BUTTON LUB NORMAL
            // IKONA Z MATERIAL ICONS, FONT AWESOME BĄDŹ GLYPHICONS
            $button[0]['typ'] = 'normal';
            $button[0]['cat_icon'] = 'material-icons';
            $button[0]['icon'] = 'mode_edit';
            $button[0]['text'] = 'Edytuj';
            $button[0]['href'] = '../page/edit-samochod.php?id=' . $id;

            $button[1]['typ'] = 'clear';
            $button[1]['cat_icon'] = '';
            $button[1]['icon'] = '';
            $button[1]['text'] = '';
            $button[1]['href'] = '';

            $this->buttons($button);
        echo '</div>';
    echo '</div>';

    }
  
}
    
public function edit_samochod ($id)
{
    
    
$mysql = connect_mysql ();
include ('../script/month.php');
    

$zapytanie = 'SELECT nazwa,rejestracja, marka, model, data_produkcji, silnik, skrzynia, paliwo FROM samochody WHERE id=' . $id;    
$result = $mysql->query($zapytanie);    
    
if ($result->num_rows == 1)
{
$row = $result->fetch_array();    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Edytuj samochód </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/edit-samochod.php">';   
            echo '<input type="hidden" value="' . $id . '" name="id">';        
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Nazwa';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input name="nazwa" type="text" class="input-sm form-control" value="' . $row['nazwa'] . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Rejestracja';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="rejestracja" class="input-sm form-control" value="' . $row['rejestracja'] . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Marka';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="marka" class="input-sm form-control" value="' . $row['marka'] . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Model';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="model" class="input-sm form-control" value="' . $row['model'] . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data produkcji';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="data" name="data_produkcji" class="input-sm form-control" value="' . $row['data_produkcji'] . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Silnik';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="silnik" class="input-sm form-control" value="' . $row['silnik'] . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Skrzynia';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="skrzynia" class="form-control input-sm">';
                            for ($x=1;$x<=count($car_skrzynia);$x++)
                            {
                                echo '<option value="'.  $x . '">' . $car_skrzynia[$x] . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Paliwo';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="paliwo" class="input-sm form-control" value="' . $row['paliwo'] . '">';
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
    $button[1]['href'] = '../page/samochod.php?id=' . $id;

    $this->buttons($button);


echo '</div>';   
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}
}
   
     
    
public function add_samochod()
{
    
$mysql = connect_mysql ();
include ('../script/month.php');

echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj samochód </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/add-samochod.php">';   
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Nazwa';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="nazwa" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Rejestracja';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="rejestracja" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Marka';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="marka" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';   
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Model';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="model" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data produkcji';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" name="data_produkcji" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Silnik';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="silnik" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Skrzynia';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="skrzynia" class="input-sm form-control">';
                            for ($x=1;$x<=count($car_skrzynia);$x++)
                            {
                                echo '<option value="' . $x . '">' . $car_skrzynia[$x] . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';   
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Skrzynia';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="paliwo" class="form-control input-sm">';
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
            $button[1]['href'] = '../page/samochody.php';

            $this->buttons($button);


        echo '</div>';       
 
}
    
    
public function add_instruktor ()
{

$mysql = connect_mysql ();
include ('../script/month.php');
    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj instruktora </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/add-instruktor.php">';    
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo 'Dane osobowe';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Pesel<sup>*</sup>';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="pesel" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Numer uprawnień';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="uprawnienia" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Imię';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="imie" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Nazwisko';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="nazwisko" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data urodzenia';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" name="data_urodzenia" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';   
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Telefon';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="telefon" id="phone" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'E-mail';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="email" name="email" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';        
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Płeć';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="plec" class="form-control input-sm">';
                            for ($x=1;$x<=count($sex);$x++)
                            {
                                echo '<option value="' . $x . '">' . $sex[$x] . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data zatrudnienia';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" class="input-sm form-control" name="data_zatrudnienia" value="' . date('Y-m-d') . '">';
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
        $button[1]['href'] = '../page/instruktorzy.php';
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';       
}

        
public function add_temat_wykladu ()
{

$mysql = connect_mysql ();
include ('../script/month.php');
    
echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj temat wykładu </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="GET" action="../script/add-temat.php">';    
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Temat';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text"  name="temat" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Kategoria';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="kategoria" class="form-control input-sm">';
                        $zapytanie = 'SELECT id, nazwa FROM kategorie_wyklad';
                        $result = $mysql->query($zapytanie);
                        while ($row = $result->fetch_array())
                        {
                            echo '<option value="' . $row['id'] . '">' . $row['nazwa'] . '</option>';
                        }    
                        echo '</select>';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Czas trwania';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="time" name="czas" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo 'Opis';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo '<textarea name="notatka" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> </textarea>';
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
        $button[1]['href'] = '../page/tematy-wyklad.php';
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';
    
    

    
    
}
    
public function add_kategorie_wykladu ()
{
    echo '<form method="GET" action="../script/add-kategoria.php">';
    $mysql = connect_mysql();
    echo '<table>';
        echo '<tr>';
            echo '<td>';
                echo 'Nazwa';
            echo '</td>';
            echo '<td>';
                echo '<input type="text" name="kategoria">';
            echo '</td>';
        echo '</tr>';
    echo '</table>';
    
    
}    
    
    
    
public function add_kategorie_wykladu_kafel()
{
    /*
    [][0] - klasa ikony
    [][1] - podpis
    [][2] - strona
    [][3] - kolor
    */    

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
    
echo '<h3 class="col-lg-8 col-lg-offset-1 col-md-11 col-md-offset-1 col-sm-11 col-sm-offset-1 col-xs-11 col-xs-offset-1 temat-wykladu-title">' . $row['temat'] . '</h3>';
    
// PRZYCISK
echo '<a href="../page/edit-temat-wykladu.php?id=' . $id . '">';
    echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
        echo 'Edytuj';
    echo '</div>';
echo '</a>';    
    
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
    
public function edit_temat_wyklad ($id)
{
    
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT tematy_wyklad.temat, tematy_wyklad.kategoria, tematy_wyklad.notatka, tematy_wyklad.czas FROM tematy_wyklad WHERE tematy_wyklad.id=' . $id;
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 0)
    {
        header ("Location: ../page/dashbboard.php");
        exit();
    }
    
    $row = $result->fetch_array();

    echo '<form method="POST" action="../script/edit-temat-wyklad.php">';
    echo '<input type="hidden" name="id" value="' . $id . '">';
    
    // TYTUŁ
    echo '<div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-10 col-xs-8 input-title title">';
        echo '<input type="text" class="form-control input-md" name="temat" value="' . $row['temat'] . '">';
    echo '</div>';
    
    // PRZYCISK
    echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button_submit text-center">';
        echo '<input type="submit" class="edit-temat-submit" value="Zapisz">';
    echo '</div>';
    
    
    
    
    echo '<div class="form-group col-lg-10 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12 edit-temat-input">';
        echo '<label for="category"> Kategoria </label>';
        $zapytanie_kategoria = 'SELECT id,nazwa FROM kategorie_wyklad';
        $result_kategoria = $mysql->query($zapytanie_kategoria);
        echo '<select name="kategoria" id="category" class="form-control input-md">';
            while ($row_kategoria = $result_kategoria->fetch_array())
            {
                if ($row_kategoria['id'] == $row['kategoria'])
                {
                echo '<option selected value="' . $row_kategoria['id'] . '">' . $row_kategoria['nazwa'] . '</option>';   
                } else
                {
                echo '<option value="' . $row_kategoria['id'] . '">' . $row_kategoria['nazwa'] . '</option>';
                }
            }
        echo '</select>';
    echo '</div>';   
    
    echo '<div class="form-group col-lg-10 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12 edit-temat-input">';
        echo '<label for="czas"> Czas trwania </label>';
        echo '<input type="time" class="form-control input-md" name="czas" id="time" value="' . $row['czas'] . '">';
    echo '</div>';     
    
    echo '<div class="form-group col-lg-10 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12 edit-temat-input">';
        echo '<label for="opis"> Opis </label>';
         echo '<textarea name="notatka" class="form-control input-md">';
            echo $row['notatka'];
        echo '</textarea>';
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
    
    // PRZYCISK
    echo '<a href="../page/add-temat.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj temat';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        $zapytanie_kategoria = 'SELECT kategorie_wyklad.id,kategorie_wyklad.nazwa FROM kategorie_wyklad ORDER BY kategorie_wyklad.id';
        $result_kategoria = $mysql->query($zapytanie_kategoria);
        if ($result_kategoria->num_rows > 0)
        {
            while ($row_kategoria = $result_kategoria->fetch_array())
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
        } else
        {
            echo '<span> Nie dodano żadnej kategorii i tematu </span>';
        }
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

    
public function PokazWszystkieKategorieWykladow ()
{

$mysql = connect_mysql();

$zapytanie = 'SELECT kategorie_wyklad.id, kategorie_wyklad.nazwa FROM kategorie_wyklad ORDER BY id';
$result = $mysql->query($zapytanie);    
 if ($result->num_rows == 0)
 {
     echo '<span> Nie ma żadnych kategorii </span>';
 } else
 {
    echo '<table>';
    echo '<thead>';
        echo '<tr>';
            echo '<td> Nazwa </td>';
            echo '<td> Ilość tematów </td>';
        echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    while ($row = $result->fetch_array())
    {
            $zapytanie_tematy = 'SELECT count(id) as ile FROM tematy_wyklad WHERE kategoria=' . $row['id'];
            $result_tematy = $mysql->query($zapytanie_tematy);
            $row_tematy = $result_tematy->fetch_array();
            $ilosc_tematow = $row_tematy['ile'];
        
            $file = '../page/kategoria-wyklad.php?id=' . $row['id'];     
            echo '<tr  onclick="document.location =\'' . $file . '\';">';
            echo '<td>';
                echo $row['nazwa'];
            echo '</td>';
            echo '<td>';
                echo $ilosc_tematow;
            echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
 }
    

}
    
public function show_profil_uczen_all($id)
{
    include ('../script/month.php');
    $mysql = connect_mysql();
    $zapytanie = 'SELECT uczniowie.pesel,uczniowie.notatka, uczniowie.pkk, uczniowie.imie, uczniowie.nazwisko, uczniowie.plec, uczniowie.data_urodzenia,uczniowie.cena, uczniowie.jazdy, uczniowie.telefon,uczniowie.data_zapisu, uczniowie.email,uczniowie.stan,uczniowie.data_zapisu, uczniowie.kurs, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, uczniowie.miejsce_spotkan, kategoria_prawo_jazdy.skrot FROM uczniowie, instruktorzy, kategoria_prawo_jazdy WHERE kategoria_prawo_jazdy.id=uczniowie.kategoria AND uczniowie.instruktor=instruktorzy.id  AND uczniowie.id=' . $id;
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
                $button[0]['href'] = '../page/edit-uczen.php?id=' . $id;

                $button[1]['typ'] = 'normal';
                $button[1]['cat_icon'] = 'material-icons';
                $button[1]['icon'] = 'change_history';
                $button[1]['text'] = 'Historia';
                $button[1]['href'] = '../page/history.php?uczen=' . $id;

                $this->buttons($button);
            echo '</div>';
        echo '</div>'; 
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
}
    
public function historia_ucznia ($id)
{
    include ('../script/month.php');
    $mysql = connect_mysql();
    $x = 0;
    $y = 0;
    
    
    $zapytanie_uczen = 'SELECT data_zapisu FROM uczniowie WHERE id=' . $id;
    $result_uczen = $mysql->query($zapytanie_uczen);
    if ($result_uczen->num_rows != 1)
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    $row_uczen = $result_uczen->fetch_array();
    $all[$y]['id'] = $x;
    $all[$y]['data'] = $row_uczen['data_zapisu'];
    $all[$y]['what'] = 4;
    $y++; 
    
    $zapytanie_dodatkowe = 'SELECT dodatkowe_jazdy.id,dodatkowe_jazdy.data, dodatkowe_jazdy.jazdy, dodatkowe_jazdy.cena FROM dodatkowe_jazdy WHERE dodatkowe_jazdy.uczen=' . $id;
    $result_dodatkowe = $mysql->query($zapytanie_dodatkowe);

    while ($row_dodatkowe = $result_dodatkowe->fetch_array())
    {
        $dodatkowe [$x]['id'] = $row_dodatkowe['id'];
        $dodatkowe [$x]['data'] = $row_dodatkowe['data'];
        $dodatkowe [$x]['jazdy'] = $row_dodatkowe['jazdy'];
        $dodatkowe [$x]['cena'] = $row_dodatkowe['cena'];
        
        $all[$y]['id'] = $x;
        $all[$y]['data'] = $row_dodatkowe['data'];
        $all[$y]['what'] = 1;
        $y++;
        $x++;
    }
    
    $zapytanie_wyklad = 'SELECT wyklady.data, wyklady.id FROM wyklady, obecnosc WHERE wyklady.id=obecnosc.wyklad AND obecnosc.uczen=' . $id;
    $result_wyklad = $mysql->query($zapytanie_wyklad);
    $x = 0;
    while ($row_wyklad = $result_wyklad->fetch_array())
    {
        $wyklad [$x]['id'] = $row_wyklad['id'];
        $wyklad [$x]['data'] = $row_wyklad['data'];
        
        $all[$y]['id'] = $x;
        $all[$y]['data'] = $row_wyklad['data'];
        $all[$y]['what'] = 2;
        $y++;
        $x++;
    }
    
    $zapytanie_jazdy = 'SELECT id,data_rozpoczecia, data_zakonczenia, stan FROM jazdy WHERE uczen=' . $id;
    $result_jazdy = $mysql->query($zapytanie_jazdy);
    $x = 0;
    while ($row_jazdy = $result_jazdy->fetch_array())
    {
        $jazda [$x]['id'] = $row_jazdy['id'];
        $jazda [$x]['data_start'] = $row_jazdy['data_rozpoczecia'];
        $jazda [$x]['data_koniec'] = $row_jazdy['data_zakonczenia'];
        $jazda [$x]['stan'] = $row_jazdy['stan'];
        
        $all[$y]['id'] = $x;
        $all[$y]['data'] = $row_jazdy['data_rozpoczecia'];
        $all[$y]['what'] = 3;
        $y++;
        $x++;
    }
    
    $zapytanie_wplata = 'SELECT id, kasa, data FROM wplaty WHERE uczen=' . $id;
    $result_wplata = $mysql->query($zapytanie_wplata);
    $x = 0;
    while ($row_wplata = $result_wplata->fetch_array())
    {
        $wplata[$x]['id'] =  $row_wplata['id'];
        $wplata[$x]['data'] = $row_wplata['data'];
        $wplata[$x]['kasa'] = $row_wplata['kasa'];
        
        $all[$y]['id'] = $x;
        $all[$y]['data'] = $row_wplata['data'];
        $all[$y]['what'] = 5;    
        $y++;
        $x++;
    }
    

    
    
    
    function sortFunction( $a, $b ) 
    {
    return strtotime($a["data"]) - strtotime($b["data"]);
    }
    
    usort ($all,"sortFunction");
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Cała historia ucznia </h3>';
    echo '</div>';
    
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        echo '<table class="table footer">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th> Data </th>';
                    echo '<th> Wydarzenie </th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                foreach ($all as $key => $value)
                {
                    switch ($value['what'])
                        {
                            case 1:
                                {
                                    echo '<tr>';
                                        echo '<td>' . $value['data'] . '</td>';
                                        echo '<td> Dodatkowe jazdy - cena ' . $dodatkowe[$value['id']]['cena'] . ' PLN - jazdy ' . $dodatkowe[$value['id']]['jazdy'] .  'H </td>';
                                    echo '</tr>';
                                    break;
                                }
                            case 2:
                                {
                                echo '<tr>';
                                        echo '<td>' . $value['data'] . '</td>';
                                        echo '<td> Wykład </td>';
                                    echo '</tr>';
                                    break;
                                break;
                                }
                            case 3:
                                {
                                    echo '<tr>';
                                        echo '<td>' . $value['data'] . ' ' . $jazda[$value['id']]['data_koniec'] . '</td>';
                                        echo '<td> Jazda - ' . $etap_jazdy[$jazda[$value['id']]['stan']] . '</td>';
                                    echo '</tr>';
                                    break;
                                }
                            case 4:
                                {
                                    echo '<tr class="info">';
                                        echo '<td>' . $value['data'] . '</td>';
                                        echo '<td> Dodanie kursanta </td>';
                                    echo '</tr>';
                                    break;
                                }
                            case 5:
                                {
                                    echo '<tr>';
                                        echo '<td>' . $value['data'] . '</td>';
                                        echo '<td> Wpłata - ' . $wplata[$value['id']]['kasa'] . 'PLN  </td>';
                                    echo '</tr>';
                                    break;
                                break;
                                }
                        }
                    
                    echo '</tr>';
                }
    
            echo '</tbody>';
        echo '</table>';
    echo '</div>';
}
    

    
public function PokazWszystkieMiejsca ()
{
    $mysql = connect_mysql();
    
    $zapytanie_dzielnica = 'SELECT id, nazwa FROM dzielnica';
    $result_dzielnica = $mysql->query($zapytanie_dzielnica);
    while ($row_dzielnica = $result_dzielnica->fetch_array())
    {
        echo '<h2>' . $row_dzielnica['nazwa'] . '</h2>';  
        $dzielnica = $row_dzielnica['id'];
        $zapytanie_miejsca = 'SELECT id, nazwa FROM miejsca WHERE dzielnica=' . $dzielnica;
        $result_miejsca = $mysql->query($zapytanie_miejsca);
        if ($result_miejsca->num_rows != 0)
        {
            echo '<table>';
            while ($row_miejsca = $result_miejsca->fetch_array())
            {
                $file = '../page/miejsce.php?id=' . $row_miejsca['id'];     
                echo '<tr style="cursor:pointer" onclick="document.location =\'' . $file . '\';">';
                    echo '<td>';
                        echo $row_miejsca['nazwa'];
                    echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
}
    
public function miejsce($id)
{
    if (isset($_GET['id']))
    {
        $mysql = connect_mysql();
        
        $zapytanie = 'SELECT dzielnica.nazwa as dzielnica, miejsca.nazwa as miejsce FROM dzielnica,miejsca WHERE dzielnica.id=miejsca.dzielnica AND miejsca.id=' . $id;
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows == 1)
        {
            $row = $result->fetch_array();
            echo '<table>';
                echo '<tr>';
                    echo '<td>';
                        echo 'Nazwa';
                    echo '</td>';
                    echo '<td>';
                        echo $row['miejsce'];
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                        echo 'Dzielnica';
                    echo '</td>';
                    echo '<td>';
                        echo $row['dzielnica'];
                    echo '</td>';
                echo '</tr>';
            
            echo '</table>';        
        } else
        {
            header ("Location: ../page/miejsca.php");
        }
    }
    else
    {
        header ("Location: ../page/miejsca.php");
        exit();
    }
    
}
    
public function miejsce_kafel ()
{
$cube[1][0] = 'mode_edit';
$cube[1][1] = 'Edytuj profil';
$cube[1][2] = '../page/edit-miejsce.php?id=' . $_GET['id'];
$cube[1][3] = '#6eb4e5';  

$cube[2][0] = '';
$cube[2][1] = '';
$cube[2][2] = '';
$cube[2][3] = '#5fafe4';

$cube[3][0] = '';
$cube[3][1] = '';
$cube[3][2] = '';
$cube[3][3] = '#7dbde9';

$cube[4][0] = '';
$cube[4][1] = '';
$cube[4][2] = '';
$cube[4][3] = '#8cc3ea';

$cube[5][0] = '';
$cube[5][1] = '';
$cube[5][2] = '';
$cube[5][3] = '#99cbee';

$cube[6][0] = '';
$cube[6][1] = '';
$cube[6][2] = '';
$cube[6][3] = '#90CAF2';  

$cube[7][0] = '';
$cube[7][1] = '';
$cube[7][2] = '';
$cube[7][3] = '#90CAF2';  

$cube[8][0] = '';
$cube[8][1] = '';
$cube[8][2] = '';
$cube[8][3] = '#90CAF2';  

$cube[9][0] = '';
$cube[9][1] = '';
$cube[9][2] = '';
$cube[9][3] = '#90CAF2';  

$cube[10][0] = '';
$cube[10][1] = '';
$cube[10][2] = '';
$cube[10][3] = '#90CAF2';      

for ($x=1;$x<=count($cube);$x++)
{
if ($x%2 == 0)
{   
    if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
    {
    echo '<a href="' . $cube[$x][2] . '">';   
    }
    echo '<div class="kafel ostatnikafel" style="background-color:' . $cube[$x][3] . '">';
        echo '<i class="material-icons">' . $cube[$x][0]  . '</i>';
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
        echo '<i class="material-icons">' . $cube[$x][0]  . '</i>';
        echo '<span>' . $cube[$x][1] . '</span>';            
    echo '</div>';
    if (($cube[$x][2] != '#') and ($cube[$x][2] != ''))
    {
    echo '</a>'; 
    }    
}
}    
}
    
    
public function add_uczen_dodatkowe_jazdy ($id)
{
    
$mysql = connect_mysql ();
include ('../script/month.php');

echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
    echo '<h3> Dodaj dodatkowe jazdy </h3>';
echo '</div>';
            
echo '<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12 col-centered update">';
    echo '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page">';
        echo '<form class="form-horizontal" method="POST" action="../script/add-dodatkowe-jazdy.php">';   
            echo '<input type="hidden" name="id" value="' . $id . '">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Opcja';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="pakiet" id="kurs" onchange="add_user_kurs()" class="input-sm form-control">';
                            $zapytanie_pakiety = 'SELECT id, cena, godziny FROM pakiety';
                            $result_pakiety = $mysql->query($zapytanie_pakiety);
                                while ($row_pakiety = $result_pakiety->fetch_array())
                                {
                                    echo '<option value="' . $row_pakiety['id'] . '">' . $row_pakiety['godziny'] . ' godzin za ' . $row_pakiety['cena'] . 'zł </option>';
                                }
                                echo '<option value="0"> Niestandardowy </option>';
                        echo '</select>';
                    echo '</div>';          
                echo '</div>';    
                echo '<div id="niestandardowy_kurs" style="display:none">';
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Cena';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="number" name="cena" min="0" class="input-sm form-control">';
                        echo '</div>';           
                    echo '</div>';  
                    echo '<div class="form-group">';
                        echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                            echo 'Jazdy';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<input type="number" name="jazdy" min="0" class="input-sm form-control">';
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
        $button[1]['href'] = '../page/uczen.php?id=' . $id;
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>';      
}
    

    
    
}

?>