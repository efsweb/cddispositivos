<?php
/////////////////////////////
//exemplo simples de class //
/////////////////////////////
date_default_timezone_set('America/Sao_Paulo');
class callSp
{ 
	public $proc;
	public $params;
	public $temp;
  /**
   * Description
   * @return type
   */
  public function __construct($proc)
  {
    $upOne = realpath(dirname(__FILE__) . '/..');
    $fileLocation = $upOne . "/config/conn.php";
    include_once( $fileLocation);
  	//conecção com o banco
  	$this->temp = new DBConnect();	
  		//$temp->teste();
  	$this->temp->Connect();
  		//fim conecção com o banco
  	$this->proc = $proc;
  }
 
  /**
   * Description
   * @return type
   */
  public function __destruct()
  {
      //echo 'A classe "', __CLASS__, '" foi destruída.<br />';
  }
  /**
   * [__toString description]
   * @return string [description]
   */
  public function execute($param){
  	$params = '';
  	foreach ($param as $key => $value) {
			$params .= "'".  $value."',";
	}
	$params = substr($params, 0, -1);
	/*echo "<pre>";
	echo $params;
	echo "</pre>";
	//*/
  	return $this->temp->call_precedure($this->proc, $params);
  }
}
 
?>