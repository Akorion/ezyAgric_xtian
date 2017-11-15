
<?php
session_start();

//unset($_SESSION['fields']);
if(isset($_POST['dataset_id'])&&$_POST['dataset_id']!="" && isset($_POST['id'])&&$_POST['id']!=""){

$dataset_id=$_POST['dataset_id'];
$index=(int)$_POST['index'];
$key=$_POST['id'];

$token=$_POST['token'];

if (strpos($key, 'bio_data_') !== false){
    $id=(int) str_replace("bio_data_","",$key);
	updateList($token,'bio_data',$index,$id);
	
}
else if(strpos($key, 'acreage_data') !== false){
   
    if($token=="add"){
	$id=1;
	updateList($token,'acreage_data',$index,$id);
	
	}else{
	
	$id=1;
	updateList($token,'acreage_data',$index,$id);
	
	}
	
	

}
else if(strpos($key, 'production_data_') !== false){

    $id=(int) str_replace("production_data_","",$key);
	
	updateList($token,'production_data',$index,$id);

}
else if(strpos($key, 'farmer_location_') !== false){

    $id=(int) str_replace("farmer_location_","",$key);
	updateList($token,'farmer_location',$index,$id);
	

}
else if(strpos($key, 'info_on_other_enterprise_') !== false){

    $id=(int) str_replace("info_on_other_enterprise_","",$key);
	updateList($token,'info_on_other_enterprise',$index,$id);

}
else if(strpos($key, 'general_questions_') !== false){

    $id=(int) str_replace("general_questions_","",$key);
	
	updateList($token,'general_questions',$index,$id);

	

}
//else if(strpos($key, 'biodata_') !== false){
//    $id=(int) str_replace("biodata_","",$key);
//    updateList($token,'biodata_',$index,$id);
//}
else{

}
}
else if(isset($_POST['token'])&&$_POST['token']="removeAll"){
 
  unset($_SESSION['fields']);
}

 
 print_r($_SESSION['fields']['acreage_data']);

 
 
 ////cart functions
 
 function addItem($session,$index,$id){
 $itemArray = array("KEY_".$index=>$id);		
	if(!empty($_SESSION['fields'][$session])) {
		if(in_array($id,$_SESSION['fields'][$session])) {
			foreach($_SESSION['fields'][$session] as $k => $v) {
				if($id == $k)	$_SESSION['fields'][$session][$k] = $id;
			}
		} else $_SESSION['fields'][$session] = array_merge($_SESSION['fields'][$session],$itemArray);
	} else 	$_SESSION['fields'][$session] = $itemArray;
 }
 
 function removeItem($session,$id){
 if (in_array($id,  $_SESSION['fields'][$session]))
     {
     unset($_SESSION['fields'][$session][array_search($id,$_SESSION['fields'][$session])]);
     }
 }
 
 function updateList($token,$session,$index,$id){

     if ($token == "add") {

         addItem($session, $index, $id);

     } else if ($token == "remove") {

         removeItem($session, $id);

     }
     ksort($_SESSION['fields'][$session]);
 }
 
 
 
?>


