<?php
ini_set('upload_max_filesize', '60M');
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/server_connection_class.php";

$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$con = new ServerConnection();
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
    $size = $_FILES["csv"]["size"];
    $size = ($size/1024/1024);
//    echo "File Size: ".$size."MB";

     $fileinfo = pathinfo($_FILES["csv"]["name"]);
	 $filetype = $_FILES["csv"]["type"];
	 $extension = strtolower(trim($fileinfo["extension"]));	// dataset file extension

//    //Validate File Type
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
//		    echo "table recording successful";
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
        $flag = $util_obj->createTableFromCSV($db,$handle,$table);

		//echo $flag;
        if ($flag)
        {
//            echo "good, lets proceed ...";
            $array = $mCrudFunctions->insertColumnsIntoTractTables($__id, $handle2);
//                        $util_obj->debug_to_console($array);
            $column_string = $util_obj->getColumnsString($db, $table, 1);
            if($column_string)  print_r($column_string); echo "<br>";
            $file = addslashes($_FILES["csv"]["tmp_name"]);
//            if($size > 2) {
            echo "<br>".$table."<br>";
            mysqli_query($con->getServerConnection(), '
                LOAD DATA LOCAL INFILE "'.$file.'" INTO TABLE '.$table.'
                    FIELDS TERMINATED BY \';\' 
                    LINES TERMINATED BY \'\\r\\n\' STARTING BY \'\'
                    IGNORE 1 LINES
                    ('. implode(', ', $column_string).')'
            )or die(mysqli_error($con->getServerConnection()));
            $string = $mCrudFunctions->get_count($table, "1");
            echo "<br>".$string."<br>";
            if ($string > 0) $util_obj->redirect_to("../admin/home.php?action=createdataset&success=1&client_id=$client_id&inserted=$string");
//            } else {
//                $string = $util_obj->insertCSVintoTable($db, $table, $column_string, $handle);
////            if (is_int($string)){
//////                echo "data inserton, OK".$string;
////                $sql = "ALTER TABLE dataset_".$__id." ADD unique_id VARCHAR(50)";
////                $q_result = $db->setResultForQuery($sql);
////                if($q_result){
//////                    echo "new column added, OK!";
////                    }
////
////                }else{
////                    echo "Ooops, column addition failed !!!!";
////                }
//
////            }else{
////                echo $string;
////            }
//                if ($string > 0) $util_obj->redirect_to("../admin/home.php?action=createdataset&success=1&client_id=$client_id&inserted=$string");
//                else echo "Error: ".$string." !!!";
//            }

		}else{
//            echo "Ooooops, sth went wrong !!!!!!!!!!";
		    $util_obj->redirect_to( "../admin/home.php?action=createdataset&success=0&flag=1" );
		}
		}
		}else{
		    $util_obj->redirect_to( "../admin/home.php?action=createdataset&success=0&flag=4" );
//            echo "OOoopss, table recording failed !!!!!!!!!!";
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