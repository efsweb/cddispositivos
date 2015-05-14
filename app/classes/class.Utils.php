<?php
date_default_timezone_set('America/Sao_Paulo');
/**
 * Utils()
 * Contem varias funcoes do sistema
 * @package     sunline
 * @subpackage  Mais
 * @name        Utils
 * @author      Eliel Fernandes - <eliel@maiskreativo.com.br>
 * @license     [Link da licenca] Commercial
 * @link        library/mais/utils.php
 * @version     0.1
 * @since       08/09/2012
 */
 
 /**
  * Mais_Utils()
  * Classe de utilidades para clientes
  **/
class Mais_Utils
{
	/**
	 * dateToBr()
	 * 	Efetua conversao da data para o padrao brasileiro - DD/MM/AAAA
	 * @access	public
	 * @return	$data	String	String com o formato correto da data
	 **/
	public function dateToBr( $date )
	{
		$data_en = explode(' ', $date);
		/*if(isset($data_en[1])){
			$data = preg_replace( '/^(\d{4})-(\d{2})-(\d{2})$/', '$3/$2/$1', $data_en[0] ) . ' - ' . $data_en[1];
		}else{*/
			$data = preg_replace( '/^(\d{4})-(\d{2})-(\d{2})$/', '$3/$2/$1', $data_en[0] );
		//}
		
		return $data;
	}
	
	/**
	 * dateToEn()
	 * 	Efetua conversao da data para o padrao Ingles EUA - AAAA-MM-DD
	 * @access	public
	 * @return	$data	String	String com o formato correto da data
	 **/
	public function dateToEn( $date )
	{
		$data_br = $date;
		$data = preg_replace( '/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $data_br );
		
		return $data;
	}	
	
	/**
	 * setMensagem()
	 * Trata html corpo do email 
	 * @access public
	 * @author Eliel Fernandes - dotEx_<eliel.fma@gmail.com>
	 * @since 30/01/12
	 **/
	public function setMensagem( $original, $substituir, $arquivo ){
		$file = file_get_contents(BASE_PATH . '/modelos/' . $arquivo);
		return str_replace($original, $substituir, $file);
	}
	
	public function setTipo(){
		$tipo = array(
				'1' => 'admin',
				'2' => 'user'
		);
		return $tipo;
	}
	public function getTipo($id){
		$tipo = array(
				'1' => 'admin',
				'2' => 'user'
		);
		return $tipo[$id];
	}
	
	
	/*
	 * Resizeimage()
	 * Altera o tamanho da imagem para o padrão do sistema
	 */
	function resizeImage($image,$width,$height,$scale){
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
		    case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
				break;
		    case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
				break;
	  	}
		imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
		
		switch($imageType) {
			case "image/gif":
		  		imagegif($newImage,$image); 
				break;
	      	case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
		  		imagejpeg($newImage,$image,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$image);  
				break;
	    }
		
		chmod($image, 0777);
		//return $image;
	}

}
?>

