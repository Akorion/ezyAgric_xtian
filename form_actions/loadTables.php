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

    ///------------------------------ cooperative list ------------------------------------//
    case  "get_cooperative_list":
        session_start();
        $client_id = $_SESSION["client_id"];
        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

        $cooperatives = 0;
        foreach ($rows as $row) {
            if ($mCrudFunctions->check_table_exists("soil_results_" . $row['id'])) {
                $id = $row['id'];
                $cooperatives = $mCrudFunctions->fetch_rows("soil_results_" . $id . " s INNER JOIN dataset_" . $id . " d ON s.unique_id = d.unique_id", " DISTINCT(TRIM(s.`cooperative`)) as coops,COUNT(*) as farmers,round(AVG(ph),2) as ph", "1 GROUP BY TRIM(s.cooperative)");
                foreach ($cooperatives as $coop) {
                    $coops = $coop['coops'];
                    $farmers = $coop['farmers'];
                    $lbl_farmers = $farmers > 1 ? $farmers . " farmers" : $farmers . " farmer";
                    echo "                
                            <div class='graph_box'>
                                <h4><strong>$coops</strong></h4>
                                $lbl_farmers &nbsp;&nbsp;&nbsp;&nbsp; Average ph: " . $coop['ph'] . " &nbsp; 
                                <a href='#' class='btn btn-sm btn-success'><span style='color: white;' onclick='doStuff()'>$coops</span></a>  
                            </div>                                             
                        <br>
                        ";
                }
            }
        }
        break;

    //----------------------------farmers cooperative table --------------------------------
    case  "special_soil_samples" :

//        echo "<h4>" . $_POST["coop"] . " Soil test results </h4>";

        $rows = $mCrudFunctions->fetch_rows("sample_analysis_results", "*", "1");

        echo " <table class='table table-hover' id='dt_example'> <thead class='bg bg-success'><th>id</th> <th>Sample</th><th>pH</th> <th>OM</th> <th>N</th> <th>P</th><th>Ca</th> <th>Mg</th><th>K</th> </thead>";

        foreach ($rows as $row) {

            echo "<tr>  <td>" . $row['id'] . " </td> <td>" . $row['Sample'] . " </td><td> " . $row['pH'] . " </td><td>" . $row['OM'] . " </td><td> " . $row['N'] . " </td><td>" . $row['P'] . " </td><td> " . $row['Ca'] . " </td><td>" . $row['Mg'] . " </td><td> " . $row['K'] . " </td> </tr></a>";
//            echo "<a href='#'> <tr>  <td>" . $row['farmer_name'] . " </td> <td>" . $row['village'] . " </td> <td><button class='btn btn-success'>Details</button> </td></tr></a>";
        }

        echo " </table> ";
        break;
    case  "soil_sample_profiles" :

//        echo "<h4>" . $_POST["coop"] . " Soil test results </h4>";

        $rows = $mCrudFunctions->fetch_rows("soil_profiles", "*", "1");

        echo " <table class='table table-hover' id='soil_prof'> <thead class='bg bg-success'><th>id</th> <th>Hole</th><th>Horizon</th><th>pH</th> <th>OM</th> <th>N</th> <th>P</th><th>Ca</th> <th>Mg</th><th>K</th> </thead>";

        foreach ($rows as $row) {

            echo "<tr>  <td>" . $row['id'] . " </td> <td>" . $row['hole'] . " </td><td>" . $row['horizon'] . " </td><td> " . $row['pH'] . " </td><td>" . $row['OM'] . " </td><td> " . $row['N'] . " </td><td>" . $row['P'] . " </td><td> " . $row['Ca'] . " </td><td>" . $row['Mg'] . " </td><td> " . $row['K'] . " </td> </tr></a>";
//            echo "<a href='#'> <tr>  <td>" . $row['farmer_name'] . " </td> <td>" . $row['village'] . " </td> <td><button class='btn btn-success'>Details</button> </td></tr></a>";
        }

        echo " </table> ";
        break;

    case  "farmers_ph_levels":

        session_start();
        $client_id = $_SESSION["client_id"];

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmers_number = array();
        $levels = array("Very low", "Low", "Medium", "High", "Very high");
        $medium;
        $high;
        $very_high;
        $low;
        $verylow;

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {

                $verylow += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "ph <= 4.5");
                $low += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "ph > 4.5 AND ph <= 5.5");
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
        $medium;
        $high;
        $very_high;
        $low;
        $verylow;

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {

                $verylow += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`n_%` <= 0.05");
                $low += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`n_%` > 0.05 AND `n_%` <= 0.15");
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
                $low += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "`om_%` > 2.5 AND `om_%` <= 0.15");
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
        $medium;
        $high;
        $very_high;
        $low;
        $verylow;

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {

                $verylow += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "k <= 0.05");
                $low += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "k > 0.05 AND k <= 0.15");
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
        $recommendations = array("Lime", "CAN", "CAN_ASN", "Urea");
        $lime = 0;
        $can = 0;
        $can_asn = 0;
        $urea = 0;

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
        fertilizer_chart($lime, $can, $can_asn, $urea);
        break;


    case  "farmers_organic_matter":
        session_start();
        $client_id = $_SESSION["client_id"];

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmers_number = array();
        $levels = array("Trace", "Very low", "Low", "Medium", "High", "Very high");
        $trace;
        $medium;
        $high;
        $very_high;
        $low;
        $verylow;

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {

                $trace += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "p_ppm like 'trace'");
                $verylow += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "p_ppm BETWEEN 0.3 AND 12");
                $low += (int)$mCrudFunctions->get_count("soil_results_" . $row['id'], "p_ppm >12 AND p_ppm<=22.5");
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
        $medium = 0;
        $high = 0;
        $very_high = 0;
        $low = 0;
        $verylow = 0;

        $ph = array();
        $coops = array();

        foreach ($rows as $row) {

            if (($mCrudFunctions->check_table_exists("soil_results_" . $row['id']))) {
                $cooperatives = $mCrudFunctions->fetch_rows("soil_results_" . $row['id'], "DISTINCT(trim(`cooperative`)) as coops, round(AVG(`ph`),2)as ph ", "1 GROUP BY TRIM(cooperative) ASC");
                foreach ($cooperatives as $coop_dst) {
                    $ph = $coop_dst['ph'];

                    if ($ph <= 4.5) {
                        $verylow += 1;
                    } elseif ($ph > 4.5 && $ph <= 5.5) {
                        $low += 1;
                    } elseif ($ph > 5.5 && $ph <= 6.5) {
                        $medium += 1;
                    } elseif ($ph > 6.5 && $ph <= 7.8) {
                        $high += 1;
                    } else {
                        $very_high += 1;
                    }
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

function fertilizer_chart($lime, $can, $can_asn, $urea)
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