<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

 $util_obj= new Utilties();
if(isset($_POST["login"]) && isset($_POST["username"]) && $_POST["username"]!=''&& isset($_POST["password"]) && $_POST["password"]!='')
{
   
    $mCrudFunctions = new CrudFunctions();
	//$password=sha1($_POST["password"]);
	$password =$_POST["password"];
    $username=$_POST["username"];
	
	$table="admin_login_tb";		 
    $columns="*";
    $where=" user_name='$username' AND password='$password' ";
	
    $rows= $mCrudFunctions->fetch_rows($table,$columns, $where);
	//echo $username." ".$password." ".sizeof($rows);
    if(sizeof($rows)==0){ 
	//redirect back to original page
	$util_obj->redirect_to( "../admin/index.php?success=0&flag=0" );
	
	}else{
	$user_id=$rows[0]['id'];
	$login_time=date('Y-m-d H:i:s');
    $ip_address=$util_obj->getClientIpV4();
    //echo(date("Y-m-d",$t));

	//setting up sessions
	$_SESSION["username"]=$username;//this is what the user uses to login
	
	$_SESSION["user_id"]=$user_id;
	
	$mCrudFunctions->insert_into_admin_logs_tb($user_id,$login_time, $ip_address);
	
	$util_obj->redirect_to( "../admin/home.php" );
	}
	
	
    
}else{

    //redirect back to original page
	$util_obj->redirect_to( "../admin/index.php?success=0&input=incomplete" );
    
}

?>