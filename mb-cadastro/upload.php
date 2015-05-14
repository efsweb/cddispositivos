<?php
require_once('app/classes/class.excelCheck.php');
require_once('google/appengine/api/cloud_storage/CloudStorageTools.php');
use google\appengine\api\cloud_storage\CloudStorageTools;

$gs_name  = $_FILES[0]['tmp_name'];
$rest = explode(".", $_FILES[0]['name']);
$type = array_pop($rest);
$newFileName = '77e63dd1525550bd49d318d17a44ba5d.xlsx';//md5($_FILES[0]['name']) . '.' . $type;

$retorno = array(
	'status' => '',
	'info'	 => '', 
	'tipo'   => '' 
);

/*if(!move_uploaded_file($gs_name, "gs://mb-cadastro-arquivos/$newFileName")){
	$retorno['status'] = 'error';
	$retorno['info']   = 'falha ao copiar ' . $newLocal;
	$retorno['tipo']   = 'string';
}else{*/
	$checkFile = new excelCheck($newFileName);
	$result = $checkFile->verificarConteudo();
	if($result == "error"){
		$retorno['status'] = 'error';
	    $retorno['info']   = 'conteudo invalido';
	    $retorno['tipo']   = 'string';
	}else{
		$feedBD = new callSp('mb_cadastro_sp_upload_arquivo');
		print_r($feedBD);
		$retorno['status'] = 'confirmacao';
	    $retorno['info']   = $feedBD;
	    $retorno['tipo']   = 'string';
	}
//}

//echo json_encode($retorno);
?>