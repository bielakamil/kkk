<?php




 function send_mail ($odbiorca,$tresc)
{
    require_once('../vendor/PHPMailer-master/class.phpmailer.php');    //dodanie klasy phpmailer
    require_once('../vendor/PHPMailer-master/class.smtp.php');    //dodanie klasy smtp
    $mail = new PHPMailer();    //utworzenie nowej klasy phpmailer
    $mail->From = "admin@mojeprawko.pl";    //adres e-mail użyty do wysyłania wiadomości
    $mail->FromName = "MojePrawko.pl";    //imię i nazwisko lub nazwa użyta do wysyłania wiadomości
    $mail->AddReplyTo('admin@mojeprawko.pl', 'MojePrawko.pl'); //adres e-mail nadawcy oraz jego nazwę w polu "Odpowiedz do"
    $mail->Host = "bielakamil.nazwa.pl";    //adres serwera SMTP wysyłającego e-mail
    $mail->Mailer = "smtp";    //do wysłania zostanie użyty serwer SMTP
    $mail->SMTPAuth = true;    //włączenie autoryzacji do serwera SMTP
    $mail->IsHTML(true);
    $mail->Username = "admin@mojeprawko.pl";    //nazwa użytkownika do skrzynki e-mail
    $mail->Password = "MHtVdn2Hh8";    //hasło użytkownika do skrzynki e-mail
    $mail->Port = 587; //port serwera SMTP zależny od konfiguracji dostawcy usługi poczty
    $mail->Subject = $tresc->title;    //Temat wiadomości, można stosować zmienne i znaczniki HTML
    $mail->Body = $tresc->html;    //Treść wiadomości, można stosować zmienne i znaczniki HTML     
    $mail->AddAddress ($odbiorca,$tresc->uczen);    //adres skrzynki e-mail oraz nazwa adresata, do którego trafi wiadomość
    $mail->Send();
}

?>