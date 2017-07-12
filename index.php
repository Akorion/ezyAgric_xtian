<!DOCTYPE html>
<?php
session_start();
#includes
require_once dirname(__FILE__) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/utility_class.php";

$util_obj = new Utilties();
$db = new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
if (isset($_GET['logout']) && $_GET['logout'] == 1) {

    if ($_SESSION["client_id"] == 2) {

        $user_id = $_SESSION["user_id"];
        $client_id = $_SESSION["client_id"];
        $time_of_activity = $util_obj->getTimeForLogs('Africa/Nairobi');
        $ip_address = $util_obj->getClientIpV4();
        $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Logged Out", $time_of_activity, $ip_address);


    }

    $_SESSION["client_id"] = null;
    $_SESSION["user_account"] = null;
    $_SESSION["account_name"] = null;
    $_SESSION["user_id"] = null;
    $_SESSION["role"] = null;

    unset($_SESSION["pages"]);


} else {
//    if (isset($_SESSION['client_id'])) {
//
//        if($_SESSION['client_id']==2){
//            $util_obj->redirect_to( "dash1.php" );
//        }else{
    $util_obj->redirect_to( "dashboardAll.php" );
//        }
//        $util_obj->redirect_to("dashboardAll.php");
//
//
//    }
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/material.min.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href=".css/datepicker.css">
    <link rel="stylesheet" type="text/css" href="less/datepicker.less">
    <link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css"/>
    <title>ezyagric_v2-Login</title>
    <style>
        input {
            text-align: left !important;
            padding-left: 20px !important;
            border: solid #c0c0c0 1px !important;
            border-radius: 4px !important;
        }
    </style>

</head>

<body style="background: url('images/woman.jpg');
      background-position:center; ">
<!--<nav class="navbar navbar-default teal">
  <div class="container">
    <div class="navbar-header logo-title">
      <a class="navbar-brand" href="#">
        <img alt="Brand"  src="images/logo.png">
      </a>
    </div>
  </div>
</nav>-->

<div class="container top">
    <div class="row">

        <div class="col-sm-6 ">
            <h1 class="primary-light"><b><i>EZY</i></b> AGRIC</h1><br/>
            <p class="secondary-light">Your number one solution<br/> for all your profiling needs.</p>
        </div>
        <div class="col-sm-6">
            <form method="post" action="./form_actions/clients_login.php" class="form-signin bg" style="background-color: #f9f9f9;">
                <h3 class="form-signin-heading no-margin">Sign in</h3>
                <div class="form-group">
                    <br/>
                    <input type="text" name="username" class="form-control" id="username" require=""
                           placeholder="Email Address"
                           style="background-color:#fff; border-radius:10px; text-align:center">
                </div>
                <div class="form-group">

                    <input type="password" name="password" class="form-control" id="password" require=""
                           placeholder="Password" style="background-color:#fff; border-radius:10px; text-align:center">
                </div>
                <a href="#" class="text-center forgot">Forgot password?</a>
                <input type="submit" name="login" class="btn btn-large btn-success center" value="Sign In"/>
                <br/><br/>

            </form>
        </div>
    </div>
</div>
<style>
    footer {
        background: transparent;
        color: #333
    }

    footer #copy {
        color: #444;
        font-weight: 100;
    }
</style>

<?php include("include/footer.php"); ?>