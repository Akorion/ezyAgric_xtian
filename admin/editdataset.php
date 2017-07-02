<div class="container-fluid">
<div class="row">
<?php include("../include/sidebar.php");?>
<!--end of sidenav-->
<!-- main content area-->
<div class="col-sm-12 col-md-9 col-lg-9">
<div class="row dash">
<h3 class="" style="color:#e91e63; padding-left:85px;">Edit Dataset</h3>
<hr/>
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
$table="datasets_tb";		 
$columns="*";
$where=" id='$id' ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if(sizeof($rows)==0){ 
}else{
$id=$rows[0]['id'];
$dataset_name=$rows[0]['dataset_name'];
$dataset_type=$rows[0]['dataset_type'];
$region=$rows[0]['region'];
$start_date=$rows[0]['start_date'];
$end_date =$rows[0]['end_date'];

echo"<div class=\"col-sm-12 col-md-10 col-lg-10\" style=\"margin-top:15px;\">
	<form class=\"form-horizontal\" method=\"post\" action=\"..\\form_actions\\edit_dataset.php\">
	<div class=\"form-group\">
	<input type=\"hidden\" class=\"form-control\" name=\"id\" value=\"$id\">
    <label for=\"fname\" class=\"col-sm-3 control-label\">Dataset Name:</label>
    <div class=\"col-sm-12 col-md-7 col-lg-7\">
      <input type=\"text\" class=\"form-control\" name=\"dname\" id=\"dname\" value=\"$dataset_name\">
    </div>
  </div>
  <div class=\"form-group\">
    <label for=\"dtype\" class=\"col-sm-3 control-label\">Dataset Type:</label>
    <div class=\"col-sm-12 col-md-7 col-lg-7\">
     <select name=\"dtype\" class=\"form-control\">
  	<option value=\"$dataset_type\">$dataset_type</option>
  	<option value=\"Farmer\">Farmer</option>
  	<option value=\"VA\">VA</option>
	</select>
    </div>
  </div>
  <div class=\"form-group\">
    <label for=\"dtype\" class=\"col-sm-3 control-label\">Region:</label>
    <div class=\"col-sm-12 col-md-7 col-lg-7\">
     <select name=\"dregion\" class=\"form-control\">
  	<option value=\"$region\">$region</option>
	<option value=\"Eastern Region\">Eastern Region</option>
  	<option value=\"Central Region\">Central Region</option>
  	<option value=\"Western Region\">Western Region</option>
  	<option value=\"Northern Region\">Northern Region</option>
	</select>
    </div>
  </div>

  <div class=\"form-group\">
    <label for=\"fname\" class=\"col-sm-3 control-label\">From:</label>
    <div class=\"col-sm-12 col-md-7 col-lg-7\">
   <input id=\"my-date-input6\" type=\"\"  name=\"start\" class=\"form-control\" value=\"$start_date\">
    </div>
  </div>

   <div class=\"form-group\">
    <label for=\"fname\" class=\"col-sm-3 control-label\">To:</label>
    <div class=\"col-sm-12 col-md-7 col-lg-7\">
   		<input id=\"my-date-input5\" type=\"\" name=\"end\" class=\"form-control\" value=\"$end_date\">
    </div>
  </div>   
<div class=\"form-group\">
    <div class=\"col-sm-12 col-md-4 col-lg-4 center\">
      <input type=\"submit\" name=\"submit\" value=\"Save changes\" class=\"btn btn-large btn-primary btn-block\">
    </div>
  </div>
  </form>
  <div class=\"form-group\">
    <div class=\"col-sm-12 col-md-4 col-lg-4 center\">
      <a type=\"submit\" href=\"../admin/home.php?action=managedataset\" class=\"btn btn-small btn-warning btn-block\">Back</a>
    </div>
  </div>
	
</div>
</div>";

}}
?>
<!--end of main content-->

</div>
</div>
<!--end of main container-->
