<?php
session_start();
#includes
require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";

$mCrudFunctions = new CrudFunctions();
$util_obj= new Utilties();
$newline ="\n";
$nextcell="";
$header="";
$body="";

$age_min=!empty($_POST['age_min'])? (int)$_POST['age_min'] :0;
$age=!empty($_POST['age'])? (int)$_POST['age'] :0;
$age_max=!empty($_POST['age_max'])? (int)$_POST['age_max'] :0;

$va=$_POST['va'];;
$va=str_replace("(","-",$va);
$va=str_replace(")","",$va);
$strings=explode('-',$va);
$va= strtolower($strings[1]); 

if(isset($_POST['id'])&&$_POST['id']!="") {
    $id = $_POST['id'];
    $gender = $_POST['gender'];

    $dataset_r = $mCrudFunctions->fetch_rows("datasets_tb", "dataset_name", " id ='$id'");
    $dataset = $dataset_r[0]["dataset_name"];

    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename=' . $dataset . ".csv");

    $bio_data_columns = array();

/////////////////////////////
    $array_bio_data_columns = "";
    $count_bio = 0;
    if (isset($_SESSION['fields']['bio_data'])) {
        $_SESSION['fields']['bio_data'] = array_unique($_SESSION['fields']['bio_data']);

    }
    foreach ($_SESSION['fields']['bio_data'] as $bio_data) {
        $count_bio++;

        $column = $mCrudFunctions->fetch_rows("bio_data", "columns", " id='$bio_data' AND dataset_id ='$id' ")[0]["columns"];
        $value = str_replace("biodata_farmer_", "", $column);

        if ($count_bio > 1 && ($count_bio - 1) < sizeof($_SESSION['fields']['bio_data'])) {

            if ($value == 'picture') {
            } else {
                $array_bio_data_columns .= "," . $column;
                array_push($bio_data_columns, $column);
            }

        } else {
            if ($value == 'picture') {
            } else {
                $array_bio_data_columns .= $column;
                array_push($bio_data_columns, $column);
            }

        }

        if ($value == 'picture') {
        } else {

            if (strpos($value, 'dob') !== false) {
                $value = "birth date";
            }
            $value = str_replace("_", " ", strtoupper($value));
            $header .= '"' . $util_obj->escape_csv_value($value) . '",';
        }

    }
//echo $array_bio_data_columns."</br>";
//print_r($bio_data_columns);
///////////////////////////////////////////

/////////////////////////////
    $farmer_location_columns = array();
    $array_farmer_location_data_columns = "";
    $count_farmer_location = 0;
    if (isset($_SESSION['fields']['farmer_location'])) {
        $_SESSION['fields']['farmer_location'] = array_unique($_SESSION['fields']['farmer_location']);
//sort($_SESSION['fields']['farmer_location']);
    }
    foreach ($_SESSION['fields']['farmer_location'] as $farmer_location) {
        $count_farmer_location++;

        if ($count_farmer_location > 1 && ($count_farmer_location - 1) < sizeof($_SESSION['fields']['farmer_location'])) {
            $columnlocation = $mCrudFunctions->fetch_rows("farmer_location", "columns", " id='$farmer_location' AND dataset_id ='$id' ")[0]["columns"];

            $array_farmer_location_data_columns .= "," . $columnlocation;
            $value = str_replace("biodata_farmer_location_farmer_", "", $columnlocation);
            $value = str_replace("home_gps_", "", $value);
            $value = str_replace("_", " ", strtoupper($value));
            $header .= '"' . $util_obj->escape_csv_value($value) . '",';

            array_push($farmer_location_columns, $columnlocation);
        } else {
            $columnlocation = $mCrudFunctions->fetch_rows("farmer_location", "columns", " id='$farmer_location' AND dataset_id ='$id' ")[0]["columns"];

            $array_farmer_location_data_columns .= $columnlocation;////
            $value = str_replace("biodata_farmer_location_farmer_", "", $columnlocation);
            $value = str_replace("home_gps_", "", $value);
            $value = str_replace("_", " ", strtoupper($value));
            $header .= '"' . $util_obj->escape_csv_value($value) . '",';
            array_push($farmer_location_columns, $columnlocation);
        }


    }
//echo $array_farmer_location_data_columns."</br>";
//print_r($_SESSION['fields']['farmer_location']);
///////////////////////////////////////////


//////////////////////////////

    if (isset($_SESSION['fields']['acreage_data'])) {

        if (sizeof($_SESSION['fields']['acreage_data']) > 0) {

            $header .= '"' . $util_obj->escape_csv_value("ACREAGE") . '",';

        }


//sort($_SESSION['fields']['production_data']);
    }

/////////////////////////

/////////////////////////////
    $production_data_columns = array();
    $array_production_data_columns = "";
    $count_production = 0;
    if (isset($_SESSION['fields']['production_data'])) {
        $_SESSION['fields']['production_data'] = array_unique($_SESSION['fields']['production_data']);
//sort($_SESSION['fields']['production_data']);
    }
    foreach ($_SESSION['fields']['production_data'] as $production_data) {
        $count_production++;
        $column = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$production_data' AND dataset_id ='$id' ");

        if ($count_production > 1 && ($count_production - 1) < sizeof($_SESSION['fields']['production_data'])) {

            $array_production_data_columns .= "," . $column[0]["columns"];
        } else {

            $array_production_data_columns .= $column[0]["columns"];
        }

        array_push($production_data_columns, $column[0]["columns"]);
        $enterprise = $column[0]['enterprise'];
        $value = str_replace($enterprise . "_production_data_", "", $column[0]['columns']);
        $value = str_replace("_", " ", strtoupper($value));
        $header .= '"' . $util_obj->escape_csv_value($value) . '",';

    }
//echo $array_production_data_columns."</br>";
//print_r($_SESSION['fields']['production_data']);
///////////////////////////////////////////

/////////////////////////////
    $info_on_other_enterprise_columns = array();
    $array_info_on_other_enterprise_data_columns = "";
    $count_info_on_other_enterprise = 0;
    if (isset($_SESSION['fields']['info_on_other_enterprise'])) {
        $_SESSION['fields']['info_on_other_enterprise'] = array_unique($_SESSION['fields']['info_on_other_enterprise']);
//sort($_SESSION['fields']['info_on_other_enterprise']);
    }
    foreach ($_SESSION['fields']['info_on_other_enterprise'] as $info_on_other_enterprise) {
        $count_info_on_other_enterprise++;

        $column = $mCrudFunctions->fetch_rows("info_on_other_enterprise", "columns", " id='$info_on_other_enterprise' AND dataset_id ='$id' ")[0]["columns"];

        if ($count_info_on_other_enterprise > 1 && ($count_info_on_other_enterprise - 1) < sizeof($_SESSION['fields']['info_on_other_enterprise'])) {

            $array_info_on_other_enterprise_data_columns .= "," . $column;
        } else {

            $array_info_on_other_enterprise_data_columns .= $column;
        }
        array_push($info_on_other_enterprise_columns, $column);
        $value = str_replace("information_on_other_crops_", "", $column);
        $value = str_replace("_", " ", strtoupper($value));
        $header .= '"' . $util_obj->escape_csv_value($value) . '",';

    }
//echo $array_info_on_other_enterprise_data_columns."</br>";
//print_r($_SESSION['fields']['info_on_other_enterprise']);
///////////////////////////////////////////


/////////////////////////////
    $general_questions_columns = array();
    $array_general_questions_data_columns = "";
    $count_general_questions = 0;
    if (isset($_SESSION['fields']['general_questions'])) {
        $_SESSION['fields']['general_questions'] = array_unique($_SESSION['fields']['general_questions']);
//sort($_SESSION['fields']['general_questions']);
    }
    foreach ($_SESSION['fields']['general_questions'] as $general_questions) {
        $count_general_questions++;
        $column = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$general_questions' AND dataset_id ='$id' ")[0]["columns"];


        if ($count_general_questions > 1 && ($count_general_questions - 1) < sizeof($_SESSION['fields']['general_questions'])) {

            $array_general_questions_data_columns .= "," . $column;
        } else {

            $array_general_questions_data_columns .= $column;
        }
        array_push($general_questions_columns, $column);
        $value = str_replace("general_questions_", "", $column);
        $value = str_replace("_", " ", strtoupper($value));
        $header .= '"' . $util_obj->escape_csv_value($value) . '",';


    }
//echo $array_general_questions_data_columns."</br>";
//print_r($_SESSION['fields']['general_questions']);
///////////////////////////////////////////

    $total_columns = "";
    if ($array_bio_data_columns != "") {

        $total_columns .= $array_bio_data_columns;

    }

    if ($total_columns != "" && $array_farmer_location_data_columns != "") {

        $total_columns .= "," . $array_farmer_location_data_columns;

    } else if ($array_farmer_location_data_columns != "") {
        $total_columns .= $array_farmer_location_data_columns;


        if ($total_columns != "" && $array_production_data_columns != "") {

            $total_columns .= "," . $array_production_data_columns;

        } else if ($array_production_data_columns != "") {
            $total_columns .= $array_production_data_columns;

        }

        if ($total_columns != "" && $array_info_on_other_enterprise_data_columns != "") {

            $total_columns .= "," . $array_info_on_other_enterprise_data_columns;

        } else if ($array_info_on_other_enterprise_data_columns != "") {
            $total_columns .= $array_info_on_other_enterprise_data_columns;

        }

        if ($total_columns != "" && $array_general_questions_data_columns != "") {

            $total_columns .= "," . $array_general_questions_data_columns;

        } else if ($array_general_questions_data_columns != "") {
            $total_columns .= $array_general_questions_data_columns;

        }


//echo $total_columns."</br>";

//$bio_data_columns = $mCrudFunctions->fetch_rows("bio_data","columns"," dataset_id ='$id'");

        /*$farmer_location_columns = $mCrudFunctions->fetch_rows("farmer_location","columns"," dataset_id ='$id'");

        $production_data_columns = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," dataset_id ='$id'");

        $info_on_other_enterprise_columns = $mCrudFunctions->fetch_rows("info_on_other_enterprise","columns"," dataset_id ='$id'");

        $general_questions_columns = $mCrudFunctions->fetch_rows("general_questions","columns"," dataset_id ='$id'");
        */
        /*
        foreach($bio_data_columns as $column){

        $value=str_replace("biodata_farmer_","",$column['columns']);
        if($value=='picture'){}else{
        $value=str_replace("_"," ",strtoupper($value));
        $header .='"'.$util_obj->escape_csv_value($value).'",';
        }

        }

        foreach($farmer_location_columns as $column){

        $value=str_replace("biodata_farmer_location_farmer_","",$column['columns']);
        $value=str_replace("home_gps_","",$value);
        $value=str_replace("_"," ",strtoupper($value));
        $header .='"'.$util_obj->escape_csv_value($value).'",';


        }

        foreach($production_data_columns as $column){

        $enterprise=$column['enterprise'];
        $value=str_replace($enterprise."_production_data_","",$column['columns']);
        $value=str_replace("_"," ",strtoupper($value));
        $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($info_on_other_enterprise_columns as $column){
        $value=str_replace("information_on_other_crops_","",$column['columns']);
        $value=str_replace("_"," ",strtoupper($value));
        $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($general_questions_columns as $column){
        $value=str_replace("general_questions_","",$column['columns']);
        $value=str_replace("_"," ",strtoupper($value));
        $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        */

        $body .= $header;
        $body .= $newline;
        $rows = array();
        $total_columns = "*";
        if (isset($_POST['district']) && $_POST['district'] != "") {
            switch ($_POST['district']) {
                case  "all" :

////////////////////////////////////////////////////////district all starts
                    if ($_POST['sel_production_id'] == "all") {
                        if ($_POST['gender'] == "all") {

                            if ($_POST['va'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, 1);
                            } else {
                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                            }

                        } else {

                            if ($_POST['va'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " lower(biodata_farmer_gender) = '$gender' ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " lower(biodata_farmer_gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");
                            }


                        }
                    } else {

                        $string = $_POST['sel_production_id'];

                        if (strpos($string, "productionyes") === false) {
                            if (strpos($string, "productionno") === false) {

                                if (strpos($string, "generalyes") === false) {

                                    if (strpos($string, "generalno") === false) {
                                    } else {
                                        $p_id = str_replace("generalno", "", $string);
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id'  ");
                                        $column = $row[0]['columns'];

                                        if ($_POST['gender'] == "all") {


                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  " . $column . " LIKE 'no'");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                            }

                                        } else {

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no'");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                            }

                                        }

                                    }
                                } else {

                                    $p_id = str_replace("generalyes", "", $string);
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];

                                    if ($_POST['gender'] == "all") {

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  " . $column . " LIKE 'yes'");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  " . $column . " LIKE 'yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                        }

                                    } else {

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  lower(biodata_farmer_gender) = '$gender' AND  " . $column . " LIKE 'yes'");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  lower(biodata_farmer_gender) = '$gender' AND  " . $column . " LIKE 'yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");

                                        }

                                    }


                                }
                            } else {


                                $p_id = str_replace("productionno", "", $string);
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];

                                if ($_POST['gender'] == "all") {


                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  " . $column . " LIKE 'no'");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");

                                    }


                                } else {

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " lower(biodata_farmer_gender) = '$gender' AND  " . $column . " LIKE 'no'");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " lower(biodata_farmer_gender) = '$gender' AND  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");

                                    }


                                }


                            }
                        } else {

                            $p_id = str_replace("productionyes", "", $string);
                            //echo $string;
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id'  ");
                            $column = $row[0]['columns'];

                            if ($_POST['gender'] == "all") {


                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  " . $column . " LIKE 'yes'");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  " . $column . " LIKE 'yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'");

                                }


                            } else {


                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " lower(biodata_farmer_gender) = '$gender' AND  " . $column . " LIKE 'yes'");


                                } else {

                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " lower(biodata_farmer_gender) = '$gender' AND  " . $column . " LIKE 'yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                }


                            }
                        }
                    }

                    break;
                default:

                    if ($_POST['subcounty'] == "all") {
                        $district = $_POST['district'];

///////////////////////////////////////////////////////////////////// subcounty all starts
                        if ($_POST['sel_production_id'] == "all") {


                            if ($_POST['gender'] == "all") {


                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' ");


                                } else {

                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");


                                }


                            } else {

                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                }


                            }

                        } else {

                            $mCrudFunctions = new CrudFunctions();
                            $string = $_POST['sel_production_id'];

                            if (strpos($string, "productionyes") === false) {
                                if (strpos($string, "productionno") === false) {
                                    if (strpos($string, "generalyes") === false) {
                                        if (strpos($string, "generalno") === false) {
                                        } else {
                                            $p_id = str_replace("generalno", "", $string);
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];


                                            if ($_POST['gender'] == "all") {

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no'  ");

                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                                }


                                            } else {

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' ");

                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                                }


                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("generalyes", "", $string);
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];

                                        if ($_POST['gender'] == "all") {

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'Yes' ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                            }


                                        } else {


                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'Yes' ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                            }


                                        }
                                    }

                                } else {
                                    $p_id = str_replace("productionno", "", $string);
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];


                                    if ($_POST['gender'] == "all") {

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                        }


                                    } else {


                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                        }


                                    }


                                }
                            } else {
                                $p_id = str_replace("productionyes", "", $string);
                                //echo $string;
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                //echo $column;

                                if ($_POST['gender'] == "all") {

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND " . $column . " LIKE 'Yes' ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                    }


                                } else {

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                    }

                                }


                            }

                        }
//////////////////////////////////////////////////////////////////// subcounty all ends
                    } else {
                        $subcounty = $_POST['subcounty'];
                        $district = $_POST['district'];
                        if ($_POST['parish'] == "all") {

                            ////////////////////////////////////////////////////////////////////// parish all starts
                            if ($_POST['sel_production_id'] == "all") {

                                if ($_POST['gender'] == "all") {

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                    }

                                } else {

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                    }


                                }


                            } else {

                                $mCrudFunctions = new CrudFunctions();
                                $string = $_POST['sel_production_id'];


                                if (strpos($string, "productionyes") === false) {
                                    if (strpos($string, "productionno") === false) {
                                        if (strpos($string, "generalyes") === false) {
                                            if (strpos($string, "generalno") === false) {
                                            } else {
                                                $p_id = str_replace("generalno", "", $string);
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];


                                                if ($_POST['gender'] == "all") {

                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' ");

                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                    }


                                                } else {

                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' ");

                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                    }


                                                }

                                            }

                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];


                                            if ($_POST['gender'] == "all") {

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes'  ");

                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                }


                                            } else {

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' ");

                                                } else {
                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                                }

                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];


                                        if ($_POST['gender'] == "all") {

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                            }


                                        } else {

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                            }

                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    //echo $string;
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    // echo $column;

                                    if ($_POST['gender'] == "all") {

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                        }


                                    } else {


                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                        }


                                    }

                                }

                            }
///////////////////////////////////////////////////////////////////////////////// parish all ends
                        } else {
                            $subcounty = $_POST['subcounty'];
                            $district = $_POST['district'];
                            $parish = $_POST['parish'];

                            if ($_POST['village'] == "all") {

                                //////////////////////////////////////////////////////////////////////////// village all starts
                                if ($_POST['sel_production_id'] == "all") {


                                    if ($_POST['gender'] == "all") {

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                        }


                                    } else {

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND biodata_farmer_location_farmer_parish LIKE '%$parish%'  ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                        }

                                    }
                                } else {

                                    $mCrudFunctions = new CrudFunctions();
                                    $string = $_POST['sel_production_id'];


                                    if (strpos($string, "productionyes") === false) {
                                        if (strpos($string, "productionno") === false) {
                                            if (strpos($string, "generalyes") === false) {
                                                if (strpos($string, "generalno") === false) {
                                                } else {
                                                    $p_id = str_replace("generalno", "", $string);
                                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                    $column = $row[0]['columns'];


                                                    if ($_POST['gender'] == "all") {

                                                        if ($_POST['va'] == "all") {

                                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND " . $column . " LIKE 'no' ");

                                                        } else {

                                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                        }

                                                    } else {

                                                        if ($_POST['va'] == "all") {

                                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' ");

                                                        } else {

                                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                        }

                                                    }

                                                }
                                            } else {
                                                $p_id = str_replace("generalyes", "", $string);
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];


                                                if ($_POST['gender'] == "all") {

                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' ");

                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                    }


                                                } else {

                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes'  ");

                                                    } else {
                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                                    }


                                                }

                                            }

                                        } else {
                                            $p_id = str_replace("productionno", "", $string);
                                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];


                                            if ($_POST['gender'] == "all") {

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'no' ");

                                                } else {
                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                }


                                            } else {

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no'  ");

                                                } else {
                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                                }


                                            }


                                        }
                                    } else {
                                        $p_id = str_replace("productionyes", "", $string);
                                        // echo $string;
                                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        //echo $column;

                                        if ($_POST['gender'] == "all") {

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes'  ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                            }


                                        } else {

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes'  ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                            }

                                        }

                                    }

                                }

                                /////////////////////////////////////////////////////////////////////////////
                            } else {
                                $subcounty = $_POST['subcounty'];
                                $district = $_POST['district'];
                                $parish = $_POST['parish'];
                                $village = $_POST['village'];


                                if ($_POST['sel_production_id'] == "all") {


                                    if ($_POST['gender'] == "all") {

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                        }


                                    } else {

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' ");

                                        } else {
                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                        }


                                    }


                                } else {

                                    $mCrudFunctions = new CrudFunctions();
                                    $string = $_POST['sel_production_id'];


                                    if (strpos($string, "productionyes") === false) {
                                        if (strpos($string, "productionno") === false) {
                                            if (strpos($string, "generalyes") === false) {
                                                if (strpos($string, "generalno") === false) {
                                                } else {
                                                    $p_id = str_replace("generalno", "", $string);
                                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                    $column = $row[0]['columns'];

                                                    if ($_POST['gender'] == "all") {

                                                        if ($_POST['va'] == "all") {

                                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND " . $column . " LIKE 'no'  ");

                                                        } else {
                                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                        }


                                                    } else {

                                                        if ($_POST['va'] == "all") {

                                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no'  ");

                                                        } else {
                                                            $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  ");

                                                        }


                                                    }

                                                }
                                            } else {
                                                $p_id = str_replace("generalyes", "", $string);
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];


                                                if ($_POST['gender'] == "all") {

                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes'  ");

                                                    } else {
                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");
                                                    }

                                                } else {


                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' ");

                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                    }

                                                }

                                            }

                                        } else {
                                            $p_id = str_replace("productionno", "", $string);
                                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];


                                            if ($_POST['gender'] == "all") {

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'no' ");

                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                }


                                            } else {

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no' ");

                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                                }


                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("productionyes", "", $string);
                                        // echo $string;
                                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        //echo $column;


                                        if ($_POST['gender'] == "all") {

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes'  ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                            }


                                        } else {

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' ");

                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows("dataset_" . $id, $total_columns, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ");

                                            }

                                        }

                                    }

                                }

                            }

                        }

                    }

                    break;

            }
        }

        foreach ($rows as $row) {

            foreach ($bio_data_columns as $column) {

                $value = str_replace("biodata_farmer_", "", $column);
                if ($value == 'picture') {
                } else {
                    $key = $column;
                    $final = $row[$key];
                    $final = str_replace("_", " ", $final);
                    $body .= '"' . $util_obj->escape_csv_value($final) . '",';
                }

            }

            foreach ($farmer_location_columns as $column) {

                $key = $column;
                $final = $row[$key];
                $final = str_replace("_", " ", $final);
                $body .= '"' . $util_obj->escape_csv_value($final) . '",';

            }
////////////////////////////////


            if (isset($_SESSION['fields']['acreage_data'])) {

                if (sizeof($_SESSION['fields']['acreage_data']) > 0) {
                    $acares = array();

                    $gardens_table = "garden_" . $id;
                    $uuid = $util_obj->remove_apostrophes($row['meta_instanceID']);
                    if ($mCrudFunctions->check_table_exists($gardens_table) > 0) {

                        $gardens = $mCrudFunctions->fetch_rows($gardens_table, " DISTINCT PARENT_KEY_ ", " PARENT_KEY_ LIKE '$uuid%' ");

                        if (sizeof($gardens) > 0) {
                            $z = 1;
                            foreach ($gardens as $garden) {
                                $key = $uuid . "/gardens[$z]";
                                $data_rows = $mCrudFunctions->fetch_rows($gardens_table, "garden_gps_point_Latitude,garden_gps_point_Longitude", " PARENT_KEY_ ='$key'");
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
                            $acreage = 0;
                            if ($total_gardens != 0) {
                                $acreage = round(array_sum($acares), 2);
                            }
                            $body .= '"' . $util_obj->escape_csv_value($acreage) . '",';

                        } else {
                            $body .= '"' . $util_obj->escape_csv_value(0) . '",';
                        }


                    }


//sort($_SESSION['fields']['production_data']);
                }

            }

            ///////////////////////////////////////
            foreach ($production_data_columns as $column) {

                $key = $column;
                $final = $row[$key];
                $final = str_replace("_", " ", $final);
                $body .= '"' . $util_obj->escape_csv_value($final) . '",';

            }

            foreach ($info_on_other_enterprise_columns as $column) {

                $key = $column;
                $final = $row[$key];
                $final = str_replace("_", " ", $final);
                $body .= '"' . $util_obj->escape_csv_value($final) . '",';

            }

            foreach ($general_questions_columns as $column) {

                $key = $column;
                $final = $row[$key];
                $final = str_replace("_", " ", $final);
                $body .= '"' . $util_obj->escape_csv_value($final) . '",';

            }
            $body .= $newline;//
        }

        echo $body;

    }

}

?>