
<?php
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/json_models_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";;
//id: id, district: district,country :country,parish : parish , village : village
/*$_POST['id']=5;
$_POST['district']='all';
$_POST['country']='all';
$_POST['parish']='all';
$_POST['village']='all';
$_POST['production']='all';
$_POST['gender']="female";*/
  
 $mCrudFunctions = new CrudFunctions();
 $json_model_obj = new JSONModel();
 $util_obj= new Utilties();
 
 //header('Cache-Control: no-cache, must-revalidate');
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json');
 
  $data= array();
if(isset($_POST['id'])&&isset($_POST['district'])
  &&isset($_POST['country'])&&isset($_POST['parish'])
  &&isset($_POST['village'])&&isset($_POST['production'])){
  
  
 
 
  $district=$_POST['district'];
  $subcounty=$_POST['country'];
  $parish=$_POST['parish'];
 
///////////////////////////////////////districts
if($_POST['district']=="all"){
$table="dataset_".$_POST['id'];
$rows=array();

if($_POST['production']=="all"){

if($_POST['gender']=="all"){
$rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  1 GROUP BY district ORDER BY frequency Desc "  );
 
}else{
$gender=$_POST['gender'];



$rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  GROUP BY district ORDER BY frequency Desc "  );

}

}else{
$string=$_POST['production'];
if(strpos($string, "productionyes")===false){
if(strpos($string, "productionno")===false){
if(strpos($string, "generalyes")===false){
if(strpos($string, "generalno")===false){
}else{

  $p_id=str_replace("generalno","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  ".$column." LIKE 'no'  GROUP BY district  ORDER BY frequency Desc"  );
  
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","   lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'no'  GROUP BY district  ORDER BY frequency Desc "  );
 
  }
}

}else{

  $p_id=str_replace("generalyes","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  ".$column." LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc "  );
  
 
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc "  );
  
  
  }
}

}else{

  $p_id=str_replace("productionno","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  ".$column." LIKE 'no'  GROUP BY district  ORDER BY frequency Desc "  );
  
  
  
  
  }else{
  $gender=$_POST['gender'];
  
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'no'  GROUP BY district  ORDER BY frequency Desc "  );
  
  
  
  }
}

}else{

  $p_id=str_replace("productionyes","",$string);
  
  //
  $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  ".$column." LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc "  );
  
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc "  );
  
  
  
  }
}


}
  
if(sizeof($rows)==0){ $util_obj->deliver_response(200,0,null );}
else{
$data= $json_model_obj->get_stat_district_json($rows);
$util_obj->deliver_response(200,1,$data );
}


}else


///////////////////////////////////////////subcounties
if($_POST['country']=="all"){

$table="dataset_".$_POST['id'];
$district=$_POST['district'];
$rows=array();
if($_POST['production']=="all"){

if($_POST['gender']=="all"){

$rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ","  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc "  );

  
}else{
 $gender=$_POST['gender'];

 $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc"  );


}
  
  
}else{
$string=$_POST['production'];
if(strpos($string, "productionyes")===false){
if(strpos($string, "productionno")===false){
if(strpos($string, "generalyes")===false){
if(strpos($string, "generalno")===false){

}else{
  $p_id=str_replace("generalno","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ","   lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc "  );
  
  }
  
}
}else{
  $p_id=str_replace("generalyes","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc "  );
  
  
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  
  }
}



}else{
  $p_id=str_replace("productionno","",$string);
  
  //
  $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  
  }
}

}else{
  $p_id=str_replace("productionyes","",$string);
  
  //
  $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
 
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc"  );
  
  
  }

}


}


if(sizeof($rows)==0){$util_obj->deliver_response(200,0,null ); }
else{
$data= $json_model_obj->get_stat_county_json($rows);
$util_obj->deliver_response(200,1,$data );
}

}else

///////////////////////////////////////////parish

if($_POST['parish']=="all"){

$table="dataset_".$_POST['id'];
$subcounty=$_POST['country'];
$rows=array();
if($_POST['production']=="all"){
if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
  COUNT(biodata_farmer_location_farmer_parish)  as frequency "," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
 
}else{
$gender=$_POST['gender'];

$rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
  COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );

}
}else{
   $string=$_POST['production'];
   if(strpos($string, "productionyes")===false){
   if(strpos($string, "productionno")===false){
   if(strpos($string, "generalyes")===false){
   if(strpos($string, "generalno")===false){
   }else{
   $p_id=str_replace("generalno","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   
   
   }
   }
   
   
   }else{
   $p_id=str_replace("generalyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );

   
   
   }else{
   $gender=$_POST['gender'];
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );

  
   
   }
   
   }
   }else{
   $p_id=str_replace("productionno","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc "  );
   
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   
   
   }
   
   }
   }else{
   $p_id=str_replace("productionyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc "  );
   
  
   
   }else{
   $gender=$_POST['gender'];
  
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   }
   
   
   }
}

if(sizeof($rows)==0){ $util_obj->deliver_response(200,1,null );}
else{
$data= $json_model_obj->get_stat_parish_json($rows);
$util_obj->deliver_response(200,1,$data );
}
  


}else

///////////////////////////////////////////village

if($_POST['village']=="all"){

$table="dataset_".$_POST['id'];
$parish=$_POST['parish'];
$rows=array();
if($_POST['production']=="all"){
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency "," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  GROUP BY village ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
   
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc "  );
  
  }
}else{
$string=$_POST['production'];
   if(strpos($string, "productionyes")===false){
   if(strpos($string, "productionno")===false){
   if(strpos($string, "generalyes")===false){
   if(strpos($string, "generalno")===false){
   }else{
   $p_id=str_replace("generalno","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
  
   
   }
   
   }
   }else{
   $p_id=str_replace("generalyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   }else{
   $gender=$_POST['gender'];
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   }
   
   }
   
   }else{
   $p_id=str_replace("productionno","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   }
   
   }
   
   }else{
   $p_id=str_replace("productionyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   }
   
   
   }
}

 if(sizeof($rows)==0){ $util_obj->deliver_response(200,1,null );}
else{
$data= $json_model_obj->get_stat_village_json($rows);
$util_obj->deliver_response(200,1,$data );
} 




}else{

//echo "off";
$table="dataset_".$_POST['id'];
$parish=$_POST['parish'];
$village=$_POST['village'];
$rows=array();
if($_POST['production']=="all"){
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency "," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
 
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc "  );
  
  
  }
}else{
   $string=$_POST['production'];
   if(strpos($string, "productionyes")===false){
   if(strpos($string, "productionno")===false){
   if(strpos($string, "generalyes")===false){
   if(strpos($string, "generalno")===false){
   }else{
   $p_id=str_replace("generalno","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc "  );
   
   }else{
   $gender=$_POST['gender'];
    
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc "  );
 
   }
   
   }
   }else{
   $p_id=str_replace("generalyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   }else{
   $gender=$_POST['gender']; 
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc "  );
   
   
   }
   
   }
   }else{
   $p_id=str_replace("productionno","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc "  );
   
   
   }else{
   $gender=$_POST['gender'];
    
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY  village ORDER BY frequency Desc "  );
   
   
   }
   
   }
   }else{
   $p_id=str_replace("productionyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc "  );
   
 
   
   }else{
   $gender=$_POST['gender'];
    
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   }
   
   }
}

if(sizeof($rows)==0){ 
$util_obj->deliver_response(200,0,null );
}
else{
$data= $json_model_obj->get_stat_village_json($rows);
$util_obj->deliver_response(200,1,$data );
} 


}

}else{
$util_obj->deliver_response(200,0,null );
}


?>

