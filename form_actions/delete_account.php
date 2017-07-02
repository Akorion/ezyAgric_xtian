<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$mCrudFunctions = new CrudFunctions();
if(isset($_POST["client_id"]) && $_POST["client_id"]!='' )
{
   
    $id=$_POST["client_id"];
	
	if(isset($_POST['delete'])){
	$table="client_accounts_tb";		 
    $where=" id='$id' ";
	$flag=$mCrudFunctions ->delete($table,$where);
	
    if($flag){
	 
    //redirect back to original page
	$util_obj->redirect_to( "../admin/home.php?action=manage&success=1" );
	
	}else{
	//redirect back to original page
	$util_obj->redirect_to( "../admin/home.php?action=manage&success=0" );
	}
    }

    if(isset($_POST['lock'])){
	$db= new DatabaseQueryProcessor();
	$table="client_accounts_tb";
    if($_POST['state']==1){$columns_value=" status='lock' ";}else{$columns_value=" status='Open' ";}	
    
    $where=" id='$id' ";
	
	$flag=$util_obj->update_table($db,$table,$columns_value,$where);
	
    if($flag){
	 
    //redirect back to original page
	$util_obj->redirect_to( "../admin/home.php?action=manage&success=1" );
	
	}else{
	//redirect back to original page
	$util_obj->redirect_to( "../admin/home.php?action=manage&success=0" );
	}
    }		
}else{
   //redirect back to original page
   $util_obj->redirect_to( "../admin/home.php?action=manage&success=0" );
  
}

?>