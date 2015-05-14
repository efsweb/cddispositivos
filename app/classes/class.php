<?php
 
/////////////////////////////
//exemplo simples de class //
/////////////////////////////

class MyClass
{
  /**
   * [$prop1 description]
   * @var string
   */
  public $prop1 = "Sou uma propriedade de classe!";
 
  /**
   * Description
   * @return type
   */
  public function __construct()
  {
      echo 'A classe "', __CLASS__, '" foi instanciada!<br />';
  }
 
  /**
   * Description
   * @return type
   */
  public function __destruct()
  {
      echo 'A classe "', __CLASS__, '" foi destruída.<br />';
  }
  /**
   * [__toString description]
   * @return string [description]
   */
  public function __toString()
  {
      echo "Usando o método toString: ";
      return $this->getProperty();
  }
  /**
   * [setProperty description]
   * @param [type] $newval [description]
   */
  public function setProperty($newval)
  {
      $this->prop1 = $newval;
  }
 /**
  * Description
  * @return type
  */
  public function getProperty()
  {
      return $this->prop1 . "<br />";
  }
}
 
?>