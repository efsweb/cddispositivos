<?php
class DBConnect{
	private $host = "";
	private $user = "";
	private $pass = "";
	private $banco = "";
	private $table = "";
	private $connected = false;
	private $result;
	private $_cn;
	public $fields = array();
	public $rows = array();
	public $debug = true;

	/**
	 * [Connect description]
	 */
	public function Connect(){
   		$this->_cn = new PDO('mysql:unix_socket=/cloudsql/carbon-facet-754:cadastro;dbname=cadastro', 'root', '');
		$this->connected = true;
	}

	public function call_precedure($proc_string, $params){
		if($this->connected){
			$pieces = explode(",", $params);
			$params_str = str_repeat('?,', count($pieces) - 1) . '?';

			$stmt = $this->_cn->prepare("call $proc_string($params_str);");
			
			for ($i=0; $i < sizeof($pieces); $i++){
				$val = $pieces[$i];
				$val = substr($val, 0, -1);
				$val = substr($val, 1, strlen($val)-1);
				$pos = $i + 1;
				$stmt->bindValue($pos,"$val",PDO::PARAM_STR);
			}

			$rs = $stmt->execute();
			return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
		}
	}
	/**
	 * [Disconect description]
	 */
	public function Disconect(){
		$this->connected = false;
	}
	/**
	 * [setDB description]
	 * @param [type] $db [description]
	 */
	public function setDB($db){
		$this->banco = $db;
	}
	/**
	 * Description
	 * @param type $tbl 
	 * @return type
	 */
	public function setTable($tbl){
		$this->table = $tbl;
	}
	/**
	 * Description
	 * @return type
	 */
	public function getTable(){
		return $this->table;
	}
	/**
	 * Description
	 * @param type $bool 
	 * @return type
	 */
	public function setDebug($bool){
		$this->debug = $bool;
	}

	public function decrypt($string) {
		$output = false;
		$arr = parse_ini_file($string);
		$this->banco=$arr['db_banco'];
		$this->host =$arr['db_host'];
		$this->user =$arr['db_username'];
		$this->pass =$arr['db_password'];
		$this->table=$arr['db_table'];
		return $arr;
	}
}
?>