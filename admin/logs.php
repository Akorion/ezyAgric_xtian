<div class="container-fluid">
<div class="row">
<!--sidebar is in an external file in includes-->
<?php include("../include/sidebar.php");?>
<!--end of sidenav-->
<!-- main content area-->
<div class="col-sm-12 col-md-9 col-lg-9">
<h3 class="text-left more-margin" style="color:red; font-size:25px;">User logs</h3>
<hr/>
<div class="row hide">
	<div class="col-sm-12 col-md-4 col-lg-4">
	<form class="form-horizontal ">
	
	<div class="form-group">
    <label for="fname" class="col-sm-3 control-label">From:</label>
    <div class="col-sm-12 col-md-7 col-lg-7">
    <input id="my-date-from" type=""  name="start" class="form-control" placeholder="pick start date">
    </div>
  </div>
  </form>
	</div>

	<div class="col-sm-12 col-md-4 col-lg-4">
	<form class="form-horizontal">
	
	<div class="form-group">
    <label for="fname" class="col-sm-3 control-label">To:</label>
    <div class="col-sm-12 col-md-7 col-lg-7">
    <input id="my-date-to" type=""  name="start" class="form-control" placeholder="pick end date">
    </div>
  </div>
  </form>
	</div>
</div>
<div class="row dash">
<div class="col-sm-12 col-md-12 col-lg-12">
<div class="table-responsive">
<?php
 #includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();

$table="admin_log_v";		 
$columns="*";
$where=" 1 ORDER BY login_time ASC  ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if(sizeof($rows)==0){  
}else{

echo"<table class=\"table table-striped\">";
echo"<thead>";
echo"<tr class=\"success\">";
    echo"<th>Username</th>";
	echo"<th>Date</th>";
	echo"<th>Login time</th>";
	echo"<th>Logout time</th>";
	echo"<th>IP address</th>";
	echo"</tr>";
echo"</thead>";
echo"<tbody>";
foreach( $rows as $row){

echo"<tr class=\"\">";
echo"<td>".$row['user_name']."</td>";
echo"<td>".$util_obj->get_date_string($row['login_time'])."</td>";
echo"<td>".$util_obj->get_time_string($row['login_time'])."</td>";
if($row['login_out_time']==""){echo"<td>---</td>";}else{ echo"<td>".$util_obj->get_time_string($row['login_out_time'])."</td>";}
echo"<td>".$row['ip_address']."</td>";
echo"</tr>";

}

echo"</tbody>";
echo"</table>";
}


?>
<!--
<table class="table table-striped">
<thead>
<tr class="success">
	<th>Date</th>
	<th>Login time</th>
	<th>Logout time</th>
	<th>Username</th>
	<th>IP address</th>
	</tr>
</thead>

<tbody>
<tr class="">
<td>10/10/2015</td>
<td>12:00 EAT</td>
<td>16:00 EAT</td>
<td>Carana</td>
<td>192.168.88.17</td>
</tr>

<tr class="info">
<td>10/10/2015</td>
<td>12:00 EAT</td>
<td>16:00 EAT</td>
<td>Carana</td>
<td>192.168.88.17</td>
</tr>
<tr class="danger">
<td>10/10/2015</td>
<td>12:00 EAT</td>
<td>16:00 EAT</td>
<td>Carana</td>
<td>192.168.88.17</td>
</tr>
<tr class="warning">
<td>10/10/2015</td>
<td>12:00 EAT</td>
<td>16:00 EAT</td>
<td>Carana</td>
<td>192.168.88.17</td>
</tr>
</tbody>
</table>-->
</div>

</div>
</div>
<!--end of main content-->
</div>
</div>
<!--end of main container-->
