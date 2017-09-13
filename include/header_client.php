<!DOCTYPE html>
<?php
session_start();
#includes
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";

$util_obj = new Utilties();
$db = new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$mCrudFunctions->delete('input_reciept_temp_tb', "1");
unset($_SESSION['inputs']);

if (!isset($_SESSION['client_id'])) {
    //redirect back to original page
    $util_obj->redirect_to("index.php?logout=1");
}

$path = $_SERVER["PHP_SELF"];

//$connect = mysqli_connect("localhost","root","","akorionc_ezyagric");
//$query = "select biodata_farmer_gender, count(*) as number from dataset_67 GROUP BY biodata_farmer_gender";
//$result = mysqli_query($connect,$query)or die("not querying");

?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/material.min.css">

    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--<script src="https://maps.googleapis.com/maps/api/js"></script>-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChJn8njjZxU_B2LtFFjxR5c3M9gTnR0xo&callback=initMap"
            type="text/javascript"></script>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/datepicker.css">
    <link rel="stylesheet" type="text/css" href="less/datepicker.less">

    <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/css/bootstrapValidator.min.css">

    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/sweetalert.css">
    <link rel="icon" type="image/png" href="images/ezy-fav.png"/>
    <link rel="stylesheet" href="css/morris.css">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">

    <!-- Select2 -->
    <link href="js/select2/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="datepicker/css/bootstrap-datepicker3.css">

    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="css/dataTables.min.css" >

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.js"></script>
    <![endif]-->
    <title>EZY AGRIC</title>
    <script type="text/javascript" src="js/sorttable.js"></script>

<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>-->
</head>
<body>
<div class="navbar navbar-default static-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".navbar-responsive-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="#"><?php if (isset($_SESSION["account_name"])) {
                    $user_account = (strlen($_SESSION["account_name"]) > 25) ? substr($_SESSION["account_name"], 0, 25) . '...' : $_SESSION["account_name"];
                    echo $user_account;
                } ?></a>
        </div>

        <div class="navbar-collapse collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><?php

                    if($_SESSION["account_name"] == "Insurance"){
                        echo "<a href=\"./insuranceDash.php\">Dashboard</a>";
                    } elseif ($_SESSION['account_name'] == "Ankole Coffee Producers Cooperative Union Ltd"){
                        echo "<a href='./acpudash.php'>Dashboard</a>";
                    }elseif ($_SESSION['client_id'] == "15"){
                        echo "<a href='./dairyDashboard.php'>Dashboard</a>";
                    } else {
                        echo "<a href=\"./dashboardAll.php\" >Dashboard</a>";
                    } ?>
                </li>
                <li class="dropdown">
                    <a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Statistics<b
                                class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="./stat_farmers.php">Farmers</a></li>
                        <!--  <li><a href="stat_vas.php">Village Agents</a></li>-->
                    </ul>
                </li>
            </ul>
            <form class="navbar-form navbar-left" style="font-size:18px;">
                <?php
                $page = basename($_SERVER['PHP_SELF']);

                switch ($page) {
                    case  "view.php":
                        echo "<input id=\"search_holder\" type=\"text\" class=\"form-control col-lg-8\" placeholder=\"Search\">";
                        break;
//                    case "dashboardAll.php":
//                        echo "<input id=\"search_holder\" type=\"text\" class=\"form-control col-lg-8\" placeholder=\"Search\">";
//                        break;
                }

                ?>
            </form>
            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                    <a href="#" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Account profile <b
                                class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php
                        if ($_SESSION["user_role"] == 1 || $_SESSION["user_role"] == 3) {
                            echo '<li><a href="#changepassword" data-toggle="modal" data-target="#changepassword">Change Password</a></li>';
                            echo '<li><a href="settings.php" >User Settings</a></li>';
                            echo '<li><a href="logs.php" >System Logs</a></li>';
                            echo '<li><a href="system_constants.php" >Default Options</a></li>';
                        }
                        ?>
                        <li><a href="help.php">Help</a></li>
                        <li><a href="./index.php?logout=1">Logout</a></li>
                    </ul>
                </li>
<!--                <li><a href="#" style="font-weight:800;">--><?php //if (isset($_SESSION["user_account"])) {
//                            $user_account = (strlen($_SESSION["user_account"]) > 20) ? substr($_SESSION["user_account"], 0, 20) . '...' : $_SESSION["user_account"];
//                            echo "Hi! " . $user_account;
//                        } ?><!--</a>-->
<!--                </li>-->


            </ul>
        </div>
    </div>
</div>