
<?php
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/json_models_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";
//id: id, district: district,country :country,parish : parish , village : village
/*$_POST['id']=5;
$_POST['district']='all';
$_POST['country']='all';
$_POST['parish']='all';
$_POST['village']='all';
$_POST['production']='all';
$_POST['gender']="all";*/
  
 $mCrudFunctions = new CrudFunctions();
 $json_model_obj = new JSONModel();
 $util_obj= new Utilties();
 
 
 $va=$_POST['va'];;
$va=str_replace("(","-",$va);
$va=str_replace(")","",$va);
//$string="hyderabad-india";
$strings=explode('-',$va);
//echo $strings[0]; // outputs: hyderabad
//echo "<br>"; // new line
$va= strtolower($strings[1]); // 
 
 $age_min=!empty($_POST['age_min'])? (int)$_POST['age_min'] :0;
  $age=!empty($_POST['age'])? (int)$_POST['age'] :0;
  $age_max=!empty($_POST['age_max'])? (int)$_POST['age_max'] :0;
  $ageFilter="";;
//floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)
if($age!="" && $age!=0){
 
  $ageFilter= " floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  AND ";
 }
 
 if($age_min<=$age_max && $age_max!=0){
  $ageFilter= "  (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  AND ";
 }
 
 if($age_min>0 &&$age_max==0 ){
  $ageFilter= " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  AND ";
 }
  //header('Content-type: application/json');
 $sql=" AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'";
  //
  
  $Yes=0;
$No=0;

 $male=0;
$female=0;
 $column="";
  $data= array();
if(isset($_POST['id'])&&isset($_POST['district'])
  &&isset($_POST['country'])&&isset($_POST['parish'])
  &&isset($_POST['village'])&&isset($_POST['production'])){
  
  
  $district=$_POST['district'];
  $subcounty=$_POST['country'];
  $parish=$_POST['parish'];
 $access_id=$_POST['id'];
 
 $enterprise= $mCrudFunctions->fetch_rows("production_data","enterprise"," dataset_id='$access_id' ")[0]['enterprise'];
 
///////////////////////////////////////districts
if($_POST['district']=="all"){
$table="dataset_".$_POST['id'];
$rows=array();


if($_POST['production']=="all"){



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
  
  if($_POST['va']=="all"){
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes'  "  );
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' "  );
  
  }else{
  
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes'  ".$sql  );
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' ".$sql  );
  }//
  
  
  
  
  
  }else{
  $gender=$_POST['gender'];
  
   if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND lower(biodata_farmer_gender) ='$gender' "  );
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND lower(biodata_farmer_gender) ='$gender' "  );

  
  }else{
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND lower(biodata_farmer_gender) ='$gender' ".$sql  );
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND lower(biodata_farmer_gender) ='$gender' ".$sql  );

  }
  
   
 }
}

}else{

  $p_id=str_replace("generalyes","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  
  if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' "  ); 
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' "  );

  
  }else{
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' ".$sql  ); 
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' ".$sql  );

  }
   
    
 
  
  }else{
  $gender=$_POST['gender'];
 
    
   if($_POST['va']=="all"){
  
   $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  AND lower(biodata_farmer_gender) ='$gender' "  );
   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes'  AND lower(biodata_farmer_gender) ='$gender' "  );

   }else{
   $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  AND lower(biodata_farmer_gender) ='$gender' ".$sql  );
   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes'  AND lower(biodata_farmer_gender) ='$gender' ".$sql  );
   }
  
  
  }
}

}else{

  $p_id=str_replace("productionno","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  
  if($_POST['gender']=="all"){
  
  
  
  if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  "  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes'  "  );
  }else{
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  ".$sql  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes'  ".$sql  );
  }//.$sql
   
  
  }else{
  $gender=$_POST['gender'];
    
	
	if($_POST['va']=="all"){
  
    $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  AND lower(biodata_farmer_gender) = '$gender' "  ); 
    $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes'  AND lower(biodata_farmer_gender) = '$gender' "  );

  
  }else{
     $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  AND lower(biodata_farmer_gender) = '$gender' ".$sql  ); 
     $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes'  AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

  }//.$sql
  
   
  
  }
}

}else{

  $p_id=str_replace("productionyes","",$string);
  
  //
  $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  
  
  
  
   if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  "  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' "  );
  
  }else{
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  ".$sql  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' ".$sql  );
  
  }//.$sql
  
  }else{
  $gender=$_POST['gender'];
    
  
   if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  AND lower(biodata_farmer_gender) = '$gender' "  );
  
  $yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND lower(biodata_farmer_gender) = '$gender' "  );

  
  }else{
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no'  AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
  
  $yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

  
  }//.$sql
  
  }
}


}

//echo $yes;
draw_pie_chart($No,$Yes,$column,$enterprise,$json_model_obj,$util_obj);



}else


///////////////////////////////////////////subcounties
if($_POST['country']=="all"){

$table="dataset_".$_POST['id'];
$district=$_POST['district'];
$rows=array();
if($_POST['production']=="all"){
 
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
  
  
   if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  "  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  "  );
  
  
  }else{
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  ".$sql  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  ".$sql  );
  
  }//.$sql
  
  }else{
  $gender=$_POST['gender'];
  
   if($_POST['va']=="all"){
   $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' "  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' "  );

   
   }else{
   
   $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

   
   }
  
  
    

  
  }
  
}
}else{
  $p_id=str_replace("generalyes","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
  
  
   if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  "  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  "  );
 
  }else{
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' ".$sql  );
  
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  ".$sql  );
 
  }
  
  
  }else{
  $gender=$_POST['gender'];
  
  if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' "  );
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' "  );
  
  }else{
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

  }
  
  
   
  }
}



}else{
  $p_id=str_replace("productionno","",$string);
  
  //
  $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
   
  
   if($_POST['va']=="all"){
    
	$No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  "  );
   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  "  );

  }else{
  
   $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' ".$sql  );
   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  ".$sql  );
  
    }
  }else{
  $gender=$_POST['gender'];
  if($_POST['va']=="all"){
    
	$No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' "  );
   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' "  );

  }else{
  
   $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
  
    }
  
  }
}

}else{
  $p_id=str_replace("productionyes","",$string);
  
  //
  $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  if($_POST['gender']=="all"){
    
  
   if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  "  );
 
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  "  );

  
  }else{
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  ".$sql  );
 
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  ".$sql  );

  }
  
  
  }else{
  $gender=$_POST['gender'];
   if($_POST['va']=="all"){
  
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' "  );
 
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' "  );

  
  }else{
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
 
  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

  }
  
  }

}


}


draw_pie_chart($No,$Yes,$column,$enterprise,$json_model_obj,$util_obj);



}else

///////////////////////////////////////////parish

if($_POST['parish']=="all"){

$table="dataset_".$_POST['id'];
$subcounty=$_POST['country'];
$rows=array();
if($_POST['production']=="all"){

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
  
      
    if($_POST['va']=="all"){
  
     $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  "  );
   
     $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  "  );

  
     }else{
  
      $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' ".$sql  );
   
      $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' ".$sql  );

     }
   
   
   }else{
   $gender=$_POST['gender'];
   if($_POST['va']=="all"){
  
     $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  "  );
   
     $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  "  );

  
     }else{
  
      $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
   
      $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

     }
   
   }
   }
   
   
   }else{
   $p_id=str_replace("generalyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   
   
    if($_POST['va']=="all"){
  
    $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' "  );

    $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' "  );

  
    }else{
  
    $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' ".$sql  );

    $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' ".$sql  );

  
    }
      
   }else{
   $gender=$_POST['gender'];
   if($_POST['va']=="all"){
  
    $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender'"  );

    $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender'"  );

  
    }else{
  
    $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

    $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

  
    }
   
   }
   
   }
   }else{
   $p_id=str_replace("productionno","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
    
	   
      if($_POST['va']=="all"){
        $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' "  );
   
        $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  "  );

      }else{
        $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' ".$sql  );
   
        $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  ".$sql  );

      }
	  
   }else{
   $gender=$_POST['gender'];
   
    if($_POST['va']=="all"){
        $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' "  );
   
        $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' "  );

      }else{
        $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
   
        $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

      }
   
   }
   
   }
   }else{
   $p_id=str_replace("productionyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   
            if($_POST['va']=="all"){
            $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' "  );
   
            $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  "  );

            }else{
              
			  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' ".$sql  );
   
              $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' ".$sql  );

            }
        
   }else{
   $gender=$_POST['gender'];
            
			if($_POST['va']=="all"){
            $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' "  );
   
            $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' "  );

            }else{
              
			  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
   
              $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

            }
   
   }
   
   
   }
}

draw_pie_chart($No,$Yes,$column,$enterprise,$json_model_obj,$util_obj);
  


}else

///////////////////////////////////////////village

if($_POST['village']=="all"){

$table="dataset_".$_POST['id'];
$parish=$_POST['parish'];
$rows=array();
if($_POST['production']=="all"){
  
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
   
      
       if($_POST['va']=="all"){
  
         $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  "  );
   
         $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  "  );

       }else{
	   
         $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' ".$sql  );
   
         $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  ".$sql  );

       }
	   
   }else{
   $gender=$_POST['gender'];
          if($_POST['va']=="all"){
  
         $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' "  );
   
         $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' "  );

       }else{
         $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
   
         $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

       }
   
   }
   
   }
   }else{
   $p_id=str_replace("generalyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
            if($_POST['va']=="all"){
               
			    $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  "  );
                $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  "  );

            }else{
                $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  ".$sql  );
                $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  ".$sql  );
            }
   }else{
   $gender=$_POST['gender'];
            if($_POST['va']=="all"){
               
			    $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' "  );
                $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' "  );

            }else{
                $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
                $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
            }
   
   }
   
   }
   
   }else{
   $p_id=str_replace("productionno","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
    
      
         if($_POST['va']=="all"){
  
            $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' "  );
   
            $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  "  );

  
         }else{
            
			$No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  ".$sql  );
   
            $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  ".$sql  );

			
         }
   
   }else{
   $gender=$_POST['gender'];
   if($_POST['va']=="all"){
  
            $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' "  );
   
            $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' "  );

  
         }else{
            
			$No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
   
            $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

			
         }
   
   }
   
   }
   
   }else{
   $p_id=str_replace("productionyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
                 if($_POST['va']=="all"){
                  
				  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' "  );
                  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  "  );

                 }else{
                   $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  ".$sql  );
                  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  ".$sql  );

                  }
   }else{
   $gender=$_POST['gender'];
  if($_POST['va']=="all"){
                  
				  $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' "  );
                  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' "  );

                 }else{
                   $No=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
                  $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter." ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

                  }
   
   }
   
   
   }
}

 draw_pie_chart($No,$Yes,$column,$enterprise,$json_model_obj,$util_obj);




}else
{

//echo "off";
$table="dataset_".$_POST['id'];
$parish=$_POST['parish'];
$village=$_POST['village'];
$rows=array();
if($_POST['production']=="all"){
  
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
   
      
        if($_POST['va']=="all"){
  
          $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' "  );
   
          $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' "  );

        }else{
          $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' ".$sql  );
   
          $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' ".$sql  );

        }
   
   
   
   }else{
   $gender=$_POST['gender'];
     if($_POST['va']=="all"){
  
          $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'"  );
   
          $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'"  );

        }else{
          $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'".$sql  );
   
          $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'".$sql  );

        }
   
   }
   
   }
   }else{
   $p_id=str_replace("generalyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
            
		  if($_POST['va']=="all"){
  
             $No =(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' "  );

             $Yes =(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  "  );

  
          }else{
              $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  ".$sql  );
   
              $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  ".$sql  );

          }
		 
   }else{
   $gender=$_POST['gender']; 
         if($_POST['va']=="all"){
  
             $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' "  );
   
             $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' "  );

  
          }else{
              $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
   
              $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

          }
   
   }
   
   }
   }else{
   $p_id=str_replace("productionno","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
   
      
         if($_POST['va']=="all"){
  
   $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  "  );

   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  "  );

  
         }else{
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' ".$sql  );

   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  ".$sql  );

         }
   
   
   
   }else{
   $gender=$_POST['gender'];
   
         if($_POST['va']=="all"){
  
   $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' "  );

   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' "  );

  
         }else{
  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

   $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

         }
   
   }
   
   }
   }else{
   $p_id=str_replace("productionyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   if($_POST['gender']=="all"){
 
    
   
          if($_POST['va']=="all"){
  
             $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'male' "  );
  
             $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'female' "  );

  
           }else{
              
			  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'male' ".$sql  );
  
              $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'female' ".$sql  );

			  
           }
   
   
   }else{
   $gender=$_POST['gender'];
    
          if($_POST['va']=="all"){
  
             $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' "  );
  
             $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' "  );

  
           }else{
              
			  $No=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );
  
              $Yes=(double)$mCrudFunctions->get_count($table,$ageFilter."  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' ".$sql  );

			  
           }
   
   }
   
   }
}

draw_pie_chart($No,$Yes,$column,$enterprise,$json_model_obj,$util_obj);


}

}else{
$util_obj->deliver_response(200,0,null );
}


function draw_pie_chart($no,$yes,$column,$enterprise,$json_model_obj,$util_obj){
$title=str_replace("general_questions_"," ",str_replace($enterprise."_production_data"," ",$column));
$titleArray=array();


$total=$no+$yes;

$per_no=($no/$total)*100;

$per_yes=($yes/$total)*100;;

if($per_no==0&& $per_yes==0){
$titleArray=array('text'=>'No Production Question Selected');

}else{
$titleArray=array('text'=>''.ucwords(str_replace("_"," ",$title))."?");

}

$datax=array();


$temp=array('No',$per_no);
array_push($datax,$temp);
array_push($datax,array('name'=>'Yes','color'=>'#FF9800','y'=>$per_yes,'sliced'=>true,'selected'=>true));
$dataArray=array('type'=>'pie','name'=>''.ucwords(str_replace("_"," ",$title)), 'data'=>$datax);



if($per_no==0&& $per_yes==0){
echo 0 ;
}else{
header('Content-type: application/json');
$data=$json_model_obj->get_piechart_graph_json($titleArray,$dataArray);//
$util_obj->deliver_response(200,1,$data );
}
}

?>

