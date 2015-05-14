<?php
require_once('classes/class.excelCheck.php');
require_once('classes/class.sendMail.php');
session_start();

$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$FileName = (isset($_POST['filename'])) ? $_POST['filename'] : '';
$nomeReal = (isset($_POST['nomeReal'])) ? $_POST['nomeReal'] : '';
$data = (isset($_POST['data'])) ? $_POST['data'] : '';


$retorno = array(
	'status' => '',
	'info'	 => '', 
	'tipo'   => '' 
);

$checkFile = new excelCheck($FileName);
$result = $checkFile->uploadConteudo($id);
$mailto = new sendMail();
$data = getdate();
$today = $data['mday']."/".$data['mon']."/".$data['year'];
$arr = array(
	"email" => array( "mail" => "cpolitano@yboh.com.br", 'data' => $today , 'filename' => $nomeReal, 'Cc' => array("0" => "thiago@yboh.com.br") ),
);
$mail = $mailto->send('Upload de arquivo', 'upload', $arr['email']);

echo "ok";

?>