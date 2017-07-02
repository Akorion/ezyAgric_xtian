<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

 $util_obj= new Utilties();
if(isset($_POST["c_name"]) && 
  $_POST["c_name"]!=''&&
  isset($_POST["c_address"]) &&
  $_POST["c_address"]!=''&& 
  isset($_POST["c_valuechain"]) &&
  $_POST["c_valuechain"]!=''&& 
   isset($_POST["password"]) &&
  $_POST["password"]!=''&& 
  isset($_POST["c_password"]) &&
  $_POST["c_password"]!=''&& 
   isset($_POST["contact_name"]) &&
  $_POST["contact_name"]!=''&& 
   isset($_POST["contact_phone"]) &&
  $_POST["contact_phone"]!=''&& 
   isset($_POST["contact_email"]) &&
  $_POST["contact_email"]!=''&& 
   isset($_POST["contact_office"]) &&
  $_POST["contact_office"]!=''&& 
  isset($_POST["c_email"]) && 
  $_POST["c_email"]!=''
)
{
   
    $mCrudFunctions = new CrudFunctions();
	//$password=sha1($_POST["password"]);
	
    $c_name=$_POST["c_name"];
	$c_address=$_POST["c_address"];
	$c_email=$_POST["c_email"];
	$c_valuechain=$_POST["c_valuechain"];
	$password =$_POST["password"];
	$c_password=$_POST["c_password"];
	$contact_name=$_POST["contact_name"];
	$contact_phone=$_POST["contact_phone"];
	$contact_email=$_POST["contact_email"];
	$contact_loc=$_POST["contact_office"];
	
    if($password==$c_password){
	$flag=$mCrudFunctions->insert_into_client_accounts_tb($c_name,$c_address,$c_email,$c_valuechain,$password,$contact_name,$contact_phone,
											   $contact_email,
											   $contact_loc
											   );
	
    if($flag){
	$util_obj->redirect_to( "../admin/home.php?action=account&success=1");
	}else{$util_obj->redirect_to( "../admin/home.php?action=account&success=0" );}	
	
	}else{
	$util_obj->redirect_to( "../admin/index.php?success=0&flag=0" );
	}
    
}else{

    //redirect back to original page
	$util_obj->redirect_to( "../admin/home.php?action=account&success=0&input=incomplete" );
    
}

?>