<?php




function generate_pdf ($info)
{
    ob_clean();
    require_once '../vendor/autoload.php';
    $pdf = new mPDF('utf-8', 'A4');
$html = 'dupa';
    $pdf->Output('f.pdf', 'F');
    }

?>