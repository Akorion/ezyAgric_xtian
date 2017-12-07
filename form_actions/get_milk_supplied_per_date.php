<?php
#includes
session_start();
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/json_models_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";

$mCrudFunctions = new CrudFunctions();
$json_model_obj = new JSONModel();
$util_obj = new Utilties();

switch ($_POST['token']){
    case "specific_date":
        $date = $_POST['date'];
        $client_id = $_SESSION["client_id"];
        $branch = $_SESSION['user_account'];
        $role =  $_SESSION['role'];
        $milk_quantity = 0; $branch_farmer_nos = array();   $cooperative_farmer_nos = array();  $rushere_farmer_nos = array();

        $today = date("Y-m-d", strtotime('today'));
        $milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$date&end_date=$date");
        $milk_periodic_data = json_decode($milk_data);

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmer_object = new stdClass();

        foreach ($rows as $row){
            if ($role == 1) {
                $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber, biodata_first_name, biodata_last_name", 1);
                if ( sizeof($rows_n) != 0) {
                    foreach ($rows_n as $rn) {
                        $name = $rn['biodata_first_name']." ".$rn['biodata_last_name'];
                        $f_nm = array("name" => $name, "mobile" => $rn['biodata_phonenumber']);
                        array_push($rushere_farmer_nos, $f_nm);
                    }
                }
            } elseif ($role == 2) {
                $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber, biodata_first_name, biodata_last_name", "sacco_branch_name LIKE '$branch'");
                if ( sizeof($rows_n) != 0) {
                    foreach ($rows_n as $rn) {
                        $name = $rn['biodata_first_name']." ".$rn['biodata_last_name'] ;
                        $f_nm = array("name" => $name, "mobile" => $rn['biodata_phonenumber']);
                        array_push($branch_farmer_nos, $f_nm);
                    }
                }
            } else {
                $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber, biodata_first_name, biodata_last_name", "biodata_cooperative_name LIKE '$branch'");
                if ( sizeof($rows_n) != 0) {
                    foreach ($rows_n as $rn) {
                        $name = $rn['biodata_first_name']." ".$rn['biodata_last_name'] ;
                        $f_nm = array("name" => $name, "mobile" => $rn['biodata_phonenumber']);
                        array_push($cooperative_farmer_nos, $f_nm);
                    }
                }
            }
        }
//        print_r($rushere_farmer_nos);

        echo "<table class=\"table\"><tr><th>Farmer</th><th>Milk Supplied (Ltrs)</th></tr>";
        foreach ($milk_periodic_data as $milk_supply)
        {
            $account = $milk_supply->account_no;
            $mobile_no = substr($account, 6);
            $milk_quantity = $milk_supply->milk_amount;
            if ($role == 1) {
                foreach ($rushere_farmer_nos as $client_farmers) {
                    $f_name = $client_farmers['name'];
                    $f_phone = $client_farmers['mobile'];
                    if($f_phone == $mobile_no){
                        echo "<tr><td>$f_name</td><td style='align-content: center;'>$milk_quantity</td></tr>";
                    }
                }
            } elseif ($role == 2) {
                foreach ($branch_farmer_nos as $branch_farmers) {
                    $f_name = $branch_farmers['name'];
                    $f_phone = $branch_farmers['mobile'];
                    if($f_phone == $mobile_no){
                        echo "<tr><td>$f_name</td><td style='align-content: center;'>$milk_quantity</td></tr>";
                    }
                }
            } else {
                foreach ($cooperative_farmer_nos as $cooperative_farmers) {
                    $f_name = $cooperative_farmers['name'];
                    $f_phone = $cooperative_farmers['mobile'];
                    if($f_phone == $mobile_no){
                        echo "<tr><td>$f_name</td><td style='align-content: center;'>$milk_quantity</td></tr>";
                    }
                }
            }
        }
        echo "</table>";
        break;

    case "date_range":
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];

        $client_id = $_SESSION["client_id"];
        $branch = $_SESSION['user_account'];
        $role =  $_SESSION['role'];
        $milk_quantity = 0; $branch_farmer_nos = array();   $cooperative_farmer_nos = array();  $rushere_farmer_nos = array();

        $today = date("Y-m-d", strtotime('today'));
        $milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$date_from&end_date=$date_to");
        $milk_periodic_data = json_decode($milk_data);

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmer_object = new stdClass();
        $milk_supplied = 0; $b_arr = array();   $milk = array(); $milk_data_arr = array();
        $brh_mlk = array(); $branches = array();    $b_vals = array();
        $bran_mlk_spd = new stdClass();

        foreach ($rows as $row){
            if ($role == 1) {
                $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber as mobile, sacco_branch_name as branch", "1");
                if ( sizeof($rows_n) != 0) {
                    foreach ($rows_n as $rn) {
                        array_push($branches, $rn['branch']);
                        $bran_mlk_spd->{$rn['branch']} = 0;
                        $f_nm = array("branch" => $rn['branch'], "mobile" => $rn['mobile']);
                        array_push($rushere_farmer_nos, $f_nm);
                    }
                }
            } elseif ($role == 2) {
                $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber as mobile, biodata_cooperative_name as branch", "sacco_branch_name LIKE '$branch'");
                if ( sizeof($rows_n) != 0) {
                    foreach ($rows_n as $rn) {
                        array_push($branches, $rn['branch']);
                        $bran_mlk_spd->{$rn['branch']} = 0;
                        $f_nm = array("branch" => $rn['branch'], "mobile" => $rn['mobile']);
                        array_push($rushere_farmer_nos, $f_nm);
                    }
                }
            } else {
                $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber as mobile, TRIM(biodata_farmer_location_farmer_village) AS branch", "biodata_cooperative_name LIKE '$branch'");
                if ( sizeof($rows_n) != 0) {
                    foreach ($rows_n as $rn) {
                        array_push($branches, $rn['branch']);
                        $bran_mlk_spd->{$rn['branch']} = 0;
                        $f_nm = array("branch" => $rn['branch'], "mobile" => $rn['mobile']);
                        array_push($rushere_farmer_nos, $f_nm);
                    }
                }
            }
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
        foreach ($bran_mlk_spd as $k){
                array_push($b_vals, $k);
        }

        if($role == 1) analyseSacco($branches, $b_vals, "column", "Milk Supplied Per Branch", "Branches");
        elseif ($role == 2) analyseBranch($branches, $b_vals, "column", "Milk Supplied Per Cooperative", "Cooperatives");
        else analyseCooperative($branches, $b_vals, "column", "Milk Supplied Per Village", "Villages");

        break;
}

function analyseSacco($branches, $b_vals, $type, $title, $label_x)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $b_vals);


    $data = $json_model_obj->drawSaccoGraph($branches, $array_int, $type, $title, $label_x);
    $util_obj->deliver_response(200, 1, $data);
}
function analyseBranch($branches, $b_vals, $type, $title, $label_x)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $b_vals);

    $data = $json_model_obj->drawSaccoGraph($branches, $array_int, $type, $title, $label_x);
    $util_obj->deliver_response(200, 1, $data);
}
function analyseCooperative($branches, $b_vals, $type, $title, $label_x)
{
    $json_model_obj = new JSONModel();
    $util_obj = new Utilties();

    //converting a string array into an array of integers
    $array_int = array_map(create_function('$value', 'return (int)$value;'), $b_vals);

    $data = $json_model_obj->drawSaccoGraph($branches, $array_int, $type, $title, $label_x);
    $util_obj->deliver_response(200, 1, $data);
}

?>

