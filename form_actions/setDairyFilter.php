<?php
#includes
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";

$util_obj = new Utilties();
$db = new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();

if (isset($_POST['va']) && isset($_POST['id'])) {

    $table = "dataset_" . $_POST['id'];
    $va = $_POST['va'];
    $va_district = $_POST['va_district'];
    $va_county = $_POST['va_county'];
    $va_parish = $_POST['va_parish'];
    $va_village = $_POST['va_village'];

    echo $va_district;
    $row0s = array();
    if ($va_district == "all") {
        $row0s = $mCrudFunctions->fetch_rows($table, " lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) AS interview_particulars_va_code  ,interview_particulars_va_name ", "  1 GROUP BY interview_particulars_va_code ORDER BY interview_particulars_va_name ASC ");

    } else if ($va_county == "all") {
        $row0s = $mCrudFunctions->fetch_rows($table, "  lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) AS interview_particulars_va_code  ,interview_particulars_va_name ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$va_district'  GROUP BY interview_particulars_va_code ORDER BY interview_particulars_va_name ASC ");

    } else if ($va_parish == "all") {
        $row0s = $mCrudFunctions->fetch_rows($table, "  lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) AS interview_particulars_va_code  ,interview_particulars_va_name ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$va_district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$va_county' GROUP BY interview_particulars_va_code ORDER BY interview_particulars_va_name ASC ");

    } else if ($va_village == "all") {

        $row0s = $mCrudFunctions->fetch_rows($table, " lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) AS interview_particulars_va_code  ,interview_particulars_va_name ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$va_district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$va_county' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$va_parish' GROUP BY interview_particulars_va_code ORDER BY interview_particulars_va_name ASC ");


    } else {
        $row0s = $mCrudFunctions->fetch_rows($table, " lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) AS interview_particulars_va_code  ,interview_particulars_va_name ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$va_district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$va_county' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$va_parish'  AND TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$va_village'GROUP BY interview_particulars_va_code ORDER BY interview_particulars_va_name ASC ");

    }

    $f = sizeof($row0s);
    echo "<option value=\"all\">all</option>";
    //print_r($district." ".$table." ".$_POST['id']);
    $temp_array = array();
    foreach ($row0s as $row) {
        $value = $util_obj->captalizeEachWord($row['interview_particulars_va_name']);
        $value = str_replace(".", "", $value) . "(" . strtoupper($row['interview_particulars_va_code']) . ")";
        // echo "<option value=\"$value\">$value</option>";
        if (in_array($value, $temp_array)) {
        } else {
            array_push($temp_array, $value);
        }

    }


    $serialized = array_map('serialize', $temp_array);
    $unique = array_unique($serialized);
    $temp_array = array_intersect_key($temp_array, $unique);


    foreach ($temp_array as $temp) {
        echo "<option value=\"$temp\">$temp</option>";
    }

}
$dataset_type = "";
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $dataset_type = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id='$id'")[0]['dataset_type'];
}

if (isset($_POST['prodution_data_id'])) {
    $id = $_POST['prodution_data_id'];
    $dataset_type = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id='$id'")[0]['dataset_type'];
}
if (isset($_POST['district']) && isset($_POST['id'])) {

    $table = "dataset_" . $_POST['id'];
    $district = $_POST['district'];

    if ($dataset_type == "Farmer") {

        $row0s = $mCrudFunctions->fetch_rows($table, " DISTINCT TRIM(TRAILING '.' FROM subcounty) as subcounty ", " TRIM(TRAILING '.' FROM district) LIKE '$district' ORDER BY subcounty ASC");
        echo "<option value=\"all\">all</option>";
        //print_r($district." ".$table." ".$_POST['id']);
        $temp_array = array();
        foreach ($row0s as $row) {
            $value = $util_obj->captalizeEachWord($row['subcounty']);
            $value = str_replace(".", "", $value);
            ///echo "<option value=\"$value\">$value</option>";
            if (in_array($value, $temp_array)) {
            } else {
                array_push($temp_array, $value);
            }
        }

        foreach ($temp_array as $temp) {
            echo "<option value=\"$temp\">$temp</option>";
        }


    } else {

        $row0s = $mCrudFunctions->fetch_rows($table, " DISTINCT TRIM(TRAILING '.' FROM subcounty) as subcounty ", " TRIM(TRAILING '.' FROM district) LIKE '$district' ORDER BY subcounty ASC");
        echo "<option value=\"all\">All</option>";
        //print_r($district." ".$table." ".$_POST['id']);
        $temp_array = array();
        foreach ($row0s as $row) {
            $value = $util_obj->captalizeEachWord($row['subcounty']);
            $value = str_replace(".", "", $value);
            // echo "<option value=\"$value\">$value</option>";
            if (in_array($value, $temp_array)) {
            } else {
                array_push($temp_array, $value);
            }

        }

        foreach ($temp_array as $temp) {
            echo "<option value=\"$temp\">$temp</option>";
        }

    }

}

if (isset($_POST['parish_district']) && isset($_POST['parish_subcounty']) && isset($_POST['id'])) {

    $table = "dataset_" . $_POST['id'];
    $district = $_POST['parish_district'];
    $subcounty = $_POST['parish_subcounty'];
    if ($dataset_type == "Farmer") {

        $row0s = $mCrudFunctions->fetch_rows($table, " DISTINCT TRIM(TRAILING '.' FROM parish) as parish ", " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' ORDER BY parish ASC ");
        echo "<option value=\"all\">all</option>";
        $temp_array = array();
        foreach ($row0s as $row) {
            $value = $util_obj->captalizeEachWord($row['parish']);
            $value = str_replace(".", "", $value);
            //echo "<option value=\"$value\">$value</option>";
            if (in_array($value, $temp_array)) {
            } else {
                array_push($temp_array, $value);
            }

        }
        print_r($temp_array);
        foreach ($temp_array as $temp) {
            echo "<option value=\"$temp\">$temp</option>";
        }

    } else {


        $row0s = $mCrudFunctions->fetch_rows($table, " DISTINCT TRIM(TRAILING '.' FROM va_location_va_parish) as va_location_va_parish ", " TRIM(TRAILING '.' FROM va_location_va_district) LIKE '$district' AND TRIM(TRAILING '.' FROM va_location_va_subcounty) LIKE '$subcounty' ORDER BY va_location_va_parish ASC ");
        echo "<option value=\"all\">All</option>";
        $temp_array = array();
        foreach ($row0s as $row) {
            $value = $util_obj->captalizeEachWord($row['va_location_va_parish']);
            $value = str_replace(".", "", $value);
            //echo "<option value=\"$value\">$value</option>";
            if (in_array($value, $temp_array)) {
            } else {
                array_push($temp_array, $value);
            }

        }
        foreach ($temp_array as $temp) {
            echo "<option value=\"$temp\">$temp</option>";
        }

    }


}

if (isset($_POST['village_district']) && isset($_POST['village_subcounty']) && isset($_POST['village_parish']) && isset($_POST['id'])) {

    $table = "dataset_" . $_POST['id'];
    $district = $_POST['village_district'];
    $subcounty = $_POST['village_subcounty'];
    $parish = $_POST['village_parish'];
//    echo  "anything here ...........";
    if ($dataset_type == "Farmer") {
        $row0s = $mCrudFunctions->fetch_rows($table, " DISTINCT  TRIM(TRAILING '.' FROM village) as village ", " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM parish) LIKE '$parish' ORDER BY village ASC ");
        echo "<option value=\"all\">all</option>";
        $temp_array = array();
        foreach ($row0s as $row) {
            $value = $util_obj->captalizeEachWord($row['village']);
            $value = str_replace(".", "", $value);
            //echo "<option value=\"$value\">$value</option>";
            if (in_array($value, $temp_array)) {
            } else {
                array_push($temp_array, $value);
            }
        }
        print_r($temp_array);
        foreach ($temp_array as $temp) {
            echo "<option value=\"$temp\">$temp</option>";
        }
    } else {


        $row0s = $mCrudFunctions->fetch_rows($table, " DISTINCT  TRIM(TRAILING '.' FROM va_location_va_village) as va_location_va_village ", " TRIM(TRAILING '.' FROM va_location_va_district) LIKE '$district' AND TRIM(TRAILING '.' FROM va_location_va_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM va_location_va_parish) LIKE '$parish' ORDER BY va_location_va_village ASC ");
        echo "<option value=\"all\">All</option>";
        $temp_array = array();
        foreach ($row0s as $row) {
            $value = $util_obj->captalizeEachWord($row['va_location_va_village']);
            $value = str_replace(".", "", $value);
            //echo "<option value=\"$value\">$value</option>";
            if (in_array($value, $temp_array)) {
            } else {
                array_push($temp_array, $value);
            }

        }
        foreach ($temp_array as $temp) {
            echo "<option value=\"$temp\">$temp</option>";
        }

    }


}

if (isset($_POST['prodution_data']) && $_POST['prodution_data'] == 'true') {

    $id = $_POST['prodution_data_id'];
    $table = "dataset_" . $id;


    if ($_POST['dataset_type'] == "Farmer") {


        $production_data = $mCrudFunctions->fetch_rows("production_data", "*", " dataset_id='$id' ");
        echo "<option value=\"all\">all</option>";
        foreach ($production_data as $data) {
            $data_id = $data['id'];
            $column = $data['columns'];
            $enterprise = $data['enterprise'];
            $string = $enterprise . "_production_data_";
            $string = str_replace($string, "", $column);
            $string = str_replace("_", " ", $string);

            $value = $util_obj->captalizeEachWord($string);


            $rows = $mCrudFunctions->fetch_rows($table, "*", " " . $column . " LIKE 'no' OR " . $column . " LIKE 'Yes'");
            if (sizeof($rows) > 0) {
                $no = "no";
                $yes = "yes";
                $key = "production";
                echo "<option value=\"$key$yes$data_id\">$value (YES)</option>";
                echo "<option value=\"$key$no$data_id\">$value (NO)</option>";
            }

        }

        $general_data = $mCrudFunctions->fetch_rows("general_questions", "*", " dataset_id='$id' ");

        foreach ($general_data as $data) {
            $data_id = $data['id'];
            $column = $data['columns'];
            $string = "general_questions_";
            $string = str_replace($string, "", $column);
            $string = str_replace("_", " ", $string);
            $value = $util_obj->captalizeEachWord($string);
            $rows = $mCrudFunctions->fetch_rows($table, "*", " " . $column . " LIKE 'no' OR " . $column . " LIKE 'Yes'");
            if (sizeof($rows) > 0) {
                $no = "no";
                $yes = "yes";
                $key = "general";
                echo "<option value=\"$key$yes$data_id\">$value (YES)</option>";
                echo "<option value=\"$key$no$data_id\">$value (NO)</option>";
            }

        }


    } else {

        $production_data = $mCrudFunctions->fetch_rows("va_production_data", "*", " dataset_id='$id' ");
        echo "<option value=\"all\">all</option>";
        foreach ($production_data as $data) {
            $data_id = $data['id'];
            $column = $data['columns'];

            $string = "production_data_va_";
            $string = str_replace($string, "", $column);
            $string = "production_data_";
            $string = str_replace($string, "", $column);
            $string = str_replace("_", " ", $string);

            $value = $util_obj->captalizeEachWord($string);


            $rows = $mCrudFunctions->fetch_rows($table, "*", " " . $column . " LIKE 'no' OR " . $column . " LIKE 'Yes'");
            if (sizeof($rows) > 0) {
                $no = "no";
                $yes = "yes";
                $key = "production";
                echo "<option value=\"$key$yes$data_id\">$value (YES)</option>";
                echo "<option value=\"$key$no$data_id\">$value (NO)</option>";
            }

        }

        $va_farm_production_data = $mCrudFunctions->fetch_rows("va_farm_production_data", "*", " dataset_id='$id' ");

        foreach ($va_farm_production_data as $data) {
            $data_id = $data['id'];
            $column = $data['columns'];
            $string = "farm_production_data_";
            $string = str_replace($string, "", $column);
            $string = str_replace("_", " ", $string);
            $value = $util_obj->captalizeEachWord($string);
            $rows = $mCrudFunctions->fetch_rows($table, "*", " " . $column . " LIKE 'no' OR " . $column . " LIKE 'Yes'");
            if (sizeof($rows) > 0) {
                $no = "no";
                $yes = "yes";
                $key = "general";
                echo "<option value=\"$key$yes$data_id\">$value (YES)</option>";
                echo "<option value=\"$key$no$data_id\">$value (NO)</option>";
            }

        }


    }


}

?>