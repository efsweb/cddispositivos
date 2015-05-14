<?php
require_once 'classes/class.callsp.php';

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
		break;
	case 'POST': 
		$id = (isset($_POST['id'])) ? $_POST['id'] : '';
		break;
	default:
		break;
}

//debug
if($debug)
	echo "valores do request  =>  " . $id . " | </br>";

$feedBD = new callSp('mb_cadastro_sp_upload_arquivo');
$params = array('apagar_registro', $id, '', '', '');
$result = json_decode($feedBD->execute($params), true);
//echo "<pre>"; print_r($result);echo "</pre>"; 
if($result['tipo_retorno'] == 'OK'){
	$retorno['status'] = $result['ok'];
	$retorno['info'] = $result['Arquivo aprovado com sucesso.'];
	$retorno['tipo'] = $result['string'];
}else{
	$retorno['status'] = $result['error'];
	$retorno['info'] = $result['Arquivo Não aprovado.'];
	$retorno['tipo'] = $result['string'];
}
return $retorno;
?>