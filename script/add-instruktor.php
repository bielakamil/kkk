<?php

    
$pesel = $_POST['pesel'];
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$data_urodzenia = $_POST['data_urodzenia'];
$telefon = $_POST['telefon'];
$email = $_POST['email'];
$plec = $_POST['plec'];
$data_zatrudnienia = $_POST['data_zatrudnienia'];
$uprawnienia = $_POST['uprawnienia'];
$pesel = str_replace(' ', '', $pesel);
$telefon = str_replace(' ', '', $telefon);

include ('../script/mysql.php');

$mysql = connect_mysql();

// SPRAWDZAMY CZY DANEGO PESELU JUŻ NIE MA W BAZIE

$zapytanie_pesel = 'SELECT id FROM instruktorzy WHERE pesel="' . $pesel . '"';
$result_pesel = $mysql->query($zapytanie_pesel);

if ($result_pesel->num_rows == 1)
{
    $row_pesel = $result_pesel->fetch_array();
    header ("Location: ../page/instruktor.php?id=" . $row_pesel['id']);
    exit();
}

// WALIDACJA 

$ile = 0;


//if (!preg_match('/^[0-9]{11}$/',$pesel)) //sprawdzamy czy pesel ma 11 cyfr
//	{
//        $_SESSION['log_pesel'] = 1; 
//		$ile++;
//	}  
//    
//    if ((strlen($imie)<3) or (strlen($imie)>50))
//    {
//        $_SESSION['log_imie'] = 1;
//        $ile++;
//    }
//    
//    if ((strlen($nazwisko)<3) or (strlen($nazwisko)>50))
//    {
//        $_SESSION['log_nazwisko'] = 1;
//        $ile++;
//    }
//    
//    if ($data_urodzenia == null)
//    {
//        $_SESSION['log_data_urodzenia'] = 1;
//        $ile++;
//    }
//    
//    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
//    {
//        $_SESSION['log_email'] = 1;
//        $ile++; 
//    }
//    
//    if ((!$plec == 1) and (!$plec == 2))
//    {
//        $_SESSION['log_plec'] = 1;
//        $ile ++;
//    }
//
//    if (!preg_match('/^[0-9]{9}$/',$telefon)) 
//	{
//        $_SESSION['log_telefon'] = 1; 
//		$ile++;
//	} 

 

if ($ile != 0)
{
    header ("Location: ../page/add-instruktor.php");
    exit();
}


// GENEROWANIE HASŁA
$passwd ='';
$liczby = '1234567890';
for($i=0; $i<8; $i++)
{
    $passwd.=$liczby{rand(0,strlen($liczby)-1)};
}

$passwd = 'admin';


$insert = 'INSERT INTO instruktorzy (id,pesel,imie,nazwisko,data_urodzenia,telefon,email,data_zatrudnienia,plec,stan,czas_wygasniecia,czas_jazdy,show_car,nr_uprawnien) VALUES (null,"' . $pesel . '","' . $imie . '","' . $nazwisko . '","' . $data_urodzenia . '","' . $telefon  . '","' . $email . '","' . $data_zatrudnienia . '","' . $plec . '","1","5","01:30:00","0","' . $uprawnienia . '")';

$mysql->query($insert);

$zapytanie_last = 'SELECT id FROM instruktorzy ORDER BY id DESC LIMIT 1';
$result_last = $mysql->query($zapytanie_last);
$row_last = $result_last->fetch_array();
$id = $row_last['id'];
$hash = password_hash($passwd,PASSWORD_DEFAULT);
$insert_passwd = 'INSERT INTO password (PESEL,HASLO) VALUES ("' . $pesel . '", "' . $hash . '")';
$mysql->query($insert_passwd);

$instruktor = $imie . ' ' . $nazwisko;
include ('../script/email.php');
include ('../script/szablon_email.php');
$x = new e_new_instruktor ($passwd,$instruktor);   
send_mail ($email,$x);    

header ("Location: ../page/instruktor.php?id=" . $id);
exit();



?>