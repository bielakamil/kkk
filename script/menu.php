<?php


if ($_SESSION['status'] == 1) // MENU DLA ADMINISTRATORA
{
    $mysql = connect_mysql();
    $zapytanie_firma = 'SELECT nazwa,facebook,instagram,www,twitter,youtube FROM firma';
    
    $result_firma = $mysql->query($zapytanie_firma);
    $row_firma = $result_firma->fetch_array();
    
    
    echo '<div id="logo">';
        echo '<a href="../page/dashboard.php">' . strtoupper ($row_firma['nazwa']) . '</a>';
    echo '</div>';
  
    echo '<li>';
        echo '<a href="#">';
            echo '<i class="material-icons"> home </i>';
            echo 'Strona Główna';
        echo '</a>';
        echo '<ol>';
            echo '<a href="../page/admin.php"> Twój profil </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/add-user.php"> Dodaj ucznia </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/add-samochod.php"> Dodaj samochód </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/add-instruktor.php"> Dodaj instruktora </a>';
        echo '</ol>';
    echo '</li>';
    
    echo '<li>';
        echo '<a href="#">';
            echo '<i class="material-icons"> dns </i>';
            echo 'Wykład';
        echo '</a>';

        echo '<ol>';
            echo '<a href="../page/add-wyklad.php"> Dodaj wykład </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/add-temat.php"> Dodaj temat wykładu </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/add-kategoria.php"> Dodaj kategorie wykładu </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/tematy-wyklad.php"> Pokaż wszystkie tematy </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/kategorie-wyklad.php"> Pokaż wszystkie kategorie </a>';
        echo '</ol>';
    echo '</li>';
    
    echo '<li>';
        echo '<a>';
            echo '<i class="material-icons"> dns </i>';
            echo 'Jazdy';
        echo '</a>';
        echo '<ol>';
            echo '<a href="../page/miejsca.php"> Wszystkie miejsca </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/add-miejsce.php"> Dodaj miejsce </a>';
        echo '</ol>';
    

    echo '</li>';
    
    echo '<li>';
        echo '<a href="#">';
            echo '<i class="material-icons"> book </i>';
            echo 'Kontakt';
        echo '</a>';
    
        if ((isset($row_firma['facebook'])) and ($row_firma['facebook'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.facebook.com/' . $row_firma['facebook'] .'"> Facebook </a>';
        echo '</ol>';    
        }
    
        if ((isset($row_firma['twitter'])) and ($row_firma['twitter'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.twitter.com/' . $row_firma['twitter'] . '"> Twitter </a>';
        echo '</ol>';  
        }
        
        if ((isset($row_firma['instagram'])) and ($row_firma['instagram'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.instagram.com/' . $row_firma['instagram'] . '"> Instagram </a>';
        echo '</ol>';  
        }
    
        if ((isset($row_firma['www'])) and ($row_firma['www'] != null))
        {
        echo '<ol>';
            echo '<a href="https://' . $row_firma['www'] . '"> WWW </a>';
        echo '</ol>';  
        }
    
        if ((isset($row_firma['youtube'])) and ($row_firma['youtube'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.youtube.com/' . $row_firma['youtube'] . '"> Youtube </a>';
        echo '</ol>';  
        }
        
        
    echo '</li>';
    
    echo '<li>';
        echo '<a href="../page/logout.php">';
            echo '<i class="material-icons"> input </i>';
            echo 'Wyloguj';
        echo '</a>';
    echo '</li>';  

} elseif ($_SESSION['status'] == 2) // MENU DLA INSTRUKTORA
{

    $mysql = connect_mysql();
    $zapytanie_firma = 'SELECT nazwa,facebook,instagram,www,twitter,youtube FROM firma';
    
    $result_firma = $mysql->query($zapytanie_firma);
    $row_firma = $result_firma->fetch_array();
    
    
    echo '<div id="logo">';
        echo '<a href="../page/dashboard.php">' . strtoupper ($row_firma['nazwa']) . '</a>';
    echo '</div>';
  
    echo '<li>';
        echo '<a href="#">';
            echo '<i class="material-icons"> home </i>';
            echo 'Strona Główna';
        echo '</a>';
        echo '<ol>';
            echo '<a href="../page/instruktor.php"> Twój profil </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/add-user.php"> Dodaj ucznia </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/add-wyklad.php"> Dodaj wykład </a>';
        echo '</ol>';
    echo '</li>';
    
    echo '<li>';
        echo '<a href="#">';
            echo '<i class="material-icons"> book </i>';
            echo 'Kontakt';
        echo '</a>';
    
        if ((isset($row_firma['facebook'])) and ($row_firma['facebook'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.facebook.com/' . $row_firma['facebook'] .'"> Facebook </a>';
        echo '</ol>';    
        }
    
        if ((isset($row_firma['twitter'])) and ($row_firma['twitter'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.twitter.com/' . $row_firma['twitter'] . '"> Twitter </a>';
        echo '</ol>';  
        }
        
        if ((isset($row_firma['instagram'])) and ($row_firma['instagram'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.instagram.com/' . $row_firma['instagram'] . '"> Instagram </a>';
        echo '</ol>';  
        }
    
        if ((isset($row_firma['www'])) and ($row_firma['www'] != null))
        {
        echo '<ol>';
            echo '<a href="https://' . $row_firma['www'] . '"> WWW </a>';
        echo '</ol>';  
        }
    
        if ((isset($row_firma['youtube'])) and ($row_firma['youtube'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.youtube.com/' . $row_firma['youtube'] . '"> Youtube </a>';
        echo '</ol>';  
        }
        
        
    echo '</li>';
    
    echo '<li>';
        echo '<a href="../page/logout.php">';
            echo '<i class="material-icons"> input </i>';
            echo 'Wyloguj';
        echo '</a>';
    echo '</li>';
} elseif ($_SESSION['status'] == 3) // MENU DLA UCZNIA
{
    
    $mysql = connect_mysql();
    $zapytanie_firma = 'SELECT nazwa,facebook,instagram,www,twitter,youtube FROM firma';
    
    $result_firma = $mysql->query($zapytanie_firma);
    $row_firma = $result_firma->fetch_array();
    
    echo '<div id="logo">';
        echo '<a href="../page/dashboard.php">' . strtoupper ($row_firma['nazwa']) . '</a>';
    echo '</div>';
  
    echo '<li>';
        echo '<a href="../page/dashboard.php">';
            echo '<i class="material-icons"> home </i>';
            echo 'Strona Główna';
        echo '</a>';
        echo '<ol>';
            echo '<a href="../page/uczen.php"> Twój profil </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/wyklady.php"> Wykłady </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/jazdy.php"> Jazdy </a>';
        echo '</ol>';
        echo '<ol>';
            echo '<a href="../page/wplaty.php"> Wpłaty </a>';
        echo '</ol>';
    echo '</li>';
    
    echo '<li>';
        echo '<a href="#">';
            echo '<i class="material-icons"> book </i>';
            echo 'Kontakt';
        echo '</a>';
    
        if ((isset($row_firma['facebook'])) and ($row_firma['facebook'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.facebook.com/' . $row_firma['facebook'] .'"> Facebook </a>';
        echo '</ol>';    
        }
    
        if ((isset($row_firma['twitter'])) and ($row_firma['twitter'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.twitter.com/' . $row_firma['twitter'] . '"> Twitter </a>';
        echo '</ol>';  
        }
        
        if ((isset($row_firma['instagram'])) and ($row_firma['instagram'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.instagram.com/' . $row_firma['instagram'] . '"> Instagram </a>';
        echo '</ol>';  
        }
    
        if ((isset($row_firma['www'])) and ($row_firma['www'] != null))
        {
        echo '<ol>';
            echo '<a href="https://' . $row_firma['www'] . '"> WWW </a>';
        echo '</ol>';  
        }
    
        if ((isset($row_firma['youtube'])) and ($row_firma['youtube'] != null))
        {
        echo '<ol>';
            echo '<a href="https://www.youtube.com/' . $row_firma['youtube'] . '"> Youtube </a>';
        echo '</ol>';  
        }
    
    echo '</li>';
    
    echo '<li>';
        echo '<a href="../page/logout.php">';
            echo '<i class="material-icons"> input </i>';
            echo 'Wyloguj';
        echo '</a>';
    echo '</li>';
    
    
    
} else
{
    header ("Location: ../page/login.php");
    exit();
}


?>