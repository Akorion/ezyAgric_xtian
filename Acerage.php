<?php
require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";

// The JSON standard MIME header.
header('Content-type: application/json');

$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$util_obj= new Utilties();

//$_GET['id']=5;
//$_GET['farmer_id']=2;   
   
$id=$_GET['id'];
$farmer_id=$_GET['farmer_id'];
   
$dataset_table="dataset_".$id;		 
$columns="*";
$where=" id='$farmer_id' ";
$rows= $mCrudFunctions->fetch_rows($dataset_table,$columns,$where);

$uuid=$util_obj->remove_apostrophes($rows[0]['meta_instanceID']);
$table="garden_".$id;
 $mCrudFunctions->check_table_exists($table);
if($mCrudFunctions->check_table_exists($table)>0){


$gardens=$mCrudFunctions->fetch_rows($table," DISTINCT parent_key_ "," parent_key_ LIKE '$uuid%'");

if(sizeof($gardens)>0){
$k=1;
$output['data']= array();

foreach($gardens as $garden){
$key=$uuid."/gardens[$k]";
$data_rows=$mCrudFunctions->fetch_rows($table,"garden_gps_point_Latitude,garden_gps_point_Longitude"," parent_key_ ='$key'");

if(sizeof($data_rows)==0){
 $data_rows=$mCrudFunctions->fetch_rows($table,"garden_gps_point_Latitude,garden_gps_point_Longitude"," PARENT_KEY_ ='$uuid'");
 }
 
$latitudes= array();
$longitudes= array();
$garden_output= array();
foreach($data_rows as $rows){
$geo_pairs = array();
$lat='lat';
$lng='lng';
$geo_pairs[$lat]=(double)$rows['garden_gps_point_Latitude'];
$geo_pairs[$lng]=(double) $rows['garden_gps_point_Longitude'];
array_push($garden_output, $geo_pairs);

array_push($longitudes, $rows['garden_gps_point_Longitude']);
array_push($latitudes, $rows['garden_gps_point_Latitude']);

}
$geo_pairs_ = array();
$geo_pairs_['lat']=(double)$latitudes[0];
$geo_pairs_['lng']=(double)$longitudes[0];
array_push($garden_output, $geo_pairs_);

array_push($output['data'], $garden_output);
$k++;
}

echo json_encode($output);

}



//print_r($output);

}



//echo  $util_obj->get_acerage_from_geo($latitudes,$longitudes);

 










?>