<?php
$upOne = realpath(dirname(__FILE__) . '/..');
$file  = $upOne . '/PDF/relatorio.pdf';//$_REQUEST ['arquivo'];
header("Content-Disposition: attachment;" . $file);
header("Content-Type: application/pdf");
header("Content-Length: " . filesize($file));
header("Pragma: no-cache");
header("Expires: 0");
header("Cache-Control: must-revalidate");

readfile($file);

exit;

?>