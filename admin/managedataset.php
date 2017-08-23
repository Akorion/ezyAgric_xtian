
<div class="container-fluid">
<div class="row">
<?php include("../include/sidebar.php");
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
<div class="col-sm-12 col-md-12 col-lg-12">
<h3 class="text-center" style="color:#e91e63; margin:10px 0;">Manage Datasets</h3>
</div>
<div class="col-sm-12 col-md-12 col-lg-12">
<div class="table-responsive">
<table class="table table-striped" style="width:100% !important">
<?php

$table="client_datasets_v";		 
$columns="*";
$where=" 1 ORDER BY time_of_creation DESC  ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if(sizeof($rows)==0){  
}else{
echo"<thead>
<tr class=\"success\">
	<th>Name</th>
	<th>Client</th>
	<th>Type</th>
	<th>Region</th>
	<th>Period</th>
	<th>Last modified</th>
	<th>Options</th>
	</tr>
</thead>
<tbody>";
foreach( $rows as $row){
    $real_id=$row['id'];
    $dataset_client_id = $row['client_id'];
   // $dataset_id = $row['id'];
	$id=$util_obj->encrypt_decrypt("encrypt", $real_id);
	$type= $row['dataset_type'];
    echo "
    <tr class=\"primary\">
    <td>".$row['dataset_name']."</td>
	<td>".$row['c_name']."</td>
    <td>".$row['dataset_type']."</td>
    <td>".$row['region']."</td>
    <td>".$row['start_date']." to ".$row['end_date']."</td>
    <td>".$row['time_of_creation']."</td>
    
	<td class=\"action\">
    <a href=\"?action=editdataset&token=$id\" class=\"txt\">Edit</a>
    <a href=\"#myModal\" data-toggle=\"modal\" onclick=\"setDataset4delete($real_id);\" data-target=\"#myModal\" class=\"txt\">Delete</a>
    <a href=\"?action=view&token=$id&type=$type\" class=\"txt\">View</a>";
  
	$gardens="garden_".$real_id;
	 if($mCrudFunctions->check_table_exists($gardens)<1){
	echo"&nbsp; <a href=\"#addgardens\" onclick=\"setDataset($real_id);\"data-toggle=\"modal\" data-target=\"#addgardens\" class=\"txt\">Add Gardens</a>";
	}

    if($row['dataset_type'] == 'Farmer'){
        echo "&nbsp; <a  class=\"txt\" data-toggle='modal' onclick='getDatasets($dataset_client_id, $real_id);' data-target='#attachdataset'>Attach Dataset</a>";
    }
	 echo "</td>";
	
}
echo"</tbody>";
}
 ?>



	
</table>
</div>

</div>
</div>

<!--end of main content-->


</div>
</div>
<!--end of main container-->

<!--modal window-->
    <!-- Modal HTML -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
			<form action="delete_dataset.php" method="post" enctype="multipart/form-data" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
				<input id="dataset_id_delete"   type="hidden" name="id" />
                    <p>Are you sure you want to delete this datatset??</p>
                </div>
                <div class="modal-footer">
                   <input type="submit"  name="submit" class="btn btn-danger"/>
			</form>
			      <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                   
                </div>
            </div>
        </div>
    </div>
	
    <!--model for adding gardens-->
	 <div id="addgardens" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
			<form action="import_gardens_csv.php" method="post" enctype="multipart/form-data" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add Farmer</h4>
                </div>
                <div class="modal-body">
				    <input id="dataset"   type="hidden" name="id" />
                    <p><input id="file" type="file" name="csv" accept=".csv" value="Choose CSV"></p>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="Submit" class="btn btn-primary"/>
					<button type="button" onclick="Refresh();" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
            </div>
        </div>
    </div>

      <!--model for adding gardens-->
	 <div id="attachdataset" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-success">ATTACH DATASET - SELECT ONLY ONE</h4>
                </div>
                <div class="modal-body"  id="available_datasets">
				 
                   <div class="checkbox" >

                   </div>
                  
                </div>
                    <h5 style='text-align:center;'>Currently Attached Datasets</h5>
                <div class="modal-body"  id="active_datasets">
				 
                   <div class="checkbox">

                   </div>
                  
                </div>
                <div class="modal-footer">
                    <input type="submit" id="btnattach" name="submit" value="Attach" class="btn btn-primary" onclick="attach();"/>
					<button type="button" onclick="Refresh();" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
	
<!--end of main container-->
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/material.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>

<script type="text/javascript">
var dataset_name;
var global_dataset_id;

function Refresh(){
    var sel_file= document.getElementById("file");
	var dataset_id = document.getElementById("dataset");
	sel_file.value="";
	dataset_id .value=""; 
}

function setDataset(id){
	var dataset_id = document.getElementById("dataset");
	dataset_id .value=id;

    $(this).parent().find("input[name=id]").val(id);
//	return dataset_id;
}

function setDataset4delete(id){
	var dataset_id = document.getElementById("dataset_id_delete");
	dataset_id .value=id; 
}

function getDatasets(id, datasetid){
    global_dataset_id = datasetid;
    $.post("../include/ajax.php", {token:"get_datasets", id:id}, function(response){
    var dataset_data = JSON.parse(response);
    var dataset_html = "";
    var active_dataset_html = "";
    for(var i = 0; i < dataset_data.length; i++) {
           var dataset = dataset_data[i];
           var dataset_id = dataset.id;
           dataset_name = dataset.dataset_name;
           var va_dataset_id = dataset.va_dataset_id;
           if(va_dataset_id != '0'){
                dataset_name = getDatasets(va_dataset_id);
               alert(dataset_name);
                active_dataset_html += "<input type='checkbox' class='styled' name='dataset_radio' id='dataset_radio' onclick='checkuncheck2();' value="+dataset_id+" checked><label>"+dataset_name+"</label><br>";
           }else{
                dataset_html += "<input type='checkbox' class='styled' name='dataset_radio' id='dataset_radio' onclick='checkuncheck();' value="+dataset_id+"><label>"+dataset_name+"</label><br>";
           
           }
        }
     $('#available_datasets').html(dataset_html);
     $('#active_datasets').html(active_dataset_html);
    });
}

function checkuncheck(){
    $('#available_datasets input[type="checkbox"]').on('change', function() {
    $('#available_datasets input[type="checkbox"]').not(this).prop('checked', false);
    });

     $('#btnattach').attr('Value', 'Attach');
}

function checkuncheck2(){
    $('#active_datasets input[type="checkbox"]').on('change', function() {
    $('#active_datasets input[type="checkbox"]').not(this).prop('checked', false);
    });

    $('#btnattach').attr('Value', 'Dettach');
}

function attach(){
    $('#available_datasets input[name="dataset_radio"]:checked').each(function() {
        var checked_dataset_id = this.value;
        //alert(checked_dataset_id);
        $.post("../include/ajax.php", {token:"update_va_dataset_id", id:checked_dataset_id, global_id:global_dataset_id}, function(response){
           swal(response, "", "success");
           location.reload();
        });
    });
     $('#attachdataset').modal('hide');
}

function get_dataset_name(va_dataset_id){
  
    $.post("../include/ajax.php", {token:"get_dataset_name", id:va_dataset_id}, function(response){
        
        var dataset_name_data = JSON.parse(response);
        active_dataset_name = dataset_name_data[0].dataset_name;

        //alert(active_dataset_name);
    });

    return active_dataset_name;
}

</script>
	
	
    
