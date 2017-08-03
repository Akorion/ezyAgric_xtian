
<?php
ini_set('upload_max_filesize', '60M');
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$util_obj= new Utilties();
 //  $_POST['id']=52;
if(isset($_POST["id"]) &&$_POST["id"]!=''){


//echo $size;
if ($_FILES["csv"]["size"] > 0) {
    
     $fileinfo = pathinfo($_FILES["csv"]["name"]);
	 $filetype = $_FILES["csv"]["type"];
   
    //Validate File Type
	if(strtolower(trim($fileinfo["extension"])) != "csv")
	{
         $util_obj->redirect_to( "../admin/home.php??action=managedataset&success=0&flag=-1" );
	}
	else
	{   
	    $id=$_POST["id"];
		
		$file_path = $_FILES["csv"]["tmp_name"];
        $handle = fopen($file_path,"r"); 
		$handle2 = fopen($file_path,"r"); 
		$table="garden_".$id;
		
		$array= array();
        $flag = $util_obj->createTableFromCSV($db,$handle,$table);
		
//		echo $flag;
       if ($flag) {   
	         $array=$mCrudFunctions->insertColumnsIntoTractTables($id,$handle2);
//			 print_r( $array);
	         $column_string= $util_obj->getColumnsString($db,$table,1);
	         $string=$util_obj->insertCSVintoTable($db,$table,$column_string,$handle);
			 
			$util_obj->redirect_to( "../admin/home.php?action=managedataset&success=1" );
	    }else{
		 $util_obj->redirect_to( "../admin/home.php?action=managedataset&success=0&flag=1" );
		}
    }     
   }else{
   $size = $_FILES["csv"]["size"];
   //print_r($_FILES);
   $util_obj->redirect_to( "../admin/home.php?action=managedataset&success=0&flag=3&size=$size" );   
   
   }
   

} else{
    $util_obj->redirect_to( "../admin/home.php?action=managedataset&success=0&flag=0" );   
}



?>