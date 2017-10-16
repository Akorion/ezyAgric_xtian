<?php
session_start();
#includes
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";

$util_obj = new Utilties();
$mCrudFunctions = new CrudFunctions();

switch ($_POST["token"]) {


    case "outgrower_dash":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $farmers = 0;
        $cows = 0;
        $milkreceived = 0;
        $milkpayment = 0;
        $taken_loans = 0;
        $taken_no_loans = 0;
        $seed_taken = 0;
        $yield = 0;
        $insured = 0;
        $not_insured = 0;

        foreach ($rows as $row) {

            $farmers += $mCrudFunctions->get_count("dataset_" . $row['id'], 1);
            $cows += $mCrudFunctions->get_sum("dataset_" . $row['id'], "number_of_cows", 1);
//            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                $milkreceived += $mCrudFunctions->get_sum("dataset_" . $row['id'], "maize_production_data_money_used_for_fertilizers", 1);
//            }
//            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                $milkpayment += $mCrudFunctions->get_sum("dataset_" . $row['id'], "maize_production_data_money_used_for_fertilizers", 1);
//            }

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                echo "the table exists";
                $taken_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_loan='yes'");

            }
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $taken_no_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_loan='no'");

                if($taken_no_loans > 1) $taken_no_loans = $taken_no_loans." farmers";
                elseif ($taken_no_loans == 1) $taken_no_loans = $taken_no_loans. " farmer";
                else $taken_no_loans = "No farmer";
            }

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_crop_insurance='yes'", 1);
            }
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $not_insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_crop_insurance='no'", 1);
            }
        }

        echo "<div class=\"col-md-3 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#3a3; padding:10px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <span class=\"fa fa-users fa-3x\"></span>
                       </div>
                       <div class=\"col-md-9\">
                           <h4> <a href='dash.php'> Dairy Farmers </a> </h4>
                         <a style=\"font-size:15px; color:blue\"><b>$farmers</b> Farmers</a> <br>
                           <span style=\"font-size:13px; color:green\">$cows Cows</span> 
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
                          <h4>Milk supplied today</h4>
                           <a style=\"font-size:15px; color:blue\"><b> " . number_format($milkreceived) . " Litres</b></a> <br>
                           <a style=\"font-size:13px; color:green\">UGX " . number_format($milkpayment) . " paid</a>

                       </div>
                   </div>
                 </div>

                 <div class=\"col-md-3 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color: #00a1b5; padding:15px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <i class=\"fa fa-sitemap fa-3x\"></i>
                       </div>
                       <div class=\"col-md-9\">
                           <h4>Loans accessed</h4>
                           <a style=\"font-size:15px; color:blue\"><b> " . number_format($taken_loans) . " farmers got </b></a> <br>
                           <a style=\"font-size:13px; color:green\"> ". $taken_no_loans ." didn't get</a>
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

?>