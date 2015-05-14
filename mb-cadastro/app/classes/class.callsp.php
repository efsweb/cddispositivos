<?php
include_once('config/conn.php');
class callSp{ 
  public $proc;
  public $params;
  public $temp;

  public function __construct($proc){
    
    $this->temp = new DBConnect();	
    $this->temp->Connect();
    $this->proc = $proc;
  }

  public function execute($param){
    $params = '';
    foreach ($param as $key => $value) {
      $params .= "'".  $value."',";
    }
    $params = substr($params, 0, -1);
    return $this->temp->call_precedure($this->proc, $params);
  }
}
 
?>