<?php

if ((isset($_POST['pesel'])) and (isset($_POST['email'])))
{
    session_start();
    $pesel = $_POST['pesel'];
    $email = $_POST['email'];

    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    $zapytanie = 'SELECT pesel FROM password WHERE pesel="' . $pesel . '"';
    $result = $mysql->query($zapytanie);
    
    if ($result->num_rows != 1)
    {
        $_SESSION['error'] = true;
        header ("Location: ../page/reset_password.php");
    }
    
    $zapytanie_admin = 'SELECT email FROM administratorzy WHERE pesel="' . $pesel . '"';
    $result_admin = $mysql->query($zapytanie_admin);
    
    if ($result_admin->num_rows == 1)
    {
        $row_admin = $result_admin->fetch_array();
        $email_admin = $row_admin['email'];
        
        if ($email ==  $email_admin)
        {
        
        $key ='';
        $liczby = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        for($i=0; $i<20; $i++)
        {
        $key.=$liczby{rand(0,strlen($liczby)-1)};
        }  
        
        $now = date ("Y-m-d G:i:s");
        $d1 = strtotime($now);
        $d2 = 60*60*24*1;
        $d = $d1+$d2;
        $data = date("Y-m-d G:i:s", $d);
            
        $zapytanie_insert = 'INSERT INTO resetpassword (id,pesel,email,klucz,data) VALUES (null,"' . $pesel . '","'. $email . '","' . $key . '","' . $data .'")';
            
        $mysql->query($zapytanie_insert);
            
        include ('../script/email.php');
        include ('../script/szablon_email.php');
        $x = new e_reset_password ($key);   
        send_mail ($email_admin,$x);    
        header ("Location: ../page/reset_password_send.php");    
        exit();
        
            
        } else
        {
            $_SESSION['error'] = true;
            header ("Location: ../page/reset_password.php");
        }
    
    }
    
    $zapytanie_instruktor = 'SELECT email FROM instruktorzy WHERE pesel="' . $pesel . '"';
    $result_instruktor = $mysql->query($zapytanie_instruktor);
    
    if ($result_instruktor->num_rows == 1)
    {
        $row_instruktor = $result_instruktor->fetch_array();
        $email_instruktor = $row_instruktor['email'];
        
        if ($email == $email_instruktor)
        {
        
        $key ='';
        $liczby = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        for($i=0; $i<20; $i++)
        {
        $key.=$liczby{rand(0,strlen($liczby)-1)};
        }  
        
        $now = date ("Y-m-d G:i:s");
        $d1 = strtotime($now);
        $d2 = 60*60*24*1;
        $d = $d1+$d2;
        $data = date("Y-m-d G:i:s", $d);
            
        $zapytanie_insert = 'INSERT INTO resetpassword (id,pesel,email,klucz,data) VALUES (null,"' . $pesel . '","'. $email . '","' . $key . '","' . $data .'")';
            
        $mysql->query($zapytanie_insert);
        include ('../script/email.php');
        include ('../script/szablon_email.php');
        $x = new e_reset_password ($key);   
        send_mail ($email_instruktor,$x);    
            
        header ("Location: ../page/reset_password_send.php");    
        } else
        {
            $_SESSION['error'] = true;
            header ("Location: ../page/reset_password.php"); 
        }
    }
    
    $zapytanie_uczen = 'SELECT email FROM uczniowie WHERE pesel="' . $pesel . '"';
    $result_uczen = $mysql->query($zapytanie_uczen);
    
    if ($result_uczen->num_rows == 1)
    {
        $row_uczen = $result_uczen->fetch_array();
        $email_uczen = $row_uczen['email'];
        
        if ($email == $email_uczen )
        {
        
        $key ='';
        $liczby = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        for($i=0; $i<20; $i++)
        {
        $key.=$liczby{rand(0,strlen($liczby)-1)};
        }  
        
        $now = date ("Y-m-d G:i:s");
        $d1 = strtotime($now);
        $d2 = 60*60*24*1;
        $d = $d1+$d2;
        $data = date("Y-m-d G:i:s", $d);
            
        $zapytanie_insert = 'INSERT INTO resetpassword (id,pesel,email,klucz,data) VALUES (null,"' . $pesel . '","'. $email . '","' . $key . '","' . $data .'")';
            
        $mysql->query($zapytanie_insert);
        include ('../script/email.php');
        include ('../script/szablon_email.php');
        $x = new e_reset_password ($key);   
        send_mail ($email_uczen,$x);
        header ("Location: ../page/reset_password_send.php");           
        } else
        {
            $_SESSION['error'] = true;
            header ("Location: ../page/reset_password.php"); 
        }
    }
    
} else 
{
    header ("Location: ../page/reset_password.php");
}


?>