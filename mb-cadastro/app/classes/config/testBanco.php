<?php
	require_once('conn.php');

	$db = new DBConnect();

	$db->Connect();

	/*
param_acao
param_yboh_id_arquivo
param_id_aplicativo
param_nome_arquivo
param_usuario_upload*/


	$db->call_precedure('mb_cadastro_sp_upload_arquivo', "inserir_registro,,$postIdApp,$filename,$_SESSION['disp']['user']")
?>

