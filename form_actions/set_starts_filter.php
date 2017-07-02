<?php
session_start();
 #includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();

/////////////////////////get farmer datasets
if(isset($_POST['datasets'])&&isset($_SESSION["client_id"])){
     $client_id=$_SESSION["client_id"];
     $table="client_datasets_v";
	 $row0s= $mCrudFunctions->fetch_rows($table,"*"," client_id='$client_id' AND dataset_type='Farmer' ");
	 foreach($row0s as $row){
	 $Id=$row['id'];
	 $label=$util_obj->captalizeEachWord($row['dataset_name']);
	 $lable=str_replace(".","",$label);
	 echo "<option value=\"$Id\">$lable</option>";
	 }
}
if(isset($_POST['va'])&& isset($_POST['id'])){

     $table="dataset_".$_POST['id'];
	 $va=$_POST['va'];
	 $row0s= $mCrudFunctions->fetch_rows($table," lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) AS interview_particulars_va_code  ,interview_particulars_va_name ","  1 GROUP BY interview_particulars_va_code ORDER BY interview_particulars_va_name ASC ");
	$f=sizeof($row0s);
	echo "<option value=\"all\">all</option>";
	 //print_r($district." ".$table." ".$_POST['id']);
	 $temp_array= array();
	 foreach($row0s as $row){
	 $value=$util_obj->captalizeEachWord($row['interview_particulars_va_name']);
	 $value=str_replace(".","",$value)."(".strtoupper($row['interview_particulars_va_code']).")";
	// echo "<option value=\"$value\">$value</option>";
	 if (in_array($value, $temp_array)) {
     }else{
	 array_push($temp_array, $value);
	 }  
	 
	 }
	 
	 
	  $serialized = array_map('serialize', $temp_array);
      $unique = array_unique($serialized);
      $temp_array =array_intersect_key($temp_array, $unique);
	
	 
	  foreach($temp_array as $temp){
	  echo "<option value=\"$temp\">$temp</option>";
	 }

}

///////////////////////////get districts
if(isset($_POST['district'])&&isset($_SESSION["client_id"])){

     $table="dataset_".$_POST['district'];
	 $row0s= $mCrudFunctions->fetch_rows($table," DISTINCT TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  as biodata_farmer_location_farmer_district"," 1 ORDER BY biodata_farmer_location_farmer_district ASC ");
	 echo "<option value=\"all\">all</option>";
	 $temp_array= array();
	 foreach($row0s as $row){
	 $value=$util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_district']);
	 $value=str_replace(".","",$value);
	 if (in_array($value, $temp_array)) {
     }else{
	 array_push($temp_array, $value);
	 }  
	 
	 }
	 foreach($temp_array as $temp){
	  echo "<option value=\"$temp\">$temp</option>";
	 }

}
/////////////////////////////get subcountry
if(isset($_POST['district_subcounty'])&& isset($_POST['id'])){

     $table="dataset_".$_POST['id'];
	 $district=$_POST['district_subcounty'];
	 $row0s= $mCrudFunctions->fetch_rows($table," DISTINCT  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as biodata_farmer_location_farmer_subcounty ","  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  LIKE '$district' ORDER BY biodata_farmer_location_farmer_subcounty ASC ");
	 echo "<option value=\"all\">all</option>";
	 $temp_array= array();
	 foreach($row0s as $row){
	 $value=$util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_subcounty']);
	 $value=str_replace(".","",$value);
	 if (in_array($value, $temp_array)) {
     }else{
	 array_push($temp_array, $value);
	 }  
	 
	 }
	 foreach($temp_array as $temp){
	  echo "<option value=\"$temp\">$temp</option>";
	 }
}

/////////////////////get production data filter
if(isset($_POST['prodution_data_id'])){
     $id=$_POST['prodution_data_id'];
	 $table="dataset_".$id;
     
	
	 $production_data=$mCrudFunctions->fetch_rows("production_data","*"," dataset_id='$id' ");
	 echo "<option value=\"all\">all</option>";
	 foreach($production_data as $data){
	 $data_id=$data['id'];
	 $column=$data['columns'];
	 $enterprise=$data['enterprise'];
	 $string=$enterprise."_production_data_";
     $string=str_replace($string,"",$column);
     $string=str_replace("_"," ",$string);
	
     $value=$util_obj->captalizeEachWord($string);
 
	 
	 $rows= $mCrudFunctions->fetch_rows($table,"*"," ".$column." LIKE 'no' OR ".$column." LIKE 'Yes'");
	 if(sizeof($rows)>0){
	  $no="no";
	  $yes="yes";
	  $key="production";
	  echo "<option value=\"$key$yes$data_id\">$value (YES)</option>";
	  echo "<option value=\"$key$no$data_id\">$value (NO)</option>";
	 }
	  
	 }
	 
	 $general_data=$mCrudFunctions->fetch_rows("general_questions","*"," dataset_id='$id' ");

	 foreach($general_data as $data){
	 $data_id=$data['id'];
	 $column=$data['columns'];
	 $string="general_questions_";
     $string=str_replace($string,"",$column);
     $string=str_replace("_"," ",$string);
     $value=$util_obj->captalizeEachWord($string);
	 $rows= $mCrudFunctions->fetch_rows($table,"*"," ".$column." LIKE 'no' OR ".$column." LIKE 'Yes'");
	 if(sizeof($rows)>0){
	  $no="no";
	  $yes="yes";
	  $key="general";
	  echo "<option value=\"$key$yes$data_id\">$value (YES)</option>";
	  echo "<option value=\"$key$no$data_id\">$value (NO)</option>";
	 }
	  
	 }
	

}


/*
if(isset($_POST['district'])&& isset($_POST['id'])){

     $table="dataset_".$_POST['id'];
	 $district=$_POST['district'];
	 $row0s= $mCrudFunctions->fetch_rows($table," DISTINCT biodata_farmer_location_farmer_subcounty "," biodata_farmer_location_farmer_district LIKE '%$district%'");
	 echo "<option value=\"all\">all</option>";
	 $temp_array= array();
	 foreach($row0s as $row){
	 $value=$util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_subcounty']);
	 $value=str_replace(".","",$value);
	  if (in_array($value, $temp_array)) {
     }else{
	 array_push($temp_array, $value);
	 }  
	 
	 }
	 foreach($temp_array as $temp){
	  echo "<option value=\"$temp\">$temp</option>";
	 }

}
*/
//get parishes for county
if(isset($_POST['parish_district'])&&isset($_POST['parish_subcounty'])&& isset($_POST['id'])){

     $table="dataset_".$_POST['id'];
	 $parish_district=$_POST['parish_district'];
	 $county=$_POST['parish_subcounty'];
	 
	 $row0s= $mCrudFunctions->fetch_rows($table," DISTINCT TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as biodata_farmer_location_farmer_parish  "," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  LIKE '$parish_district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  LIKE '$county' ORDER BY biodata_farmer_location_farmer_parish ASC");
	 echo "<option value=\"all\">all</option>";
	 $temp_array= array();
	 foreach($row0s as $row){
	 $value=$util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_parish']);
	 $value=str_replace(".","",$value);
	  if (in_array($value, $temp_array)) {
     }else{
	 array_push($temp_array, $value);
	 }  
	 
	 }
	 foreach($temp_array as $temp){
	  echo "<option value=\"$temp\">$temp</option>";
	 }

}
//get villages from parishes 
if(isset($_POST['village_district'])&&isset($_POST['village_subcounty'])&&isset($_POST['village_parish'])&& isset($_POST['id'])){

     $table="dataset_".$_POST['id'];
	 $village_district=$_POST['village_district'];
	 $village_subcounty=$_POST['village_subcounty'];
	 $parish=$_POST['village_parish'];
	 $row0s= $mCrudFunctions->fetch_rows($table," DISTINCT TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as biodata_farmer_location_farmer_village "," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  LIKE '$village_district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  LIKE '$village_subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish)  LIKE '$parish' ORDER BY biodata_farmer_location_farmer_village ASC ");
	 echo "<option value=\"all\">all</option>";
	 $temp_array= array();
	 foreach($row0s as $row){
	 $value=$util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_village']);
	 $value=str_replace(".","",$value);
	  if (in_array($value, $temp_array)) {
     }else{
	 array_push($temp_array, $value);
	 }  
	 
	 }
	 foreach($temp_array as $temp){
	  echo "<option value=\"$temp\">$temp</option>";
	 }

}		



//Biyende.

?>