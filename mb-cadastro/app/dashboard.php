<?php
require_once('classes/class.Utils.php');
require_once('classes/class.callsp.php');

$utils = new Mais_Utils();
$dados = array();

$feedBD = new callSp('mb_cadastro_sp_upload_arquivo');
$params = array('load_arquivos_base', '', '', '', '');

foreach (json_decode($feedBD->execute($params), true) as $key => $value) {
	$linha = array();
	//echo "<pre>"; print_r($value);echo "</pre>";
	$dataBR = $utils->dateToBr($value['data_upload']);
	$linha['data']   =$dataBR;
	$linha['dataen']   =$value['data_upload'];
	$linha['nome']   = $value['nome_arquivo'];
	$linha['status'] = $value['status_arquivo'];
	$linha['id']     = $value['yboh_id_arquivo'];
	$linha['id_aplicativo'] = $value['id_aplicativo'];
	$linha['usuario_upload'] = $value['usuario_upload'];
	$dados[] = $linha;
}

echo json_encode($dados);
?>