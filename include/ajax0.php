<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT']."/ezyagric_v2/php_lib/user_functions/crud_functions_class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ezyagric_v2/php_lib/lib_functions/database_query_processor_class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ezyagric_v2/php_lib/lib_functions/utility_class.php";
$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();

$token = $_POST['token'];
switch ($token) {
	case 'delete_user':
		# code...
		delete_user();
		break;
	case 'add_item':
		add_item();
		break;
	case 'edit_user':
		edit_user();
		break;
  case 'delete_item':
    delete_item();
    break;
  case 'edit_item':
    edit_item();
    break;
  case 'save_user_edits':
    save_edits();
    break;
  case 'save_edit_item':
    save_edit_item();
    break;
  case 'get_datasets':
    get_datasets();
    break;
  case 'get_dataset_name':
    get_dataset_name();
    break;
  case 'update_va_dataset_id':
    update_va_dataset_id();
    break;
	default:
		# code...
		break;
}

function add_item(){
	global $util_obj;
	global $db;
	global $mCrudFunctions;
	//echo "Item Added";

	$iname = $_POST['iname'];
	$itype = $_POST['itype'];
	$uname = $_POST['uname'];
	$upacre = $_POST['upacre'];
	$uprice = $_POST['uprice'];
	$cpu = $_POST['cpu'];

	$client_id = $_SESSION['client_id'];

	$result = $mCrudFunctions->insert_into_out_grower_threshold_tb($client_id, $iname, $itype, $uname, $upacre, $uprice, $cpu);

	if($result){
		echo "Item Saved";
	}else{
		echo "An Error Occured"; 
	}
}

function delete_user(){
	global $util_obj;
	global $db;
	global $mCrudFunctions;
	
	$id = $_POST['id'];
	
	$result = $mCrudFunctions->delete_from_clients_users_tb($id); 
	
	
   $user_id=$_SESSION["user_id"];
   $client_id=$_SESSION["client_id"];
   $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
   $ip_address=$util_obj->getClientIpV4();
   $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Deleted system user  ", $time_of_activity, $ip_address);

   echo "fdghj";

}


function delete_item(){
  global $util_obj;
  global $db;
  global $mCrudFunctions;
  
  $id = $_POST['id'];
  
  $result = $mCrudFunctions->delete_from_out_grower_threshold_tb($id); 



}

function edit_user(){

	global $util_obj;
  global $db;
  global $mCrudFunctions;
  
   $id = $_POST['id'];
  
   $result = $mCrudFunctions->select_from_clients_users_tb($id);
   
   $user_id=$_SESSION["user_id"];
   $client_id=$_SESSION["client_id"];
   $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
   $ip_address=$util_obj->getClientIpV4();
   $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Edited system user information ", $time_of_activity, $ip_address);

  
  $user_data = json_encode($result);

  echo $user_data;
}

function edit_item(){

  global $util_obj;
  global $db;
  global $mCrudFunctions;
  
  $id = $_POST['id'];
  
  $result = $mCrudFunctions->select_from_out_grower_threshold_tb($id);

  $item_data = json_encode($result);

  echo $item_data;
}

function save_edits(){
  global $util_obj;
  global $db;
  global $mCrudFunctions;

  $id = $_POST['id'];
  $username = $_POST['uname'];
  $useremail = $_POST['uemail'];
  $userpassword = $_POST['upasswd'];
  $userrole = $_POST['urole'];

  $result = $mCrudFunctions->update_clients_users_tb($id, $username, $useremail, $userpassword, $userrole);

  if($result){
    echo "Updated";
  }else{
    echo "Failed";
  }

}

function save_edit_item(){
  global $util_obj;
  global $db;
  global $mCrudFunctions;

  $id = $_POST['id'];
  $iname = $_POST['iname'];
  $itype = $_POST['itype'];
  $uname = $_POST['uname'];
  $upacre = $_POST['upacre'];
  $uprice = $_POST['uprice'];
  $cpu = $_POST['cpu'];

  $result = $mCrudFunctions->update_out_grower_threshold_tb($id, $iname, $itype, $uname, $upacre, $uprice, $cpu);

  if($result){
    echo "Updated";
  }else{
    echo "Failed";
  }


}

function get_datasets(){
   global $util_obj;
   global $db;
   global $mCrudFunctions;

   $id = $_POST['id'];
   $result = $mCrudFunctions->select_from_datasets_tb($id);

   $dataset_data = json_encode($result);

   echo $dataset_data;
}

function update_va_dataset_id(){
   global $util_obj;
   global $db;
   global $mCrudFunctions;

   $id = $_POST['id'];
   $global_id = $_POST['global_id'];

   $result = $mCrudFunctions->update_datasets_tb($id, $global_id);

   if($result){
     echo "Dataset Attached";
   }else{
     echo "Failed";
   }
}


function get_dataset_name(){
   global $util_obj;
   global $db;
   global $mCrudFunctions;

   $id = $_POST['id'];
   $result = $mCrudFunctions->select_name_from_datasets_tb($id);

   $dataset_name_data = json_encode($result);

   echo $dataset_name_data;
}

?>