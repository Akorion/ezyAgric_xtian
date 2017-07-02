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
$mCrudFunctions = new CrudFunctions();
?>
<!--end of sidenav-->
<!-- main content area-->

<?php

if(isset($_GET['token'])&&$_GET['token']!=""){
$id=$util_obj->encrypt_decrypt("decrypt", $_GET['token']);
$table="client_accounts_tb";		 
$columns="*";
$where=" id='$id' ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if(sizeof($rows)==0){ 
}else{
$id=$rows[0]['id'];
$c_name=$rows[0]['c_name'];
$c_address=$rows[0]['c_address'];
$c_email=$rows[0]['c_email'];
$c_value_chain=$rows[0]['c_value_chain'];
$contact_name=$rows[0]['contact_name'];
$contact_number=$rows[0]['contact_number'];
$contact_email=$rows[0]['contact_email'];
$contact_loc=$rows[0]['contact_loc'];

echo"<form id=\"form_1\" method=\"post\" action=\"..\\form_actions\\edit_account.php\" >";
echo"
<div class=\"col-sm-12 col-md-9 col-lg-9\">
<div class=\"row\">
<h3 class=\"\" style=\"color:#e91e63; padding-left:85px;\">Edit Account</h3>
<hr/>
<div class=\"col-sm-12 col-md-6 col-lg-6\" style=\"margin-top:15px;\">
<h4 class=\"text-center\">Client information</h4>
  <div class=\"form-horizontal\">

  <input name=\"id\" type=\"hidden\" class=\"form-control\" id=\"id\" value=\"$id\">
  <div class=\"form-group\">
    <label for=\"fname\" class=\"col-sm-3 control-label\">Full Name:</label>
    <div class=\"col-sm-12 col-md-6 col-lg-6\">
      <input name=\"c_name\" type=\"text\" class=\"form-control\" id=\"fname\" value=\"$c_name\">
    </div>
  </div>
  <div class=\"form-group\">
    <label for=\"address\" class=\"col-sm-3 control-label\">Physical address:</label>
    <div class=\"col-sm-12 col-md-6 col-lg-6\">
      <input  name=\"c_address\" type=\"text\" class=\"form-control\" id=\"addr\" value=\"$c_address\">
    </div>
  </div>

  <div class=\"form-group\">
    <label for=\"email\" class=\"col-sm-3 control-label\">Email address:</label>
    <div class=\"col-sm-12 col-md-6 col-lg-6\">
      <input  name=\"c_email\" type=\"email\" class=\"form-control\" id=\"eaddr\" value=\"$c_email\">
    </div>
  </div>

  <div class=\"form-group\">
    <label for=\"fname\" class=\"col-sm-3 control-label\">Value Chain:</label>
    <div class=\"col-sm-12 col-md-6 col-lg-6\">
      <input  name=\"c_value_chain\" type=\"text\" class=\"form-control\" id=\"eaddr\" value=\"$c_value_chain\">
    </div>
  </div>
  </div>
</div>

<div class=\"col-sm-12 col-md-6 col-lg-6\" style=\"margin-top:15px;\">
<h4 class=\"text-center\">Contact Personel information</h4>
  <div class=\"form-horizontal\">
  <div class=\"form-group\">
    <label for=\"fname\" class=\"col-sm-3 control-label\">Full Name:</label>
    <div class=\"col-sm-12 col-md-6 col-lg-6\">
      <input  name=\"contact_name\" type=\"text\" class=\"form-control\" id=\"eaddr\" value=\"$contact_name\">
    </div>
  </div>
  <div class=\"form-group\">
    <label for=\"address\" class=\"col-sm-3 control-label\">Number:</label>
    <div class=\"col-sm-12 col-md-6 col-lg-6\">
      <input  name=\"contact_number\" type=\"phone\" class=\"form-control\" id=\"eaddr\" value=\"$contact_number\">
    </div>
  </div>

  <div class=\"form-group\">
    <label for=\"email\" class=\"col-sm-3 control-label\">Email address:</label>
    <div class=\"col-sm-12 col-md-6 col-lg-6\">
      <input  name=\"contact_email\" type=\"email\" class=\"form-control\" id=\"eaddr\" value=\"$contact_email\">
    </div>
  </div>
  <div class=\"form-group\">
    <label for=\"address\" class=\"col-sm-3 control-label\">Office Location:</label>
    <div class=\"col-sm-12 col-md-6 col-lg-6\">
      <input  name=\"contact_loc\" type=\"text\" class=\"form-control\" id=\"eaddr\" value=\"$contact_loc\">
    </div>
  </div>
  </div>
</div>
</div>";

echo"</form>";
echo"
<div class=\"row\">
<div class=\"col-sm-6 col-md-6 col-lg-6\">
<div class=\"form-group\" style=\"\">
    <div class=\"col-sm-12 col-md-6 col-lg-6 center\">
      <input onclick=\"submitForm();\"  value=\"Save Changes\" class=\"btn btn-large btn-primary btn-block\">
    </div>
  </div>
</div>
</div>";
}
}
?>
</div><!--end of main content-->

</div>

</div><!--end of main container-->
<script type="text/javascript">
function submitForm(){
document.forms["form_1"].submit();
}
</script>