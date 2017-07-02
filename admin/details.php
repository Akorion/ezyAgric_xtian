<div class="container-fluid">
<div class="row">
<?php include("../include/sidebar.php");?>
<?php
 #includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();?>
<!--end of sidenav-->
<!-- main content area-->
<div class="col-sm-12 col-md-9 col-lg-9">
<legend><h3 class="text-center" style="color:#e91e63;"><?php if(isset($_GET['type'])&&$_GET['type']!=""){
if($_GET['type']=="Farmer"){ echo "Farmer details";}
if($_GET['type']=="VA"){ echo "VA details";}
}?>
</h3></legend>
<!--map row-->
<div class="row">
<div class="col-sm-12 col-md-12 col-lg-12">
<div class="">
            <div class="card-image">
              <div class="" id="map-canvas"></div><!--map canvas-->
            </div>
  </div>
</div>
</div>

<?php

if(isset($_GET['token'])&&$_GET['token']!=""&&isset($_GET['s'])&&$_GET['s']!=""
   &&isset($_GET['type'])&&$_GET['type']!=""){
 $type=$_GET['type'];
$dataset_id=$util_obj->encrypt_decrypt("decrypt", $_GET['s']);
$id=$util_obj->encrypt_decrypt("decrypt", $_GET['token']);
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

///////////////////////////////////////////// lat_long_pic starts
echo"<div class=\"row\">
<input type=\"hidden\" id=\"latitude\" value=\"$latitude\" />
<input type=\"hidden\" id=\"longitude\" value=\"$longitude\" />
<div class=\"col-sm-4 col-md-4 col-lg-4\">
 <span ><img src=\"$picture\" class=\"on_map\"></span>
</div>

<div class=\"col-sm-8 col-md-8 col-lg-8\">
<h6>GPs location:</h6>
<p>$latitude , $longitude</p>
</div>
  
</div>";

///////////////////////////////////////////// lat_long_pic ends

/////////////////////////////////////////////personal data_starts
echo"<div class=\"row down \">
	<div class=\"col-sm-12 col-md-4 col-lg-4 \">
  <legend><h4>Personal profile</h4></legend>
  <div class=\"card\" id=\"\">
  <div class=\"caption\">
   &nbsp; &nbsp;&nbsp;<h6>Name:</h6>
  &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;<p>$name</p><br/>
  &nbsp; &nbsp;&nbsp;<h6>Sex:</h6>
  &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;<p>$gender</p><br/>
  &nbsp; &nbsp;&nbsp;<h6>Phone</h6>
  &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;<p>$phone</p><br/>
      </div>
    </div>
  </div>";
 /////////////////////////////////////////////personal data_ends
}

if($type=="Farmer"){

$latitude=$util_obj->remove_apostrophes($rows[0]['biodata_farmer_location_farmer_home_gps_Latitude']);
$longitude=$util_obj->remove_apostrophes($rows[0]['biodata_farmer_location_farmer_home_gps_Longitude']);
$picture=$util_obj->remove_apostrophes($rows[0]['biodata_farmer_picture']);
///////////////////////////////////////////// lat_long_pic starts
echo"<div class=\"row\">
<input type=\"hidden\" id=\"latitude\" value=\"$latitude\" />
<input type=\"hidden\" id=\"longitude\" value=\"$longitude\" />
<div class=\"col-sm-4 col-md-4 col-lg-4\">
 <span class=\"\" id=\"\"><img src=\"$picture\" class=\"on_map\"></span>
</div>

<div class=\"col-sm-8 col-md-8 col-lg-8\">
<h6>GPs location:</h6>
<p>$latitude , $longitude</p>
</div>
</div>";
///////////////////////////////////////////// lat_long_pic ends
  $table="bio_data";		 
  $columns="*";
  $where=" dataset_id='$dataset_id' ";
  $rows2= $mCrudFunctions->fetch_rows($table,$columns,$where);
echo $dataset_id;
/////////////////////////////////////////////personal data_starts
  echo"<div class=\"row data1\">
<div class=\"col-sm-12 col-me-6 col-lg-6\">
<div class=\"\">
<h5 class=\"\" style=\"color:orange; font-size:18px;\">Personal Profile</h5>";

  
  foreach($rows2 as $row){
  $column=$row['columns'];
  $value=$rows[0][$column];
  $value=$util_obj->captalizeEachWord($value);
  $string=str_replace("biodata_farmer_","",$column);
  $string=str_replace("_"," ",$string);
  $lable=$util_obj->captalizeEachWord($string);
  if($lable!="Picture"){
  echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><br/>";
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
   echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><br/>";
  }
   
  
  }
  echo"</div>";
/////////////////////////////////////////////personal data_ends
  
 /////////////////////////////////////////////other data_starts 
  echo"<div class=\"\">
<h5 class=\"\" style=\"color:orange;font-size:18px;\">Others</h5>";
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
   echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><br/>";
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
   
   echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><br/>";
  }
  }
  
  echo"</div>
</div>
";
/////////////////////////////////////////////other data_ends

/////////////////////////////////////////////production data_starts
  echo"<div class=\"col-sm-12 col-md- col-lg-6\" style=\"margin-bottom:100px;\">
<div class=\"\">
<h5 class=\"\" style=\"color:orange;font-size:18px;\">Production Data</h5>";

 
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
   echo"<h6 class=\"trim\">$lable:</h6>
  <p>$value</p><br/>";
 
  }
  }
  
      echo"</div>
    </div>
  </div>";
/////////////////////////////////////////////production data_ends


}




}





}?>
  
</div><!--end of main content-->
<?php include("../include/footer_client.php"); ?>
<script>
      function initialize() {
	    lat_holder = document.getElementById('latitude');
		longi_holder = document.getElementById('longitude');
		
        var mapCanvas = document.getElementById('map-canvas');
        
		
		var mapOptions = {
          center: new google.maps.LatLng(lat_holder.value,longi_holder.value),
          zoom: 12,
            
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
		
		
        var map = new google.maps.Map(mapCanvas, mapOptions)
		
		var marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat_holder.value,longi_holder.value),
        map: map,
        title: ''
        });
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
