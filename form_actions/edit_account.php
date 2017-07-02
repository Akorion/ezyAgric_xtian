<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
if(isset($_POST["c_name"]) && 
  $_POST["c_name"]!=''&&
  isset($_POST["c_address"]) &&
  $_POST["c_address"]!=''&& 
  
  isset($_POST["c_value_chain"]) &&
  $_POST["c_value_chain"]!=''&& 
   isset($_POST["id"]) &&
  $_POST["id"]!=''&& 
  //isset($_POST["c_password"]) &&
  //$_POST["c_password"]!=''&& 
   isset($_POST["contact_name"]) &&
  $_POST["contact_name"]!=''&& 
   isset($_POST["contact_number"]) &&
  $_POST["contact_number"]!=''&& 
   isset($_POST["contact_email"]) &&
  $_POST["contact_email"]!=''&& 
  isset($_POST["contact_loc"]) &&
  $_POST["contact_loc"]!=''&& 
  isset($_POST["c_email"]) && 
  $_POST["c_email"]!=''
)
{
   
	//$password=sha1($_POST["password"]);
	
    $id=$_POST["id"];
	$c_name=$_POST["c_name"];
	$c_address=$_POST["c_address"];
	$c_email=$_POST["c_email"];
	
    $c_value_chain=$_POST["c_value_chain"];
    $contact_name=$_POST["contact_name"];
    $contact_number=$_POST["contact_number"];
    $contact_email=$_POST["contact_email"];
    $contact_loc=$_POST["contact_loc"];
  
	$table="client_accounts_tb";		 
    $columns_value=" c_name='$c_name', c_address='$c_address', c_email='$c_email',c_value_chain='$c_value_chain' , 
	                 contact_name='$contact_name' , contact_number='$contact_number' , contact_email='$contact_email' ,
					 contact_loc='$contact_loc' ";
    $where=" id='$id' ";
	
	$flag=$util_obj->update_table($db,$table,$columns_value,$where);
	
    if($flag){
	 $id=$util_obj->encrypt_decrypt("encrypt", $_POST["id"]);
    //redirect back to original page
	$util_obj->redirect_to( "../admin/home.php?action=edit&token=$id&success=1" );
	
	}else{
	$id=$util_obj->encrypt_decrypt("encrypt", $_POST["id"]);
	$util_obj->redirect_to( "../admin/home.php?action=edit&token=$id&success=0" );
	}	
}else{
   if(isset($_POST["id"])&&$_POST["id"]!=""){
    $id=$util_obj->encrypt_decrypt("encrypt", $_POST["id"]);
    //redirect back to original page
	$util_obj->redirect_to( "../admin/home.php?action=edit&token=$id&success=0&input=incomplete" );
   }else{
   $util_obj->redirect_to( "../admin/home.php?action=manage" );
   }
  
    
}

?>