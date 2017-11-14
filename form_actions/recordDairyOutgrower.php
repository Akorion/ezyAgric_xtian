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
        $role =  $_SESSION['role'];
        $branch = $_SESSION['user_account'];

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
        $total_cows = 0;
        $calves = 0;
        $bulls = 0;
        foreach ($rows as $row) {
            if ($role == 1) {
                $farmers += $mCrudFunctions->get_count("dataset_" . $row['id'], 1);
//            $cows += $mCrudFunctions->get_sum("dataset_" . $row['id'], "number_of_cows", 1);
                $data_table = "dataset_" . $row['id'];
                $cows = (int)$mCrudFunctions->get_sum("$data_table", "number_of_cows", "1");
                $calves = (int)$mCrudFunctions->get_sum("$data_table", "number_of_calves", "1");
                $bulls = (int)$mCrudFunctions->get_sum("$data_table", "number_of_bulls", "1");
                $total_cows = $cows + $calves + $bulls;

                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                echo "the table exists";
                    $taken_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_loan='yes'");

                }
                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $taken_no_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_loan='no'");

                    if ($taken_no_loans > 1) $taken_no_loans = $taken_no_loans . " farmers";
                    elseif ($taken_no_loans == 1) $taken_no_loans = $taken_no_loans . " farmer";
                    else $taken_no_loans = "No farmer";
                }

                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_crop_insurance='yes'");
                }
                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $not_insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_crop_insurance='no'");
                }

            }
            else {
                $key = $util_obj->encrypt_decrypt("encrypt", $row['id']);
                $type = $util_obj->encrypt_decrypt("encrypt", 'Farmer');

                $farmers += $mCrudFunctions->get_count("dataset_" . $row['id'], "sacco_branch_name LIKE '$branch'");

                $data_table = "dataset_" . $row['id'];

                $cows += (int)$mCrudFunctions->get_sum("$data_table", "number_of_cows", "sacco_branch_name LIKE '$branch'");
                $calves += (int)$mCrudFunctions->get_sum("$data_table", "number_of_calves", "sacco_branch_name LIKE '$branch'");
                $bulls += (int)$mCrudFunctions->get_sum("$data_table", "number_of_bulls", "sacco_branch_name LIKE '$branch'");
                $total_cows = $cows + $calves + $bulls;

                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
//                echo "the table exists";
                    $taken_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_loan='yes' AND sacco_branch_name LIKE '$branch'");

                }
                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $taken_no_loans += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_loan='no' AND sacco_branch_name LIKE '$branch'");

                    if ($taken_no_loans > 1) $taken_no_loans = $taken_no_loans . " farmers";
                    elseif ($taken_no_loans == 1) $taken_no_loans = $taken_no_loans . " farmer";
                    else $taken_no_loans = "No farmer";
                }

                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_crop_insurance='yes' AND sacco_branch_name LIKE '$branch'");
                }
                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $not_insured += $mCrudFunctions->get_count("dataset_" . $row['id'], "accessed_crop_insurance='no' AND sacco_branch_name LIKE '$branch'");
                }
            }
        }

    if($role == 1){
        echo "<div class=\"col-md-3 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
               <div class=\"x_panel tile  overflow_hidden\">
                   <div class=\"col-md-3\" style=\"background-color:#3a3; padding:10px; border-radius:2px;color:#fff; margin-top: -20px;\">
                       <span class=\"fa fa-users fa-3x\"></span>
                   </div>
                   <div class=\"col-md-9\">
                       <h4> <a href='dash.php'> Dairy Farmers </a> </h4>
                     <a style=\"font-size:15px; color:blue\"><b>$farmers</b> Farmers</a> <br>
                       <span style=\"font-size:13px; color:green\">$total_cows Cattle</span> 
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
    }
    else {
        echo "<div class=\"col-md-3 col-sm-4 col-xs-12\" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
                   <div class=\"x_panel tile  overflow_hidden\">
                       <div class=\"col-md-3\" style=\"background-color:#3a3; padding:10px; border-radius:2px;color:#fff; margin-top: -20px;\">
                           <span class=\"fa fa-users fa-3x\"></span>
                       </div>
                       <div class=\"col-md-9\">
                           <h4> <a href='loadFarmDash.php?token=$key&category=$type'> Dairy Farmers </a> </h4>
                         <a style=\"font-size:15px; color:blue\"><b>$farmers</b> Farmers</a> <br>
                           <span style=\"font-size:13px; color:green\">$total_cows Cattle</span> 
                            <a href='loadFarmDash.php?token=$key&category=$type'><span class='pull-right glyphicon glyphicon-circle-arrow-right' style='color: green; font-size: 20px;'> </span></a>

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
                 </div>                ";
    }


        break;
}

?>