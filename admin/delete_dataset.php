<?php
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$util_obj= new Utilties();

if(isset($_POST["id"]) &&$_POST["id"]!=''){
$id=$_POST["id"];

$mCrudFunctions->delete("datasets_tb"," id='$id'");
$mCrudFunctions->delete("farmer_location","dataset_id='$id'");
$mCrudFunctions->delete("bio_data","dataset_id='$id'");
$mCrudFunctions->delete("production_data","dataset_id='$id'");
$mCrudFunctions->delete("interview_particulars","dataset_id='$id'");
$mCrudFunctions->delete("info_on_other_enterprise","dataset_id='$id'");
$mCrudFunctions->delete("general_questions","dataset_id='$id'");

	if($mCrudFunctions->check_table_exists("garden_".$id)<1){
		$table_name="garden_".$id;
		$db->setResultForQuery("DROP TABLE $table_name ");
	}
$util_obj->redirect_to( "../admin/home.php?action=managedataset&success=1" );
}else{
	$util_obj->redirect_to( "../admin/home.php?action=managedataset&success=0&flag=0" );  
}
?>