<?php
ob_start();
session_start();
require_once('classes/class.login.php');

$debug = false;

switch($_SERVER['REQUEST_METHOD'])
{
	case 'GET': 
		$pass = (isset($_GET['senha'])) ? $_GET['senha'] : '';
		$user = (isset($_GET['usuario'])) ? $_GET['usuario'] : '';
		break;
	case 'POST': 
		$pass = (isset($_POST['senha'])) ? $_POST['senha'] : '';
		$user = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
		break;
	default:
		break;
}



//debug
if($debug)
	echo "valores do request  =>  " . $pass . " | " . $user . "</br>";

//executar verificação
if (array_key_exists($user, $arr)) {

	if($arr[$user]['pass'] == $pass){
		//debug
		if($debug)
			echo "valores da permissao  =>  " . $arr[$user]['permission'] . "</br>";
		
		//criar session
		
		$_SESSION["disp"]["user"] = $user;
		$_SESSION["disp"]["permissao"] = $arr[$user]['permission'];
		$_SESSION["disp"]["email"] = $arr[$user]['mail'];

		//debug
		if($debug){
			echo "valores da session  =>  ";
			print_r($_SESSION);
			echo  "</br>";
		}

		echo "ok";
	}else{
		echo "error";
	}
}else{
	echo "error";
}


?>