<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

 $mCrudFunctions = new CrudFunctions();
 $util_obj= new Utilties();
 
 switch($_POST['token']){
 
 case 'getdefaults' :
 if(isset($_POST['id'])&&$_POST['id']!="" ){
  header('Content-type: application/json');
  $id =$_POST['id'];
  //$client_id=$_SESSION['client_id'];
  $rows=$mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","id='$id' ");
  
  if(sizeof($rows)>0){
  
   $units=$rows[0]['units'];
   $unit_per_acre=(double)$rows[0]['unit_per_acre'];
   $unit_price=(double)$rows[0]['unit_price'];
   $commission_per_unit=(double)$rows[0]['commission_per_unit'];
   
   $dataArray=array('units'=>$units,'unit_per_acre'=>$unit_per_acre, 'unit_price'=>$unit_price,'commission_per_unit'=>$commission_per_unit);
  
   $util_obj->deliver_response(200,1,$dataArray);
  }
 
 
 }
 break;
 
 
 

 
}
 


?>