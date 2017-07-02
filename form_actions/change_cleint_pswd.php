<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();

$mCrudFunctions = new CrudFunctions();

$id=$_SESSION["client_id"];
if(isset($_POST["old_pass"]) && 
  $_POST["old_pass"]!=''&&
  isset($_POST["new_pass"]) &&
  $_POST["new_pass"]!=''&& 
  isset($_POST["conf_pass"]) &&
  $_POST["conf_pass"]!=''
)
{
   
	$old_pass=$_POST["old_pass"];
	$new_pass=$_POST["new_pass"];
	$conf_pass=$_POST["conf_pass"];

	$table="client_accounts_tb";	

    $where=" id='$id' AND c_password='$old_pass' ";
	$rows= $mCrudFunctions->fetch_rows($table,"*",$where);
	
	
	if(sizeof($rows)==0){
	echo 0;
	}else{
	if($new_pass==$conf_pass){
	  
	  $table="client_accounts_tb";	

	 
      $columns_value=" c_password='$new_pass' ";
      $where=" id='$id' ";
	  
	  $flag=$util_obj->update_table($db,$table,$columns_value,$where);
	
      if($flag){
	    echo 1;
	
	  }else{
	     echo -1;
	  }	

	}else{
	echo -2;
	}

	}
	
}else{
   
    echo -3;
}

?>