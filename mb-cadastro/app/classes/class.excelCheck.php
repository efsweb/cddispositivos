<?php
class excelCheck{
public $fileName;
  public $fields_obri = array("IdDownloadMobile","Conta Concessionário","Razão Social","Nome","CPF","RG","Cargo","Email","Celular","Modelo","Sistema Operacional","IDAparelho","Status","Data Cadastro", "Data Aprovação");

  public $arr = array();
  /**
   * Description
   * @return type
   */
  public function __construct($fileName){
    require_once('PHPReader/excel_reader2.php');
    require_once 'PHPReader/simpleXLS.php';
    require_once 'class.callsp.php';/**/
    $this->fileName = $fileName;
  }


  public function uploadConteudo($id){
    /*$upOne = realpath(dirname(__FILE__) . '/../..');
    $fileLocation = $upOne . "/arquivos/" . $this->fileName;
    chmod($fileLocation, 0777);
    if( !file_exists( $fileLocation ) ) die( 'File could not be found at: ' . $fileLocation );

    $path_parts = pathinfo($fileLocation);
    $ext =  $path_parts['extension'];

    if($ext == 'xls'){

      $data = new Spreadsheet_Excel_Reader($fileLocation, false);
      $fields = array();
      $values = array_fill_keys($this->fields_obri, '');
      $arr =array();
      for($row=1;$row<=$data->rowcount($sheet_index=0);$row++) {
        for($col=1;$col<=$data->colcount($sheet_index=0);$col++) {
          if($row == 1){
            $fields[] = utf8_encode((string)$data->val($row,$col,$sheet_index=0));
          }else{
            if(in_array($fields[$col-1], $this->fields_obri) )
              $values[$fields[$col-1]] = utf8_encode((string)$data->val($row,$col,$sheet_index=0));
          }
        }
        if($row != 1){
          $values['id'] = $id;
          $arr[] = $values;
          $values = array_fill_keys($this->fields_obri, '');
        }
      }

        //echo "<pre>"; print_r($arr); echo "</pre>"; 
      $feedBD = new callSp('mb_cadastro_sp_insere_linhas');
      for ($i=0; $i < sizeof($arr); $i++) { 
        //echo "<pre>"; print_r($arr[$i]); echo "</pre>"; 
        $feedBD->execute($arr[$i]);
      }
    }elseif($ext == 'xlsx'){
      $data = new SimpleXLSX($fileLocation); 
      $row = array_fill_keys($this->fields_obri, '');
      $cells = $data->rows(1); 
      foreach($cells as $col => $cell){
        if($col == 0){
          $fields = $cell;                                
        }else{
          //$row = array();
          foreach($fields as $findex => $fval){
              if(in_array( utf8_encode((string)trim($fval)), $this->fields_obri) )
                $row[$fval] = (isset($cell[$findex])?$cell[$findex]:NULL);
          }
                                //print_r($row);
          $arr[] = $row;
        }
      }               
      //echo "<pre> xlsx fields"; print_r($fields); echo "</pre>"; 
      //echo "<pre> xlsx"; print_r($arr); echo "</pre>"; 
     $feedBD = new callSp('mb_cadastro_sp_insere_linhas');
      for ($i=0; $i < sizeof($arr); $i++) { 
        $arr[$i]['id'] = $id;
        //echo "<pre>"; print_r($arr[$i]); echo "</pre>"; 
        $feedBD->execute($arr[$i]);
      }
    }*/
  }
  public function verificarConteudo(){
    $fileLocation = "gs://mb-cadastro-arquivos/$this->fileName";
    $path_parts = pathinfo($fileLocation);
    $ext =  $path_parts['extension'];

    if($ext == 'xls'){
      $data = new Spreadsheet_Excel_Reader($fileLocation, false);
      //print_r($data->boundsheets);
      for($row=1;$row<=$data->rowcount($sheet_index=0);$row++) 
        for($col=1;$col<=$data->colcount($sheet_index=0);$col++) 
          $arr[$row][$col] = utf8_encode((string)$data->val($row,$col,$sheet_index=0));

        $result = array_diff($this->fields_obri, $arr[1]);
        $total = sizeof($result);

        if ($total == 0) {
        return "ok";
      }else{
        unlink($fileLocation);
        return "error";
      }
    }elseif($ext == 'xlsx'){
      $data = new SimpleXLSX($fileLocation); 
      $cells = $data->rows(1); 
      foreach($cells as $col => $cell){
        if($col == 0){
          $fields = $cell;                                
        }else{
          $row = array();
          foreach($fields as $findex => $fval){
            $row[$fval] = (isset($cell[$findex])?$cell[$findex]:NULL);
          }
          $rs[] = $row;
        }
      }               

      $keys = array_keys($rs[0]);
      $result = array_diff($this->fields_obri, $keys);
      $total = sizeof($result);
      if ($total == 0) {
        return "ok";
      }else{
        return "error";
      }
    }
  }  
}
