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
//            echo "good, lets proceed ...";
            $array = $mCrudFunctions->insertColumnsIntoTractTables($__id, $handle2);
//                        $util_obj->debug_to_console($array);
            $column_string = $util_obj->getColumnsString($db, $table, 1);
//                        $util_obj->debug_to_console($column_string);
            $string = $util_obj->insertCSVintoTable($db, $table, $column_string, $handle);
////                        $util_obj->debug_to_console($string);
//            if (is_int($string)){
////                echo "data inserton, OK".$string;
//                $sql = "ALTER TABLE dataset_".$__id." ADD unique_id VARCHAR(50)";
//                $q_result = $db->setResultForQuery($sql);
//                if($q_result){
////                    echo "new column added, OK!";
//                    $table = "dataset_" . $__id;
//                    $f_id_rows = $mCrudFunctions->fetch_rows($table, "*", "1");
//                    $f_id = array();    $counter =1;
////                    $query = "UPDATE ". $table . "SET";
//                    $id_arr = array();  $f_id_arr = array(); $farmer_ids = array();
//                    $dataSize = count($f_id_rows);
//                    echo $dataSize."<br>";
//                    foreach ($f_id_rows as $id_rows) {
//                        $id = $id_rows['id'];
//                        $name = $id_rows['biodata_farmer_name'];
//                        $dob = $id_rows['biodata_farmer_dob'];
//                        $district = $id_rows['biodata_farmer_location_farmer_district'];
//                        $village = $id_rows['biodata_farmer_location_farmer_village'];
//
//                        $name_arr = explode(" ", $name);
//                        $name_arr[0] = substr($name_arr[0], 0, 3);
//                        $name_arr[0] = strtoupper($name_arr[0]);
//                        $name_arr[1] = substr($name_arr[1], 0, 3);
//                        $name_arr[1] = strtoupper($name_arr[1]);
//                        $name = implode("", $name_arr);
//
//                        $dob = str_replace("/", "", $dob);
//                        $dob = str_replace("-", "", $dob);
//                        $district = substr($district, 0, 3);
//                        $district = strtoupper($district);
//                        $village = substr($village, 0, 3);
//                        $village = strtoupper($village);
//
//                        $farmer_id = $name."" .$dob.":" .$district.":" .$village;
////                        $query .= " unique_id = ". $farmer_id;
//
////                        while($counter != $dataSize){
////                            array_push($f_id_arr[0], $id);
////                            array_push($f_id_arr[1], $farmer_id);
////                        }
//
////                        $f_id_arr = array($id, $farmer_id);
//                        $id_arr[] = "unique_id = '".$farmer_id."'";
//                        $f_value = 'unique_id = '.$farmer_id;
//                        if($dataSize == $counter){
//                            array_push($f_id, $f_id_arr);      // contains both id & generated id
//                            array_push($farmer_ids, $id_arr);
//                        }
//
//                        $counter++;
//                    }
//                    print_r($f_id);
//                    $ids = array();
//                    foreach ($f_id as $f_ids){
//                        foreach ($f_ids as $id_key => $id_value){
//                            $f_ids[$id_key] = mysqli_real_escape_string($con->getServerConnection(), $f_ids[$id_key]);
//                        }
//                        $ids[] = implode(",",$f_ids);
//                    }
//
//                    $fids = array();
//                    foreach ($farmer_ids as $f_ids){
//                        foreach ($f_ids as $id_key => $id_value){
//                            $f_ids[$id_key] = mysqli_real_escape_string($con->getServerConnection(), $f_ids[$id_key]);
//                        }
//                        $fids[] = implode(",",$f_ids);
//                    }
//                    echo "<br><br>";
////                    print_r($ids);
//////                    $ids_values = implode(",", $ids);
//////                    print_r($ids_values);
////                    $query = "UPDATE ". $table . " SET ". implode(',',$fids);
////                    print_r($query);
//                    $query = "INSERT INTO ". $table. "(id, unique_id) VALUES ".implode(',', $ids)." ON DUPLICATE KEY UPDATE ". implode(',', $fids);
////                    print_r($query);
////                /**    INSERT INTO table (id,Col1,Col2) VALUES (1,1,1),(2,2,3),(3,9,3),(4,10,12)
////                    ON DUPLICATE KEY UPDATE Col1=VALUES(Col1),Col2=VALUES(Col2);     **/
//
////                    $result = $db->setResultForQuery($query);
//
////////
////////                    $result = $util_obj->insert_into($db, "dataset_" . $__id, "unique_id", $f_id);
////                    if($result) $util_obj->redirect_to("../admin/home.php?action=createdataset&success=1&client_id=$client_id&inserted=$string");
////                    else    echo "creation of farmer id failed due to error ,,,, !";
//                }else{
//                    echo "Ooops, column addition failed !!!!";
//                }
//
//            }else{
//                echo $string;
//            }
            if ($string > 0) $util_obj->redirect_to("../admin/home.php?action=createdataset&success=1&client_id=$client_id&inserted=$string");
		}else{
//            echo "Ooooops, sth went wrong !!!!!!!!!!";
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