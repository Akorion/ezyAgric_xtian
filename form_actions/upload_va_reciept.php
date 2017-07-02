<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

 $mCrudFunctions = new CrudFunctions();
 $util_obj= new Utilties();
 $client_id=$_SESSION['client_id'];
 $id=$util_obj->encrypt_decrypt("decrypt", $_POST['id']);
 
 $s=$util_obj->encrypt_decrypt("decrypt", $_POST['s']);
 
 
 $va_rows= $mCrudFunctions->fetch_rows("dataset_".$s,"*"," id='$id' ");
 $va_name=$va_rows[0]['biodata_va_name'];
 
 $target="../images/va_receipt/";
 
 if(isset($_FILES['receipt']['name'])){
    // Path to move uploaded files
     $temp_image = explode(".", $_FILES["receipt"]["name"]);
	
     $newimagename = "c".round(microtime(true)) . '.' . end($temp_image);
     
     $sizeimage = $_FILES['receipt']['size'];
	 
	 
	 if(($sizeimage/1024/1024  > 17)){
	  
	 }else{
	    $image_flag=false;
		
		if (move_uploaded_file($_FILES['receipt']['tmp_name'], $target.$newimagename)) {
          $image_flag=true;
		  
        }
		
		
		if( $image_flag==true){
           //echo $client_id ." ".$s." ".$id." ".$newimagename;
		   $flag=$mCrudFunctions->insert_into_va_reciept_tb($client_id, $s , $id, $newimagename);
            //echo $flag;
			
			$user_id=$_SESSION["user_id"];
            $client_id=$_SESSION["client_id"];
            $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
            $ip_address=$util_obj->getClientIpV4();
            $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Saved Reciept  under VA:$va_name  ", $time_of_activity, $ip_address);

		
		}
	}
}

?>