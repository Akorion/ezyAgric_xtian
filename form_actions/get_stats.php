

    
<?php
/*
<!--<table id="fixed-header">
    </table>-->
*/

#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";;

//id: id, district: district,country :country,parish : parish , village : village
if(isset($_POST['id'])&&isset($_POST['district'])
  &&isset($_POST['country'])&&isset($_POST['parish'])
  &&isset($_POST['village'])&&isset($_POST['production'])){
 $mCrudFunctions = new CrudFunctions();
 $util_obj= new Utilties();

  $district=$_POST['district'];
  $subcounty=$_POST['country'];
  $parish=$_POST['parish'];
  
  
  $gender_males=0;
  $gender_females=0;
  
  $production_yes=0;
  $production_no=0;
  
  
  
///////////////////////////////////////districts
if($_POST['district']=="all"){
$table="dataset_".$_POST['id'];
$rows=array();

if($_POST['production']=="all"){

if($_POST['gender']=="all"){
$rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  1 GROUP BY district ORDER BY frequency Desc "  );
 
 $total_males=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = 'male'");
 $total_females=$mCrudFunctions->get_count($table, " lower(biodata_farmer_gender) = 'female'");
 $ttl=$total_males+$total_females;
 $gender_males=round(($total_males/$ttl)*100,1);
 $gender_females=round(($total_females/$ttl)*100,1);
 
}else{
$gender=$_POST['gender'];

if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
}else{
  $gender_males=0;
  $gender_females=100;
}

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
  
  
  $total_yes=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes'");
  $total_no=$mCrudFunctions->get_count($table," ".$column." LIKE 'no'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
  $total_males=$mCrudFunctions->get_count($table,"lower(biodata_farmer_gender) = 'male' AND  ".$column." LIKE 'no'");
  $total_females=$mCrudFunctions->get_count($table,"lower(biodata_farmer_gender) = 'female' AND  ".$column." LIKE 'no'");
  $ttl=$total_males+$total_females;
 $gender_males=round(($total_males/$ttl)*100,1);
 $gender_females=round(($total_females/$ttl)*100,1);
  
  
  }else{
  $gender=$_POST['gender'];
  
  if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
}else{
  $gender_males=0;
  $gender_females=100;
}
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","   lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'no'  GROUP BY district  ORDER BY frequency Desc "  );
 
  $total_yes=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes'");
  $total_no=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
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
  
  $total_yes=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes'");
  $total_no=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
  
   $total_males=$mCrudFunctions->get_count($table,"lower(biodata_farmer_gender) = 'male' AND  ".$column." LIKE 'Yes'");
   $total_females=$mCrudFunctions->get_count($table,"lower(biodata_farmer_gender) = 'female' AND  ".$column." LIKE 'Yes'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
  
  }else{
  $gender=$_POST['gender'];
  
  if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
 }else{
  $gender_males=0;
  $gender_females=100;
 }
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc "  );
  
  $total_yes=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes'");
  $total_no=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'no'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
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
  
  $total_yes=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes'");
  $total_no=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
   $total_males=$mCrudFunctions->get_count($table,"lower(biodata_farmer_gender) = 'male' AND  ".$column." LIKE 'no'");
   $total_females=$mCrudFunctions->get_count($table,"lower(biodata_farmer_gender) = 'female' AND  ".$column." LIKE 'no'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
  
  
  }else{
  $gender=$_POST['gender'];
  
  if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
  }else{
  $gender_males=0;
  $gender_females=100;
  }
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'no'  GROUP BY district  ORDER BY frequency Desc "  );
  
  $total_yes=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes'");
  $total_no=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'no'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
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
  
  $total_yes=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes'");
  $total_no=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
  $total_males=$mCrudFunctions->get_count($table,"lower(biodata_farmer_gender) = 'male' AND  ".$column." LIKE 'Yes'");
   $total_females=$mCrudFunctions->get_count($table,"lower(biodata_farmer_gender) = 'female' AND  ".$column." LIKE 'Yes'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
  
  }else{
  $gender=$_POST['gender'];
  if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
  }else{
  $gender_males=0;
  $gender_females=100;
  }
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc "  );
  
  $total_yes=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes'");
  $total_no=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'no'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
  }
}


}
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}


echo" <input id=\"production_yes\" type=\"hidden\" value=\"$production_yes\" />";
echo" <input id=\"production_no\" type=\"hidden\" value=\"$production_no\" />";

echo" <input id=\"gender_males\" type=\"hidden\" value=\"$gender_males\" />";
echo" <input id=\"gender_females\" type=\"hidden\" value=\"$gender_females\" />";
  
echo" <h4 style=\"text-align:center  ;\" class=\"vas\">Number Of Farmers Per District</h4><div class=\"table-responsive\">";

echo'<table  class="table hide">
<thead>
<tr class="">
	<th>Number</th>
	<th>District</th>
	<th>Frequency</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody >';
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
	echo "<tr >
	<td>$i</td>
	<td>$district</td>
	<td>$frequency</td>
	<td>$percentage</td>
	</tr>";
	$i++;
	
	}
	
	
	
	
echo "<tr class=\"\">
	<td></td>
	<td></td>
	<th>Total: $total</th>
	<td></td>
	</tr>";

echo'</tbody>
</table>
</div>';

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

  $total_males=$mCrudFunctions->get_count($table,"   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'male'");
 $total_females=$mCrudFunctions->get_count($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'female'");
 $ttl=$total_males+$total_females;
 $gender_males=round(($total_males/$ttl)*100,1);
 $gender_females=round(($total_females/$ttl)*100,1);
  
}else{
 $gender=$_POST['gender'];

  if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
  }else{
  $gender_males=0;
  $gender_females=100;
  }
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
  
  
  $total_yes=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
  $total_no=$mCrudFunctions->get_count($table,"   ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
 $total_males=$mCrudFunctions->get_count($table," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'male'");
 $total_females=$mCrudFunctions->get_count($table," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'female'");
 $ttl=$total_males+$total_females;
 $gender_males=round(($total_males/$ttl)*100,1);
 $gender_females=round(($total_females/$ttl)*100,1);
  
  }else{
  $gender=$_POST['gender'];
  
  if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
  }else{
  $gender_males=0;
  $gender_females=100;
  }
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ","   lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc "  );
  
  $total_yes=$mCrudFunctions->get_count($table,"   lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
  $total_no=$mCrudFunctions->get_count($table,"    lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
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
  
   $total_yes=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
  $total_no=$mCrudFunctions->get_count($table,"   ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
  $total_males=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'male'");
 $total_females=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'female'");
 $ttl=$total_males+$total_females;
 $gender_males=round(($total_males/$ttl)*100,1);
 $gender_females=round(($total_females/$ttl)*100,1);
  
  
  }else{
  $gender=$_POST['gender'];
  
  if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
  }else{
  $gender_males=0;
  $gender_females=100;
  }
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  
  $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
  $total_no=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
  $ttl_p=$total_yes+$total_no;
  
  $production_yes=round(($total_yes/$ttl_p)*100,1);
  $production_no=round(($total_no/$ttl_p)*100,1);
  
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
  
   $total_yes=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
   $total_no=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
  
  
   $total_males=$mCrudFunctions->get_count($table," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'male'");
   $total_females=$mCrudFunctions->get_count($table," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
  
  }else{
  $gender=$_POST['gender'];
  if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
  }else{
  $gender_males=0;
  $gender_females=100;
  }
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
   $total_yes=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
   $total_no=$mCrudFunctions->get_count($table,"   lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
  
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
  
   $total_yes=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
   $total_no=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
  
   $total_males=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'male'");
   $total_females=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
  
  }else{
  $gender=$_POST['gender'];
  if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
  }else{
  $gender_males=0;
  $gender_females=100;
  }
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc"  );
  
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
   $total_no=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
  
  }

}


}


  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

echo" <input id=\"production_yes\" type=\"hidden\" value=\"$production_yes\" />";
echo" <input id=\"production_no\" type=\"hidden\" value=\"$production_no\" />";

echo" <input id=\"gender_males\" type=\"hidden\" value=\"$gender_males\" />";
echo" <input id=\"gender_females\" type=\"hidden\" value=\"$gender_females\" />";

echo"<h4 style=\"text-align:center  ;\" class=\"vas\">Number Of Farmers Per Sub-County</h4>
<div  class=\"table-responsive\">";

echo'<table class="table hide">
<thead>
<tr >
	<th>Number</th>
	<th>Sub-county</th>
	<th>Frequency</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody >';
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
	echo "<tr>
	<td>$i</td>
	<td>$subcounty</td>
	<td>$frequency</td>
	<td>$percentage</td>
	</tr>";
	$i++;
	
	}
	
	
echo "<tr >
	<td></td>
	<td></td>
	<th>Total: $total</th>
	<td></td>
	</tr>";

echo'</tbody>
</table>
</div>';

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
 $total_males=$mCrudFunctions->get_count($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
}else{
$gender=$_POST['gender'];
if($gender=='male'){
  $gender_males=100;
  $gender_females=0;
  }else{
  $gender_males=0;
  $gender_females=100;
  }
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
   
   
   $total_yes=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $total_no=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   
   
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $total_no=$mCrudFunctions->get_count($table,"  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
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

   
   $total_yes=$mCrudFunctions->get_count($table,"".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $total_no=$mCrudFunctions->get_count($table," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );

   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
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
   
   
   $total_yes=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $total_no=$mCrudFunctions->get_count($table," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
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
   
   
   $total_yes=$mCrudFunctions->get_count($table," ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $total_no=$mCrudFunctions->get_count($table," ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   }
   
   
   }
}

  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

echo" <input id=\"production_yes\" type=\"hidden\" value=\"$production_yes\" />";
echo" <input id=\"production_no\" type=\"hidden\" value=\"$production_no\" />";

echo" <input id=\"gender_males\" type=\"hidden\" value=\"$gender_males\" />";
echo" <input id=\"gender_females\" type=\"hidden\" value=\"$gender_females\" />";
   
echo"<h4 style=\"text-align:center  ;\" class=\"vas\">Number of farmers per Parish</h4>
<div   class=\"table-responsive\">";

echo'<table  class="table hide">
<thead>
<tr >
	<th>Number</th>
	<th>Parish</th>
	<th>Frequency</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody >';
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
	echo "<tr >
	<td>$i</td>
	<td>$parish</td>
	<td>$frequency</td>
	<td>$percentage</td>
	</tr>";
	$i++;
	
	}
	
	
	
echo "<tr >
	<td></td>
	<td></td>
	<th>Total: $total</th>
	<td></td>
	</tr>";

echo'</tbody>
</table>';
echo'</div>';
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
  
   $total_males=$mCrudFunctions->get_count($table,"   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
  
  }else{
  $gender=$_POST['gender'];
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
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
   
   
   $total_yes=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $total_no=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"   ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
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
   
   
   $total_yes=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $total_no=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"   ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
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
   
   
   $total_yes=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $total_no=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"   ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
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
   
   
   $total_yes=$mCrudFunctions->get_count($table," ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $total_no=$mCrudFunctions->get_count($table," ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"   ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   }
   
   
   }
}

  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

echo" <input id=\"production_yes\" type=\"hidden\" value=\"$production_yes\" />";
echo" <input id=\"production_no\" type=\"hidden\" value=\"$production_no\" />";

echo" <input id=\"gender_males\" type=\"hidden\" value=\"$gender_males\" />";
echo" <input id=\"gender_females\" type=\"hidden\" value=\"$gender_females\" />";

echo"<h4 style=\"text-align:center ;\" class=\"vas\">Number of farmers per Village</h4>
<div   class=\"table-responsive\">";
//table
echo"<table  class=\"table hide \">
<thead>
<tr >
	<th>Number</th>
	<th>Village</th>
	<th>Frequency</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody >";
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
	echo "<tr >
	<td>$i</td>
	<td>$village_h</td>
	<td>$frequency</td>
	<td>$percentage</td>
	</tr>";
	
	$i++;
	
	}
	
	
echo "<tr >
	<td></td>
	<td></td>
	<th>Total: $total</th>
	<td></td>
	</tr>";
echo'</tbody>
</table>';
echo'</div>';

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
  
   $total_males=$mCrudFunctions->get_count($table,"  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
  
  }else{
  $gender=$_POST['gender'];
 
   if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
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
   
   $total_yes=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $total_no=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
    if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc "  );
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
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
   
   $total_yes=$mCrudFunctions->get_count($table,"".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $total_no=$mCrudFunctions->get_count($table," ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   }else{
   $gender=$_POST['gender'];
    if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc "  );
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
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
   
   
   $total_yes=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $total_no=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
    if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY  village ORDER BY frequency Desc "  );
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
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
   
   
   $total_yes=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $total_no=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   $total_males=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'male' ");
   $total_females=$mCrudFunctions->get_count($table,"  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = 'female'");
   $ttl=$total_males+$total_females;
   $gender_males=round(($total_males/$ttl)*100,1);
   $gender_females=round(($total_females/$ttl)*100,1);
   
   }else{
   $gender=$_POST['gender'];
    if($gender=='male'){
   $gender_males=100;
   $gender_females=0;
   }else{
   $gender_males=0;
   $gender_females=100;
   }
   $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc "  );
   
   
   $total_yes=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $total_no=$mCrudFunctions->get_count($table," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'");
   $ttl_p=$total_yes+$total_no;
  
   $production_yes=round(($total_yes/$ttl_p)*100,1);
   $production_no=round(($total_no/$ttl_p)*100,1);
   
   }
   
   }
}

  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

echo" <input id=\"production_yes\" type=\"hidden\" value=\"$production_yes\" />";
echo" <input id=\"production_no\" type=\"hidden\" value=\"$production_no\" />";

echo" <input id=\"gender_males\" type=\"hidden\" value=\"$gender_males\" />";
echo" <input id=\"gender_females\" type=\"hidden\" value=\"$gender_females\" />";

echo"<h4 style=\"text-align:center ;\" class=\"vas\">Number of farmers in $village</h4>
<div   class=\"table-responsive\">";

echo"<table  class=\"table hide\">
<thead>
<tr >
	<th>Number</th>
	<th>Village</th>
	<th>Frequency</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody >";
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
		//print_r($data);
		}else
		if(sizeof($data)>1){
		$data[$i]["freq"]=$info["freq"]+$frequency;
		//print_r($data);
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
	
	//print_r($data);
	}
	
	}
	
	
	$i=1;
	foreach($data as $final){
	$village_h=$final['village'];
	$frequency=$final['freq'];
	$percentage= ($frequency/$total)*100;
	$percentage= round($percentage,2);
	echo "<tr >
	<td>$i</td>
	<td>$village_h</td>
	<td>$frequency</td>
	<td>$percentage</td>
	</tr>";
	
	$i++;
	
	}
echo "<tr >
	<td></td>
	<td></td>
	<th>Total: $total</th>
	<td></td>
	</tr>";

echo'</tbody>
</table>';
echo'</div>';






}

}



/*
<!--
<script>
var tableOffset = $(".table").offset().top;
var $header = $(".table > thead").clone();
var $fixedHeader = $("#fixed-header").append($header);

$(window).bind("scroll", function() {
    var offset = $(this).scrollTop();

    if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
        $fixedHeader.show();
    }
    else if (offset < tableOffset) {
        $fixedHeader.hide();
    }
});</script>
*/

}



?>


