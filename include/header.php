<!DOCTYPE html>
<?php
ini_set('upload_max_filesize', '60M');
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
if(!isset($_SESSION['user_id'])){
 //redirect back to original page
 $util_obj->redirect_to( "index.php?logout=1" );
}


?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../css/material.min.css">
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script src="js/sorttable.js"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="../css/datepicker.css">
<link rel="stylesheet" type="text/css" href="../less/datepicker.less">
<link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/css/bootstrapValidator.min.css">
<link rel="stylesheet" type="text/css" href="../css/custom.css">
<link rel="stylesheet" type="text/css" href="../css/select.css">
<link rel="stylesheet" type="text/css" href="../css/build.css">
<link rel="stylesheet" type="text/css" href="../css/sweetalert.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.js"></script>
      <script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.js"></script>
    <![endif]-->
  <title>Welcome to akorion: ezyagric_v2</title>


</head>
<body>

<div class="navbar navbar-default static-top" role="navigation">
<div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
       <a class="navbar-brand" href="#">Akorion Logo</a>
    </div>
    <div class="navbar-collapse collapse navbar-responsive-collapse">
        <ul class="nav navbar-nav navbar-right">
             <li><a href="index.php?logout=1">Logout</a></li>
        </ul>
    </div>
    </div>
</div>
    
   
