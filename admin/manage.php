<div class="container-fluid">
<div class="row">
<?php include("../include/sidebar.php");?>
<!--end of sidenav-->

<!-- main content area-->
<div class="col-sm-12 col-md-9 col-lg-9 data">
<div class="row">
<h3 class="text-center" style="color:red; font-size:25px;">Manage Accounts</h3>
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

$table="client_accounts_tb";		 
$columns="*";
$where=" 1 ORDER BY time_of_creation DESC  ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if(sizeof($rows)==0){  
}else{

echo"<table class=\"table table-striped thead-fixed\">";
echo"<thead>";
echo"<tr class=\"success\">";
	echo"<th>Client Name</th>";
	echo"<th>Physical address</th>";
	echo"<th>email address</th>";
	echo"<th>Contact personel</th>";
	echo"<th>contact personel phone</th>";
	echo"<th>Action</th>";
	echo"</tr>";
echo"</thead>";

echo"<tbody>";
foreach( $rows as $row){
$real_id=$row['id'];
$lock=1;
$status="Lock";
if($row['status']=="Open"){
}else{
$status="Open";
$lock=0;
}
$id=$util_obj->encrypt_decrypt("encrypt", $real_id);
echo "<tr class=\"primary\">
	<td>".$row["c_name"]."</td>
	<td>".$row["c_address"]."</td>
	<td>".$row["c_email"]."</td>
	<td>".$row["contact_name"]."</td>
	<td>".$row["contact_number"]."</td>
	<td><a href=\"?action=edit&token=$id\" class=\"btn btn-small btn-info txt\">Edit</a>
		<a href=\"#lock\" data-toggle=\"modal\" onclick=\"lock_account($real_id,$lock)\"data-target=\"#lock\" style=\"color:#ff9800;\">$status</a>
		<a href=\"#del\" data-toggle=\"modal\" onclick=\"delete_account($real_id)\" data-target=\"#del\" style=\"color:#f44336;\">Delete</a>
	</td>
	</tr>";

}

echo"</tbody>";
echo"</table>";
}


?>

</div>

</div>
</div>
<!--end of main content-->
</div>
</div>
<!--end of main container-->


<!--modal window-->
    <!-- Modal HTML -->
	<form method="post" action="..\form_actions\delete_account.php" >
	<input type="hidden" id="lock_id_field" name="client_id" />
	<input type="hidden" id="lock_status" name="state" />
    <div id="lock" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <input type="submit" value="Yes" name="lock" class="btn btn-danger"></button>
                </div>
            </div>
        </div>
    </div>
	</form>
<!--delete modal window -->
<form method="post" action="..\form_actions\delete_account.php" >
	<input type="hidden" id="delete_id_field" name="client_id" />
    <div id="del" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this account?</p>
                </div>
				
                <div class="modal-footer">
                    <input type="button" value="Cancel" class="btn btn-primary" data-dismiss="modal"></input>
                    <input type="submit" name="delete" value="Yes" class="btn btn-danger"  ></input>
                </div>
				
            </div>
        </div>
    </div>
	</form>
	
	<script type="text/javascript">
	function delete_account(id){
    var idInput = document.getElementById("delete_id_field");
    idInput.value=id;
    }
	function lock_account(id,lock){
    var idInput = document.getElementById("lock_id_field");
	var lockInput = document.getElementById("lock_status");
    idInput.value=id;
	lockInput.value=lock;
    }
	</script>