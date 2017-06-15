<?php




//if ((!isset($_GET['search'])) or ($_GET['search'] == null)) 
//{
//} else
//{
//$search = $_GET['q']; 
//session_start ();    
//include ('../script/mysql.php');
//$mysql = connect_mysql ();
//$status = $_SESSION['status'];
//$pesel = $_SESSION['pesel'];
//    
//switch ($status)
//{
//    case 1:
//        {
//            // WYSZUKIWARKA DLA ADMINISTRATORA
//            $wyraz = explode(" ", $search);
//$ile = count ($wyraz);
//            
//if ($ile == 1)
//{
//$zapytanie_uczniowie = 'SELECT id, imie, nazwisko FROM uczniowie WHERE imie LIKE "' . $search . '%" OR nazwisko LIKE "' . $search . '%"';   
//$zapytanie_instruktorzy = 'SELECT id, imie, nazwisko FROM instruktorzy WHERE imie LIKE "' . $search . '%" OR nazwisko LIKE "' . $search . '%"'; $zapytanie_admin = 'SELECT id, imie, nazwisko FROM administratorzy WHERE imie LIKE "' . $search . '%" OR nazwisko LIKE "' . $search . '%"';
//$zapytanie_miejsce_wykladow = 'SELECT id, nazwa FROM wyklady_miejsce WHERE nazwa LIKE "' . $search . '%"';
//} else
//{
//$zapytanie_uczniowie = 'SELECT id, imie, nazwisko FROM uczniowie WHERE (imie LIKE "' . $wyraz[0] . '%" AND nazwisko LIKE "' . $wyraz[1] . '%") OR (imie LIKE "' . $wyraz[1] . '%" AND nazwisko LIKE "' . $wyraz[0] . '%") ';  
//$zapytanie_instruktorzy = 'SELECT id, imie, nazwisko FROM instruktorzy WHERE (imie LIKE "' . $wyraz[0] . '%" AND nazwisko LIKE "' . $wyraz[1] . '%") OR (imie LIKE "' . $wyraz[1] . '%" AND nazwisko LIKE "' . $wyraz[0] . '%") ';  
//$zapytanie_admin = 'SELECT id, imie, nazwisko FROM administratorzy WHERE (imie LIKE "' . $wyraz[0] . '%" AND nazwisko LIKE "' . $wyraz[1] . '%") OR (imie LIKE "' . $wyraz[1] . '%" AND nazwisko LIKE "' . $wyraz[0] . '%") ';  
//$zapytanie_miejsce_wykladow = 'SELECT id, nazwa FROM wyklady_miejsce WHERE nazwa LIKE "' . $search . '%"';
//}
//
//@$result_uczniowie = $mysql->query($zapytanie_uczniowie);  
//@$result_instruktorzy = $mysql->query($zapytanie_instruktorzy);
//@$result_admin = $mysql->query($zapytanie_admin);
//@$result_miejsce_wykladow = $mysql->query($zapytanie_miejsce_wykladow); 
//if (@$result_uczniowie->num_rows > 0)
//{
//    while (@$row_uczniowie = $result_uczniowie->fetch_array())
//    {
//        echo '<a href="uczen.php?id=' . $row_uczniowie['id'] . '">';
//            echo '<li>';
//                echo '<img src="../img/prifile-pic.png">';
//                echo '<b>' . $row_uczniowie['imie'] . ' ' . $row_uczniowie['nazwisko'] . '</b>';
//                echo '<span> Uczeń </span>';
//            echo '</li>';
//        echo '</a>';
//    }
//}
//if (@$result_instruktorzy->num_rows > 0)
//{           
//    while ($row_instruktorzy = $result_instruktorzy->fetch_array())
//    {
//        echo '<a href="instruktor.php?id=' . $row_instruktorzy['id'] . '">';
//            echo '<li>';
//                echo '<img src="../img/prifile-pic.png">';
//                echo '<b>' . $row_instruktorzy['imie'] . ' ' . $row_instruktorzy['nazwisko'] . '</b>';
//                echo '<span> Instruktor </span>';
//            echo '</li>';
//        echo '</a>';
//    }
//}
// 
//if (@$result_admin->num_rows > 0)
//{
//    while ($row_admin = $result_admin->fetch_array())
//    {
//        echo '<a href="admin.php?id=' . $row_admin['id'] . '">';
//            echo '<li>';
//                echo '<img src="../img/prifile-pic.png">';
//                echo '<b>' . $row_admin ['imie'] . ' ' . $row_admin['nazwisko'] . '</b>';
//                echo '<span> Administrator </span>';
//            echo '</li>';
//        echo '</a>';
//    }
//}
//    
//if (@$result_miejsce_wykladow->num_rows > 0)
//{
//while ($row_miejsce_wykladow = $result_miejsce_wykladow->fetch_array())
//{
//    echo '<a href="miejsce-wyklad.php?id=' . $row_miejsce_wykladow['id'] . '">';
//        echo '<li>';
//            echo '<img src="../img/prifile-pic.png">';
//            echo '<b>' . $row_miejsce_wykladow['nazwa'] . '</b>';
//            echo '<span> Miejsce wykładów </span>';
//        echo '</li>';
//    echo '</a>';
//} 
//}             
//            
//            
//            break;
//        }
//        
//    case 2:
//        {
//            // WYSZUKIWARKA DLA INSTRUKTORA
//
//$wyraz = explode(" ", $search);
//$ile = count ($wyraz);
//            
//if ($ile == 1)
//{
//$zapytanie_uczniowie = 'SELECT id, imie, nazwisko FROM uczniowie WHERE imie LIKE "' . $search . '%" OR nazwisko LIKE "' . $search . '%"';   
//$zapytanie_instruktorzy = 'SELECT id, imie, nazwisko FROM instruktorzy WHERE imie LIKE "' . $search . '%" OR nazwisko LIKE "' . $search . '%"'; $zapytanie_admin = 'SELECT id, imie, nazwisko FROM administratorzy WHERE imie LIKE "' . $search . '%" OR nazwisko LIKE "' . $search . '%"';
//$zapytanie_miejsce_wykladow = 'SELECT id, nazwa FROM wyklady_miejsce WHERE nazwa LIKE "' . $search . '%"';
//} else
//{
//$zapytanie_uczniowie = 'SELECT id, imie, nazwisko FROM uczniowie WHERE (imie LIKE "' . $wyraz[0] . '%" AND nazwisko LIKE "' . $wyraz[1] . '%") OR (imie LIKE "' . $wyraz[1] . '%" AND nazwisko LIKE "' . $wyraz[0] . '%") ';  
//$zapytanie_instruktorzy = 'SELECT id, imie, nazwisko FROM instruktorzy WHERE (imie LIKE "' . $wyraz[0] . '%" AND nazwisko LIKE "' . $wyraz[1] . '%") OR (imie LIKE "' . $wyraz[1] . '%" AND nazwisko LIKE "' . $wyraz[0] . '%") ';  
//$zapytanie_admin = 'SELECT id, imie, nazwisko FROM administratorzy WHERE (imie LIKE "' . $wyraz[0] . '%" AND nazwisko LIKE "' . $wyraz[1] . '%") OR (imie LIKE "' . $wyraz[1] . '%" AND nazwisko LIKE "' . $wyraz[0] . '%") ';  
//$zapytanie_miejsce_wykladow = 'SELECT id, nazwa FROM wyklady_miejsce WHERE nazwa LIKE "' . $search . '%"';
//}
//
//@$result_uczniowie = $mysql->query($zapytanie_uczniowie);  
//@$result_instruktorzy = $mysql->query($zapytanie_instruktorzy);
//@$result_admin = $mysql->query($zapytanie_admin);
//@$result_miejsce_wykladow = $mysql->query($zapytanie_miejsce_wykladow); 
//if (@$result_uczniowie->num_rows > 0)
//{
//    while (@$row_uczniowie = $result_uczniowie->fetch_array())
//    {
//        echo '<a href="uczen.php?id=' . $row_uczniowie['id'] . '">';
//            echo '<li>';
//                echo '<img src="../img/prifile-pic.png">';
//                echo '<b>' . $row_uczniowie['imie'] . ' ' . $row_uczniowie['nazwisko'] . '</b>';
//                echo '<span> Uczeń </span>';
//            echo '</li>';
//        echo '</a>';
//    }
//}
//if (@$result_instruktorzy->num_rows > 0)
//{           
//    while ($row_instruktorzy = $result_instruktorzy->fetch_array())
//    {
//        echo '<a href="instruktor.php?id=' . $row_instruktorzy['id'] . '">';
//            echo '<li>';
//                echo '<img src="../img/prifile-pic.png">';
//                echo '<b>' . $row_instruktorzy['imie'] . ' ' . $row_instruktorzy['nazwisko'] . '</b>';
//                echo '<span> Instruktor </span>';
//            echo '</li>';
//        echo '</a>';
//    }
//}
// 
//if (@$result_admin->num_rows > 0)
//{
//    while ($row_admin = $result_admin->fetch_array())
//    {
//        echo '<a href="admin.php?id=' . $row_admin['id'] . '">';
//            echo '<li>';
//                echo '<img src="../img/prifile-pic.png">';
//                echo '<b>' . $row_admin ['imie'] . ' ' . $row_admin['nazwisko'] . '</b>';
//                echo '<span> Administrator </span>';
//            echo '</li>';
//        echo '</a>';
//    }
//}
//    
//if (@$result_miejsce_wykladow->num_rows > 0)
//{
//while ($row_miejsce_wykladow = $result_miejsce_wykladow->fetch_array())
//{
//    echo '<a href="miejsce-wyklad.php?id=' . $row_miejsce_wykladow['id'] . '">';
//        echo '<li>';
//            echo '<img src="../img/prifile-pic.png">';
//            echo '<b>' . $row_miejsce_wykladow['nazwa'] . '</b>';
//            echo '<span> Miejsce wykładów </span>';
//        echo '</li>';
//    echo '</a>';
//} 
//}    
//            
//            break;
//        }
//    case 3:
//        {
//            // WYSZUKIWARKA DLA UCZNIA
//           
//$wyraz = explode(" ", $search);
//$ile = count ($wyraz);
//$x = 0;            
//if ($ile == 1)
//{
//$zapytanie_uczniowie = 'SELECT id, imie, nazwisko FROM uczniowie WHERE (imie LIKE "' . $search . '%" OR nazwisko LIKE "' . $search . '%") AND uczniowie.private_profil="1"';   
//$zapytanie_instruktorzy = 'SELECT id, imie, nazwisko FROM instruktorzy WHERE imie LIKE "' . $search . '%" OR nazwisko LIKE "' . $search . '%"';  
//$zapytanie_miejsce_wykladow = 'SELECT id, nazwa FROM wyklady_miejsce WHERE nazwa LIKE "' . $search . '%"';
//} else
//{
//$zapytanie_uczniowie = 'SELECT id, imie, nazwisko FROM uczniowie WHERE ((imie LIKE "' . $wyraz[0] . '%" AND nazwisko LIKE "' . $wyraz[1] . '%") OR (imie LIKE "' . $wyraz[1] . '%" AND nazwisko LIKE "' . $wyraz[0] . '%")) AND uczniowie.private_profil="1" ';  
//$zapytanie_instruktorzy = 'SELECT id, imie, nazwisko FROM instruktorzy WHERE (imie LIKE "' . $wyraz[0] . '%" AND nazwisko LIKE "' . $wyraz[1] . '%") OR (imie LIKE "' . $wyraz[1] . '%" AND nazwisko LIKE "' . $wyraz[0] . '%") ';  
//$zapytanie_miejsce_wykladow = 'SELECT id, nazwa FROM wyklady_miejsce WHERE nazwa LIKE "' . $search . '%"';
//}
//
//$result_uczniowie = $mysql->query($zapytanie_uczniowie);  
//$result_instruktorzy = $mysql->query($zapytanie_instruktorzy);
//$result_miejsce_wykladow = $mysql->query($zapytanie_miejsce_wykladow);  
//if (@$result_uczniowie->num_rows > 0)
//{
//    while ($row_uczniowie = $result_uczniowie->fetch_array())
//    {
//        echo '<a href="uczen.php?id=' . $row_uczniowie['id'] . '">';
//            echo '<li>';
//                echo '<img src="../img/prifile-pic.png">';
//                echo '<b>' . $row_uczniowie['imie'] . ' ' . $row_uczniowie['nazwisko'] . '</b>';
//                echo '<span> Uczeń </span>';
//            echo '</li>';
//        echo '</a>';
//    }
//}
//           
//if (@$result_instruktorzy->num_rows > 0)
//{
//    while ($row_instruktorzy = $result_instruktorzy->fetch_array())
//    {
//        echo '<a href="instruktor.php?id=' . $row_instruktorzy['id'] . '">';
//            echo '<li>';
//                echo '<img src="../img/prifile-pic.png">';
//                echo '<b>' . $row_instruktorzy['imie'] . ' ' . $row_instruktorzy['nazwisko'] . '</b>';
//                echo '<span> Instruktor </span>';
//            echo '</li>';
//        echo '</a>';
//    }   
//}
//   
//if (@$result_miejsce_wykladow->num_rows > 0)
//{
//while ($row_miejsce_wykladow = $result_miejsce_wykladow->fetch_array())
//{
//    echo '<a href="miejsce-wyklad.php?id=' . $row_miejsce_wykladow['id'] . '">';
//        echo '<li>';
//            echo '<img src="../img/prifile-pic.png">';
//            echo '<b>' . $row_miejsce_wykladow['nazwa'] . '</b>';
//            echo '<span> Miejsce wykładów </span>';
//        echo '</li>';
//    echo '</a>';
//} 
//}
//     
//
//             break;
//        }
//        
//        
//}
//    
// 
//}

$term = $_GET['q'];

include ('../script/mysql.php');

$mysql = connect_mysql();
$x = 0;


// KURSANCI
$zapytanie = 'SELECT id, imie, nazwisko FROM uczniowie WHERE concat(imie, " ", nazwisko) LIKE "%' . $term . '%" OR concat(nazwisko, " ", imie) LIKE "%' . $term . '%" ORDER BY nazwisko, imie LIMIT 10';
$result = $mysql->query($zapytanie);


while ($row = $result->fetch_array())
{
    $x++;
    $name = $row['imie'] . ' ' . $row['nazwisko'];
    $arr[$x]['id'] = $row['id'];
    $arr[$x]['text'] = $name;
    $arr[$x]['category'] = 'Uczeń';
}


echo json_encode($arr);





?>