<?php
require_once('classes/class.sendMail.php');

$debug = false;

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET': 
		$file = (isset($_GET['file'])) ? $_GET['file'] : '';
		break;
	case 'POST': 
		$file = (isset($_POST['file'])) ? $_POST['file'] : '';
		break;
	default:
		break;
}



//debug
if($debug)
	echo "valores do request  =>  " . $file . " | " . "</br>";


$upOne = realpath(dirname(__FILE__) . '/..');
$fileLocation = $upOne . "/arquivos/" . $file;
if(file_exists($fileLocation)){
	$mailto = new sendMail();
	$data = getdate();
	$today = $data[mday]."/".$data[mon]."/".$data[year];
	
	$arr = array(
		"email" => array( "mail" => "cpolitano@yboh.com.br", 'data' => $today , 'filename' => $file, 'Cc' => array("0" => "thiago@yboh.com.br") ),
	);
	echo $mailto->send('Status do arquivo', 'relatorio', $arr['email']);
	echo "ok";
}else{
	echo "error";
}




?>