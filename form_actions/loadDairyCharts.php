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
    case  "get_expenditure":
        session_start();
        $client_id = $_SESSION["client_id"];
        $role =  $_SESSION['role'];
        $branch = $_SESSION['user_account'];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $labor_expenditure = 0; $injection_expenditure = 0; $acariceides_expenditure = 0;
        $tools_expenditure = 0; $deworming_expenditure = 0; $clearing_expenditure = 0;

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $data_table = "dataset_" . $row['id'];
                if($role == 1){
                    $labor_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_labor", 1);
                    $injection_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_injections", 1);
                    $acariceides_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_acaricides", 1);
                    $deworming_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_deworning", 1);
                    $clearing_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_land_clearing", 1);
                    $tools_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_farm_tools", 1);
                }
                if($role == 2){
                    $labor_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_labor", "sacco_branch_name LIKE '$branch'");
                    $injection_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_injections", "sacco_branch_name LIKE '$branch'");
                    $acariceides_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_acaricides", "sacco_branch_name LIKE '$branch'");
                    $deworming_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_deworning", "sacco_branch_name LIKE '$branch'");
                    $clearing_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_land_clearing", "sacco_branch_name LIKE '$branch'");
                    $tools_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_farm_tools", "sacco_branch_name LIKE '$branch'");
                }
                else {
                    $labor_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_labor", "biodata_cooperative_name LIKE '$branch'");
                    $injection_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_injections", "biodata_cooperative_name LIKE '$branch'");
                    $acariceides_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_acaricides", "biodata_cooperative_name LIKE '$branch'");
                    $deworming_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_deworning", "biodata_cooperative_name LIKE '$branch'");
                    $clearing_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_land_clearing", "biodata_cooperative_name LIKE '$branch'");
                    $tools_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_farm_tools", "biodata_cooperative_name LIKE '$branch'");
                }
            }
        }
        echo "
            <table class='table table-responsive table-bordered' style='height: 85%; font-size: larger;'>
                <thead class='dark' style=\" min-height: 20%; font-weight: bolder; font-size: 1.2em; \">
                <td colspan=\"2\" class='text-center'>Expenditure Break Down </td>
                
                </thead>

                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Labour Expenses</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($labor_expenditure) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Injections Expenses</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($injection_expenditure) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Acaricides Expenses</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($acariceides_expenditure) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Deworming Expenses</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($deworming_expenditure) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Clearing Expenses</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($clearing_expenditure) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:dodgerblue\">Tools Expenses</td>
                    <td style=\" font-weight: bolder; color:dodgerblue\"> " . number_format($tools_expenditure) . " UGX</td>
                </tr>
                <tr>
                    <td style=\" font-weight: bolder; color:#000\">Total Expenditure</td>
                    <td style=\" font-weight: bolder; color:#000\"> " . number_format($tools_expenditure + $clearing_expenditure + $deworming_expenditure + $acariceides_expenditure + $injection_expenditure + $labor_expenditure) . " UGX</td>
                </tr>  
                
            </table>
        ";

        break;

    case  "farmers_piechart" :

        if (isset($_POST['gender'])) {
            session_start();
            $client_id = $_SESSION["client_id"];
            $role =  $_SESSION['role'];
            $branch = $_SESSION['user_account'];
            $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

            $male = 0;  $female = 0;

            foreach ($rows as $row) {
                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    if($role == 1){
                        $male += $mCrudFunctions->get_count("dataset_" . $row['id'], "lower(biodata_gender) LIKE 'male'");
                        $female += $mCrudFunctions->get_count("dataset_" . $row['id'], "lower(biodata_gender) LIKE 'female'");
                    } elseif($role == 2){
                        $male += $mCrudFunctions->get_count("dataset_" . $row['id'], "lower(biodata_gender) LIKE 'male' AND sacco_branch_name LIKE '$branch' ");
                        $female += $mCrudFunctions->get_count("dataset_" . $row['id'], "lower(biodata_gender) LIKE 'female' AND sacco_branch_name LIKE '$branch' ");
                    }else {
                        $male += $mCrudFunctions->get_count("dataset_" . $row['id'], "lower(biodata_gender) LIKE 'male' AND biodata_cooperative_name LIKE '$branch' ");
                        $female += $mCrudFunctions->get_count("dataset_" . $row['id'], "lower(biodata_gender) LIKE 'female' AND biodata_cooperative_name LIKE '$branch' ");
                    }
                }
            }
            draw_donut_chart($male, $female);
        }
        break;

    case  "milk_collection" :
        session_start();
        $role =  $_SESSION['role'];
        $branch = $_SESSION['user_account'];
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $area = array(); $milk_supply = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                if($role == 1){
                    $milk_quantity = $mCrudFunctions->fetch_rows("dataset_".$row['id'], "DISTINCT(sacco_branch_name)as sacco, SUM(milk_production_data_milk_for_dairy) AS MILK_SUPPLIED", "1 GROUP BY sacco_branch_name ORDER BY sacco_branch_name ASC" );
                    foreach ($milk_quantity as $milk){
                        array_push($area, $milk['sacco']);
                        array_push($milk_supply, $milk['MILK_SUPPLIED']);
                    }
                }elseif($role == 2){
                    $milk_quantity = $mCrudFunctions->fetch_rows("dataset_".$row['id'], "DISTINCT(biodata_cooperative_name)as cooperative, SUM(milk_production_data_milk_for_dairy) AS MILK_SUPPLIED", "sacco_branch_name LIKE '$branch' GROUP BY cooperative ORDER BY cooperative ASC" );
                    foreach ($milk_quantity as $milk){
                        array_push($area, $milk['cooperative']);
                        array_push($milk_supply, $milk['MILK_SUPPLIED']);
                    }
                }else{
                    $milk_quantity = $mCrudFunctions->fetch_rows("dataset_".$row['id'], "DISTINCT(biodata_farmer_location_farmer_village)as village, SUM(milk_production_data_milk_for_dairy) AS MILK_SUPPLIED", "biodata_cooperative_name LIKE '$branch' GROUP BY village ORDER BY village ASC" );
                    foreach ($milk_quantity as $milk){
                        array_push($area, $milk['village']);
                        array_push($milk_supply, $milk['MILK_SUPPLIED']);
                    }
                }

            }
        }
        if($role == 1) milk_per_branch($area, $milk_supply, "column", "Quantity Of Milk Supplied Per Branch");
        elseif ($role == 2) milk_per_cooperative($area, $milk_supply, "column", "Quantity Of Milk Supplied Per Cooperative");
        else milk_per_village($area, $milk_supply, "column", "Quantity Of Milk Supplied Per Village");

        break;

    case  "farmers_districts" :
        session_start();
        $client_id = $_SESSION["client_id"];
        $role =  $_SESSION['role'];
        $branch = $_SESSION['user_account'];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $district = array();    $no_farmers = array();  $youth = array(); $old = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                if($role == 1){
                    $slected_rows = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                        "biodata_farmer_location_farmer_subcounty AS district, COUNT(*) AS farmers, 
                       SUM(CASE WHEN (2017-CONCAT(19,substring(biodata_age,-2,2))) < 35 OR (2017-CONCAT(19,substring(biodata_age,-2,2))) = 35 THEN 1 ELSE 0 end) AS youth, 
                       SUM(CASE WHEN (2017-CONCAT(19,substring(biodata_age,-2,2))) > 35 THEN 1 ELSE 0 end) AS biodata_age ", 1);

                    foreach ($slected_rows as $sel) {
                        array_push($no_farmers, $sel['farmers']);
                        array_push($district, $sel['district']);
                        array_push($youth, $sel['youth']);
                        array_push($old, $sel['old']);
                    }
                }elseif($role == 2){
                    $slected_rows = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                        "biodata_farmer_location_farmer_subcounty AS district, COUNT(*) AS farmers, 
                       SUM(CASE WHEN (2017-CONCAT(19,substring(biodata_age,-2,2))) < 35 OR (2017-CONCAT(19,substring(biodata_age,-2,2))) = 35 THEN 1 ELSE 0 end) AS youth, 
                       SUM(CASE WHEN (2017-CONCAT(19,substring(biodata_age,-2,2))) > 35 THEN 1 ELSE 0 end) AS biodata_age ", "sacco_branch_name LIKE '$branch'");

                    foreach ($slected_rows as $sel) {
                        array_push($no_farmers, $sel['farmers']);
                        array_push($district, $sel['district']);
                        array_push($youth, $sel['youth']);
                        array_push($old, $sel['old']);
                    }
                } else{
                    $slected_rows = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                        "biodata_farmer_location_farmer_subcounty AS district, COUNT(*) AS farmers, 
                       SUM(CASE WHEN (2017-CONCAT(19,substring(biodata_age,-2,2))) < 35 OR (2017-CONCAT(19,substring(biodata_age,-2,2))) = 35 THEN 1 ELSE 0 end) AS youth, 
                       SUM(CASE WHEN (2017-CONCAT(19,substring(biodata_age,-2,2))) > 35 THEN 1 ELSE 0 end) AS old ", "biodata_cooperative_name LIKE '$branch' GROUP by district ORDER by district ASC ");

                    foreach ($slected_rows as $sel) {
                        array_push($no_farmers, $sel['farmers']);
                        array_push($district, $sel['district']);
                        array_push($youth, $sel['youth']);
                        array_push($old, $sel['old']);
                    }
                }
            }
        }
        draw_graph($district, $no_farmers, $youth, $old, "column");
        break;

    case  "crops_grown" :
        session_start();
        $client_id = $_SESSION["client_id"];
        $role =  $_SESSION['role'];
        $branch = $_SESSION['user_account'];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $amount = array(); $labour = array();   $injection = array(); $land = array(); $animal_feeds = array();
        $acaricides = array();   $deworming = array();  $clearing = array();    $tools = array(); $fencing = array();

        $expenses = array('Acaricides','Labour','Injections','Tools','Deworming','Land','Feeds','Fencing');
        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                if($role == 1){
                    $rows_num = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                        "SUM(expenditure_on_acaricides)AS acaricides, SUM(expenditure_on_labor) AS labour, SUM(expenditure_on_injections) AS injections, SUM(expenditure_on_farm_tools) AS tools,
                                   SUM(expenditure_on_deworning) AS deworming, SUM(expenditure_on_land_clearing) AS land,SUM(expenditure_on_animal_feeeds) AS animal_feeds,SUM(expenditure_on_fencing) AS fencing", 1);

                    foreach ($rows_num as $row_n) {
                        array_push($acaricides, $row_n['acaricides']);
                        array_push($labour, $row_n['labour']);
                        array_push($injection, $row_n['injections']);
                        array_push($tools, $row_n['tools']);
                        array_push($deworming, $row_n['deworming']);
                        array_push($land, $row_n['land']);
                        array_push($animal_feeds, $row_n['animal_feeds']);
                        array_push($fencing, $row_n['fencing']);
                    }

                    array_push($expenses, $acaricides);
                    array_push($expenses, $labour);
                    array_push($expenses, $injection);
                    array_push($expenses, $tools);
                    array_push($expenses, $deworming);
                    array_push($expenses, $land);
                    array_push($expenses, $animal_feeds);
                    array_push($expenses, $fencing);
                } elseif ($role == 2){
                    $rows_num = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                        "SUM(expenditure_on_acaricides)AS acaricides, SUM(expenditure_on_labor) AS labour, SUM(expenditure_on_injections) AS injections, SUM(expenditure_on_farm_tools) AS tools,
                                   SUM(expenditure_on_deworning) AS deworming, SUM(expenditure_on_land_clearing) AS land,SUM(expenditure_on_animal_feeeds) AS animal_feeds,SUM(expenditure_on_fencing) AS fencing", "sacco_branch_name LIKE '$branch'");

                    foreach ($rows_num as $row_n) {
                        array_push($acaricides, $row_n['acaricides']);
                        array_push($labour, $row_n['labour']);
                        array_push($injection, $row_n['injections']);
                        array_push($tools, $row_n['tools']);
                        array_push($deworming, $row_n['deworming']);
                        array_push($land, $row_n['land']);
                        array_push($animal_feeds, $row_n['animal_feeds']);
                        array_push($fencing, $row_n['fencing']);
                    }

                    array_push($expenses, $acaricides);
                    array_push($expenses, $labour);
                    array_push($expenses, $injection);
                    array_push($expenses, $tools);
                    array_push($expenses, $deworming);
                    array_push($expenses, $land);
                    array_push($expenses, $animal_feeds);
                    array_push($expenses, $fencing);
                } else {
                    $rows_num = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],
                        "SUM(expenditure_on_acaricides)AS acaricides, SUM(expenditure_on_labor) AS labour, SUM(expenditure_on_injections) AS injections, SUM(expenditure_on_farm_tools) AS tools,
                                   SUM(expenditure_on_deworning) AS deworming, SUM(expenditure_on_land_clearing) AS land,SUM(expenditure_on_animal_feeeds) AS animal_feeds,SUM(expenditure_on_fencing) AS fencing", "biodata_cooperative_name LIKE '$branch''");

                    foreach ($rows_num as $row_n) {
                        array_push($acaricides, $row_n['acaricides']);
                        array_push($labour, $row_n['labour']);
                        array_push($injection, $row_n['injections']);
                        array_push($tools, $row_n['tools']);
                        array_push($deworming, $row_n['deworming']);
                        array_push($land, $row_n['land']);
                        array_push($animal_feeds, $row_n['animal_feeds']);
                        array_push($fencing, $row_n['fencing']);
                    }

                    array_push($expenses, $acaricides);
                    array_push($expenses, $labour);
                    array_push($expenses, $injection);
                    array_push($expenses, $tools);
                    array_push($expenses, $deworming);
                    array_push($expenses, $land);
                    array_push($expenses, $animal_feeds);
                    array_push($expenses, $fencing);
                }
            }
        }
        analyseRegions("line", $expenses, "Farmers and their locations");
        break;

    case "youth_farmers" :

        session_start();
        $client_id = $_SESSION["client_id"];
        $role =  $_SESSION['role'];
        $branch = $_SESSION['user_account'];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $farmers_number = array();
        $tp = array();
        $youth = array();
        $old = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                if($role == 1){
                    $slected_rows = $mCrudFunctions->fetch_rows("dataset_" . $row['id'] . " GROUP BY mode_of_transport ORDER BY farmers DESC ",
                        "DISTINCT(mode_of_transport) as transport, COUNT(*) as farmers", 1);

                    foreach ($slected_rows as $sel) {
                        array_push($farmers_number, $sel['farmers']);
                        array_push($tp, $sel['transport']);
                    }
                } elseif ($role == 2){
                    $slected_rows = $mCrudFunctions->fetch_rows("dataset_" . $row['id'] . " GROUP BY mode_of_transport ORDER BY farmers DESC ",
                        "DISTINCT(mode_of_transport) as transport, COUNT(*) as farmers", "sacco_branch_name LIKE '$branch'");

                    foreach ($slected_rows as $sel) {
                        array_push($farmers_number, $sel['farmers']);
                        array_push($tp, $sel['transport']);
                    }
                } else {
                    $slected_rows = $mCrudFunctions->fetch_rows("dataset_" . $row['id'],"DISTINCT(mode_of_transport) as transport, 
                           COUNT(*) as farmers", "biodata_cooperative_name LIKE '$branch' GROUP BY mode_of_transport ORDER BY farmers DESC ");

                    foreach ($slected_rows as $sel) {
                        array_push($farmers_number, $sel['farmers']);
                        array_push($tp, $sel['transport']);
                    }
                }
            }
        }
        analyseSeeds($tp, $farmers_number, "column", "Milk transportation modes used");
        break;

    case  "average_yield" :

        session_start();
        $client_id = $_SESSION["client_id"];
        $role =  $_SESSION['role'];
        $branch = $_SESSION['user_account'];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $breeds = array();
        $no_farmers = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                if($role == 1){
                    $slected_rows = $mCrudFunctions->fetch_rows("dataset_" . $row['id'] . "  ",
                        "DISTINCT(cattle_breed) as breeds, COUNT(*) as farmers","1 GROUP BY breeds");

                    foreach ($slected_rows as $sel) {
                        array_push($no_farmers, $sel['farmers']);
                        array_push($breeds, $sel['breeds']);
                    }
                } elseif ($role == 2){
                    $slected_rows = $mCrudFunctions->fetch_rows("dataset_" . $row['id'] . "  ",
                        "DISTINCT(cattle_breed) as breeds, COUNT(*) as farmers","sacco_branch_name LIKE '$branch' GROUP BY breeds");

                    foreach ($slected_rows as $sel) {
                        array_push($no_farmers, $sel['farmers']);
                        array_push($breeds, $sel['breeds']);
                    }
                } else{
                    $slected_rows = $mCrudFunctions->fetch_rows("dataset_" . $row['id'] . "  ",
                        "DISTINCT(cattle_breed) as breeds, COUNT(*) as farmers",
                        "biodata_cooperative_name LIKE '$branch' GROUP BY breeds");

                    foreach ($slected_rows as $sel) {
                        array_push($no_farmers, $sel['farmers']);
                        array_push($breeds, $sel['breeds']);
                    }
                }
            }
        }
        analyseSeeds($breeds, $no_farmers, "column", "Farmers with different breeds");
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
function draw_climate_graph($district, $used_action, $no_action, $type){
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

function milk_per_village($source, $milk_supply, $type, $title)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $milk_supply);


    $data = $json_model_obj->drawMilkPerVillageGraph($source, $array_int, $type, $title);
    $util_obj->deliver_response(200, 1, $data);
}

function milk_per_branch($source, $milk_supply, $type, $title)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $milk_supply);


    $data = $json_model_obj->drawMilkPerBranchGraph($source, $array_int, $type, $title);
    $util_obj->deliver_response(200, 1, $data);
}

function milk_per_cooperative($source, $milk_supply, $type, $title)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $milk_supply);


    $data = $json_model_obj->drawMilkPerCooperativeGraph($source, $array_int, $type, $title);
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

function draw_milkhours_pie_chart($morning, $evening)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Milk collection hours');

    $datax = array();

    $temp = array('Morning', $morning);
    array_push($datax, $temp);

    $femdata = array('Evening', $evening);
    array_push($datax, $femdata);

    $dataArray = array('type' => 'pie', 'name' => 'Ict', 'data' => $datax);

    $data = $json_model_obj->get_piechart_graph_json($titleArray, $dataArray);
    $util_obj->deliver_response(200, 1, $data);

}

?>