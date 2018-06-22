<?php
session_start();
#includes
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";

$util_obj = new Utilties();
$mCrudFunctions = new CrudFunctions();

switch ($_POST["token"]) {

    case  "farm_inputs" :
        if (isset($_POST["dataset_id"]) &&
            $_POST["dataset_id"] != '' &&
            isset($_POST["farmer_id"]) &&
            $_POST["farmer_id"] != '' &&
            isset($_POST["fertilizer_selected"]) &&
            $_POST["fertilizer_selected"] != '' &&
            isset($_POST["seed_selected"]) &&
            $_POST["seed_selected"] != '' &&
            isset($_POST["herbicide_selected"]) &&
            $_POST["herbicide_selected"] != ''
        ) {

            $dataset_id = $_POST["dataset_id"];
            $farmer_id = $_POST["farmer_id"];
            $fertilizer_selected = $_POST["fertilizer_selected"];
            $seed_selected = $_POST["seed_selected"];
            $herbicide_selected = $_POST["herbicide_selected"];
            $tractor_money = $_POST["tractor_money"];
            $cash_taken = $_POST["cash_taken"];
            $herbicide_taken = $_POST["herbicide_taken"];
            $seed_taken = $_POST["seed_taken"];
            $fertilizer_taken = $_POST["fertilizer_taken"];


            ////////////////////////////////////////////////////////////////////fertilizer starts
            if ($fertilizer_taken != "" && ($fertilizer_taken > 0)) {
                $fertilizer_columns = array("farmer_id", "fertilizer", "quantity");
                $fertilizer_values = array();

                $fertilizer_values[0] = $farmer_id;
                $fertilizer_values[1] = $fertilizer_selected;
                $fertilizer_values[2] = $fertilizer_taken;
                // print_r($fertilizer_columns);
                $columns_value = "quantity='$fertilizer_taken'";
                $where = "farmer_id='$farmer_id' AND fertilizer='$fertilizer_selected'";
                $flag = $mCrudFunctions->create_table_tb("fertilizer", $dataset_id, $fertilizer_columns);

                if ($flag == 0) {
                    $count = $mCrudFunctions->get_count("fertilizer_" . $dataset_id, $where);

                    if ($count > 0) {

                        $yes = $mCrudFunctions->update("fertilizer_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("fertilizer_" . $dataset_id, $fertilizer_columns, $fertilizer_values);
                        echo $yes;
                    }

                } else {

                    $count = $mCrudFunctions->get_count("fertilizer_" . $dataset_id, " farmer_id='$farmer_id' AND fertilizer='$fertilizer_selected'");

                    if ($count > 0) {
                        $yes = $mCrudFunctions->update("fertilizer_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("fertilizer_" . $dataset_id, $fertilizer_columns, $fertilizer_values);
                        echo $yes;
                    }

                }

            }
            //////////////////////////////////////////////////////////////////////////fertilizer Ends

            //echo $herbicide_taken." ";
            ///////////////////////////////////////////////////////////////////herbicides starts
            if ($herbicide_taken != "" && ($herbicide_taken > 0)) {
                $herbicide_columns = array("farmer_id", "herbicide", "quantity");
                $herbicide_values = array();

                $herbicide_values[0] = $farmer_id;
                $herbicide_values[1] = $herbicide_selected;
                $herbicide_values[2] = $herbicide_taken;
                // print_r($fertilizer_columns);
                $columns_value = "quantity='$herbicide_taken'";
                $where = "farmer_id='$farmer_id' AND herbicide='$herbicide_selected'";
                $flag = $mCrudFunctions->create_table_tb("herbicide", $dataset_id, $herbicide_columns);

                if ($flag == 0) {
                    $count = $mCrudFunctions->get_count("herbicide_" . $dataset_id, $where);

                    if ($count > 0) {

                        $yes = $mCrudFunctions->update("herbicide_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("herbicide_" . $dataset_id, $herbicide_columns, $herbicide_values);
                        echo $yes;
                    }

                } else {

                    $count = $mCrudFunctions->get_count("herbicide_" . $dataset_id, " farmer_id='$farmer_id' AND herbicide='$herbicide_selected'");

                    if ($count > 0) {
                        $yes = $mCrudFunctions->update("herbicide_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("herbicide_" . $dataset_id, $herbicide_columns, $herbicide_values);
                        echo $yes;
                    }

                }

            }
            //////////////////////////////////////////////////////////////////////////herbicide Ends

            ///////////////////////////////////////////////////////////////////$seed_taken starts
            if ($seed_taken != "" && ($seed_taken > 0)) {
                $seeds_columns = array("farmer_id", "seed", "quantity");
                $seed_values = array();

                $seed_values[0] = $farmer_id;
                $seed_values[1] = $seed_selected;
                $seed_values[2] = $seed_taken;
                // print_r($fertilizer_columns);
                $columns_value = "quantity='$seed_taken'";
                $where = "farmer_id='$farmer_id' AND seed='$seed_selected'";
                $flag = $mCrudFunctions->create_table_tb("seed", $dataset_id, $seeds_columns);

                if ($flag == 0) {
                    $count = $mCrudFunctions->get_count("seed_" . $dataset_id, $where);

                    if ($count > 0) {

                        $yes = $mCrudFunctions->update("seed_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("seed_" . $dataset_id, $seeds_columns, $seed_values);
                        echo $yes;
                    }

                } else {

                    $count = $mCrudFunctions->get_count("seed_" . $dataset_id, " farmer_id='$farmer_id' AND seed='$seed_selected'");

                    if ($count > 0) {
                        $yes = $mCrudFunctions->update("seed_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("seed_" . $dataset_id, $seeds_columns, $seed_values);
                        echo $yes;
                    }

                }

            }
            //////////////////////////////////////////////////////////////////////////$seed_taken Ends

            ////////////////////////////////////////////////////////////////////cash taken starts
            if ($cash_taken != "" && ($cash_taken > 0)) {
                $cash_columns = array("farmer_id", "cash_taken");
                $cash_values = array();

                $cash_values[0] = $farmer_id;
                $cash_values[1] = $cash_taken;

                // print_r($fertilizer_columns);
                $columns_value = "cash_taken='$cash_taken'";
                $where = "farmer_id='$farmer_id'";
                $flag = $mCrudFunctions->create_table_tb("cash", $dataset_id, $cash_columns);

                if ($flag == 0) {
                    $count = $mCrudFunctions->get_count("cash_" . $dataset_id, $where);

                    if ($count > 0) {

                        $yes = $mCrudFunctions->update("cash_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("cash_" . $dataset_id, $cash_columns, $cash_values);
                        echo $yes;
                    }

                } else {

                    $count = $mCrudFunctions->get_count("cash_" . $dataset_id, " farmer_id='$farmer_id'");

                    if ($count > 0) {
                        $yes = $mCrudFunctions->update("cash_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("cash_" . $dataset_id, $cash_columns, $cash_values);
                        echo $yes;
                    }

                }

            }
            //////////////////////////////////////////////////////////////////////////cash Ends

            ////////////////////////////////////////////////////////////////////tractor_money starts
            if ($tractor_money != "" && ($tractor_money > 0)) {
                $tractormoney_columns = array("farmer_id", "tractor_money");
                $tractormoney_values = array();

                $tractormoney_values[0] = $farmer_id;
                $tractormoney_values[1] = $tractor_money;

                // print_r($fertilizer_columns);
                $columns_value = "tractor_money='$tractor_money'";
                $where = "farmer_id='$farmer_id'";
                $flag = $mCrudFunctions->create_table_tb("tractormoney", $dataset_id, $tractormoney_columns);

                if ($flag == 0) {
                    $count = $mCrudFunctions->get_count("tractormoney_" . $dataset_id, $where);

                    if ($count > 0) {

                        $yes = $mCrudFunctions->update("tractormoney_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("tractormoney_" . $dataset_id, $tractormoney_columns, $tractormoney_values);
                        echo $yes;
                    }

                } else {

                    $count = $mCrudFunctions->get_count("tractormoney_" . $dataset_id, " farmer_id='$farmer_id'");

                    if ($count > 0) {
                        $yes = $mCrudFunctions->update("tractormoney_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("tractormoney_" . $dataset_id, $tractormoney_columns, $tractormoney_values);
                        echo $yes;
                    }

                }

            }
            //////////////////////////////////////////////////////////////////////////$tractor_money Ends


        }

        break;

    case  "farm_yield" :

        if (isset($_POST["dataset_id"]) &&
            $_POST["dataset_id"] != '' &&
            isset($_POST["farmer_id"]) &&
            $_POST["farmer_id"] != '' &&
            isset($_POST["yield"]) &&
            $_POST["yield"] != ''
        ) {

            $dataset_id = $_POST["dataset_id"];
            $farmer_id = $_POST["farmer_id"];
            $yield = $_POST["yield"];
            //echo $yield;
            ////////////////////////////////////////////////////////////////////yields starts
            if ($yield != "" && ($yield > 0)) {
                $yield_columns = array("farmer_id", "yield");
                $yield_values = array();

                $yield_values[0] = $farmer_id;
                $yield_values[1] = $yield;

                // print_r($fertilizer_columns);
                $columns_value = "yield='$yield'";
                $where = "farmer_id='$farmer_id'";
                $flag = $mCrudFunctions->create_table_tb("yield", $dataset_id, $yield_columns);

                if ($flag == 0) {
                    $count = $mCrudFunctions->get_count("yield_" . $dataset_id, $where);

                    if ($count > 0) {

                        $yes = $mCrudFunctions->update("yield_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("yield_" . $dataset_id, $yield_columns, $yield_values);
                        echo $yes;
                    }

                } else {

                    $count = $mCrudFunctions->get_count("yield_" . $dataset_id, " farmer_id='$farmer_id'");

                    if ($count > 0) {
                        $yes = $mCrudFunctions->update("yield_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("yield_" . $dataset_id, $yield_columns, $yield_values);
                        echo $yes;
                    }

                }

            }
            //////////////////////////////////////////////////////////////////////////yields Ends

        }
        break;

    case  "farm_cash_returns" :

        if (isset($_POST["dataset_id"]) &&
            $_POST["dataset_id"] != '' &&
            isset($_POST["farmer_id"]) &&
            $_POST["farmer_id"] != ''
        ) {

            $dataset_id = $_POST["dataset_id"];
            $farmer_id = $_POST["farmer_id"];
            $tractor_money_returned = $_POST["tractor_money_returned"];
            $cash_returned = $_POST["cash_returned"];


            ////////////////////////////////////////////////////////////////////tractor_money_returned starts
            if ($tractor_money_returned != "" && ($tractor_money_returned > 0)) {

                $tractor_money_returned_columns = array("farmer_id", "tractor_money_returned");
                $tractor_money_returned_values = array();

                $tractor_money_returned_values[0] = $farmer_id;
                $tractor_money_returned_values[1] = $tractor_money_returned;

                // print_r($fertilizer_columns);
                $columns_value = " tractor_money_returned='$tractor_money_returned'";
                $where = "farmer_id='$farmer_id'";
                $flag = $mCrudFunctions->create_table_tb("tractor_money_returned", $dataset_id, $tractor_money_returned_columns);

                if ($flag == 0) {
                    $count = $mCrudFunctions->get_count("tractor_money_returned_" . $dataset_id, $where);

                    if ($count > 0) {

                        $yes = $mCrudFunctions->insert_into_table_tb("tractor_money_returned_" . $dataset_id, $tractor_money_returned_columns, $tractor_money_returned_values);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("tractor_money_returned_" . $dataset_id, $tractor_money_returned_columns, $tractor_money_returned_values);
                        echo $yes;
                    }

                } else {

                    $count = $mCrudFunctions->get_count("tractor_money_returned_" . $dataset_id, " farmer_id='$farmer_id'");

                    if ($count > 0) {
                        $yes = $mCrudFunctions->insert_into_table_tb("tractor_money_returned_" . $dataset_id, $tractor_money_returned_columns, $tractor_money_returned_values);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("tractor_money_returned_" . $dataset_id, $tractor_money_returned_columns, $tractor_money_returned_values);
                        echo $yes;
                    }

                }

            }
            //////////////////////////////////////////////////////////////////////////tractor_money_returned Ends


            ////////////////////////////////////////////////////////////////////cash_returned starts
            if ($cash_returned != "" && ($cash_returned > 0)) {

                $cash_returned_columns = array("farmer_id", "cash_returned");
                $cash_returned_values = array();

                $cash_returned_values[0] = $farmer_id;
                $cash_returned_values[1] = $cash_returned;

                // print_r($fertilizer_columns);
                $columns_value = "  cash_returned='$cash_returned'";
                $where = "farmer_id='$farmer_id'";
                $flag = $mCrudFunctions->create_table_tb("cash_returned", $dataset_id, $cash_returned_columns);

                if ($flag == 0) {
                    $count = $mCrudFunctions->get_count("cash_returned_" . $dataset_id, $where);

                    if ($count > 0) {

                        $yes = $mCrudFunctions->insert_into_table_tb("cash_returned_" . $dataset_id, $cash_returned_columns, $cash_returned_values);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("cash_returned_" . $dataset_id, $cash_returned_columns, $cash_returned_values);
                        echo $yes;
                    }

                } else {

                    $count = $mCrudFunctions->get_count("cash_returned_" . $dataset_id, " farmer_id='$farmer_id'");

                    if ($count > 0) {
                        $yes = $mCrudFunctions->insert_into_table_tb("cash_returned_" . $dataset_id, $cash_returned_columns, $cash_returned_values);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("cash_returned_" . $dataset_id, $cash_returned_columns, $cash_returned_values);
                        echo $yes;
                    }

                }

            }
            //////////////////////////////////////////////////////////////////////////cash_returned Ends

        }
        break;

    case  "farm_acerage":
        if (isset($_POST["dataset_id"]) &&
            $_POST["dataset_id"] != '' &&
            isset($_POST["farmer_id"]) &&
            $_POST["farmer_id"] != '' &&
            isset($_POST["acerage"]) &&
            $_POST["acerage"] != ''
        ) {

            $dataset_id = $_POST["dataset_id"];
            $farmer_id = $_POST["farmer_id"];
            $acerage = $_POST["acerage"];
            //echo $acerage;
            if ($acerage != "" && ($acerage > 0)) {
                $acerage_columns = array("farmer_id", "acerage");
                $acerage_values = array();

                $acerage_values[0] = $farmer_id;
                $acerage_values[1] = $acerage;

                // print_r($fertilizer_columns);
                $columns_value = "acerage='$acerage'";
                $where = "farmer_id='$farmer_id'";
                $flag = $mCrudFunctions->create_table_tb("acerage", $dataset_id, $acerage_columns);
                echo $flag;
                if ($flag == 0) {
                    $count = $mCrudFunctions->get_count("acerage_" . $dataset_id, $where);

                    if ($count > 0) {
                        $yes = $mCrudFunctions->update("acerage_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("acerage_" . $dataset_id, $acerage_columns, $acerage_values);
                        echo $yes;
                    }

                } else {
                    $count = $mCrudFunctions->get_count("acerage_" . $dataset_id, " farmer_id='$farmer_id'");

                    if ($count > 0) {
                        $yes = $mCrudFunctions->update("acerage_" . $dataset_id, $columns_value, $where);
                        echo $yes;
                    } else {
                        $yes = $mCrudFunctions->insert_into_table_tb("acerage_" . $dataset_id, $acerage_columns, $acerage_values);
                        echo $yes;
                    }
                }
            }
        }
        break;

    case  "outgrower_dash":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $farmers = 0;
        $acerage = 0;
        $cash_given_out = 0;
        $cash_returned = 0;
        $taken_loans = 0;
        $taken_no_loans = 0;
        $tractor_money_returned = 0;
        $seed_taken = 0;
        $yield = 0;
        $insured = 0;
        $not_insured = 0;

        foreach ($rows as $row) {

            $farmers += $mCrudFunctions->get_count("dataset_" . $row['id'], 1);
            $acreage = $mCrudFunctions->fetch_rows("total_acerage_tb", "ttl_acerage", "dataset_id=" . $row['id'])[0]['ttl_acerage'];
            if ($acreage < 1) {
                $acreage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "maize_production_data_total_land_under_production", 1);
            }
            if ($_SESSION['client_id'] == 5) {
                $acreage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "coffee_production_data_number_of_acres_of_coffee", 1);
            }
            if ($_SESSION['account_name'] == "Kiima Foods") {
                $acreage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "crop_production_data_maize_production_land", 1);
                $cash_given_out += $mCrudFunctions->get_sum("dataset_" . $row['id'], "crop_production_data_money_used_for_fertilizers", 1);
            }

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $cash_given_out += $mCrudFunctions->get_sum("dataset_" . $row['id'], "maize_production_data_money_used_for_fertilizers", 1);
            }
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                echo "the table exists";
                $taken_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_loan_accessed_before='yes'");
            }
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $taken_no_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_loan_accessed_before='no'");
            }

            if ($mCrudFunctions->check_table_exists("tractor_money_returned_" . $row['id'])) {
                $tractor_money_returned = $tractor_money_returned + $mCrudFunctions->get_sum("tractor_money_returned_" . $row['id'], "tractor_money_returned", 1);
            }

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_crop_insurance_accessed_before='yes'", 1);
                if ($_SESSION["account_name"] == "Insurance") {
                    $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "1");
                }
            }
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $not_insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_crop_insurance_accessed_before='no'", 1);
            }
        }

        echo "<div class=\"col-md-3 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#3a3; padding:10px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <span class=\"fa fa-users fa-3x\"></span>
                       </div>
                       <div class=\"col-md-9\">
                           <h4> <a href='dash.php'> Out Grower Farmers </a> </h4>
                         <a style=\"font-size:15px; color:blue\"><b>Total Farmers: $farmers</b></a> <br>
                           <span style=\"font-size:13px; color:green\" class='acres'>Total Acreage: $acreage</span> 
                            <a href='dash.php'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>

                       </div>
                   </div>
                 </div>
                 <div class=\"col-md-3 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#822; padding:15px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <i class=\"fa fa-money fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                       <h4> <a href='expenditure.php'> Fertlizer Expenditure </a> </h4>
                        
                           <a style=\"font-size:15px; color:blue\"><b>UGX " . number_format($cash_given_out) . "</b></a> <br>
                       <!--    <a style=\"font-size:13px; color:green\">UGX " . number_format($cash_returned) . " returned</a>  -->
                           <a href='expenditure.php'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>
                       </div>
                   </div>
                 </div>

                 <div class=\"col-md-3 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color: #00a1b5; padding:15px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <i class=\"fa fa-sitemap fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                       <h4> <a href='farmerloans.php'> Loans accessed </a> </h4>
                         
                           <a style=\"font-size:15px; color:blue\"><b> " . number_format($taken_loans) . " farmers got </b></a> <br>
                           <a style=\"font-size:13px; color:green\"> " . number_format($taken_no_loans) . " farmers didnt get</a>
                           <a href='farmerloans.php'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>
                       </div>
                   </div>
                 </div>

                 <div class=\"col-md-3 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:orange; padding:15px; border-radius:2px;color:#fff; margin-top: -20px; \">
                           <i class=\"fa fa-leaf fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                           <h4>Insured farmers</h4>
                           <a style=\"font-size:15px; color:blue\"><b> Insured: $insured</b></a> <br>
                           <a style=\"font-size:13px; color:green\">Not insured: $not_insured</a>

                       </div>
                   </div>
                 </div>
 ";


        break;

    case  "acpcu_outgrower_dash":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $farmers = 0;
        $acerage = 0;
        $cash_given_out = 0;
        $cash_returned = 0;
        $taken_loans = 0;
        $taken_no_loans = 0;
        $tractor_money_returned = 0;
        $seed_taken = 0;
        $yield = 0;
        $insured = 0;
        $not_insured = 0;

        foreach ($rows as $row) {
            $type = $row['dataset_type'];
            $dataset_id = $row['id'];
            $key = $util_obj->encrypt_decrypt("encrypt", $dataset_id);

            $farmers += $mCrudFunctions->get_count("dataset_" . $row['id'], 1);
            $acerage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "production_acreage", 1);
            if ($_SESSION['account_name'] == "Ankole Coffee Producers Cooperative Union Ltd" || $_SESSION["account_name"] == "Insurance") $acerage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "production_data_land_size", 1);
//            elseif($_SESSION['client_id'] == 5) { $acerage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "coffee_production_data_number_of_acres_of_coffee", 1); }

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $cash_given_out += $mCrudFunctions->get_distinct_count("soil_results_" . $row['id'], " COUNT(DISTINCT(TRIM(`cooperative`)))", "1 ");
            }

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                echo "the table exists";
                $taken_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_loan_accessed_before='yes'");
            }
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $taken_no_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_loan_accessed_before='no'");
            }

            if ($mCrudFunctions->check_table_exists("tractor_money_returned_" . $row['id'])) {
                $tractor_money_returned = $tractor_money_returned + $mCrudFunctions->get_sum("tractor_money_returned_" . $row['id'], "tractor_money_returned", 1);
            }

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_crop_insurance_accessed_before='yes'", 1);
                if ($_SESSION["account_name"] == "Insurance") {
                    $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "1");
                }
            }
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $not_insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_crop_insurance_accessed_before='no'", 1);
            }
        }

        echo "
                 <div class=\"col-md-4 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  \">
                       <div class=\"col-md-3\" style=\"background-color:#822; padding:15px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <i class=\"fa fa-money fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                       <h4> <a href='cooperatives.php'> Cooperatives </a> </h4>
                        
                           <a style=\"font-size:15px; color:blue\"><b>Number of Cooperatives: " . number_format($cash_given_out) . "</b></a> <br>
                           <!--a style=\"font-size:13px; color:green\">UGX " . number_format($cash_returned) . " returned</a-->
                           <a href='cooperatives.php'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>
                       </div>
                   </div>
                 </div>
                 
                 <div class=\"col-md-4 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#3a3; padding:10px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <span class=\"fa fa-users fa-3x\"></span>
                       </div>
                       <div class=\"col-md-9\">
                           <h4> <a href='view.php?token=$key&type=$type'> Farmers </a> </h4>
                         <a style=\"font-size:15px; color:blue\"><b>Total Farmers: $farmers</b></a> <br>
                           <!--span style=\"font-size:13px; color:green\">$acerage Acres</span--> 
                            <a href='view.php?token=$key&type=$type'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>

                       </div>
                   </div>
                 </div>              

                 <div class=\"col-md-4 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color: #00a1b5; padding:15px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <i class=\"fa fa-sitemap fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                       <h4> <a href='#'> Acreage </a> </h4>
                         
                           <a style=\"font-size:15px; color:blue\"><b>Total Acreage: " . number_format($acerage) . "</b></a> <br>
                           <!--a style=\"font-size:13px; color:green\"> " . number_format($taken_no_loans) . " farmers didnt get</a-->
                           <a href='#'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>
                       </div>
                   </div>
                 </div>";
        break;
    case "savana_dash":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $farmers = 0;
        $acreage = 0;
        $cash_given_out = 0;
        $yield = 0;
        $farmer_acreage = 0;
        $enterprise_farmers = array();

        foreach ($rows as $row) {
            $type = $row['dataset_type'];
            $dataset_id = $row['id'];
            $key = $util_obj->encrypt_decrypt("encrypt", $dataset_id);

            $farmers += $mCrudFunctions->get_count("dataset_" . $row['id'], 1);

            $acreage += $mCrudFunctions->fetch_rows("total_acerage_tb", "ttl_acerage", "dataset_id=" . $row['id'])[0]['ttl_acerage'];
            if ($acreage < 1) {
                $acreage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "coffee_production_data_number_of_acres_of_coffee", 1);
            }

            $crop_farmers = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "DISTINCT(crop_production_data_crop_name) as crop,
                                COUNT(biodata_farmer_name) as farmers", "1 GROUP BY crop_production_data_crop_name");
            foreach ($crop_farmers as $res){
                $items = new stdClass();
                $items->crop =  ucfirst(trim(strtolower($res['crop'])));
                $items->no_of_farmers = $res['farmers'];

                array_push($enterprise_farmers, $items);
            }

//            $results = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "*", "1");
//            foreach ($results as $res) {
//                $uuid = $util_obj->remove_apostrophes($res['meta_instanceID']);
////                $price_per_kg = $util_obj->remove_apostrophes($res['production_data_price_per_kg']);
////                $insured_yield = $util_obj->remove_apostrophes($res['production_data_insured_yield_in_kgs']);
//
//                //----------  calculating total acreage for the farmers ------------------
//                $acares = array();
//                $farmer_acreage = 0;
//                $gardens_table = "garden_" . $row['id'];
//                if ($mCrudFunctions->check_table_exists($gardens_table) > 0) {
//                    $gardens = $mCrudFunctions->fetch_rows($gardens_table, " DISTINCT PARENT_KEY_ ", " PARENT_KEY_ LIKE '$uuid%' ");
//
//                    if (sizeof($gardens) < 0) {
//
//                    } else {
//                        $z = 1;
//                        foreach ($gardens as $garden) {
//                            $keyz = $uuid . "/gardens[$z]";
//                            $data_rows = $mCrudFunctions->fetch_rows($gardens_table, "garden_gps_point_Latitude,garden_gps_point_Longitude", " PARENT_KEY_ ='$keyz'");
//                            if (sizeof($data_rows) == 0) {
//                                $data_rows = $mCrudFunctions->fetch_rows($gardens_table, "garden_gps_point_Latitude,garden_gps_point_Longitude", " PARENT_KEY_ ='$uuid'");
//                            }
//                            $latitudes = array();
//                            $longitudes = array();
//
//                            foreach ($data_rows as $row_) {
//                                array_push($longitudes, $row_['garden_gps_point_Longitude']);
//                                array_push($latitudes, $row_['garden_gps_point_Latitude']);
//                            }
//                            $acerage = $util_obj->get_acerage_from_geo($latitudes, $longitudes);
//                            array_push($acares, $acerage);
//                            $z++;
//                        }
//                        $total_gardens = sizeof($gardens);
//
//                        if ($total_gardens != 0) {
//                            $farmer_acreage += round(array_sum($acares)/*$total_gardens*/, 2);
////                            $value_of_insured_yield = $farmer_acreage * $price_per_kg * $insured_yield;
////                            $premium = 0.05 * $value_of_insured_yield;
////                            $levy = 0.005 * $premium;
////                            $vat = 0.18 * ($premium + $levy);
////                            if ($farmer_acreage <= 5) {
////                                $subsidy = 0.5 * $premium;
////                            } else {
////                                $subsidy = 0.3 * $premium;
////                            }
////                            $real_premium = ($premium - $subsidy) + $levy + $vat;
////                            $total_premium += $real_premium;
//                        }
//                    }
//
//                }
////                else {
////                    $farmer_acreage = $util_obj->remove_apostrophes($row['production_data_land_size']);
////                    $value_of_insured_yield = $farmer_acreage * $price_per_kg * $insured_yield;
////                    $premium = 0.05 * $value_of_insured_yield;
////                    $levy = 0.005 * $premium;
////                    $vat = 0.18 * ($premium + $levy);
////                    if ($farmer_acreage <= 5) {
////                        $subsidy = 0.5 * $premium;
////                    } else {
////                        $subsidy = 0.3 * $premium;
////                    }
////                    $real_premium = ($premium - $subsidy) + $levy + $vat;
////                    $total_premium += $real_premium;
////                }
//            }
//
//
//            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                $cash_given_out += $mCrudFunctions->get_sum("dataset_" . $row['id'], "maize_production_data_money_used_for_fertilizers", 1);
//            }
//
//            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
////                echo "the table exists";
//                $taken_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_loan_accessed_before='yes'");
//            }
//            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                $taken_no_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_loan_accessed_before='no'");
//            }
//
//            if ($mCrudFunctions->check_table_exists("tractor_money_returned_" . $row['id'])) {
//                $tractor_money_returned = $tractor_money_returned + $mCrudFunctions->get_sum("tractor_money_returned_" . $row['id'], "tractor_money_returned", 1);
//            }
//
//            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_crop_insurance_accessed_before='yes'", 1);
//                if ($_SESSION["account_name"] == "Insurance") {
//                    $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "1");
//                }
//            }
//            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                $not_insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_crop_insurance_accessed_before='no'", 1);
//            }
        }

        $tmp = new stdClass();
        $tmp->crop = 'Coffee';
        $tmp->no_of_farmers = $mCrudFunctions->get_count("dataset_" . 71, 1);
        array_push($enterprise_farmers, $tmp);

        for($i=0; $i < count($enterprise_farmers); $i++){
            if($enterprise_farmers[$i]->crop == $enterprise_farmers[$i+1]->crop){
                $enterprise_farmers[$i]->no_of_farmers += $enterprise_farmers[$i+1]->no_of_farmers;
                unset($enterprise_farmers[$i+1]);
                $enterprise_farmers = array_values($enterprise_farmers);
            }
        }

        echo "<div class=\"col-md-6 col-sm-6 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#3a3; padding:10px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <span class=\"fa fa-users fa-3x\"></span>
                       </div>
                       <div class=\"col-md-9\">
                           <h4> <a href = 'dash.php'> Out Grower Farmers </a ></h4>
                            <a style = \"font-size:15px; color:blue\"><b> Total Farmers: $farmers </b></a><br>";
                            foreach($enterprise_farmers as $rs){ 
                                echo "<span style =\"font-size:15px; color:blue\" >". $rs->crop .": ". $rs->no_of_farmers ."</span><br>";
                            }
                        echo "<a href = 'dash.php'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style = 'color: green; font-size: 20px;'> </span></a>
                       </div>
                   </div >
                 </div >
                 <div class=\"col-md-6 col-sm-6 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#822; padding:15px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <i class=\"fa fa-money fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                       <h4> <a href='#'> Acreage </a> </h4>                        
                           <span href='#' style=\"font-size:15px; color:blue\"><b>Total Acreage: " . number_format($acreage) . "</b></span> 
                           <a href='expenditure.php'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>
                       </div>
                   </div>
                 </div>
 ";


        break;

    case  "soil_test_dash":

        $cash_given_out = $mCrudFunctions->get_count("sample_analysis_results", "1");
        $total_pits = $mCrudFunctions->get_distinct_count("soil_profiles", "COUNT(DISTINCT(hole))", "1");

        echo "
                 <div class=\"col-md-6 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#822; padding:15px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <i class=\"fa fa-money fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                       <h4> <a href='#'> Samples </a> </h4>
                        
                           <a style=\"font-size:15px; color:blue\"><b>Total samples: " . number_format($cash_given_out) . "</b></a> <br> <br>
                       </div>
                   </div>
                 </div>
                 
                 <div class=\"col-md-6 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#3a3; padding:10px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <span class=\"fa fa-users fa-3x\"></span>
                       </div>
                       <div class=\"col-md-9\">
                           <h4> <a href='#'> Holes/Pits </a> </h4>
                         <a style=\"font-size:15px; color:blue\"><b>Total Holes: ".$total_pits."</b></a> <br>  <br>

                       </div>
                   </div>
                 </div>";
        break;

    case  "insurance_dash":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $farmers = 0;
        $acreage = 0;
        $cash_given_out = 0;
        $cash_returned = 0;
        $taken_loans = 0;
        $taken_no_loans = 0;
        $tractor_money_returned = 0;
        $seed_taken = 0;
        $yield = 0;
        $insured = 0;
        $not_insured = 0;
        $value_of_insured_yield = 0;
        $premium = 0;
        $levy = 0;
        $vat = 0;
        $subsidy = 0;
        $farmer_acreage = 0;
        $real_premium = 0;
        $total_premium = 0;

        foreach ($rows as $row) {
            $type = $row['dataset_type'];
            $dataset_id = $row['id'];
            $key = $util_obj->encrypt_decrypt("encrypt", $dataset_id);

            $farmers += $mCrudFunctions->get_count("dataset_" . $row['id'], 1);
            $acreage = $mCrudFunctions->fetch_rows("total_acerage_tb", "ttl_acerage", "dataset_id=" . $row['id'])[0]['ttl_acerage'];
            if ($acreage < 1) {
                $acreage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "production_data_land_size", 1);
            }

            $results = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "*", "1");

            foreach ($results as $res) {
                $uuid = $util_obj->remove_apostrophes($res['meta_instanceID']);
                $price_per_kg = $util_obj->remove_apostrophes($res['production_data_price_per_kg']);
                $insured_yield = $util_obj->remove_apostrophes($res['production_data_insured_yield_in_kgs']);

                $acares = array();
                $farmer_acreage = 0;
                $gardens_table = "garden_" . $row['id'];
                if ($mCrudFunctions->check_table_exists($gardens_table) > 0) {
                    $gardens = $mCrudFunctions->fetch_rows($gardens_table, " DISTINCT PARENT_KEY_ ", " PARENT_KEY_ LIKE '$uuid%' ");

                    if (sizeof($gardens) < 0) {

                    } else {
                        $z = 1;
                        foreach ($gardens as $garden) {
                            $keyz = $uuid . "/gardens[$z]";
                            $data_rows = $mCrudFunctions->fetch_rows($gardens_table, "garden_gps_point_Latitude,garden_gps_point_Longitude", " PARENT_KEY_ ='$keyz'");
                            if (sizeof($data_rows) == 0) {
                                $data_rows = $mCrudFunctions->fetch_rows($gardens_table, "garden_gps_point_Latitude,garden_gps_point_Longitude", " PARENT_KEY_ ='$uuid'");
                            }
                            $latitudes = array();
                            $longitudes = array();

                            foreach ($data_rows as $row_) {
                                array_push($longitudes, $row_['garden_gps_point_Longitude']);
                                array_push($latitudes, $row_['garden_gps_point_Latitude']);
                            }
                            $acerage = $util_obj->get_acerage_from_geo($latitudes, $longitudes);
                            array_push($acares, $acerage);
                            $z++;
                        }
                        $total_gardens = sizeof($gardens);

                        if ($total_gardens != 0) {
                            $farmer_acreage = round(array_sum($acares)/*$total_gardens*/, 2);
                            $value_of_insured_yield = $farmer_acreage * $price_per_kg * $insured_yield;
                            $premium = 0.05 * $value_of_insured_yield;
                            $levy = 0.005 * $premium;
                            $vat = 0.18 * ($premium + $levy);
                            if ($farmer_acreage <= 5) {
                                $subsidy = 0.5 * $premium;
                            } else {
                                $subsidy = 0.3 * $premium;
                            }
                            $real_premium = ($premium - $subsidy) + $levy + $vat;
                            $total_premium += $real_premium;
                        }
                    }

                } else {
                    $farmer_acreage = $util_obj->remove_apostrophes($row['production_data_land_size']);
                    $value_of_insured_yield = $farmer_acreage * $price_per_kg * $insured_yield;
                    $premium = 0.05 * $value_of_insured_yield;
                    $levy = 0.005 * $premium;
                    $vat = 0.18 * ($premium + $levy);
                    if ($farmer_acreage <= 5) {
                        $subsidy = 0.5 * $premium;
                    } else {
                        $subsidy = 0.3 * $premium;
                    }
                    $real_premium = ($premium - $subsidy) + $levy + $vat;
                    $total_premium += $real_premium;
                }
                // --------------- Premium Calculation ------------------
//                $value_of_insured_yield = $farmer_acreage * $price_per_kg * $insured_yield;
//                $premium = 0.05 * $value_of_insured_yield;
//                $levy = 0.005 * $premium;
//                $vat = 0.18 * ($premium + $levy);
//                if($farmer_acreage <= 5) { $subsidy = 0.5 * $premium; }
//                else{ $subsidy = 0.3 * $premium; }
//                $real_premium = ($premium - $subsidy) + $levy + $vat;
//                $total_premium += $real_premium;
            }


            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $cash_given_out += $mCrudFunctions->get_sum("dataset_" . $row['id'], "maize_production_data_money_used_for_fertilizers", 1);
            }

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                echo "the table exists";
                $taken_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_loan_accessed_before='yes'");
            }
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $taken_no_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_loan_accessed_before='no'");
            }

            if ($mCrudFunctions->check_table_exists("tractor_money_returned_" . $row['id'])) {
                $tractor_money_returned = $tractor_money_returned + $mCrudFunctions->get_sum("tractor_money_returned_" . $row['id'], "tractor_money_returned", 1);
            }

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_crop_insurance_accessed_before='yes'", 1);
                if ($_SESSION["account_name"] == "Insurance") {
                    $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "1");
                }
            }
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $not_insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "general_questions_crop_insurance_accessed_before='no'", 1);
            }
        }

        echo "<div class=\"col-md-4 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:orange; padding:15px; border-radius:2px;color:#fff; margin-top: -20px; \">
                           <i class=\"fa fa-leaf fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                           <h4> <a href='view.php?token=$key&type=$type'> Insured farmers </a> </h4>
                         <a href='#' style=\"font-size:15px; color:blue\"><b> Total Farmers: $farmers</b></a> <br>
                           <span style=\"font-size:13px; color:green\"><b> Total Acreage: $acreage </b></span> 
                            <a href='view.php?token=$key&type=$type'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>

                       </div>
                   </div>
                 </div>
                 <div class=\"col-md-4 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#822; padding:15px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <i class=\"fa fa-money fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                       <h4> <a href='expenditure.php'> Premium </a> </h4>
                        
                           <span href='#' style=\"font-size:15px; color:blue\"><b>Total Amount(UGX): " . number_format($total_premium) . "</b></span> 
                          
                           <a href='expenditure.php'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>
                       </div>
                   </div>
                 </div>

                 <div class=\"col-md-4 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color: #00a1b5; padding:15px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <i class=\"fa fa-sitemap fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                       <h4> <a href='farmerloans.php'> Loans accessed </a> </h4>
                         
                           <a href='#' style=\"font-size:15px; color:blue\"><b> " . number_format($taken_loans) . " farmers got </b></a> <br>
                           <a href='#' style=\"font-size:13px; color:green\"> " . number_format($taken_no_loans) . " farmers didnt get</a>
                           <a href='farmerloans.php'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>
                       </div>
                   </div>
                 </div>

         <!--        <div class=\"col-md-3 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:orange; padding:15px; border-radius:2px;color:#fff; margin-top: -20px; \">
                           <i class=\"fa fa-leaf fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                           <h4>Insured farmers</h4>
                           <a style=\"font-size:15px; color:blue\"><b> Insured: $insured</b></a> <br>
                           <a style=\"font-size:13px; color:green\">Not insured: $not_insured</a>
                       </div>
                   </div>
                 </div> -->
 ";


        break;

}

?>

