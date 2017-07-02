
<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";
 //$util_obj= new Utilties();

 
 switch($_POST["token"]){
 
 case  :
 if(isset($_POST["dataset_id"]) && 
  $_POST["dataset_id"]!=''&&
  isset($_POST["farmer_id"]) &&
  $_POST["farmer_id"]!=''&& 
  isset($_POST["fertilizer_selected"]) &&
  $_POST["fertilizer_selected"]!=''&& 
   isset($_POST["seed_selected"]) &&
  $_POST["seed_selected"]!=''&& 
  isset($_POST["herbicide_selected"]) &&
  $_POST["herbicide_selected"]!=''
  ){
  
  $dataset_id=$_POST["dataset_id"];
  $farmer_id=$_POST["farmer_id"]; 
  $fertilizer_selected =$_POST["fertilizer_selected"];
  $seed_selected=$_POST["seed_selected"];
  $herbicide_selected= $_POST["herbicide_selected"];
  $tractor_money=$_POST["tractor_money"];
  $cash_taken=$_POST["cash_taken"];
  
  
  }
 break;
 			
 
 }
 
if(isset($_POST["dataset_id"]) && 
  $_POST["dataset_id"]!=''
)
{
    $mCrudFunctions = new CrudFunctions();
	
    $dataset_id=$_POST["dataset_id"];
	$array=array("test1","test2","test3");
	//print_r($array);
	$x=$mCrudFunctions->create_table_tb("seedss",$dataset_id,array("test1","test2","test3"));
	
	echo "HERE".$dataset_id." ".$x;
   
}else{

    //redirect back to original page
	echo "HERE";
	//$util_obj->redirect_to( "../admin/home.php?action=account&success=0&input=incomplete" );
    
}

?>