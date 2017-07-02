<div class="container-fluid">
<div class="row">
<?php include("../include/sidebar.php");?>
<!--end of sidenav-->
<!-- main content area-->
<div class="col-sm-12 col-md-9 col-lg-9">


<?php
 #includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
if(isset($_GET['token'])&&$_GET['token']!=""){
$id=$util_obj->encrypt_decrypt("decrypt", $_GET['token']);
$dataset_=$util_obj->encrypt_decrypt("encrypt", $id);
$dataset= $mCrudFunctions->fetch_rows("datasets_tb","*"," id =$id");
$dataset_name=$dataset[0]["dataset_name"];
$dataset_type=$dataset[0]["dataset_type"];
echo"<legend><h3 class=\"text-center\" style=\"color:#e91e63;\">$dataset_name</h3></legend>
<div class=\"row down\">";

$table="dataset_".$id;		 
$columns="*";
$where=" 1 ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if($dataset_type=="Farmer"){
if(sizeof($rows)==0){  
}else{
foreach($rows as $row ){
$real_id=$row['id'];

$real_id=$util_obj->encrypt_decrypt("encrypt", $real_id);
$name=$row['biodata_farmer_name'];
$gender=$row['biodata_farmer_gender'];
$dob=$row['biodata_farmer_dob'];
$picture=$row['biodata_farmer_picture'];
$district=$row['biodata_farmer_location_farmer_district'];
$phone_number=$row['biodata_farmer_phone_number'];
echo "
  <div class=\"col-sm-12 col-md-3 col-lg-3\">
  <div class=\"thumbnail card\" id=\"card1\">
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
  <p><a href=\"?action=details&s=$dataset_&token=$real_id&type=$dataset_type\" class=\"btn btn-raised\">View more &raquo;</a></p>
      </div>
    </div>
  </div>";}
  
  }}
}
if($dataset_type=="VA"){
if(sizeof($rows)==0){  
}else{
foreach($rows as $row ){
$real_id=$row['id'];

$real_id=$util_obj->encrypt_decrypt("encrypt", $real_id);
$name=$row['name'];
$gender=$row['gender'];
$dob='';//$row['biodata_farmer_dob'];
$picture=$row['picture'];
$district='';//$row['biodata_farmer_location_farmer_district'];
$phone_number=$row['va_phonenumber'];
echo "
  <div class=\"col-sm-12 col-md-3 col-lg-3\">
  <div class=\"thumbnail card\" id=\"card1\">
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
  <p><a href=\"?action=details&s=$dataset_&token=$real_id&type=$dataset_type\" class=\"btn btn-raised\">View more &raquo;</a></p>
      </div>
    </div>
  </div>";}
  
  }}




?>
</div><!--end of row-->



</div><!--end of main content-->
</div>
<!--end of main container-->