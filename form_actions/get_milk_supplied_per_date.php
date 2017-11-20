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
                    $f_phone = $branch_farmers['mobile_no'];
                    if($f_phone == $mobile_no){
                        echo "<tr><td>$f_name</td><td style='align-content: center;'>$milk_quantity</td></tr>";
                    }
                }
            } else {
                foreach ($cooperative_farmer_nos as $cooperative_farmers) {
                    $f_name = $cooperative_farmers['name'];
                    $f_phone = $cooperative_farmers['mobile_no'];
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
//        $milk_data_arr = (array)$milk_periodic_data;
//        print_r($milk_data_arr);

        $rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");
        $farmer_object = new stdClass();

        foreach ($rows as $row){
            if ($role == 1) {
                $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber as mobile, sacco_branch_name as branch", "1");

                if ( sizeof($rows_n) != 0) {
                    foreach ($rows_n as $rn) {
                        $name = $rn['branch'];
                        $rushere_farmer_nos[$name][] = $rn;
//                        $f_nm = array("branch" => $rn['sacco_branch_name'], "mobile" => $rn['biodata_phonenumber']);
//                        array_push($rushere_farmer_nos, $f_nm);
                    }
                }
            } elseif ($role == 2) {
                $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber, biodata_cooperative_name", "sacco_branch_name LIKE '$branch'");
                if ( sizeof($rows_n) != 0) {
                    foreach ($rows_n as $rn) {
//                        $name =
                        $f_nm = array("cooperative" => $rn['biodata_cooperative_name'], "mobile" => $rn['biodata_phonenumber']);
                        array_push($branch_farmer_nos, $f_nm);
                    }
                }
            } else {
                $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber, TRIM(biodata_farmer_location_farmer_village) AS village", "biodata_cooperative_name LIKE '$branch'");
                if ( sizeof($rows_n) != 0) {
                    foreach ($rows_n as $rn) {
//                        $name = ." ".$rn['biodata_last_name'] ;
                        $f_nm = array("village" => $rn['village'], "mobile" => $rn['biodata_phonenumber']);
                        array_push($cooperative_farmer_nos, $f_nm);
                    }
                }
            }
        }
//        print_r($rushere_farmer_nos['Kampala']);

//        $templevel="";
//        $newkey=0;
//        $grouparr[$templevel]="";
//        foreach ($rushere_farmer_nos as $key => $val) {
//            if ($templevel==$val['branch']){
//                $grouparr[$templevel][$newkey]=$val;
//            } else {
//                $grouparr[$val['branch']][$newkey]=$val;
//            }
//            $newkey++;
//        }
//        print_r($grouparr);

//        $result = array();
//        foreach ($rushere_farmer_nos as $data) {
//            $id = $data['branch'];
////            if (isset($result[$id])) {
//                $result[$id][] = $data;
////            } else {
////                $result[$id] = array($data);
////            }
//        }
//        print_r($result);
//        echo sizeof($result);
//        foreach ($result as $k=>$v){
////            print_r($v);
//            foreach ($v as $v_set){
//                echo $v_set['mobile']."<br>";
//            }
//        }

//        array_multisort()
        $milk_supplied = 0; $b_arr = array();   $milk = array(); $milk_data_arr = array();

//        echo "<table class=\"table\"><tr><th>Farmer</th><th>Milk Supplied (Ltrs)</th></tr>";
        foreach ($milk_periodic_data as $milk_supply){

            $account = $milk_supply->account_no;
            $mobile_no = substr($account, 6);
            $milk_quantity = $milk_supply->milk_amount;

            $milk_arr = array('mobile'=>$mobile_no, 'milk_quantity'=>$milk_quantity);
            array_push($milk_data_arr,$milk_arr);

            if ($role == 1) {
                foreach ($rushere_farmer_nos as $k=>$v){
////            print_r($v);
                    $i = 0;
                    foreach ($v as $v_set){
//                        echo $v_set['mobile']."<br>";
                        if($v_set['mobile'] == $mobile_no){
//                            echo $v_set['mobile']."<br>";
                            $milk_supplied +=  $milk_quantity;
                            array_push($b_arr, $v_set['branch']);
//                            array_push($milk, $milk_supplied);
                            if(sizeof($b_arr) > 1){
                                if($b_arr[$i] != $b_arr[$i-1]){
//                                    array_push($b_arr, $v_set['branch']);
                                    $milk_supplied = $milk_supplied - $milk_quantity;
                                    array_push($milk, $milk_supplied);
                                } elseif ($b_arr[$i] == $b_arr[$i-1]) {
                                    $milk_supplied +=  $milk_quantity;
                                    array_push($milk, $milk_supplied);
                                    array_pop($b_arr);
                                }
                            }

//                            array_push($milk, $milk_supplied);
                        }
                        $i++;
                    }
                }
//                foreach ($rushere_farmer_nos as $client_farmers) {
//                    $b_name = $client_farmers['branch'];
//                    $f_phone = $client_farmers['mobile'];
//                    if($f_phone == $mobile_no){
//                        $milk_supplied +=  $milk_quantity;
//                        array_push($b_arr, $b_name);
////                        array_push($milk, $milk_supplied);
//                    }
//                }
            } elseif ($role == 2) {
                foreach ($branch_farmer_nos as $branch_farmers) {
                    $f_name = $branch_farmers['name'];
                    $f_phone = $branch_farmers['mobile_no'];
                    if($f_phone == $mobile_no){
                        echo "<tr><td>$f_name</td><td style='align-content: center;'>$milk_quantity</td></tr>";
                    }
                }
            } else {
                foreach ($cooperative_farmer_nos as $cooperative_farmers) {
                    $f_name = $cooperative_farmers['name'];
                    $f_phone = $cooperative_farmers['mobile_no'];
                    if($f_phone == $mobile_no){
                        echo "<tr><td>$f_name</td><td style='align-content: center;'>$milk_quantity</td></tr>";
                    }
                }
            }
        }

        print_r($milk_data_arr);
//        print_r($b_arr);    echo "<br>";
//        print_r($milk);
//        echo "</table>";
        break;
}


//function in_object($needle, $haystack) {
//    return in_array($needle, get_object_vars($haystack));
//}

?>

