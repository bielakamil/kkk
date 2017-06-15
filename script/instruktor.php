<?php

class instruktor
{

    
public function __construct () // Funkcja uruchamia się podczas tworzenie nowego obiektu, czyli co stronę
{
    include ('../script/mysql.php'); // Dołączenie pliku mysql.php
    $mysql = connect_mysql(); // Połączenie z bazą mysql
    $zapytanie = 'SELECT instruktorzy.id,instruktorzy.imie, instruktorzy.nazwisko FROM instruktorzy WHERE pesel="' . $_SESSION['pesel'] . '"'; // Zapytanie zwracające imie i nazwisko ucznia
    $result = $mysql->query($zapytanie); // Wysłanie zapytania
    
    if ($result->num_rows != 1) // Jeśli jest tylko jeden rekord to istnieje podany uczeń
    {
        header ("Location: ../page/login.php");
    }
    
    $row = $result->fetch_array(); // Pobranie wyniku
    
    // Przypisanie zmiennych
    $this->id = $row['id'];
    $this->pesel = $_SESSION['pesel'];
    $this->imie = $row['imie'];
    $this->nazwisko = $row['nazwisko'];  
}    
    
public function dashboard ()
{
    // AGENDA
    echo '<div class="row">';
    echo '<h4 class="text-center"> Serwis w fazie testów. Informacje w nim zawarte mogą różnić się od rzeczywistych. Zachęcamy do zgłaszania błędów na adres: krzysiek@4-kolka.pl </h4>'; 
        echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-1 col-lg-offset-1 agenda shadow">';
            echo '<div class="row">';
                $this->agenda();
            echo '</div>';
        echo '</div>';
    echo '</div>';
    
//    // NOTATKI
//    echo '<div class="row">';
//        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 title">';
//            echo '<h3> Moje notatki </h3>';
//        echo '</div>';  
//        echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 note shadow">';
//            echo 'W budowie';   
//        echo '</div>';  
//    echo '</div>';
    
    


    
    
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
            echo '<a href="#">';
                echo $this->imie . ' ' . $this->nazwisko;
            echo '</a>';
            echo '<i class="fa fa-angle-down fa-2x fa-fw hidden-xs hidden-sm" id="small-menu-open"> </i>';
            echo '<i class="fa fa-bars fa-2x fa-fw hidden-lg hidden-md" id="mobile-menu-open"> </i>';
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
                        echo 'Opis egzaminu';
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
    

    
public function show_portfel()
{
    
        $mysql = connect_mysql();
        
        $zapytanie_budzet = 'SELECT date(data) AS dzien, count(*) AS ile FROM budzet WHERE instruktor="' . $this->id . '" GROUP BY dzien ORDER BY dzien DESC';
        
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
                $zapytanie_dzien = 'SELECT id,data,kwota,wczesniej,aktualnie,status,id_status FROM budzet WHERE instruktor="' . $this->id . '" AND date(data)="' . $data . '" ORDER BY data DESC';
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
                                    echo 'Uczeń ' . $uczen . ' wpłacił ' . $kwota . 'zł ';
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
    
function wplata ($id)
{

$mysql = connect_mysql();
    
$zapytanie = 'SELECT instruktorzy.imie AS instruktor_imie, instruktorzy.nazwisko AS instruktor_nazwisko, uczniowie.imie AS uczen_imie, uczniowie.nazwisko AS uczen_nazwisko,uczniowie.id AS uczen_id, wplaty.instruktor, wplaty.kasa, wplaty.data FROM instruktorzy,wplaty,uczniowie WHERE wplaty.instruktor=instruktorzy.id AND uczniowie.id=wplaty.uczen AND wplaty.id=' . $id;
    
$result = $mysql->query($zapytanie);
    
if ($result->num_rows == 1)
{
    $row = $result->fetch_array();
    
    include ('../script/month.php');
    $date = $row['data'];
    $data_dzien = date("j", strtotime($date));
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
            echo '<td><a href="../page/uczen.php?id=' . $row['uczen_id'] .'">' . $uczen . '</a></td>';
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
    
    $cube[1][0] = 'mode_edit';
    $cube[1][1] = 'Edytuj';
    $cube[1][2] = '../page/edit-wplata.php?id=' . $_GET['id'];
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
    
public function edit_wplata($id)
{
$mysql = connect_mysql();
$zapytanie = 'SELECT instruktorzy.imie AS instruktor_imie, instruktorzy.nazwisko AS instruktor_nazwisko, uczniowie.imie AS uczen_imie, uczniowie.nazwisko AS uczen_nazwisko,uczniowie.id AS uczen_id, wplaty.instruktor, wplaty.kasa, wplaty.data FROM instruktorzy,wplaty,uczniowie WHERE wplaty.instruktor=instruktorzy.id AND uczniowie.id=wplaty.uczen AND wplaty.id=' . $id;
    
$result = $mysql->query($zapytanie);
    
if ($result->num_rows == 1)
{
    $row = $result->fetch_array();
    include ('../script/month.php');
    $date = $row['data'];
    $data_dzien = date("j", strtotime($date));
    $data_miesiac = $month[date("n", strtotime($date))];
    $data_rok = date("Y", strtotime($date));
    $data_godzina = date("G", strtotime($date));
    $data_minuta = date("i", strtotime($date));
    $data = $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . ' ' . $data_godzina . ':' . $data_minuta;
    $instruktor = $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'];
    $uczen = $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'];
    
    echo '<form method="POST" action="../script/edit-wplata.php">';
    echo '<input type="hidden" value="' . $id . '" name="id">';
    echo '<table>';
        echo '<tr>';
            echo '<td> Data </td>';
            echo '<td>';
                echo $data;
            echo '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Instruktor </td>';
            echo '<td>';
                echo $instruktor;
            echo '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Uczeń </td>';
            echo '<td>';
                echo $uczen;
            echo '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Wpłata </td>';
            echo '<td>';
                echo '<input type="number" name="kasa" min="0" value="' . $row['kasa'] . '">';
            echo '</td>';
        echo '</tr>';
    
    
    echo '</table>';
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}
}
    
public function edit_wplata_kafel ()
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
    $cube[2][2] = '../page/wplata.php?id=' . $_GET['id'];
    $cube[2][3] = '#6eb4e5';
    
    

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
        
        $zapytanie = 'SELECT uczniowie.id, uczniowie.imie,uczniowie.pkk, uczniowie.nazwisko, uczniowie.pesel, uczniowie.data_zapisu, uczniowie.stan, uczniowie.kurs, uczniowie.jazdy, kategoria_prawo_jazdy.skrot FROM uczniowie, kategoria_prawo_jazdy WHERE date(uczniowie.data_zapisu)="' . $data . '" AND kategoria_prawo_jazdy.id=uczniowie.kategoria AND uczniowie.instruktor="' . $this->id . '"  ORDER BY uczniowie.data_zapisu DESC';
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
    
public function menu ()
{
    $mysql = connect_mysql();
    $zapytanie_firma = 'SELECT nazwa,facebook,instagram,www,twitter,youtube FROM firma';
    
    $result_firma = $mysql->query($zapytanie_firma);
    $row_firma = $result_firma->fetch_array();
    
    // LOGO
    echo '<a href="../page/dashboard.php">';
        echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 logo text-center">';
            echo '<b>MOJE</b>PRAWKO.PL';
        echo '</div>';
    echo '</a>';
    echo '<div class="hidden-lg hidden-md col-sm-2 col-sm-offset-10 col-xs-2 col-xs-offset-10 text-right close_menu">';
        echo '<i class="fa fa-times fa-fw fa-2x"  id="mobile-menu-close"></i>';
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
//            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
//                echo '<a href="../page/show_all_user.php?new=on"> Nowi kursanci  </a>';
//            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                $zapytanie_uczen = 'SELECT count(id) AS ile FROM uczniowie WHERE instruktor=' . $this->id;
                $result_uczen = $mysql->query($zapytanie_uczen);
                $row_uczen = $result_uczen->fetch_array();
                echo '<a href="../page/show_all_user.php"> Moi kursanci [' . $row_uczen['ile'] . ']  </a>';
            echo '</div>';
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/show_all_user.php?all=on"> Wszyscy kursanci  </a>';
            echo '</div>';
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/grupy.php?all=on"> Grupy  </a>';
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
                $rok =  date('Y');
                $miesiac =  date('m');
                $ilosc_dni = date('t');
                $zapytanie_wyklad = 'SELECT count(*) as ile FROM wyklady WHERE  wyklady.instruktor="' . $this->id . '" AND wyklady.data BETWEEN "' . $rok . '-' . $miesiac . '-01" AND "' . $rok . '-' . $miesiac . '-' . $ilosc_dni . '"';
                $result_wyklad = $mysql->query($zapytanie_wyklad);
                $row_wyklad = $result_wyklad->fetch_array();
                echo '<a href="../page/wyklady.php?instruktor=' . $this->id . '"> Moje wykłady [' . $row_wyklad['ile'] . '] </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/wyklady.php"> Wszystkie w OSK </a>';
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
                $zapytanie_jazdy = 'SELECT count(id) AS ile FROM jazdy WHERE stan=1 AND instruktor=' . $this->id;
                $result_jazdy = $mysql->query($zapytanie_jazdy);
                $row_jazdy = $result_jazdy->fetch_array();
                echo '<a href="../page/jazdy.php?new=on"> Najbliższe jazdy [' . $row_jazdy['ile'] . '] </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/jazdy.php"> Moje jazdy </a>';
            echo '</div>'; 
            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                echo '<a href="../page/jazdy.php?all=on"> Wszystkie w OSK </a>';
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
//                $zapytanie_egzamin = 'SELECT count(egzamin.id) as ile FROM egzamin,uczniowie WHERE egzamin.uczen=uczniowie.id AND egzamin.stan=1 AND uczniowie.instruktor=' . $this->id;
//                $result_egzamin = $mysql->query($zapytanie_egzamin);
//                $row_egzamin = $result_egzamin->fetch_array();
//                echo '<a href="../page/egzaminy.php?new=on"> Najbliższe egzaminy [' . $row_egzamin['ile'] . ']  </a>';
//            echo '</div>'; 
//            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
//                echo '<a href="../page/egzaminy.php"> Wszystkie egzaminy </a>';
//            echo '</div>'; 
//        echo '</div>';
//    echo '</div>';
//    
//    // SAMOCHODY
//    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
//        echo '<a href="#">';
//            echo '<i class="material-icons"> directions_car </i>';
//            echo '<span> Samochody </span>';
//        echo '</a>';
//        echo '<div class="row subsubmenu">'; 
//            echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
//                echo '<a href="../page/samochody.php"> Wszystkie samochody </a>';
//            echo '</div>';
//        echo '</div>';
//    echo '</div>';
    
    
    // WIADOMOŚCI
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="material-icons"> settings </i>';
            echo '<span> Wiadomości </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
                echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                    echo '<a href="../page/send-message.php">  Wyślij wiadomość </a>';
                echo '</div>';
            echo '</div>';
    echo '</div>';
    
    // USTAWIENIA
    echo '<div class="hidden-lg hidden-md col-sm-12 col-xs-12 submenu">';
        echo '<a href="#">';
            echo '<i class="material-icons"> settings </i>';
            echo '<span> Ustawienia </span>';
        echo '</a>';
        echo '<div class="row subsubmenu">'; 
                echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                    echo '<a href="#"> Twój profil</a>';
                echo '</div>';
                echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                    echo '<a href="../other/pytania.php"> Pytania </a>';
                echo '</div>';
                echo '<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 col-md-offset-2 col-lg-offset-2 href">';
                    echo '<a href="../page/settings.php"> Ustawienia </a>';
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
        
public function ile_powiadomien ()
{

    $mysql = connect_mysql(); // Połączenie z bazą mysql
    
    $zapytanie = 'SELECT count(odbiorca) as ile FROM powiadomienia WHERE odbiorca="' . $this->pesel . '" AND stan=1';
    
    $result = $mysql->query($zapytanie);
    
    $row = $result->fetch_array();
    $ile = $row['ile'];
    return $ile;
}
    

public function PokazWszystkichKursantowWSzkole() 
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszyscy kursanci w szkole </h3>';
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
                    echo '<th> Data zapisu </th>';
                    echo '<th> Kursant </th>';
                    echo '<th> Instruktor </th>';
                    echo '<th data-breakpoints="xs sm md"> Kategoria </th>';
                    echo '<th data-breakpoints="xs sm md"> Kurs </th>';
                    echo '<th data-breakpoints="xs sm "> Stan </th>';
                    echo '<th data-breakpoints="xs" data-type="number" > Wykłady </th>'; 
                    echo '<th data-breakpoints="xs  "> Jazdy </th>';
//                    echo '<th data-breakpoints="xs sm md"> % </th>';
                    echo '<th  data-type="html"> </th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                while ($row = $result->fetch_array())
                {
                    if ($row['stan'] == 6)
                    {
                        echo '<tr class="danger">';
                    } elseif ($row['stan'] == 5)
                    {
                        echo '<tr class="success">';
                    } elseif ($row['stan'] == 4)
                    {
                        echo '<tr class="info">';
                    } else
                    {
                        echo '<tr>';
                    }
                        $dzien = date("j", strtotime($row['data_zapisu'])) . ' ' . $month[date("n", strtotime($row['data_zapisu']))] . ' ' . date("Y", strtotime($row['data_zapisu']));
                        echo '<td>' . $dzien . '</td>';
                        echo '<td>' . $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'] . '</td>';
                        echo '<td>' . $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'] . '</td>';
                        
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
                        
                        $caly_czas = $row['jazdy'];
                        
                        $zapytanie_dodatkowe = 'SELECT dodatkowe_jazdy.cena, dodatkowe_jazdy.jazdy FROM dodatkowe_jazdy WHERE  dodatkowe_jazdy.uczen=' . $id;
                        $result_dodatkowe = $mysql->query($zapytanie_dodatkowe);

                        while ($row_dodatkowe = $result_dodatkowe->fetch_array())
                        {
                            $caly_czas += $row_dodatkowe['jazdy'];
                        }    
                    
                        echo '<td data-sort-value="' . $czas  . '">';                    
                            echo round($czas) . '/' . $caly_czas;
                        echo '</td>';
//                        echo '<td>';
//                            echo $row['procent'];
//                        echo '</td>';
                        echo '<td> <a href="../page/uczen.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                    echo '</tr>';
                }
            echo '</tbody>';
        echo '</table>';
    echo '</div>';
    
    
    
}       
    
    
public function podglad_jazd_uczen ($id)
{
    

    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT uczniowie.imie as uczen_imie, uczniowie.nazwisko as uczen_nazwisko, uczniowie.id as uczen_id, instruktorzy.id as instruktor_id, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, instruktorzy.show_car FROM uczniowie, instruktorzy WHERE instruktorzy.id=uczniowie.instruktor AND uczniowie.instruktor=' . $this->id . ' AND uczniowie.id=' . $id;
    $result = $mysql->query($zapytanie);
    if ($result->num_rows != 1)
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }

    $row =  $result->fetch_array();
    $x = 0;
     // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Podgląd jazd - ' . $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'] . '</h3>';
    echo '</div>';
    
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';

        $zapytanie_kategoria = 'SELECT zadania_kategoria.id, zadania_kategoria.nazwa FROM zadania_kategoria, zadania_uczen, zadania_instruktor_kategorie WHERE zadania_kategoria.id=zadania_instruktor_kategorie.kategoria  AND zadania_uczen.uczen=' . $id . '  GROUP BY zadania_kategoria.nazwa ORDER BY zadania_instruktor_kategorie.level';
        $result_kategoria = $mysql->query($zapytanie_kategoria);
    
        if ($result_kategoria->num_rows == 0)
        {
            echo '<a href="../script/generate-arkusz-oceniania.php?id=' . $id . '">';
                echo '<button class="btn btn-primary"> Wygeneruj arkusz oceniania </button>';
            echo '</a>';
        }
    
        while ($row_kategoria = $result_kategoria->fetch_array())
        {
            $zapytanie_zadanie = 'SELECT zadania_uczen.id, zadania_uczen.ocena, zadania_wyzwania.zakres, zadania_wyzwania.level, zadania_wyzwania.nazwa, zadania_uczen.data FROM zadania_uczen, zadania_wyzwania WHERE zadania_wyzwania.id=zadania_uczen.zadanie AND zadania_uczen.uczen=' . $row['uczen_id'] . ' AND zadania_wyzwania.kategoria=' . $row_kategoria['id'] . ' ORDER BY zadania_wyzwania.level';
            $result_zadanie = $mysql->query($zapytanie_zadanie);
            
            if ($result_zadanie->num_rows == 0)
            {
                continue;
            }
            
            if ($x%2 == 0)
            {
                echo '<div class="row">';
            }
            $x++;
            if ($row_kategoria['nazwa'] != 'Miasto')
            {
                echo '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">';
            } else
            {
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';                
            }
            
                echo '<h4>' . $row_kategoria['nazwa'] . ' - <span class="procent-' . $row_kategoria['id'] . '"> 0% </span> </h4>';
                echo '<table class="table footable" data-sorting="true" >';
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th> Nazwa </th>';
                            if ($row_kategoria['nazwa'] != 'Miasto')
                            {
                                echo '<th data-type="html" data-sortable="false"> </th>';
                                echo '<th data-type="html"> Postępy </th>';
                                echo '<th data-type="html" data-sortable="false"> </th>';
                            } else
                            {
                                echo '<th data-type="html"> Postępy </th>';
                            }
                            
                            echo '<th class="text-center"> Data </th>';
//                            echo '<th data-breakpoints="xs sm md" class="text-center"> Data </th>';
                            if ($row_kategoria['nazwa'] != 'Miasto')
                            {
                                echo '<th data-breakpoints="xs sm md lg" class="text-center"> Postęp </th>';
                            }    
                        echo '</tr>';
                    echo '</thead>';
                    while ($row_zadanie = $result_zadanie->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td class="col-lg-3 col-md-3 col-xs-4 col-sm-3" data-sort-value="' . $row_zadanie['level'] . '">' .  $row_zadanie['nazwa'] . '</td>';
                            switch ($row_zadanie['zakres'])
                            {
                                case 1:   
                                    {
                                        $min = 0;
                                        $max = 1;
                                        break;
                                    }
                                case 2:   
                                    {
                                        $min = -1;
                                        $max = 1;
                                        break;
                                    }
                                case 3:   
                                    {
                                        $min = -5;
                                        $max = 5;
                                        break;
                                    }
                                case 4:   
                                    {
                                        $min = 0;
                                        $max = 4;
                                        break;
                                    }
                                default:   
                                    {
                                        $min = 0;
                                        $max = 1;
                                        break;
                                    }
                            }
                            if ($row_kategoria['nazwa'] != 'Miasto')
                            {
                                echo '<td data-type="html">';
                                    echo '<div class="range-button range-minus" id="m-' . $row_zadanie['id'] . '"> - </div>';
                                echo '</td>';
                            }
                            
                            echo '<td data-type="html">';
                                   echo '<input type="range" onchange="ajax_update_zadanie(this)"   step="1" min="' . $min . '" max="' . $max . '" value="' . $row_zadanie['ocena'] . '" category="' . $row_kategoria['id'] . '" id="' . $row_zadanie['id'] . '">'; 

                                
                            echo '</td>';
                        
                            if ($row_kategoria['nazwa'] != 'Miasto')
                            {
                                echo '<td data-type="html">';
                                    echo '<div class="range-button range-plus" id="p-' . $row_zadanie['id'] . '"> + </div>';
                                echo '</td>';
                            }
                        
                            $start = $row_zadanie['data'];
                            $start_dzien = date("j", strtotime($start));
                            include ('../script/month.php');
                            $start_miesiac = $month[date("n", strtotime($start))];
                            $start_rok = date("Y", strtotime($start));
                            
//                            echo '<td class="date-' . $row_zadanie['id'] . ' text-center"  data-sort-value="' . $start_rok . '-' . date("n",strtotime($start)) . '-' . $start_dzien  . '">';
//                                echo $start_dzien . ' ' . $start_miesiac . ' ' . $start_rok;
//                            echo '</td>';                            
                            echo '<td class="date-' . $row_zadanie['id'] . ' text-center"  data-sort-value="' . $start_rok . '-' . date("n",strtotime($start)) . '-' . $start_dzien  . '">';
                                echo $start_dzien . '/0' . date("n",strtotime($start));
                            echo '</td>';
                        
                            if ($row_kategoria['nazwa'] != 'Miasto')
                            {
                                echo '<td class="postep-' . $row_zadanie['id'] . '">';
                            
                                switch ($row_zadanie['ocena'])
                                {
                                    case 0:
                                        {
                                            $ocena = 'Brak danych';
                                            break;
                                        }
                                    case 1:
                                        {
                                            $ocena = 'Do poprawy';
                                            break;
                                        }
                                    case 2:
                                        {
                                            $ocena = 'Słabo';
                                            break;
                                        }
                                    case 3:
                                        {
                                            $ocena = 'Dobrze';
                                            break;
                                        }
                                    case 4:
                                        {
                                            $ocena = 'Rewalacyjnie';
                                            break;
                                        }
                                        
                                }
                        
                                echo $ocena;
                        
                            echo '</td>';
                            }
                            
                        echo '</tr>';
                    }
                echo '</table>';
            echo '</div>';
            if ($x%2 == 0)
            {
                echo '</div>';
            }
            
            echo '<script>';
                echo 'podglad_jazd_kategoria(' . $row_kategoria['id'] . ')'; 
            echo '</script>';
        }
    
    echo '</div>';
    
    

    
    
    
}
    
    
public function show_profil_instruktor($id = 0)
{
    
if ($id != 0)
{
   $zapytanie = 'SELECT instruktorzy.id,instruktorzy.pesel, instruktorzy.imie, instruktorzy.nazwisko, instruktorzy.plec, instruktorzy.data_urodzenia, instruktorzy.telefon, instruktorzy.email FROM instruktorzy WHERE instruktorzy.id="' . $id . '"';    
} else
{
    $zapytanie = 'SELECT instruktorzy.id,instruktorzy.pesel, instruktorzy.imie, instruktorzy.nazwisko, instruktorzy.plec, instruktorzy.data_urodzenia, instruktorzy.telefon, instruktorzy.email FROM instruktorzy WHERE instruktorzy.pesel="' . $this->pesel . '"';  
}

    
$mysql = connect_mysql ();


$result = $mysql->query($zapytanie);
    
if ($result->num_rows == 1)
{
    $row = $result->fetch_array();
    $id = $row['id'];
    echo '<div id="profil">';
        echo '<div class="profil-details">';
            echo '<h1>' . $row['imie'] . ' ' . $row['nazwisko'] . '</h1>';
            echo '<a href="#"> <div id="button-profil"> Szczegóły </div> </a>';
            echo '<div class="clear"></div>'; 
            $telefon  = $row['telefon'];
            $t1 = substr($telefon,0,3);
            $t2 = substr($telefon,3,3);
            $t3 = substr($telefon,6,3);
            echo '<span> Telefon: ' . $t1 . ' ' . $t2 . ' ' . $t3 . '</span>';
            echo '<span> E-mail: ' . $row['email']  . ' </span>';    

        echo '</div>';
        echo '<div id="photo">';
            echo '<img src="../img/prifile-pic.png" width="100%" height="100%">';
        echo '</div>';
        echo '<div class="clear"></div>';
        $zapytanie_jazdy = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE stan=2 AND instruktor="' . $row['id'] . '"';
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
    
        $zapytanie_wyklady = 'SELECT count(*) AS ile FROM wyklady WHERE stan=2 AND instruktor=' . $id;
        $result_wyklady = $mysql->query($zapytanie_wyklady);
        $row_wyklady = $result_wyklady->fetch_array();
        $wyklady = $row_wyklady['ile'];
    
        $zapytanie_jazdy = 'SELECT data_rozpoczecia, data_zakonczenia FROM jazdy WHERE stan=2 AND instruktor="' . $id . '"';
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

        $zapytanie_dodatkowe = 'SELECT dodatkowe_jazdy.cena, dodatkowe_jazdy.jazdy FROM dodatkowe_jazdy WHERE  dodatkowe_jazdy.uczen=' . $id;
        $result_dodatkowe = $mysql->query($zapytanie_dodatkowe);

        while ($row_dodatkowe = $result_dodatkowe->fetch_array())
        {
            $caly_czas += $row_dodatkowe['jazdy'];
            $caly_koszt += $row_dodatkowe['cena'];
        }

        $zapytanie_pieniadze = 'SELECT aktualnie FROM budzet WHERE instruktor=' . $id . ' ORDER BY budzet.id DESC LIMIT 1';
        $result_pieniadze = $mysql->query($zapytanie_pieniadze);
        
        if ($result_pieniadze->num_rows == 0)
        {
            $pieniadze = 0;
        } else
        {
            $row_pieniadze = $result_pieniadze->fetch_array();
            $pieniadze = $row_pieniadze['aktualnie'];
        }
        
    
        echo '<div id="cube">';
            echo '<a href="../page/wyklady.php?instruktor=' . $id . '">';
                echo '<div class="ikona pierwsza-dziecko" style="background-color: #5fafe4">';
                    echo '<i>' . $wyklady . '</i>';
                    echo '<span> Moje wykłady </span>';
                echo '</div>';
            echo '</a>';
            echo '<a href="../page/finanse.php">';
                echo '<div class="ikona" style="background-color: #6eb4e5">';
                    echo '<i>' . round($pieniadze) . 'PLN </i>';
                    echo '<span> Finanse </span>';
                echo '</div>';
            echo '</a>';
            echo '<a href="../page/jazdy.php?instruktor=' . $id . '">';
                echo '<div class="ikona" style="background-color: #7dbde9">';
                    echo '<i>' . round($czas) . '</i>';
                    echo '<span> Moje jazdy </span>';
                echo '</div>';
            echo '</a>';
        echo '</div>';

        
        echo '<h2> Wykłady </h2>';
        echo '<div id="friends" style="height:auto">';
            $this->PokazWszystkieWyklady();
        echo '</div>';
            
            
        echo '<h2> Opcje </h2>';
        echo '<div id="options">';
            
           
            
            
            
        echo '</div>';
    echo '</div>';
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}
    

    
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
     
    
public function show_profil_instruktor_kafel ($id)
{
    

    
    /*
    [][0] - klasa ikony
    [][1] - podpis
    [][2] - strona
    [][3] - kolor
    */    
    if ($id == 0)
    {
    $cube[1][0] = 'mode_edit';
    $cube[1][1] = 'Edytuj profil';
    $cube[1][2] = '../page/edit-instruktor.php';
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
    } else
    {
    $cube[1][0] = '';
    $cube[1][1] = '';
    $cube[1][2] = '';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = '';
    $cube[2][1] = '';
    $cube[2][2] = '#';
    $cube[2][3] = '#6eb4e5';
    
    $cube[3][0] = 'work';
    $cube[3][1] = 'Pokaż wykłady';
    $cube[3][2] = '../page/wyklady.php?instruktor=' . $_GET['id'];
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
    }

        for ($x=1;$x<=count($cube);$x++)
    {
        
if ($x%2 == 0)
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
    
public function edit_profil_instruktor()
{
$mysql = connect_mysql();    
$zapytanie = 'SELECT instruktorzy.pesel, instruktorzy.imie, instruktorzy.nr_uprawnien, instruktorzy.nazwisko, instruktorzy.plec, instruktorzy.data_urodzenia, instruktorzy.telefon, instruktorzy.email FROM instruktorzy WHERE instruktorzy.pesel="' . $this->pesel . '"';
    
$result = $mysql->query($zapytanie);    
    
if ($result->num_rows == 1)
{
    
$row = $result->fetch_array();
    include ('../script/month.php');
    echo '<form method="POST" action="../script/edit-instruktor.php">';
        echo '<table>';
            echo '<tr>';
                echo '<td> Pesel </td>';
                echo '<td>' . $row['pesel'] . '</td>';
                echo '<input type="hidden" name="pesel" value="' . $row['pesel'] . '">';
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
                echo '<td> Płeć </td>';
                echo '<td>';
                        echo $sex[$row['plec']];
                echo '</td>';
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
    $cube[2][2] = '../page/instruktor.php';
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
    
// 
//        echo '<h2> Opis </h2>';
//        echo '<div id="opis">';
//            echo $row['notatka'];
//
//        echo '</div>';
//        
//        echo '<h2> Wykłady </h2>';
//        echo '<div id="historia">';
//            $zapytanie_wyklad = 'SELECT wyklady.id, wyklady.data, wyklady.stan FROM wyklady WHERE wyklady.temat=' . $id .  ' ORDER BY id DESC';
//            $result_wyklad = $mysql->query($zapytanie_wyklad);
//        
//            if ($result_wyklad->num_rows > 0)
//            {
//                echo '<table>';
//                    while ($row_wyklad = $result_wyklad->fetch_array())
//                    {
//                    $file = '../page/wyklad.php?id=' . $row_wyklad['id'];     
//                    echo '<tr style="cursor:pointer" onclick="document.location =\'' . $file . '\';">';
//                    $start = $row_wyklad['data'];
//                    $start_dzien = date("j", strtotime($start));
//                    include ('../script/month.php');
//                    $start_miesiac = $month[date("n", strtotime($start))];
//                    $start_rok = date("Y", strtotime($start));
//                    $start_godzina = date("G", strtotime($start));
//                    $start_minuta = date("i", strtotime($start));
//                    echo '<td>' . $start_dzien . ' ' . $start_miesiac . ' ' . $start_rok . ' ' . $start_godzina . ':' . $start_minuta . '</td>';
//                    if ($row_wyklad['stan'] == 1)
//                    {
//                        $stan = 'Zaplanowane';
//                    } else
//                    {
//                        $stan = 'Odbyły się';
//                    }
//                    echo '<td>' . $stan . '</td>';
//                echo '</tr>';
//                    }
//                echo '</table>';
//            }
//            
//        echo '</div>';
//        
//        
//
////        
////        $zapytanie_wyklad = 'SELECT wyklady.id, wyklady.data, wyklady.stan FROM wyklady WHERE wyklady.temat=' . $id .  ' ORDER BY id DESC LIMIT 5';
////        $result_wyklad = $mysql->query($zapytanie_wyklad);
////        
////        if ($result_wyklad->num_rows > 0)
////        {
////            echo '<h2> Ostatnie 5 wykładów </h2>';
////            echo '<table>';
////                while ($row_wyklad = $result_wyklad->fetch_array())
////                {
////                $file = '../page/wyklad.php?id=' . $row_wyklad['id'];     
////                echo '<tr style="cursor:pointer" onclick="document.location =\'' . $file . '\';">';
////                    $start = $row_wyklad['data'];
////                    $start_dzien = date("j", strtotime($start));
////                    include ('../script/month.php');
////                    $start_miesiac = $month[date("n", strtotime($start))];
////                    $start_rok = date("Y", strtotime($start));
////                    $start_godzina = date("G", strtotime($start));
////                    $start_minuta = date("i", strtotime($start));
////                    
////                    
////                    echo '<td>' . $start_dzien . ' ' . $start_miesiac . ' ' . $start_rok . ' ' . $start_godzina . ':' . $start_minuta . '</td>';
////                    if ($row_wyklad['stan'] == 1)
////                    {
////                        $stan = 'Zaplanowane';
////                    } else
////                    {
////                        $stan = 'Odbyły się';
////                    }
////                    echo '<td>' . $stan . '</td>';
////                echo '</tr>';
////                }
////            echo '</table>';
////        }
////        
////        echo '<h2> Notatka </h2>';
////        echo '<p>' . $row['notatka'] . '<p>';
//    }
//} else
//{
//    header ("Location: ../page/dashboard.php"); // PRZEKIEROWUJEMY NA STRONĘ GŁÓWNĄ
//    exit();
//}
    
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
                        echo '<th>  </th>';
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
                            $dzien  = date("d", strtotime($row['data'])) . ' '  . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                        
                            $czas = date("H", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));    
                        
                            echo '<td>' . $dzien . '</td>';
                            echo '<td>' . $czas . '</td>';
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
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan, wyklady_miejsce.nazwa, wyklady.instruktor, tematy_wyklad.temat FROM wyklady, obecnosc, uczniowie, wyklady_miejsce,tematy_wyklad WHERE wyklady.temat=tematy_wyklad.id AND wyklady.miejsce=wyklady_miejsce.id AND uczniowie.id=obecnosc.uczen AND obecnosc.wyklad=wyklady.id AND uczniowie.id="' . $id . '" ORDER BY wyklady.data DESC';
        $result = $mysql->query($zapytanie);
        
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th>  </th>';
                        echo '<th> Wykładowca  </th>';
                        echo '<th> Temat </th>';
                        echo '<th> Miejsce </th>';
                        echo '<th> Stan </th>';
                        echo '<th data-type="html"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            $data = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                            $czas = date("G", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
                    
                            echo '<td>' . $data . '</td>';
                            echo '<td>' . $czas . '</td>';
                            
                            
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
                            echo '<td>' . $row['temat'] . '</td>';
                            echo '<td>' . $row['nazwa'] . '</td>';
                            echo '<td>' . $etap_wyklad[$row['stan']] . '</td>';
                            echo '<td> <a href="../page/wyklad.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
                echo '<tfoot>';
                    echo '<tr>';
                        echo '<td colspan="7"> Do tej pory był na <b> ' . $result->num_rows . ' </b> wykładach </td>';
                    
                    echo '</tr>';
            
                echo '</tfoot>';
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
        if ($id == $this->id)
        {
             echo '<h3> Moje wykłady </h3>';  
        } else
        {
            echo '<h3> Wszystkie wykłady - instruktor ' . $instruktor . ' </h3>';   
        }
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-wyklad.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj wykład';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA NA CAŁĄ SZEROKOŚĆ 
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';        
        $rok =  date('Y');
        $miesiac =  date('m');
        $ilosc_dni = date('t');
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan, wyklady_miejsce.nazwa FROM wyklady, wyklady_miejsce WHERE wyklady.miejsce=wyklady_miejsce.id AND wyklady.instruktor="' . $id . '" AND wyklady.data BETWEEN "' . $rok . '-' . $miesiac . '-01" AND "' . $rok . '-' . $miesiac . '-' . $ilosc_dni . '" ORDER BY wyklady.data DESC';
        $result = $mysql->query($zapytanie);
        $x = $result->num_rows;
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>  </th>';
                        echo '<th> Data </th>';
                        echo '<th data-breakpoints="xs" data-title="Godzina">  </th>';
                        echo '<th data-breakpoints="xs"> Stan </th>';
                        echo '<th data-breakpoints="xs sm md"> Miejsce </th>';
                        echo '<th data-type="html"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    $ilosc_prowadzonych = 0;
                    while ($row = $result->fetch_array())
                    {
                        if ($row['stan'] == 1)
                        {
                            echo '<tr class="success">';
                        } elseif ($row['stan'] == 3)
                        {
                            echo '<tr class="danger">';
                        } else
                        {
                            $ilosc_prowadzonych++;
                            echo '<tr>';
                        }
                            $data = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                            $czas = date("G", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
                    
                            echo '<td>' . $x . '</td>';
                            $x--;
                            echo '<td>' . $data . '</td>';
                            echo '<td>' . $czas . '</td>';
                            echo '<td>' . $etap_wyklad[$row['stan']] . '</td>';
                            echo '<td>' . $row['nazwa'] . '</td>';
                            echo '<td> <a href="../page/wyklad.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
                echo '<tfoot>';
                    echo '<tr>';
                        echo '<td colspan="6"> W tym miesiącu prowadziłeś <b>' . $ilosc_prowadzonych  . ' </b>wykłady. </td>';
                    echo '</tr>';
                echo '</tfoot>';
            echo '</table>';
        } else
        {
            echo '<span> Nie zaplanowano wykładów </span>';
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
                    $data  = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data'])) . ', ' . date("G:i", strtotime($row['data']));
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
    
    
public function PokazWszystkieJazdyWSZkole()
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie jazdy w OSK </h3>';
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
                        if (($row['stan'] == 3) or ($row['stan'] == 4))
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
    
public function PokazWszystkieJazdy()
{
    
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Moje jazdy </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<a href="../page/add-jazdy.php">';
        echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
            echo 'Dodaj jazdy';
        echo '</div>';
    echo '</a>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
    
        $rok =  date('Y');
        $miesiac =  date('m');
        $ilosc_dni = date('t');
        
        $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, uczniowie.imie as uczen_imie, uczniowie.nazwisko as uczen_nazwisko, instruktorzy.show_car FROM jazdy,uczniowie,instruktorzy WHERE jazdy.uczen=uczniowie.id AND instruktorzy.id=jazdy.instruktor AND instruktorzy.id=' . $this->id . ' AND jazdy.data_rozpoczecia BETWEEN "' . $rok . '-' . $miesiac . '-01" AND "' . $rok . '-' . $miesiac . '-' . $ilosc_dni . '"  ORDER BY jazdy.data_rozpoczecia DESC, jazdy.id ASC';
        $result = $mysql->query($zapytanie);
        $wynik = 0;
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20" data-filtering="true" data-filter-placeholder="Szukaj" data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th data-breakpoints="xs">  </th>';
                        echo '<th> Data </th>';
                        echo '<th>  </th>';
                        echo '<th data-breakpoints="xs sm"> Czas trwania </th>';
                        echo '<th> Uczeń </th>';
                        echo '<th data-breakpoints="xs sm"> Samochód </th>';
                        echo '<th data-breakpoints="xs sm"> Stan </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    $x = $result->num_rows;
                    $all = 0;
                    while ($row = $result->fetch_array())
                    {
                        if (($row['stan'] == 3) or ($row['stan'] == 4))
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
                            echo '<td>' . $x . '</td>';
                            echo '<td>';
                            $x--;
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
                                $diff = abs(strtotime($row['data_zakonczenia']) - strtotime($row['data_rozpoczecia']));
                                $mod = $diff%3600;
                                $hour = ($diff-$mod)/3600;
                                $min = $mod/60;
                                
                                if ($row['stan'] == 2)
                                {
                                    $all += $diff;
                                }
                        
                                if ($min < 10)
                                {
                                    $min = '0' . $min;
                                }

                                echo $hour . ':' . $min . 'h';
                            echo '</td>';
                            echo '<td>';
                                echo $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'];
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
                echo '<tfoot>';
                    echo '<tr>';
                        $godzina = ($all-(floor($all%3600)))/3600;
                        $minuta = ($all-($godzina*3600))/60;
                        if ($minuta < 10)
                        {
                            $minuta = '0' . $minuta;
                        }
                        echo '<td colspan="8"> W tym miesiącu przeprowadziłeś <b>' . $godzina . ' godzin i ' . $minuta . ' minut </b> jazd. </td>';
                    echo '</tr>';
                echo '</tfoot>';
            echo '</table>';
        } else
        {
            echo '<span> Nie ma żadnych jazd </span>';
        }
    echo '</div>';

}
    
public function PokazWszystkieJazdyInstruktora()
{
    $mysql = connect_mysql(); 
    $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, uczniowie.imie, uczniowie.nazwisko, instruktorzy.show_car FROM jazdy,uczniowie,instruktorzy WHERE jazdy.uczen=uczniowie.id AND instruktorzy.id=jazdy.instruktor AND instruktorzy.pesel="' . $this->pesel . '" ORDER BY jazdy.data_rozpoczecia DESC';
    
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
                echo '<table class="table footable"  data-sorting="true" data-paging="true">';
                    $zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia,jazdy.notatka, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, jazdy.miejsce_spotkania, uczniowie.imie as uczen_imie, uczniowie.nazwisko as uczen_nazwisko, instruktorzy.show_car, uczniowie.telefon FROM jazdy,uczniowie,instruktorzy WHERE jazdy.uczen=uczniowie.id AND jazdy.stan=1 AND instruktorzy.id=jazdy.instruktor AND date(jazdy.data_rozpoczecia)="' . $row_dzien['dzien'] . '" AND instruktorzy.id=' . $this->id . '  ORDER BY jazdy.data_rozpoczecia ASC, jazdy.id ASC';
                    $result= $mysql->query($zapytanie);
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            echo '<td class="col-lg-2 col-lg-md-2 col-sm-5 col-xs-5">';
                                $start = date("H", strtotime($row['data_rozpoczecia'])) . ':' . date("i", strtotime($row['data_rozpoczecia']));
                                $koniec = date("H", strtotime($row['data_zakonczenia'])) . ':' . date("i", strtotime($row['data_zakonczenia']));

                                echo $start . ' - ' . $koniec;
                            echo '</td>';
                            echo '<td data-breakpoints="xs sm" data-title="Czas trwania" class="col-lg-1 col-md-1">';
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
                            echo '<td class="col-lg-3 col-md-3 col-sm-6 col-xs-6">' . $row['uczen_imie'] . ' ' . $row['uczen_nazwisko'] . '</td>';
                            echo '<td data-breakpoints="xs sm" data-title="Miejsce spotkania" class="col-lg-2 col-md-2">' . $row['miejsce_spotkania'] . '</td>';
                            echo '<td data-breakpoints="xs sm" data-title="Samochód" class="col-lg-2 col-md-2">';
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
                            echo '<td data-breakpoints="xs sm" data-title="Telefon" class="col-lg-1 col-md-2">' . $row['telefon'] . '</td>';
                            echo '<td data-type="html" class="col-lg-1 col-md-1 col-sm-1 col-xs-1"> <a href="../page/jazda.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</table>';
            }
        }
    echo '</div>';
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
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true">';
                echo '<thead>';
                    echo '<th>  </th>';
                    echo '<th> Data </th>';
                    echo '<th>  </th>';
                    echo '<th> Czas trwania </th>';
                    echo '<th> Samochód </th>';
                    echo '<th> Instruktor </th>';
                    echo '<th> Stan </th>';
                    echo '<th data-type="html"> </th>';
                echo '</thead>';
                echo '<tbody>';
                    $x = $result->num_rows;
                    $ile_odwolano = 0;
                    $wynik = 0;
                    while ($row = $result->fetch_array())
                    {
                        if (($row['stan'] == 3) or ($row['stan'] == 4))
                        {
                            $ile_odwolano++;
                            echo '<tr class="danger">';
                        } elseif ($row['stan'] == 1)
                        {
                            echo '<tr class="success">';
                            $wynik = $wynik + abs(strtotime($row['data_zakonczenia']) - strtotime($row['data_rozpoczecia']));
                        } else
                        {
                            $wynik = $wynik + abs(strtotime($row['data_zakonczenia']) - strtotime($row['data_rozpoczecia']));
                            echo '<tr>';
                        }
                        
                            
                            echo '<td data-value="' . $x . '">' . $x . '</td>';
                            $x--;
                            echo '<td>';
                                $dzien = date("j", strtotime($row['data_rozpoczecia'])) . ' ' . $month[date("n", strtotime($row['data_rozpoczecia']))] . ' ' . date("Y", strtotime($row['data_rozpoczecia']));
                                echo $dzien;
                            echo '</td>';
                            echo '<td>';
                                $czas = date("H", strtotime($row['data_rozpoczecia'])) . ':' . date("i", strtotime($row['data_rozpoczecia'])) . ' - ' . date("H", strtotime($row['data_zakonczenia'])) . ':' . date("i", strtotime($row['data_zakonczenia']));
                                $start = strtotime($row['data_rozpoczecia']);
                                $koniec = strtotime($row['data_zakonczenia']);
                                $roznica = $koniec-$start;
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
                        
                                if ($row['stan'] == 3)
                                {
                                    $zapytanie_kara = 'SELECT odwolane_jazdy.cena FROM odwolane_jazdy WHERE jazda=' . $row['id'];
                                    $result_kara = $mysql->query($zapytanie_kara);
                                    
                                    if ($result_kara->num_rows > 0)
                                    {
                                        $row_kara = $result_kara->fetch_array();
                                        echo ' - ' . $row_kara['cena'] . 'zł';
                                    } else
                                    {
                                        echo ' - bez kary';
                                    }

                                }
                                
                            echo '</td>';
                            echo '<td> <a href="../page/jazda.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                        echo '</tr>';
                    }
                echo '</tbody>';
                echo '<tfoot>';
                    echo '<tr>';
                        $godzina = ($wynik-(floor($wynik%3600)))/3600;
                        $minuta = ($wynik-($godzina*3600))/60;
                        if ($minuta < 10)
                        {
                            $minuta = '0' . $minuta;
                        }
                        $czas = $godzina . ':' . $minuta;
                        
            
                        $all = $row_uczen['jazdy'];
                        $diff = $all*3600 - $wynik;
            
                        $dgodzina = ($diff-(floor($diff%3600)))/3600;
                        $dminuta = ($diff-($dgodzina*3600))/60;
            
                        echo '<td colspan="8"> W sumie rozpisano <b>' . $czas . '</b> godzin jazd. Do końca pozostało <b> ' . $dgodzina . ' godzin i ' . $dminuta. ' minut</b>. Anulowano <b>' . $ile_odwolano . '</b> spotkań </td>';
                    echo '</tr>';
                echo '</tfoot>';
            echo '</table>';
        } else
        {
           echo '<span> Nie ma żadnych jazd </span>';
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
        $zapytanie = 'SELECT jazdy.notatka,jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia, jazdy.stan, uczniowie.imie, uczniowie.nazwisko,uczniowie.id as uczen_id, instruktorzy.id as instruktor_id, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, jazdy.samochod, jazdy.miejsce_spotkania FROM jazdy, uczniowie, instruktorzy WHERE jazdy.uczen=uczniowie.id AND  instruktorzy.id=jazdy.instruktor AND jazdy.id=' . $id;
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
                
                if ($row['stan'] == 3)
                {
                    echo '<td>'; 
                    echo $etap_jazdy[$row['stan']];
                    
                    $zapytanie_kara = 'SELECT odwolane_jazdy.cena FROM odwolane_jazdy WHERE jazda=' . $id;
                    $result_kara = $mysql->query($zapytanie_kara);
                    
                    if ($result_kara->num_rows == 0)
                    {
                        echo ' - bez kary';
                    } else
                    {
                        $row_kara = $result_kara->fetch_array();
                        echo ' -  kara ' . $row_kara['cena'] . 'zł';
                    }
                    
                    echo '</td>';
                } else
                {
                    echo '<td>' . $etap_jazdy[$row['stan']] . '</td>';
                }
            echo '</tr>';      
            echo '<tr>';
                echo '<td> Uczeń </td>';
                echo '<td> <a href="../page/uczen.php?id=' . $row['uczen_id'] . '">' . $row['imie'] . ' ' . $row['nazwisko'] . '</a></td>';
            echo '<tr>';     
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
        $button[0]['typ'] = 'normal';
        $button[0]['cat_icon'] = 'material-icons';
        $button[0]['icon'] = 'mode_edit';
        $button[0]['text'] = 'Edytuj';
        $button[0]['href'] = '../page/edit-jazda.php?id=' . $id;
    
        if ($row['stan'] == 1)
        {
            $button[1]['typ'] = 'normal';
            $button[1]['cat_icon'] = 'fa';
            $button[1]['icon'] = 'fa-adjust';
            $button[1]['text'] = 'Jazdy odbyły się w danym terminie';
            $button[1]['href'] = '../script/jazdy-change.php?id=' . $id;
        } else
        {
            $button[1]['typ'] = 'clear';
            $button[1]['cat_icon'] = '';
            $button[1]['icon'] = '';
            $button[1]['text'] = '';
            $button[1]['href'] = '';
        }
        
    
        $this->buttons($button);
            
    
    echo '</div>';
echo '</div>'; 
    
}   
    
public function edit_jazda ($id)
{
   
$mysql = connect_mysql ();
include ('../script/month.php');
    
$zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia,jazdy.notatka, jazdy.data_zakonczenia, jazdy.stan,uczniowie.id as uczen_id, uczniowie.imie, uczniowie.nazwisko, instruktorzy.show_car, jazdy.samochod FROM jazdy, uczniowie, instruktorzy WHERE jazdy.uczen=uczniowie.id AND  instruktorzy.id=jazdy.instruktor AND instruktorzy.id=' . $this->id.  ' AND jazdy.id=' . $id;
$result = $mysql->query($zapytanie);
if ($result->num_rows == 0)
{
    header ("Location: ../page/dashboard.php");
    exit();
}
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
                        echo '<select name="stan" id="stan" class="form-control input-sm">';
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
                $kara = '';
                if ($row['stan'] == 3)
                {
                    echo '<div class="form-group kara">';
                    $zapytanie_kara = 'SELECT odwolane_jazdy.cena FROM odwolane_jazdy WHERE jazda=' . $id;
                    $result_kara = $mysql->query($zapytanie_kara);
                    
                    if ($result_kara->num_rows > 0)
                    {
                        $row_kara = $result_kara->fetch_array();
                        $kara = $row_kara['cena'];
                    }
                    
                    
                } else
                {
                    echo '<div class="form-group kara" style="display:none">';
                }
                
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Kara';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="number" name="kara" class="form-control input-sm" min="0" value="' . $kara . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Kursant';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input class="form-control input-sm" disabled type="text" value="' . $row['imie'] . ' ' . $row['nazwisko'] . '">';
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
                    echo 'Opis jazd';
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
  
public function edit_kafel ()
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
    
public function jazda_kafel()
{
  /*
    [][0] - klasa ikony
    [][1] - podpis
    [][2] - strona
    [][3] - kolor
    */    
    global $stan_id;
    global $jazdy_id;
    global $instruktor_id;
    
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT instruktorzy.pesel FROM instruktorzy, jazdy WHERE instruktorzy.id=jazdy.instruktor AND jazdy.id=' . $jazdy_id;
    $result = $mysql->query($zapytanie);
    $row = $result->fetch_array();
    
    
    if (($stan_id==1) and ($_SESSION['pesel'] == $row['pesel']))
    {
    $cube[1][0] = 'whatshot';
    $cube[1][1] = 'Odwołaj jazdy';
    $cube[1][2] = '../script/odwolaj_jazdy.php?id=' . $jazdy_id;
    $cube[1][3] = '#5fafe4';
        
    $cube[3][0] = 'explore';
    $cube[3][1] = 'Jazdy się odbyły w danym terminie';
    $cube[3][2] = '../script/jazdy-change.php?id=' . $jazdy_id;
    $cube[3][3] = '#7dbde9';    
    } else 
    {
    $cube[1][0] = '';
    $cube[1][1] = '';
    $cube[1][2] = '';
    $cube[1][3] = '#5fafe4';   
        
    $cube[3][0] = '';
    $cube[3][1] = '';
    $cube[3][2] = '';
    $cube[3][3] = '#7dbde9';    
    }
    
    
    $cube[2][0] = 'mode_edit';
    $cube[2][1] = 'Edytuj';
    $cube[2][2] = '../page/edit-jazda.php?id=' . $jazdy_id;
    $cube[2][3] = '#6eb4e5';
    
    $cube[4][0] = '';
    $cube[4][1] = '';
    $cube[4][2] = '#';
    $cube[4][3] = '#8cc3ea';
    
    
    $cube[5][0] = '';
    $cube[5][1] = 'Podgląd jazd';
    $cube[5][2] = '../page/podglad_jazd.php?id=' . $jazdy_id;
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
    
function showPowiadomienia ()
{
$mysql = connect_mysql(); 

    
$zapytanie = 'SELECT powiadomienia.nadawca, powiadomienia.numer, powiadomienia.id_tresc FROM powiadomienia WHERE powiadomienia.stan=1 AND powiadomienia.odbiorca="' . $this->pesel . '" ORDER BY data DESC';
    
$result = $mysql->query($zapytanie);

    
//echo '<div class="all_pow">';
//        echo '<a href="all_powiadomienia.php"> Pokaż wszystkie powiadomienia </a>';
//    echo '</div>';    
    
if ($result->num_rows == 0)
{    
    echo '<span> Nie ma żadnych powiadomień </span>';
} 
echo '<table class="table table-hover">';
while ($row = $result->fetch_array())
{

$status = $row['numer'];
$nadawca = $row['nadawca'];  
$tresc = $row['id_tresc'];
echo '<tr>';
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
                
                if ($result_admin->num_rows == 1)
                {
                    $row_admin = $result_admin->fetch_array();
                    echo 'Administrator ' . $row_admin['imie'] . ' ' . $row_admin['nazwisko'];    
                } else
                {
                    $zapytanie_uczen = 'SELECT uczniowie.imie, uczniowie.nazwisko FROM uczniowie WHERE uczniowie.pesel="' . $nadawca . '"';
                    $result_uczen = $mysql->query($zapytanie_uczen);
                    $row_uczen =  $result_uczen->fetch_array();
                    echo 'Uczeń ' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'];
                }
                
            }
            
            $zapytanie_data = 'SELECT data_rozpoczecia FROM jazdy WHERE id=' . $tresc;
            $result_data = $mysql->query($zapytanie_data);
            $row_data = $result_data->fetch_array();
            $data = $row_data['data_rozpoczecia'];
            include ('../script/month.php');
            $data_dzien = date("j", strtotime($data));
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
            // DODANO UCZNIA  
            $zapytanie_uczen = 'SELECT uczniowie.imie, uczniowie.nazwisko FROM uczniowie WHERE uczniowie.id=' . $tresc;
            $result_uczen = $mysql->query($zapytanie_uczen);
            $row_uczen = $result_uczen->fetch_array();
            echo '<td>';
                echo 'Dodano ucznia ' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'];
            echo '</td>';       
            break;
        }
    case 3:
        {
            break;
        }
    case 4:
        {
            $zapytanie_wyklady = 'SELECT wyklady.data FROM wyklady WHERE wyklady.id=' . $tresc;
            $result_wyklady = $mysql->query($zapytanie_wyklady);
            $row_wyklady = $result_wyklady->fetch_array();
            $data = $row_wyklady['data'];
            include ('../script/month.php');
            $data_dzien = date("j", strtotime($data));
            $data_miesiac = $month[date("n", strtotime($data))];
            $data_rok = date("Y", strtotime($data));
            $data_godzina = date("G", strtotime($data));
            $data_minuta = date("i", strtotime($data));
            echo '<td>';
            echo 'Odwołano wykłady w dniu ' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . ' roku o godzinie ' . $data_godzina . ':' . $data_minuta;
            echo '</td>';
            break;
        }
}
echo '</tr>';
}
echo '</table>';
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
    
    // ILOŚC KAR
    
    $zapytanie_kara = 'SELECT odwolane_jazdy.cena FROM odwolane_jazdy WHERE uczen=' . $id;
    $result_kara = $mysql->query($zapytanie_kara);

    while ($row_kara = $result_kara->fetch_array())
    {
        $caly_koszt += $row_kara['cena'];
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
    $szerokosc = (100-(floor($czas)/$caly_czas*100));     
    }   
    
    
    $zapytanie_ocena = 'SELECT zadania_wyzwania.zakres, zadania_wyzwania.waga, zadania_uczen.ocena FROM zadania_uczen, zadania_wyzwania WHERE zadania_wyzwania.id=zadania_uczen.zadanie AND zadania_uczen.uczen=' . $id;
    
    $result_uczen = $mysql->query($zapytanie_ocena);
    
    $suma = 0; // MAKSYMALNY WYNIK JAKI MOŻNA ZDOBYĆ
    $uzyskany_wynik = 0; // UZYSKANY WYNIK;
    while ($row_uczen = $result_uczen->fetch_array())
    {
        switch ($row_uczen['zakres'])
        {
            case 1:   
                {
                    $min = 0;
                    $max = 1;
                    break;
                }
            case 2:   
                {
                    $min = -1;
                    $max = 1;
                    break;
                }
            case 3:   
                {
                    $min = -5;
                    $max = 5;
                    break;
                }
            case 4:   
                {
                    $min = 0;
                    $max = 4;
                    break;
                }
            default:   
                {
                    $min = 0;
                    $max = 1;
                    break;
                }
        }
        
        $uzyskany_wynik  = $uzyskany_wynik + ($row_uczen['ocena']* $row_uczen['waga']);
        $suma = $suma + ($max* $row_uczen['waga']);
    }
    
    
    
    if (($uzyskany_wynik < 0) or ($suma <= 0))
    {
        $postep = 0;
    } else
    {
        $postep =  floor($uzyskany_wynik/$suma*100);
    }
    

    
    if ($row['instruktor'] == $this->id)
    {
        echo '<a href="../page/podglad_jazd.php?id=' . $id . '">';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_postep">';
                echo '<div class="postep" style="width:' . $postep . '%"> ' . $postep . '% </div>';
                echo '<div class="time" style="width:' . $szerokosc .  '%"> </div>';
            echo '</div>';
        echo '</a>';    
    } else
    {
       echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile_postep">';
            echo '<div class="postep" style="width:' . $postep . '%"> ' . $postep . '% </div>';
            echo '<div class="time" style="width:' . $szerokosc .  '%"> </div>';
        echo '</div>'; 
    }
    
    

    echo '<a href="../page/add-dodatkowe-jazdy.php?id=' . $id . '">';
        echo '<div class="col-lg-2 col-lg-offset-10 col-md-2 col-md-offset-10 col-sm-2 col-sm-offset-10 col-xs-8 col-xs-offset-4 profile_upgrade"> Powiększ szkolenie </div>';
    echo '</a>';
    
    
//    echo '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 profile_h3"> Znajomi </div>';
//    echo '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 profile_friends">';
//        $this->friends($id);
//    echo '</div>';
echo '</div>';   
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}


    

}     
    
    
public function export_to_pdf_user ()
{
    $mysql = connect_mysql();
    
    echo '<form method="POST" action="../script/export-to-pdf-user.php">';
    
    
    
        echo '<table>';
            echo '<tr>';
                echo '<td> Uczeń </td>';
                echo '<td>';
                    if (isset($_GET['id']))
                    {
                        $id = $_GET['id'];
                        $zapytanie_uczen = 'SELECT imie, nazwisko FROM uczniowie WHERE id='. $id;
                        $result_uczen = $mysql->query($zapytanie_uczen);

                        if ($result_uczen->num_rows == 1)
                        {
                            $row_uczen = $result_uczen->fetch_array();
                            echo '<input type="hidden" name="uczen" value="' . $id . '">';
                            echo $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'];
                        } else
                        {
                            header ("Location: ../page/dashboard.php");
                            exit();
                        }
                    } else
                    {
                        echo '<select name="uczen">';
                            $zapytanie_uczen = 'SELECT id, imie, nazwisko FROM uczniowie ORDER BY nazwisko,imie';
                            $result_uczen = $mysql->query($zapytanie_uczen);
                            while ($row_uczen = $result_uczen->fetch_array())
                            {
                                echo '<option value="' . $row_uczen['id'] . '">' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'] . '</option>';
                            }
                        echo '</select>';
                    }
                echo '</td>';
            echo '</tr>';
            
        echo '</table>';
    
    
        echo '<h2> Rzeczy do wyeksportowania </h2>';
        echo '<table>';
            echo '<tr>';
                echo '<td>';
                    echo '<input type="checkbox" name="info" value="info"  style="width:auto">';
                echo '<td>';
                echo '<td>';
                    echo 'Informacje';
                echo '<td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td>';
                    echo '<input type="checkbox" name="wyklad" value="wyklad" style="width:auto">';
                echo '<td>';
                echo '<td>';
                    echo 'Wykłady';
                echo '<td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td>';
                    echo '<input type="checkbox" name="jazdy" value="jazdy" style="width:auto">';
                echo '<td>';
                echo '<td>';
                    echo 'Jazdy';
                echo '<td>';
            echo '</tr>';
    
        echo '</table>';
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
            while ($row_uczniowie = $result_uczniowie->fetch_array())
            {
                echo '<tr>';
                    echo '<td>' . $row_uczniowie['imie'] . ' ' . $row_uczniowie['nazwisko'] . '</td>';
                echo '</tr>';
            }
        echo '</table>';
    
    
    echo '</div>';
}
    
      
public function export_to_pdf_user_kafel ()
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
    
    
public function PokazWszystkiePowiadomienia()
{

$mysql = connect_mysql();
        
    
$zapytanie = 'SELECT date(data) AS dzien, count(*) AS ilosc FROM powiadomienia WHERE odbiorca="' . $this->pesel . '" GROUP BY dzien ORDER BY dzien DESC';
$result = $mysql->query($zapytanie);
  

    
if ($result->num_rows > 0)    
{
 
while($row = $result->fetch_array())
{
    $data = $row['dzien'];
    
    $zapytanie_pow_ucz = 'SELECT powiadomienia.id, powiadomienia.data, powiadomienia.numer, powiadomienia.ID_tresc, powiadomienia.nadawca, uczniowie.imie, uczniowie.nazwisko FROM uczniowie, powiadomienia WHERE uczniowie.pesel=powiadomienia.nadawca AND powiadomienia.odbiorca="' . $this->pesel . '" AND date(data)="' . $data . '"';
    
    $result_pow = $mysql->query($zapytanie_pow_ucz);
    
    if ($result_pow->num_rows == 0)
    {
        $zapytanie_pow_ins = 'SELECT powiadomienia.id, powiadomienia.data, powiadomienia.numer, powiadomienia.ID_tresc, powiadomienia.nadawca, instruktorzy.imie, instruktorzy.nazwisko FROM instruktorzy, powiadomienia WHERE instruktorzy.pesel=powiadomienia.nadawca AND powiadomienia.odbiorca="' . $this->pesel . '" AND date(data)="' . $data . '"';
    
        $result_pow = $mysql->query($zapytanie_pow_ins); 
        
        if ($result_pow->num_rows == 0)
        {
            $zapytanie_pow_adm = 'SELECT powiadomienia.id, powiadomienia.data, powiadomienia.numer, powiadomienia.ID_tresc, powiadomienia.nadawca, administratorzy.imie, administratorzy.nazwisko FROM administratorzy, powiadomienia WHERE administratorzy.pesel=powiadomienia.nadawca AND powiadomienia.odbiorca="' . $this->pesel . '" AND date(data)="' . $data . '"';
    
            $result_pow = $mysql->query($zapytanie_pow_adm);  
        } 
    }
    
    include ('../script/month.php');
    $data_dzien = date("j", strtotime($data));
    $data_miesiac = $month[date("n", strtotime($data))];
    $data_rok = date("Y", strtotime($data));
    
    
    
    echo '<h3>' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . '</h3>';
    echo '<table>';
        while ($row_pow = $result_pow->fetch_array())
        {   
            
     
        
            $status = $row_pow['numer'];
            $nadawca = $row_pow['nadawca'];  
            $tresc = $row_pow['ID_tresc'];

switch ($status)
{
    case 1:
        {     
            $zapytanie_instruktor = 'SELECT instruktorzy.imie, instruktorzy.nazwisko FROM instruktorzy WHERE instruktorzy.pesel="' . $nadawca . '"';
            $result_instruktor = $mysql->query($zapytanie_instruktor);
            $file = '../page/jazda.php?id=' . $tresc; 
            echo '<tr onclick="document.location =\'' . $file . '\';">';
            echo '<td>';
            if ($result_instruktor->num_rows == 1)
            {
                $row_instruktor = $result_instruktor->fetch_array();
                echo 'Instruktor ' . $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'];
            } else
            {
                $zapytanie_admin = 'SELECT administratorzy.imie, administratorzy.nazwisko FROM administratorzy WHERE administratorzy.pesel="' . $nadawca . '"';
                
                $result_admin = $mysql->query($zapytanie_admin);
                
                if ($result_admin->num_rows == 1)
                {
                    $row_admin = $result_admin->fetch_array();
                    echo 'Administrator ' . $row_admin['imie'] . ' ' . $row_admin['nazwisko'];    
                } else
                {
                    $zapytanie_uczen = 'SELECT uczniowie.imie, uczniowie.nazwisko FROM uczniowie WHERE uczniowie.pesel="' . $nadawca . '"';
                    $result_uczen = $mysql->query($zapytanie_uczen);
                    $row_uczen =  $result_uczen->fetch_array();
                    echo 'Uczeń ' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'];
                }
                
            }
            
            $zapytanie_data = 'SELECT data_rozpoczecia FROM jazdy WHERE id=' . $tresc;
            $result_data = $mysql->query($zapytanie_data);
            $row_data = $result_data->fetch_array();
            $data = $row_data['data_rozpoczecia'];
            include ('../script/month.php');
            $data_dzien = date("j", strtotime($data));
            $data_miesiac = $month[date("n", strtotime($data))];
            $data_rok = date("Y", strtotime($data));
            $data_godzina = date("G", strtotime($data));
            $data_minuta = date("i", strtotime($data));
            echo ' odwołał jazdy w dniu ' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . ' roku o godzinie ' . $data_godzina . ':' . $data_minuta;
            echo '</td>';
            echo '</tr>';
            break;
        }
    case 2:
        {
            $zapytanie_uczen = 'SELECT uczniowie.imie, uczniowie.nazwisko FROM uczniowie WHERE uczniowie.id=' . $tresc;
            $result_uczen = $mysql->query($zapytanie_uczen);
            $row_uczen = $result_uczen->fetch_array();
            $file = '../page/uczen.php?id=' . $tresc; 
            echo '<tr onclick="document.location =\'' . $file . '\';">';
            echo '<td>';
                echo 'Dodano ucznia ' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'];
            echo '</td>';
            echo '</tr>';        
            break;
        }
    case 3:
        {
            break;
        }
    case 4:
        {
            $zapytanie_wyklady = 'SELECT wyklady.data FROM wyklady WHERE wyklady.id=' . $tresc;
            $result_wyklady = $mysql->query($zapytanie_wyklady);
            $row_wyklady = $result_wyklady->fetch_array();
            $data = $row_wyklady['data'];
            include ('../script/month.php');
            $data_dzien = date("j", strtotime($data));
            $data_miesiac = $month[date("n", strtotime($data))];
            $data_rok = date("Y", strtotime($data));
            $data_godzina = date("G", strtotime($data));
            $data_minuta = date("i", strtotime($data));
            echo '<tr>';
            echo '<td>';
            echo 'Odwołano wykłady w dniu ' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . ' roku o godzinie ' . $data_godzina . ':' . $data_minuta;
            echo '</td>';
            echo '</tr>';
            break;
        }
}
        }
    echo '</table>';
    
    
}
    
} else
{
    echo '<span> Nie ma żadnych powiadomień </span>';
}
    
    
}
    
public function show_profil_uczen_all($id)
{
    include ('../script/month.php');
    $mysql = connect_mysql();
    $zapytanie = 'SELECT uczniowie.pesel,uczniowie.notatka, uczniowie.pkk, uczniowie.imie, uczniowie.nazwisko, uczniowie.plec, uczniowie.data_urodzenia,uczniowie.cena, uczniowie.jazdy, uczniowie.telefon,uczniowie.data_zapisu, uczniowie.email,uczniowie.stan,uczniowie.data_zapisu, uczniowie.kurs,instruktorzy.id as instruktor_id, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, uczniowie.miejsce_spotkan, kategoria_prawo_jazdy.skrot FROM uczniowie, instruktorzy, kategoria_prawo_jazdy WHERE kategoria_prawo_jazdy.id=uczniowie.kategoria AND uczniowie.instruktor=instruktorzy.id  AND uczniowie.id=' . $id;
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
                        $zapytanie_grupa = 'SELECT grupy.nazwa FROM grupy, kursanci_grupa WHERE grupy.id=kursanci_grupa.grupa AND kursanci_grupa.kursant=' . $id;
                        $result_grupa = $mysql->query($zapytanie_grupa);
                        $grupa = '';
                        while ($row_grupa = $result_grupa->fetch_array())
                        {
                            $grupa .= $row_grupa['nazwa'] . ',';
                        }
                        echo '<td> Grupa </td>';
                        echo '<td>' . $grupa . '</td>';
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
                if ($row['instruktor_id'] == $this->id)
                {
                    $button[0]['typ'] = 'normal';
                    $button[0]['cat_icon'] = 'material-icons';
                    $button[0]['icon'] = 'mode_edit';
                    $button[0]['text'] = 'Edytuj';
                    $button[0]['href'] = '../page/edit-uczen.php?id=' . $id;
                } else
                {
                    $button[0]['typ'] = 'clear';
                    $button[0]['cat_icon'] = '';
                    $button[0]['icon'] = '';
                    $button[0]['text'] = '';
                    $button[0]['href'] = '';
                }
        
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
        //header ("Location: ../page/dashboard.php");
        exit();
    }
}
    
public function PokazZadaniaInstruktora ()
{
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT zadania_instruktor_kategorie.id, zadania_instruktor_kategorie.level, zadania_kategoria.nazwa FROM zadania_instruktor_kategorie, zadania_kategoria WHERE zadania_instruktor_kategorie.kategoria=zadania_kategoria.id AND zadania_instruktor_kategorie.instruktor=' . $this->id . ' ORDER BY zadania_instruktor_kategorie.level';
    
    $result = $mysql->query($zapytanie);
    
    
    echo '<form method="POST" action="../script/zadania_instruktor.php">';
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Zadania instruktora </h3>';
    echo '</div>';
    
    // PRZYCISK
    echo '<button class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button text-center">';
        echo 'Zapisz ustawienia';
    echo '</button>';
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        
        while ($row = $result->fetch_array())
        {
            echo '<input type="text" name="category-' . $row['id'] . '" class="input-md form-control" value="' . $row['nazwa'] . '">';
            echo '<table class="table">';
                $zapytanie_zadania = 'SELECT zadania_wyzwania.id, zadania_wyzwania.nazwa, zadania_wyzwania.zakres FROM zadania_wyzwania WHERE zadania_wyzwania.kategoria=' . $row['id'];
                $result_zadania = $mysql->query($zapytanie_zadania);
            
                while ($row_zadania = $result_zadania->fetch_array())
                {
                    echo '<tr>';
                        echo '<td>';
                            echo '<input name="task-' . $row_zadania['id'] . '-name" class="form-control input-sm" type="text" value="' . $row_zadania['nazwa'] . '">';
                        echo '</td>';
                        echo '<td>';
                            echo '<select name="task-' . $row_zadania['id'] . '-range" class="form_control input-sm">';
                                if ($row_zadania['zakres'] == 1)
                                {
                                    echo '<option selected value="1"> ZALICZONE/NIEZALICZONE </option>';
                                } else
                                {
                                    echo '<option value="1"> ZALICZONE/NIEZALICZONE </option>';
                                }
                    
                                if ($row_zadania['zakres'] == 2)
                                {
                                    echo '<option selected value="2"> ŹLE - NEUTRALNIE - DOBRZE </option>';
                                } else
                                {
                                    echo '<option value="2"> ŹLE - NEUTRALNIE - DOBRZE </option>';
                                }
                    
                                if ($row_zadania['zakres'] == 3)
                                {
                                    echo '<option selected value="3"> OD -5 DO 5 </option>';
                                } else
                                {
                                    echo '<option value="3"> OD -5 DO 5 </option>';
                                }
                    
                                if ($row_zadania['zakres'] == 4)
                                {
                                    echo '<option selected value="4"> OD 0 DO 4 </option>';
                                } else
                                {
                                    echo '<option value="4"> OD 0 DO 4 </option>';
                                }                   
                            echo '</select>';
                        echo '</td>';
                        echo '<td>';
                            echo '<a href="../script/usun_zadanie.php?id=' . $row_zadania['id'] . '">';
                                echo '<div class="btn btn-primary"> Usuń </div>';
                            echo '</a>';
                        echo '</td>';
                    echo '</tr>';
                }
            
            
            echo '</table>';
        }
    
    echo '</form>';
    
    echo '<h4> Dodaj nowe zadanie </h4>';
    
    echo '<form method="POST" action="../script/dodaj_zadanie.php">';
        echo '<div class="form-group">';
            echo '<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                echo 'Nazwa';
            echo '</label>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                echo '<input type="text"  name="nazwa" class="input-sm form-control">';
            echo '</div>';
        echo '</div>'; 
        echo '<div class="form-group">';
            echo '<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                echo 'Kategoria';
            echo '</label>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                echo '<select class="input-sm form-control" name="kategoria">';
                    $zapytanie_kategorie = 'SELECT zadania_kategoria.id, zadania_kategoria.nazwa FROM zadania_kategoria ORDER BY id';
                    $result_kategoria = $mysql->query($zapytanie_kategorie);
    
                    while ($row_kategoria = $result_kategoria->fetch_array())
                    {
                        echo '<option value="' . $row_kategoria['id'] . '">' . $row_kategoria['nazwa'] . '</option>';
                    }
                    
                echo '</select>';
            echo '</div>';
        echo '</div>'; 
        echo '<div class="form-group">';
            echo '<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                echo 'Zakres';
            echo '</label>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                echo '<select class="input-sm form-control" name="kategoria">';
                    echo '<option value="1"> ZALICZONE/NIEZALICZONE </option>';
                    echo '<option value="2"> ŹLE - NEUTRALNIE - DOBRZE </option>';
                    echo '<option value="3"> OD -5 DO 5 </option>';
                    echo '<option value="4"> OD 0 DO 4 </option>';
                echo '</select>';
            echo '</div>';
        echo '</div>'; 
        echo '<button class="btn btn-primary col-xs-12"> Dodaj </button>';
    
    echo '</form>';
    
    echo '</div>';
    
    
    
    
}

public function settings ()
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
$zapytanie = 'SELECT password.login,instruktorzy.czas_wygasniecia,instruktorzy.czas_jazdy, instruktorzy.show_car FROM instruktorzy,password WHERE password.pesel=instruktorzy.pesel AND instruktorzy.pesel="' . $this->pesel . '"';
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
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Czas jazdy';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        $czas =  date("H:i", strtotime($row['czas_jazdy']));
                        echo '<input type="time" value="' . $czas . '" name="czas_jazdy" class="input-sm form-control">';
                    echo '</div>';
                echo '</div>';     
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wyświetlanie samochodu';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="show_car" class="input-sm form-control">';
                            if ($row['show_car'] == 0)
                            {
                                echo '<option selected value="0"> Po rejestracji </option>';
                                echo '<option value="1"> Po nazwie </option>';
                            } else
                            {
                                echo '<option value="0"> Po rejestracji </option>';
                                echo '<option selected value="1"> Po nazwie </option>'; 
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
}
    
public function add_user ()
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 title">';
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
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" oninput="add_uczen_pesel()" id="pesel" name="pesel" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Imię';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="imie" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Nazwisko';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="nazwisko" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Data urodzenia';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" id="data_urodzenia" name="data_urodzenia" autocomplete="off" onchange="wiek()" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wiek';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" disabled id="age" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Płeć';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
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
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="telefon" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'E-mail';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
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
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="pkk" autocomplete="off" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';    
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Kategoria';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
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
                        echo 'Rodzaj kursu';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
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
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="jazdy" id="jazdy" disabled value="' . $jazdy . '" class="input-sm form-control">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Cena';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" id="cena" name="cena" disabled value="' . $cena . '" class="input-sm form-control">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Etap kursu';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
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
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="date" name="data_zapisu" value="' . date('Y-m-d') . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Grupa';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
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
                        echo 'Instruktor';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="instruktor" class="form-control input-sm">';
                            $zapytanie_instruktorzy = 'SELECT id, imie, nazwisko FROM instruktorzy ORDER BY nazwisko';
                            $result_instruktorzy = $mysql->query($zapytanie_instruktorzy);
                            while ($row_instruktorzy = $result_instruktorzy->fetch_array())
                            {
                                if ($row_instruktorzy['imie'] == 'Nie wybrano')
                                {
                                    echo '<option selected value="' . $row_instruktorzy['id'] . '">' . $row_instruktorzy['imie'] . ' ' . $row_instruktorzy['nazwisko'] . ' </option>';
                                } else
                                {
                                    echo '<option  value="' . $row_instruktorzy['id'] . '">' . $row_instruktorzy['imie'] . ' ' . $row_instruktorzy['nazwisko'] . ' </option>';
                                }
                                
                            }
                        echo '</select>';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wpłata';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="number" min="0" name="wplata" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Miejsce spotkań';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="miejsce" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';  
            echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Hasło';
                    echo '</label>';
                    echo '<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="passwd" value="admin" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';
                echo 'Ustalenia przy zapisie';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form">';
                echo '<div class="form-group col-md-11 col-lg-12 col-ms-12 col-xs-12 col-centered">';
                    echo '<textarea class="form-control" name="notatka"> </textarea>';
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
    
public function PokazWszystkichUczniow() 
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Moi kursanci </h3>';
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
            $zapytanie = 'SELECT uczniowie.id, uczniowie.imie as uczen_imie, uczniowie.nazwisko as uczen_nazwisko, uczniowie.pesel, uczniowie.data_zapisu, uczniowie.stan, uczniowie.kurs, uczniowie.jazdy, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, uczniowie.procent, kategoria_prawo_jazdy.skrot FROM uczniowie, instruktorzy, kategoria_prawo_jazdy WHERE uczniowie.instruktor=instruktorzy.id AND kategoria_prawo_jazdy.id=uczniowie.kategoria AND instruktorzy.id="' . $this->id . '" AND !(uczniowie.stan=5 OR uczniowie.stan=6) ORDER BY uczniowie.data_zapisu DESC';
            $result= $mysql->query($zapytanie);
    
            echo '<thead>';
                echo '<tr>';
                    echo '<th> Kursant </th>';
                    echo '<th data-breakpoints="xs sm md"> Data zapisu </th>';
                    echo '<th data-breakpoints="xs sm md"> Kategoria </th>';
                    echo '<th data-breakpoints="xs sm md"> Kurs </th>';
                    echo '<th data-breakpoints="xs sm "> Stan </th>';
                    echo '<th data-breakpoints="xs" data-type="number" > Wykłady </th>'; 
                    echo '<th data-breakpoints="xs"> Jazdy </th>';
//                    echo '<th data-breakpoints="xs sm md"> % </th>';
                    echo '<th data-type="html"> </th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                while ($row = $result->fetch_array())
                {
                    if ($row['stan'] == 4)
                    {
                        echo '<tr class="info">';
                    } 
                    else
                    {
                        echo '<tr>';
                    }
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
                    
                        $caly_czas = $row['jazdy'];
                        
                        $zapytanie_dodatkowe = 'SELECT dodatkowe_jazdy.cena, dodatkowe_jazdy.jazdy FROM dodatkowe_jazdy WHERE  dodatkowe_jazdy.uczen=' . $id;
                        $result_dodatkowe = $mysql->query($zapytanie_dodatkowe);

                        while ($row_dodatkowe = $result_dodatkowe->fetch_array())
                        {
                            $caly_czas += $row_dodatkowe['jazdy'];
                        } 
                        
                        echo '<td data-sort-value="' . round($czas)  . '">';                    
                            echo round($czas) . '/' . $caly_czas;
                        echo '</td>';
//                        echo '<td>';
//                            echo $row['procent'];
//                        echo '</td>';
                        echo '<td> <a href="../page/uczen.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';
                    echo '</tr>';
                }
            echo '</tbody>';
        echo '</table>';
    echo '</div>';
    
    
    
}  
    

    
public function edit_profil_uczen ($id)
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
$mysql = connect_mysql();    
$zapytanie = 'SELECT uczniowie.pesel,uczniowie.notatka, uczniowie.imie, uczniowie.nazwisko, uczniowie.data_zapisu, uczniowie.plec,uczniowie.miejsce_spotkan, uczniowie.data_urodzenia, uczniowie.telefon, uczniowie.email, uczniowie.stan, uczniowie.pkk, uczniowie.kurs, uczniowie.jazdy, uczniowie.cena, uczniowie.kategoria, instruktorzy.imie as instruktor_imie, instruktorzy.nazwisko as instruktor_nazwisko, kategoria_prawo_jazdy.skrot FROM uczniowie, instruktorzy, kategoria_prawo_jazdy WHERE instruktorzy.id=uczniowie.instruktor AND kategoria_prawo_jazdy.id=uczniowie.kategoria AND uczniowie.id="' . $id . '" AND uczniowie.instruktor=' . $this->id; 
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
                            echo 'Grupa';
                        echo '</label>';
                        echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                            echo '<select class="input-sm form-control" name="grupa[]" id="select2" multiple>';
                                $zapytanie_grupy = 'SELECT id, nazwa FROM grupy ORDER BY nazwa';
                                $result_grupy = $mysql->query($zapytanie_grupy);
                                $zapytanie_kursant_grupy = 'SELECT grupa FROM kursanci_grupa WHERE kursant=' . $id;
                                $result_kursant_grupy = $mysql->query($zapytanie_kursant_grupy);
                                while ($row_kursant_grupy = $result_kursant_grupy->fetch_array())
                                {
                                    $grupy[] = $row_kursant_grupy['grupa'];
                                }
                                
                                while ($row_grupy = $result_grupy->fetch_array())
                                {

                                    $x = false;
                                    foreach ($grupy as $grupa)
                                    {
                                        if ($grupa == $row_grupy['id'])
                                        {
                                            $x = true;
                                        }
                                    }
                                    
                                    if ($x == true)
                                    {
                                        echo '<option selected="selected" value="' . $row_grupy['id'] . '">' . $row_grupy['nazwa'] . '</option>'; 
                                    } else
                                    {
                                        echo '<option value="' . $row_grupy['id'] . '">' . $row_grupy['nazwa'] . '</option>';
                                    }
                                    
                                }
                            echo '</select>';
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
                            echo '<input disabled="disabled" class="input-sm form-control" type="text" value="' . $row['instruktor_imie'] . ' ' . $row['instruktor_nazwisko'] . '">';
                        echo '</div>';
                    echo '</div>'; 
                echo '</div>';
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                    echo 'Ustalenia przy zapisie';
                    echo '<br> <span style="font-size:14px"> Notatka jest widziana dla kursanta </span>';
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
                        echo '<input type="time" id="start_jazda" name="start_jazda" onchange="jazdaChange()" value="13:00" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Godzina - koniec';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        $zapytanie_instruktor = 'SELECT czas_jazdy FROM instruktorzy WHERE id=' . $this->id;
                        $result_instruktor = $mysql->query($zapytanie_instruktor);
                        $row_instruktor = $result_instruktor->fetch_array();
                        $time1 = '13:00:00';
                        $time2 = $row_instruktor['czas_jazdy'];
                        $time = strtotime($time1) + strtotime($time2) - strtotime('00:00:00');
                        $time = date('H:i', $time);
                        echo '<input type="hidden" id="czas_jazd" value="' . $time2 . '">';
                        echo '<input type="time" name="end_jazda" id="koniec_jazda" value="' . $time . '" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Kursant';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        if ($id > 0)
                        {
                            echo '<input type="hidden" name="id" value="' . $id . '">';
                            echo '<select disabled name="kursant" id="add_jazda_kursant" class="form-control input-sm">';
                            
                            $zapytanie_uczen = 'SELECT id, imie, nazwisko, miejsce_spotkan FROM uczniowie WHERE id=' . $id;
                        } else
                        {
                            echo '<select name="id" id="add_jazda_kursant" class="form-control input-sm">';
                            $zapytanie_uczen = 'SELECT id, imie, nazwisko, miejsce_spotkan FROM uczniowie WHERE instruktor="' . $this->id . '" AND !(uczniowie.stan=5 OR uczniowie.stan=6) ORDER BY imie';
                        }
                            
                            
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
    
                mysqli_data_seek ($result_uczen,0);
    
    
                while ($row_uczen =  $result_uczen->fetch_array())
                {
                    echo '<input type="hidden" id="add_jazda_m-' . $row_uczen['id'] . '" value="' . $row_uczen['miejsce_spotkan'] . '">';
                }
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Instruktor';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="hidden" name="instruktor" value="' . $this->id . '">';
                        echo '<input type="text" class="form-control input-sm" disabled value="' . $this->imie . ' ' . $this->nazwisko . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Samochód';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select name="samochod" class="form-control input-sm">';
                            echo '<option value="0"> Nie wybrałem samochodu </option>';
                            $zapytanie_car = 'SELECT id, rejestracja, marka, model,nazwa FROM samochody';
                            $result_car = $mysql->query($zapytanie_car);
                            $zapytanie = 'SELECT show_car FROM instruktorzy WHERE id=' . $this->id;
                            $result = $mysql->query($zapytanie);
                            $row = $result->fetch_array();
                            
                            while ($row_car = $result_car->fetch_array())
                            {
                                if ($row['show_car'] == 0)
                                {
                                    $samochod = $row_car['rejestracja'];
                                } else
                                {
                                    $samochod = $row_car['nazwa'];
                                }
                                echo '<option value="' . $row_car['id'] . '"> ' . $samochod . ' </option>';
                            }

                        echo '</select>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Miejsce spotkania';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" name="miejsce_spotkania" class="input-sm form-control" id="add_jazda_miejsce_spotkania">';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 subtitle">';   
                echo 'Opis jazd';
            echo '</div>';
            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';   
                echo '<p style="font-size:13px"> Notatka jest widziana dla kursanta! </p>';
                echo '<textarea name="notatka" style="width:100%;height:200px"></textarea>';
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

    
    
public function small_menu ()
{ 
echo '<li> <a href="#"> Twój profil </a> </li>';
//echo '<li> <a href="../page/instruktor.php"> Twój profil </a> </li>';
echo '<li> <a href="../other/pytania.php"> Pytania </a> </li>';
echo '<li> <a href="../page/settings.php"> Ustawienia </a> </li>';
echo '<li> <a href="../page/logout.php"> Wyloguj </a> </li>';
}
    
public function show_profil_admin ($id)
{
$mysql = connect_mysql();    
$zapytanie = 'SELECT pesel, imie, nazwisko, telefon, email   FROM administratorzy WHERE administratorzy.id="' . $id . '"';
    
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
    
public function news ()
{
    
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
            echo '<div class="kafel ostatnikafel" style="background-color:' . $cube[$x][3] . '">';
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
                        echo '<th>  </th>';
                        echo '<th> Data </th>';
                        echo '<th data-title="Godzina">  </th>';
                        echo '<th data-breakpoints="xs sm"> Instruktor </th>';
                        echo '<th data-breakpoints="xs sm md"> Stan </th>';
                        echo '<th data-breakpoints="xs sm"> Miejsce </th>';
                        echo '<th data-type="html"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    $x = $result->num_rows;
                    while ($row = $result->fetch_array())
                    {
                        if ($row['stan'] == 1)
                        {
                            echo '<tr class="success">';
                        } elseif ($row['stan'] == 3)
                        {
                            echo '<tr class="danger">';
                        } else
                        {
                            echo '<tr>';
                        }
                            $data = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                            $czas = date("G", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
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
                            echo '<td>' . $x . '</td>';
                            $x--;
                            echo '<td>' . $data . '</td>';
                            echo '<td>' . $czas . '</td>';
                            echo '<td>' . $instruktor . '</td>';
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
        $zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan, instruktorzy.id AS instruktor_id,instruktorzy.imie, instruktorzy.nazwisko, tematy_wyklad.temat, kategorie_wyklad.nazwa,wyklady_miejsce.id AS wyklad_id,wyklady_miejsce.nazwa AS miejsce, tematy_wyklad.id AS temat_id, kategorie_wyklad.id AS kategoria_id, grupy.nazwa as grupa FROM wyklady, instruktorzy, tematy_wyklad, kategorie_wyklad, wyklady_miejsce, grupy, wyklad_grupa WHERE wyklady_miejsce.id=wyklady.miejsce AND wyklad_grupa.wyklad=wyklady.id AND grupy.id=wyklad_grupa.grupa AND wyklady.instruktor=instruktorzy.id AND wyklady.temat=tematy_wyklad.id AND tematy_wyklad.kategoria=kategorie_wyklad.id  AND wyklady.id="' . $id . '"'; 
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
                echo '<td>' . $data . ', ' . $czas . '</td>';
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
            echo '<tr>';
                echo '<td> Grupa </td>';
                echo '<td>' . $row['grupa']  . '</td>';
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
    
        $button[1]['typ'] = 'clear';
        $button[1]['cat_icon'] = '';
        $button[1]['icon'] = '';
        $button[1]['text'] = '';
        $button[1]['href'] = '';
    
        $this->buttons($button);
            
    
    echo '</div>';
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
                        echo '<input type="time" name="time" value="13:00" class="form-control input-sm">';
                    echo '</div>';
                echo '</div>'; 
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Wykładowca';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<input type="text" class="form-control input-sm" value="' . $this->imie  . ' ' . $this->nazwisko . '" disabled> <input type="hidden" name="wykladowca" value="' . $this->id . '">';
                    echo '</div>';
                echo '</div>';
                echo '<div class="form-group">';
                    echo '<label class="control-label col-lg-3 col-md-12 col-sm-12 col-xs-12">';
                        echo 'Grupa';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" name="grupa">';
                            $zapytanie_grupy = 'SELECT id, nazwa FROM grupy ORDER BY nazwa';
                            $result_grupy = $mysql->query($zapytanie_grupy);
                            while ($row_grupy = $result_grupy->fetch_array())
                            {
                                echo '<option value="' . $row_grupy['id'] . '">' . $row_grupy['nazwa'] . '</option>';
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
      

public function edit_wyklad($id)
{
    
$mysql = connect_mysql ();
include ('../script/month.php');
    
$zapytanie = 'SELECT wyklady.id, wyklady.data, wyklady.stan, wyklady.instruktor, wyklady.temat, instruktorzy.imie, instruktorzy.nazwisko,wyklady.miejsce, tematy_wyklad.kategoria, wyklad_grupa.grupa  FROM wyklady,instruktorzy, tematy_wyklad, wyklad_grupa WHERE wyklad_grupa.wyklad=wyklady.id AND wyklady.instruktor=instruktorzy.id AND wyklady.temat=tematy_wyklad.id AND wyklady.id=' . $id; 

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
                        echo '<select name="instruktor"  class="form-control input-sm">';
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
                        echo 'Grupa';
                    echo '</label>';
                    echo '<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">';
                        echo '<select class="form-control input-sm" name="grupa">';
                            $zapytanie_grupy = 'SELECT id, nazwa FROM grupy ORDER BY nazwa';
                            $result_grupy = $mysql->query($zapytanie_grupy);
                            while ($row_grupy = $result_grupy->fetch_array())
                            {
                                if ($row_grupy['id'] == $row['grupa'])
                                {
                                    echo '<option selected value="' . $row_grupy['id'] . '">' . $row_grupy['nazwa'] . '</option>';
                                } else
                                {
                                    echo '<option value="' . $row_grupy['id'] . '">' . $row_grupy['nazwa'] . '</option>';
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
        $wynik = 0;
        $result = $mysql->query($zapytanie);
        if ($result->num_rows > 0)
        {
            echo '<table class="footer table">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Wpłata </th>';
                        echo '<th> Instruktor </th>';
//                        echo '<th data-type="html"> </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            $wynik = $wynik + $row['kasa'];
                            $data = date("d", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                            echo '<td>' . $data . '</td>';
                            echo '<td>' . $row['kasa'] . '</td>';
                            echo '<td>' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
//                            echo '<td> <a href="../page/wplata.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';  
                        echo '</tr>';
                    }
                echo '</tbody>';
                echo '<tfoot>';
                    echo '<tr>';
                        $zapytanie_uczen = 'SELECT cena FROM uczniowie WHERE id=' . $id;
                        $result_uczen = $mysql->query($zapytanie_uczen);
                        $row_uczen = $result_uczen->fetch_array();
                        $all = $row_uczen['cena'];
            
                        $zapytanie_kara = 'SELECT odwolane_jazdy.cena FROM odwolane_jazdy WHERE uczen=' . $id;
                        $result_kara = $mysql->query($zapytanie_kara);

                        while ($row_kara = $result_kara->fetch_array())
                        {
                            $all += $row_kara['cena'];
                        }
                
                        echo '<td colspan="3"> Do tej pory wpłacono <b> ' . $wynik . ' </b> na <b> ' . $all . ' PLN</B>. Do końca pozostało <b>' . ($all-$wynik) . ' PLN </b>. </td>';
                    echo '</tr>';
                echo '</tfoot>';
            echo '</table>';
        } else
        {
            echo '<span> Nie wpłaciłeś jeszcze żadnych pieniędzy </span>';
            exit();
        } 
    echo '</div>';
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
                        echo '<select name="pakiet" id="kurs" onchange="check_extra_drive()" class="input-sm form-control">';
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
    
    // BIAŁA KARTKA
    echo '<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 white-page">';
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Rejestracja </th>';
                        echo '<th> Nazwa </th>';
                        echo '<th> Marka </th>';
                        echo '<th data-breakpoints="xs sm md"> Model </th>';
                        echo '<th data-breakpoints="xs sm md"> Skrzynia </th>';
//                        echo '<th data-type="html">  </th>';
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
//                            echo '<td> <a href="../page/samochod.php?id=' . $row['id'] . '"> <i class="fa fa-info"> </i></a> </td>';            
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
            $button[0]['icon'] = 'local_parking';
            $button[0]['text'] = 'Dodaj przebieg';
            $button[0]['href'] = '../page/add-przebieg.php?id=' . $_GET['id'];
        
            $button[1]['typ'] = 'normal';
            $button[1]['cat_icon'] = 'material-icons';
            $button[1]['icon'] = 'bug_report';
            $button[1]['text'] = 'Pokaż cały przebieg';
            $button[1]['href'] = '../page/car_przebieg.php?id=' . $_GET['id'];
        
            $button[2]['typ'] = 'normal';
            $button[2]['cat_icon'] = 'material-icons';
            $button[2]['icon'] = 'local_gas_station';
            $button[2]['text'] = 'Dodaj tankowanie';
            $button[2]['href'] = '../page/add_paliwo.php?id=' . $_GET['id'];
        
            $button[3]['typ'] = 'normal';
            $button[3]['cat_icon'] = 'material-icons';
            $button[3]['icon'] = 'ev_station';
            $button[3]['text'] = 'Pokaż wszystkie tankowania';
            $button[3]['href'] = '../page/all_paliwo.php?id=' . $_GET['id'];

            $this->buttons($button);
        echo '</div>';
    echo '</div>';

    }
  
}
    
public function add_przebieg ($id)
{
    if (!isset($id))
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    // ŁĄCZENIE Z BAZĄ DANYCH
    $mysql = connect_mysql();
    
    // SPRAWDZAMY CZY SAMOCHÓD O PODANYM ID ISTNEIEJ I POBIERAMY AKTUALNY PRZEBIEG 
    $zapytanie_samochod = 'SELECT nazwa, rejestracja, marka, model FROM samochody WHERE id=' . $id;
    $result_samochod = $mysql->query($zapytanie_samochod);
    if ($result_samochod->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    $row_samochod = $result_samochod->fetch_array();
    echo '<h2>' . $row_samochod['nazwa'] . ' - ' . $row_samochod['rejestracja'] . '</h2>';
    echo '<form method="GET" action="../script/add-przebieg.php">';
    echo '<input type="hidden" name="car" value="' . $id . '">';
    echo '<table>';
    echo '<tr>';
    echo '<td> Przebieg </td>';
    echo '<td>';
    $zapytanie_przebieg = 'select id, data, instruktor, przebieg FROM przebieg WHERE przebieg.samochod=' . $id . ' ORDER BY data DESC LIMIT 1';
    $result_przebieg = $mysql->query($zapytanie_przebieg);
    if ($result_przebieg->num_rows == 1)
    {
        $row_przebieg = $result_przebieg->fetch_array();
        $data = $row_przebieg['data'];
        $data_dzien = date("j", strtotime($data));
        include ('../script/month.php');
        $data_miesiac = $month[date("n", strtotime($data))];
        $data_rok = date("Y", strtotime($data));
        $data_godzina = date("G", strtotime($data));
        $data_minuta = date("i", strtotime($data));
        echo $row_przebieg['przebieg'] . 'km z dnia ' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok , ' ' . $data_godzina . ':' . $data_minuta;
    } else
    {
        echo 'Nie podano';
    }
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Data </td>';
    echo '<td>';
    $current_data = date ('Y-m-d');
    $current_czas = date('H:i');
    echo '<input type="date" value="' . $current_data . '"  class="select_all_data" name="date"> <input class="select_all_data" type="time" name="time" value="' . $current_czas . '">';
    echo '</td>';
    echo '<tr>';
    echo '<td> Przebieg </td>';
    echo '<td>';
        if (isset($row_przebieg['przebieg']))
        {
            $przebieg = $row_przebieg['przebieg'];
        } else
        {
            $przebieg = 0;
        }
        echo '<input type="number" value="' . $przebieg . '" name="przebieg">';
    echo '</td>';
    echo '</tr>';
    echo '</table>';
}
    
public function add_przebieg_kafel ()
{
   $cube[1][0] = 'done';
    $cube[1][1] = 'Akceptuj';
    $cube[1][2] = '#';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = 'clear';
    $cube[2][1] = 'Anuluj';
    $cube[2][2] = '../page/samochod.php?id=' . $_GET['id'];
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
    
public function samochod_kafel()
{
  /*
    [][0] - klasa ikony
    [][1] - podpis
    [][2] - strona
    [][3] - kolor
    */   

    $cube[1][0] = 'local_parking';
    $cube[1][1] = 'Dodaj przebieg';
    $cube[1][2] = '../page/add-przebieg.php?id=' . $_GET['id'];
    $cube[1][3] = '#5fafe4';

    $cube[2][0] = 'bug_report';
    $cube[2][1] = 'Pokaż cały przebieg';
    $cube[2][2] = '../page/car_przebieg.php?id=' . $_GET['id'];
    $cube[2][3] = '#6eb4e5';
    
    $cube[3][0] = 'local_gas_station';
    $cube[3][1] = 'Dodaj tankowanie';
    $cube[3][2] = '../page/add_paliwo.php?id=' . $_GET['id'];
    $cube[3][3] = '#7dbde9';
    
    $cube[4][0] = 'ev_station';
    $cube[4][1] = 'Pokaż wszystkie tankowania';
    $cube[4][2] = '../page/all_paliwo.php?id=' . $_GET['id'];
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
    include ('../script/month.php');
    /*
    1 - WYKŁAD 
    2 - JAZDY
    3 - EGZAMIN
    */
        
    $c = 0;
    $a = 0;
    
    $zapytanie_wyklad = 'SELECT wyklady.id,wyklady.data, wyklady_miejsce.nazwa FROM wyklady, wyklady_miejsce WHERE wyklady_miejsce.id=wyklady.miejsce AND instruktor=' . $this->id . ' AND date(data)="' . $date . '" ORDER BY data';
    $result_wyklad = $mysql->query($zapytanie_wyklad); 
    while ($row_wyklad = $result_wyklad->fetch_array())
    {
        $wyklad[$c]['id'] = $row_wyklad['id'];
        $wyklad[$c]['data'] = $row_wyklad['data'];
        $wyklad[$c]['miejsce'] = $row_wyklad['nazwa'];
        $today[$a]['data'] = $row_wyklad['data'];
        $today[$a]['id'] = $c;
        $today[$a]['what'] = 1;
        $c++;
        $a++;
    }
    
    $zapytanie_jazdy = 'SELECT uczniowie.id, jazdy.data_rozpoczecia, jazdy.data_zakonczenia, jazdy.miejsce_spotkania, uczniowie.imie, uczniowie.nazwisko FROM jazdy,uczniowie WHERE jazdy.uczen=uczniowie.id AND jazdy.instruktor="' . $this->id . '" AND date(data_rozpoczecia)="' . $date . '" ORDER BY data_rozpoczecia'; 
    $result_jazdy = $mysql->query($zapytanie_jazdy);
    $c = 0;
    while ($row_jazdy = $result_jazdy->fetch_array())
    {
        $jazdy[$c]['id'] = $row_jazdy['id'];
        $jazdy[$c]['koniec'] = date("H:i", strtotime($row_jazdy['data_zakonczenia'])); 
        $jazdy[$c]['uczen'] = $row_jazdy['imie'] . ' ' . $row_jazdy['nazwisko'];
        $jazdy[$c]['gdzie'] = $row_jazdy['miejsce_spotkania'];
        $today[$a]['data'] = $row_jazdy['data_rozpoczecia'];
        $today[$a]['id'] = $c;
        $today[$a]['what'] = 2;
        $a++;
        $c++;
    }
    
    
    
    
    $zapytanie_egzamin = 'SELECT egzamin.id, egzamin.data, uczniowie.imie, uczniowie.nazwisko, egzamin.typ FROM uczniowie, egzamin WHERE egzamin.uczen=uczniowie.id AND uczniowie.instruktor=' . $this->id . ' AND date(egzamin.data)="' . $date . '" ORDER BY egzamin.data';
    $result_egzamin = $mysql->query($zapytanie_egzamin);
    $c = 0;
    while ($row_egzamin = $result_egzamin->fetch_array())
    {
        $egzamin[$c]['id'] = $row_egzamin['id'];
        $egzamin[$c]['data'] = $row_egzamin['data'];
        $egzamin[$c]['typ'] = $row_egzamin['typ'];
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
        
    echo '<table class="table" style="margin-bottom:0;">';
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
                    echo 'Wykłady w ' . $wyklad[$id]['miejsce'];
                    echo '</a>';
                    break;
                    }
                case 2:
                    {
                    $id = $today[$v]['id'];
                    echo '<a class="non-href" href="../page/uczen.php?id=' . $jazdy[$id]['id'] . '">';
                    echo  $jazdy[$id]['koniec'] . ' - Jazdy z ' . $jazdy[$id]['uczen'];
                    echo ' - ' . $jazdy[$id]['gdzie'];
                    
                    echo '</a>';
                    break;
                    }
                case 3:
                    {
                    $id = $today[$v]['id'];
                    echo '<a class="non-href" href="../page/egzamin.php?id=' . $egzamin[$id]['id'] . '">';
                    echo $egzamin_typ[$egzamin[$id]['typ']] . ' - ' . $egzamin[$id]['uczen'];
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
        echo '<span> Nie zaplonowano żadnych zajęć </span>';
        return 0;
    }
    
     
    
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
                            for ($x=1;$x<=count($egzamin_typ);$x++)
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
        $zapytanie = 'SELECT egzamin.id, egzamin.data, egzamin.stan, egzamin.typ, uczniowie.imie, uczniowie.nazwisko FROM uczniowie,egzamin WHERE egzamin.uczen=uczniowie.id AND uczniowie.instruktor=' . $this->id;
        $result = $mysql->query($zapytanie);
    
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th>  </th>';
                        echo '<th> Kursant </th>';
                        echo '<th data-breakpoints="xs sm"> Rodzaj </th>';
                        echo '<th data-breakpoints="xs sm"> Stan </th>';
                        echo '<th data-type="html">  </th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    while ($row = $result->fetch_array())
                    {
                        echo '<tr>';
                            $dzien = date("j", strtotime($row['data'])) . ' ' . $month[date("n", strtotime($row['data']))] . ' ' . date("Y", strtotime($row['data']));
                            $czas = date("H", strtotime($row['data'])) . ':' . date("i", strtotime($row['data']));
                            echo '<td>' . $dzien . '</td>';
                            echo '<td>' . $czas . '</td>';
                            echo '<td>' . $row['imie'] . ' ' . $row['nazwisko'] . '</td>';
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
        $zapytanie = 'SELECT egzamin.id, egzamin.data, egzamin.stan,egzamin.typ, uczniowie.imie, uczniowie.nazwisko FROM uczniowie,egzamin WHERE egzamin.uczen=uczniowie.id AND egzamin.stan=1 AND uczniowie.instruktor=' . $this->id;
        $result = $mysql->query($zapytanie);
    
        if ($result->num_rows > 0)
        {
            echo '<table class="table footable"  data-sorting="true" data-paging="true" data-paging-size="20"  data-paging-count-format="{CP} z {TP}">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th> Data </th>';
                        echo '<th> Kursant </th>';
                        echo '<th data-breakpoints="xs sm"> Rodzaj </th>';
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
    
    
public function show_uczen_jazdy_flitr($uczen)
{
        // ŁĄCZYMY SIĘ Z BAZĄ DANYCH
    $mysql = connect_mysql (); 
    
    $zapytanie = 'SELECT jazdy.data_rozpoczecia, uczniowie.id FROM jazdy, uczniowie WHERE jazdy.uczen=uczniowie.id AND uczniowie.id="' . $uczen . '" ORDER BY data_rozpoczecia DESC';
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 0)
    {
        return 0;
    }
    
    while ($row = $result->fetch_array())
    {
        $data = $row['data_rozpoczecia'];
        $instruktor_id = $row['id'];
    }

    $data_dzien = date("d", strtotime($data));
    $data_miesiac = date("m", strtotime($data));
    $data_rok = date("Y", strtotime($data));
    
    $current_dzien = date("d");
    $current_miesiac = date("m");
    $current_rok = date("Y");

    $koniec = "$data_rok-$data_miesiac-$data_dzien";
    $start = "$current_rok-$current_miesiac-$current_dzien";
    
    // WYŚWIETLAMY FLITROWANIE
    echo '<i class="material-icons"> sort </i>';
    echo '<div>';
        echo '<b> Data </b>';
        echo '<input type="date" id="data_start" value="' . $koniec. '" onchange="flitr_all_jazdy ()">';
        echo '<input type="date" id="data_end" value="' . $start . '" onchange="flitr_all_jazdy ()">';
        echo '<input type="hidden" id="instruktor" value="' . $instruktor_id . '">';
        echo '<br>';
        echo '<b> Samochód </b>';
        // WYŚWIETLAMY SAMOCHODY
        $zapytanie_car = 'SELECT samochody.nazwa, samochody.id, samochody.rejestracja, samochody.model, samochody.marka FROM samochody';
        $result_car = $mysql->query($zapytanie_car);
        $zapytanie_show_car = 'SELECT show_car FROM instruktorzy WHERE pesel="' . $this->pesel . '"'; // SPRAWDZENIE JAK MA BYĆ WYŚWIETLANY SAMOCHÓD
        $result_show_car = $mysql->query($zapytanie_show_car); 
        $row_show_car = $result_show_car->fetch_array();
        echo '<input type="hidden" id="ile_samochod" value="' . $result_car->num_rows . '">';
        $x = 1;
        while ($row_car = $result_car->fetch_array())
        {
            echo '<input type="hidden" id="car_id_' . $x . '" value="' . $row_car['id'] . '">';
            echo '<input checked="checked" id="car_' . $x . '" type="checkbox" onchange="flitr_all_jazdy ()">';
            if ($row_show_car['show_car'] == 0)
            {
                echo ' ' .$row_car['rejestracja'] . '-' . $row_car['marka'] . ' ' . $row_car['model'];
            } else
            {
                echo ' ' . $row_car['nazwa'];   
            }
            $x++;
            echo '<br>';
        }
        // UCZNIOWIE
        echo '<b> Uczeń </b>';
        $zapytanie_uczen = 'SELECT uczniowie.id, uczniowie.imie, uczniowie.nazwisko FROM uczniowie, instruktorzy WHERE instruktorzy.id=uczniowie.instruktor AND (uczniowie.stan="3" OR uczniowie.stan="4" OR uczniowie.stan="5") AND instruktorzy.pesel="' . $this->pesel . '"';
        $result_uczen = $mysql->query($zapytanie_uczen);
        echo '<select id="uczen" onchange="flitr_all_jazdy ()">';
            echo '<option value="0"> Wszyscy uczniowie </option>';
            while ($row_uczen = $result_uczen->fetch_array())
            {
                echo '<option value="' . $row_uczen['id'] . '">' . $row_uczen['imie'] . ' ' . $row_uczen['nazwisko'] . '</option>';
            }
        echo '</select>'; 
        echo '<br>';
        echo '<b> Stan </b>';
        echo '<input checked="checked" id="stan_1" type="checkbox" onchange="flitr_all_jazdy ()"> Zaplanowane <br>';
        echo '<input checked="checked" id="stan_2"  type="checkbox" onchange="flitr_all_jazdy ()"> Odbyły się <br>';
        echo '<input checked="checked" id="stan_3"  type="checkbox" onchange="flitr_all_jazdy ()"> Odwołane przez ucznia  <br>';
        echo '<input checked="checked" id="stan_4"  type="checkbox" onchange="flitr_all_jazdy ()"> Odwołane przez instruktora <br>';
    echo '</div>';
}
    
public function show_car_przebieg ($id)
{
    if (isset($_GET['id']))
    {
    $mysql = connect_mysql();
    $zapytanie = 'SELECT date(data) as dzien, count(*) as ile FROM przebieg WHERE samochod=' . $id . ' GROUP BY dzien ORDER BY dzien DESC;';
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows == 0)
    {
        echo '<span> Nie dodano żadnego przebiegu </span>';
        exit();
    }
    
    while ($row = $result->fetch_array())
    {
        $data = $row['dzien'];
        $ile = $row['ile'];
        $start = 0;
        $koniec = 0;
        $i_start = 1;
        $data_dzien = date("j", strtotime($data));
        include ('../script/month.php');
        $data_miesiac = $month[date("n", strtotime($data))];
        $data_rok = date("Y", strtotime($data));
        
        echo '<h2>' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . '</h2>';
        $zapytanie_dzien = 'SELECT id, data, instruktor,przebieg FROM przebieg WHERE samochod=' . $id . ' AND date(data)="' . $data . '" ORDER BY data DESC';
        $result_dzien = $mysql->query($zapytanie_dzien);
        if ($result_dzien->num_rows != 0)
        {
            echo '<table>';
                while($row_dzien = $result_dzien->fetch_array())
                {
                if ($i_start == 1)
                {
                    $koniec = $row_dzien['przebieg'];
                }
                $i_start++;
                $start = $row_dzien['przebieg'];
                    
                echo '<tr>';
                    $data = $row_dzien['data'];
                    $data_godzina = date("G", strtotime($data));
                    $data_minuta = date("i", strtotime($data));
                    echo '<td>' . $data_godzina . ':' . $data_minuta . '</td>';
                    if ($row_dzien['instruktor'] == null)
                    {
                        $instruktor = 'Nie podano';
                    } else
                    {
                        $zapytanie_instruktor = 'SELECT imie, nazwisko FROM instruktorzy WHERE id=' . $row_dzien['instruktor'];
                        $result_instruktor = $mysql->query($zapytanie_instruktor);
                        if ($result_instruktor->num_rows == 1)
                        {
                            $row_instruktor = $result_instruktor->fetch_array();
                            $instruktor = $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'];
                        } else
                        {
                            $instruktor = 'Nie podano';
                        }
                    }
                    echo '<td>' . $instruktor . '</td>';
                    echo '<td>' . $row_dzien['przebieg'] . 'km</td>';
                echo '</tr>';
                }
                echo '<tr>';
                        echo '<td colspan="3"><b> Przejechano ' . ($koniec-$start) . 'km </b> </td>';
                echo '</tr>';
                
            echo '</table>';
        }
        
    }
    
    } else
    {
        header ("Location: ../page/dashboard.php");
    }
}
    
public function add_paliwo ($id)
{
    // SPRAWDZAMY CZY ISTNIEJE ZMIENNA 
    if (!isset($id))
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    // ŁĄCZYMY Z BAZĄ DANYCH
    $mysql = connect_mysql ();
    
    // SPRAWDZAMY CZY ISTNIEJE TAKI SAMOCHÓD
    $zapytanie_samochod = 'SELECT id, nazwa, rejestracja FROM samochody WHERE id=' . $id;
    $result_samochod = $mysql->query($zapytanie_samochod);
    if ($result_samochod->num_rows == 0)
    {
        header ("Location: ../page/dashboard.php");
        exit();   
    }
    $row_samochod = $result_samochod->fetch_array();
    // OSTATNIE TANKOWANIE
    $zapytanie_paliwo = 'SELECT paliwo, data FROM paliwo WHERE samochod=' . $id . ' ORDER BY data DESC LIMIT 1';
    $result_paliwo = $mysql->query($zapytanie_paliwo);
    if ($result_paliwo->num_rows == 0)
    {
        $paliwo = 'Nie tankowano';
    } else
    {
        $row_paliwo = $result_paliwo->fetch_array();
        $data = $row_paliwo['data'];
        $data_dzien = date("j", strtotime($data));
        include ('../script/month.php');
        $data_miesiac = $month[date("n", strtotime($data))];
        $data_rok = date("Y", strtotime($data));
        $paliwo = $row_paliwo['paliwo'] . 'l w dniu ' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok;
    }
    echo '<form method="GET" action="../script/add-paliwo.php">';
    echo '<table>';
        echo '<tr>';
            echo '<td> Ostatnie tankowanie </td>';
            echo '<td>' . $paliwo . '</td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Samochód </td>';
            echo '<td>' . $row_samochod['nazwa'] . '</td>';
            echo '<input type="hidden" name="samochod" value="' . $row_samochod['id'] . '">';
        echo '</tr>';
        echo '<tr>';    
            $data = date("Y-m-d");
            $time = date("H:i");
            echo '<td> Data </td>';
            echo '<td> <input type="date" name="data" class="select_all_data" value="' . $data . '"> <input type="time" name="time" class="select_all_data" value="' . $time . '"> </td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Ilość </td>';
            echo '<td> <input type="number" name="paliwo" id="paliwo" oninput="paliwo_cena()"> </td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td> Koszt </td>';
            echo '<td> <input type="number" oninput="paliwo_cena()" id="cena" name="cena" step="0.01"> </td>';
        echo '</tr>';
        echo '<tr style="display:none" id="paliwo-r">';
            echo '<td> </td>';
            echo '<td id="paliwo-l"> </td>';
        echo '</tr>';    
        echo '<tr>';
            echo '<td> Karta - bezgotówkowo </td>';
            echo '<td> <input type="checkbox" name="karta"> </td>';
        echo '</tr>';

    echo '</table>';
    
    
}
    
public function biezacy_portfel()
{
    $mysql = connect_mysql();
    $zapytanie_portfel = 'SELECT budzet.aktualnie FROM budzet, instruktorzy WHERE budzet.instruktor=instruktorzy.id AND instruktorzy.pesel=' . $_SESSION['pesel'] . ' ORDER BY data DESC LIMIT 1';
    $result_portfel = $mysql->query($zapytanie_portfel);
    if ($result_portfel->num_rows == 0)
    {
        $aktualny_portfel = 0;
    } else
    {
        $row_portfel = $result_portfel->fetch_array();
        $aktualny_portfel = $row_portfel['aktualnie'];
    }
    return $aktualny_portfel . ' ZŁ';
}
    
public function show_car_paliwo ($id)
{
    
if (!isset($_GET['id']))
{
    header ("Location: ../page/dashboard.php");
}
    
$mysql = connect_mysql();
    
$zapytanie = 'SELECT paliwo.id, paliwo.data, paliwo.paliwo, paliwo.cena, paliwo.instruktor FROM paliwo WHERE samochod=' . $id . ' ORDER BY data DESC';
$result = $mysql->query($zapytanie);
if ($result->num_rows == 0)
{
    echo '<span> Nie tankowano jeszcze </span>';
    exit();
} else
{
echo '<table>';
echo '<thead>';
    echo '<tr>';
        echo '<td> Data </td>';
        echo '<td> Ile litrów? </td>';
        echo '<td> Cena </td>';
        echo '<td> Koszt za litr </td>';
        echo '<td> Instruktor </td>';
    echo '<tr>';
echo '</thead>';    
while ($row = $result->fetch_array())
{
    echo '<tr>';
        $data = $row['data'];
        $data_dzien = date("j", strtotime($data));
        include ('../script/month.php');
        $data_miesiac = $month[date("n", strtotime($data))];
        $data_rok = date("Y", strtotime($data));
        $data_time = date("H:i", strtotime($data));
        echo '<td>' . $data_dzien . ' ' . $data_miesiac . ' ' . $data_rok . ' ' . $data_time . '</td>';
        echo '<td>' . $row['paliwo'] . '</td>';
        echo '<td>' . $row['cena'] . '</td>';
        if ($row['paliwo'] == 0)
        {
            $cena_za_litr = 0;
        } else
        {
            $cena_za_litr = round($row['cena']/$row['paliwo'],2);
        }
        echo '<td>' . $cena_za_litr . '</td>';
        if ($row['instruktor'] == 0)
        {
            $instruktor = 'Nie podano';
        } else
        {
            $zapytanie_instruktor = 'SELECT imie, nazwisko FROM instruktorzy WHERE id=' . $row['instruktor'];
            $result_instruktor = $mysql->query($zapytanie_instruktor);
            $row_instruktor = $result_instruktor->fetch_array();
            $instruktor = $row_instruktor['imie'] . ' ' . $row_instruktor['nazwisko'];
        }
        echo '<td>' . $instruktor . '</td>';
    echo '</tr>';
}
echo '</table>';
}    
}
    
public function add_paliwo_kafel()
{
    $cube[1][0] = 'done';
    $cube[1][1] = 'Akceptuj';
    $cube[1][2] = '#';
    $cube[1][3] = '#5fafe4';
    
    $cube[2][0] = 'clear';
    $cube[2][1] = 'Anuluj';
    $cube[2][2] = '../page/samochod.php?id=' . $_GET['id'];
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
    
public function PokazWszystkieGrupy()
{
    // ŁACZENIE Z BAZĄ I DOŁĄCZANIE WYMAGANYCH PLIKÓW
    $mysql = connect_mysql();
    include ('../script/month.php');
    
    // TYTUŁ
    echo '<div class="col-lg-9 col-md-9 col-sm-10 col-xs-8 title">';
        echo '<h3> Wszystkie grupy </h3>';
    echo '</div>';
    
    
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
    
public function message ($id)
{
    
    $mysql = connect_mysql();    
    include ('../script/month.php');   

    $zapytanie = 'SELECT message_user.id, message_user.title, message_user.text, message_user.reading, message_user.sender, message_user.date, message_user.receiver FROM message_user WHERE message_user.sender=' . $this->id . ' AND message_user.id=' . $id;
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
        echo '<b> Odbiorca: </b>';
            $zapytanie_odbiorca = 'SELECT uczniowie.id,uczniowie.imie, uczniowie.nazwisko FROM uczniowie WHERE uczniowie.id=' . $row['receiver'];
            $result_odbiorca = $mysql->query($zapytanie_odbiorca);

            if ($result_odbiorca->num_rows == 0)
            {
                echo 'Nie wiadomo kto wysłał wiadomość. Zgłoś to Administracji';
            } else
            {
                $row_odbiorca = $result_odbiorca->fetch_array();
                echo '<a href="../page/uczen.php?id=' . $row_odbiorca['id'] . '">' . $row_odbiorca['imie'] . ' ' . $row_odbiorca['nazwisko'] . '</a>';
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
    
public function send_message()
{
    
    $mysql = connect_mysql();
    
    include ('../script/month.php');

    echo '<form method="POST" action="../script/send_message.php">';
    
    // TYTUŁ
    echo '<div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-10 col-xs-8 input-title title">';
        echo '<input type="text" class="form-control input-md" name="temat" placeholder="Temat">';
    echo '</div>';
    
    // PRZYCISK
    echo '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4 add_button_submit text-center">';
        echo '<input type="submit" class="edit-temat-submit" value="Wyślij">';
    echo '</div>';
    
    echo '<div class="form-group col-lg-10 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12 edit-temat-input">';
        echo '<label for="category"> Odbiorcy </label>';

        echo '<select class="select2 form-control input-sm" name="odbiorca[]" multiple="multiple">';
        
            echo '<optgroup label="Stopień">';
                foreach ($etap  as $id_et => $et)
                {
                    echo '<option value="st-' . $id_et . '">' . $et . '</option>';
                }
            echo '</optgroup>';
    
            echo '<optgroup label="Grupy">';
                $zapytanie_grupy = 'SELECT id, nazwa FROM grupy';
                $result_grupy = $mysql->query($zapytanie_grupy);
                while ($row_grupy = $result_grupy->fetch_array())
                {
                    echo '<option value="gr-' . $row_grupy['id'] . '">' . $row_grupy['nazwa'] . '</option>';
                }
            echo '</optgroup>';
            echo '<optgroup label="Kursanci">';
                $zapytanie_nadawca = 'SELECT id, imie, nazwisko FROM uczniowie';
                $result_nadawca = $mysql->query($zapytanie_nadawca);
                while ($row_nadawca = $result_nadawca->fetch_array())
                {
                    echo '<option value="ku-' . $row_nadawca['id'] . '">' . $row_nadawca['imie'] . ' ' . $row_nadawca['nazwisko'] . '</option>';
                }
            echo '</optgroup>';
        echo '</select>';
    echo '</div>';   
    
    echo '<div class="form-group col-lg-10 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12 edit-temat-input">';
        echo '<label for="opis"> Treść </label>';
            echo '<textarea name="tresc" class="form-control input-md">';
        echo '</textarea>';
    echo '</div>';   
    
    echo '</form>';
}

    
}
?>