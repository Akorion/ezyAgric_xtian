<?php
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/pagination_class.php";

$mCrudFunctions = new CrudFunctions();

 if(isset($_POST['id'])){
           $sql="";
           if(!empty($_POST['search'])){
              $search=$_POST['search'];
             if($_POST['district']=="all"){
                 $sql= " AND  (   biodata_farmer_location_farmer_district LIKE '%$search%' OR 
                 biodata_farmer_location_farmer_subcounty LIKE '%$search%' OR
                 biodata_farmer_location_farmer_parish LIKE '%$search%' OR 
				 biodata_farmer_location_farmer_village LIKE '%$search%' OR 
				 biodata_farmer_name LIKE '%$search%' OR 
				 biodata_farmer_phone_number LIKE '%$search%' )";
             }else{
              if($_POST['subcounty']=="all"){
                 $sql= " AND  (
                 biodata_farmer_location_farmer_subcounty LIKE '%$search%' OR
                 biodata_farmer_location_farmer_parish LIKE '%$search%' OR 
				 biodata_farmer_location_farmer_village LIKE '%$search%' OR
                 biodata_farmer_name LIKE '%$search%'	OR 
				 biodata_farmer_phone_number LIKE '%$search%'  )";
              }else{
                 if($_POST['parish']=="all"){
                 $sql= "  AND (      biodata_farmer_location_farmer_parish LIKE '%$search%' OR 
				 biodata_farmer_location_farmer_village LIKE '%$search%' OR
				 biodata_farmer_name LIKE '%$search%' OR 
				 biodata_farmer_phone_number LIKE '%$search%' )";
                 }else{
                   if($_POST['village']=="all"){
                      $sql= " AND  ( biodata_farmer_location_farmer_village LIKE '%$search%'  OR  biodata_farmer_name LIKE '%$search%'
                      OR biodata_farmer_phone_number LIKE '%$search%' )";
                    }else{
                       $sql= " AND  ( biodata_farmer_name LIKE '%$search%'  OR biodata_farmer_phone_number LIKE '%$search%'  )";
                    }
                }
            }
        }
        
        
    }
 
           $district=$_POST['district'];
		   $sel_production_id =$_POST['sel_production_id'];
		   $subcounty=$_POST['subcounty'];
		   $parish=$_POST['parish'];
		   $village=$_POST['village'];
		   $gender=$_POST['gender'];
           $table="dataset_".$_POST['id'];	
	       $page =!empty($_POST['page'])? (int)$_POST['page'] : 1;
		    $total_count=0;
		   if($_POST['district']=="all"){
		   
		   if($_POST['sel_production_id']=="all"){
		   if($_POST['gender']=="all"){
		    if($sql!=""){
			$sql_=str_replace("AND","",$sql);
			$total_count=$mCrudFunctions->get_count($table,$sql_);
			}else{
			 $total_count=$mCrudFunctions->get_count($table,1);
			}
		   
		   }else{
		    $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' ");
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
			$sql_=str_replace("AND","",$sql);
			//$total_count=$mCrudFunctions->get_count($table,$sql_);
		    $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' ".$sql );
		   }else{
		    $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no'  ".$sql);
		   }
			
		   }
		   }else{
		   //generalyes
		    $p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' ".$sql );
		   }else{
		    $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' ".$sql);
		   }
		   
		   }
		   }else{
		   //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' ".$sql );
		   }else{
		    $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'no' ".$sql);
		   }
		   }
		   }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
		   
		    if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' ".$sql);
		   }else{
		    $total_count=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) ='$gender' AND ".$column."  LIKE 'Yes' ".$sql);
		   }
		   
		   }
		   
		   }
		   }else{if($_POST['subcounty']=="all"){
		   
		    if($_POST['sel_production_id']=="all"){
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' ".$sql);
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
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ".$sql);
		    }	
			
		    }
		    }else{
		    //generalyes
			$p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ".$sql);
		    }	
		   
		    }
		    }else{
		    //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'no' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ".$sql);
		    }	
		    }
		    }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			
		    if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%'  AND ".$column." LIKE 'Yes' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ".$sql);
		    }	
		     
		   
		    }
            //////////////////////////////
			}
		   
		   
		   }else{if($_POST['parish']=="all"){
		    
			if($_POST['sel_production_id']=="all"){
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' ".$sql);
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
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ".$sql);
		    }
			
		    }
		    }else{
		    //generalyes
			$p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ".$sql);
		    }
		   
		    }
		    }else{
		    //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $p_id;
            $row =$mCrudFunctions->fetch_rows("production_data","columns","  id='$p_id'  ");
			//print_r($row);
            $column =$row[0]['columns'];
			
			
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'no' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ".$sql);
		    }
			
		    }
		    }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND ".$column." LIKE 'Yes' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ".$sql);
		    }
		   
		    }
            //////////////////////////////
	
			}
		   
		   
	
		   }else{if($_POST['village']=="all"){
		   
		    if($_POST['sel_production_id']=="all"){
			
			if($_POST['gender']=="all"){
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' ".$sql);
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
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ".$sql);
		    }
			
		    }
		    }else{
		    //generalyes
		    $p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
			
			 if($_POST['gender']=="all"){
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ".$sql);
		    }
		    }
		    }else{
		    //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
			 if($_POST['gender']=="all"){
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'no' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ".$sql);
		    }
			
		    }
		    }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
		     
			 if($_POST['gender']=="all"){
		     $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  AND ".$column." LIKE 'Yes' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ".$sql);
		    }
		   
		   
		    }
            //////////////////////////////			
			}		   
		   }else{
		   if($_POST['sel_production_id']=="all"){
		    if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' ".$sql);
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
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ".$sql);
		    }
			
		    }
		    }else{
		    //generalyes
			$p_id=str_replace("generalyes","",$sel_production_id);
            $row =$mCrudFunctions->fetch_rows("general_questions","columns"," id='$p_id' "  );
            $column =$row[0]['columns'];
		    if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ".$sql);
		    }
			
		    }
		    }else{
		    //productionno
		    $p_id=str_replace("productionno","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
		    
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'no' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'no' ".$sql);
		    }
		   
		    }
		    }else{
		    //productionyes
		    $p_id=str_replace("productionyes","",$sel_production_id);
            //echo $string;
            $row =$mCrudFunctions->fetch_rows("production_data","columns"," id='$p_id'  ");
            $column =$row[0]['columns'];
		    
			if($_POST['gender']=="all"){
		    $total_count=$mCrudFunctions->get_count($table," biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%' AND ".$column." LIKE 'Yes' ".$sql);
		    }else{
		    $total_count=$mCrudFunctions->get_count($table,"  biodata_farmer_location_farmer_district LIKE '%$district%' AND biodata_farmer_location_farmer_subcounty LIKE '%$subcounty%' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND biodata_farmer_location_farmer_village LIKE '%$village%'  AND lower(biodata_farmer_gender) ='$gender' AND ".$column." LIKE 'Yes' ".$sql);
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