<?php
error_reporting( E_ALL & ~E_NOTICE );

require_once 'simpleXLS.php';




$excel = new ExcelReader("2014 10 21 Registro Sprinter Info MB.xlsx","UTF-8");
//print_r($excel->getWorksheetList() );

?>

