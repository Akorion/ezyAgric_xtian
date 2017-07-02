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
      visibility:hidden;
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


}

if(isset($_POST['iname'])){
  $username = $_POST['username'];
  $email = $_POST['useremail'];
  $password = $_POST['userpassword'];
  $userrole = $_POST['role'];



  $result = $mCrudFunctions->insert_into_clients_users_tb($username, $email, $password, $userrole);


}
?>
<?php include "include/breadcrumb.php"?>
<div class="containers">

<div class="container" style="margin-bottom:80px;"><!--start of pagination row-->


<table class="table table-bordered" id="userstable">
  <thead>
    <tr>
      <th>#</th>
      <th>User </th>
	  <th>Email</th>
	  <th>Role</th>
      <th>Activity</th>
      <th>Time</th>
	  <th>IP Address</th>
    </tr>
  </thead>
  <tbody>

  <?php
  $client_id=$_SESSION["client_id"];
  $data = $mCrudFunctions->fetch_rows('client_logs_v', '*', "client_id='$client_id' ORDER BY  timestamp DESC ");
  $id=0;
  foreach($data as $user_data){
  $id++;
  $username = $user_data['username'];
    echo "<tr><th scope='row'>";
    echo $id;
    echo "</th><td>";
    echo $user_data['username'];
    echo "</td><td>";
    echo $user_data['user_email'];
    echo "</td><td>";
	
	
    if($user_data['role'] == 1){
      echo "Admin";    
    }else{
      echo "User";
    }
	echo"</td><td> ";
	echo $user_data['activity'];
	echo"</td><td> ";
	echo $user_data['time_of_activity'];
	echo"</td><td> ";
	echo  $user_data['ip_address']= "" ? $user_data['ip_address']: "N/A";
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




</div><!--end of main content-->


<?php include("include/footer_client.php"); ?>



