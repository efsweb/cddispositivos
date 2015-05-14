<?php
ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');
error_reporting(0);

require_once 'classes/class.callsp.php';
require_once 'classes/class.Utils.php';

$utils = new Mais_Utils();

$android = '';
$ios = '';
$total = '';
$arquivos = '';
$iosCad = '';
$androidCad = '';
$mes_extenso = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');

$debug = false;
$retorno = array(
	'status' => '',
	'info'	 => '', 
	'tipo'   => '' 
);

switch($_SERVER['REQUEST_METHOD']){
	case 'GET': 
		$appname = (isset($_GET['appname'])) ? $_GET['appname'] : '';
		break;
	case 'POST': 
		$appname = (isset($_POST['appname'])) ? $_POST['appname'] : '';
		break;
	default:
		break;
}

//debug
if($debug)
	echo "valores do request  =>  " . $appname . " | </br>";

$data = getdate();
$today = 'São Paulo, ' . $data['mday'] . " de " . $mes_extenso[intval($data['mon']) - 1] . " de " . $data['year'];
$feedBD = new callSp('mb_cadastro_relatorio');

$params = array($appname);
$result = json_decode($feedBD->execute($params), true);

if($debug){echo "<pre>"; print_r($result);echo "</pre>"; }

foreach ($result as $key => $value) {
	foreach ($value as $key2 => $value2) {
		if($key2 == 'visao'){
			switch ($value2) {
				case 'tabela_1':
					if (trim($result[$key]['col_1']) == 'Android') {
						$android = trim($result[$key]['col_2']);
					} elseif (trim($result[$key]['col_1']) == 'IOS') {
						$ios = trim($result[$key]['col_2']);
					}elseif (trim($result[$key]['col_1']) == 'Total') {
						$total = trim($result[$key]['col_2']);
					}
					
					break;
				case 'tabela_2':
					$dataBR = $utils->dateToBr($result[$key]['col_2']);
					$arquivos .= '<tr><td class="center">'. $dataBR . '</td><td class="tb_body">'. trim($result[$key]['col_1']) . '</td><td>'. trim($result[$key]['col_3']) . '</td></tr>';
					break;
				case 'tabela_3':
					if (trim($result[$key]['col_3']) == "Android") {
						$dataBR = $utils->dateToBr($result[$key]['col_4']);
						$androidCad .= '<tr><td valign="middle">'.$dataBR.'</td><td class="tb_dados" valign="middle">	<div><span class="span_names">YBOH: </span>'.trim($result[$key]['col_1']).'</div>	<div><span class="span_names">Nome: </span>'.trim($result[$key]['col_2']).'</div>	<div><span class="span_names">ID: </span>'.trim($result[$key]['col_6']).'</div></td><td valign="middle">	'.trim($result[$key]['col_5']).'</td></tr>';
					} elseif (trim($result[$key]['col_3']) == "IOS") {
						//2014-10-31 18:25:10
						
						$dataBR = $utils->dateToBr($result[$key]['col_4']);
						$iosCad .= '<tr><td valign="middle">'.$dataBR.'</td><td class="tb_dados" valign="middle">	<div><span class="span_names">YBOH: </span>'.trim($result[$key]['col_1']).'</div>	<div><span class="span_names">Nome: </span>'.trim($result[$key]['col_2']).'</div>	<div><span class="span_names">ID: </span>'.trim($result[$key]['col_6']).'</div></td><td valign="middle">	'.trim($result[$key]['col_5']).'</td></tr>';
					}					
					break;
				
				default:
					break;
			}
		}
	}
}

if($debug)
	echo $android . '</br>'. $ios . '</br>'. $total . '</br>'. $arquivos . '</br>'. $iosCad . '</br>'. $androidCad . '</br>';

$upOne = realpath(dirname(__FILE__) . '/..');
$pdfFileLocation = $upOne . "/modelos/pdfBase.html";
$content  = file_get_contents($pdfFileLocation);
if($appname == "sprinterinfomb"){
	$newName = 'SprinterInfo MB';
}else if($appname == "businfomb"){
	$newName = 'BusInfo MB';
}

$find 	 = array('#data#', '#APLICATIVO#','#android#','#ios#','#total#','#arquivo#','#iosCad#','#androidCad#');
$replace = array($today, $newName,   $android,   $ios,   $total,   $arquivos,   $iosCad,  $androidCad);
//$return = $android . '</br>'. $ios . '</br>'. $total . '</br>'. $arquivos . '</br>'. $iosCad . '</br>'. $androidCad . '</br>';
$return .= str_replace($find, $replace, $content);

$pdfFileLocationSave = $upOne . "/modelos/modelo_pdf.html";
file_put_contents($pdfFileLocationSave, $return);

if($debug)
	echo $return;

$retorno['status'] = 'ok';
$retorno['info'] = 'Arquivo gerado com sucesso.';
$retorno['tipo'] = 'string';

echo json_encode($retorno);

?>