<?php

if (isset($_POST['uczen']))
{
    
    $uczen = $_POST['uczen'];
    $wyklad = $_POST['wyklad'];
    echo 'Uczeń: ' . $uczen . '<br>';
    echo $wyklad;
    
    
    require('generate-pdf.php');
    
    $argument['autor'] = 'Kamil Biela';
    $argument['title'] = 'Uczeń - Natalia Zając';
    $argument['output'] = 'Natalia Zając';
    $argument['body'] = '<style>'.file_get_contents('../style/style-pdf.css').'</style> <body> jhjj </body>';
    $pdf = generate_pdf($argument);

} else
{
    header ("Location: ../page/dashboard.php");
    exit();
}
 



?>