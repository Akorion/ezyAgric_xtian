<style>
   .right{
    float:right;
    margin:0 40px;

    }
    .btn-fab{
    position:fixed !important;
    right:60px;
    bottom:90px;
    z-index:100;
    }

    
    .col-x {
      padding:1px !important;
      transition-durarion:1s !important; 
      -webkit-transition-durarion:1s; 
      background:none;
      visibility:gone;
    }
    
    .col-x:hover .card{
      box-shadow: 0 0 20px rgba(0,0,0,0.4);
      cursor:pointer;
        border-color:green;
        position:relative; 
        z-index:1;
    }
</style>
<style type="text/css">
  .dropdown i,a i,a.fa{
    color: #FF5722;

    text-orientation: none !important;
  }
.dropdown a:hover, ul li a:hover,li a:hover{

     text-orientation: none !important;
  }
a, a:link, a:visited, a:active, a:hover {
  border:0!important;
  text-orientation: none !important;

}
    .badge1{
/*    position: absolute;*/
    font-size: .6em;
    color: orange;
    bottom: 10px;
    right: 2px;
    height: 20px;
    width: 30px;
    padding: 5px 5px;
    border-radius: 50px;
   
    }
.containers {
  padding-right: 0px;
  padding-left: 0px;
  margin-right: auto;
  margin-left: auto;
}
.down{
margin-top:-3em !important;
}
</style>


<?php include("include/header_client.php");

 #includes
require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();


?>
<div class="col-xs-12">
<?php include "include/breadcrumb.php"?>
</div>
<div class="containers">

<div class="container" style="margin-bottom:80px;"><!--start of pagination row-->


<button type="button" class="btn-success btn" data-toggle="modal" data-target="#add_option">Add Input</button>
<table class="table" style="width:100% !important">
  <thead>
    <tr>
      <th>#No</th>
      <th>Input</th>
      <th>Type</th>
	  <th>Units</th>
	  <th>Units Per Acreage</th>
	  <th>Unit Price</th>
	  <th>Commission Per Unit</th>
	  <th>Options</th>
	  
    </tr>
  </thead>
  <tbody>

  <?php
  
  $client_id=$_SESSION["client_id"];
  
  $data = $mCrudFunctions->fetch_rows('out_grower_threshold_tb', '*', "client_id='$client_id'   ");
  $count=0;
  foreach($data as $row){
  $id=$row['id'];
  $count++;
    echo "<tr>";
	
	echo "<th scope='row'>".$count."</th>";
	
	echo"<td>".$row['item']."</td>";
	
	echo"<td>". $row['item_type']."</td>";
	
	echo"<td>". $row['units']."</td>";//
	
	$unit_per_acre = $row['unit_per_acre']==0 ?"N/A":$row['unit_per_acre'];
	echo"<td>". $unit_per_acre."</td>";
	
	$unit_price = $row['unit_price']==0 ?"N/A":$row['unit_price'];
	echo"<td>".$unit_price."</td>";//
	
	$commission_per_unit = $row['commission_per_unit']==0 ?"N/A":$row['commission_per_unit'];
	echo"<td>". $commission_per_unit."</td>";
	echo"<td> <button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#edit_option' onclick='edit_item($id);'>Edit</button>  <button type='button' class='btn btn-danger btn-sm' onclick='delete_item($id);'>Delete</button></td>";
	
    echo "</tr>";
	
  }
  ?>

  <br/>
  </tbody>
  <!--<tfoot>
    
    <td></td>
  </tfoot>-->
</table>
</div><!--end of pagination row-->

<!-- modal -->


 <div id="add_option" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">ADD NEW INPUT</h4>
                </div>
                <div class="modal-body">
                  <!-- <form id="changepassword" method="" action="">-->
                <div class="form-group" id="inamediv">
                <label>Input Name</label>
                <input id="iname" type="text" class="form-control" name="iname" placeholder="Enter Item Name" />
                 </div>
                <div class="form-group" id='itypediv'>
                 <label>Input Type</label>
                     <select class="selectpicker form-control" name="itype" id="itype">
					          <option value="">Select Type</option>
                              <option value="Fertilizer">Fertilizer</option>
                              <option value="Herbicide">Herbicide</option>
                              <option value="Seed">Seed</option>
                              
                            </select>
                </div>
                <div class="form-group">
                <label>Unit Type</label>
                <input id="uname" type="text" class="form-control" name="uname" placeholder="Enter Unit Name" />
                 </div>
                 <div class="form-group">
                <label>Unit Of Item Per Acre</label>
                <input id="upacre" type="number" class="form-control" name="upacre" placeholder="Enter Units required per Acre" />
                 </div>
                  <div class="form-group">
                <label>Unit Price</label>
                <input id="uprice" type="number" class="form-control" name="uprice" placeholder="Enter Unit Price" />
                 </div>

                  <div class="form-group">
                <label>Comission Per Unit</label>
                <input id="cpu" type="number" class="form-control" name="cpu" placeholder="Enter Comission Per Unit" />
                 </div>

                  <div class="form-group">
                 <p class='text text-danger' id='error_msg'></p>
                 </div>
                
                <!--</form>-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button onclick="addItem();"type="button" class="btn btn-danger">Save</button>
                </div>
            </div>
        </div>
    </div>


<!-- /modal -->




<!-- modal -->


 <div id="edit_option" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">EDIT INPUT</h4>
                </div>
                <div class="modal-body">
                  <!-- <form id="changepassword" method="" action="">-->
                <div class="form-group" id='inamediv'>
                <label>Input Name</label>
                <input id="editiname" type="text" class="form-control" name="editiname" placeholder="Enter Item Name" />
                 </div>
                <div class="form-group" id='itypediv'>
                 <label>Input Type</label>
                     <select class="selectpicker form-control" name="edititype" id="edititype">
                    <option value="">Select Type</option>
                              <option value="Fertilizer">Fertilizer</option>
                              <option value="Herbicide">Herbicide</option>
                              <option value="Seed">Seed</option>
                              <option value="Service">Service</option>
                            </select>
                </div>
                <div class="form-group" id='utypediv'>
                <label>Unit Type</label>
                <input id="edituname" type="text" class="form-control" name="edituname" placeholder="Enter Unit Name" />
                 </div>
                 <div class="form-group">
                <label>Unit Of Item Per Acre</label>
                <input id="editupacre" type="number" class="form-control" name="editupacre" placeholder="Enter Units required per Acre" />
                 </div>
                  <div class="form-group">
                <label>Unit Price</label>
                <input id="edituprice" type="number" class="form-control" name="edituprice" placeholder="Enter Unit Price" />
                 </div>

                  <div class="form-group">
                <label>Comission Per Unit</label>
                <input id="editcpu" type="number" class="form-control" name="editcpu" placeholder="Enter Comission Per Unit" />
                 </div>
                

                  <div class="form-group">
                 <p class='text text-danger' id='edit_error_msg'></p>
                 </div>
                <!--</form>-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button onclick="editItem();"type="button" class="btn btn-danger">Save</button>
                </div>
            </div>
        </div>
    </div>


<!-- /modal -->



</div><!--end of main content-->

<div id="alert-modal" class="modal fade brexit">
  <div class="modal-dialog modal-m euroexit">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 id="alert-modal-title" class="modal-title"></h4>
      </div>
      <div id="alert-modal-body" class="modal-body" style="color:#777;"></div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>


<?php include("include/footer_client.php"); ?>



<script type="text/javascript">

var item_to_edit_id;

function showProgressBar(){
 $(".cssload-container").show();
}
 function hideProgressBar(){
 $(".cssload-container").hide();
}
function showempty(){
   $("#empty-message").show();
    }
function hideempty(){
    $("#empty-message").hide();
}

function addItem(){
    var error = 0;
    var error_message = ""
    var iname = $('#iname').val();
    var itype = $('#itype').val();
    var uname = $('#uname').val();
    var upacre = $('#upacre').val();
    var uprice = $('#uprice').val();
    var cpu = $('#cpu').val();

    var anyFieldIsEmpty = iname == '' || itype == '' || uname == '' || upacre == '' || uprice == '' || cpu == '';
    if(anyFieldIsEmpty){
      error = 1;
     
      $('#error_msg').html("Please Fill all Fields");
      
    }
    if(error == 0){
       $('#add_option').modal('hide');
    $.post("include/ajax.php", {token:"add_item", iname:iname, itype:itype, uname:uname, upacre:upacre, uprice:uprice, cpu:cpu}, function(response){
        
		swal(""+response, "", "success"); 
		
        location.reload();
    });
   
    }

}

function delete_item(id){
   swal({   title: "Are you sure?",   text: "",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   cancelButtonText: "No, cancel!",   closeOnConfirm: false,   closeOnCancel: false }, 
    function(isConfirm)
    {   if (isConfirm) { 
	
	$.post('include/ajax.php', {token:'delete_item', id:id}, function(response){
	swal("Deleted!", "", "success");   
	location.reload();
	});  
	} 
    else {    
	swal("Cancelled", " ", "error");
	} 
	});
}

function edit_item(id){
    $.post("include/ajax.php", {id:id, token:"edit_item"}, function(response){
        var item_data = JSON.parse(response);
        var item_id = item_data[0].id; item_to_edit_id = item_id;
        var item_name = item_data[0].item;
        var item_type = item_data[0].item_type;
        var item_units = item_data[0].units;
        var item_units_per_acre = item_data[0].unit_per_acre;
        var item_price = item_data[0].unit_price;
        var item_comission_per_unit = item_data[0].commission_per_unit;

        if(item_type == 'Service'){
          $('#editiname').prop('readonly', true);
         // $('#edititype').prop('readonly', true);
          $('#edituname').prop('readonly', true);
          $('#edititype').prop('disabled',true);
          
        }else{
          $('#editiname').prop('readonly', false);
          // $('#edititype').prop('readonly', false);
            $('#edititype').prop('disabled',false);
          $('#edituname').prop('readonly', false);
        } 

        $('#editiname').val(item_name);
        $('#edititype').val(item_type);
        $('#edituname').val(item_units);
        $('#editupacre').val(item_units_per_acre);
        $('#edituprice').val(item_price);
        $('#editcpu').val(item_comission_per_unit);
     
  });
}

function editItem(){
  var edit_error = 0;
  var edit_error_msg = "";

  var new_item_name = $('#editiname').val();
  var new_item_type = $('#edititype').val();
  var new_item_unit = $('#edituname').val();
  var new_item_units_per_acre = $('#editupacre').val();
  var new_unit_price = $('#edituprice').val();
  var new_cpu = $('#editcpu').val();

  var anyEditFieldIsEmpty = new_item_name == '' || new_item_type == '' || new_item_unit == '' || new_item_units_per_acre == '' || new_unit_price == '' || new_cpu == '';
  if(anyEditFieldIsEmpty){
    edit_error = 1;
    edit_error_msg += "Please Fill in all Fields";
      $('#edit_error_msg').html("Please Fill all Fields");
  }

if(edit_error == 0){
  $('#edit_option').modal('hide');

   $.post('include/ajax.php', {id:item_to_edit_id, iname:new_item_name, itype:new_item_type, uname:new_item_unit, upacre:new_item_units_per_acre, uprice:new_unit_price, cpu:new_cpu, token:'save_edit_item'}, function(response){
    
	//alert(response);
    swal("" + response, "", "success"); 
	
    location.reload();
  });
}
  
}

function alertModal(title, body) {
  // Display error message to the user in a modal
      $('#alert-modal-title').html(title);
      $('#alert-modal-body').html(body);
      $('#alert-modal').modal('show');
  }
</script>


    
    