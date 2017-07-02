<!DOCTYPE html>
<?php
#includes
    require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
    require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
    require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";
?>
<html>
<head>
	
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/material.min.css">

	<style type="text/css" media="print">
	@media all {
	.page-break	{ display: none; }
	}

	@media print {
	.page-break	{ display: block; page-break-before: always; }
		}
		   
table th,.table td{
	border-top: none !important;
}

.personal, h6, p
{
	display: inline-block;

}
table
{		/*   top  
          right*/
          margin-left: 45px;
          margin-right: auto;
	
}

tr, td
{
  width: 15%;
  text-align: justify;
}

thead,th
{
  color: #009688;
}

.container {
         height:auto;
          width:210mm;
        /* to centre page on screen*/
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 15px;

    }
@page {
    size: 21cm 29.7cm;
    margin: 30mm 45mm 30mm 45mm; /* change the margins as you want them to be. */
}

body
{

background-color: #f5f5f5;
text-align: justify;
}
.navbar{
background-color:#fff !important;
position:fixed;
top:0;
width:100%;
padding:10px; !important;
height:60px;
z-index:3;
box-shadow:0 0 10px rgba(0,0,0,0.4)

}
.navbar a{
height:100%;
margin:2px !important;

cursor:pointer;}

a.back{
 text-decoration:none !important;
 
}
a.back:hover{
background:#eee;
}
a.right{
float:right;}

i.small{
font-size:1em;
vertical-align:middle;
margin-right:20px;
margin-bottom:5px;}

p.center{
width:auto !important;
margin:0 25%;
}
	</style>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<div class="navbar">
<div class="container">
 
 <p class="center">
 <a onclick="BackTo();" class="back btn btn-danger">&laquo; Back</a>
 <a onclick="Print();" class="btn btn-success"><i class="material-icons small">add</i>Print</a>
 </p>
 </div>
</div>
<div id="content" class="container" style="margin-top:45px; background:#fff;">
<?php 
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


///////////////////////////////////////districts
if($_POST['district']=="all"){
$table="dataset_".$_POST['id'];
$rows=array();

if($_POST['production']=="all"){

if($_POST['gender']=="all"){
$rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  1 GROUP BY  district ORDER BY frequency Desc "  );

}else{
$gender=$_POST['gender'];
$rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
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
   $production_header= str_replace("general_questions","",$column)."(no)";
  if($_POST['gender']=="all"){
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  ".$column." LIKE 'no'  GROUP BY district  ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","   lower(biodata_farmer_gender) = '$gender'  AND ".$column." LIKE 'no'  GROUP BY district ORDER BY frequency Desc "  );
 
  
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
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  ".$column." LIKE 'Yes'  GROUP BY district  ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'Yes'  GROUP BY district  ORDER BY frequency Desc "  );
  
  
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
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  ".$column." LIKE 'no'  GROUP BY district ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND   ".$column." LIKE 'no'  GROUP BY district  ORDER BY frequency Desc"  );
  
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
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  ".$column." LIKE 'Yes'  GROUP BY district  ORDER BY frequency Desc"  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ","  lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'Yes'  GROUP BY district  ORDER BY frequency Desc "  );
  
  
  }
}


}

$production_header=$util_obj->captalizeEachWord($production_header);
 echo"<div class=\"row\">";
echo"<h3 class=\"text-center\">Dataset: &nbsp;<em>$dataset</em></h3>"; 
echo"<h4 class=\"text-center\"><strong>District:</strong> <em>$district</em> &nbsp;<strong>Subcounty:</strong> <em>$county</em>&nbsp;<strong>
Parish:</strong> <em>$parish</em> <strong>Village:</strong> <em>$village</em></h4>";
echo "<h4 class=\"text-center\"><strong>Production Data:</strong> <em>$production_header</em></h4>";

echo "<h4 class=\"text-center\"><strong>Gender:</strong> <em>$gender</em></h4>";

echo"</div>";
echo '<hr>'; 
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

echo"<div class=\"row\">
<div class=\"col-sm-12 col-md-12 col-lg-12\">
<h4  class=\"text-center\" >Number of farmers per District</h4>
<div   class=\"table-responsive\">";

echo'<table  class="">
<thead>
<tr class="">
	<th>No. &nbsp;&nbsp;</th>
	<th>District &nbsp;&nbsp;</th>
	<th>Frequency &nbsp;&nbsp;</th>
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
	
	echo "<tr class=\"\">
	<td>$i&nbsp;&nbsp; </td>
	<td>$district &nbsp;&nbsp; </td>
	<td>$frequency &nbsp;&nbsp; </td>
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
}else///////////////////////////////////////////subcounties
if($_POST['country']=="all"){

$table="dataset_".$_POST['id'];
$district=$_POST['district'];
$rows=array();
if($_POST['production']=="all"){

if($_POST['gender']=="all"){

$rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
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
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency "," ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc "  );
  
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table," TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
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
 echo"<div class=\"row\">";
echo"<h3 class=\"text-center\">Dataset: &nbsp;<em>$dataset</em></h3>"; 
echo"<h4 class=\"text-center\"><strong>District:</strong> <em>$district</em> &nbsp;<strong>Subcounty:</strong> <em>$county</em>&nbsp;<strong>
Parish:</strong> <em>$parish</em> <strong>Village:</strong> <em>$village</em></h4>";
echo "<h4 class=\"text-center\"><strong>Production Data:</strong> <em>$production_header</em></h4>";

echo "<h4 class=\"text-center\"><strong>Gender:</strong> <em>$gender</em></h4>";

echo"</div>";
echo '<hr>'; 
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

echo"<div class=\"row\">
<div class=\"col-sm-12 col-md-12 col-lg-12\">
<h4  class=\"text-center\" >Number of farmers per Subcounty</h4>
<div   class=\"table-responsive\">";

echo'<table  class="">
<thead>
<tr class="">
	<th>No. &nbsp;&nbsp;</th>
	<th>Subcounty &nbsp;&nbsp;</th>
	<th>Frequency &nbsp;&nbsp;</th>
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
	
	$i++;
	}
	
	$i=1;
	foreach($data as $final){
	$subcounty=$final['subcounty'];
	$frequency=$final['freq'];
	$percentage= ($frequency/$total)*100;
	$percentage= round($percentage,2);
	echo "<tr class=\"\">
	<td>$i&nbsp;&nbsp; </td>
	<td>$subcounty &nbsp;&nbsp; </td>
	<td>$frequency &nbsp;&nbsp; </td>
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
   COUNT(biodata_farmer_location_farmer_parish)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column." LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc "  );
   
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
 echo"<div class=\"row\">";
echo"<h3 class=\"text-center\">Dataset: &nbsp;<em>$dataset</em></h3>"; 
echo"<h4 class=\"text-center\"><strong>District:</strong> <em>$district</em> &nbsp;<strong>Subcounty:</strong> <em>$county</em>&nbsp;<strong>
Parish:</strong> <em>$parish</em> <strong>Village:</strong> <em>$village</em></h4>";
echo "<h4 class=\"text-center\"><strong>Production Data:</strong> <em>$production_header</em></h4>";

echo "<h4 class=\"text-center\"><strong>Gender:</strong> <em>$gender</em></h4>";

echo"</div>";
echo '<hr>'; 
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

echo"<div class=\"row\">
<div class=\"col-sm-12 col-md-12 col-lg-12\">
<h4  class=\"text-center\" >Number of farmers per Parish</h4>
<div   class=\"table-responsive\">";

echo'<table  class="">
<thead>
<tr class="">
	<th>No. &nbsp;&nbsp;</th>
	<th>Parish &nbsp;&nbsp;</th>
	<th>Frequency &nbsp;&nbsp;</th>
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
	echo "<tr class=\"\">
	<td>$i&nbsp;&nbsp; </td>
	<td>$parish &nbsp;&nbsp; </td>
	<td>$frequency &nbsp;&nbsp; </td>
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

///////////////////////////////////////////village

if($_POST['village']=="all"){

$table="dataset_".$_POST['id'];
$parish=$_POST['parish'];
$subcounty=$_POST['country'];

$rows=array();
if($_POST['production']=="all"){
  if($_POST['gender']=="all"){
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ","  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  "  );
  //print_r($rows);
  }else{
  $gender=$_POST['gender'];
  
  $rows =$mCrudFunctions->fetch_rows($table,"TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
  
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
   COUNT(biodata_farmer_location_farmer_village)  as frequency "," lower(biodata_farmer_gender) = '$gender'  AND  ".$column."  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc "  );
   
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
 echo"<div class=\"row\">";
echo"<h3 class=\"text-center\">Dataset: &nbsp;<em>$dataset</em></h3>"; 
echo"<h4 class=\"text-center\"><strong>District:</strong> <em>$district</em> &nbsp;<strong>Subcounty:</strong> <em>$county</em>&nbsp;<strong>
Parish:</strong> <em>$parish</em> <strong>Village:</strong> <em>$village</em></h4>";
echo "<h4 class=\"text-center\"><strong>Production Data:</strong> <em>$production_header</em></h4>";

echo "<h4 class=\"text-center\"><strong>Gender:</strong> <em>$gender</em></h4>";

echo"</div>";
echo '<hr>'; 
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

echo"<div class=\"row\">
<div class=\"col-sm-12 col-md-12 col-lg-12\">
<h4  class=\"text-center\" >Number of farmers per Village</h4>
<div   class=\"table-responsive\">";

echo'<table  class="">
<thead>
<tr class="">
	<th>No. &nbsp;&nbsp;</th>
	<th>Village &nbsp;&nbsp;</th>
	<th>Frequency &nbsp;&nbsp;</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody >';
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
	echo "<tr class=\"\">
	<td>$i&nbsp;&nbsp; </td>
	<td>$village_h&nbsp;&nbsp; </td>
	<td>$frequency &nbsp;&nbsp; </td>
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
   COUNT(biodata_farmer_location_farmer_village)  as frequency ","  ".$column." LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY  village ORDER BY frequency Desc "  );
   
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
 echo"<div class=\"row\">";
echo"<h3 class=\"text-center\">Dataset: &nbsp;<em>$dataset</em></h3>"; 
echo"<h4 class=\"text-center\"><strong>District:</strong> <em>$district</em> &nbsp;<strong>Subcounty:</strong> <em>$county</em>&nbsp;<strong>
Parish:</strong> <em>$parish</em> <strong>Village:</strong> <em>$village</em></h4>";
echo "<h4 class=\"text-center\"><strong>Production Data:</strong> <em>$production_header</em></h4>";

echo "<h4 class=\"text-center\"><strong>Gender:</strong> <em>$gender</em></h4>";

echo"</div>";
echo '<hr>'; 
  
if(sizeof($rows)>0){
$total=0;
for($i=0;$i<sizeof($rows); $i++ ){
$total=$total+$rows[$i]['frequency'];
}

echo"<div class=\"row\">
<div class=\"col-sm-12 col-md-12 col-lg-12\">
<h4  class=\"text-center\" >Number of farmers in $village</h4>
<div   class=\"table-responsive\">";

echo'<table  class="">
<thead>
<tr class="">
	<th>No. &nbsp;&nbsp;</th>
	<th>Village &nbsp;&nbsp;</th>
	<th>Frequency &nbsp;&nbsp;</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody >';
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
	echo "<tr class=\"\">
	<td>$i&nbsp;&nbsp; </td>
	<td>$village_h&nbsp;&nbsp; </td>
	<td>$frequency &nbsp;&nbsp; </td>
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










}
/*

echo"<div class=\"row\">
<div class=\"col-sm-12 col-md-12 col-lg-12\">
<h4 class=\"text-center\">Number of farmers per sub-county</h4>
<div class=\"table-responsive\">
<table class=\"table\">
<thead>
<tr class=\"\">
	<th>Number</th>
	<th>Region</th>
	<th>District</th>
	<th>Sub-county</th>
	<th>Parish</th>
	<th>Village</th>
	<th>Frequency</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody>";

	for($i =0; $i<=15; $i++)
	{
	echo '<tr>
	<td>1</td>
	<td>East</td>
	<td>Mbale</td>
	<td>Buyaga</td>
	<td>idungu</>
	<td>Kuma</td>
	<td>100</td>
	<td>10</td>
	</tr>';
	}

echo"
</tbody>
</table>
</div>*/;
echo"
</div>
</div>";
}
?>
</div><!-- end of main container-->
</body>
</html>
<script type="text/javascript">
function Print(){

	var restorepage = document.body.innerHTML;
	var printcontent = document.getElementById("content").innerHTML;
	document.body.innerHTML = printcontent;
	window.print();
	document.body.innerHTML = restorepage;
}

function BackTo(){
window.history.back();
}

</script>