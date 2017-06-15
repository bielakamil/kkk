<?php

$text = $_GET['search'];
$input = $_GET['id'];


include ('../script/mysql.php');

$mysql = connect_mysql();

$wyraz = explode(" ", $text);
$ile = count ($wyraz);

if ($text ==  '')
{
    die;
}


if ($ile == 1)
{
$zapytanie_uczniowie = 'SELECT id, imie, nazwisko FROM uczniowie WHERE (imie LIKE "' . $text . '%" OR nazwisko LIKE "' . $text . '%") AND !(stan=5 OR stan=6)';  
} else
{
$zapytanie_uczniowie = 'SELECT id, imie, nazwisko FROM uczniowie WHERE ((imie LIKE "' . $wyraz[0] . '%" AND nazwisko LIKE "' . $wyraz[1] . '%") OR (imie LIKE "' . $wyraz[1] . '%" AND nazwisko LIKE "' . $wyraz[0] . '%")) AND !(stan=5 OR stan=6)';  
}
$result = $mysql->query($zapytanie_uczniowie);

while ($row = $result->fetch_array())
{
    
    $funkcja = 'select_wyklad("' . $row['id'] . '","' . $input . '","' . $row['imie'] . ' ' . $row['nazwisko'] . '")'; 
    
    echo "<div onclick='$funkcja'>";
       echo $row['imie'] . ' ' . $row['nazwisko'];
    echo '</div>';
}

?>