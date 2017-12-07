<?php
session_start();
#includes
require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";

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

$va_sql=" ";
if($_POST['va']!="all"){
$va_sql=" AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'";
}
$ageFilter="";
if($age!="" && $age!=0){
 
  $ageFilter= " floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  AND ";
 }
 
 if($age_min<=$age_max && $age_max!=0){
  $ageFilter= "  (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  AND ";
 }
 
 if($age_min>0 &&$age_max==0 ){
  $ageFilter= " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  AND ";
 }

$date = $_POST['date_'];
$client_id = $_SESSION["client_id"];
$branch = $_SESSION['user_account'];
$role =  $_SESSION['role'];

$rushere_farmer_nos = array();

if(isset($_POST['id'])&&isset($_POST['district'])
  &&isset($_POST['country'])&&isset($_POST['parish'])
  &&isset($_POST['village'])&&isset($_POST['production'])) {

    $id = $_POST['id'];
    $district = $_POST['district'];
    $county = $_POST['country'];
    $parish = $_POST['parish'];
    $village = $_POST['village'];
    $gender = $_POST['gender'];
    $production_header = "all";

    $mCrudFunctions = new CrudFunctions();
    $util_obj = new Utilties();

    $dataset_r = $mCrudFunctions->fetch_rows("datasets_tb", "dataset_name", " id ='$id' AND client_id = '$client_id' ");
    $dataset = $dataset_r[0]["dataset_name"];

    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename=' . $dataset . ".csv");

    if($_SESSION["account_name"] == "Rushere SACCO"){
        if($role == 1){
            if(isset($_POST['date_']) && $_POST['date_'] != ""){
                $rows = array();    $data = array();
                $table = "dataset_" . $_POST['id'];
                $date = $_POST['date_'];

                $milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$date&end_date=$date");
                $milk_periodic_data = json_decode($milk_data);

                $rows = $mCrudFunctions->fetch_rows($table, "biodata_phonenumber, biodata_first_name, biodata_last_name", 1);
                foreach ($rows as $rn) {
                    $name = $rn['biodata_first_name']." ".$rn['biodata_last_name'];
                    $f_nm = array("name" => $name, "mobile" => $rn['biodata_phonenumber']);
                    array_push($rushere_farmer_nos, $f_nm);
                }

                foreach ($milk_periodic_data as $milk_supply)
                {
                    $account = $milk_supply->account_no;
                    $mobile_no = substr($account, 6);
                    $milk_quantity = $milk_supply->milk_amount;

                    foreach ($rushere_farmer_nos as $client_farmers) {
                        $f_name = $client_farmers['name'];
                        $f_phone = $client_farmers['mobile'];
                        if ($f_phone == $mobile_no) {
                            $f_data = array("name"=>$f_name, "milk_qnty"=>$milk_quantity);
                            array_push($data, $f_data);
                        }
                    }
                }

//                $production_header = $util_obj->captalizeEachWord($production_header);

                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "Milk supplied By the Following Farmers on " . $date.'",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "No." . '",';
                $header .= '"' . "Farmer" . '",';
                $header .= '"' . "Milk (Ltrs)" . '",';

                $header .= $newline;

                $body .= $header;

                if(sizeof($rows) > 0){
                    $i = 1;
                    foreach($data as $fdata){
                        $district = $fdata['name'];
                        $frequency = $fdata['milk_qnty'];

                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                        $body .= $newline;

                        $i++;
                    }
                    $body .= '"' . $nextcell . '",';
                    $body .= '"' . $nextcell . '",';

                    echo $body;
                }

            }
            else if(isset($_POST['date_from']) && $_POST['date_from'] != "" && isset($_POST['date_to']) && $_POST['date_to'] != ""){
                $rows = array();    $data = array();
                $table = "dataset_" . $_POST['id'];
                $date_from = $_POST['date_from'];   $date_to = $_POST['date_to'];
                $bran_mlk_spd = new stdClass();
                $branches = array();    $milk_data_arr = array();   $brh_mlk = array();

                $milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$date_from&end_date=$date_to");
                $milk_periodic_data = json_decode($milk_data);

                $rows = $mCrudFunctions->fetch_rows($table, "biodata_phonenumber as mobile, sacco_branch_name as branch", 1);
                foreach ($rows as $rn) {
                    array_push($branches, $rn['branch']);
                    $bran_mlk_spd->{$rn['branch']} = 0;
                    $f_nm = array("branch" => $rn['branch'], "mobile" => $rn['mobile']);
                    array_push($rushere_farmer_nos, $f_nm);
                }
                $branches = array_values(array_unique($branches));

                foreach ($milk_periodic_data as $milk_supply){
                    $account = $milk_supply->account_no;
                    $mobile_no = substr($account, 6);
                    $milk_quantity = $milk_supply->milk_amount;

                    $milk_arr = array('mobile'=>$mobile_no, 'milk_quantity'=>$milk_quantity);
                    array_push($milk_data_arr,$milk_arr);
                }

                foreach ($milk_data_arr as $milk_arr ){
                    $mobile_no_ = $milk_arr['mobile'];
                    $milk_quantity_ = $milk_arr['milk_quantity'];
                    if ($role == 1 || $role == 2 || $role == 3) {
                        foreach ($rushere_farmer_nos as $v_set){
                            if ($v_set['mobile'] == $mobile_no_) {
                                $milk_arr['branch'] =  $v_set['branch'];
                                array_push($brh_mlk,  $milk_arr);
                            }
                        }
                    }
                }

                foreach ($brh_mlk as $values){
                    $bran_mlk_spd->{$values['branch']} += (int) $values['milk_quantity'];
                }

                foreach ($milk_periodic_data as $milk_supply)
                {
                    $account = $milk_supply->account_no;
                    $mobile_no = substr($account, 6);
                    $milk_quantity = $milk_supply->milk_amount;

                    foreach ($rushere_farmer_nos as $client_farmers) {
                        $f_name = $client_farmers['name'];
                        $f_phone = $client_farmers['mobile'];
                        if ($f_phone == $mobile_no) {
                            $f_data = array("name"=>$f_name, "milk_qnty"=>$milk_quantity);
                            array_push($data, $f_data);
                        }
                    }
                }

//                $production_header = $util_obj->captalizeEachWord($production_header);

                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "Milk Supplied Per Branch From " . $date_from." To ". $date_to.'"",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "No." . '",';
                $header .= '"' . "Branch" . '",';
                $header .= '"' . "Milk (Ltrs)" . '",';

                $header .= $newline;

                $body .= $header;

                if(sizeof($rows) > 0){
                    $i = 0;
                    foreach($bran_mlk_spd as $fdata){
                        $district = $branches[$i];
                        $frequency = $fdata;

                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                        $body .= $newline;

                        $i++;
                    }
                    $body .= '"' . $nextcell . '",';
                    $body .= '"' . $nextcell . '",';

                    echo $body;
                }

            }
            else {
                ///////////////////////////////////////districts
                if ($_POST['district'] == "all") {
                    $table = "dataset_" . $_POST['id'];
                    $rows = array();

                    if ($_POST['production'] == "all") {

                        if ($_POST['gender'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM sacco_branch_name) as sacco, 
  COUNT(sacco_branch_name)  as frequency ", " 1 GROUP BY sacco ORDER BY frequency Desc ");

                        } else {
                            $gender = $_POST['gender'];
                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM sacco_branch_name) as sacco, 
  COUNT(sacco_branch_name)  as frequency ", " lower(biodata_gender) = '$gender' GROUP BY sacco ORDER BY frequency Desc ");

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
                                        $production_header = str_replace("general_questions", "", $column) . "(no)";
                                        if ($_POST['gender'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter  lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no'  $va_sql  GROUP BY district ORDER BY frequency Desc ");


                                        }
                                    }

                                } else {

                                    $p_id = str_replace("generalyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];

                                    $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc ");


                                    }
                                }

                            } else {

                                $p_id = str_replace("productionno", "", $string);
                                $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $enterprise = $row[0]['enterprise'];

                                $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";

                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'no' $va_sql GROUP BY district ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc");

                                }
                            }

                        } else {

                            $p_id = str_replace("productionyes", "", $string);

                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            $enterprise = $row[0]['enterprise'];

                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                            if ($_POST['gender'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc ");


                            }
                        }


                    }

                    $production_header = $util_obj->captalizeEachWord($production_header);

                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';

                    $header .= '"' . "DATASET NAME :" . $dataset . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "DISTRICT :" . $district . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "SUB-COUNTY :" . $county . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "PARISH :" . $parish . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "VILLAGE :" . $village . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "GENDER :" . $gender . '",';
                    $header .= $newline;
                    $header .= $newline;

                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "Number of farmers per Branch" . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "No." . '",';
                    $header .= '"' . "Branch" . '",';
                    $header .= '"' . "Frequency" . '",';
                    $header .= '"' . "%(ge)" . '",';

                    $header .= $newline;

                    $body .= $header;
                    if (sizeof($rows) > 0) {
                        $total = 0;
                        for ($i = 0; $i < sizeof($rows); $i++) {
                            $total = $total + $rows[$i]['frequency'];
                        }

                        $i = 1;
                        $data = array();
                        foreach ($rows as $row) {

                            $district = $util_obj->captalizeEachWord($row['sacco']);
                            $frequency = $row['frequency'];

                            $x = array();
                            $x['sacco'] = $district;
                            $x['freq'] = $frequency;

                            if (sizeof($data) < 1) {
                                array_push($data, $x);
                            } else {

                                $i = 0;
                                foreach ($data as $info) {

                                    if (in_array($district, $info)) {
                                        if (sizeof($data) == 1) {
                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                        } else
                                            if (sizeof($data) > 1) {
                                                $data[$i]["freq"] = $info["freq"] + $frequency;

                                            }

                                        $i++;
                                    } else {

                                        $apparent_size = (sizeof($data) - 1);
                                        if ($apparent_size == $i) {
                                            array_push($data, $x);
                                        }

                                        $i++;

                                    }

                                }
                            }

                        }

                        $i = 1;
                        foreach ($data as $final) {
                            $district = $final['sacco'];
                            $frequency = $final['freq'];
                            $percentage = ($frequency / $total) * 100;
                            $percentage = round($percentage, 2);
                            $body .= '"' . $nextcell . '",';

                            $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                            $body .= $newline;

                            $i++;

                        }

                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                        echo $body;

                    }

                } else///////////////////////////////////////////subcounties
                    if ($_POST['country'] == "all") {

                        $table = "dataset_" . $_POST['id'];
                        $district = $_POST['district'];
                        $rows = array();
                        if ($_POST['production'] == "all") {

                            if ($_POST['gender'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


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

                                            $production_header = str_replace("general_questions", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("generalyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                        }
                                    }


                                } else {
                                    $p_id = str_replace("productionno", "", $string);

                                    //
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    $enterprise = $row[0]['enterprise'];

                                    $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                    }
                                }

                            } else {
                                $p_id = str_replace("productionyes", "", $string);

                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $enterprise = $row[0]['enterprise'];

                                $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                }

                            }


                        }

                        $production_header = $util_obj->captalizeEachWord($production_header);
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';

                        $header .= '"' . "DATASET NAME :" . $dataset . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "DISTRICT :" . $district . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "SUB-COUNTY :" . $county . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PARISH :" . $parish . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "VILLAGE :" . $village . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "GENDER :" . $gender . '",';
                        $header .= $newline;
                        $header .= $newline;

                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "Number of farmers per Subcounty" . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "No." . '",';
                        $header .= '"' . "Sub-county" . '",';
                        $header .= '"' . "Frequency" . '",';
                        $header .= '"' . "%(ge)" . '",';

                        $header .= $newline;

                        $body .= $header;

                        if (sizeof($rows) > 0) {
                            $total = 0;
                            for ($i = 0; $i < sizeof($rows); $i++) {
                                $total = $total + $rows[$i]['frequency'];
                            }

                            $i = 1;
                            $data = array();
                            foreach ($rows as $row) {


                                $subcounty = $util_obj->captalizeEachWord($row['subcounty']);
                                $frequency = $row['frequency'];

                                $x = array();
                                $x['subcounty'] = $subcounty;
                                $x['freq'] = $frequency;
                                if (sizeof($data) < 1) {
                                    array_push($data, $x);
                                } else {

                                    $i = 0;
                                    foreach ($data as $info) {

                                        if (in_array($subcounty, $info)) {
                                            if (sizeof($data) == 1) {
                                                $data[$i]["freq"] = $info["freq"] + $frequency;

                                            } else
                                                if (sizeof($data) > 1) {
                                                    $data[$i]["freq"] = $info["freq"] + $frequency;

                                                }

                                            $i++;
                                        } else {

                                            $apparent_size = (sizeof($data) - 1);
                                            if ($apparent_size == $i) {
                                                array_push($data, $x);
                                            }

                                            $i++;

                                        }

                                    }
                                }

                            }

                            $i = 1;
                            foreach ($data as $final) {
                                $subcounty = $final['subcounty'];
                                $frequency = $final['freq'];
                                $percentage = ($frequency / $total) * 100;
                                $percentage = round($percentage, 2);
                                $body .= '"' . $nextcell . '",';

                                $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($subcounty) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                $body .= $newline;

                                $i++;

                            }


                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                            echo $body;

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
  COUNT(biodata_farmer_location_farmer_parish)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
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
                                                $production_header = str_replace("general_questions", "", $column) . "(No)";
                                                if ($_POST['gender'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                                } else {
                                                    $gender = $_POST['gender'];

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                                }
                                            }


                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            $production_header = str_replace("general_questions", "", $column) . "(Yes)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $enterprise = $row[0]['enterprise'];

                                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY  parish ORDER BY frequency Desc ");

                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    $enterprise = $row[0]['enterprise'];

                                    $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                                    }


                                }
                            }


                            $production_header = $util_obj->captalizeEachWord($production_header);
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';

                            $header .= '"' . "DATASET NAME :" . $dataset . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "DISTRICT :" . $district . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "SUB-COUNTY :" . $county . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "PARISH :" . $parish . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "VILLAGE :" . $village . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "GENDER :" . $gender . '",';
                            $header .= $newline;
                            $header .= $newline;

                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "Number of farmers per Parish" . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "No." . '",';
                            $header .= '"' . "Parish" . '",';
                            $header .= '"' . "Frequency" . '",';
                            $header .= '"' . "%(ge)" . '",';

                            $header .= $newline;

                            $body .= $header;

                            if (sizeof($rows) > 0) {
                                $total = 0;
                                for ($i = 0; $i < sizeof($rows); $i++) {
                                    $total = $total + $rows[$i]['frequency'];
                                }

                                $i = 1;
                                $data = array();
                                foreach ($rows as $row) {

                                    $parish = $util_obj->captalizeEachWord($row['parish']);
                                    $frequency = $row['frequency'];

                                    $x = array();
                                    $x['parish'] = $parish;
                                    $x['freq'] = $frequency;
                                    if (sizeof($data) < 1) {
                                        array_push($data, $x);
                                    } else {

                                        $i = 0;
                                        foreach ($data as $info) {

                                            if (in_array($parish, $info)) {
                                                if (sizeof($data) == 1) {
                                                    $data[$i]["freq"] = $info["freq"] + $frequency;

                                                } else
                                                    if (sizeof($data) > 1) {
                                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                                    }

                                                $i++;
                                            } else {

                                                $apparent_size = (sizeof($data) - 1);
                                                if ($apparent_size == $i) {
                                                    array_push($data, $x);
                                                }

                                                $i++;

                                            }
                                        }
                                    }

                                }


                                $i = 1;
                                foreach ($data as $final) {
                                    $parish = $final['parish'];
                                    $frequency = $final['freq'];
                                    $percentage = ($frequency / $total) * 100;
                                    $percentage = round($percentage, 2);
                                    $body .= '"' . $nextcell . '",';

                                    $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                    $body .= '"' . $util_obj->escape_csv_value($parish) . '",';
                                    $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                    $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                    $body .= $newline;

                                    $i++;

                                }

                                $body .= '"' . $nextcell . '",';
                                $body .= '"' . $nextcell . '",';
                                $body .= '"' . $nextcell . '",';
                                $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                                echo $body;
                            }

                        } else

///////////////////////////////////////////village

                            if ($_POST['village'] == "all") {

                                $table = "dataset_" . $_POST['id'];
                                $parish = $_POST['parish'];
                                $subcounty = $_POST['country'];
                                $rows = array();
                                if ($_POST['production'] == "all") {
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

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
                                                    $production_header = str_replace("general_questions", "", $column) . "(No)";
                                                    if ($_POST['gender'] == "all") {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc ");

                                                    } else {
                                                        $gender = $_POST['gender'];

                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                                    }

                                                }
                                            } else {
                                                $p_id = str_replace("generalyes", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                $production_header = str_replace("general_questions", "", $column) . "(Yes)";
                                                if ($_POST['gender'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                                } else {
                                                    $gender = $_POST['gender'];

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                                }

                                            }

                                        } else {
                                            $p_id = str_replace("productionno", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            $enterprise = $row[0]['enterprise'];

                                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY  village  ORDER BY frequency Desc ");

                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("productionyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $enterprise = $row[0]['enterprise'];

                                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                        }


                                    }
                                }

                                $production_header = $util_obj->captalizeEachWord($production_header);
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';

                                $header .= '"' . "DATASET NAME :" . $dataset . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "DISTRICT :" . $district . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "SUB-COUNTY :" . $county . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PARISH :" . $parish . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "VILLAGE :" . $village . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "GENDER :" . $gender . '",';
                                $header .= $newline;
                                $header .= $newline;

                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "Number of farmers per Village" . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "No." . '",';
                                $header .= '"' . "Village" . '",';
                                $header .= '"' . "Frequency" . '",';
                                $header .= '"' . "%(ge)" . '",';

                                $header .= $newline;

                                $body .= $header;

                                if (sizeof($rows) > 0) {
                                    $total = 0;
                                    for ($i = 0; $i < sizeof($rows); $i++) {
                                        $total = $total + $rows[$i]['frequency'];
                                    }

                                    $i = 1;
                                    $data = array();
                                    foreach ($rows as $row) {

                                        $village_h = $util_obj->captalizeEachWord($row['village']);
                                        $frequency = $row['frequency'];

                                        $x = array();
                                        $x['village'] = $village_h;
                                        $x['freq'] = $frequency;

                                        if (sizeof($data) < 1) {
                                            array_push($data, $x);
                                        } else {

                                            $i = 0;
                                            foreach ($data as $info) {

                                                if (in_array($village_h, $info)) {
                                                    if (sizeof($data) == 1) {
                                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                                    } else
                                                        if (sizeof($data) > 1) {
                                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                                        }

                                                    $i++;
                                                } else {

                                                    $apparent_size = (sizeof($data) - 1);
                                                    if ($apparent_size == $i) {
                                                        array_push($data, $x);
                                                    }

                                                    $i++;

                                                }

                                            }
                                        }

                                    }

                                    $i = 1;
                                    foreach ($data as $final) {
                                        $village_h = $final['village'];
                                        $frequency = $final['freq'];
                                        $percentage = ($frequency / $total) * 100;
                                        $percentage = round($percentage, 2);
                                        $body .= '"' . $nextcell . '",';

                                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($village_h) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                        $body .= $newline;

                                        $i++;

                                    }

                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                                    echo $body;
                                }


                            }
                            else {

//echo "off";
                                $table = "dataset_" . $_POST['id'];
                                $parish = $_POST['parish'];
                                $village = $_POST['village'];
                                $subcounty = $_POST['country'];
                                $rows = array();
                                if ($_POST['production'] == "all") {
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


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
                                                    $production_header = str_replace("general_questions", "", $column) . "(No)";
                                                    if ($_POST['gender'] == "all") {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                                    } else {
                                                        $gender = $_POST['gender'];

                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                                    }

                                                }
                                            } else {
                                                $p_id = str_replace("generalyes", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                                if ($_POST['gender'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                                } else {
                                                    $gender = $_POST['gender'];

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");


                                                }

                                            }
                                        } else {
                                            $p_id = str_replace("productionno", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            $enterprise = $row[0]['enterprise'];

                                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("productionyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $enterprise = $row[0]['enterprise'];

                                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                        }

                                    }
                                }


                                $production_header = $util_obj->captalizeEachWord($production_header);
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';

                                $header .= '"' . "DATASET NAME :" . $dataset . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "DISTRICT :" . $district . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "SUB-COUNTY :" . $county . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PARISH :" . $parish . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "VILLAGE :" . $village . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "GENDER :" . $gender . '",';
                                $header .= $newline;
                                $header .= $newline;

                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "Number of farmers in $village" . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "No." . '",';
                                $header .= '"' . "Village" . '",';
                                $header .= '"' . "Frequency" . '",';
                                $header .= '"' . "%(ge)" . '",';

                                $header .= $newline;

                                $body .= $header;

                                if (sizeof($rows) > 0) {
                                    $total = 0;
                                    for ($i = 0; $i < sizeof($rows); $i++) {
                                        $total = $total + $rows[$i]['frequency'];
                                    }

                                    $i = 1;
                                    $data = array();
                                    foreach ($rows as $row) {

                                        $x = array();
                                        $village_h = $util_obj->captalizeEachWord($row['village']);;
                                        $frequency = $row['frequency'];
                                        $x['village'] = $village_h;
                                        $x['freq'] = $frequency;

                                        if (sizeof($data) < 1) {
                                            array_push($data, $x);
                                        } else {

                                            $i = 0;
                                            foreach ($data as $info) {

                                                if (in_array($village_h, $info)) {
                                                    if (sizeof($data) == 1) {
                                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                                    } else
                                                        if (sizeof($data) > 1) {
                                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                                        }

                                                    $i++;
                                                } else {

                                                    $apparent_size = (sizeof($data) - 1);
                                                    if ($apparent_size == $i) {
                                                        array_push($data, $x);
                                                    }

                                                    $i++;

                                                }
                                            }
                                        }

                                    }

                                    $i = 1;
                                    foreach ($data as $final) {
                                        $village_h = $final['village'];
                                        $frequency = $final['freq'];
                                        $percentage = ($frequency / $total) * 100;
                                        $percentage = round($percentage, 2);
                                        $body .= '"' . $nextcell . '",';

                                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($village_h) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                        $body .= $newline;

                                        $i++;

                                    }

                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                                    echo $body;

                                }

                            }
            }
        } elseif ($role == 2){
            if(isset($_POST['date_']) && $_POST['date_'] != ""){
                $rows = array();    $data = array();
                $table = "dataset_" . $_POST['id'];
                $date = $_POST['date_'];

                $milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$date&end_date=$date");
                $milk_periodic_data = json_decode($milk_data);

                $rows = $mCrudFunctions->fetch_rows($table, "biodata_phonenumber, biodata_first_name, biodata_last_name", "sacco_branch_name LIKE '$branch'");
                foreach ($rows as $rn) {
                    $name = $rn['biodata_first_name']." ".$rn['biodata_last_name'];
                    $f_nm = array("name" => $name, "mobile" => $rn['biodata_phonenumber']);
                    array_push($rushere_farmer_nos, $f_nm);
                }

                foreach ($milk_periodic_data as $milk_supply)
                {
                    $account = $milk_supply->account_no;
                    $mobile_no = substr($account, 6);
                    $milk_quantity = $milk_supply->milk_amount;

                    foreach ($rushere_farmer_nos as $client_farmers) {
                        $f_name = $client_farmers['name'];
                        $f_phone = $client_farmers['mobile'];
                        if ($f_phone == $mobile_no) {
                            $f_data = array("name"=>$f_name, "milk_qnty"=>$milk_quantity);
                            array_push($data, $f_data);
                        }
                    }
                }

//                $production_header = $util_obj->captalizeEachWord($production_header);

                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "Milk supplied By the Following Farmers on " . $date.'",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "No." . '",';
                $header .= '"' . "Farmer" . '",';
                $header .= '"' . "Milk (Ltrs)" . '",';

                $header .= $newline;

                $body .= $header;

                if(sizeof($rows) > 0){
                    $i = 1;
                    foreach($data as $fdata){
                        $district = $fdata['name'];
                        $frequency = $fdata['milk_qnty'];

                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                        $body .= $newline;

                        $i++;
                    }
                    $body .= '"' . $nextcell . '",';
                    $body .= '"' . $nextcell . '",';

                    echo $body;
                }

            }
            else if(isset($_POST['date_from']) && $_POST['date_from'] != "" && isset($_POST['date_to']) && $_POST['date_to'] != ""){
                $rows = array();    $data = array();
                $table = "dataset_" . $_POST['id'];
                $date_from = $_POST['date_from'];   $date_to = $_POST['date_to'];
                $bran_mlk_spd = new stdClass();
                $branches = array();    $milk_data_arr = array();   $brh_mlk = array();

                $milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$date_from&end_date=$date_to");
                $milk_periodic_data = json_decode($milk_data);

                $rows = $mCrudFunctions->fetch_rows($table, "biodata_phonenumber as mobile, biodata_cooperative_name as branch", "sacco_branch_name LIKE '$branch'");
                foreach ($rows as $rn) {
                    array_push($branches, $rn['branch']);
                    $bran_mlk_spd->{$rn['branch']} = 0;
                    $f_nm = array("branch" => $rn['branch'], "mobile" => $rn['mobile']);
                    array_push($rushere_farmer_nos, $f_nm);
                }
                $branches = array_values(array_unique($branches));

                foreach ($milk_periodic_data as $milk_supply){
                    $account = $milk_supply->account_no;
                    $mobile_no = substr($account, 6);
                    $milk_quantity = $milk_supply->milk_amount;

                    $milk_arr = array('mobile'=>$mobile_no, 'milk_quantity'=>$milk_quantity);
                    array_push($milk_data_arr,$milk_arr);
                }

                foreach ($milk_data_arr as $milk_arr ){
                    $mobile_no_ = $milk_arr['mobile'];
                    $milk_quantity_ = $milk_arr['milk_quantity'];
//                    if ($role == 1 || $role == 2 || $role == 3) {
                        foreach ($rushere_farmer_nos as $v_set){
                            if ($v_set['mobile'] == $mobile_no_) {
                                $milk_arr['branch'] =  $v_set['branch'];
                                array_push($brh_mlk,  $milk_arr);
                            }
                        }
//                    }
                }

                foreach ($brh_mlk as $values){
                    $bran_mlk_spd->{$values['branch']} += (int) $values['milk_quantity'];
                }

                foreach ($milk_periodic_data as $milk_supply)
                {
                    $account = $milk_supply->account_no;
                    $mobile_no = substr($account, 6);
                    $milk_quantity = $milk_supply->milk_amount;

                    foreach ($rushere_farmer_nos as $client_farmers) {
                        $f_name = $client_farmers['name'];
                        $f_phone = $client_farmers['mobile'];
                        if ($f_phone == $mobile_no) {
                            $f_data = array("name"=>$f_name, "milk_qnty"=>$milk_quantity);
                            array_push($data, $f_data);
                        }
                    }
                }

                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "Milk Supplied Per Cooperative From " . $date_from." To ". $date_to.'"",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "No." . '",';
                $header .= '"' . "Cooperative" . '",';
                $header .= '"' . "Milk (Ltrs)" . '",';

                $header .= $newline;

                $body .= $header;

                if(sizeof($rows) > 0){
                    $i = 0;
                    foreach($bran_mlk_spd as $fdata){
                        $district = $branches[$i];
                        $frequency = $fdata;

                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                        $body .= $newline;

                        $i++;
                    }
                    $body .= '"' . $nextcell . '",';
                    $body .= '"' . $nextcell . '",';

                    echo $body;
                }

            }
            else {
                ///////////////////////////////////////districts
                if ($_POST['district'] == "all") {
                    $table = "dataset_" . $_POST['id'];
                    $rows = array();

                    if ($_POST['production'] == "all") {

                        if ($_POST['gender'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter 1 $va_sql  GROUP BY district ORDER BY frequency Desc ");

                        } else {
                            $gender = $_POST['gender'];
                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter lower(biodata_farmer_gender) = '$gender' $va_sql  GROUP BY district ORDER BY frequency Desc ");

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
                                        $production_header = str_replace("general_questions", "", $column) . "(no)";
                                        if ($_POST['gender'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter  lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no'  $va_sql  GROUP BY district ORDER BY frequency Desc ");


                                        }
                                    }

                                } else {

                                    $p_id = str_replace("generalyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];

                                    $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc ");


                                    }
                                }

                            } else {

                                $p_id = str_replace("productionno", "", $string);
                                $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $enterprise = $row[0]['enterprise'];

                                $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";

                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'no' $va_sql GROUP BY district ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc");

                                }
                            }

                        } else {

                            $p_id = str_replace("productionyes", "", $string);

                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            $enterprise = $row[0]['enterprise'];

                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                            if ($_POST['gender'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc ");


                            }
                        }


                    }

                    $production_header = $util_obj->captalizeEachWord($production_header);

                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';

                    $header .= '"' . "DATASET NAME :" . $dataset . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "DISTRICT :" . $district . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "SUB-COUNTY :" . $county . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "PARISH :" . $parish . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "VILLAGE :" . $village . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "GENDER :" . $gender . '",';
                    $header .= $newline;
                    $header .= $newline;

                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "Number of farmers per District" . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "No." . '",';
                    $header .= '"' . "District" . '",';
                    $header .= '"' . "Frequency" . '",';
                    $header .= '"' . "%(ge)" . '",';

                    $header .= $newline;

                    $body .= $header;
                    if (sizeof($rows) > 0) {
                        $total = 0;
                        for ($i = 0; $i < sizeof($rows); $i++) {
                            $total = $total + $rows[$i]['frequency'];
                        }

                        $i = 1;
                        $data = array();
                        foreach ($rows as $row) {

                            $district = $util_obj->captalizeEachWord($row['district']);
                            $frequency = $row['frequency'];

                            $x = array();
                            $x['district'] = $district;
                            $x['freq'] = $frequency;

                            if (sizeof($data) < 1) {
                                array_push($data, $x);
                            } else {

                                $i = 0;
                                foreach ($data as $info) {

                                    if (in_array($district, $info)) {
                                        if (sizeof($data) == 1) {
                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                        } else
                                            if (sizeof($data) > 1) {
                                                $data[$i]["freq"] = $info["freq"] + $frequency;

                                            }

                                        $i++;
                                    } else {

                                        $apparent_size = (sizeof($data) - 1);
                                        if ($apparent_size == $i) {
                                            array_push($data, $x);
                                        }

                                        $i++;

                                    }

                                }
                            }

                        }

                        $i = 1;
                        foreach ($data as $final) {
                            $district = $final['district'];
                            $frequency = $final['freq'];
                            $percentage = ($frequency / $total) * 100;
                            $percentage = round($percentage, 2);
                            $body .= '"' . $nextcell . '",';

                            $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                            $body .= $newline;

                            $i++;

                        }

                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                        echo $body;

                    }
                } else///////////////////////////////////////////subcounties
                    if ($_POST['country'] == "all") {

                        $table = "dataset_" . $_POST['id'];
                        $district = $_POST['district'];
                        $rows = array();
                        if ($_POST['production'] == "all") {

                            if ($_POST['gender'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


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

                                            $production_header = str_replace("general_questions", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("generalyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                        }
                                    }


                                } else {
                                    $p_id = str_replace("productionno", "", $string);

                                    //
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    $enterprise = $row[0]['enterprise'];

                                    $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                    }
                                }

                            } else {
                                $p_id = str_replace("productionyes", "", $string);

                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $enterprise = $row[0]['enterprise'];

                                $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                }

                            }


                        }

                        $production_header = $util_obj->captalizeEachWord($production_header);
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';

                        $header .= '"' . "DATASET NAME :" . $dataset . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "DISTRICT :" . $district . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "SUB-COUNTY :" . $county . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PARISH :" . $parish . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "VILLAGE :" . $village . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "GENDER :" . $gender . '",';
                        $header .= $newline;
                        $header .= $newline;

                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "Number of farmers per Subcounty" . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "No." . '",';
                        $header .= '"' . "Sub-county" . '",';
                        $header .= '"' . "Frequency" . '",';
                        $header .= '"' . "%(ge)" . '",';

                        $header .= $newline;

                        $body .= $header;

                        if (sizeof($rows) > 0) {
                            $total = 0;
                            for ($i = 0; $i < sizeof($rows); $i++) {
                                $total = $total + $rows[$i]['frequency'];
                            }

                            $i = 1;
                            $data = array();
                            foreach ($rows as $row) {


                                $subcounty = $util_obj->captalizeEachWord($row['subcounty']);
                                $frequency = $row['frequency'];

                                $x = array();
                                $x['subcounty'] = $subcounty;
                                $x['freq'] = $frequency;
                                if (sizeof($data) < 1) {
                                    array_push($data, $x);
                                } else {

                                    $i = 0;
                                    foreach ($data as $info) {

                                        if (in_array($subcounty, $info)) {
                                            if (sizeof($data) == 1) {
                                                $data[$i]["freq"] = $info["freq"] + $frequency;

                                            } else
                                                if (sizeof($data) > 1) {
                                                    $data[$i]["freq"] = $info["freq"] + $frequency;

                                                }

                                            $i++;
                                        } else {

                                            $apparent_size = (sizeof($data) - 1);
                                            if ($apparent_size == $i) {
                                                array_push($data, $x);
                                            }

                                            $i++;

                                        }

                                    }
                                }

                            }

                            $i = 1;
                            foreach ($data as $final) {
                                $subcounty = $final['subcounty'];
                                $frequency = $final['freq'];
                                $percentage = ($frequency / $total) * 100;
                                $percentage = round($percentage, 2);
                                $body .= '"' . $nextcell . '",';

                                $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($subcounty) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                $body .= $newline;

                                $i++;

                            }


                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                            echo $body;

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
  COUNT(biodata_farmer_location_farmer_parish)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
  COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

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
                                                $production_header = str_replace("general_questions", "", $column) . "(No)";
                                                if ($_POST['gender'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                                } else {
                                                    $gender = $_POST['gender'];

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                                }
                                            }


                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            $production_header = str_replace("general_questions", "", $column) . "(Yes)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $enterprise = $row[0]['enterprise'];

                                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY  parish ORDER BY frequency Desc ");

                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    $enterprise = $row[0]['enterprise'];

                                    $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                                    }


                                }
                            }


                            $production_header = $util_obj->captalizeEachWord($production_header);
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';

                            $header .= '"' . "DATASET NAME :" . $dataset . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "DISTRICT :" . $district . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "SUB-COUNTY :" . $county . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "PARISH :" . $parish . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "VILLAGE :" . $village . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "GENDER :" . $gender . '",';
                            $header .= $newline;
                            $header .= $newline;

                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "Number of farmers per Parish" . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "No." . '",';
                            $header .= '"' . "Parish" . '",';
                            $header .= '"' . "Frequency" . '",';
                            $header .= '"' . "%(ge)" . '",';

                            $header .= $newline;

                            $body .= $header;

                            if (sizeof($rows) > 0) {
                                $total = 0;
                                for ($i = 0; $i < sizeof($rows); $i++) {
                                    $total = $total + $rows[$i]['frequency'];
                                }

                                $i = 1;
                                $data = array();
                                foreach ($rows as $row) {

                                    $parish = $util_obj->captalizeEachWord($row['parish']);
                                    $frequency = $row['frequency'];

                                    $x = array();
                                    $x['parish'] = $parish;
                                    $x['freq'] = $frequency;
                                    if (sizeof($data) < 1) {
                                        array_push($data, $x);
                                    } else {

                                        $i = 0;
                                        foreach ($data as $info) {

                                            if (in_array($parish, $info)) {
                                                if (sizeof($data) == 1) {
                                                    $data[$i]["freq"] = $info["freq"] + $frequency;

                                                } else
                                                    if (sizeof($data) > 1) {
                                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                                    }

                                                $i++;
                                            } else {

                                                $apparent_size = (sizeof($data) - 1);
                                                if ($apparent_size == $i) {
                                                    array_push($data, $x);
                                                }

                                                $i++;

                                            }
                                        }
                                    }

                                }


                                $i = 1;
                                foreach ($data as $final) {
                                    $parish = $final['parish'];
                                    $frequency = $final['freq'];
                                    $percentage = ($frequency / $total) * 100;
                                    $percentage = round($percentage, 2);
                                    $body .= '"' . $nextcell . '",';

                                    $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                    $body .= '"' . $util_obj->escape_csv_value($parish) . '",';
                                    $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                    $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                    $body .= $newline;

                                    $i++;

                                }

                                $body .= '"' . $nextcell . '",';
                                $body .= '"' . $nextcell . '",';
                                $body .= '"' . $nextcell . '",';
                                $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                                echo $body;
                            }

                        } else

///////////////////////////////////////////village

                            if ($_POST['village'] == "all") {

                                $table = "dataset_" . $_POST['id'];
                                $parish = $_POST['parish'];
                                $subcounty = $_POST['country'];
                                $rows = array();
                                if ($_POST['production'] == "all") {
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

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
                                                    $production_header = str_replace("general_questions", "", $column) . "(No)";
                                                    if ($_POST['gender'] == "all") {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc ");

                                                    } else {
                                                        $gender = $_POST['gender'];

                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                                    }

                                                }
                                            } else {
                                                $p_id = str_replace("generalyes", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                $production_header = str_replace("general_questions", "", $column) . "(Yes)";
                                                if ($_POST['gender'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                                } else {
                                                    $gender = $_POST['gender'];

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                                }

                                            }

                                        } else {
                                            $p_id = str_replace("productionno", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            $enterprise = $row[0]['enterprise'];

                                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY  village  ORDER BY frequency Desc ");

                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("productionyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $enterprise = $row[0]['enterprise'];

                                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                        }


                                    }
                                }


                                $production_header = $util_obj->captalizeEachWord($production_header);
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';

                                $header .= '"' . "DATASET NAME :" . $dataset . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "DISTRICT :" . $district . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "SUB-COUNTY :" . $county . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PARISH :" . $parish . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "VILLAGE :" . $village . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "GENDER :" . $gender . '",';
                                $header .= $newline;
                                $header .= $newline;

                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "Number of farmers per Village" . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "No." . '",';
                                $header .= '"' . "Village" . '",';
                                $header .= '"' . "Frequency" . '",';
                                $header .= '"' . "%(ge)" . '",';

                                $header .= $newline;

                                $body .= $header;

                                if (sizeof($rows) > 0) {
                                    $total = 0;
                                    for ($i = 0; $i < sizeof($rows); $i++) {
                                        $total = $total + $rows[$i]['frequency'];
                                    }

                                    $i = 1;
                                    $data = array();
                                    foreach ($rows as $row) {

                                        $village_h = $util_obj->captalizeEachWord($row['village']);
                                        $frequency = $row['frequency'];

                                        $x = array();
                                        $x['village'] = $village_h;
                                        $x['freq'] = $frequency;

                                        if (sizeof($data) < 1) {
                                            array_push($data, $x);
                                        } else {

                                            $i = 0;
                                            foreach ($data as $info) {

                                                if (in_array($village_h, $info)) {
                                                    if (sizeof($data) == 1) {
                                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                                    } else
                                                        if (sizeof($data) > 1) {
                                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                                        }

                                                    $i++;
                                                } else {

                                                    $apparent_size = (sizeof($data) - 1);
                                                    if ($apparent_size == $i) {
                                                        array_push($data, $x);
                                                    }

                                                    $i++;

                                                }

                                            }
                                        }

                                    }

                                    $i = 1;
                                    foreach ($data as $final) {
                                        $village_h = $final['village'];
                                        $frequency = $final['freq'];
                                        $percentage = ($frequency / $total) * 100;
                                        $percentage = round($percentage, 2);
                                        $body .= '"' . $nextcell . '",';

                                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($village_h) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                        $body .= $newline;

                                        $i++;

                                    }

                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                                    echo $body;
                                }


                            }
                            else {

//echo "off";
                                $table = "dataset_" . $_POST['id'];
                                $parish = $_POST['parish'];
                                $village = $_POST['village'];
                                $subcounty = $_POST['country'];
                                $rows = array();
                                if ($_POST['production'] == "all") {
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


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
                                                    $production_header = str_replace("general_questions", "", $column) . "(No)";
                                                    if ($_POST['gender'] == "all") {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                                    } else {
                                                        $gender = $_POST['gender'];

                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                                    }

                                                }
                                            } else {
                                                $p_id = str_replace("generalyes", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                                if ($_POST['gender'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                                } else {
                                                    $gender = $_POST['gender'];

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");


                                                }

                                            }
                                        } else {
                                            $p_id = str_replace("productionno", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            $enterprise = $row[0]['enterprise'];

                                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("productionyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $enterprise = $row[0]['enterprise'];

                                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                        }

                                    }
                                }


                                $production_header = $util_obj->captalizeEachWord($production_header);
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';

                                $header .= '"' . "DATASET NAME :" . $dataset . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "DISTRICT :" . $district . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "SUB-COUNTY :" . $county . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PARISH :" . $parish . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "VILLAGE :" . $village . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "GENDER :" . $gender . '",';
                                $header .= $newline;
                                $header .= $newline;

                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "Number of farmers in $village" . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "No." . '",';
                                $header .= '"' . "Village" . '",';
                                $header .= '"' . "Frequency" . '",';
                                $header .= '"' . "%(ge)" . '",';

                                $header .= $newline;

                                $body .= $header;

                                if (sizeof($rows) > 0) {
                                    $total = 0;
                                    for ($i = 0; $i < sizeof($rows); $i++) {
                                        $total = $total + $rows[$i]['frequency'];
                                    }

                                    $i = 1;
                                    $data = array();
                                    foreach ($rows as $row) {

                                        $x = array();
                                        $village_h = $util_obj->captalizeEachWord($row['village']);;
                                        $frequency = $row['frequency'];
                                        $x['village'] = $village_h;
                                        $x['freq'] = $frequency;

                                        if (sizeof($data) < 1) {
                                            array_push($data, $x);
                                        } else {

                                            $i = 0;
                                            foreach ($data as $info) {

                                                if (in_array($village_h, $info)) {
                                                    if (sizeof($data) == 1) {
                                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                                    } else
                                                        if (sizeof($data) > 1) {
                                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                                        }

                                                    $i++;
                                                } else {

                                                    $apparent_size = (sizeof($data) - 1);
                                                    if ($apparent_size == $i) {
                                                        array_push($data, $x);
                                                    }

                                                    $i++;

                                                }
                                            }
                                        }

                                    }

                                    $i = 1;
                                    foreach ($data as $final) {
                                        $village_h = $final['village'];
                                        $frequency = $final['freq'];
                                        $percentage = ($frequency / $total) * 100;
                                        $percentage = round($percentage, 2);
                                        $body .= '"' . $nextcell . '",';

                                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($village_h) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                        $body .= $newline;

                                        $i++;

                                    }

                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                                    echo $body;

                                }

                            }
            }
        } else {
            if(isset($_POST['date_']) && $_POST['date_'] != ""){
                $rows = array();    $data = array();
                $table = "dataset_" . $_POST['id'];
                $date = $_POST['date_'];

                $milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$date&end_date=$date");
                $milk_periodic_data = json_decode($milk_data);

                $rows = $mCrudFunctions->fetch_rows($table, "biodata_phonenumber, biodata_first_name, biodata_last_name", "biodata_cooperative_name LIKE '$branch'");
                foreach ($rows as $rn) {
                    $name = $rn['biodata_first_name']." ".$rn['biodata_last_name'];
                    $f_nm = array("name" => $name, "mobile" => $rn['biodata_phonenumber']);
                    array_push($rushere_farmer_nos, $f_nm);
                }

                foreach ($milk_periodic_data as $milk_supply)
                {
                    $account = $milk_supply->account_no;
                    $mobile_no = substr($account, 6);
                    $milk_quantity = $milk_supply->milk_amount;

                    foreach ($rushere_farmer_nos as $client_farmers) {
                        $f_name = $client_farmers['name'];
                        $f_phone = $client_farmers['mobile'];
                        if ($f_phone == $mobile_no) {
                            $f_data = array("name"=>$f_name, "milk_qnty"=>$milk_quantity);
                            array_push($data, $f_data);
                        }
                    }
                }

                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "Milk supplied By the Following Farmers on " . $date.'",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "No." . '",';
                $header .= '"' . "Farmer" . '",';
                $header .= '"' . "Milk (Ltrs)" . '",';

                $header .= $newline;

                $body .= $header;

                if(sizeof($rows) > 0){
                    $i = 1;
                    foreach($data as $fdata){
                        $district = $fdata['name'];
                        $frequency = $fdata['milk_qnty'];

                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                        $body .= $newline;

                        $i++;
                    }
                    $body .= '"' . $nextcell . '",';
                    $body .= '"' . $nextcell . '",';

                    echo $body;
                }

            }
            else if(isset($_POST['date_from']) && $_POST['date_from'] != "" && isset($_POST['date_to']) && $_POST['date_to'] != ""){
                $rows = array();    $data = array();
                $table = "dataset_" . $_POST['id'];
                $date_from = $_POST['date_from'];   $date_to = $_POST['date_to'];
                $bran_mlk_spd = new stdClass();
                $branches = array();    $milk_data_arr = array();   $brh_mlk = array();

                $milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$date_from&end_date=$date_to");
                $milk_periodic_data = json_decode($milk_data);

                $rows = $mCrudFunctions->fetch_rows($table, "biodata_phonenumber as mobile, TRIM(biodata_farmer_location_farmer_village) AS branch", "biodata_cooperative_name LIKE '$branch'");
                foreach ($rows as $rn) {
                    array_push($branches, $rn['branch']);
                    $bran_mlk_spd->{$rn['branch']} = 0;
                    $f_nm = array("branch" => $rn['branch'], "mobile" => $rn['mobile']);
                    array_push($rushere_farmer_nos, $f_nm);
                }
                $branches = array_values(array_unique($branches));

                foreach ($milk_periodic_data as $milk_supply){
                    $account = $milk_supply->account_no;
                    $mobile_no = substr($account, 6);
                    $milk_quantity = $milk_supply->milk_amount;

                    $milk_arr = array('mobile'=>$mobile_no, 'milk_quantity'=>$milk_quantity);
                    array_push($milk_data_arr,$milk_arr);
                }

                foreach ($milk_data_arr as $milk_arr ){
                    $mobile_no_ = $milk_arr['mobile'];
                    $milk_quantity_ = $milk_arr['milk_quantity'];
//                    if ($role == 1 || $role == 2 || $role == 3) {
                    foreach ($rushere_farmer_nos as $v_set){
                        if ($v_set['mobile'] == $mobile_no_) {
                            $milk_arr['branch'] =  $v_set['branch'];
                            array_push($brh_mlk,  $milk_arr);
                        }
                    }
//                    }
                }

                foreach ($brh_mlk as $values){
                    $bran_mlk_spd->{$values['branch']} += (int) $values['milk_quantity'];
                }

                foreach ($milk_periodic_data as $milk_supply)
                {
                    $account = $milk_supply->account_no;
                    $mobile_no = substr($account, 6);
                    $milk_quantity = $milk_supply->milk_amount;

                    foreach ($rushere_farmer_nos as $client_farmers) {
                        $f_name = $client_farmers['name'];
                        $f_phone = $client_farmers['mobile'];
                        if ($f_phone == $mobile_no) {
                            $f_data = array("name"=>$f_name, "milk_qnty"=>$milk_quantity);
                            array_push($data, $f_data);
                        }
                    }
                }

                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "Milk Supplied Per Village From " . $date_from." To ". $date_to.'"",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "No." . '",';
                $header .= '"' . "Village" . '",';
                $header .= '"' . "Milk (Ltrs)" . '",';

                $header .= $newline;

                $body .= $header;

                if(sizeof($rows) > 0){
                    $i = 0;
                    foreach($bran_mlk_spd as $fdata){
                        $district = $branches[$i];
                        $frequency = $fdata;

                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                        $body .= $newline;

                        $i++;
                    }
                    $body .= '"' . $nextcell . '",';
                    $body .= '"' . $nextcell . '",';

                    echo $body;
                }

            }
            else {
                ///////////////////////////////////////districts
                if ($_POST['district'] == "all") {
                    $table = "dataset_" . $_POST['id'];
                    $rows = array();

                    if ($_POST['production'] == "all") {

                        if ($_POST['gender'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter 1 $va_sql  GROUP BY district ORDER BY frequency Desc ");

                        } else {
                            $gender = $_POST['gender'];
                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter lower(biodata_farmer_gender) = '$gender' $va_sql  GROUP BY district ORDER BY frequency Desc ");

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
                                        $production_header = str_replace("general_questions", "", $column) . "(no)";
                                        if ($_POST['gender'] == "all") {

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter  lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no'  $va_sql  GROUP BY district ORDER BY frequency Desc ");


                                        }
                                    }

                                } else {

                                    $p_id = str_replace("generalyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];

                                    $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc ");


                                    }
                                }

                            } else {

                                $p_id = str_replace("productionno", "", $string);
                                $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $enterprise = $row[0]['enterprise'];

                                $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";

                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'no' $va_sql GROUP BY district ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc");

                                }
                            }

                        } else {

                            $p_id = str_replace("productionyes", "", $string);

                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            $enterprise = $row[0]['enterprise'];

                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                            if ($_POST['gender'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district,
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc ");


                            }
                        }


                    }

                    $production_header = $util_obj->captalizeEachWord($production_header);

                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';

                    $header .= '"' . "DATASET NAME :" . $dataset . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "DISTRICT :" . $district . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "SUB-COUNTY :" . $county . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "PARISH :" . $parish . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "VILLAGE :" . $village . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "GENDER :" . $gender . '",';
                    $header .= $newline;
                    $header .= $newline;

                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "Number of farmers per District" . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "No." . '",';
                    $header .= '"' . "District" . '",';
                    $header .= '"' . "Frequency" . '",';
                    $header .= '"' . "%(ge)" . '",';

                    $header .= $newline;

                    $body .= $header;
                    if (sizeof($rows) > 0) {
                        $total = 0;
                        for ($i = 0; $i < sizeof($rows); $i++) {
                            $total = $total + $rows[$i]['frequency'];
                        }

                        $i = 1;
                        $data = array();
                        foreach ($rows as $row) {

                            $district = $util_obj->captalizeEachWord($row['district']);
                            $frequency = $row['frequency'];

                            $x = array();
                            $x['district'] = $district;
                            $x['freq'] = $frequency;

                            if (sizeof($data) < 1) {
                                array_push($data, $x);
                            } else {

                                $i = 0;
                                foreach ($data as $info) {

                                    if (in_array($district, $info)) {
                                        if (sizeof($data) == 1) {
                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                        } else
                                            if (sizeof($data) > 1) {
                                                $data[$i]["freq"] = $info["freq"] + $frequency;

                                            }

                                        $i++;
                                    } else {

                                        $apparent_size = (sizeof($data) - 1);
                                        if ($apparent_size == $i) {
                                            array_push($data, $x);
                                        }

                                        $i++;

                                    }

                                }
                            }

                        }

                        $i = 1;
                        foreach ($data as $final) {
                            $district = $final['district'];
                            $frequency = $final['freq'];
                            $percentage = ($frequency / $total) * 100;
                            $percentage = round($percentage, 2);
                            $body .= '"' . $nextcell . '",';

                            $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                            $body .= $newline;

                            $i++;

                        }

                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                        echo $body;

                    }
                } else///////////////////////////////////////////subcounties
                    if ($_POST['country'] == "all") {

                        $table = "dataset_" . $_POST['id'];
                        $district = $_POST['district'];
                        $rows = array();
                        if ($_POST['production'] == "all") {

                            if ($_POST['gender'] == "all") {

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


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

                                            $production_header = str_replace("general_questions", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("generalyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                        }
                                    }


                                } else {
                                    $p_id = str_replace("productionno", "", $string);

                                    //
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    $enterprise = $row[0]['enterprise'];

                                    $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                    }
                                }

                            } else {
                                $p_id = str_replace("productionyes", "", $string);

                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $enterprise = $row[0]['enterprise'];

                                $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty,
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                }

                            }


                        }

                        $production_header = $util_obj->captalizeEachWord($production_header);
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';

                        $header .= '"' . "DATASET NAME :" . $dataset . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "DISTRICT :" . $district . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "SUB-COUNTY :" . $county . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PARISH :" . $parish . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "VILLAGE :" . $village . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "GENDER :" . $gender . '",';
                        $header .= $newline;
                        $header .= $newline;

                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "Number of farmers per Subcounty" . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "No." . '",';
                        $header .= '"' . "Sub-county" . '",';
                        $header .= '"' . "Frequency" . '",';
                        $header .= '"' . "%(ge)" . '",';

                        $header .= $newline;

                        $body .= $header;

                        if (sizeof($rows) > 0) {
                            $total = 0;
                            for ($i = 0; $i < sizeof($rows); $i++) {
                                $total = $total + $rows[$i]['frequency'];
                            }

                            $i = 1;
                            $data = array();
                            foreach ($rows as $row) {


                                $subcounty = $util_obj->captalizeEachWord($row['subcounty']);
                                $frequency = $row['frequency'];

                                $x = array();
                                $x['subcounty'] = $subcounty;
                                $x['freq'] = $frequency;
                                if (sizeof($data) < 1) {
                                    array_push($data, $x);
                                } else {

                                    $i = 0;
                                    foreach ($data as $info) {

                                        if (in_array($subcounty, $info)) {
                                            if (sizeof($data) == 1) {
                                                $data[$i]["freq"] = $info["freq"] + $frequency;

                                            } else
                                                if (sizeof($data) > 1) {
                                                    $data[$i]["freq"] = $info["freq"] + $frequency;

                                                }

                                            $i++;
                                        } else {

                                            $apparent_size = (sizeof($data) - 1);
                                            if ($apparent_size == $i) {
                                                array_push($data, $x);
                                            }

                                            $i++;

                                        }

                                    }
                                }

                            }

                            $i = 1;
                            foreach ($data as $final) {
                                $subcounty = $final['subcounty'];
                                $frequency = $final['freq'];
                                $percentage = ($frequency / $total) * 100;
                                $percentage = round($percentage, 2);
                                $body .= '"' . $nextcell . '",';

                                $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($subcounty) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                $body .= $newline;

                                $i++;

                            }


                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                            echo $body;

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
  COUNT(biodata_farmer_location_farmer_parish)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
  COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

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
                                                $production_header = str_replace("general_questions", "", $column) . "(No)";
                                                if ($_POST['gender'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                                } else {
                                                    $gender = $_POST['gender'];

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                                }
                                            }


                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            $production_header = str_replace("general_questions", "", $column) . "(Yes)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $enterprise = $row[0]['enterprise'];

                                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY  parish ORDER BY frequency Desc ");

                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    $enterprise = $row[0]['enterprise'];

                                    $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish,
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                                    }


                                }
                            }


                            $production_header = $util_obj->captalizeEachWord($production_header);
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';

                            $header .= '"' . "DATASET NAME :" . $dataset . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "DISTRICT :" . $district . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "SUB-COUNTY :" . $county . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "PARISH :" . $parish . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "VILLAGE :" . $village . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "GENDER :" . $gender . '",';
                            $header .= $newline;
                            $header .= $newline;

                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "Number of farmers per Parish" . '",';
                            $header .= $newline;
                            $header .= '"' . $nextcell . '",';
                            $header .= '"' . "No." . '",';
                            $header .= '"' . "Parish" . '",';
                            $header .= '"' . "Frequency" . '",';
                            $header .= '"' . "%(ge)" . '",';

                            $header .= $newline;

                            $body .= $header;

                            if (sizeof($rows) > 0) {
                                $total = 0;
                                for ($i = 0; $i < sizeof($rows); $i++) {
                                    $total = $total + $rows[$i]['frequency'];
                                }

                                $i = 1;
                                $data = array();
                                foreach ($rows as $row) {

                                    $parish = $util_obj->captalizeEachWord($row['parish']);
                                    $frequency = $row['frequency'];

                                    $x = array();
                                    $x['parish'] = $parish;
                                    $x['freq'] = $frequency;
                                    if (sizeof($data) < 1) {
                                        array_push($data, $x);
                                    } else {

                                        $i = 0;
                                        foreach ($data as $info) {

                                            if (in_array($parish, $info)) {
                                                if (sizeof($data) == 1) {
                                                    $data[$i]["freq"] = $info["freq"] + $frequency;

                                                } else
                                                    if (sizeof($data) > 1) {
                                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                                    }

                                                $i++;
                                            } else {

                                                $apparent_size = (sizeof($data) - 1);
                                                if ($apparent_size == $i) {
                                                    array_push($data, $x);
                                                }

                                                $i++;

                                            }
                                        }
                                    }

                                }


                                $i = 1;
                                foreach ($data as $final) {
                                    $parish = $final['parish'];
                                    $frequency = $final['freq'];
                                    $percentage = ($frequency / $total) * 100;
                                    $percentage = round($percentage, 2);
                                    $body .= '"' . $nextcell . '",';

                                    $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                    $body .= '"' . $util_obj->escape_csv_value($parish) . '",';
                                    $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                    $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                    $body .= $newline;

                                    $i++;

                                }

                                $body .= '"' . $nextcell . '",';
                                $body .= '"' . $nextcell . '",';
                                $body .= '"' . $nextcell . '",';
                                $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                                echo $body;
                            }

                        } else

///////////////////////////////////////////village

                            if ($_POST['village'] == "all") {

                                $table = "dataset_" . $_POST['id'];
                                $parish = $_POST['parish'];
                                $subcounty = $_POST['country'];
                                $rows = array();
                                if ($_POST['production'] == "all") {
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

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
                                                    $production_header = str_replace("general_questions", "", $column) . "(No)";
                                                    if ($_POST['gender'] == "all") {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc ");

                                                    } else {
                                                        $gender = $_POST['gender'];

                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                                    }

                                                }
                                            } else {
                                                $p_id = str_replace("generalyes", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                $production_header = str_replace("general_questions", "", $column) . "(Yes)";
                                                if ($_POST['gender'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                                } else {
                                                    $gender = $_POST['gender'];

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                                }

                                            }

                                        } else {
                                            $p_id = str_replace("productionno", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            $enterprise = $row[0]['enterprise'];

                                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY  village  ORDER BY frequency Desc ");

                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("productionyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $enterprise = $row[0]['enterprise'];

                                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                        }


                                    }
                                }


                                $production_header = $util_obj->captalizeEachWord($production_header);
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';

                                $header .= '"' . "DATASET NAME :" . $dataset . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "DISTRICT :" . $district . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "SUB-COUNTY :" . $county . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PARISH :" . $parish . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "VILLAGE :" . $village . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "GENDER :" . $gender . '",';
                                $header .= $newline;
                                $header .= $newline;

                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "Number of farmers per Village" . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "No." . '",';
                                $header .= '"' . "Village" . '",';
                                $header .= '"' . "Frequency" . '",';
                                $header .= '"' . "%(ge)" . '",';

                                $header .= $newline;

                                $body .= $header;

                                if (sizeof($rows) > 0) {
                                    $total = 0;
                                    for ($i = 0; $i < sizeof($rows); $i++) {
                                        $total = $total + $rows[$i]['frequency'];
                                    }

                                    $i = 1;
                                    $data = array();
                                    foreach ($rows as $row) {

                                        $village_h = $util_obj->captalizeEachWord($row['village']);
                                        $frequency = $row['frequency'];

                                        $x = array();
                                        $x['village'] = $village_h;
                                        $x['freq'] = $frequency;

                                        if (sizeof($data) < 1) {
                                            array_push($data, $x);
                                        } else {

                                            $i = 0;
                                            foreach ($data as $info) {

                                                if (in_array($village_h, $info)) {
                                                    if (sizeof($data) == 1) {
                                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                                    } else
                                                        if (sizeof($data) > 1) {
                                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                                        }

                                                    $i++;
                                                } else {

                                                    $apparent_size = (sizeof($data) - 1);
                                                    if ($apparent_size == $i) {
                                                        array_push($data, $x);
                                                    }

                                                    $i++;

                                                }

                                            }
                                        }

                                    }

                                    $i = 1;
                                    foreach ($data as $final) {
                                        $village_h = $final['village'];
                                        $frequency = $final['freq'];
                                        $percentage = ($frequency / $total) * 100;
                                        $percentage = round($percentage, 2);
                                        $body .= '"' . $nextcell . '",';

                                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($village_h) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                        $body .= $newline;

                                        $i++;

                                    }

                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                                    echo $body;
                                }


                            }
                            else {

//echo "off";
                                $table = "dataset_" . $_POST['id'];
                                $parish = $_POST['parish'];
                                $village = $_POST['village'];
                                $subcounty = $_POST['country'];
                                $rows = array();
                                if ($_POST['production'] == "all") {
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


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
                                                    $production_header = str_replace("general_questions", "", $column) . "(No)";
                                                    if ($_POST['gender'] == "all") {
                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                                    } else {
                                                        $gender = $_POST['gender'];

                                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                                    }

                                                }
                                            } else {
                                                $p_id = str_replace("generalyes", "", $string);
                                                //
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];
                                                $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                                if ($_POST['gender'] == "all") {
                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                                } else {
                                                    $gender = $_POST['gender'];

                                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");


                                                }

                                            }
                                        } else {
                                            $p_id = str_replace("productionno", "", $string);
                                            //
                                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];
                                            $enterprise = $row[0]['enterprise'];

                                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("productionyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $enterprise = $row[0]['enterprise'];

                                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village,
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                        }

                                    }
                                }


                                $production_header = $util_obj->captalizeEachWord($production_header);
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';

                                $header .= '"' . "DATASET NAME :" . $dataset . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "DISTRICT :" . $district . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "SUB-COUNTY :" . $county . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PARISH :" . $parish . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "VILLAGE :" . $village . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "GENDER :" . $gender . '",';
                                $header .= $newline;
                                $header .= $newline;

                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "Number of farmers in $village" . '",';
                                $header .= $newline;
                                $header .= '"' . $nextcell . '",';
                                $header .= '"' . "No." . '",';
                                $header .= '"' . "Village" . '",';
                                $header .= '"' . "Frequency" . '",';
                                $header .= '"' . "%(ge)" . '",';

                                $header .= $newline;

                                $body .= $header;

                                if (sizeof($rows) > 0) {
                                    $total = 0;
                                    for ($i = 0; $i < sizeof($rows); $i++) {
                                        $total = $total + $rows[$i]['frequency'];
                                    }

                                    $i = 1;
                                    $data = array();
                                    foreach ($rows as $row) {

                                        $x = array();
                                        $village_h = $util_obj->captalizeEachWord($row['village']);;
                                        $frequency = $row['frequency'];
                                        $x['village'] = $village_h;
                                        $x['freq'] = $frequency;

                                        if (sizeof($data) < 1) {
                                            array_push($data, $x);
                                        } else {

                                            $i = 0;
                                            foreach ($data as $info) {

                                                if (in_array($village_h, $info)) {
                                                    if (sizeof($data) == 1) {
                                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                                    } else
                                                        if (sizeof($data) > 1) {
                                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                                        }

                                                    $i++;
                                                } else {

                                                    $apparent_size = (sizeof($data) - 1);
                                                    if ($apparent_size == $i) {
                                                        array_push($data, $x);
                                                    }

                                                    $i++;

                                                }
                                            }
                                        }

                                    }

                                    $i = 1;
                                    foreach ($data as $final) {
                                        $village_h = $final['village'];
                                        $frequency = $final['freq'];
                                        $percentage = ($frequency / $total) * 100;
                                        $percentage = round($percentage, 2);
                                        $body .= '"' . $nextcell . '",';

                                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($village_h) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                        $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                        $body .= $newline;

                                        $i++;

                                    }

                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . $nextcell . '",';
                                    $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                                    echo $body;

                                }

                            }
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
                    $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter 1 $va_sql  GROUP BY district ORDER BY frequency Desc ");

                } else {
                    $gender = $_POST['gender'];
                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter lower(biodata_farmer_gender) = '$gender' $va_sql  GROUP BY district ORDER BY frequency Desc ");

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
                                $production_header = str_replace("general_questions", "", $column) . "(no)";
                                if ($_POST['gender'] == "all") {

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", " $ageFilter  lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no'  $va_sql  GROUP BY district ORDER BY frequency Desc ");


                                }
                            }

                        } else {

                            $p_id = str_replace("generalyes", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];

                            $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                            if ($_POST['gender'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' $va_sql  GROUP BY district  ORDER BY frequency Desc ");


                            }
                        }

                    } else {

                        $p_id = str_replace("productionno", "", $string);
                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        $enterprise = $row[0]['enterprise'];

                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";

                        if ($_POST['gender'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'no' $va_sql GROUP BY district ORDER BY frequency Desc ");

                        } else {
                            $gender = $_POST['gender'];

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'no' $va_sql  GROUP BY district  ORDER BY frequency Desc");

                        }
                    }

                } else {

                    $p_id = str_replace("productionyes", "", $string);

                    //
                    $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                    $column = $row[0]['columns'];
                    $enterprise = $row[0]['enterprise'];

                    $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                    if ($_POST['gender'] == "all") {

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  " . $column . " LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc");

                    } else {
                        $gender = $_POST['gender'];

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) as district, 
  COUNT(biodata_farmer_location_farmer_district)  as frequency ", "$ageFilter  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' $va_sql GROUP BY district  ORDER BY frequency Desc ");


                    }
                }


            }

            $production_header = $util_obj->captalizeEachWord($production_header);

            $header .= $newline;
            $header .= '"' . $nextcell . '",';
            $header .= '"' . $nextcell . '",';

            $header .= '"' . "DATASET NAME :" . $dataset . '",';
            $header .= $newline;
            $header .= '"' . $nextcell . '",';
            $header .= '"' . $nextcell . '",';
            $header .= '"' . "DISTRICT :" . $district . '",';
            $header .= $newline;
            $header .= '"' . $nextcell . '",';
            $header .= '"' . $nextcell . '",';
            $header .= '"' . "SUB-COUNTY :" . $county . '",';
            $header .= $newline;
            $header .= '"' . $nextcell . '",';
            $header .= '"' . $nextcell . '",';
            $header .= '"' . "PARISH :" . $parish . '",';
            $header .= $newline;
            $header .= '"' . $nextcell . '",';
            $header .= '"' . $nextcell . '",';
            $header .= '"' . "VILLAGE :" . $village . '",';
            $header .= $newline;
            $header .= '"' . $nextcell . '",';
            $header .= '"' . $nextcell . '",';
            $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
            $header .= $newline;
            $header .= '"' . $nextcell . '",';
            $header .= '"' . $nextcell . '",';
            $header .= '"' . "GENDER :" . $gender . '",';
            $header .= $newline;
            $header .= $newline;

            $header .= '"' . $nextcell . '",';
            $header .= '"' . $nextcell . '",';
            $header .= '"' . "Number of farmers per District" . '",';
            $header .= $newline;
            $header .= '"' . $nextcell . '",';
            $header .= '"' . "No." . '",';
            $header .= '"' . "District" . '",';
            $header .= '"' . "Frequency" . '",';
            $header .= '"' . "%(ge)" . '",';

            $header .= $newline;

            $body .= $header;
            if (sizeof($rows) > 0) {
                $total = 0;
                for ($i = 0; $i < sizeof($rows); $i++) {
                    $total = $total + $rows[$i]['frequency'];
                }

                $i = 1;
                $data = array();
                foreach ($rows as $row) {

                    $district = $util_obj->captalizeEachWord($row['district']);
                    $frequency = $row['frequency'];

                    $x = array();
                    $x['district'] = $district;
                    $x['freq'] = $frequency;

                    if (sizeof($data) < 1) {
                        array_push($data, $x);
                    } else {

                        $i = 0;
                        foreach ($data as $info) {

                            if (in_array($district, $info)) {
                                if (sizeof($data) == 1) {
                                    $data[$i]["freq"] = $info["freq"] + $frequency;

                                } else
                                    if (sizeof($data) > 1) {
                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                    }

                                $i++;
                            } else {

                                $apparent_size = (sizeof($data) - 1);
                                if ($apparent_size == $i) {
                                    array_push($data, $x);
                                }

                                $i++;

                            }

                        }
                    }

                }

                $i = 1;
                foreach ($data as $final) {
                    $district = $final['district'];
                    $frequency = $final['freq'];
                    $percentage = ($frequency / $total) * 100;
                    $percentage = round($percentage, 2);
                    $body .= '"' . $nextcell . '",';

                    $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                    $body .= '"' . $util_obj->escape_csv_value($district) . '",';
                    $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                    $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                    $body .= $newline;

                    $i++;

                }

                $body .= '"' . $nextcell . '",';
                $body .= '"' . $nextcell . '",';
                $body .= '"' . $nextcell . '",';
                $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                echo $body;

            }
        } else///////////////////////////////////////////subcounties
            if ($_POST['country'] == "all") {

                $table = "dataset_" . $_POST['id'];
                $district = $_POST['district'];
                $rows = array();
                if ($_POST['production'] == "all") {

                    if ($_POST['gender'] == "all") {

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                    } else {
                        $gender = $_POST['gender'];

                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


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

                                    $production_header = str_replace("general_questions", "", $column) . "(No)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "   lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");


                                    }

                                }
                            } else {
                                $p_id = str_replace("generalyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                                }
                            }


                        } else {
                            $p_id = str_replace("productionno", "", $string);

                            //
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            $enterprise = $row[0]['enterprise'];

                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                            if ($_POST['gender'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", "  lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                            }
                        }

                    } else {
                        $p_id = str_replace("productionyes", "", $string);

                        //
                        $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                        $column = $row[0]['columns'];
                        $enterprise = $row[0]['enterprise'];

                        $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                        if ($_POST['gender'] == "all") {
                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                        } else {
                            $gender = $_POST['gender'];

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) as subcounty, 
  COUNT(biodata_farmer_location_farmer_subcounty)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' GROUP BY subcounty ORDER BY frequency Desc ");

                        }

                    }


                }

                $production_header = $util_obj->captalizeEachWord($production_header);
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';

                $header .= '"' . "DATASET NAME :" . $dataset . '",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "DISTRICT :" . $district . '",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "SUB-COUNTY :" . $county . '",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "PARISH :" . $parish . '",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "VILLAGE :" . $village . '",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "GENDER :" . $gender . '",';
                $header .= $newline;
                $header .= $newline;

                $header .= '"' . $nextcell . '",';
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "Number of farmers per Subcounty" . '",';
                $header .= $newline;
                $header .= '"' . $nextcell . '",';
                $header .= '"' . "No." . '",';
                $header .= '"' . "Sub-county" . '",';
                $header .= '"' . "Frequency" . '",';
                $header .= '"' . "%(ge)" . '",';

                $header .= $newline;

                $body .= $header;

                if (sizeof($rows) > 0) {
                    $total = 0;
                    for ($i = 0; $i < sizeof($rows); $i++) {
                        $total = $total + $rows[$i]['frequency'];
                    }

                    $i = 1;
                    $data = array();
                    foreach ($rows as $row) {


                        $subcounty = $util_obj->captalizeEachWord($row['subcounty']);
                        $frequency = $row['frequency'];

                        $x = array();
                        $x['subcounty'] = $subcounty;
                        $x['freq'] = $frequency;
                        if (sizeof($data) < 1) {
                            array_push($data, $x);
                        } else {

                            $i = 0;
                            foreach ($data as $info) {

                                if (in_array($subcounty, $info)) {
                                    if (sizeof($data) == 1) {
                                        $data[$i]["freq"] = $info["freq"] + $frequency;

                                    } else
                                        if (sizeof($data) > 1) {
                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                        }

                                    $i++;
                                } else {

                                    $apparent_size = (sizeof($data) - 1);
                                    if ($apparent_size == $i) {
                                        array_push($data, $x);
                                    }

                                    $i++;

                                }

                            }
                        }

                    }

                    $i = 1;
                    foreach ($data as $final) {
                        $subcounty = $final['subcounty'];
                        $frequency = $final['freq'];
                        $percentage = ($frequency / $total) * 100;
                        $percentage = round($percentage, 2);
                        $body .= '"' . $nextcell . '",';

                        $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($subcounty) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                        $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                        $body .= $newline;

                        $i++;

                    }


                    $body .= '"' . $nextcell . '",';
                    $body .= '"' . $nextcell . '",';
                    $body .= '"' . $nextcell . '",';
                    $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                    echo $body;

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
  COUNT(biodata_farmer_location_farmer_parish)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                        } else {
                            $gender = $_POST['gender'];

                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
  COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

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
                                        $production_header = str_replace("general_questions", "", $column) . "(No)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                        }
                                    }


                                } else {
                                    $p_id = str_replace("generalyes", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    $production_header = str_replace("general_questions", "", $column) . "(Yes)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc  ");

                                    }

                                }
                            } else {
                                $p_id = str_replace("productionno", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $enterprise = $row[0]['enterprise'];

                                $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY  parish ORDER BY frequency Desc ");

                                }

                            }
                        } else {
                            $p_id = str_replace("productionyes", "", $string);
                            //
                            $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            $enterprise = $row[0]['enterprise'];

                            $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                            if ($_POST['gender'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish ORDER BY frequency Desc ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) as parish, 
   COUNT(biodata_farmer_location_farmer_parish)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' GROUP BY parish  ORDER BY frequency Desc ");

                            }


                        }
                    }


                    $production_header = $util_obj->captalizeEachWord($production_header);
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';

                    $header .= '"' . "DATASET NAME :" . $dataset . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "DISTRICT :" . $district . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "SUB-COUNTY :" . $county . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "PARISH :" . $parish . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "VILLAGE :" . $village . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "GENDER :" . $gender . '",';
                    $header .= $newline;
                    $header .= $newline;

                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "Number of farmers per Parish" . '",';
                    $header .= $newline;
                    $header .= '"' . $nextcell . '",';
                    $header .= '"' . "No." . '",';
                    $header .= '"' . "Parish" . '",';
                    $header .= '"' . "Frequency" . '",';
                    $header .= '"' . "%(ge)" . '",';

                    $header .= $newline;

                    $body .= $header;

                    if (sizeof($rows) > 0) {
                        $total = 0;
                        for ($i = 0; $i < sizeof($rows); $i++) {
                            $total = $total + $rows[$i]['frequency'];
                        }

                        $i = 1;
                        $data = array();
                        foreach ($rows as $row) {

                            $parish = $util_obj->captalizeEachWord($row['parish']);
                            $frequency = $row['frequency'];

                            $x = array();
                            $x['parish'] = $parish;
                            $x['freq'] = $frequency;
                            if (sizeof($data) < 1) {
                                array_push($data, $x);
                            } else {

                                $i = 0;
                                foreach ($data as $info) {

                                    if (in_array($parish, $info)) {
                                        if (sizeof($data) == 1) {
                                            $data[$i]["freq"] = $info["freq"] + $frequency;

                                        } else
                                            if (sizeof($data) > 1) {
                                                $data[$i]["freq"] = $info["freq"] + $frequency;

                                            }

                                        $i++;
                                    } else {

                                        $apparent_size = (sizeof($data) - 1);
                                        if ($apparent_size == $i) {
                                            array_push($data, $x);
                                        }

                                        $i++;

                                    }
                                }
                            }

                        }


                        $i = 1;
                        foreach ($data as $final) {
                            $parish = $final['parish'];
                            $frequency = $final['freq'];
                            $percentage = ($frequency / $total) * 100;
                            $percentage = round($percentage, 2);
                            $body .= '"' . $nextcell . '",';

                            $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($parish) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                            $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                            $body .= $newline;

                            $i++;

                        }

                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . $nextcell . '",';
                        $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                        echo $body;
                    }

                } else

///////////////////////////////////////////village

                    if ($_POST['village'] == "all") {

                        $table = "dataset_" . $_POST['id'];
                        $parish = $_POST['parish'];
                        $subcounty = $_POST['country'];
                        $rows = array();
                        if ($_POST['production'] == "all") {
                            if ($_POST['gender'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND   TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");

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
                                            $production_header = str_replace("general_questions", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("generalyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $production_header = str_replace("general_questions", "", $column) . "(Yes)";
                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                        }

                                    }

                                } else {
                                    $p_id = str_replace("productionno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    $enterprise = $row[0]['enterprise'];

                                    $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'no' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY  village  ORDER BY frequency Desc ");

                                    }

                                }

                            } else {
                                $p_id = str_replace("productionyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $enterprise = $row[0]['enterprise'];

                                $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village ORDER BY frequency Desc  ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . "  LIKE 'Yes' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' GROUP BY village  ORDER BY frequency Desc ");


                                }


                            }
                        }


                        $production_header = $util_obj->captalizeEachWord($production_header);
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';

                        $header .= '"' . "DATASET NAME :" . $dataset . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "DISTRICT :" . $district . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "SUB-COUNTY :" . $county . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PARISH :" . $parish . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "VILLAGE :" . $village . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "GENDER :" . $gender . '",';
                        $header .= $newline;
                        $header .= $newline;

                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "Number of farmers per Village" . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "No." . '",';
                        $header .= '"' . "Village" . '",';
                        $header .= '"' . "Frequency" . '",';
                        $header .= '"' . "%(ge)" . '",';

                        $header .= $newline;

                        $body .= $header;

                        if (sizeof($rows) > 0) {
                            $total = 0;
                            for ($i = 0; $i < sizeof($rows); $i++) {
                                $total = $total + $rows[$i]['frequency'];
                            }

                            $i = 1;
                            $data = array();
                            foreach ($rows as $row) {

                                $village_h = $util_obj->captalizeEachWord($row['village']);
                                $frequency = $row['frequency'];

                                $x = array();
                                $x['village'] = $village_h;
                                $x['freq'] = $frequency;

                                if (sizeof($data) < 1) {
                                    array_push($data, $x);
                                } else {

                                    $i = 0;
                                    foreach ($data as $info) {

                                        if (in_array($village_h, $info)) {
                                            if (sizeof($data) == 1) {
                                                $data[$i]["freq"] = $info["freq"] + $frequency;

                                            } else
                                                if (sizeof($data) > 1) {
                                                    $data[$i]["freq"] = $info["freq"] + $frequency;

                                                }

                                            $i++;
                                        } else {

                                            $apparent_size = (sizeof($data) - 1);
                                            if ($apparent_size == $i) {
                                                array_push($data, $x);
                                            }

                                            $i++;

                                        }

                                    }
                                }

                            }

                            $i = 1;
                            foreach ($data as $final) {
                                $village_h = $final['village'];
                                $frequency = $final['freq'];
                                $percentage = ($frequency / $total) * 100;
                                $percentage = round($percentage, 2);
                                $body .= '"' . $nextcell . '",';

                                $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($village_h) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                $body .= $newline;

                                $i++;

                            }

                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                            echo $body;
                        }


                    }
                    else {

//echo "off";
                        $table = "dataset_" . $_POST['id'];
                        $parish = $_POST['parish'];
                        $village = $_POST['village'];
                        $subcounty = $_POST['country'];
                        $rows = array();
                        if ($_POST['production'] == "all") {
                            if ($_POST['gender'] == "all") {
                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                            } else {
                                $gender = $_POST['gender'];

                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
  COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");


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
                                            $production_header = str_replace("general_questions", "", $column) . "(No)";
                                            if ($_POST['gender'] == "all") {
                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village  ORDER BY frequency Desc ");

                                            } else {
                                                $gender = $_POST['gender'];

                                                $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                            }

                                        }
                                    } else {
                                        $p_id = str_replace("generalyes", "", $string);
                                        //
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];
                                        $production_header = str_replace("general_questions", "", $column) . "(Yes)";

                                        if ($_POST['gender'] == "all") {
                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                        } else {
                                            $gender = $_POST['gender'];

                                            $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");


                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionno", "", $string);
                                    //
                                    $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    $enterprise = $row[0]['enterprise'];

                                    $production_header = str_replace($enterprise . "_production_data", "", $column) . "(No)";
                                    if ($_POST['gender'] == "all") {
                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                    } else {
                                        $gender = $_POST['gender'];

                                        $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'no' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc ");

                                    }

                                }
                            } else {
                                $p_id = str_replace("productionyes", "", $string);
                                //
                                $row = $mCrudFunctions->fetch_rows("production_data", "enterprise,columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                $enterprise = $row[0]['enterprise'];

                                $production_header = str_replace($enterprise . "_production_data", "", $column) . "(Yes)";
                                if ($_POST['gender'] == "all") {
                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", "  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                } else {
                                    $gender = $_POST['gender'];

                                    $rows = $mCrudFunctions->fetch_rows($table, "TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) as village, 
   COUNT(biodata_farmer_location_farmer_village)  as frequency ", " lower(biodata_farmer_gender) = '$gender'  AND  " . $column . " LIKE 'Yes' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' GROUP BY village ORDER BY frequency Desc  ");

                                }

                            }
                        }


                        $production_header = $util_obj->captalizeEachWord($production_header);
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';

                        $header .= '"' . "DATASET NAME :" . $dataset . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "DISTRICT :" . $district . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "SUB-COUNTY :" . $county . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PARISH :" . $parish . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "VILLAGE :" . $village . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "PRODUCTION DATA :" . $production_header . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "GENDER :" . $gender . '",';
                        $header .= $newline;
                        $header .= $newline;

                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "Number of farmers in $village" . '",';
                        $header .= $newline;
                        $header .= '"' . $nextcell . '",';
                        $header .= '"' . "No." . '",';
                        $header .= '"' . "Village" . '",';
                        $header .= '"' . "Frequency" . '",';
                        $header .= '"' . "%(ge)" . '",';

                        $header .= $newline;

                        $body .= $header;

                        if (sizeof($rows) > 0) {
                            $total = 0;
                            for ($i = 0; $i < sizeof($rows); $i++) {
                                $total = $total + $rows[$i]['frequency'];
                            }

                            $i = 1;
                            $data = array();
                            foreach ($rows as $row) {

                                $x = array();
                                $village_h = $util_obj->captalizeEachWord($row['village']);;
                                $frequency = $row['frequency'];
                                $x['village'] = $village_h;
                                $x['freq'] = $frequency;

                                if (sizeof($data) < 1) {
                                    array_push($data, $x);
                                } else {

                                    $i = 0;
                                    foreach ($data as $info) {

                                        if (in_array($village_h, $info)) {
                                            if (sizeof($data) == 1) {
                                                $data[$i]["freq"] = $info["freq"] + $frequency;

                                            } else
                                                if (sizeof($data) > 1) {
                                                    $data[$i]["freq"] = $info["freq"] + $frequency;

                                                }

                                            $i++;
                                        } else {

                                            $apparent_size = (sizeof($data) - 1);
                                            if ($apparent_size == $i) {
                                                array_push($data, $x);
                                            }

                                            $i++;

                                        }
                                    }
                                }

                            }

                            $i = 1;
                            foreach ($data as $final) {
                                $village_h = $final['village'];
                                $frequency = $final['freq'];
                                $percentage = ($frequency / $total) * 100;
                                $percentage = round($percentage, 2);
                                $body .= '"' . $nextcell . '",';

                                $body .= '"' . $util_obj->escape_csv_value($i) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($village_h) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($frequency) . '",';
                                $body .= '"' . $util_obj->escape_csv_value($percentage) . '",';
                                $body .= $newline;

                                $i++;

                            }

                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . $nextcell . '",';
                            $body .= '"' . "Total :" . $util_obj->escape_csv_value($total) . '",';
                            echo $body;

                        }

                    }
    }

}
?>