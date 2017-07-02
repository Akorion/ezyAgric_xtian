<?php
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/pagination_class.php";

$mCrudFunctions = new CrudFunctions();
 $va=$_POST['va'];;
$va=str_replace("(","-",$va);
$va=str_replace(")","",$va);
//$string="hyderabad-india";
$strings=explode('-',$va);
//echo $strings[0]; // outputs: hyderabad
//echo "<br>"; // new line
$va= strtolower($strings[1]); // outputs: india

$age_min=!empty($_POST['age_min'])? (int)$_POST['age_min'] :0;
$age=!empty($_POST['age'])? (int)$_POST['age'] :0;
$age_max=!empty($_POST['age_max'])? (int)$_POST['age_max'] :0;

 if(isset($_POST['id'])){
 
           $district=$_POST['district'];
		   $sel_production_id =$_POST['sel_production_id'];
		   $subcounty=$_POST['subcounty'];
		   $parish=$_POST['parish'];
		   $village=$_POST['village'];
		   $gender=$_POST['gender'];
           $table="dataset_".$_POST['id'];	
	       $page =!empty($_POST['page'])? (int)$_POST['page'] : 1;
		    $total_count=0;//$mCrudFunctions->get_count($table,1);
		   if($_POST['district']=="all"){
		   
		   if($_POST['sel_production_id']=="all"){
		   if($_POST['gender']=="all"){
		    if($_POST['va']=="all"){
			 
			 if($age!="" && $age!=0){
			 $total_count=$mCrudFunctions->get_count($table," floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
 
             }else
 
             if($age_min<=$age_max && $age_max!=0){
             $total_count=$mCrudFunctions->get_count($table," (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
             
			 }else
 
             if($age_min>0 &&$age_max==0 ){
               $total_count=$mCrudFunctions->get_count($table," (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");

			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table,1);

			 }
			 
			}else{
			
			if($age!="" && $age!=0){
			 
             $total_count=$mCrudFunctions->get_count($table," lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
             }else
 
             if($age_min<=$age_max && $age_max!=0){
                $total_count=$mCrudFunctions->get_count($table," lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
			
			 }else
 
             if($age_min>0 &&$age_max==0 ){
              
              $total_count=$mCrudFunctions->get_count($table," lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table," lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
			

			 }
			
			
			
			
			
			}
		   
		   }else{
		   
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
			 
             	 $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
             }else
 
             if($age_min<=$age_max && $age_max!=0){
               $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			 $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' ");

			 }
			
			
			
			}else{
			
			
			
			if($age!="" && $age!=0){
			 
             $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			 $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 }else
             if($age_min>0 &&$age_max==0 ){
             
	
			 $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
			

			 }
			
			
			
			
			
			
			
			
			
			}
			
			
		   }
		   }else{
		   
		   if(strpos($sel_production_id, "productionyes")===false){
		   if(strpos($sel_production_id, "productionno")===false){
		   if(strpos($sel_production_id, "generalyes")===false){
		   if(strpos($sel_production_id, "generalno")===false){
		   }else{
		   //generalno
		    $p_id=str_replace("generalno","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
		    if($_POST['gender']=="all"){
		    
			
			if($_POST['va']=="all"){
		
			if($age!="" && $age!=0){
			 
             $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  " );
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			 $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) " );
			
			 }else
             if($age_min>0 &&$age_max==0 ){
             
	
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) " );
			
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' " );
			

			 }
			
			
			}else{
			
						
			if($age!="" && $age!=0){
			 
   
			 $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age " );

			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			 $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) " );

			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )" );

			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'" );


			 }
			
			}
			
			
			
		   }else{
		   
		   if($_POST['va']=="all"){
		   
		   if($age!="" && $age!=0){
		
			$total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		   
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			 $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		   
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		   
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' ");
		   

			 }
		   
		   }else{
		   
		   if($age!="" && $age!=0){
		
			
		     $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		   
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			 $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		   
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		   
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		   

			 }
		   
		   
		   }///////////////////////hh
		   
		    
		   }/////////////////////////jj
			
		   }
		   }else{
		   //generalyes
		    $p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
		    
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  " );
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			 $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) " );
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) " );
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' " );

			 }
			
			
			
			
			}else{
			
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  " );
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			 $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) " );
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) " );
			
			 
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' " );
			
			 }
			
			
			
			
			}
			
			
		   }else{
		   
		   //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
		   if($_POST['va']=="all"){
		   
		   if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		   
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			 $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		   
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		   
			 
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' ");
		   
			 }
		   
		   
		   
		   }else{
		   
		   
		   
		     if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		   
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		   
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		   
			 
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		   
			 } 
		   
		   
		   
		   }
		   
		    
		   }
		   
		   }
		   }else{
		   //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			if($_POST['gender']=="all"){
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age " );
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) " );
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) " );
			
			 
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' " );
			
			 } 
			
			
			
			
			
			
			
			
			
			
			}else{
			
			if($age!="" && $age!=0){
		
			$total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age " );
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) " );
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) " );
			
			 
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' " );
			
			 } 
			
			
			
			}
			
			
		    
		   }else{
		   
		   //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
		   
		   if($_POST['va']=="all"){
		   
		   if($age!="" && $age!=0){
		
			$total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		   
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		   
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )");
		   
			 
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' ");
		   
			 } 
		   
		   
		   }else{
		   
		   
		   if($age!="" && $age!=0){
		
			$total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		   
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )");
		   
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		   
			 
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		   
			 } 
		   
		   }
		   
		    
		   }
		   }
		   }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
		   
		    if($_POST['gender']=="all"){
			
			 //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
			$total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age " );
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) " );
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) " );
			
			 
			 }else{
			 
			  $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' " );
			
			 } 
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
			$total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age " );
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) " );
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) " );
			
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' " );
			
			 } 
			
			
			
			}
			
		    
		   }else{
		   //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
		   if($_POST['va']=="all"){
		   
		   
		   if($age!="" && $age!=0){
		
			$total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		   
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		   
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		   
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' ");
		   
			 } 
		   
		   
		   
		   }else{
		   
		    if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
		    
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 } 
		   
		   
		   }
		   
		   
		    
		   }
		   
		   }
		   
		   }
		   }else{if($_POST['subcounty']=="all"){
		   
		    if($_POST['sel_production_id']=="all"){
			if($_POST['gender']=="all"){
		    
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  ");
		    
			 } 
			
			}else{
		    

		   if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		   
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		   
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		   
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' ");
		   
			 }

		   }	
			}else{
			///////////////////////////////
			if(strpos($sel_production_id, "productionyes")===false){
		    if(strpos($sel_production_id, "productionno")===false){
		    if(strpos($sel_production_id, "generalyes")===false){
		    if(strpos($sel_production_id, "generalno")===false){
		    }else{
		    //generalno
			$p_id=str_replace("generalno","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			if($_POST['gender']=="all"){
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
			
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' ");
			
			 }
			
			
			}else{
			
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
			
			 }
			
			
			
			
			}
			
			
		    
		    }else{
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			if($_POST['va']=="all"){
			 
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ");
			
			 }
			
			
			}else{
			
			 if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )");
			 
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			 
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
			 
			 }
			
			
			
			}
			
			
		   
		    }	
			
		    }
		    }else{
		    //generalyes
			$p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
		    
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			
			}else{
		    
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			 $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			
			
			}	
		   
		    }
		    }else{
		    //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' ");
			
			 }
			
			}else{
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
			
			 }
			
			
			
			}
			
			
		    
		    }else{
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ");
			
			 }
			
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
			
			 }
			
			
			
			}
			
			
		    
		    }	
		    }
		    }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			
		    if($_POST['gender']=="all"){
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' ");
			
			 }
			
			
			}else{
			
			if($age!="" && $age!=0){
		
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
			
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
			
			 }
			
			
			
			}
			
		    
		    }else{
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ");
			
			 }
			
			
			}else{
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
			
			 }
			
			
			
			}
			
			
		    
		    }	
		     
		   
		    }
            //////////////////////////////
			}
		   
		   
		   }else{if($_POST['parish']=="all"){
		    
			if($_POST['sel_production_id']=="all"){
			if($_POST['gender']=="all"){
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  ");
		    
			 }
			
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			
			
			
			}
		    
			
			}else{
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
		   
			if($_POST['va']=="all"){
			 
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender'  AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' ");
		    
			 }
			
			
			}else{
			
			 
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			
			}
			
			
			
			}
			}else{
			///////////////////////////////
			if(strpos($sel_production_id, "productionyes")===false){
		    if(strpos($sel_production_id, "productionno")===false){
		    if(strpos($sel_production_id, "generalyes")===false){
		    if(strpos($sel_production_id, "generalno")===false){
		    }else{
		    //generalno
			$p_id=str_replace("generalno","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			if($_POST['gender']=="all"){
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' ");
			
			  if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' ");
			
			 }
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		      $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' ");
			
			 }
			
			
			}
			
		    
		    }else{
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
		   
			if($_POST['va']=="all"){
			 
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			
			
			}else{
			 
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			}
			
			}
			
		    }
		    }else{
		    //generalyes
			$p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			 
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' ");
			
			 }
			
			}else{
			
			 
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
			
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
             
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
			
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
			
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
			
			 }
			
			
			
			}
			
		   
		    }else{
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
		  if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		  
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		  
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		  
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ");
		  
			 }
		  
		  
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			
			
			}
			
		    
			
			}
		   
		    }
		    }else{
		    //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $p_id;
            $row =$mCrudFunctions->fetch_rows("production_data","columns","  id=1724  ");
			//print_r($row);
            $column =$row[0]['columns'];
			
			
			if($_POST['gender']=="all"){
		    
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			
			}else{
			/////////////////////////////////16/08/2016 1706
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			
			}
			
			}else{
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			
			
			}else{
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			}
		    
			}
			
		    }
		    }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			}else{
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			
			}
			
		    
			
			}else{
		    
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			
			}else{
			
		   if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		   
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		   
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		   
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		   
			 }
		   
		   
		   
			}
			
			}
		   
		    }
            //////////////////////////////
	
			}
		   
		   
	
		   }else{if($_POST['village']=="all"){
		   
		    if($_POST['sel_production_id']=="all"){
			
			if($_POST['gender']=="all"){
		     
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' ");
		    
			 }
			
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			
			}
			
			}else{
		    
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' ");
		    
			 }
			
			
			}else{
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			
			}
			
			}
			
			}else{
			///////////////////////////////
			if(strpos($sel_production_id, "productionyes")===false){
		    if(strpos($sel_production_id, "productionno")===false){
		    if(strpos($sel_production_id, "generalyes")===false){
		    if(strpos($sel_production_id, "generalno")===false){
		    }else{
		    //generalno
			$p_id=str_replace("generalno","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			if($_POST['gender']=="all"){
		     
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			
			
			}
			
			}else{
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			}else{
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			
			}
			
			}
			
		    }
		    }else{
		    //generalyes
		    $p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
			 if($_POST['gender']=="all"){
		     //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			 
			 if($_POST['va']=="all"){
			 
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			
			 
			 }else{
			 
			 
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			
			 }
			 
			 
			
			}else{
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			
			
			}
			
			}
		    }
		    }else{
		    //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			 if($_POST['gender']=="all"){
			 //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			 
			 if($_POST['va']=="all"){
			 
			 if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			 }else{
			 
			 
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			  
			
			 
			 }
			 
			
			}else{
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			}
		  
			}
			
		    }
		    }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
		     
			 if($_POST['gender']=="all"){
			 //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			 
			 if($_POST['va']=="all"){
			 
			 
			 if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			
			
			 }else{
			 
			 if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			 
			 }
			
			}else{
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			
			 if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			
			
			}
			
			}
		   
		    }
            //////////////////////////////			
			}		   
		   }else{
		   if($_POST['sel_production_id']=="all"){
		    if($_POST['gender']=="all"){
		    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			 $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' ");
		    
			 }
			
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			 $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			
			
			
			}
			
			}else{
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			 $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' ");
		    
			 }
			
			
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			 $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			}
			
			
			}
		   }else{
			///////////////////////////////
			if(strpos($sel_production_id, "productionyes")===false){
		    if(strpos($sel_production_id, "productionno")===false){
		    if(strpos($sel_production_id, "generalyes")===false){
		    if(strpos($sel_production_id, "generalno")===false){
		    }else{
		    //generalno
			$p_id=str_replace("generalno","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			 
			 if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			 $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			 $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			
			}
			
			
		    
			}else{
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			 $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			 $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
		    
			 }
			
			
			}
			
			
		    
			
			}
			
		    }
		    }else{
		    //generalyes
			$p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
		    if($_POST['gender']=="all"){
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age   ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )   ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )   ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'   ");
		    
			 }
			
			
			}
			
			}else{
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			}
			
			}
			
		    }
		    }else{
		    //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
		    
			if($_POST['gender']=="all"){
		    
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			
			}else{
		    
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			 
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ");
		    
			 }
			
			
			}
		   
		    }
		    }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
		    
			if($_POST['gender']=="all"){
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' ");
		    
			 }
	
			}else{
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			
			$total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			}
			
			}else{
			
			//lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
			
			
			if($_POST['va']=="all"){
			
			
			if($age!="" && $age!=0){
		
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max ) ");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ");
		    
			 }
			
			
			}else{
			
			
			if($age!="" && $age!=0){
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age ");
		    
			
			 }else
 
             if($age_min<=$age_max && $age_max!=0){
            
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )");
		    
			 
			 }else
             if($age_min>0 &&$age_max==0 ){
             
			  
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min ) ");
		    
			 
			 }else{
			
			$total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");
		    
			 }
			
			
			
			}
			
		    
			
			}
		   
		    }
            //////////////////////////////
		   }
		   }}}}
		  
		   
	       $per_page=16;
	       $pagination_obj= new Pagination($page,$per_page,$total_count); 
           $total_pages= $pagination_obj->getTotalPages();
		  //hidden 
          echo"<input id=\"per_page\"type=\"hidden\" value=\"$per_page\" />";
		  //hidden 
          echo"<input id=\"total_count\"type=\"hidden\" value=\"$total_count\" />";
		  //hidden 
          echo"<input id=\"current_page\"type=\"hidden\" value=\"$page\"  />";
		  if($total_pages>0){
		  echo '<ul class="pagination center">';
		  if( $pagination_obj->check4PreviousPage()){
		  $previous_page= $pagination_obj->getPreviousPage();
		   echo"<li class=\"waves-effect\"><a onclick=\"filterDataPagination(1);\" style=\"height:35px;\"><i class=\"material-icons\"><<<<</i></a></li>";
		  echo"<li class=\"waves-effect\"><a onclick=\"filterDataPagination($previous_page);\" style=\"height:35px;\"><i class=\"material-icons\"><<</i></a></li>";
		  };
		  $j=0;
		  //$offset= $pagination_obj->getOffset();
		  for($i=$page;$i<$total_pages; $i++){
		  
		  if($total_pages>7){
		  if($j==7){
		  break;
		  }
		  }
		  if($page==$i){
		  echo"<li class=\"active\"><a onclick=\"filterDataPagination($i);\">$i</a></li>";
		  }else{
		  echo"<li class=\"waves-effect\"><a onclick=\"filterDataPagination($i);\">$i</a></li>";
		  }
		  
		  $j++;  
		  }
		  
		  if( $pagination_obj->check4NextPage()){
		  $next_page= $pagination_obj->getNextPage();
		  echo"<li class=\"waves-effect\"><a onclick=\"filterDataPagination($next_page)\" style=\"height:35px;\"><i class=\"material-icons\">>></i></a></li>";
		  
		  echo"<li class=\"waves-effect\"><a onclick=\"filterDataPagination($total_pages)\" style=\"height:35px;\"><i class=\"material-icons\">>>>></i></a></li>";
		  };
		  echo'</ul>';
		 }

}
          
unset($mCrudFunctions);
?>