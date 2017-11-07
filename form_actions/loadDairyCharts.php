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
                    $male += $mCrudFunctions->get_count("dataset_" . $row['id'], "gender='male'");
                    $female += $mCrudFunctions->get_count("dataset_" . $row['id'], "gender='female'");
                }
            }
            draw_donut_chart($male, $female);
        }
        break;

    case  "milk_collection" :

        if (isset($_POST['ict_range'])) {
            session_start();
            $client_id = $_SESSION["client_id"];
            $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

            $morning = 0;
            $evening = 0;

            foreach ($rows as $row) {
                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $morning += $mCrudFunctions->get_sum("dataset_" . $row['id'], "milk_litres_morning", 1);
                    $evening += $mCrudFunctions->get_sum("dataset_" . $row['id'], "milk_litres_evening", 1);
                }
            }
            draw_milkhours_pie_chart($morning, $evening);
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
                $slected_rows = $mCrudFunctions->fetch_farmer_rows("dataset_" . $row['id'] . " GROUP BY district ORDER BY city_town ASC",
                    "district AS district, COUNT(*) AS farmers, 
               SUM(CASE WHEN (2017-CONCAT(19,substring(age,-2,2))) < 35 OR (2017-CONCAT(19,substring(age,-2,2))) = 35 THEN 1 ELSE 0 end) AS youth, 
               SUM(CASE WHEN (2017-CONCAT(19,substring(age,-2,2))) > 35 THEN 1 ELSE 0 end) AS old 
               
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
                     GROUP BY city_town",
                    "COUNT(*) AS farmers, SUM(CASE WHEN gender='male' THEN 1 ELSE 0 end) AS males, SUM(CASE WHEN gender='female' THEN 1 ELSE 0 end) AS females, city_town as region");

                if ($rows_num) {

                    foreach ($rows_num as $row_n) {
                        array_push($numbers, $row_n['farmers']);
                        array_push($regions, $row_n['region']);
                        array_push($males, $row_n['males']);
                        array_push($females, $row_n['females']);
                    }
                }

            }
        }
        analyseRegions("line", $regions, $numbers, $males, $females, "Farmers and their locations");
        break;

    case "youth_farmers" :

        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $farmers_number = array();
        $tp = array();
        $youth = array();
        $old = array();


        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                $slected_rows = $mCrudFunctions->fetch_farmer_rows("dataset_" . $row['id'] . " GROUP BY mode_of_transport ORDER BY farmers DESC ",
                    "mode_of_transport as transport, COUNT(*) as farmers
                    ");

                foreach ($slected_rows as $sel) {
                    array_push($farmers_number, $sel['farmers']);
                    array_push($tp, $sel['transport']);
                }
            }
        }
        analyseSeeds($tp, $farmers_number, "bar", "Milk transportation modes used");
        break;

    case  "average_yield" :

        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $breeds = array();
        $no_farmers = array();

        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {

                if ($mCrudFunctions->check_table_exists("dataset_" . $row['id'])) {
                    $slected_rows = $mCrudFunctions->fetch_farmer_rows("dataset_" . $row['id'] . " GROUP BY breed_of_cows ",
                        "breed_of_cows as breeds, COUNT(*) as farmers
                    ");

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