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



if(isset($_POST['username'])){
  $username = $_POST['username'];
  $email = $_POST['useremail'];
  $password = $_POST['userpassword'];
  $userrole = $_POST['role'];



  $result = $mCrudFunctions->insert_into_clients_users_tb($username, $email, $password, $userrole);

  unset($_POST);
 
}


?>
<?php include "include/breadcrumb.php"?>
<div class="containers">

<div class="container" style="margin-bottom:80px;"><!--start of pagination row-->

<td><button type="button" class="btn-success btn" data-toggle="modal" data-target="#submitform">Add New User</button></td>
<table class="table" id="userstable" style="width:100% !important">
  <thead>
    <tr>
      <th>#User ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
	  <th class='text-right'>Options</th>
    </tr>
  </thead>
  <tbody>

  <?php
 $client_id=$_SESSION["client_id"];
  $user_id=$_SESSION["user_id"];
  $data = $mCrudFunctions->fetch_rows('clients_users_tb', '*', "client_id='$client_id' ");
  $count=0;
  foreach($data as $user_data){
     $id=$user_data['id'];
     $count++;
    $username = $user_data['username'];
    echo "<tr><th scope='row'>";
    echo $count;
    echo "</th><td>";
    echo $user_data['username'];
    echo "</td><td>";
    echo $user_data['user_email'];
    echo "</td><td>";
	switch ($user_data['role']){
	 
	case 0:
	echo "Root"; 
	
	break;
	case 1:
	echo "Admin"; 
	break;
	case 2:
	echo "User"; 
	
	break;
	case 3:
	echo "Director"; 
	
	break;
	
	}
	
	if($user_id==$id){
	
	echo"</td><td class='text-right'><button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editform' onclick='edit_user($id)'>edit</button> ";
	
	
	}else{
	
	echo"</td><td class='text-right'> <button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editform' onclick='edit_user($id)'>edit</button>  <button class='btn btn-danger btn-sm' onclick='delete_user($id)'>delete</button></span>";
	
	}
	
    echo "</td></tr>";
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


<div class="modal fade" id="submitform" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    ADD A NEW USER
                </h4>
            </div>

            
            <!-- Modal Body -->
            <div class="modal-body">
                
               
                  <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                      <input type="text" class="form-control"
                      id="username" placeholder="Enter Username" name="username" />
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Email</label>
                      <input type="email" class="form-control"
                          id="useremail" name="useremail" placeholder="Enter Email"/>
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                      <input type="password" class="form-control"
                    id="userpassword" name="userpassword" placeholder="Enter Password"/>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputPassword1">Confirm Password</label>
                      <input type="password" class="form-control"
                    id="userpassword1" name="userpassword1" placeholder="Confirm Password"/>
                  </div>

                   <div class="form-group">
                   <label>Role</label>
                   <select class="selectpicker form-control"  id="userrole" name="userrole">
                   <option value="1">Admin</option>
                    <option value="2">User</option>
					<option value="3">Director</option>

                   </select>
                   </div>

				   <div class="form-group">
                    <p id='create_user_error' class='text-danger'></p>
                   </div>


                 
                 
                </form>
                
                
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button  class="btn btn-primary" value="Save" onclick="saveData();">
                    Save 
                </button>
            </div>
        </div>
    </div>
</div>

<!-- modal -->


<div class="modal fade" id="editform" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    EDIT USER
                </h4>
            </div>

            
            <!-- Modal Body -->
            <div class="modal-body">
                
               
                  <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                      <input type="text" class="form-control"
                      id="editusername" placeholder="Enter Username" name="editusername" />
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Email</label>
                      <input type="email" class="form-control"
                          id="editemail" name="editemail" placeholder="Enter Email"/>
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                      <input type="text" class="form-control"
                          id="editpassword" name="editpassword" placeholder="Enter Password"/>
                  </div>
                   <div class="form-group">
                   <label>Role</label>
                   <select class="selectpicker form-control" name="editrole" id="editrole">
                   <option value="1">Admin</option>
                    <option value="2">User</option>
					<option value="3">Director</option>

                   </select>
                   </div>
            
                   <div class="form-group">
                    <p id='edit_user_error' class='text-danger'></p>
                   </div>
                
				
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Close
                </button>
                <button class="btn btn-primary" onclick="saveChanges();">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>


</div><!--end of main content-->


<?php include("include/footer_client.php"); ?>



<script type="text/javascript">
var user_id_to_edit;


function saveData(){
  var err = 0;
  var err_msg = "";
  var uname = $('#username').val();
  var email = $('#useremail').val();
  var pass = $('#userpassword').val();
  var role = $('#userrole').val();

   var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    
    var validEmail = pattern.test(email); 

    if(!validEmail){
        err_msg += "Email not Valid\n";
        err = 1;
		
        $('#create_user_error').html(err_msg);
    }
 
  var anyFieldIsEmpty = uname == '' || email == '' || pass == '' || role == '';
  if(anyFieldIsEmpty){
      err = 1;
      err_msg += "Please Fill in all Fields";
      $('#create_user_error').html(err_msg);
      
  }

  if(err == 0) {
  $.post("include/ajax.php",{token:"add_user", uname:uname, uemail:email, upass:pass, urole:role}, function(response){
      swal(""+response, "", "success");
	  
	  location.reload();
      
  });

   $('#submitform').modal('hide');
  }
}

function delete_user(id){
  //alert(id);
  swal({   title: "Are you sure?",   text: "",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, Delete!",   cancelButtonText: "No, cancel!",   closeOnConfirm: false,   closeOnCancel: false }, 
    function(isConfirm)
    {   if (isConfirm) { $.post('include/ajax.php', {token:'delete_user', id:id}, function(response){location.reload();});   swal("Deleted!", "", "success");   } 
    else {     swal("Cancelled", " ", "error");   } });

   
}

function edit_user(id){
  $.post("include/ajax.php", {id:id, token:"edit_user"}, function(response){
    var user_data = JSON.parse(response);
    var user_id = user_data[0].id; user_id_to_edit = user_id;
    var user_name = user_data[0].username;
    var user_email = user_data[0].user_email;
    var user_password = user_data[0].password;
    var user_role = user_data[0].role;

    $('#editusername').val(user_name);
    $('#editemail').val(user_email);
    $('#editpassword').val(user_password);
    $('#editrole').val(user_role);

  });
}

function saveChanges(){
  var edit_err = 0;
  var edit_err_msg = "";
  
  var new_user_name = $('#editusername').val();
  var new_user_email = $('#editemail').val();
  var new_user_password = $('#editpassword').val();
  var new_user_role = $('#editrole').val();

 var anyEditFieldIsEmpty = new_user_name == "" || new_user_email == "" || new_user_password == "" || new_user_role == "";

  if(anyEditFieldIsEmpty){
      edit_err = 1;
      edit_err_msg += "Please Fill in All Fields";
      $('#edit_user_error').html(edit_err_msg);
  }

  var safepattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    
  var newValidEmail = safepattern.test(new_user_email); 

    if(!newValidEmail){
        edit_err_msg += "Email not Valid\n";
        edit_err = 1;
        $('#edit_user_error').html(edit_err_msg);
    }
  if(edit_err == 0){

  $.post('include/ajax.php', {id:user_id_to_edit, uname:new_user_name, uemail:new_user_email, upasswd:new_user_password, urole:new_user_role, token:'save_user_edits'}, function(response){
    //alert(response);
    
    swal(""+response, "", "success");
    location.reload();	
 
  });
  
  $('#editform').modal('hide');

  }

}
</script>
    
    