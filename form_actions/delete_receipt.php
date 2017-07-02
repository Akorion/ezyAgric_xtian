<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

 $mCrudFunctions = new CrudFunctions();
 $util_obj= new Utilties();
 
 $va_dataset_id=$util_obj->encrypt_decrypt("decrypt", $_POST['va_dataset_id']);
 
 $va_id=$util_obj->encrypt_decrypt("decrypt", $_POST['va_id']);
 
 //echo $_SESSION["client_id"]." ".$_SESSION["user_id"]." ".$va_dataset_id." ". $va_id." ".$_POST['farmer_dataset_id']." ". $_POST['farmer_id']." ".$_POST['id']." ".$_POST['type'];
 $client_id=$_SESSION["client_id"];
 $user_id=$_SESSION["user_id"];
 $farmer_dataset_id=$_POST['farmer_dataset_id'];
 $farmer_id=$_POST['farmer_id'];
 $input_type=$_POST['type'];
 $input_id=$_POST['id'];
 
 
 $va_rows= $mCrudFunctions->fetch_rows("dataset_".$va_dataset_id,"*"," id='$va_id' ");

 $code=str_replace(".","",$va_rows[0]['biodata_va_code']);
 $code=str_replace(" ","",$code);
 $code=strtolower($code);
 $va_code=$code;
 
 $target="../images/va_receipt/";
 
 
 $rows=$mCrudFunctions->fetch_rows("input_reciept_temp_tb","*","client_id='$client_id' AND user_id='$user_id' AND va_dataset_id='$va_dataset_id' AND va_id='$va_code' AND farmer_dataset_id='$farmer_dataset_id' AND farmer_id='$farmer_id' AND input_id='$input_id' AND input_type='$input_type' ");
 
 $id=$rows[0]['id'];
 $file=$target.$rows[0]['reciept_url'];
 
 if (!unlink($file))
  {
  echo ("Error deleting $file");
  }
else
  {
  $mCrudFunctions->delete("input_reciept_temp_tb","id='$id'");
  }
 
 
 
 
?>