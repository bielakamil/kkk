<?php


if (isset($_POST['temat']))
{
    $temat = $_POST['temat'];
    $id = $_POST['id'];
    $kategoria = $_POST['kategoria'];
    $notatka = $_POST['notatka'];
    $czas = $_POST['czas'];
    include ('../script/permissions.php');
    admin();
    
    include ('../script/mysql.php');
    $mysql = connect_mysql();
    
    // SPRAWDZAMY CZY ISTNIEJE DANY TEMAT
    $zapytanie_temat = 'SELECT id, temat, kategoria, notatka,czas FROM tematy_wyklad WHERE id=' . $id;
    $result_temat = $mysql->query($zapytanie_temat);
    
    if ($result_temat->num_rows == 1)
    {
        $row_temat = $result_temat->fetch_array();
        if ($temat != $row_temat['temat'])
        {
            $update = 'UPDATE tematy_wyklad SET temat="' . $temat . '" WHERE id=' . $id;
            $mysql->query($update);
        }
        
        if ($kategoria != $row_temat['kategoria'])
        {
            $update = 'UPDATE tematy_wyklad SET kategoria="' . $kategoria . '" WHERE id=' . $id;
            $mysql->query($update);
        }
        
        if ($czas != $row_temat['czas'])
        {
            $update = 'UPDATE tematy_wyklad SET czas="' . $czas . '" WHERE id=' . $id;
            $mysql->query($update);
        }
        $update_text = "'$notatka'";
        $update = 'UPDATE tematy_wyklad SET notatka=' . $update_text . ' WHERE id=' . $id;
        $mysql->query($update);
       
        
        
        header ("Location: ../page/temat-wyklad.php?id=" . $id);
        exit();
        
        
    } else
    {
        header ("Location: ../page/dashboard.php");
        exit();
    }
    
    
    
    
} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}

header ("Location: ../page/dashboard.php");
exit();


?>