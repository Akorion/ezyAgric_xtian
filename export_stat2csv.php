<?php
#includes
require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";

$newline ="\n";
$nextcell="";
$header="";
$body="";

$age_min=!empty($_POST['age_min'])? (int)$_POST['age_min'] :0;
$age=!empty($_POST['age'])? (int)$_POST['age'] :0;
$age_max=!empty($_POST['age_max'])? (int)$_POST['age_max'] :0;

$va=$_POST['va'];;
$va=str_replace("(","-",$va);
$va=str_replace(")","",$va);
$strings=explode('-',$va);
$va= strtolower($strings[1]);

$va_sql=" ";
if($_POST['va']!="all"){
$va_sql=" AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'";
}
$ageFilter="";
if($age!="" && $age!=0){
 
  $ageFilter= " floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  AND ";
 }
 
 if($age_min<=$age_max && $age_max!=0){
  $ageFilter= "  (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  AND ";
 }
 
 if($age_min>0 &&$age_max==0 ){
  $ageFilter= " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  AND ";
 }

if(isset($_POST['id'])&&isset($_POST['district'])
  &&isset($_POST['country'])&&isset($_POST['parish'])
  &&isset($_POST['village'])&&isset($_POST['production'])){
 $mCrudFunctions = new CrudFunctions();
 $util_obj= new Utilties();
$id=$_POST['id'];
$district=$_POST['district'];
$county=$_POST['country'];
$parish= $_POST['parish'];
$village=$_POST['village'];
$gender=$_POST['gender'];
$production_header="all";
$dataset_r = $mCrudFunctions->fetch_rows("datasets_tb","dataset_name"," id ='$id'");
$dataset=$dataset_r[0]["dataset_name"];

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$dataset.".csv");

///////////////////////////////////////districts
if($_POST['district']=="all"){
$table="dataset_".$_POST['id'];
$rows=array();

if($_POST['production']=="all"){

if($_POST['gender']=="all"){
$rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency "," $ageFilter 1 $va_sql  GROUP BY district ORDER BY frequency Desc "  );

}else{
$gender=$_POST['gender'];
$rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency "," $ageFilter lower(biodata_farmer_gender) = '$gender' $va_sql  GROUP BY district ORDER BY frequency Desc "  );

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
   $production_header= str_replace("general_questions","",$column)."(no)";
  if($_POST['gender']=="all"){
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","$ageFilter  ".$column." LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency "," $ageFilter  lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'no'  $va_sql  GROUP BY district ORDER BY frequency Desc "  );
 
  
  }
}

}else{

  $p_id=str_replace("generalyes","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  
  $production_header= str_replace("general_questions","",$column)."(Yes)";
  
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","$ageFilter  ".$column." LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc "  );
  
  
  }
}

}else{

  $p_id=str_replace("productionno","",$string);
  $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  $enterprise=$row[0]['enterprise'];
 
  $production_header= str_replace($enterprise."_production_data","",$column)."(No)";
  
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","$ageFilter  ".$column." LIKE 'no' $va_sql GROUP BY district ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc"  );
  
  }
}

}else{

  $p_id=str_replace("productionyes","",$string);
  
  //
  $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  $enterprise=$row[0]['enterprise'];
 
  $production_header= str_replace($enterprise."_production_data","",$column)."(Yes)";
  if($_POST['gender']=="all"){
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","$ageFilter  ".$column." LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc"  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc "  );
  
  
  }
}


}

$production_header=$util_obj->captalizeEachWord($production_header);

         $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 
		 $header.='"'."DATASET NAME :".$dataset.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."DISTRICT :".$district.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."SUB-COUNTY :".$county.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PARISH :".$parish.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."VILLAGE :".$village.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PRODUCTION DATA :".$production_header.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."GENDER :".$gender.'",';
		 $header.=$newline;
		 $header.=$newline;
		 
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."Number of farmers per District".'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'."No.".'",';
		 $header.='"'."District".'",';
		 $header.='"'."Frequency".'",';
		 $header.='"'."%(ge)".'",';
		
         $header.=$newline;
		
         $body.=$header;
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

    $i=1;
	$data = array();
	foreach($rows as $row)
	{
	
	
	$district=$util_obj->captalizeEachWord($row['district']);
	$frequency=$row['frequency'];
	
	$x= array();
	$x['district']=$district;
    $x['freq']=$frequency;
	
    if(sizeof($data)<1){
	array_push($data, $x);
	}else{
	
	$i=0;
	foreach($data as $info){
	
	if (in_array($district, $info))
    {
        if(sizeof($data)==1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}else
		if(sizeof($data)>1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}
		
		$i++;
    }
    else
    {   
	    
	    $apparent_size=(sizeof($data)-1);
        if($apparent_size==$i){
		array_push($data, $x);
		}
		
		$i++;
		
    }
	
	}
	}
	
	}
	
	$i=1;
	foreach($data as $final){
	$district=$final['district'];
	$frequency=$final['freq'];
	$percentage= ($frequency/$total)*100;
	$percentage= round($percentage,2);
	$body.='"'.$nextcell.'",';
	
	$body .='"'.$util_obj->escape_csv_value($i).'",';
	$body .='"'.$util_obj->escape_csv_value($district).'",';
	$body .='"'.$util_obj->escape_csv_value($frequency).'",';
	$body .='"'.$util_obj->escape_csv_value($percentage).'",';
	$body.=$newline;
	
	$i++;
	
	}
	
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body .='"'."Total :".$util_obj->escape_csv_value($total).'",';
	echo $body;

}
}else///////////////////////////////////////////subcounties
if($_POST['country']=="all"){

$table="dataset_".$_POST['id'];
$district=$_POST['district'];
$rows=array();
if($_POST['production']=="all"){

if($_POST['gender']=="all"){

$rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ","  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );

}else{
 $gender=$_POST['gender'];

 $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );


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
  
  $production_header= str_replace("general_questions","",$column)."(No)";
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ","   lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  
  }
  
}
}else{
  $p_id=str_replace("generalyes","",$string);
  //
  $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  $production_header= str_replace("general_questions","",$column)."(Yes)";
  
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  }
}



}else{
  $p_id=str_replace("productionno","",$string);
  
  //
  //
  $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  $enterprise=$row[0]['enterprise'];
 
  $production_header= str_replace($enterprise."_production_data","",$column)."(No)";
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  }
}

}else{
  $p_id=str_replace("productionyes","",$string);
  
 //
  $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  $enterprise=$row[0]['enterprise'];
 
  $production_header= str_replace($enterprise."_production_data","",$column)."(Yes)";
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  }

}


}

$production_header=$util_obj->captalizeEachWord($production_header);
         $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 
		 $header.='"'."DATASET NAME :".$dataset.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."DISTRICT :".$district.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."SUB-COUNTY :".$county.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PARISH :".$parish.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."VILLAGE :".$village.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PRODUCTION DATA :".$production_header.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."GENDER :".$gender.'",';
		 $header.=$newline;
		 $header.=$newline;
		 
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."Number of farmers per Subcounty".'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'."No.".'",';
		 $header.='"'."Sub-county".'",';
		 $header.='"'."Frequency".'",';
		 $header.='"'."%(ge)".'",';
		
         $header.=$newline;
		
         $body.=$header;
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

    $i=1;
	$data = array();
	foreach($rows as $row)
	{
	
	
	$subcounty=$util_obj->captalizeEachWord($row['subcounty']);
	$frequency=$row['frequency'];
	
	$x= array();
	$x['subcounty']=$subcounty;
    $x['freq']=$frequency;
    if(sizeof($data)<1){
	array_push($data, $x);
	}else{
	
	$i=0;
	foreach($data as $info){
	
	if (in_array($subcounty, $info))
    {
        if(sizeof($data)==1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}else
		if(sizeof($data)>1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}
		
		$i++;
    }
    else
    {   
	    
	    $apparent_size=(sizeof($data)-1);
        if($apparent_size==$i){
		array_push($data, $x);
		}
		
		$i++;
		
    }
	
	}
	}
	
	}
	
	$i=1;
	foreach($data as $final){
	$subcounty=$final['subcounty'];
	$frequency=$final['freq'];
	$percentage= ($frequency/$total)*100;
	$percentage= round($percentage,2);
	$body.='"'.$nextcell.'",';
	
	$body .='"'.$util_obj->escape_csv_value($i).'",';
	$body .='"'.$util_obj->escape_csv_value($subcounty).'",';
	$body .='"'.$util_obj->escape_csv_value($frequency).'",';
	$body .='"'.$util_obj->escape_csv_value($percentage).'",';
	$body.=$newline;
	
	$i++;
	
	}
	
	
	
	
	
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body .='"'."Total :".$util_obj->escape_csv_value($total).'",';
	echo $body;

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
  COUNT(biodata_farmer_location_farmer_parish)  as frequency ","  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );

}else{
$gender=$_POST['gender'];

$rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
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
   $production_header= str_replace("general_questions","",$column)."(No)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  "  );
   
   }
   }
   
   
   }else{
   $p_id=str_replace("generalyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   $production_header= str_replace("general_questions","",$column)."(Yes)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );

   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  "  );

   }
   
   }
   }else{
   $p_id=str_replace("productionno","",$string);
    //
   $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   $enterprise=$row[0]['enterprise'];
 
   $production_header= str_replace($enterprise."_production_data","",$column)."(No)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY  parish ORDER BY frequency Desc "  );
   
   }
   
   }
   }else{
   $p_id=str_replace("productionyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   $enterprise=$row[0]['enterprise'];
 
   $production_header= str_replace($enterprise."_production_data","",$column)."(Yes)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc "  );
   
   }
   
   
   }
}



$production_header=$util_obj->captalizeEachWord($production_header);
 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 
		 $header.='"'."DATASET NAME :".$dataset.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."DISTRICT :".$district.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."SUB-COUNTY :".$county.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PARISH :".$parish.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."VILLAGE :".$village.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PRODUCTION DATA :".$production_header.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."GENDER :".$gender.'",';
		 $header.=$newline;
		 $header.=$newline;
		 
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."Number of farmers per Parish".'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'."No.".'",';
		 $header.='"'."Parish".'",';
		 $header.='"'."Frequency".'",';
		 $header.='"'."%(ge)".'",';
		
         $header.=$newline;
		
         $body.=$header;
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

    $i=1;
	$data = array();
	foreach($rows as $row)
	{
	
	$parish=$util_obj->captalizeEachWord($row['parish']);
	$frequency=$row['frequency'];
	
	$x= array();
	$x['parish']=$parish;
    $x['freq']=$frequency;
    if(sizeof($data)<1){
	array_push($data, $x);
	}else{
	
	$i=0;
	foreach($data as $info){
	
	if (in_array($parish, $info))
    {
        if(sizeof($data)==1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}else
		if(sizeof($data)>1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}
		
		$i++;
    }
    else
    {   
	    
	    $apparent_size=(sizeof($data)-1);
        if($apparent_size==$i){
		array_push($data, $x);
		}
		
		$i++;
		
    }
	}
	}
	
	}
	
	
	$i=1;
	foreach($data as $final){
	$parish=$final['parish'];
	$frequency=$final['freq'];
	$percentage= ($frequency/$total)*100;
	$percentage= round($percentage,2);
	$body.='"'.$nextcell.'",';
	
	$body .='"'.$util_obj->escape_csv_value($i).'",';
	$body .='"'.$util_obj->escape_csv_value($parish).'",';
	$body .='"'.$util_obj->escape_csv_value($frequency).'",';
	$body .='"'.$util_obj->escape_csv_value($percentage).'",';
	$body.=$newline;
	
	$i++;
	
	}
	
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body .='"'."Total :".$util_obj->escape_csv_value($total).'",';
	echo $body;
}

}else

///////////////////////////////////////////village

if($_POST['village']=="all"){

$table="dataset_".$_POST['id'];
$parish=$_POST['parish'];
$subcounty=$_POST['country'];
$rows=array();
if($_POST['production']=="all"){
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ","  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
  
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
   $production_header= str_replace("general_questions","",$column)."(No)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  "  );
   
   }
   
   }
   }else{
   $p_id=str_replace("generalyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   $production_header= str_replace("general_questions","",$column)."(Yes)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  "  );
   
   }
   
   }
   
   }else{
   $p_id=str_replace("productionno","",$string);
    //
   $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   $enterprise=$row[0]['enterprise'];
 
   $production_header= str_replace($enterprise."_production_data","",$column)."(No)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY  village  ORDER BY frequency Desc "  );
   
   }
   
   }
   
   }else{
   $p_id=str_replace("productionyes","",$string);
    //
  $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  $enterprise=$row[0]['enterprise'];
 
  $production_header= str_replace($enterprise."_production_data","",$column)."(Yes)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   }
   
   
   }
}


$production_header=$util_obj->captalizeEachWord($production_header);
 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 
		 $header.='"'."DATASET NAME :".$dataset.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."DISTRICT :".$district.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."SUB-COUNTY :".$county.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PARISH :".$parish.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."VILLAGE :".$village.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PRODUCTION DATA :".$production_header.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."GENDER :".$gender.'",';
		 $header.=$newline;
		 $header.=$newline;
		 
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."Number of farmers per Village".'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'."No.".'",';
		 $header.='"'."Village".'",';
		 $header.='"'."Frequency".'",';
		 $header.='"'."%(ge)".'",';
		
         $header.=$newline;
		
         $body.=$header;
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

    $i=1;
	$data = array();
	foreach($rows as $row)
	{
	
	$village_h=$util_obj->captalizeEachWord($row['village']);
	$frequency=$row['frequency'];
	
	$x= array();
	$x['village']=$village_h;
    $x['freq']=$frequency;
    
	if(sizeof($data)<1){
	array_push($data, $x);
	}else{
	
	$i=0;
	foreach($data as $info){
	
	if (in_array($village_h, $info))
    {
        if(sizeof($data)==1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}else
		if(sizeof($data)>1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}
		
		$i++;
    }
    else
    {   
	    
	    $apparent_size=(sizeof($data)-1);
        if($apparent_size==$i){
		array_push($data, $x);
		}
		
		$i++;
		
    }
	
	}
	}
	
	}
	
	$i=1;
	foreach($data as $final){
	$village_h=$final['village'];
	$frequency=$final['freq'];
	$percentage= ($frequency/$total)*100;
	$percentage= round($percentage,2);
	$body.='"'.$nextcell.'",';
	
	$body .='"'.$util_obj->escape_csv_value($i).'",';
	$body .='"'.$util_obj->escape_csv_value($village_h).'",';
	$body .='"'.$util_obj->escape_csv_value($frequency).'",';
	$body .='"'.$util_obj->escape_csv_value($percentage).'",';
	$body.=$newline;
	
	$i++;
	
	}
	
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body .='"'."Total :".$util_obj->escape_csv_value($total).'",';
	echo $body;
}


}else{

//echo "off";
$table="dataset_".$_POST['id'];
$parish=$_POST['parish'];
$village=$_POST['village'];
$subcounty=$_POST['country'];
$rows=array();
if($_POST['production']=="all"){
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ","  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc "  );
  
  
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
   $production_header= str_replace("general_questions","",$column)."(No)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  "  );
   
   }
   
   }
   }else{
   $p_id=str_replace("generalyes","",$string);
   //
   $row = $mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   $production_header= str_replace("general_questions","",$column)."(Yes)";
   
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  "  );
   
   
   }
   
   }
   }else{
   $p_id=str_replace("productionno","",$string);
   //
  $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
  $column =$row[0]['columns'];
  $enterprise=$row[0]['enterprise'];
 
  $production_header= str_replace($enterprise."_production_data","",$column)."(No)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc "  );
   
   }
   
   }
   }else{
   $p_id=str_replace("productionyes","",$string);
    //
   $row = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," id='$p_id' ");
   $column =$row[0]['columns'];
   $enterprise=$row[0]['enterprise'];
 
   $production_header= str_replace($enterprise."_production_data","",$column)."(Yes)";
   if($_POST['gender']=="all"){
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  "  );
   
   }else{
   $gender=$_POST['gender'];
   
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  "  );
   
   }
   
   }
}



$production_header=$util_obj->captalizeEachWord($production_header);
 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 
		 $header.='"'."DATASET NAME :".$dataset.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."DISTRICT :".$district.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."SUB-COUNTY :".$county.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PARISH :".$parish.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."VILLAGE :".$village.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."PRODUCTION DATA :".$production_header.'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",'; 
		 $header.='"'."GENDER :".$gender.'",';
		 $header.=$newline;
		 $header.=$newline;
		 
		 $header.='"'.$nextcell.'",';
		 $header.='"'.$nextcell.'",';
		 $header.='"'."Number of farmers in $village".'",';
		 $header.=$newline;
		 $header.='"'.$nextcell.'",';
		 $header.='"'."No.".'",';
		 $header.='"'."Village".'",';
		 $header.='"'."Frequency".'",';
		 $header.='"'."%(ge)".'",';
		
         $header.=$newline;
		
         $body.=$header;
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

    $i=1;
	$data = array();
	foreach($rows as $row)
	{
	
	$x= array();
	$village_h=$util_obj->captalizeEachWord($row['village']);;
	$frequency=$row['frequency'];
	$x['village']=$village_h;
    $x['freq']=$frequency;
    
	if(sizeof($data)<1){
	array_push($data, $x);
	}else{
	
	$i=0;
	foreach($data as $info){
	
	if (in_array($village_h, $info))
    {
        if(sizeof($data)==1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}else
		if(sizeof($data)>1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		
		}
		
		$i++;
    }
    else
    {   
	    
	    $apparent_size=(sizeof($data)-1);
        if($apparent_size==$i){
		array_push($data, $x);
		}
		
		$i++;
		
    }
	}
	}
	
	}
	
	$i=1;
	foreach($data as $final){
	$village_h=$final['village'];
	$frequency=$final['freq'];
	$percentage= ($frequency/$total)*100;
	$percentage= round($percentage,2);
	$body.='"'.$nextcell.'",';
	
	$body .='"'.$util_obj->escape_csv_value($i).'",';
	$body .='"'.$util_obj->escape_csv_value($village_h).'",';
	$body .='"'.$util_obj->escape_csv_value($frequency).'",';
	$body .='"'.$util_obj->escape_csv_value($percentage).'",';
	$body.=$newline;
	
	$i++;
	
	}
	
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body.='"'.$nextcell.'",';
	$body .='"'."Total :".$util_obj->escape_csv_value($total).'",';
	echo $body;

}

}
}
?>