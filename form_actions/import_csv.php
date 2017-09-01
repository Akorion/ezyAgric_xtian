<?php
ini_set('upload_max_filesize', '60M');
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$util_obj= new Utilties();
   
if(isset($_POST["submit"])&&
  isset($_POST["dataset_nm"]) &&
  $_POST["dataset_nm"]!=''&& 
  isset($_POST["dataset_type"]) &&
  $_POST["dataset_type"]!=''&& 
  isset($_POST["start"]) &&
  $_POST["start"]!=''&& 
  isset($_POST["end"]) &&
  $_POST["end"]!=''&& 
  isset($_POST["client_id"]) &&
  $_POST["client_id"]!=''&&   
  isset($_POST["region"]) &&
  $_POST["region"]!=''){


//echo $size;
if ($_FILES["csv"]["size"] > 0) {
    
     $fileinfo = pathinfo($_FILES["csv"]["name"]);
	 $filetype = $_FILES["csv"]["type"];
	 $extension = strtolower(trim($fileinfo["extension"]));	// dataset file extension
   
    //Validate File Type
	if(strtolower(trim($fileinfo["extension"])) != "csv")
	{
         $util_obj->redirect_to( "../admin/home.php?action=createdataset&success=0&flag=-1" );
	}
	else
	{   
	    $name=$_POST["dataset_nm"];
		$client_id=$_POST['client_id'];
		$type=$_POST["dataset_type"];
	    $from=$_POST["start"];
        $to=$_POST["end"];
	    $region=$_POST["region"];
		
	    $flag_0=$mCrudFunctions->insert_into_datasets_tb($name,$client_id,$type,$region,$util_obj->mysql_prep($from),$util_obj->mysql_prep($to),175);
		if($flag_0){
		$rows= $mCrudFunctions->fetch_rows("datasets_tb","*"," 1 ORDER BY id DESC LIMIT 1");
        if(sizeof($rows)==0){ 
	   		//echo "Error ......";
	    }else{
		
		$file_path = $_FILES["csv"]["tmp_name"];
        $handle = fopen($file_path,"r"); 
		$handle2 = fopen($file_path,"r"); 
		$__id=$rows[0]['id'];
		$table="dataset_".$__id;
		$array= array();
        $flag =$util_obj->createTableFromCSV($db,$handle,$table);
		
		//echo $flag;
        if ($flag)
        {
            $array = $mCrudFunctions->insertColumnsIntoTractTables($__id, $handle2);
//                        $util_obj->debug_to_console($array);
            $column_string = $util_obj->getColumnsString($db, $table, 1);
//                        $util_obj->debug_to_console($column_string);
            $string = $util_obj->insertCSVintoTable($db, $table, $column_string, $handle);
//                        $util_obj->debug_to_console($string);
            if ($string > 0) $util_obj->redirect_to("../admin/home.php?action=createdataset&success=1&client_id=$client_id&inserted=$string");
		}else{
		    $util_obj->redirect_to( "../admin/home.php?action=createdataset&success=0&flag=1" );
		}
		}
		}else{
		    $util_obj->redirect_to( "../admin/home.php?action=createdataset&success=0&flag=4" );
		}
    }     
   }else{
   $size = $_FILES["csv"]["size"];
   //print_r($_FILES);
   $util_obj->redirect_to( "../admin/home.php?action=createdataset&success=0&flag=3&size=$size" );   
   
   }
   

} else{
    $util_obj->redirect_to( "../admin/home.php?action=createdataset&success=0&flag=0" );   
}



?>