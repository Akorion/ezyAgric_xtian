<?php
session_start();
#includes
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/json_models_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";
//id: id, district: district,country :country,parish : parish , village : village
/*$_POST['id']=5;
$_POST['district']='Bugiri';
$_POST['country']='Kapyanga';
$_POST['parish']='Bugudo';
$_POST['village']='Bugudo';
$_POST['production']='all';
$_POST['gender']="all";*/

$mCrudFunctions = new CrudFunctions();
$json_model_obj = new JSONModel();
$util_obj = new Utilties();

//header('Cache-Control: no-cache, must-revalidate');
//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
// header('Content-type: application/json');

$va = $_POST['va'];
$va = str_replace("(", "-", $va);
$va = str_replace(")", "", $va);
//$string="hyderabad-india";
$strings = explode('-', $va);
//echo $strings[0]; // outputs: hyderabad
//echo "<br>"; // new line
$va = strtolower($strings[1]); //


$age_min = !empty($_POST['age_min']) ? (int)$_POST['age_min'] : 0;
$age = !empty($_POST['age']) ? (int)$_POST['age'] : 0;
$age_max = !empty($_POST['age_max']) ? (int)$_POST['age_max'] : 0;
$ageFilter = "";
//floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)
if ($age != "" && $age != 0) {
    $ageFilter = " floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  AND ";
}

if ($age_min <= $age_max && $age_max != 0) {
    $ageFilter = "  (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  AND ";
}

if ($age_min > 0 && $age_max == 0) {
    $ageFilter = " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  AND ";
}

$data = array();
$branch = $_SESSION['user_account'];
$role =  $_SESSION['role'];

if (isset($_POST['id']) && isset($_POST['district'])
    && isset($_POST['country']) && isset($_POST['parish'])
    && isset($_POST['village']) && isset($_POST['production']))
{
    if($_SESSION["account_name"] == "Rushere SACCO"){
        $district = $_POST['district'];
        $subcounty = $_POST['country'];
        $parish = $_POST['parish'];

        if($role == 1){
            ///////////////////////////////////////districts
            if ($_POST['district'] == "all") {
                $table = "dataset_" . $_POST['id'];
                $rows = array();

                if ($_POST['production'] == "all") {

                    if ($_POST['gender'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM sacco_branch_name)  as sacco,
    COUNT(sacco_branch_name)  as frequency ", "1 GROUP BY sacco ORDER BY frequency Desc ");


                    } else {
                        $gender = $_POST['gender'];

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM sacco_branch_name) as sacco, 
    COUNT(sacco_branch_name)  as frequency ", " lower(biodata_gender) = '$gender'  GROUP BY sacco ORDER BY frequency Desc ");

                    }

                }
                else {
                    $string = $_POST['production'];
                    if (strpos($string, "productionyes") === false) {
                        if (strpos($string, "productionno") === false) {
                            if (strpos($string, "generalyes") === false) {
                                if (strpos($string, "generalno") === false) {
                                } else {

                                    $p_id = str_replace("generalno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {
                                        //


                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  GROUP BY district  ORDER BY frequency Desc");

                                        }

                                    } else {
                                        $gender = $_POST['gender'];

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");


                                        }


                                    }
                                }

                            } else {

                                $p_id = str_replace("generalyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {
                                    ////lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");


                                    }


                                } else {
                                    $gender = $_POST['gender'];
                                    ////lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                                    }

                                }
                            }

                        } else {

                            $p_id = str_replace("productionno", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];

                            if ($_POST['gender'] == "all") {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");

                                }


                            } else {
                                $gender = $_POST['gender'];
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");


                                }


                            }
                        }

                    } else {

                        $p_id = str_replace("productionyes", "", $string);

                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {
                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                            }


                        } else {
                            $gender = $_POST['gender'];
                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                            } else {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");


                            }


                        }
                    }


                }

                if (sizeof($rows) == 0) {
                    //$util_obj->deliver_response(200,0,null );
                    echo 0;
                } else {

                    $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers Per Branch'), "Branches", "sacco");
                    $util_obj->deliver_response(200, 1, $data);
                }


            } else

            ///////////////////////////////////////////subcounties
            if ($_POST['country'] == "all") {

                $table = "dataset_" . $_POST['id'];
                $district = $_POST['district'];
                $rows = array();
                if ($_POST['production'] == "all") {

                    if ($_POST['gender'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");

                    } else {
                        $gender = $_POST['gender'];

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc");

                    }

                }
                else {
                    $string = $_POST['production'];
                    if (strpos($string, "productionyes") === false) {
                        if (strpos($string, "productionno") === false) {
                            if (strpos($string, "generalyes") === false) {
                                if (strpos($string, "generalno") === false) {

                                } else {
                                    $p_id = str_replace("generalno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                        }

                                    } else {
                                        $gender = $_POST['gender'];


                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");

                                        } else {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc ");


                                        }

                                    }

                                }
                            } else {
                                $p_id = str_replace("generalyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");


                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc ");


                                    }

                                } else {
                                    $gender = $_POST['gender'];


                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['gender'] == "all") {

                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "    " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                        } else {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                        }

                                    } else {


                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                        } else {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                        }

                                    }


                                }
                            }


                        } else {
                            $p_id = str_replace("productionno", "", $string);

                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                } else {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                }


                            } else {
                                $gender = $_POST['gender'];


                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                } else {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                }

                            }
                        }

                    } else {
                        $p_id = str_replace("productionyes", "", $string);

                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");

                            }


                        } else {
                            $gender = $_POST['gender'];
                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc");


                            } else {
                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc");


                            }


                        }

                    }


                }


                if (sizeof($rows) == 0) {
                    //$util_obj->deliver_response(200,0,null );
                    echo 0;
                } else {
                    $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $district . " District"), "Subcounties", "subcounty");
                    $util_obj->deliver_response(200, 1, $data);
                }

            } else

            ///////////////////////////////////////////parish

            if ($_POST['parish'] == "all") {

                $table = "dataset_" . $_POST['id'];
                $subcounty = $_POST['country'];
                $rows = array();
                if ($_POST['production'] == "all") {
                    if ($_POST['gender'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                    } else {
                        $gender = $_POST['gender'];

                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                    }
                }
                else {
                    $string = $_POST['production'];
                    if (strpos($string, "productionyes") === false) {
                        if (strpos($string, "productionno") === false) {
                            if (strpos($string, "generalyes") === false) {
                                if (strpos($string, "generalno") === false) {
                                } else {
                                    $p_id = str_replace("generalno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                        }


                                    } else {
                                        $gender = $_POST['gender'];

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                        if ($_POST['va'] == "all") {


                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                        }


                                    }
                                }


                            } else {
                                $p_id = str_replace("generalyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");

                                    }

                                } else {
                                    $gender = $_POST['gender'];

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                    }

                                }

                            }
                        } else {
                            $p_id = str_replace("productionno", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");


                            } else {
                                $gender = $_POST['gender'];


                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                } else {
                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                }


                            }

                        }
                    } else {
                        $p_id = str_replace("productionyes", "", $string);
                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish  ORDER BY frequency Desc ");

                            }


                        } else {
                            $gender = $_POST['gender'];


                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                            }


                        }


                    }
                }

                if (sizeof($rows) == 0) {
                    //$util_obj->deliver_response(200,1,null );
                    echo 0;

                } else {
                    $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $subcounty . " Subcounty"), "Parishes", "parish");
                    $util_obj->deliver_response(200, 1, $data);
                }


            } else

            ///////////////////////////////////////////village

            if ($_POST['village'] == "all") {

                $table = "dataset_" . $_POST['id'];
                $parish = $_POST['parish'];
                $rows = array();
                if ($_POST['production'] == "all") {
                    if ($_POST['gender'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  GROUP BY village ORDER BY frequency Desc ");

                    } else {
                        $gender = $_POST['gender'];

                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc ");

                    }
                }
                else {
                    $string = $_POST['production'];
                    if (strpos($string, "productionyes") === false) {
                        if (strpos($string, "productionno") === false) {
                            if (strpos($string, "generalyes") === false) {
                                if (strpos($string, "generalno") === false) {
                                } else {
                                    $p_id = str_replace("generalno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                        }


                                    } else {
                                        $gender = $_POST['gender'];


                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                        }


                                    }

                                }
                            } else {
                                $p_id = str_replace("generalyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                    }


                                } else {
                                    $gender = $_POST['gender'];

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                    }

                                }

                            }

                        } else {
                            $p_id = str_replace("productionno", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                }

                            } else {
                                $gender = $_POST['gender'];


                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                }

                            }

                        }

                    } else {
                        $p_id = str_replace("productionyes", "", $string);
                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                            }


                        } else {
                            $gender = $_POST['gender'];

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                            }


                        }


                    }
                }

                if (sizeof($rows) == 0) {

                    //$util_obj->deliver_response(200,1,null );
                    echo 0;

                } else {
                    $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $parish . " Parish"), "Villages", "village");
                    $util_obj->deliver_response(200, 1, $data);
                }


            }
            else {

                //echo "off";
                $table = "dataset_" . $_POST['id'];
                $parish = $_POST['parish'];
                $village = $_POST['village'];
                $rows = array();
                if ($_POST['production'] == "all") {
                    if ($_POST['gender'] == "all") {

                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                        if ($_POST['va'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");
                        } else {

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                        }


                    } else {
                        $gender = $_POST['gender'];


                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                        if ($_POST['va'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                        } else {

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                        }


                    }
                } else {
                    $string = $_POST['production'];
                    if (strpos($string, "productionyes") === false) {
                        if (strpos($string, "productionno") === false) {
                            if (strpos($string, "generalyes") === false) {
                                if (strpos($string, "generalno") === false) {
                                } else {
                                    $p_id = str_replace("generalno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                        }


                                    } else {
                                        $gender = $_POST['gender'];


                                        ///lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                        } else {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                        }

                                    }

                                }
                            } else {
                                $p_id = str_replace("generalyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");


                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                    }


                                } else {
                                    $gender = $_POST['gender'];

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                                    }


                                }

                            }
                        } else {
                            $p_id = str_replace("productionno", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'GROUP BY village ORDER BY frequency Desc ");


                                }


                            } else {
                                $gender = $_POST['gender'];


                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY  village ORDER BY frequency Desc ");


                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY  village ORDER BY frequency Desc ");


                                }


                            }

                        }
                    } else {
                        $p_id = str_replace("productionyes", "", $string);
                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                            }


                        } else {
                            $gender = $_POST['gender'];


                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");


                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                            }


                        }

                    }
                }

                if (sizeof($rows) == 0) {

                    //$util_obj->deliver_response(200,0,null );
                    echo 0;

                } else {
                    $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $village . ' Village'), "Village", "village");
                    $util_obj->deliver_response(200, 1, $data);
                }


            }
        }
        elseif($role == 2) {
            ///////////////////////////////////////districts
            if ($_POST['district'] == "all") {
                $table = "dataset_" . $_POST['id'];
                $rows = array();

                if ($_POST['production'] == "all") {

                    if ($_POST['gender'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_cooperative_name)  as cooperative,
    COUNT(biodata_cooperative_name)  as frequency ", " sacco_branch_name LIKE '$branch' GROUP BY cooperative ORDER BY frequency Desc ");

                    } else {
                        $gender = $_POST['gender'];

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_cooperative_name) as cooperative, 
    COUNT(biodata_cooperative_name)  as frequency ", " lower(biodata_gender) = '$gender' AND sacco_branch_name LIKE '$branch'  GROUP BY cooperative ORDER BY frequency Desc ");

                    }
                }
                else {
                    $string = $_POST['production'];
                    if (strpos($string, "productionyes") === false) {
                        if (strpos($string, "productionno") === false) {
                            if (strpos($string, "generalyes") === false) {
                                if (strpos($string, "generalno") === false) {
                                } else {

                                    $p_id = str_replace("generalno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {
                                        //


                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  GROUP BY district  ORDER BY frequency Desc");

                                        }

                                    } else {
                                        $gender = $_POST['gender'];

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");


                                        }


                                    }
                                }

                            } else {

                                $p_id = str_replace("generalyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {
                                    ////lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");


                                    }


                                } else {
                                    $gender = $_POST['gender'];
                                    ////lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                                    }

                                }
                            }

                        } else {

                            $p_id = str_replace("productionno", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];

                            if ($_POST['gender'] == "all") {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");

                                }


                            } else {
                                $gender = $_POST['gender'];
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");


                                }


                            }
                        }

                    } else {

                        $p_id = str_replace("productionyes", "", $string);

                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {
                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                            }


                        } else {
                            $gender = $_POST['gender'];
                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                            } else {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");


                            }


                        }
                    }


                }

                if (sizeof($rows) == 0) {
                    //$util_obj->deliver_response(200,0,null );
                    echo 0;
                } else {

                    $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers Per Cooperative'), "Cooperatives", "cooperative");
                    $util_obj->deliver_response(200, 1, $data);
                }

            } else

                ///////////////////////////////////////////subcounties
                if ($_POST['country'] == "all") {

                    $table = "dataset_" . $_POST['id'];
                    $district = $_POST['district'];
                    $rows = array();
                    if ($_POST['production'] == "all") {

                        if ($_POST['gender'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND sacco_branch_name LIKE '$branch' GROUP BY subcounty  ORDER BY frequency Desc ");

                        } else {
                            $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND sacco_branch_name LIKE '$branch' GROUP BY subcounty  ORDER BY frequency Desc");

                        }

                    }
                    else {
                        $string = $_POST['production'];
                        if (strpos($string, "productionyes") === false) {
                            if (strpos($string, "productionno") === false) {
                                if (strpos($string, "generalyes") === false) {
                                    if (strpos($string, "generalno") === false) {

                                    } else {
                                        $p_id = str_replace("generalno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                            }

                                        } else {
                                            $gender = $_POST['gender'];


                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");

                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc ");


                                            }

                                        }

                                    }
                                } else {
                                    $p_id = str_replace("generalyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");


                                        } else {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc ");


                                        }

                                    } else {
                                        $gender = $_POST['gender'];


                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['gender'] == "all") {

                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "    " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                            }

                                        } else {


                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                            }

                                        }


                                    }
                                }


                            } else {
                                $p_id = str_replace("productionno", "", $string);

                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                    }


                                } else {
                                    $gender = $_POST['gender'];


                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                    }

                                }
                            }

                        } else {
                            $p_id = str_replace("productionyes", "", $string);

                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");

                                }


                            } else {
                                $gender = $_POST['gender'];
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc");


                                } else {
                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc");


                                }


                            }

                        }


                    }


                    if (sizeof($rows) == 0) {
                        //$util_obj->deliver_response(200,0,null );
                        echo 0;
                    } else {
                        $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $district . " District"), "Subcounties", "subcounty");
                        $util_obj->deliver_response(200, 1, $data);
                    }

                } else

                    ///////////////////////////////////////////parish

                    if ($_POST['parish'] == "all") {

                        $table = "dataset_" . $_POST['id'];
                        $subcounty = $_POST['country'];
                        $rows = array();
                        if ($_POST['production'] == "all") {
                            if ($_POST['gender'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND sacco_branch_name LIKE '$branch' GROUP BY parish ORDER BY frequency Desc ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND sacco_branch_name LIKE '$branch' GROUP BY parish ORDER BY frequency Desc ");

                            }
                        }
                        else {
                            $string = $_POST['production'];
                            if (strpos($string, "productionyes") === false) {
                                if (strpos($string, "productionno") === false) {
                                    if (strpos($string, "generalyes") === false) {
                                        if (strpos($string, "generalno") === false) {
                                        } else {
                                            $p_id = str_replace("generalno", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            if ($_POST['gender'] == "all") {

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                                }


                                            } else {
                                                $gender = $_POST['gender'];

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                                if ($_POST['va'] == "all") {


                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                                }


                                            }
                                        }


                                    } else {
                                        $p_id = str_replace("generalyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");

                                            }

                                        } else {
                                            $gender = $_POST['gender'];

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                            }

                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");


                                    } else {
                                        $gender = $_POST['gender'];


                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                        } else {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                        }


                                    }

                                }
                            } else {
                                $p_id = str_replace("productionyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish  ORDER BY frequency Desc ");

                                    }


                                } else {
                                    $gender = $_POST['gender'];


                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                    }


                                }


                            }
                        }

                        if (sizeof($rows) == 0) {
                            //$util_obj->deliver_response(200,1,null );
                            echo 0;

                        } else {
                            $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $subcounty . " Subcounty"), "Parishes", "parish");
                            $util_obj->deliver_response(200, 1, $data);
                        }


                    } else

                        ///////////////////////////////////////////village

                        if ($_POST['village'] == "all") {

                            $table = "dataset_" . $_POST['id'];
                            $parish = $_POST['parish'];
                            $rows = array();
                            if ($_POST['production'] == "all") {
                                if ($_POST['gender'] == "all") {


                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND sacco_branch_name LIKE '$branch'  GROUP BY village ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND sacco_branch_name LIKE '$branch' GROUP BY village ORDER BY frequency Desc ");

                                }
                            }
                            else {
                                $string = $_POST['production'];
                                if (strpos($string, "productionyes") === false) {
                                    if (strpos($string, "productionno") === false) {
                                        if (strpos($string, "generalyes") === false) {
                                            if (strpos($string, "generalno") === false) {
                                            } else {
                                                $p_id = str_replace("generalno", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                if ($_POST['gender'] == "all") {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                    }


                                                } else {
                                                    $gender = $_POST['gender'];


                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                                    }


                                                }

                                            }
                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            if ($_POST['gender'] == "all") {

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                }


                                            } else {
                                                $gender = $_POST['gender'];

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                }

                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                            }

                                        } else {
                                            $gender = $_POST['gender'];


                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                            }

                                        }

                                    }

                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                        }


                                    } else {
                                        $gender = $_POST['gender'];

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                        }


                                    }


                                }
                            }

                            if (sizeof($rows) == 0) {

                                //$util_obj->deliver_response(200,1,null );
                                echo 0;

                            } else {
                                $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $parish . " Parish"), "Villages", "village");
                                $util_obj->deliver_response(200, 1, $data);
                            }


                        }
                        else {

                            //echo "off";
                            $table = "dataset_" . $_POST['id'];
                            $parish = $_POST['parish'];
                            $village = $_POST['village'];
                            $rows = array();
                            if ($_POST['production'] == "all") {
                                if ($_POST['gender'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                }
                            }
                            else {
                                $string = $_POST['production'];
                                if (strpos($string, "productionyes") === false) {
                                    if (strpos($string, "productionno") === false) {
                                        if (strpos($string, "generalyes") === false) {
                                            if (strpos($string, "generalno") === false) {
                                            } else {
                                                $p_id = str_replace("generalno", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                if ($_POST['gender'] == "all") {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                                    }


                                                } else {
                                                    $gender = $_POST['gender'];


                                                    ///lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                                    if ($_POST['va'] == "all") {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                                    } else {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                    }

                                                }

                                            }
                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            if ($_POST['gender'] == "all") {

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                }


                                            } else {
                                                $gender = $_POST['gender'];

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                                                }


                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {
                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'GROUP BY village ORDER BY frequency Desc ");


                                            }


                                        } else {
                                            $gender = $_POST['gender'];


                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY  village ORDER BY frequency Desc ");


                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY  village ORDER BY frequency Desc ");


                                            }


                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                                        }


                                    } else {
                                        $gender = $_POST['gender'];


                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                        }


                                    }

                                }
                            }

                            if (sizeof($rows) == 0) {

                                //$util_obj->deliver_response(200,0,null );
                                echo 0;

                            } else {
                                $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $village . ' Village'), "Village", "village");
                                $util_obj->deliver_response(200, 1, $data);
                            }


                        }
        }
        else {
            ///////////////////////////////////////districts
            if ($_POST['district'] == "all") {
                $table = "dataset_" . $_POST['id'];
                $rows = array();

                if ($_POST['production'] == "all") {

                    if ($_POST['gender'] == "all") {

                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  as district,
    COUNT(DISTINCT(biodata_farmer_location_farmer_district))  as frequency ", " biodata_cooperative_name LIKE '$branch' GROUP BY district ORDER BY frequency Desc ");

                    } else {
                        $gender = $_POST['gender'];

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(DISTINCT(biodata_farmer_location_farmer_district)) as frequency ", " lower(biodata_gender) = '$gender' AND biodata_cooperative_name LIKE '$branch'  GROUP BY district ORDER BY frequency Desc ");

                    }
                }
                else {
                    $string = $_POST['production'];
                    if (strpos($string, "productionyes") === false) {
                        if (strpos($string, "productionno") === false) {
                            if (strpos($string, "generalyes") === false) {
                                if (strpos($string, "generalno") === false) {
                                } else {

                                    $p_id = str_replace("generalno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {
                                        //


                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  GROUP BY district  ORDER BY frequency Desc");

                                        }

                                    } else {
                                        $gender = $_POST['gender'];

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");


                                        }


                                    }
                                }

                            } else {

                                $p_id = str_replace("generalyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {
                                    ////lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");


                                    }


                                } else {
                                    $gender = $_POST['gender'];
                                    ////lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                                    }

                                }
                            }

                        } else {

                            $p_id = str_replace("productionno", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];

                            if ($_POST['gender'] == "all") {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");

                                }


                            } else {
                                $gender = $_POST['gender'];
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");


                                }


                            }
                        }

                    } else {

                        $p_id = str_replace("productionyes", "", $string);

                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {
                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                            }


                        } else {
                            $gender = $_POST['gender'];
                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                            } else {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");


                            }


                        }
                    }


                }

                if (sizeof($rows) == 0) {
                    //$util_obj->deliver_response(200,0,null );
                    echo 0;
                } else {

                    $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers Per District'), "Districts", "district");
                    $util_obj->deliver_response(200, 1, $data);
                }

            } else

                ///////////////////////////////////////////subcounties
                if ($_POST['country'] == "all") {

                    $table = "dataset_" . $_POST['id'];
                    $district = $_POST['district'];
                    $rows = array();
                    if ($_POST['production'] == "all") {

                        if ($_POST['gender'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(DISTINCT(biodata_farmer_location_farmer_subcounty)) as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND biodata_cooperative_name LIKE '$branch' GROUP BY subcounty  ORDER BY frequency Desc ");

                        } else {
                            $gender = $_POST['gender'];

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(DISTINCT(biodata_farmer_location_farmer_subcounty))  as frequency ", " lower(biodata_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND biodata_cooperative_name LIKE '$branch' GROUP BY subcounty  ORDER BY frequency Desc");

                        }


                    }
                    else {
                        $string = $_POST['production'];
                        if (strpos($string, "productionyes") === false) {
                            if (strpos($string, "productionno") === false) {
                                if (strpos($string, "generalyes") === false) {
                                    if (strpos($string, "generalno") === false) {

                                    } else {
                                        $p_id = str_replace("generalno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                            }

                                        } else {
                                            $gender = $_POST['gender'];


                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");

                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc ");


                                            }

                                        }

                                    }
                                } else {
                                    $p_id = str_replace("generalyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");


                                        } else {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc ");


                                        }

                                    } else {
                                        $gender = $_POST['gender'];


                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['gender'] == "all") {

                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "    " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                            }

                                        } else {


                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                            }

                                        }


                                    }
                                }


                            } else {
                                $p_id = str_replace("productionno", "", $string);

                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                    }


                                } else {
                                    $gender = $_POST['gender'];


                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                    }

                                }
                            }

                        } else {
                            $p_id = str_replace("productionyes", "", $string);

                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");

                                }


                            } else {
                                $gender = $_POST['gender'];
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc");


                                } else {
                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc");


                                }


                            }

                        }


                    }


                    if (sizeof($rows) == 0) {
                        //$util_obj->deliver_response(200,0,null );
                        echo 0;
                    } else {
                        $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $district . " District"), "Subcounties", "subcounty");
                        $util_obj->deliver_response(200, 1, $data);
                    }

                } else

                    ///////////////////////////////////////////parish

                    if ($_POST['parish'] == "all") {

                        $table = "dataset_" . $_POST['id'];
                        $subcounty = $_POST['country'];
                        $rows = array();
                        if ($_POST['production'] == "all") {
                            if ($_POST['gender'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(DISTINCT(biodata_farmer_location_farmer_parish)) as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND biodata_cooperative_name LIKE '$branch' GROUP BY parish ORDER BY frequency Desc ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(DISTINCT(biodata_farmer_location_farmer_parish))  as frequency ", " lower(biodata_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND biodata_cooperative_name LIKE '$branch' GROUP BY parish ORDER BY frequency Desc ");

                            }
                        }
                        else {
                            $string = $_POST['production'];
                            if (strpos($string, "productionyes") === false) {
                                if (strpos($string, "productionno") === false) {
                                    if (strpos($string, "generalyes") === false) {
                                        if (strpos($string, "generalno") === false) {
                                        } else {
                                            $p_id = str_replace("generalno", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            if ($_POST['gender'] == "all") {

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                                }


                                            } else {
                                                $gender = $_POST['gender'];

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                                if ($_POST['va'] == "all") {


                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                                }


                                            }
                                        }


                                    } else {
                                        $p_id = str_replace("generalyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");

                                            }

                                        } else {
                                            $gender = $_POST['gender'];

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                            }

                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");


                                    } else {
                                        $gender = $_POST['gender'];


                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                        } else {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                        }


                                    }

                                }
                            } else {
                                $p_id = str_replace("productionyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish  ORDER BY frequency Desc ");

                                    }


                                } else {
                                    $gender = $_POST['gender'];


                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                    }


                                }


                            }
                        }

                        if (sizeof($rows) == 0) {
                            //$util_obj->deliver_response(200,1,null );
                            echo 0;

                        } else {
                            $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $subcounty . " Subcounty"), "Parishes", "parish");
                            $util_obj->deliver_response(200, 1, $data);
                        }


                    } else

                        ///////////////////////////////////////////village

                        if ($_POST['village'] == "all") {

                            $table = "dataset_" . $_POST['id'];
                            $parish = $_POST['parish'];
                            $rows = array();
                            if ($_POST['production'] == "all") {
                                if ($_POST['gender'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(DISTINCT(biodata_farmer_location_farmer_village)) as frequency ", " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND biodata_cooperative_name LIKE '$branch'  GROUP BY village ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(DISTINCT(biodata_farmer_location_farmer_village)) as frequency ", " lower(biodata_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND biodata_cooperative_name LIKE '$branch' GROUP BY village ORDER BY frequency Desc ");

                                }
                            }
                            else {
                                $string = $_POST['production'];
                                if (strpos($string, "productionyes") === false) {
                                    if (strpos($string, "productionno") === false) {
                                        if (strpos($string, "generalyes") === false) {
                                            if (strpos($string, "generalno") === false) {
                                            } else {
                                                $p_id = str_replace("generalno", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                if ($_POST['gender'] == "all") {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                    }


                                                } else {
                                                    $gender = $_POST['gender'];


                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                                    }


                                                }

                                            }
                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            if ($_POST['gender'] == "all") {

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                }


                                            } else {
                                                $gender = $_POST['gender'];

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                }

                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                            }

                                        } else {
                                            $gender = $_POST['gender'];


                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                            }

                                        }

                                    }

                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                        }


                                    } else {
                                        $gender = $_POST['gender'];

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                        }


                                    }


                                }
                            }

                            if (sizeof($rows) == 0) {

                                //$util_obj->deliver_response(200,1,null );
                                echo 0;

                            } else {
                                $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $parish . " Parish"), "Villages", "village");
                                $util_obj->deliver_response(200, 1, $data);
                            }


                        }
                        else {

                            //echo "off";
                            $table = "dataset_" . $_POST['id'];
                            $parish = $_POST['parish'];
                            $village = $_POST['village'];
                            $rows = array();
                            if ($_POST['production'] == "all") {
                                if ($_POST['gender'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                }
                            }
                            else {
                                $string = $_POST['production'];
                                if (strpos($string, "productionyes") === false) {
                                    if (strpos($string, "productionno") === false) {
                                        if (strpos($string, "generalyes") === false) {
                                            if (strpos($string, "generalno") === false) {
                                            } else {
                                                $p_id = str_replace("generalno", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                if ($_POST['gender'] == "all") {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                    if ($_POST['va'] == "all") {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                                    } else {

                                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                                    }


                                                } else {
                                                    $gender = $_POST['gender'];


                                                    ///lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                                    if ($_POST['va'] == "all") {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                                    } else {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                    }

                                                }

                                            }
                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            if ($_POST['gender'] == "all") {

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                                }


                                            } else {
                                                $gender = $_POST['gender'];

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


                                                } else {

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                                                }


                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {
                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'GROUP BY village ORDER BY frequency Desc ");


                                            }


                                        } else {
                                            $gender = $_POST['gender'];


                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY  village ORDER BY frequency Desc ");


                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY  village ORDER BY frequency Desc ");


                                            }


                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                                        }


                                    } else {
                                        $gender = $_POST['gender'];


                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                        }


                                    }

                                }
                            }

                            if (sizeof($rows) == 0) {

                                //$util_obj->deliver_response(200,0,null );
                                echo 0;

                            } else {
                                $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $village . ' Village'), "Village", "village");
                                $util_obj->deliver_response(200, 1, $data);
                            }


                        }
        }
    }
    else {
    $district = $_POST['district'];
    $subcounty = $_POST['country'];
    $parish = $_POST['parish'];

    ///////////////////////////////////////districts
    if ($_POST['district'] == "all") {
        $table = "dataset_" . $_POST['id'];
        $rows = array();

        if ($_POST['production'] == "all") {

            if ($_POST['gender'] == "all") {

                if ($_POST['va'] == "all") {

                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  as district,
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  1 GROUP BY district ORDER BY frequency Desc ");

                } else {
                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                }


            } else {
                $gender = $_POST['gender'];

                if ($_POST['va'] == "all") {

                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  GROUP BY district ORDER BY frequency Desc ");

                } else {

                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                }

            }

        } else {
            $string = $_POST['production'];
            if (strpos($string, "productionyes") === false) {
                if (strpos($string, "productionno") === false) {
                    if (strpos($string, "generalyes") === false) {
                        if (strpos($string, "generalno") === false) {
                        } else {

                            $p_id = str_replace("generalno", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {
                                //


                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'  GROUP BY district  ORDER BY frequency Desc");

                                }

                            } else {
                                $gender = $_POST['gender'];

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");


                                }


                            }
                        }

                    } else {

                        $p_id = str_replace("generalyes", "", $string);
                        //
                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {
                            ////lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                            if ($_POST['va'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");


                            }


                        } else {
                            $gender = $_POST['gender'];
                            ////lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                            } else {
                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                            }

                        }
                    }

                } else {

                    $p_id = str_replace("productionno", "", $string);
                    //
                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                    $column = $row[0]['columns'];

                    if ($_POST['gender'] == "all") {
                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                        if ($_POST['va'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                        } else {

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");

                        }


                    } else {
                        $gender = $_POST['gender'];
                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                        if ($_POST['va'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no'  GROUP BY district  ORDER BY frequency Desc ");

                        } else {

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district  ORDER BY frequency Desc ");


                        }


                    }
                }

            } else {

                $p_id = str_replace("productionyes", "", $string);

                //
                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                $column = $row[0]['columns'];
                if ($_POST['gender'] == "all") {
                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                    if ($_POST['va'] == "all") {

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                    } else {

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");

                    }


                } else {
                    $gender = $_POST['gender'];
                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                    if ($_POST['va'] == "all") {

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes'  GROUP BY district ORDER BY frequency Desc ");

                    } else {
                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
    COUNT(biodata_farmer_location_farmer_district)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY district ORDER BY frequency Desc ");


                    }


                }
            }


        }

        if (sizeof($rows) == 0) {
            //$util_obj->deliver_response(200,0,null );
            echo 0;
        } else {

            $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers Per District'), "Districts", "district");
            $util_obj->deliver_response(200, 1, $data);
        }


    } else


    ///////////////////////////////////////////subcounties
        if ($_POST['country'] == "all") {

            $table = "dataset_" . $_POST['id'];
            $district = $_POST['district'];
            $rows = array();
            if ($_POST['production'] == "all") {

                if ($_POST['gender'] == "all") {
    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                    if ($_POST['va'] == "all") {

                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");

                    } else {
                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc ");


                    }

                } else {
                    $gender = $_POST['gender'];

                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                    if ($_POST['va'] == "all") {

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc");

                    } else {

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc");


                    }


                }


            } else {
                $string = $_POST['production'];
                if (strpos($string, "productionyes") === false) {
                    if (strpos($string, "productionno") === false) {
                        if (strpos($string, "generalyes") === false) {
                            if (strpos($string, "generalno") === false) {

                            } else {
                                $p_id = str_replace("generalno", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                    }

                                } else {
                                    $gender = $_POST['gender'];


                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");

                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc ");


                                    }

                                }

                            }
                        } else {
                            $p_id = str_replace("generalyes", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc ");


                                } else {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc ");


                                }

                            } else {
                                $gender = $_POST['gender'];


                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['gender'] == "all") {

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "    " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                    }

                                } else {


                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                                    }

                                }


                            }
                        }


                    } else {
                        $p_id = str_replace("productionno", "", $string);

                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                            } else {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                            }


                        } else {
                            $gender = $_POST['gender'];


                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                            } else {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");


                            }

                        }
                    }

                } else {
                    $p_id = str_replace("productionyes", "", $string);

                    //
                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                    $column = $row[0]['columns'];
                    if ($_POST['gender'] == "all") {
                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                        if ($_POST['va'] == "all") {

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                        } else {

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty ORDER BY frequency Desc ");

                        }


                    } else {
                        $gender = $_POST['gender'];
                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                        if ($_POST['va'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty  ORDER BY frequency Desc");


                        } else {
                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty)  as subcounty, 
    COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY subcounty  ORDER BY frequency Desc");


                        }


                    }

                }


            }


            if (sizeof($rows) == 0) {
    //$util_obj->deliver_response(200,0,null );
                echo 0;
            } else {
                $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $district . " District"), "Subcounties", "subcounty");
                $util_obj->deliver_response(200, 1, $data);
            }

        } else

    ///////////////////////////////////////////parish

            if ($_POST['parish'] == "all") {

                $table = "dataset_" . $_POST['id'];
                $subcounty = $_POST['country'];
                $rows = array();
                if ($_POST['production'] == "all") {
                    if ($_POST['gender'] == "all") {

                        // lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                        if ($_POST['va'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                        } else {

                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                        }


                    } else {
                        $gender = $_POST['gender'];


                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                        if ($_POST['va'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");
                        } else {

                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                        }

                    }
                } else {
                    $string = $_POST['production'];
                    if (strpos($string, "productionyes") === false) {
                        if (strpos($string, "productionno") === false) {
                            if (strpos($string, "generalyes") === false) {
                                if (strpos($string, "generalno") === false) {
                                } else {
                                    $p_id = str_replace("generalno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                        }


                                    } else {
                                        $gender = $_POST['gender'];

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                        if ($_POST['va'] == "all") {


                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                        }


                                    }
                                }


                            } else {
                                $p_id = str_replace("generalyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");

                                    }

                                } else {
                                    $gender = $_POST['gender'];

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                    if ($_POST['va'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                    } else {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                    }

                                }

                            }
                        } else {
                            $p_id = str_replace("productionno", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");


                            } else {
                                $gender = $_POST['gender'];


                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                                } else {
                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                                }


                            }

                        }
                    } else {
                        $p_id = str_replace("productionyes", "", $string);
                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        if ($_POST['gender'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish  ORDER BY frequency Desc ");

                            }


                        } else {
                            $gender = $_POST['gender'];


                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");


                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
    COUNT(biodata_farmer_location_farmer_parish)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY parish ORDER BY frequency Desc ");


                            }


                        }


                    }
                }

                if (sizeof($rows) == 0) {
    //$util_obj->deliver_response(200,1,null );
                    echo 0;

                } else {
                    $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $subcounty . " Subcounty"), "Parishes", "parish");
                    $util_obj->deliver_response(200, 1, $data);
                }


            } else

    ///////////////////////////////////////////village

                if ($_POST['village'] == "all") {

                    $table = "dataset_" . $_POST['id'];
                    $parish = $_POST['parish'];
                    $rows = array();
                    if ($_POST['production'] == "all") {
                        if ($_POST['gender'] == "all") {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  GROUP BY village ORDER BY frequency Desc ");


                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                            }


                        } else {
                            $gender = $_POST['gender'];

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc ");


                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                            }

                        }
                    } else {
                        $string = $_POST['production'];
                        if (strpos($string, "productionyes") === false) {
                            if (strpos($string, "productionno") === false) {
                                if (strpos($string, "generalyes") === false) {
                                    if (strpos($string, "generalno") === false) {
                                    } else {
                                        $p_id = str_replace("generalno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                            }


                                        } else {
                                            $gender = $_POST['gender'];


                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                            }


                                        }

                                    }
                                } else {
                                    $p_id = str_replace("generalyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                        }


                                    } else {
                                        $gender = $_POST['gender'];

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                        }

                                    }

                                }

                            } else {
                                $p_id = str_replace("productionno", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                    }

                                } else {
                                    $gender = $_POST['gender'];


                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                    }

                                }

                            }

                        } else {
                            $p_id = str_replace("productionyes", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " " . $column . "  LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                }


                            } else {
                                $gender = $_POST['gender'];

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
     COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                }


                            }


                        }
                    }

                    if (sizeof($rows) == 0) {

                        //$util_obj->deliver_response(200,1,null );
                        echo 0;

                    } else {
                        $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $parish . " Parish"), "Villages", "village");
                        $util_obj->deliver_response(200, 1, $data);
                    }


                } else {

    //echo "off";
                    $table = "dataset_" . $_POST['id'];
                    $parish = $_POST['parish'];
                    $village = $_POST['village'];
                    $rows = array();
                    if ($_POST['production'] == "all") {
                        if ($_POST['gender'] == "all") {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");
                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                            }


                        } else {
                            $gender = $_POST['gender'];


                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                            } else {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                            }


                        }
                    } else {
                        $string = $_POST['production'];
                        if (strpos($string, "productionyes") === false) {
                            if (strpos($string, "productionno") === false) {
                                if (strpos($string, "generalyes") === false) {
                                    if (strpos($string, "generalno") === false) {
                                    } else {
                                        $p_id = str_replace("generalno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                            } else {

                                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                            }


                                        } else {
                                            $gender = $_POST['gender'];


                                            ///lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                            if ($_POST['va'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                            } else {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                            }

                                        }

                                    }
                                } else {
                                    $p_id = str_replace("generalyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");


                                        }


                                    } else {
                                        $gender = $_POST['gender'];

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


                                        } else {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                                        }


                                    }

                                }
                            } else {
                                $p_id = str_replace("productionno", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                if ($_POST['gender'] == "all") {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'GROUP BY village ORDER BY frequency Desc ");


                                    }


                                } else {
                                    $gender = $_POST['gender'];


                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY  village ORDER BY frequency Desc ");


                                    } else {

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY  village ORDER BY frequency Desc ");


                                    }


                                }

                            }
                        } else {
                            $p_id = str_replace("productionyes", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            if ($_POST['gender'] == "all") {

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                if ($_POST['va'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village ORDER BY frequency Desc ");


                                }


                            } else {
                                $gender = $_POST['gender'];


                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");


                                } else {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
    COUNT(biodata_farmer_location_farmer_village)  as frequency ", $ageFilter . " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' GROUP BY village  ORDER BY frequency Desc ");

                                }


                            }

                        }
                    }

                    if (sizeof($rows) == 0) {

    //$util_obj->deliver_response(200,0,null );
                        echo 0;

                    } else {
                        $data = $json_model_obj->get_stat_bar_graph_json2($rows, array("text" => 'Farmers In ' . $village . ' Village'), "Village", "village");
                        $util_obj->deliver_response(200, 1, $data);
                    }


                }


    }
}
else {
    $util_obj->deliver_response(200, 0, null);
}


?>

