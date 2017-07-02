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
        analyseRegions("column", $regions, $numbers, $males, $females);
        break;

    case "youth_farmers" :

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
        analyseSeeds($seed_source, $farmers_number, "bar");
        break;

    case  "average_yield" :

        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $district = array();
        $no_farmers = array();
        $youth = array();



        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                $slected_rows = $mCrudFunctions->fetch_farmer_rows("dataset_" . $row['id'] . " GROUP BY biodata_farmer_location_farmer_district",
                    "biodata_farmer_location_farmer_district as district, SUM(case WHEN `general_questions_were_climate_change_reduction_actions_used`='No' then 1 else 0 end) no_climate_action,
                SUM(case WHEN `general_questions_were_climate_change_reduction_actions_used`='Yes' then 1 else 0 end) used_climate_action
                    ");

                foreach ($slected_rows as $sel) {
                    array_push($no_farmers, $sel['used_climate_action']);
                    array_push($district, $sel['district']);
                    array_push($youth, $sel['no_climate_action']);
                }
            }
        }
        draw_climate_graph($district, $no_farmers, $youth, "line");
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

function analyseSeeds($source, $farmers, $type)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $farmers);


    $data = $json_model_obj->drawSeedGraph($source, $array_int, $type);
    $util_obj->deliver_response(200, 1, $data);
}

function analyseRegions($type, $categories, $farmers, $males, $females)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $array_int = array_map(create_function('$value', 'return (int)$value;'), $farmers);
    $males_int = array_map(create_function('$value', 'return (int)$value;'), $males);
    $females_int = array_map(create_function('$value', 'return (int)$value;'), $females);

    $data = $json_model_obj->getRegions($type, $categories, $array_int, $males_int, $females_int);
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

function draw_ict_pie_chart($male, $female)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    $titleArray = array('text' => 'Ict usage Analysis');

    $datax = array();

    $temp = array('Used ict', $male);
    array_push($datax, $temp);

    $femdata = array('Didn\'t use ict', $female);
    array_push($datax, $femdata);

    $dataArray = array('type' => 'pie', 'name' => 'Ict', 'data' => $datax);

    $data = $json_model_obj->get_piechart_graph_json($titleArray, $dataArray);
    $util_obj->deliver_response(200, 1, $data);

}

?>