<?php
require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";

$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$util_obj= new Utilties();

if(isset($_POST["Submit"])){

$size = $_FILES["csv"]["size"];
echo $size;
if ($_FILES["csv"]["size"] > 0) {
    
    
     $fileinfo = pathinfo($_FILES["csv"]["name"]);
	 $filetype = $_FILES["csv"]["type"];
    
    echo  "level1"; 
    //Validate File Type
	if(strtolower(trim($fileinfo["extension"])) != "csv")
	{
         echo"bad file";
	}
	else
	{
		$file_path = $_FILES["csv"]["tmp_name"];
		ini_set('auto_detect_line_endings',TRUE);
        $handle = fopen($file_path,"r"); 
		$handle2 = fopen($file_path,"r"); 
		$table="dataset_11";
		$array= array();
        $flag =$util_obj->createTableFromCSV($db,$handle,$table);
        if ($flag) {   
	         $array=$mCrudFunctions->insertColumnsIntoTractTables(11,$handle2);
	         $column_string= $util_obj->getColumnsString($db,$table,1);
	         $util_obj->insertCSVintoTable($db,$table,$column_string,$handle); 
	    }
	   echo  "level2". print_r($array); 
    }
	   
       
   }else{
   echo  "level3"; 
   }
   

} else{
     echo "ghdafshg";    
}



?>