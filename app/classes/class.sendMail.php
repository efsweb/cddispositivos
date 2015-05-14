<?php
require 'PHPMailer/class.smtp.php';
require 'PHPMailer/class.phpmailer.php';
/////////////////////////////
//    class enviar email   //
/////////////////////////////
date_default_timezone_set('America/Sao_Paulo');
class sendMail
{

	public function __construct()
	{
		date_default_timezone_set('America/Sao_Paulo');
		
	}

	public function __destruct()
	{
		//echo 'A classe "', __CLASS__, '" foi destruída.<br />';
	}

	public function send($Subject, $modelo, $arr){
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 465;
		$mail->SMTP_PORT = "465";
		$mail->SMTPSecure = 'ssl';
		$mail->SMTPAuth = true;
		$mail->Username = "suporte@yboh.com.br";
		$mail->Password = "YBOH2011";
		$mail->CharSet = 'UTF-8';

		$mail->setFrom('suporte@yboh.com.br', 'YBOH');
		$mail->addReplyTo('suporte@yboh.com.br', 'YBOH');

		$upOne = realpath(dirname(__FILE__) . '/../..');
		$fileLocation = $upOne . "/modelos/" . $modelo . ".html" ; 
		$body = file_get_contents($fileLocation);
		switch ($modelo) {
			case 'senha':
			$body = str_replace("#EMAIL#", $arr['mail'], $body);
			$body = str_replace("#SENHA#", $arr['pass'], $body);
			$body = str_replace("#USUARIO#", $arr['user'], $body);
			break;
			case 'upload':
			$body = str_replace("#DATA#", $arr['data'], $body);
			$body = str_replace("#ARQUIVO#", $arr['filename'], $body);
			foreach ($arr['Cc'] as $key => $value) {
				$mail->AddBCC($value);
			}

			break;
			case 'relatorio':
			$body = str_replace("#DATA#", $arr['data'], $body);
			$body = str_replace("#ARQUIVO#", $arr['filename'], $body);
			foreach ($arr['Cc'] as $key => $value) {
				$mail->AddBCC($value);
			}

			break;
			default:
				# code...
			break;
		}		
		$mail->IsHTML(true);
		$mail->addAddress($arr['mail'], 'YBOH');
		$mail->Subject = $Subject;
		$mail->Body = $body;

		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			//echo "Message sent!";
		}

	}



}

?>