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
 
 if(isset($_FILES['receipt']['name'])){
    // Path to move uploaded files
     $temp_image = explode(".", $_FILES["receipt"]["name"]);
	
     $newimagename = "c".round(microtime(true)) . '.' . end($temp_image);
     
     $sizeimage = $_FILES['receipt']['size'];
	 
	 echo $sizeimage;
	 if(($sizeimage/1024/1024  > 17)){
	  
	 }else{
	    $image_flag=false;
		
		if (move_uploaded_file($_FILES['receipt']['tmp_name'], $target.$newimagename)) {
          $image_flag=true;
		  
        }
		
		if( $image_flag==true){
          
		   $flag=$mCrudFunctions->insert_into_input_reciept_temp_tb(
            $client_id, 
			$user_id, 
			$va_dataset_id,
			$va_code, 
			$farmer_dataset_id, 
			$farmer_id, 
			$input_id, 
			$input_type, 
			$newimagename
			);
		}
	}
}
 
 
?>