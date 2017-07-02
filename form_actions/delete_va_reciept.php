<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

 $mCrudFunctions = new CrudFunctions();
 $util_obj= new Utilties();
 
 
 $id=$_POST['id'];
 
 
 
 $target="../images/va_receipt/";
 
 
 $rows=$mCrudFunctions->fetch_rows("va_reciept_tb","*","id='$id'  ");
 
 $id=$rows[0]['id'];
 $dataset_id=$rows[0]['dataset_id'];
 $va_id=$rows[0]['va_id'];
 $file=$target.$rows[0]['reciept_url'];
 

 
 if (!unlink($file))
  {
  echo ("Error deleting $file");
  }
else
  {
             $va_rows= $mCrudFunctions->fetch_rows("dataset_".$dataset_id,"*"," id='$va_id' ");
             $va_name=$va_rows[0]['biodata_va_name'];
            $mCrudFunctions->delete("va_reciept_tb","id='$id'");
  
            $user_id=$_SESSION["user_id"];
            $client_id=$_SESSION["client_id"];
            $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
            $ip_address=$util_obj->getClientIpV4();
            $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Deleted Reciept  under VA:$va_name  ", $time_of_activity, $ip_address);

  }
 
 
 
 
?>