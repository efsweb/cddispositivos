<?php
require_once 'classes/class.callsp.php';
require_once 'classes/class.sendMail.php';
require_once 'classes/class.login.php';
$debug = false;
$retorno = array(
	'status' => '',
	'info'	 => '', 
	'tipo'   => '' 
);


switch($_SERVER['REQUEST_METHOD'])
{

	case 'GET': 
		$id = (isset($_GET['id'])) ? $_GET['id'] : '';
		$filename = (isset($_GET['filename'])) ? $_GET['filename'] : '';
		$userup = (isset($_GET['userup'])) ? $_GET['userup'] : '';
		$idapp = (isset($_GET['idapp'])) ? $_GET['idapp'] : '';

		break;
	case 'POST': 
		$id = (isset($_POST['id'])) ? $_POST['id'] : '';
		$filename = (isset($_POST['filename'])) ? $_POST['filename'] : '';
		$userup = (isset($_POST['userup'])) ? $_POST['userup'] : '';
		$idapp = (isset($_POST['idapp'])) ? $_POST['idapp'] : '';

		break;
	default:
		break;
}

//debug
if($debug)
	echo "valores do request  =>  " . $id . " | </br>" . $filename . " | </br>" . $userup . " | </br>" .$idapp;

$feedBD = new callSp('mb_cadastro_sp_upload_arquivo');
$params = array('aprovar_registro', $id, '', '', '');
$result = json_decode($feedBD->execute($params), true);
if($debug)
	echo "<pre>"; print_r($result);echo "</pre>"; 
if($result[0]['tipo_retorno'] == 'OK'){
	$mailto = new sendMail();
	$data = getdate();
	$today = $data['mday']."/".$data['mon']."/".$data['year'];

	$mailLogin = $arr[$userup]['mail'];
	if($debug)
		echo $mailLogin;
	$arr = array(
		"email" => array( "mail" => $mailLogin, 'data' => $today , 'filename' => $filename, 'Cc' => array("0" => "thiago@yboh.com.br") ),
	);
	$mail = $mailto->send('Cadastro Concluído - ' . $filename, 'relatorio', $arr['email']);

	$retorno['status'] = 'ok';
	$retorno['info'] = 'Arquivo aprovado com sucesso.';
	$retorno['tipo'] = 'string';
}else{
	$retorno['status'] = 'error';
	$retorno['info'] = 'Arquivo Não aprovado.';
	$retorno['tipo'] = 'string';
}
return $retorno;
?>