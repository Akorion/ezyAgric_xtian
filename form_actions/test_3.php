
<?php
session_start();
#includes
require_once $_SERVER['DOCUMENT_ROOT']."/ezyagric_v2/php_lib/user_functions/crud_functions_class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/ezyagric_v2/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();

$birth_date="1990-01-28 00:00:000";


echo $util_obj->getAge( $birth_date,"Africa/Nairobi");//round($util_obj->daysBetween($birth_date,$current_date)/365,0);


 //$util_obj= new Utilties();
/* $_POST["dataset_id"]=221;
if(isset($_POST["dataset_id"]) && 
  $_POST["dataset_id"]!=''
)
{
   
    $mCrudFunctions = new CrudFunctions();
	//$password=sha1($_POST["password"]);
	
    $dataset_id=$_POST["dataset_id"];
	$array=array("test1","test2","test3");
	//print_r($array);
	$x=$mCrudFunctions->create_table_tb("seedsss",$dataset_id,array("test1","test2","test3"));
	
	echo "HERE".$dataset_id." ".$x;
   
}else{

    //redirect back to original page
	echo "HERE";
	//$util_obj->redirect_to( "../admin/home.php?action=account&success=0&input=incomplete" );
    
}
*/
?>