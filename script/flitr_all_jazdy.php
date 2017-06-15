<?php




$data_start = $_GET['data_start'];
$data_end = $_GET['data_end'];
$uczen = $_GET['uczen'];
$instruktor = $_GET['instruktor'];
$stan1 = $_GET['stan1'];
$stan2 = $_GET['stan2'];
$stan3 = $_GET['stan3'];
$stan4 = $_GET['stan4'];
$samochod = $_GET['samochod'];






include ('../script/mysql.php');

$mysql = connect_mysql();


$zapytanie = 'SELECT jazdy.id,jazdy.data_rozpoczecia, jazdy.data_zakonczenia,jazdy.samochod, jazdy.stan, uczniowie.imie, uczniowie.nazwisko, instruktorzy.show_car FROM jazdy,uczniowie,instruktorzy WHERE jazdy.uczen=uczniowie.id AND instruktorzy.id=jazdy.instruktor AND instruktorzy.id="' . $instruktor . '" AND  date(jazdy.data_zapisu) BETWEEN "' . $data_start . '" AND "' . $data_end . '"';

if ($uczen != 0)
{
    $zapytanie =  $zapytanie . ' AND uczniowie.id="' . $uczen . '"';
}

for ($x=1;$x<=$samochod;$x++)
{
    $car[$x]['id'] = $_GET['car_id_' . $x];
    $car[$x]['check'] = $_GET['car_' . $x];
    
    if ($car[$x]['check'] == 'false')   
    {
    $zapytanie =  $zapytanie . ' AND jazdy.samochod!=' . $car[$x]['id'];   
    }  
}

if ($stan1 == 'false')
{
    $zapytanie = $zapytanie . ' AND jazdy.stan!=1';
}
if ($stan2 == 'false')
{
    $zapytanie = $zapytanie . ' AND jazdy.stan!=2';
}
if ($stan3 == 'false')
{
    $zapytanie = $zapytanie . ' AND jazdy.stan!=3';
}
if ($stan4 == 'false')
{
    $zapytanie = $zapytanie . ' AND jazdy.stan!=4';
}

$zapytanie = $zapytanie . ' ORDER BY jazdy.data_rozpoczecia DESC';

$result = $mysql->query($zapytanie);

    if ($result->num_rows > 0)
    {
        echo '<table id="all_jazdy">';
            echo '<thead>';
                echo '<tr>';
                    echo '<td> Data rozpoczęcia </td>';
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
                $start_dzien = date("j", strtotime($start));
                $start_miesiac = $month[date("n", strtotime($start))];
                $start_rok = date("Y", strtotime($start));
                $start_godzina = date("G", strtotime($start));
                $start_minuta = date("i", strtotime($start));

                $koniec_dzien = date("j", strtotime($koniec));
                $koniec_miesiac = $month[date("n", strtotime($koniec))];
                $koniec_rok = date("Y", strtotime($koniec));
                $koniec_godzina = date("G", strtotime($koniec));
                $koniec_minuta = date("i", strtotime($koniec));
                    
                    echo '<td>';
                        echo  $start_dzien . ' ' . $start_miesiac . ' ' . $start_rok . ' ' . $start_godzina . ':' . $start_minuta . ' - ' . $koniec_godzina . ':' . $koniec_minuta ;
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



?>