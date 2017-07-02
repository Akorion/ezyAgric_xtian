
<?php
session_start();

//unset($_SESSION['inputs']);


#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
//require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

 
 $util_obj= new Utilties();
if(isset(
$_POST['farmer_dataset_id'])&&$_POST['farmer_dataset_id']!="" 
&& isset($_POST['va_dataset_id'])&&$_POST['va_dataset_id']!=""
&& isset($_POST['va_id'])&&$_POST['va_id']!=""
&& isset($_POST['farmer_id'])&&$_POST['farmer_id']!=""
&& isset($_POST['id'])&&$_POST['id']!=""

&& isset($_POST['type'])&&$_POST['type']!=""

){

 $va_dataset_id=$util_obj->encrypt_decrypt("decrypt", $_POST['va_dataset_id']);
 $va_id=$util_obj->encrypt_decrypt("decrypt", $_POST['va_id']);
 $client_id=$_SESSION["client_id"];
 $user_id=$_SESSION["user_id"];
 $farmer_dataset_id=$_POST['farmer_dataset_id'];
 $farmer_id=$_POST['farmer_id'];

switch($_POST['type']){

case 'seed' :
$qty=$_POST['qty'];
$id=$_POST['id'];
$date=$_POST['date'];
$token=$_POST['token'];
$type=$_POST['type'];
$array=array('id'=>$id);
updateList($token,"seed",$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date );

break;

case 'fertilizer' :
$qty=$_POST['qty'];
$id=$_POST['id'];
$date=$_POST['date'];
$token=$_POST['token'];
$type=$_POST['type'];
updateList($token,"fertilizer",$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date);

break;
case 'herbicide' :
$qty=$_POST['qty'];
$id=$_POST['id'];
$date=$_POST['date'];
$token=$_POST['token'];
$type=$_POST['type'];
updateList($token,"herbicide",$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date);
break;

case 'tractor':
$qty=$_POST['qty'];
$id=$_POST['id'];
$date=$_POST['date'];
$token=$_POST['token'];
$type=$_POST['type'];
updateList($token,"tractor",$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date);

break;

case 'cash':
$qty=$_POST['qty'];
$id=$_POST['id'];
$date=$_POST['date'];
$token=$_POST['token'];
$type=$_POST['type'];
updateList($token,"cash",$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date);

break;

case 'produce':

$qty=$_POST['qty'];
$id=$_POST['id'];
$date=$_POST['date'];
$token=$_POST['token'];
$type=$_POST['type'];
updateList($token,"produce",$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date);

break;

case 'paid':

$qty=$_POST['qty'];
$id=$_POST['id'];
$date=$_POST['date'];
$token=$_POST['token'];
$type=$_POST['type'];
updateList($token,"paid",$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date);

break;

}


}




 
 print_r($_SESSION['inputs']);

 //unset($_SESSION['inputs']);
 
 ////cart functions
 
 function addItem($session,$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date){
 $itemArray = array($type.$id=>array('client_id'=>$client_id,'user_id'=>$user_id,'va_dataset_id'=>$va_dataset_id,'va_id'=>$va_id,'farmer_dataset_id'=>$farmer_dataset_id,'farmer_id'=>$farmer_id,'id'=>$id,'qty'=>$qty,'date'=>$date));		
		if(!empty($_SESSION['inputs'][$session])) {
		
		
			if(in_array($id,$_SESSION['inputs'][$session])) {
				foreach($_SESSION['inputs'][$session] as $k => $v) {
					if($id == $k)	$_SESSION['inputs'][$session][$k] = $id;
				}
			} else $_SESSION['inputs'][$session] = array_merge($_SESSION['inputs'][$session],$itemArray);
		} else 	$_SESSION['inputs'][$session] = $itemArray;
 
 }
 
 function removeItem($session,$type,$id){
 //if (in_array($id,  $_SESSION['inputs'][$session][$type.$id]))
   //  {
     unset($_SESSION['inputs'][$session][$type.$id]);
    // }
 
 }
 
 function updateList($token,$session,$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date){
 
    if($token=="add"){
	
	addItem($session,$type,$client_id,$user_id,$va_dataset_id,$va_id,$farmer_dataset_id,$farmer_id,$id,$qty,$date);
	
	}else if($token=="remove"){
	
	removeItem($session,$type,$id);
	
	}
    ksort($_SESSION['inputs'][$session]);
 }
 
 
 
?>


