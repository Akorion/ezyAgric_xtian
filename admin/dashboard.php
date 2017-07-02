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
<div class="col-sm-12 col-md-9 col-lg-9 data">
<div class="row dash">
<div class="col-sm-12 col-md-4 col-lg-4">
<div class="panel panel-danger">
  <div class="panel-heading"><h4>Total Clients</h4></div>
  <div class="panel-body">
  
  <?php
  $clients=sizeof($mCrudFunctions->fetch_rows("client_accounts_tb","*",1));  
   echo"<h1 class=\"text-center\">$clients</h1>";
   ?>
   <a href="?action=manage">view more&raquo;</a>
  </div>
</div>
</div>

<div class="col-sm-12 col-md-4 col-lg-4">
<div class="panel panel-primary">
  <div class="panel-heading"><h4>Total Datasets</h4></div>
  <div class="panel-body">
 <?php $datasets=sizeof($mCrudFunctions->fetch_rows("client_datasets_v","*",1)); 
  echo"<h1 class=\"text-center\">$datasets</h1>";?>
  <a href="?action=managedataset">view more&raquo;</a>
  </div>
</div>
</div>

<div class="col-sm-12 col-md-4 col-lg-4">
<div class="panel panel-info">
  <div class="panel-heading"><h4>Last login</h4></div>
  <div class="panel-body">
 <dl class="dl-horizontal text-left">
 <?php
 
$table="admin_log_v";		 
$columns="*";
$where=" 1 ORDER BY login_out_time DESC LIMIT 1 ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if(sizeof($rows)==0){  
}else{
if($rows[0]['login_out_time']==""){}else{
echo"<dt>Name</dt>";
echo"<dd>".$rows[0]['user_name']."</dd>";
echo"<dt>Date</dt>";
echo"<dd>".$util_obj->get_date_string($rows[0]['login_out_time'])."</dd>";
echo"<dt>Time</dt>";
echo"<dd>".$util_obj->get_time_string($rows[0]['login_out_time'])."</dd>";
}
}
?>
</dl>
<br/>
<a href="?action=logs">view more&raquo;</a>
  </div>
</div>
</div>
</div>

<!-- secon row starts-->

<div class="row dash hide">
<div class="col-sm-12 col-md-4 col-lg-4">
<div class="panel panel-default">
  <div class="panel-heading"><h4>Total Village Agents</h4></div>
  <div class="panel-body">
   <h1 class="text-center">1000</h1>
   <a href="?action=vas">view more&raquo;</a>
  </div>
</div>
</div>

<div class="col-sm-12 col-md-4 col-lg-4">
<div class="panel panel-warning">
  <div class="panel-heading"><h4>Total Farmers</h4></div>
  <div class="panel-body">
  <h1 class="text-center">500</h1>
  <a href="?action=farmers">view more&raquo;</a>
  </div>
</div>
</div>
</div>

<!--end of main content-->
</div>
</div>
</div> <!--end of main content-->
