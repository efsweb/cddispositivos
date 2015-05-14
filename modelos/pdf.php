<?php
 include_once("../app/mpdf/mpdf.php");

$file = "relatorio.pdf";

$mpdf=new mPDF('utf8-s','A4','12','sans-serif' , 0 , 0 , 0 , 0 , 0 , 41); 
$mpdf->useOddEven = 1;
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

$content  = file_get_contents('pdf.html');

$mpdf->WriteHTML($content);
$a = $mpdf->Output("$file",'F');

echo $a;

// header("Content-Disposition: attachment;" . $file);
// header("Content-Type: application/pdf");
// header("Content-Length: " . filesize($file));
// header("Pragma: no-cache");
// header("Expires: 0");
// header("Cache-Control: must-revalidate");

// readfile($file);

//exit; 


?>