<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
if(isset($_POST["dname"]) && 
  $_POST["dname"]!=''&&
  isset($_POST["dtype"]) &&
  $_POST["dtype"]!=''&& 
  isset($_POST["dregion"]) &&
  $_POST["dregion"]!=''&& 
   isset($_POST["start"]) &&
  $_POST["start"]!=''&& 
  isset($_POST["end"]) &&
  $_POST["end"]!=''&&  
  isset($_POST["id"]) && 
  $_POST["id"]!=''
)
{
    $id=$_POST["id"];
	$start=$_POST["start"];
	$end=$_POST["end"];
	$dregion=$_POST["dregion"];
	$dtype=$_POST["dtype"];
	$dname=$_POST["dname"];
	
	$table="datasets_tb";		 
    $columns_value=" dataset_name='$dname', dataset_type='$dtype', region='$dregion' , start_date='$start' , end_date='$end'  ";
    $where=" id='$id' ";
	
	$flag=$util_obj->update_table($db,$table,$columns_value,$where);
	
    if($flag){
	 $id=$util_obj->encrypt_decrypt("encrypt", $_POST["id"]);
    //redirect back to original page
	$util_obj->redirect_to( "../admin/home.php?action=editdataset&token=$id&success=1" );
	
	}else{
	$id=$util_obj->encrypt_decrypt("encrypt", $_POST["id"]);
	$util_obj->redirect_to( "../admin/home.php?action=editdataset&token=$id&success=0" );
	}	
}else{
   if(isset($_POST["id"])&&$_POST["id"]!=""){
    $id=$util_obj->encrypt_decrypt("encrypt", $_POST["id"]);
    //redirect back to original page
	$util_obj->redirect_to( "../admin/home.php?action=editdataset&token=$id&success=0&input=incomplete" );
   }else{
   $util_obj->redirect_to( "../admin/home.php?action=managedataset" );
   }
  
    
}

?>