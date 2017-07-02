<!DOCTYPE html>
<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
if(isset($_GET['logout'])&&$_GET['logout']==1){
   $logout_time=date('Y-m-d H:i:s');
   $user_id=$_SESSION["user_id"];
   $table="admin_logs_tb";		 
   $columns="*";
   $where=" user_id='$user_id' ORDER BY login_time DESC LIMIT 1";
   $rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
   $id=$rows[0]['id'];
   $where_u=" id='$id' ";
   $columns_value=" login_out_time='$logout_time' ";
   $util_obj->update_table($db,$table,$columns_value,$where_u);
   $_SESSION["username"]=null;
   $_SESSION["user_id"]=null;
}else{
if(isset($_SESSION['user_id'])){$util_obj->redirect_to( "home.php" );}
}
?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<link href='http://fonts.googleapis.com/css?family=Lato:300,100' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="../css/custom.css">
	<title>ezyagric_v2-Login</title>
</head>
<body>
<nav class="navbar navbar-default teal">
  <div class="container">
    <div class="navbar-header logo-title">
      <a class="navbar-brand" href="#">
        <img alt="Brand"  src="../images/logo.png">
      </a>
    </div>
  </div>
</nav>

<div class="container top">
<div class="row">
<div class="col-sm-12 col-md-5 col-lg-5 bg">
	<form method="post" action="../form_actions/login.php" class="form-signin">
        <h2 class="form-signin-heading text-center" style="color:#ff9800;">Admin Login</h2>
        <div class="form-group">
        <label class="forusername">Username</label>
        <input type="text" name="username" class="form-control" id="username" require="" placeholder="Username">
        </div>
        <div class="form-group">
        <label class="forpassword">Password</label>
        <input type="password"  name="password" class="form-control" id="password" require="" placeholder="Password">
        </div>
        <input type="submit" value="Sign In" name="login" class="btn btn-large btn-success center"></input>
        <!--<a  type="submit" class="btn btn-large btn-success center">Sign In</a>
            <!--<br/><br/>

            <a href="#" class="text-center forgot">Forgot password?</a>-->
	</form>
</div>	
</div>
</div>


<?php include("../include/footer.php");?>