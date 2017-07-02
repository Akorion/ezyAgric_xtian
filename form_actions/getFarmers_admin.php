<?php
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/pagination_class.php";
	

$mCrudFunctions = new CrudFunctions();



if(isset($_POST['id'])&&$_POST['id']!=""){

$page =!empty($_POST['page'])? (int)$_POST['page'] :1;
//$per_page =!empty($_POST['per_page'])? (int)$_POST['per_page'] :16;
$per_page= 16;
$id= $_POST['id'];	
$total_count_default=$mCrudFunctions->get_count("dataset_".$id,1);

$total_count =!empty($_POST['total_count'])? (int)$_POST['total_count'] :$total_count_default;
 $pagination_obj= new Pagination($page,$per_page,$total_count); 
 $offset= $pagination_obj->getOffset();
$gender=$_POST['gender'];
if(isset($_POST['district'])&&$_POST['district']!=""){

switch($_POST['district']){
case  "all" :

////////////////////////////////////////////////////////district all starts
if($_POST['sel_production_id']=="all"){
   if($_POST['gender']=="all"){
   output($id," 1 LIMIT $per_page OFFSET $offset ");
   }else{
   //
   echo $gender;
   output($id," lower(biodata_farmer_gender) = '$gender' LIMIT $per_page OFFSET $offset ");
   }
   }else{
  
    
   $string=$_POST['sel_production_id'];
    
	
   if(strpos($string, "productionyes")===false){
   if(strpos($string, "productionno")===false){
   
   if(strpos($string, "generalyes")===false){
   
   if(strpos($string, "generalno")===false){
   }else{
    $p_id=str_replace("generalno","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id'  "  );
    $column =$row[0]['columns'];
    
	if($_POST['gender']=="all"){
    output($id," ".$column." LIKE 'no'  LIMIT $per_page OFFSET $offset");
    }else{
   //
    output($id," lower(biodata_farmer_gender) = '$gender' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
   
    }
	
   }
   
   }else{
    $p_id=str_replace("generalyes","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
	
	if($_POST['gender']=="all"){
    output($id,"  ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
   
    output($id," lower(biodata_farmer_gender) = '$gender' AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
   
    }
  
   }
   
   }else{
	$p_id=str_replace("productionno","",$string);
    $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
   
    if($_POST['gender']=="all"){
    output($id,"  ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }else{
   
    output($id," lower(biodata_farmer_gender) = '$gender' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
   
    }
   
   
   }
   }
   else{
    $p_id=str_replace("productionyes","",$string);
   //echo $string;
   $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
   $column =$row[0]['columns'];
  // echo $column;
  // output($id,"  ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
   //echo $per_page;
   if($_POST['gender']=="all"){
    output($id,"  ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
   
    output($id," lower(biodata_farmer_gender) = '$gender' AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
   
    }
   
   }
   
   
   
   }


/////////////////////////////////////////////////////////////////////district all ends
break;
default: 

if($_POST['subcounty']=="all"){
$district=$_POST['district'];

///////////////////////////////////////////////////////////////////// subcounty all starts
   if($_POST['sel_production_id']=="all"){
   
   
    if($_POST['gender']=="all"){
	//echo $per_page;
	//$snt= filter_var($district, FILTER_SANITIZE_STRING);
    output($id,"  biodata_farmer_location_farmer_district LIKE '%$district%'  LIMIT $per_page OFFSET $offset");
	
    }else{
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND lower(biodata_farmer_gender) = '$gender' LIMIT $per_page OFFSET $offset ");
    }
	
   }else{
  
    $mCrudFunctions = new CrudFunctions();
   $string=$_POST['sel_production_id'];
    
	
   if(strpos($string, "productionyes")===false){
   if(strpos($string, "productionno")===false){
   if(strpos($string, "generalyes")===false){
   if(strpos($string, "generalno")===false){
   }else{
    $p_id=str_replace("generalno","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
	
	if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }else{
	
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND lower(biodata_farmer_gender) = '$gender' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    
    }
	
   }
   
   }else{
    $p_id=str_replace("generalyes","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
  
    if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
	
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
	
    
    }
  
     
  
   }
   
   }else{
	$p_id=str_replace("productionno","",$string);
    $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
   
    if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }else{
	
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND lower(biodata_farmer_gender) = '$gender' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }
  
   
   
   }
   }
   else{
    $p_id=str_replace("productionyes","",$string);
   //echo $string;
   $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   //echo $column;
   
    if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND lower(biodata_farmer_gender) = '$gender' AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
	
    }
   
   
   }
   
   }
//////////////////////////////////////////////////////////////////// subcounty all ends
}else{
  $subcounty=$_POST['subcounty'];
  $district=$_POST['district'];
  if($_POST['parish']=="all"){
  
  ////////////////////////////////////////////////////////////////////// parish all starts
   if($_POST['sel_production_id']=="all"){
   
   if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND lower(biodata_farmer_gender) = '$gender' LIMIT $per_page OFFSET $offset ");
	
	
    }
   
   
   }else{
  
    $mCrudFunctions = new CrudFunctions();
   $string=$_POST['sel_production_id'];
    
	
   if(strpos($string, "productionyes")===false){
   if(strpos($string, "productionno")===false){ 
   if(strpos($string, "generalyes")===false){
   if(strpos($string, "generalno")===false){
   }else{
    $p_id=str_replace("generalno","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
   
    if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }
   
   }
   
   }else{
    $p_id=str_replace("generalyes","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
  
    if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) = '$gender' AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
	
    }
  
   }
   
   }else{
	$p_id=str_replace("productionno","",$string);
    $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
   
    if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset  ");
    }
   
   }
   }
   else{
    $p_id=str_replace("productionyes","",$string);
   //echo $string;
   $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
  // echo $column;
   
   if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND lower(biodata_farmer_gender) = '$gender' AND ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
	
    }
   
   }
  
   }
///////////////////////////////////////////////////////////////////////////////// parish all ends
  }else{
  $subcounty=$_POST['subcounty'];
  $district=$_POST['district'];
  $parish= $_POST['parish'];
  
   if($_POST['village']=="all"){
   
   //////////////////////////////////////////////////////////////////////////// village all starts
   if($_POST['sel_production_id']=="all"){
   
   
   if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND lower(biodata_farmer_gender) = '$gender' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' LIMIT $per_page OFFSET $offset  ");

	
    }
   }else{
  
    $mCrudFunctions = new CrudFunctions();
   $string=$_POST['sel_production_id'];
    
	
   if(strpos($string, "productionyes")===false){
   if(strpos($string, "productionno")===false){  
   if(strpos($string, "generalyes")===false){ 
   if(strpos($string, "generalno")===false){
   }else{
    $p_id=str_replace("generalno","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
   
   
    if($_POST['gender']=="all"){
     output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }else{
	 output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }
   
   }
   }else{
    $p_id=str_replace("generalyes","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
   
    if($_POST['gender']=="all"){
     output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND   ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
	 output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
	
    }
   
   }
   
   }else{
	$p_id=str_replace("productionno","",$string);
    $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
    
	if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND   ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) = '$gender' AND   ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");

	
    }
   
   
   }
   }
   else{
   $p_id=str_replace("productionyes","",$string);
  // echo $string;
   $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   //echo $column;
   
    if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND   ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) = '$gender' AND   ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");

    }
   
   }

   }
   
   /////////////////////////////////////////////////////////////////////////////
   }else{
   $subcounty=$_POST['subcounty'];
   $district=$_POST['district'];
  $parish= $_POST['parish'];
   $village=$_POST['village'];
   
   
 
   if($_POST['sel_production_id']=="all"){
   
   
   if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND biodata_farmer_location_farmer_village LIKE '%$village%' AND lower(biodata_farmer_gender) = '$gender'  LIMIT $per_page OFFSET $offset ");
	
    }
   
   
   }else{
  
    $mCrudFunctions = new CrudFunctions();
   $string=$_POST['sel_production_id'];
    
	
   if(strpos($string, "productionyes")===false){
   if(strpos($string, "productionno")===false){  
   if(strpos($string, "generalyes")===false){ 
   if(strpos($string, "generalno")===false){
   }else{
    $p_id=str_replace("generalno","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
      
   if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
 
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
	
    }
   
   }
   }else{
    $p_id=str_replace("generalyes","",$string);
    $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
   
    if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND   ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
	
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) = '$gender' AND   ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
	
	
    }
   
   }
   
   }else{
	$p_id=str_replace("productionno","",$string);
    $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' "  );
    $column =$row[0]['columns'];
    
   
    if($_POST['gender']=="all"){
    output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND   ".$column." LIKE 'no' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND lower(biodata_farmer_gender) = '$gender' AND   ".$column." LIKE 'no'  LIMIT $per_page OFFSET $offset ");
	
	
	
    }
   
   }
   }
   else{
   $p_id=str_replace("productionyes","",$string);
  // echo $string;
   $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   //echo $column;
   
   
   if($_POST['gender']=="all"){
   output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND   ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
    }else{
	output($id," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND lower(biodata_farmer_gender) = '$gender' AND   ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
	
	
	
	
    }
   
   }

   }
   
   }
   
  }

}

break;
}
 
}


}

 
 
 
 /*function output($id,$where){
$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$dataset_=$util_obj->encrypt_decrypt("encrypt", $id);
$dataset= $mCrudFunctions->fetch_rows("datasets_tb","*"," id =$id ");
$dataset_name=$dataset[0]["dataset_name"];
$dataset_type=$dataset[0]["dataset_type"];

$table="dataset_".$id;		 
$columns="*";

$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if($dataset_type=="Farmer"){
if(sizeof($rows)==0){  
}else{
foreach($rows as $row ){
$real_id=$row['id'];

$real_id=$util_obj->encrypt_decrypt("encrypt", $real_id);
$name=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_farmer_name']));
$gender=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_farmer_gender']));
$dob=$util_obj->remove_apostrophes($row['biodata_farmer_dob']);
$picture=$util_obj->remove_apostrophes($row['biodata_farmer_picture']);
$district=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_farmer_location_farmer_district']));
$phone_number=$util_obj->remove_apostrophes($row['biodata_farmer_phone_number']);

  echo"<div class=\"col-sm-12 col-md-3 col-lg-3\">
  <div class=\"thumbnail card\" id=\"\">
  <img src=\"$picture\" alt=\"Images\" class=\"image\">
  <div class=\"caption\">
  <h6>Name:</h6>
  <p>$name</p><br/>
  <h6>Sex:</h6>
  <p>$gender</p><br/>
  <h6>DOB:</h6>
  <p>$dob</p><br/>
  <h6>District:</h6><p>$district</p><br/>
  <h6>Phone</h6>
  <p>$phone_number</p><br/>
  <p class=\"right\"><a href=\"?action=details&s=$dataset_&token=$real_id&type=$dataset_type\" class=\"btn btn-raised\">More Details &raquo;</a></p>
      </div>
    </div>
  </div>";
  
  }
  
  
  
  }
 
if($dataset_type=="VA"){
if(sizeof($rows)==0){  
}else{
foreach($rows as $row ){
$real_id=$row['id'];

$real_id=$util_obj->encrypt_decrypt("encrypt", $real_id);
$name=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['name']));
$gender=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['gender']));
$dob='';//$row['biodata_farmer_dob'];
$picture=$util_obj->remove_apostrophes($row['picture']);
$district='';//$row['biodata_farmer_location_farmer_district'];
$phone_number=$util_obj->remove_apostrophes($row['va_phonenumber']); 

 echo"<div class=\"col-sm-12 col-md-3 col-lg-3\">
  <div class=\"thumbnail card\" id=\"\">
  <img src=\"$picture\" alt=\"Images\" class=\"image\">
  <div class=\"caption\">
  <h6>Name:</h6>
  <p>$name</p><br/>
  <h6>Sex:</h6>
  <p>$gender</p><br/>
  <h6>DOB:</h6>
  <p>$dob</p><br/>
  <h6>District:</h6><p>$district</p><br/>
  <h6>Phone</h6>
  <p>$phone_number</p><br/>
  <p class=\"right\"><a href=\"?action=details&s=$dataset_&token=$real_id&type=$dataset_type\" class=\"btn btn-raised\">View details &raquo;</a></p>
      </div>
    </div>
  </div>";



}
}
}

}
}*/

 
 function output($id,$where){
$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$dataset_=$util_obj->encrypt_decrypt("encrypt", $id);
$dataset= $mCrudFunctions->fetch_rows("datasets_tb","*"," id =$id ");
$dataset_name=$dataset[0]["dataset_name"];
$dataset_type=$dataset[0]["dataset_type"];

$table="dataset_".$id;		 
$columns="*";

$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if($dataset_type=="Farmer"){
if(sizeof($rows)==0){  
      echo"<p style=\"font-size:1.4em; width:100%; text-align:center; color:#999; margin-top:100px\">No Data</p>";
}else{
foreach($rows as $row ){
$real_id=$row['id'];

$real_id=$util_obj->encrypt_decrypt("encrypt", $real_id);
$name=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_farmer_name']));
$gender=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_farmer_gender']));
$dob=$util_obj->remove_apostrophes($row['biodata_farmer_dob']);
$picture=$util_obj->remove_apostrophes($row['biodata_farmer_picture']);
$district=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_farmer_location_farmer_district']));
$phone_number=$util_obj->remove_apostrophes($row['biodata_farmer_phone_number']);

$uuid=$util_obj->remove_apostrophes($row['meta_instanceID']);

$name = strlen($name) <= 18? $name: substr($name,0,20)."..."; 


  echo"<div class=\"col-sm-12 col-md-4 col-lg-3\">
  
  <div class=\"thumbnail card\" id=\"\">
  <div class=\"image-box\" style=\"background-image:url('$picture'); 
  background-color:#e9e9e9; 
  background-position:center; 
  background-repeat:no-repeat;
  background-size:100%\">
  
   <span class=\"gender\">".substr($gender,0,1)."</span>
   </div>
  <div class=\"caption\" style=\"margin:0\"> 
  <p>$name</p><br/>";
//  <h6>Sex:</h6>
//  <p>$gender</p><br/>
//  <h6>DOB:</h6>
//  <p>$dob</p><br/>
//  <h6>District:</h6><p>$district</p><br/>
//  <h6>Phone</h6>
//  <p>$phone_number</p><br/>";
  
  
  
  $latitudes= array();
  $longitudes= array();
  $acares =array();

  $gardens_table="garden_".$id;
 
if($mCrudFunctions->check_table_exists($gardens_table)>0){
//echo $uuid;

$gardens=$mCrudFunctions->fetch_rows($gardens_table," DISTINCT PARENT_KEY_ "," PARENT_KEY_ LIKE '$uuid%' ");



if(sizeof($gardens)<0){
//echo"<p>Gardens: $total_gardens ,AverageAcerage: $average </p><br/>";

}else{
 $z=1;
 foreach($gardens as $garden){
 $key=$uuid."/gardens[$z]";
 $data_rows=$mCrudFunctions->fetch_rows($gardens_table,"garden_gps_point_Latitude,garden_gps_point_Longitude"," PARENT_KEY_ ='$key'");
 if(sizeof($data_rows)==0){
 $data_rows=$mCrudFunctions->fetch_rows($gardens_table,"garden_gps_point_Latitude,garden_gps_point_Longitude"," PARENT_KEY_ ='$uuid'");
 }
 foreach($data_rows as $row_){

 array_push($longitudes, $row_['garden_gps_point_Longitude']);
 array_push($latitudes, $row_['garden_gps_point_Latitude']);

 }
 $acerage=$util_obj->get_acerage_from_geo($latitudes,$longitudes);
 array_push( $acares, $acerage);
 $z++;
 }
 $total_gardens=sizeof($gardens);
 $average=0;
 if($total_gardens!=0){
 $average=round(array_sum($acares)/$total_gardens,3);
 }

 $_gardens = $total_gardens==1?" Garden":" Gardens";
 echo"<h6>$total_gardens$_gardens</h6>
 <p style=\"color:#888\"> <b>Av.Acerage:</b> $average</p><br/>";
}


} 
  
  
  
  echo"
  <p style=\"margin-top:10px\"><a  href=\"?action=details&s=$dataset_&token=$real_id&type=$dataset_type\" class=\" btn\" style=\"color:#F57C00; margin:0; width:100%;\">View More Details &raquo;</a></p>
      </div>
    </div>
  </div>";
  
  }
  
  
  
  }
 
if($dataset_type=="VA"){
if(sizeof($rows)==0){
    echo"<p style=\"font-size:2em; text-align:center; color:#777; margin-top:100px\">No Data</p>";
}else{
foreach($rows as $row ){
$real_id=$row['id'];

$real_id=$util_obj->encrypt_decrypt("encrypt", $real_id);
$name=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['name']));
$gender=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['gender']));
$dob='';//$row['biodata_farmer_dob'];
$picture=$util_obj->remove_apostrophes($row['picture']);
$district='';//$row['biodata_farmer_location_farmer_district'];
$phone_number=$util_obj->remove_apostrophes($row['va_phonenumber']); 

 echo"<div class=\"col-sm-12 col-md-3 col-lg-3\">
  <div class=\"thumbnail card\" id=\"\">
  <img src=\"$picture\" alt=\"Images\" class=\"image\">
  <div class=\"caption\">
  <h6>Name:</h6>
  <p>$name</p><br/>
  <h6>Sex:</h6>
  <p>$gender</p><br/>
  <h6>DOB:</h6>
  <p>$dob</p><br/>
  <h6>District:</h6><p>$district</p><br/>
  <h6>Phone</h6>
  <p>$phone_number</p><br/>
  <p class=\"right\"><a href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\" class=\"btn btn-raised\">View details &raquo;</a></p>
      </div>
    </div>
  </div>";



}
}
}

}
}
  
  


?>