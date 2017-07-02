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
<div class="row dash">
<h3 class="" style="color:#e91e63; padding-left:85px;">Create New  Dataset</h3>
<hr/>
<div class="col-sm-12 col-md-10 col-lg-10" style="margin-top:15px;">
    <form class="form-horizontal" method="post" action="../form_actions/import_csv.php" enctype="multipart/form-data" >
	<div class="form-group">
    <label for="fname" class="col-sm-3 control-label">Dataset Name:</label>
    <div class="col-sm-12 col-md-7 col-lg-7">
      <input type="text" name="dataset_nm" class="form-control" id="dname" placeholder="Dataset Name">
    </div>
  </div>

  <div class="form-group">
    <label for="dtype" class="col-sm-3 control-label">Dataset Type:</label>
    <div class="col-sm-12 col-md-7 col-lg-7">
     <select name="dataset_type" class="form-control">
  	<option value="Farmer" >Farmer</option>
  	<option value="VA">VA</option>
	</select>
    </div>
  </div>
  
  <div class="form-group">
    <label for="dtype" class="col-sm-3 control-label">Client:</label>
    <div class="col-sm-12 col-md-7 col-lg-7">
     <select name="client_id" class="form-control">
  	 <option value="">Select Client</option>
	 <?php
	 $table="client_accounts_tb";		 
     $columns="*";
     $where=" 1 ";
     $rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
	 foreach($rows as $row){
	 $client_id =$row['id'];
	 $client =$row['c_name'];
	  echo " <option value=\"$client_id\">$client</option>";
	 
	 }
	 ?>
  	
	</select>
    </div>
  </div>
  
  <div class="form-group">
    <label for="dtype" class="col-sm-3 control-label">Region:</label>
    <div class="col-sm-12 col-md-7 col-lg-7">
     <select name="region" class="form-control">
  	 <option value="Eastern Region">Eastern Region</option>
  	 <option value="Central Region">Central Region</option>
  	 <option value="Western Region">Western Region</option>
  	 <option value="Northern Region">Northern Region</option>
	</select>
    </div>
  </div>

  <div class="form-group">
    <label for="fname" class="col-sm-3 control-label">From:</label>
    <div class="col-sm-12 col-md-7 col-lg-7">
   <input id="my-date-input" type=""  name="start" class="form-control" placeholder="pick start date">
    </div>
  </div>

   <div class="form-group">
    <label for="fname" class="col-sm-3 control-label">To:</label>
    <div class="col-sm-12 col-md-7 col-lg-7">
   		<input id="my-date-input1" type="" name="end" class="form-control" placeholder="pick end date">
    </div>
  </div>

 <div class="form-group">
    <label for="fname" class="col-sm-3 control-label">File:</label>
    <div class="col-sm-12 col-md-7 col-lg-7">
    <input type="file" name="csv" value="choose csv">
    </div>
  </div>
<!--        accept=".csv"-->

        <div class="form-group">
    <div class="col-sm-12 col-md-6 col-lg-6 center">
      <input type="submit" name="submit" value="Create Dataset" class="btn btn-large btn-primary btn-block">
    </div>
  </div>

	</form>
</div>
</div>
<!--end of main content-->

</div>
</div>
<!--end of main container-->
