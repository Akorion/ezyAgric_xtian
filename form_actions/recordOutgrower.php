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
            $acerage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "maize_production_data_total_land_under_production", 1);
            if($_SESSION['client_id'] == 16 || $_SESSION['client_id'] == 17) $acerage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "production_data_land_size", 1);
            elseif($_SESSION['client_id'] == 5) { $acerage += $mCrudFunctions->get_sum("dataset_" . $row['id'], "coffee_production_data_number_of_acres_of_coffee", 1); }
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
                if($_SESSION['client_id'] == 17){ $insured += $mCrudFunctions->get_count("dataset_".$row['id'], "1"); }
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
                         <a style=\"font-size:15px; color:blue\"><b>$farmers</b></a> <br>
                           <span style=\"font-size:13px; color:green\">$acerage Acres</span> 
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
                           <a style=\"font-size:13px; color:green\">UGX " . number_format($cash_returned) . " returned</a>
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
}

/*
if(isset($_POST["dataset_id"]) &&
 $_POST["dataset_id"]!=''
)
{


   $dataset_id=$_POST["dataset_id"];
   $array=array("test1","test2","test3");
   //print_r($array);
   $x=$mCrudFunctions->create_table_tb("seedss",$dataset_id,array("test1","test2","test3"));

   echo "HERE".$dataset_id." ".$x;

}else{

   //redirect back to original page
   echo "HERE";
   //$util_obj->redirect_to( "../admin/home.php?action=account&success=0&input=incomplete" );

}
*/
?>