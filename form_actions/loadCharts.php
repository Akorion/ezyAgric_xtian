<!--<style>-->
<!--    .graph_box{-->
<!--        padding: 10px; -moz-box-shadow: 0 0 5px #999; -webkit-box-shadow: 0 0 5px #999; box-shadow: 0 0 5px #999;-->
<!--    }-->
<!--</style>-->

<?php
#includes
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/json_models_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";


$mCrudFunctions = new CrudFunctions();
$json_model_obj = new JSONModel();
$util_obj = new Utilties();

switch ($_POST["token"]) {

    //geting loans summary : used in farmerloans.php
    case  "get_expenditure":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $male = 0;
        $female = 0;


        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $male += (int)$mCrudFunctions->get_sum("dataset_" . $row['id'], "maize_production_data_money_used_for_fertilizers", "biodata_farmer_gender='male'");

                $female += (int)$mCrudFunctions->get_sum("dataset_" . $row['id'], "maize_production_data_money_used_for_fertilizers", "biodata_farmer_gender='female'");

            }
        }
        echo "
            <table class='table table-responsive table-bordered' style='height: 85%; font-size: larger;'>
                <thead class='dark' style=\" min-height: 20%; font-weight: bolder; font-size: 1.2em; \">
                <td colspan=\"2\" class='text-center'>Expenditure on Fertlizers</td>
                
                </thead>

                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Male</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($male) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Female</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($female) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Total</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($male + $female) . " UGX</td>
                </tr>
                
            </table>
        ";

        break;

    ///------------------------------ cooperative list ------------------------------------//
    case  "get_cooperative_list":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $cooperatives = 0;
        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("soil_results_" . $row['id'])) {
                $id = $row['id'];
                $cooperatives = $mCrudFunctions->fetch_rows("soil_results_".$id." s INNER JOIN dataset_".$id." d ON s.unique_id = d.unique_id", " DISTINCT(TRIM(s.`cooperative`)) as coops,COUNT(*) as farmers,round(AVG(ph),2) as ph", "1 GROUP BY TRIM(s.cooperative)");
                foreach ($cooperatives as $coop) {
                    $coops = $coop['coops'];
                    $farmers = $coop['farmers'];
                    $lbl_farmers = $farmers > 1 ? $farmers." farmers": $farmers." farmer";
                    echo "                
                            <div class='graph_box'>
                                <h4><strong>$coops</strong></h4>
                                $lbl_farmers &nbsp;&nbsp;&nbsp;&nbsp; Average ph: ".$coop['ph']." &nbsp; 
                                <a href='#' class='btn btn-sm btn-success'><span style='color: white;' onclick='doStuff()'>$coops</span></a>  
                            </div>                                             
                        <br>
                        ";
                }
            }
        }
        break;

    //geting loans summary : used in farmerloans.php
    case  "get_loans_data":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $male = 0;
        $female = 0;


        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $male += (int)$mCrudFunctions->get_sum("dataset_" . $row['id'], "general_questions_loan_amount_accessed", "biodata_farmer_gender='male' AND general_questions_loan_amount_accessed != ''");

                $female += (int)$mCrudFunctions->get_sum("dataset_" . $row['id'], "general_questions_loan_amount_accessed", "biodata_farmer_gender='male' AND general_questions_loan_amount_accessed != ''");

            }
        }
        echo "
            <table class='table table-responsive table-bordered' style='height: 85%; font-size: larger;'>
                <thead class='dark' style=\" min-height: 20%; font-weight: bolder; font-size: 1.2em; \">
                <td colspan=\"2\" class='text-center'>Total Loans that farmers got</td>
                
                </thead>

                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Male</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($male) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Female</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($female) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Grand Total</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($male + $female) . " UGX</td>
                </tr>
                
            </table>
        ";

        break;

    //geting insurance summary : used in farmersInsured.php
    case  "farmers_insured_data":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $male = 0;
        $female = 0;


        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $male += (int)$mCrudFunctions->get_sum("dataset_" . $row['id'], "general_questions_crop_insurance_sources", "biodata_farmer_gender='male' AND general_questions_crop_insurance_sources != ''");

                $female += (int)$mCrudFunctions->get_sum("dataset_" . $row['id'], "general_questions_loan_amount_accessed", "biodata_farmer_gender='male' AND general_questions_loan_amount_accessed != ''");
//                $female += (int)$mCrudFunctions->fetch_rows()
            }
        }
        echo "
            <table class='table table-responsive table-bordered' style='height: 85%; font-size: larger;'>
                <thead class='dark' style=\" min-height: 20%; font-weight: bolder; font-size: 1.2em; \">
                <td colspan=\"2\" class='text-center'>Total Loans that farmers got</td>
                
                </thead>

                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Male</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($male) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Female</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($female) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Grand Total</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($male + $female) . " UGX</td>
                </tr>
                
            </table>
        ";

        break;

    case  "farmers_piechart" :

        if (isset($_POST['gender'])) {
            session_start();
            $client_id = $_SESSION["client_id"];
            $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

            $male = 0;
            $female = 0;

            foreach ($rows as $row) {
                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $male += $mCrudFunctions->get_count("dataset_" . $row['id'], "biodata_farmer_gender='male'");
                    $female += $mCrudFunctions->get_count("dataset_" . $row['id'], "biodata_farmer_gender='female'");
                }
            }
            draw_donut_chart($male, $female);
        }
        break;

    //----------------------------farmers cooperative table --------------------------------
    case  "farmers_cop_table" :
        session_start();
        $client_id = $_SESSION["client_id"];
        $account_name = $_SESSION['account_name'];
        $condition = $_POST["coop"];

        if ($condition != ''){
            $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

            echo "<h4>".$_POST["coop"]." cooperative farmers </h4>";
            echo " <table class='table table-responsive'> <thead class='bg bg-success'><th>name</th> <th>village</th> <th>Action</th></thead>";

            foreach ($rows as $row) {
                $id = $row['id'];
                $dataset_ = $util_obj->encrypt_decrypt("encrypt", $id);
                $dataset_type = $row['dataset_type'];
                if ($mCrudFunctions->check_table_exists("soil_results_" . $id)) {
                    $cooperatives = $mCrudFunctions->fetch_rows("soil_results_".$id." s INNER JOIN dataset_".$id." d ON s.unique_id = d.unique_id", "d.*, s.`cooperative`,s.`village`,s.`ph`,s.`om_%`,s.`n_%`,s.`p_ppm`,s.`k`", "s.cooperative = '".$condition."' ORDER BY d.biodata_farmer_name ASC");
                    foreach ($cooperatives as $coop) {
                        $real_id = $coop['id'];
                        $real_id = $util_obj->encrypt_decrypt("encrypt", $real_id);
                        $name = $util_obj->capitalizeName($coop['biodata_farmer_name']);
                        echo "<tr><td>".$name." </td> <td>".$coop['village']." </td> <td><a href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\"> <button class='btn btn-success'>View Details</button> </a></td></tr>";
                    }
                }
            }
        }else{
            $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

            echo "<h4>".$_POST["coop"]." cooperative farmers </h4>";
            echo " <table class='table table-responsive'> <thead class='bg bg-success'><th>name</th> <th>village</th> <th>Action</th></thead>";

            foreach ($rows as $row) {
                if ($mCrudFunctions->check_table_exists("soil_results_" . $row['id'])) {

                    $cooperatives = $mCrudFunctions->fetch_rows("soil_results_" . $row['id'], "*", "1 ORDER BY farmer_name");
                    foreach ($cooperatives as $coop) {
                        echo "<a href='#'> <tr>  <td>".$coop['farmer_name']." </td> <td>".$coop['village']." </td> <td><button class='btn btn-success'>Details</button> </td></tr></a>";
                    }
                }
            }
        }

        echo " </table> ";
        break;

    case  "ict_usage_piechart" :

        if (isset($_POST['ict_range'])) {
            session_start();
            $client_id = $_SESSION["client_id"];
            $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

            $ict_used = 0;
            $ict_not_used = 0;

            foreach ($rows as $row) {
                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $ict_used += $mCrudFunctions->get_count("dataset_" . $row['id'], "maize_production_data_was_ict_used='yes'");
                    $ict_not_used += $mCrudFunctions->get_count("dataset_" . $row['id'], "maize_production_data_was_ict_used='no'");
                }
            }
            draw_ict_pie_chart($ict_used, $ict_not_used);
        }
        break;

    case  "farmers_districts" :
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $district = array();
        $no_farmers = array();
        $youth = array();
        $old = array();

        foreach ($rows as $row) {

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $slected_rows = $mCrudFunctions->fetch_farmer_rows("dataset_" . $row['id'] . " GROUP BY biodata_farmer_location_farmer_district ORDER BY biodata_farmer_location_farmer_district ASC",
                    "biodata_farmer_location_farmer_district AS district, COUNT(*) AS farmers, 
               SUM(CASE WHEN (2017-CONCAT(19,substring(biodata_farmer_dob,-2,2))) < 35 OR (2017-CONCAT(19,substring(biodata_farmer_dob,-2,2))) = 35 THEN 1 ELSE 0 end) AS youth, 
               SUM(CASE WHEN (2017-CONCAT(19,substring(biodata_farmer_dob,-2,2))) > 35 THEN 1 ELSE 0 end) AS old 
               
                    ");

                foreach ($slected_rows as $sel) {
                    array_push($no_farmers, $sel['farmers']);
                    array_push($district, $sel['district']);
                    array_push($youth, $sel['youth']);
                    array_push($old, $sel['old']);
                }
            }
        }
        draw_graph($district, $no_farmers, $youth, $old, "column");
        break;

    case  "crops_grown" :
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $numbers = array();
        $regions = array();
        $males = array();
        $females = array();
        $dts = array();

        foreach ($rows as $row) {

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $rows_num = $mCrudFunctions->fetch_farmer_rows("dataset_" . $row['id'] . "
                     GROUP BY biodata_farmer_location_farmer_region ORDER BY biodata_farmer_location_farmer_region ASC",
                    "count(*) AS farmers, SUM(CASE WHEN biodata_farmer_gender='male' THEN 1 ELSE 0 end) AS males,
                    SUM(CASE WHEN biodata_farmer_gender='female' THEN 1 ELSE 0 end) AS females,
                     biodata_farmer_location_farmer_region as regions");

                if ($rows_num) {

                    foreach ($rows_num as $row_n) {
                        array_push($numbers, $row_n['farmers']);
                        array_push($regions, $row_n['regions']);
                        array_push($males, $row_n['males']);
                        array_push($females, $row_n['females']);
                    }
                }

            }
        }
        analyseRegions("column", $regions, $numbers, $males, $females, "Farmers and their regions");
        break;

    case "seed_sources" :

        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $farmers_number = array();
        $seed_source = array();
        $youth = array();
        $old = array();


        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $slected_rows = $mCrudFunctions->fetch_farmer_rows("dataset_" . $row['id'] . " GROUP BY maize_production_data_improved_seeds_source ORDER BY farmers DESC ",
                    "maize_production_data_improved_seeds_source as seed_source, COUNT(*) as farmers
                    ");
                foreach ($slected_rows as $sel) {
                    array_push($farmers_number, $sel['farmers']);
                    array_push($seed_source, $sel['seed_source']);
                }
            }
        }
        analyseSeeds($seed_source, $farmers_number, "column", "Sources where farmers got seeds from");
        break;

    case  "average_yield" :

        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $district = array();
        $used_climate_action = array();
        $didnt_use_climate_action = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $slected_rows = $mCrudFunctions->fetch_farmer_rows("dataset_" . $row['id'] . " GROUP BY biodata_farmer_location_farmer_district",
                    "biodata_farmer_location_farmer_district as district, SUM(case WHEN `general_questions_were_climate_change_reduction_actions_used`='No' then 1 else 0 end) no_climate_action,
                SUM(case WHEN `general_questions_were_climate_change_reduction_actions_used`='Yes' then 1 else 0 end) used_climate_action
                    ");

                foreach ($slected_rows as $sel) {
                    array_push($used_climate_action, $sel['used_climate_action']);
                    array_push($district, $sel['district']);
                    array_push($didnt_use_climate_action, $sel['no_climate_action']);
                }
            }
        }
        draw_climate_graph($district, $used_climate_action, $didnt_use_climate_action, "column");
        break;

    case  "ace_average_yield_per_acre":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $average_yield = array();
        $aces = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $rows_num = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                    "DISTINCT(biodata_farmer_organization_name) as ace, 
                            AVG(production_data_average_yield_in_kgs_per_acre) as average_yield",
                    "1 GROUP BY biodata_farmer_organization_name");
                if ($rows_num) {
                    foreach ($rows_num as $row_n) {
                        array_push($average_yield, $row_n['average_yield']);
                        array_push($aces, $row_n['ace']);
                    }
                }
            }
        }
        ace_average_yield_graph("column", $aces, $average_yield);
        break;

    case  "ace_average_cost_of_prodn_per_acre":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $average_cost_of_prodn = array();
        $aces = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $rows_num = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                    "DISTINCT(biodata_farmer_organization_name) as ace, 
                            AVG(production_data_value_of_insured_yield_per_acre) as average_cost_of_prodn",
                    "1 GROUP BY biodata_farmer_organization_name");
                if ($rows_num) {
                    foreach ($rows_num as $row_n) {
                        array_push($average_cost_of_prodn, $row_n['average_cost_of_prodn']);
                        array_push($aces, $row_n['ace']);
                    }
                }
            }
        }
        ace_average_cost_of_prodn_graph("column", $aces, $average_cost_of_prodn);
        break;

    case  "ace_average_yield_insured":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $average_yield_insured = array();
        $aces = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $rows_num = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                    "DISTINCT(biodata_farmer_organization_name) as ace, 
                            AVG(production_data_insured_yield_in_kgs) as average_yield_insured",
                    "1 GROUP BY biodata_farmer_organization_name");
                if ($rows_num) {
                    foreach ($rows_num as $row_n) {
                        array_push($average_yield_insured, $row_n['average_yield_insured']);
                        array_push($aces, $row_n['ace']);
                    }
                }
            }
        }
        ace_average_yield_insured_graph("line", $aces, $average_yield_insured);
        break;

    case  "ace_average_acreage":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $average_acerage = array();
        $aces = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $rows_num = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                    "DISTINCT(biodata_farmer_organization_name) as ace, 
                            ROUND(AVG(production_data_land_size),2) as average_acerage",
                    "1 GROUP BY biodata_farmer_organization_name");
                if ($rows_num) {
                    foreach ($rows_num as $row_n) {
                        array_push($average_acerage, $row_n['average_acerage']);
                        array_push($aces, $row_n['ace']);
                    }
                }
            }
        }
        ace_average_acerage_graph("line", $aces, $average_acerage);
        break;

    case  "farmers_ph_levels":

        session_start();
        $client_id = $_SESSION["client_id"];

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmers_number = array();
        $levels = array("Very low", "Low", "Medium", "High", "Very high");
        $medium; $high; $very_high; $low; $verylow;

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {

                $verylow += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "ph <= 4.5");
                $low+= (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "ph > 4.5 AND ph <= 5.5");
                $medium += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "ph > 5.5 AND ph <= 6.5");
                $high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "ph > 6.5 AND ph <= 7.8");
                $very_high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "ph > 7.8");

            }
        }
        array_push($farmers_number, $verylow);
        array_push($farmers_number, $low);
        array_push($farmers_number, $medium);
        array_push($farmers_number, $high);
        array_push($farmers_number, $very_high);

        analyseSeeds($levels, $farmers_number, "column", "Ph levels of Farmers Gardens");
        break;

    case "macro_nutrients_nitrogen":
        session_start();
        $client_id = $_SESSION["client_id"];

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmers_number = array();
        $levels = array("Very low", "Low", "Medium", "High", "Very high");
        $medium; $high; $very_high; $low; $verylow;

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {

                $verylow += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`n_%` <= 0.05");
                $low+= (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`n_%` > 0.05 AND `n_%` <= 0.15");
                $medium += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`n_%` > 0.15 AND `n_%` <= 0.25");
                $high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`n_%` > 0.25 AND `n_%` <= 0.5");
                $very_high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`n_%` > 0.5");

            }
        }
        array_push($farmers_number, $verylow);
        array_push($farmers_number, $low);
        array_push($farmers_number, $medium);
        array_push($farmers_number, $high);
        array_push($farmers_number, $very_high);

        line_chart_analyze_results($levels, $farmers_number, "line", "Nitrogen Levels of Farmers' Gardens");
        break;

    case  "macro_nutrients_phosphorous":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $low;
        $medium;
        $high;
        $very_high;

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {

                $verylow += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`om_%` <= 2.5");
                $low+= (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`om_%` > 2.5 AND `om_%` <= 0.15");
                $medium += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`om_%` > 0.15 AND `om_%` <= 3.5");
                $high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`om_%` > 3.5 AND `om_%` <= 4.9");
                $very_high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`om_%` > 4.9");

            }
        }
        draw_organic_chart($low, $medium, $high, $very_high);
        break;

    case  "macro_nutrients_potassium":
        session_start();
        $client_id = $_SESSION["client_id"];

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmers_number = array();
        $levels = array("Very low", "Low", "Medium", "High", "Very high");
        $medium; $high; $very_high; $low; $verylow;

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {

                $verylow += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "k <= 0.05");
                $low+= (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "k > 0.05 AND k <= 0.15");
                $medium += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "k > 0.15 AND k <= 0.25");
                $high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "k > 0.25 AND k <= 0.5");
                $very_high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "k > 0.5");

            }
        }
        array_push($farmers_number, $verylow);
        array_push($farmers_number, $low);
        array_push($farmers_number, $medium);
        array_push($farmers_number, $high);
        array_push($farmers_number, $very_high);

        analyseSeeds($levels, $farmers_number, "column", "Potassium levels of Farmers' Gardens");
        break;

    case  "farmers_lime_requirement":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $farmers_number = array();
        $recommendations  = array("Lime", "CAN", "CAN_ASN", "Urea");
        $lime=0;    $can=0;    $can_asn=0; $urea=0;

        foreach ($rows as $row) {
            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {
                $lime += $mCrudFunctions->get_count("soil_results_" . $row['id'], "ph <= 5.2");
                $can += $mCrudFunctions->get_count("soil_results_" . $row['id'], "ph < 5.3");
                $can_asn += $mCrudFunctions->get_count("soil_results_" . $row['id'], "ph >= 5.3 AND ph <= 6.5");
                $urea += $mCrudFunctions->get_count("soil_results_" . $row['id'], "ph > 6.5");
            }
        }
        array_push($farmers_number, $lime);
        array_push($farmers_number, $can);
        array_push($farmers_number, $can_asn);
        array_push($farmers_number, $urea);

//        analyseSeeds($recommendations, $farmers_number, "column", "Farmers With Fertilizer Recommendations");
        fertilizer_chart($lime,$can,$can_asn,$urea);
    break;

    case  "ace_crop_insured":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

//        $farmers_number = array();
//        $regions = array("Eastern", "Northern", "Western");
        $east = 0; $north = 0; $west = 0;

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $east += $mCrudFunctions->get_count("dataset_" . $row['id'], "biodata_farmer_location_farmer_region LIKE 'eastern'");
                $north += $mCrudFunctions->get_count("dataset_" . $row['id'], "biodata_farmer_location_farmer_region LIKE 'northern'");
                $west += $mCrudFunctions->get_count("dataset_" . $row['id'], "biodata_farmer_location_farmer_region LIKE 'western'");
            }
        }

        ace_crop_insured_pie_chart($east, $north, $west);
        break;

    case  "insurance_age_group":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $youth = 0;
        $old = 0;

        foreach ($rows as $row) {

            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $slected_rows = $mCrudFunctions->fetch_farmer_rows("dataset_" . $row['id'],
                    " SUM(CASE WHEN (2017-substring(`biodata_farmer_dob`,-4,4)) < 35 OR (2017-substring(biodata_farmer_dob,-4,4)) = 35 THEN 1 ELSE 0 end) AS youth, 
                           SUM(CASE WHEN (2017-substring(biodata_farmer_dob,-4,4)) > 35 THEN 1 ELSE 0 end) AS old ");

                foreach ($slected_rows as $sel) {
                    $youth += $sel['youth'];
                    $old += $sel['old'];
                }
            }
        }
        insurance_age_group_donut_chart($youth, $old);
        break;

    case  "farmers_organic_matter":
        session_start();
        $client_id = $_SESSION["client_id"];

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmers_number = array();
        $levels = array("Trace", "Very low", "Low", "Medium", "High", "Very high");
        $trace; $medium; $high; $very_high; $low; $verylow;

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {

                $trace += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "p_ppm like 'trace'");
                $verylow += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "p_ppm BETWEEN 0.3 AND 12");
                $low+= (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "p_ppm >12 AND p_ppm<=22.5");
                $medium += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "p_ppm > 22.5 AND p_ppm <= 35.5");
                $high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "p_ppm > 35.5 AND p_ppm <= 68.5");
                $very_high += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "p_ppm > 68.5");

            }
        }
        array_push($farmers_number, $trace);
        array_push($farmers_number, $verylow);
        array_push($farmers_number, $low);
        array_push($farmers_number, $medium);
        array_push($farmers_number, $high);
        array_push($farmers_number, $very_high);

        analyseSeeds($levels, $farmers_number, "column", "Phosphorous levels from farmers' gardens");
        break;

    case  "coops_ph_levels":
        session_start();
        $client_id = $_SESSION["client_id"];

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmers_number = array();
        $levels = array("Very low", "Low", "Medium", "High", "Very high");
        $medium=0; $high=0; $very_high=0; $low=0; $verylow=0;

        $ph = array();  $coops = array();

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {
                $cooperatives = $mCrudFunctions->fetch_rows("soil_results_" . $row['id'], "DISTINCT(trim(`cooperative`)) as coops, round(AVG(`ph`),2)as ph ", "1 GROUP BY TRIM(cooperative) ASC");
                foreach ($cooperatives as $coop_dst){
                    $ph = $coop_dst['ph'];

                    if($ph <= 4.5) { $verylow += 1; }
                    elseif ($ph > 4.5 && $ph <= 5.5) { $low+= 1; }
                    elseif ($ph > 5.5 && $ph <= 6.5) { $medium += 1; }
                    elseif ($ph > 6.5 && $ph <= 7.8) { $high += 1; }
                    else{ $very_high += 1; }
                }
            }
        }
        array_push($farmers_number, $verylow);
        array_push($farmers_number, $low);
        array_push($farmers_number, $medium);
        array_push($farmers_number, $high);
        array_push($farmers_number, $very_high);

        coops_ph_levels($levels, $farmers_number, "bar", "Average Ph levels of Cooperatives");

    break;
}

function debug_to_console($data)
{
    if (is_array($data) || is_object($data)) {
        echo("<script>console.log('PHP: " . json_encode($data) . "');</script>");
    } else {
        echo("<script>console.log('PHP: $data');</script>");
    }
}

//organic matter data
function draw_organic_chart($low, $moderate, $high, $veryhigh)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Distribution of organic matter levels of the farmers');

    $datax = array();

    $temp = array('Low', $low);
    array_push($datax, $temp);

    array_push($datax, array('Moderate', $moderate));
    array_push($datax, array('High', $high));
    array_push($datax, array('Very high', $veryhigh));

    $dataArray = array('type' => 'pie', 'name' => 'OrganicMatter', 'data' => $datax);

    $data = $json_model_obj->get_donutchart_graph_json($titleArray, $dataArray);
    $util_obj->deliver_response(200, 1, $data);
}

//------------------------------------------------------------------------
function draw_donut_chart($male, $female)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Gender Analysis');

    $datax = array();

    $temp = array('Male', $male);
    array_push($datax, $temp);

    array_push($datax, array('Female', $female));

    $dataArray = array('type' => 'pie', 'name' => 'Gender', 'data' => $datax);

    $data = $json_model_obj->get_donutchart_graph_json($titleArray, $dataArray);//
    $util_obj->deliver_response(200, 1, $data);
}

function insurance_age_group_donut_chart($youth, $old)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Insurance Vs Age Group');

    $datax = array();

    array_push($datax, array('Youth', $youth));
    array_push($datax, array('Old', $old));

    $dataArray = array('type' => 'pie', 'name' => 'Age Group', 'data' => $datax);
    $data = $json_model_obj->InsuranceAgeGroup_donut_chart($titleArray, $dataArray);//
    $util_obj->deliver_response(200, 1, $data);
}

function draw_organic_matter_chart($low, $moderate, $high, $very_high)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Organic Matter Levels For Farmers Gardens');
    $datax = array();

    array_push($datax, array('Low', $low));
    array_push($datax, array('Moderate', $moderate));
    array_push($datax, array('High', $high));
    array_push($datax, array('Very High', $very_high));

    $dataArray = array('type' => 'pie', 'name' => 'Organic Matter', 'data' => $datax);

    $data = $json_model_obj->get_donutchart_graph_json($titleArray, $dataArray);//
    $util_obj->deliver_response(200, 1, $data);
}

function draw_graph($district, $no_farmers, $youth, $old, $type)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $no_farmers);
    $old_int = array_map(create_function('$value', 'return (int)$value;'), $old);
    $youth_int = array_map(create_function('$value', 'return (int)$value;'), $youth);

    $data = $json_model_obj->get_column_graph($district, $array_int, $youth_int, $old_int, $type);
    $util_obj->deliver_response(200, 1, $data);
}

function draw_ph_level_graph($district, $acidic, $optimal, $neutral, $alkaline, $type)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $acidic_arr = array_map(create_function('$value', 'return (int)$value;'), $acidic);
    $optimal_arr = array_map(create_function('$value', 'return (int)$value;'), $optimal);
    $neutral_arr = array_map(create_function('$value', 'return (int)$value;'), $neutral);
    $alkaline_arr = array_map(create_function('$value', 'return (int)$value;'), $alkaline);

    $data = $json_model_obj->get_ph_graph($district, $acidic_arr, $optimal_arr, $neutral_arr, $alkaline_arr, $type);
    $util_obj->deliver_response(200, 1, $data);
}

function draw_macro_nutrients_nitrogen_graph($district, $low, $medium, $high, $excessive, $type)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $low_arr = array_map(create_function('$value', 'return (int)$value;'), $low);
    $medium_arr = array_map(create_function('$value', 'return (int)$value;'), $medium);
    $high_arr = array_map(create_function('$value', 'return (int)$value;'), $high);
    $excessive_arr = array_map(create_function('$value', 'return (int)$value;'), $excessive);

    $data = $json_model_obj->get_macro_nutrients_nitrogen_graph($district, $low_arr, $medium_arr, $high_arr, $excessive_arr, $type);
    $util_obj->deliver_response(200, 1, $data);
}

function draw_macro_nutrients_phosphorous_graph($district, $low, $medium, $high, $excessive, $type)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $low_arr = array_map(create_function('$value', 'return (int)$value;'), $low);
    $medium_arr = array_map(create_function('$value', 'return (int)$value;'), $medium);
    $high_arr = array_map(create_function('$value', 'return (int)$value;'), $high);
    $excessive_arr = array_map(create_function('$value', 'return (int)$value;'), $excessive);

    $data = $json_model_obj->get_macro_nutrients_phosphorous_graph($district, $low_arr, $medium_arr, $high_arr, $excessive_arr, $type);
    $util_obj->deliver_response(200, 1, $data);
}

function draw_macro_nutrients_potassium_graph($district, $very_low, $low, $medium, $high, $very_high, $type)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $very_low_arr = array_map(create_function('$value', 'return (int)$value;'), $very_low);
    $low_arr = array_map(create_function('$value', 'return (int)$value;'), $low);
    $medium_arr = array_map(create_function('$value', 'return (int)$value;'), $medium);
    $high_arr = array_map(create_function('$value', 'return (int)$value;'), $high);
    $very_high_arr = array_map(create_function('$value', 'return (int)$value;'), $very_high);

    $data = $json_model_obj->get_macro_nutrients_potassium_graph($district, $very_low_arr, $low_arr, $medium_arr, $high_arr, $very_high_arr, $type);
    $util_obj->deliver_response(200, 1, $data);
}

function draw_climate_graph($district, $used_action, $no_action, $type)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $used_action);
    $no_action_int = array_map(create_function('$value', 'return (int)$value;'), $no_action);

    $data = $json_model_obj->drawActionGraph($district, $array_int, $no_action_int, $type);
    $util_obj->deliver_response(200, 1, $data);
}

function analyseSeeds($source, $farmers, $type, $title)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $farmers);


    $data = $json_model_obj->drawSeedGraph($source, $array_int, $type, $title);
    $util_obj->deliver_response(200, 1, $data);
}

function coops_ph_levels($source, $farmers, $type, $title)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $farmers);


    $data = $json_model_obj->coopsPhGraph($source, $array_int, $type, $title);
    $util_obj->deliver_response(200, 1, $data);
}

function line_chart_analyze_results($source, $farmers, $type, $title)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $farmers);


    $data = $json_model_obj->drawLineGraph($source, $array_int, $type, $title);
    $util_obj->deliver_response(200, 1, $data);
}

function analyseRegions($type, $categories, $farmers, $males, $females, $title)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $array_int = array_map(create_function('$value', 'return (int)$value;'), $farmers);
    $males_int = array_map(create_function('$value', 'return (int)$value;'), $males);
    $females_int = array_map(create_function('$value', 'return (int)$value;'), $females);

    $data = $json_model_obj->getRegions($type, $categories, $array_int, $males_int, $females_int, $title);
    $util_obj->deliver_response(200, 1, $data);
}

function ace_average_yield_graph($type, $aces, $average_yield)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $yield_arr = array_map(create_function('$value', 'return (int)$value;'), $average_yield);

    $data = $json_model_obj->getAceAverageYield($type, $aces, $yield_arr);
    $util_obj->deliver_response(200, 1, $data);
}

function ace_average_cost_of_prodn_graph($type, $aces, $average_cost_of_prodn)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $cost_of_prodn_arr = array_map(create_function('$value', 'return (int)$value;'), $average_cost_of_prodn);

    $data = $json_model_obj->getAceAverageCostOfProdn($type, $aces, $cost_of_prodn_arr);
    $util_obj->deliver_response(200, 1, $data);
}

function ace_average_yield_insured_graph($type, $aces, $average_yield_insured)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $yield_insured_arr = array_map(create_function('$value', 'return (int)$value;'), $average_yield_insured);

    $data = $json_model_obj->getAceAverageYieldInsured($type, $aces, $yield_insured_arr);
    $util_obj->deliver_response(200, 1, $data);
}

function ace_average_acerage_graph($type, $aces, $average_acerage)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $acerage_arr = array_map(create_function('$value', 'return (int)$value;'), $average_acerage);

    $data = $json_model_obj->getAceAverageAcerage($type, $aces, $acerage_arr);
    $util_obj->deliver_response(200, 1, $data);
}

function cropsGrown($type)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();
    $data = $json_model_obj->getCropsGrown($type);
    $util_obj->deliver_response(200, 1, $data);
}

function connection()
{
    $mycon = new mysqli("host", "user", "password", "database");
    return $mycon;
}

function draw_ict_pie_chart($used_ict, $didnt_use_ict)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Ict usage Analysis');

    $datax = array();

    $temp = array('Used ict', $used_ict);
    array_push($datax, $temp);

    $femdata = array('Didn\'t use ict', $didnt_use_ict);
    array_push($datax, $femdata);

    $dataArray = array('type' => 'pie', 'name' => 'Ict', 'data' => $datax);

    $data = $json_model_obj->get_piechart_graph_json($titleArray, $dataArray);
    $util_obj->deliver_response(200, 1, $data);
}

function draw_soil_texture_pie_chart($sand, $clay)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Proportions of Lime Requirements of Farmers');

    $datax = array();

    array_push($datax, array('<b>Require Lime</b>', $sand));
    array_push($datax, array('<b>Don\'t Require Lime</b>', $clay));

    $dataArray = array('type' => 'pie', 'name' => 'Portion of Farmers', 'data' => $datax);

    $data = $json_model_obj->get_piechart_graph_json($titleArray, $dataArray);
    $util_obj->deliver_response(200, 1, $data);
}

function ace_crop_insured_pie_chart($east, $north, $west)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Regional Distribution of Farmers Insured');

    $datax = array();

    array_push($datax, array('Eastern', $east));
    array_push($datax, array('Northern', $north));
    array_push($datax, array('Western', $west));

    $dataArray = array('type' => 'pie', 'name' => 'Region', 'data' => $datax);

    $data = $json_model_obj->get_piechart_graph_json($titleArray, $dataArray);
    $util_obj->deliver_response(200, 1, $data);
}

function fertilizer_chart($lime,$can,$can_asn,$urea)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Farmers With Fertilizer Recommendations');

    $datax = array();

    array_push($datax, array('Lime', $lime));
    array_push($datax, array('Calcium Ammonium Nitrate', $can));
    array_push($datax, array('Calcium Ammonium Nitrate & Ammonium Sulphate Nitrate', $can_asn));
    array_push($datax, array('Urea', $urea));

    $dataArray = array('type' => 'pie', 'name' => 'Farmers', 'data' => $datax);

    $data = $json_model_obj->get_piechart_graph_json($titleArray, $dataArray);
    $util_obj->deliver_response(200, 1, $data);
}
?>

<!--<script type="text/javascript">-->
<!--    $(document).ready(function () {-->
<!--        $('#dt_example').DataTable();-->
<!--    });-->
<!--</script>-->
