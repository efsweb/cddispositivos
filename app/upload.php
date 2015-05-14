<?php
session_start();
require_once('classes/class.fileUpload.php');
require_once('classes/class.excelCheck.php');
require_once('google/appengine/api/cloud_storage/CloudStorageTools.php');
require_once('classes/class.sendMail.php');

$uploaddir = __DIR__ . '/../arquivos/';
$testerFile = new fileUpload();


/*//debug
echo "<pre>";
print_r($_FILES);
echo "</pre>";
//*/
$retorno = array(
	'status' => '',
	'info'	 => '', 
	'tipo'   => '' 
);


$uploadfile = $uploaddir . $_FILES[0]['name'];
$postIdApp = (isset($_POST['appname'])) ? $_POST['appname'] : '';
$force = (isset($_POST['force'])) ? TRUE : FALSE;
$filename = $_FILES[0]['name'];
$newFileName = md5($_FILES[0]['name']);

$rest = explode(".", $_FILES[0]['name']);
$type = array_pop($rest);
//echo  $testerFile->check_file_uploaded_name($newFileName) .' <=> ' . $testerFile->check_file_uploaded_length($newFileName) .' <=> ' . $testerFile->check_file_type_by_name($type) ;
if( $testerFile->check_file_uploaded_name($newFileName) && $testerFile->check_file_uploaded_length($newFileName)  ) {
	//ok para verificação inicial
	//mover0
	$newLocal = 'gs://mb-cadastro-arquivos/' . $newFileName . '.xlsx';
	if (!move_uploaded_file($_FILES[0]['tmp_name'] , $newLocal)) {
	    //echo "falha ao copiar...\n";
	    $retorno['status'] = 'error';
	    $retorno['info']   = 'falha ao copiar ' . $newLocal;
	    $retorno['tipo']   = 'string';
	    
	}else{
		//verificar conteudo
		$checkFile = new excelCheck($newFileName . '.'  . $type);
		$result = $checkFile->verificarConteudo();
		//echo $result;
		if($result == "error"){
			
			$retorno['status'] = 'error';
		    $retorno['info']   = 'conteudo invalido';
		    $retorno['tipo']   = 'string';
	    
		}else{

			$feedBD = new callSp('mb_cadastro_sp_upload_arquivo');

			if($force){
				$dados = array(
					'inserir_registro_forcado', ' ', $postIdApp, $filename, $_SESSION['disp']['user']
				);
	          	//print_r($dados);
	          	$feedBDResult = json_decode($feedBD->execute($dados), true);
	          	if($feedBDResult[0]['tipo_retorno'] == "OK"){
	          		
				$retorno['status'] = 'ok';
			    $data = getdate();
				$today = $data['mday']."/".$data['mon']."/".$data['year'];
			    $retorno['info']   = array('id_banco' => $feedBDResult[0]['yboh_id_arquivo'], 'nomeFile' => $newFileName . "." . $type, 'nomeReal'=> $filename, 'data'=>$today);
			    $retorno['tipo']   = 'array';
	    
	          	}else{
	          		$retorno['status'] = 'error';
				    $retorno['info']   = 'problemas com o banco';
				    $retorno['tipo']   = 'string';
	          	}
			}else{
	          	$dados = array(
					'inserir_registro', ' ', $postIdApp, $filename, $_SESSION['disp']['user']
				);
	          	//print_r($dados);
	          	$feedBDResult = json_decode($feedBD->execute($dados), true);
	  			//[{"cod_retorno":"1","tipo_retorno":"CONFIRMACAO","desc_retorno":"Dado existe. Solicitar confirmacao de sobreposicao"}]
	  			
	  			//print_r($feedBDResult[0]['tipo_retorno']);
	 
	  			if($feedBDResult[0]['tipo_retorno'] == 'CONFIRMACAO'){

	  		// 		$mailto = new sendMail();
					// $data = getdate();
					// $today = $data['mday']."/".$data['mon']."/".$data['year'];
					// $arr = array(
					// 	"email" => array( "mail" => "thiago@yboh.com.br", 'data' => $today , 'filename' => $filename, 'Cc' => array("0"=>"thiago@yboh.com.br", "1"=>"eliel@yboh.com.br") ),
					// );
					// $mail = $mailto->send('Upload de arquivo', 'upload', $arr['email']);

					
	  				$retorno['status'] = 'confirmacao';
				    $retorno['info']   = 'arquivo existente';
				    $retorno['tipo']   = 'string';
	  			}elseif ($feedBDResult[0]['tipo_retorno'] == "OK") {
	  				$retorno['status'] = 'ok';
	  				$data = getdate();
					$today = $data['mday']."/".$data['mon']."/".$data['year'];
				    $retorno['info']   = array('id_banco' => $feedBDResult[0]['yboh_id_arquivo'], 'nomeFile' => $newFileName . "." . $type, 'data'=>$today);
				    $retorno['tipo']   = 'array';
	  			}else{
	  				$retorno['status'] = 'error';
				    $retorno['info']   = 'problemas com o banco';
				    $retorno['tipo']   = 'string';
	  			}
	  		}
			/*$query = $_SERVER['PHP_SELF'];
			$path = pathinfo( $query );

			$str = "http://$_SERVER[HTTP_HOST]$path[dirname]";
			$location = substr($str, 0, strlen($str)-3) . "arquivos/" . $newFileName . '.xls';
			*/
			
			//*/
			//echo "ok";
		}
	}

	
}else{
	$retorno['status'] = 'error';
    $retorno['info']   = 'problemas com o tipo de arquivo';
    $retorno['tipo']   = 'string';
}

echo json_encode($retorno);
?>