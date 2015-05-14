<?php

$aplicativo = $_POST['appname'];
$uploadfile = $_GET['arquivo'];
$uploaddir = __DIR__ . '/../arquivos/';
echo $aplicativo;
// foreach($_FILES as $file){
// 	if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name']))){
// 		echo 'ok';
// 	}else{
// 		echo 'erro';
// 	}
// }
?>