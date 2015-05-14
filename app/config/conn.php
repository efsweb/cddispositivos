<?php
class DBConnect{
	private $host = "";
	private $user = "";
	private $pass = "";
	private $banco = "";
	private $table = "";
	private $connected = false;
	private $result;
	private $link;
	public $fields = array();
	public $rows = array();
	public $debug = true;

	/**
	 * [Connect description]
	 */
	public function Connect(){
		error_reporting(E_ALL);
		//$upOne = realpath(dirname(__FILE__) . '/..');
   		$config = "config.ini" ;
		$this->decrypt($config);
		//if($this->debug) { echo $this->banco."<->".$this->host."<->".$this->user."<->".$this->pass."<->".$this->table."<->"; }
		if(empty($banco)){
			$dns = "mysql:" . $this->host . ":" . $this->banco;
			$this->link = new PDO($dns, $this->user, $this->pass);
			$this->connected = true;
		}else{
			echo "</br>no DB selected</br>";
		}
	}

	/**
	 * [call_precedure description]
	 * @param  [type] $proc_string [description]
	 * @param  [type] $params      [description]
	 * @return [type]              [description]
	 */
	public function call_precedure($proc_string, $params){
		if($this->connected){
			$pieces = explode(",", $params);
			$params_str = str_repeat('?,', count($pieces) - 1) . '?';
			$stmt = $this->link->prepare("call ".$proc_string."(". $params_str .")");
			
			for ($i=0; $i < sizeof($pieces); $i++) {
				$val = $pieces[$i];
				$val = substr($val, 0, -1);
				$val = substr($val, 1, strlen($val)-1);
				$pos = $i + 1;
				$stmt->bindValue($pos, "$val" , PDO::PARAM_STR);
			}
			$rs = $stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return json_encode($result);
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
		/*$encrypt_method = "AES-256-CBC";
		$secret_key = '';
		$secret_iv = 'encriptador';
		// hash
		$key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		*/
		//THIAGO ADICIONEI ASPAS NAS VARIAVEIS GLOBAIS ABAIXO ISSO REMOVEU O ALERTA DO PHP E FUNCIONOU NORMALMENTE
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