<?php

require_once('classes/class.login.php');
require_once('classes/class.sendMail.php');
$debug = false;
$mail_exist = false;
$keySav;
switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET': 
		$mail = (isset($_GET['email'])) ? $_GET['email'] : '';
		break;
	case 'POST': 
		$mail = (isset($_POST['email'])) ? $_POST['email'] : '';
		break;
	default:
		break;
}



//debug
if($debug)
	echo "valores do request  =>  " . $mail . "</br>";

//executar verificação
foreach ($arr as $key => $value) {
	//debug
	if($debug)
		echo $arr[$key]['mail'];


	if( $arr[$key]['mail'] == $mail){
		//send mail
		$keySav = $key;
		$mail_exist = true;
	}
}

if($mail_exist){
	//send mail
	$mailto = new sendMail();

	$mailto->send('Esqueci minha senha', 'senha', $arr[$keySav]);
	echo "ok";
}else{
	echo "error";
}
?>