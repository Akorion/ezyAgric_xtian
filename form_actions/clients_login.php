<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

 $util_obj= new Utilties();
if(isset($_POST["login"]) && isset($_POST["username"]) && $_POST["username"]!=''&& isset($_POST["password"]) && $_POST["password"]!='')
{
   
    $mCrudFunctions = new CrudFunctions();
	
	$password =$_POST["password"];
    $username=$_POST["username"];
	
	$table="clients_users_tb";
    $columns="*";
    $where=" user_email='$username' AND password='$password' ";
    $rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
	$user_role = $rows[0]['role'];
	$_SESSION["user_role"]=$user_role;

    if(sizeof($rows)==0){ 
	//redirect back to original page
	$table="client_accounts_tb";		 
    $columns="*";
    $where=" c_email='$username' AND c_password='$password' ";
    $rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
	
	$client_id=$rows[0]['id'];
	//$client_id=$rows[0]['client_id'];
	$username=$rows[0]['c_name'];
	
	$status =$rows[0]['status'];
	
	if($status=="Open"){
	
	//setting up sessions
	$_SESSION["user_account"]=$username;//this is what the user uses to login
	$_SESSION["account_name"]=$username;;
	$_SESSION["client_id"]=$client_id;
	$_SESSION["user_id"]=0;
//        if($_SESSION["client_id"]==15){
//
//            $user_id=$_SESSION["user_id"];
//            $client_id=$_SESSION["client_id"];
//            $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
//            $ip_address=$util_obj->getClientIpV4();
//            $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Logged In", $time_of_activity, $ip_address);
//
//            $util_obj->redirect_to( "../dairyDashboard.php" );
//
////	$util_obj->redirect_to( "../dash1.php" );
//        }else
    if($_SESSION["client_id"]==2){
	
	$user_id=$_SESSION["user_id"];
	$client_id=$_SESSION["client_id"];
	$time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
	$ip_address=$util_obj->getClientIpV4();
	$mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Logged In", $time_of_activity, $ip_address);

        $util_obj->redirect_to( "../dashboardAll.php" );

//	$util_obj->redirect_to( "../dash1.php" );
	}
	elseif($_SESSION["account_name"]== 'Ankole Coffee Producers Cooperative Union Ltd'){
        $util_obj->redirect_to( "../acpudash.php" );
    }
    elseif ($_SESSION["account_name"] == "Insurance"){
        $util_obj->redirect_to("../insuranceDash.php");
    }
    elseif($_SESSION["account_name"] == "Ahurire"){
        $util_obj->redirect_to("../dairyDashboard.php");
    }
	else{
	    $util_obj->redirect_to( "../dashboardAll.php" );
	}
	
	
	}else{
	
	//redirect back to original page
	$util_obj->redirect_to( "../index.php?success=0&lock=1" );
	
	}
	
	
	}else{
	$client_id=$rows[0]['client_id'];
	$user_id=$rows[0]['id'];
	$username=$rows[0]['username'];
	 
	$info=$mCrudFunctions->fetch_rows("client_accounts_tb","*","id='$client_id' ");
	$status =$info[0]['status'];
	if($status=="Open"){
	
	//setting up sessions
	$_SESSION["account_name"]=$info[0]['c_name'];;
	$_SESSION["user_account"]=$username;//this is what the user uses to login
	
	$_SESSION["client_id"]=$client_id;
	$_SESSION["user_id"]=$user_id;
	$_SESSION["role"]=$rows[0]['role'];
	
	
	if($_SESSION["client_id"]==2){
	
	
	$user_id=(int)$_SESSION["user_id"];
	$client_id=(int)$_SESSION["client_id"];
	$time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
	$ip_address=$util_obj->getClientIpV4();
	$mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Logged In", $time_of_activity, $ip_address);
	
	$util_obj->redirect_to( "../dashboardAll.php" );
	}else{
	
	$util_obj->redirect_to( "../dashboardAll.php" );
	}
	
	
	}else{
	
	//redirect back to original page
	$util_obj->redirect_to( "../index.php?success=0&lock=1" );
	
	}
	
	}
	
	
    
}else{

    //redirect back to original page
	$util_obj->redirect_to( "../index.php?success=0&input=incomplete" );
    
}

?>