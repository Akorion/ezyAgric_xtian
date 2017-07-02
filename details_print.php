<!DOCTYPE html>
<?php
#includes
require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";

$mCrudFunctions = new CrudFunctions();
 $util_obj= new Utilties();
?>
<html>
<head>
	
	<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/material.min.css">


<style type="text/css">
  @media all {
  .page-break { display: none; }
  }

  @media print {
  .page-break { 
					display: block; page-break-before: always;
					}

   a[href]:after {
    content: none !important;
  }
  a {
        display:none;
    }
  
    }
       
table th,.table td,.btn{
  border-top: none !important;
}

.personal, h6, p
{
  display: inline-block;
  

}
table
{   /*   top  
          right*/
          margin-left: 45px;
          margin-right: auto;
          overflow:none;
          text-align:justify;
  

}

body
{
  background-color:#9E9E9E;
}

.container {
         height:auto;
          width:220mm;
        /* to centre page on screen*/
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 15px;
        margin-top: -35px;

    }
@page {
    size: 21cm 29.7cm;
   /* margin: 30mm 45mm 30mm 45mm;*/ /* change the margins as you want them to be. */
}


  </style>

</head>
<body>       

<div style="margin-left:0%; margin:15px auto; background-color:#BDBDBD;width:18%; padding-left:10px">
    <a onclick="BackTo();" class="btn btn-danger">Back &laquo;</a>
    <a onclick="Print();" class="btn btn-success">Print</a>

 
</div>
<div id="content" class="container" style="margin-top:45px; background:#fff;">
<?php

if(isset($_POST['id'])&&$_POST['id']!=""&&isset($_POST['type'])&&$_POST['type']!=""
   &&isset($_POST['dataset'])&&$_POST['dataset']!="" ){
   
   $picture =!empty($_POST['picture'])? (string)$_POST['picture'] : "";
  echo" 
<h3 class=\"text-center\">Personal Profile</h3>
<div class=\"row\">
<div class=\"col-md-12\">
<img src=\"$picture\" class=\"img-responsive\" style=\"margin-left:27%; height:250px; width:250px;\">
</div>
</div>";
$dataset_id=$_POST['dataset'];
$id=$_POST['id'];
$type=$_POST['type'];

$table="dataset_".$dataset_id;		 
$columns="*";
$where=" id='$id' ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);

if(sizeof($rows)==0){ 

}else
{

$id=$rows[0]['id'];

if($type=="VA"){

$latitude=$util_obj->remove_apostrophes($rows[0]['va_home_location_Latitude']);
$longitude=$util_obj->remove_apostrophes($rows[0]['va_home_location_Longitude']);
$picture=$util_obj->remove_apostrophes($rows[0]['picture']);
$gender=$util_obj->captalizeEachWord($rows[0]['gender']);
$name=$util_obj->captalizeEachWord($rows[0]['name']);
$phone=$util_obj->remove_apostrophes($rows[0]['va_phonenumber']);

}

if($type=="Farmer"){
$latitude=$util_obj->remove_apostrophes($rows[0]['biodata_farmer_location_farmer_home_gps_Latitude']);
$longitude=$util_obj->remove_apostrophes($rows[0]['biodata_farmer_location_farmer_home_gps_Longitude']);
$picture=$util_obj->remove_apostrophes($rows[0]['biodata_farmer_picture']);

 $table="bio_data";		 
 $columns="*";
 $where=" dataset_id='$dataset_id' ";
 $rows2= $mCrudFunctions->fetch_rows($table,$columns,$where);
 
 echo"<div class=\"row\" style=\"margin-top:15px;\">
 <div class=\"col-md-12\">
 <table class=\"table\">
 ";
  foreach($rows2 as $row){
  $column=$row['columns'];
  $value=$rows[0][$column];
  $value=$util_obj->captalizeEachWord($value);
  $string=str_replace("biodata_farmer_","",$column);
  $string=str_replace("_"," ",$string);
  $lable=$util_obj->captalizeEachWord($string);
  if($lable!="Picture"){
  echo"<tr><th>$lable:</th>
  <td>$value</td></tr>";
  }
  
  }
  
  $table="farmer_location";		 
  $columns="*";
  $where=" dataset_id='$dataset_id' ";
  $rows3= $mCrudFunctions->fetch_rows($table,$columns,$where);
  
  foreach($rows3 as $row){
   $column=$row['columns'];
  $value=$rows[0][$column];
  $value=$util_obj->captalizeEachWord($value);
  $string=str_replace("biodata_farmer_location_farmer_","",$column);
  $string=str_replace("_"," ",$string);
  $lable=$util_obj->captalizeEachWord($string);
  if(!strpos($column,'_gps_')){
   echo"<tr><th>$lable:</th>
  <td>$value</td></tr>";
  }
     
  }
  
  echo"</table>
</div>
</div>";
echo"
<div class=\"page-break\"></div>";
echo"<div class=\"row\">
<div class=\"col-md-12\">
<h3 class=\"text-center\">Production data</h3>
<table class=\"table\">";

  $table="production_data";		 
  $columns="*";
  $where=" dataset_id='$dataset_id' ";
  $rows4= $mCrudFunctions->fetch_rows($table,$columns,$where);
  
  foreach($rows4 as $row){
   $column=$row['columns'];
   $enterprise=$row['enterprise'];
  $value=$rows[0][$column];
   $value=$util_obj->captalizeEachWord($value);
  $string=$enterprise."_production_data_";
   $string=str_replace($string,"",$column);
  $string=str_replace("_"," ",$string);
  $lable=$util_obj->captalizeEachWord($string);
  if($value!=null){
   echo"<tr><th>$lable:</th>
  <td>$value</td></tr>";
 
  }
  }
  

 echo"</table>
</div>
</div>";

echo"
<div class=\"page-break\"></div>
<div class=\"col-md-12\">
<h3 class=\"text-center\">Others</h3>
<div class=\"table-responsive\">
<table class=\"table\" style=\"margin-left:15px; width:100%;\">";

$table="info_on_other_enterprise";		 
  $columns="*";
  $where=" dataset_id='$dataset_id' ";
  $rows5= $mCrudFunctions->fetch_rows($table,$columns,$where);
  
  foreach($rows5 as $row){
   $column=$row['columns'];
  $value=$rows[0][$column];
  $value=$util_obj->captalizeEachWord($value);
  $string="information_on_other_crops_";
   $string=str_replace($string,"",$column);
  $string=str_replace("_"," ",$string);
  $lable=$util_obj->captalizeEachWord($string);
  if($value!=null){
   echo"<tr><th>$lable:</th>
  <td>$value</td></tr>";
  }
  }
  
  
  $table="general_questions";		 
  $columns="*";
  $where=" dataset_id='$dataset_id' ";
  $rows6= $mCrudFunctions->fetch_rows($table,$columns,$where);
  
  foreach($rows6 as $row){
   $column=$row['columns'];
   
  $value=$rows[0][$column];
   $value=$util_obj->captalizeEachWord($value);
  $string="general_questions_";
   $string=str_replace($string,"",$column);
  $string=str_replace("_"," ",$string);
  $lable=$util_obj->captalizeEachWord($string);
  if($value!=null){
  
   echo"<tr><th>$lable:</th>
  <td>$value</td></tr>";
  }
  }
  
 echo"</table>";
 echo "<div class=\"row\">";
          echo "
            <div class=\"col-sm-8\">
            <p class=\"lead\">Generated From EZY AGRIC </p>
            </div>

            <div class=\"col-sm-4 pull-right\">
                <p class=\"lead\">www.akorion.com</p>
            </div>
          ";
 echo "</div>";

 echo "</div></div>";

}


}
}?>

</div>
</div>
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