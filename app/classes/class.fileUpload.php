<?php
/**
* 
*/
date_default_timezone_set('America/Sao_Paulo');
class fileUpload{
	
	

	function __construct()
	{
		# code...
	}

	/**
	* Check $_FILES[][name]
	*
	* @param (string) $filename - Uploaded file name.
	*/
	function check_file_uploaded_name ($filename)
	{
	    return (bool) ((preg_match("`^[-0-9A-Z_\.]+$`i",$filename)) ? true : false);
	}

	/**
	* Check $_FILES[][name] length.
	*
	* @param (string) $filename - Uploaded file name.
	*/
	function check_file_uploaded_length ($filename)
	{
	    return (bool) ((strlen($filename) < 225) ? true : false);
	}

	function check_file_type($fileExtencion){
		$ext_type = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		return in_array($fileExtencion, $ext_type);
	}


	function check_file_type_by_name($fileExtencion){
		$ext_type = array("xls", "xlsx");
		return in_array($fileExtencion, $ext_type);
	}

}



?>