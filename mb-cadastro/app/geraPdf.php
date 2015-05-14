<?php
ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');
error_reporting(0);

include_once(dirname(__FILE__) . "/mpdf/mpdf.php");

$upOne = realpath(dirname(__FILE__) . '/..');
$pdfFileLocation = $upOne . "/modelos/modelo_pdf.html";
$content  = file_get_contents($pdfFileLocation);

$file  = $upOne . "/PDF/relatorio.pdf";

$mpdf=new mPDF('utf8-s','A4','12','sans-serif' , 15 , 15 , 20 , 45 , 0 , 0); 
$mpdf->useOddEven = 1;
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
$mpdf->shrink_tables_to_fit=1;

$mpdf->WriteHTML($content);
$a = $mpdf->Output("$file",'F');

// //echo 'ok';

$retorno['status'] = 'ok';
$retorno['info'] = 'Arquivo gerado com sucesso.';
$retorno['tipo'] = 'string';

echo json_encode($retorno);

//return $retorno;
//*/

?>