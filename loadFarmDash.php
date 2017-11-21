<?php include("include/header_client.php");
session_start();
#includes
require_once dirname(__FILE__) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/utility_class.php";

$util_obj = new Utilties();
$db = new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();

$role =  $_SESSION['role'];
$branch = $_SESSION['user_account'];
$client_id = $_SESSION["client_id"];

$rows = $mCrudFunctions->fetch_rows("datasets_tb", "*", "client_id='$client_id' AND dataset_type='Farmer'");

$dataset_id = '';
$cows = 0;  $calves = 0;    $bulls = 0; $total_cows = 0;
$total_lactating = 0;
$total_expecting = 0;
$labor_expenditure = 0;
$injection_expenditure = 0;
$acariceides_expenditure = 0;
$tools_expenditure = 0;
$deworming_expenditure = 0;
$clearing_expenditure = 0;
$total_loans = 0;
$total_rarmers = 0;
$tp_motorcycle = 0; $tp_vehicle = 0;    $tp_bicycle = 0;    $tp_others = 0;
$rushere_farmer_nos = array();  $milk_quantity = 0;

$today = date("Y-m-d", strtotime('today'));
$svndays = date("Y-m-d", strtotime('-1 day'));
$milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$svndays&end_date=$today");
$milk_periodic_data = json_decode($milk_data);
//print_r($role, $branch);
foreach ($rows as $row) {
    if($role == 1){
        $token = $_GET['token'];
        $category = $_GET['category'];

        $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "biodata_phonenumber", 1);
        if ( sizeof($rows_n) != 0) {
            foreach ($rows_n as $rn) { array_push($rushere_farmer_nos, $rn['biodata_phonenumber']); }
        }

        $total_acreage = 0;
        $dataset_id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
        $dataset_type = $util_obj->encrypt_decrypt("decrypt", $_GET['category']);

        $data_table = "dataset_" . $row['id'];

        $cows = (int)$mCrudFunctions->get_sum("$data_table", "number_of_cows", "1");
        $calves = (int)$mCrudFunctions->get_sum("$data_table", "number_of_calves", "1");
        $bulls = (int)$mCrudFunctions->get_sum("$data_table", "number_of_bulls", "1");
        $total_cows = $cows +   $calves +    $bulls;
        $total_rarmers = (int)$mCrudFunctions->get_count("$data_table", "1");

        $total_lactating = (int)$mCrudFunctions->get_sum("$data_table", "lactating_cows", "1");
        $total_expecting = (int)$mCrudFunctions->get_sum("$data_table", "cows_expecting", "1");

        $labor_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_labor", "1");
        $injection_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_injections", "1");
        $acariceides_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_acaricides", "1");
        $tools_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_farm_tools", "1");
        $deworming_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_deworning", "1");
        $clearing_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_land_clearing", "1");

        $total_loans = (int)$mCrudFunctions->get_count("$data_table", "general_questions_accessed_loan LIKE 'yes'");
        if($total_loans == 1) $total_loans = $total_loans." Farmer";
        else $total_loans = $total_loans." Farmers";

        $tp_motorcycle = (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport='motorcycle'");
        if($tp_motorcycle == 1) $tp_motorcycle = $tp_motorcycle." Farmer";
        else $tp_motorcycle = $tp_motorcycle." Farmers";

        $tp_vehicle = (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport='vehicle'");
        if($tp_vehicle == 1) $tp_vehicle = $tp_vehicle." Farmer";
        else $tp_vehicle = $tp_vehicle." Farmers";

        $tp_bicycle = (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport='bicycle'");
        if($tp_bicycle == 1) $tp_bicycle = $tp_bicycle." Farmer";
        else $tp_bicycle = $tp_bicycle." Farmers";

        $tp_others = (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport !='motorcycle' && mode_of_transport !='vehicle' && mode_of_transport !='bicycle' ");
        if($tp_others == 1) $tp_others = $tp_others." Farmer";
        else $tp_others = $tp_others." Farmers";
    }
    else if($role == 2) {
        $token = $_GET['token'];
        $category = $_GET['category'];

        $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "id, biodata_phonenumber", "sacco_branch_name LIKE '$branch'");
        if ( sizeof($rows_n) != 0) {
//            $key = $util_obj->encrypt_decrypt("encrypt", $row['id']);
            foreach ($rows_n as $rn) { array_push($rushere_farmer_nos, $rn['biodata_phonenumber']); }
        }

        $total_acreage = 0;
        $dataset_id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
        $dataset_type = $util_obj->encrypt_decrypt("decrypt", $_GET['category']);

        $data_table = "dataset_" . $row['id'];
//        print_r($data_table);

        $cows += (int)$mCrudFunctions->get_sum("$data_table", "number_of_cows", "sacco_branch_name LIKE '$branch'");
        $calves += (int)$mCrudFunctions->get_sum("$data_table", "number_of_calves", "sacco_branch_name LIKE '$branch'");
        $bulls += (int)$mCrudFunctions->get_sum("$data_table", "number_of_bulls", "sacco_branch_name LIKE '$branch'");
        $total_cows = $cows +   $calves +    $bulls;

        $total_rarmers += (int)$mCrudFunctions->get_count("$data_table", "sacco_branch_name LIKE '$branch'");

        $total_lactating += (int)$mCrudFunctions->get_sum("$data_table", "lactating_cows", "sacco_branch_name LIKE '$branch'");
        $total_expecting += (int)$mCrudFunctions->get_sum("$data_table", "cows_expecting", "sacco_branch_name LIKE '$branch'");

        $labor_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_labor", "sacco_branch_name LIKE '$branch'");
        $injection_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_injections", "sacco_branch_name LIKE '$branch'");
        $acariceides_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_acaricides", "sacco_branch_name LIKE '$branch'");
        $tools_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_farm_tools", "sacco_branch_name LIKE '$branch'");
        $deworming_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_deworning", "sacco_branch_name LIKE '$branch'");
        $clearing_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_land_clearing", "sacco_branch_name LIKE '$branch'");

        $total_loans += (int)$mCrudFunctions->get_count("$data_table", "general_questions_accessed_loan LIKE 'yes' AND sacco_branch_name LIKE '$branch'");
        if($total_loans == 1) $total_loans = $total_loans." Farmer";
        else $total_loans = $total_loans." Farmers";

        $tp_motorcycle += (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport='motorcycle' AND sacco_branch_name LIKE '$branch'");
        if($tp_motorcycle == 1) $tp_motorcycle = $tp_motorcycle." Farmer";
        else $tp_motorcycle = $tp_motorcycle." Farmers";

        $tp_vehicle += (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport='vehicle' AND sacco_branch_name LIKE '$branch'");
        if($tp_vehicle == 1) $tp_vehicle = $tp_vehicle." Farmer";
        else $tp_vehicle = $tp_vehicle." Farmers";

        $tp_bicycle += (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport='bicycle' AND sacco_branch_name LIKE '$branch'");
        if($tp_bicycle == 1) $tp_bicycle = $tp_bicycle." Farmer";
        else $tp_bicycle = $tp_bicycle." Farmers";

        $tp_others += (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport !='motorcycle' && mode_of_transport !='vehicle' && mode_of_transport !='bicycle' AND sacco_branch_name LIKE '$branch'");
        if($tp_others == 1) $tp_others = $tp_others." Farmer";
        else $tp_others = $tp_others." Farmers";
    }
    else {
        $token = $_GET['token'];
        $category = $_GET['category'];

        $rows_n = $mCrudFunctions->fetch_rows("dataset_" . $row['id'], "id, 	biodata_phonenumber", "biodata_cooperative_name LIKE '$branch'");
        if ( sizeof($rows_n) != 0) {
//            $key = $util_obj->encrypt_decrypt("encrypt", $row['id']);
            foreach ($rows_n as $rn) { array_push($rushere_farmer_nos, $rn['biodata_phonenumber']); }
        }

        $total_acreage = 0;
        $dataset_id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
//        echo $dataset_id;
        $dataset_type = $util_obj->encrypt_decrypt("decrypt", $_GET['category']);

        $data_table = "dataset_" . $row['id'];
//        print_r($data_table);

        $cows += (int)$mCrudFunctions->get_sum("$data_table", "number_of_cows", "biodata_cooperative_name LIKE '$branch'");
        $calves += (int)$mCrudFunctions->get_sum("$data_table", "number_of_calves", "biodata_cooperative_name LIKE '$branch'");
        $bulls += (int)$mCrudFunctions->get_sum("$data_table", "number_of_bulls", "biodata_cooperative_name LIKE '$branch'");
        $total_cows = $cows +   $calves +    $bulls;

        $total_rarmers += (int)$mCrudFunctions->get_count("$data_table", "biodata_cooperative_name LIKE '$branch'");

        $total_lactating += (int)$mCrudFunctions->get_sum("$data_table", "lactating_cows", "biodata_cooperative_name LIKE '$branch'");
        $total_expecting += (int)$mCrudFunctions->get_sum("$data_table", "cows_expecting", "biodata_cooperative_name LIKE '$branch'");

        $labor_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_labor", "biodata_cooperative_name LIKE '$branch'");
        $injection_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_injections", "biodata_cooperative_name LIKE '$branch'");
        $acariceides_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_acaricides", "biodata_cooperative_name LIKE '$branch'");
        $tools_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_farm_tools", "biodata_cooperative_name LIKE '$branch'");
        $deworming_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_deworning", "biodata_cooperative_name LIKE '$branch'");
        $clearing_expenditure += (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_land_clearing", "biodata_cooperative_name LIKE '$branch'");

        $total_loans += (int)$mCrudFunctions->get_count("$data_table", "general_questions_accessed_loan LIKE 'yes' AND biodata_cooperative_name LIKE '$branch'");
        if($total_loans == 1) $total_loans = $total_loans." Farmer";
        else $total_loans = $total_loans." Farmers";

        $tp_motorcycle += (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport='motorcycle' AND biodata_cooperative_name LIKE '$branch'");
        if($tp_motorcycle == 1) $tp_motorcycle = $tp_motorcycle." Farmer";
        else $tp_motorcycle = $tp_motorcycle." Farmers";

        $tp_vehicle += (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport='vehicle' AND biodata_cooperative_name LIKE '$branch'");
        if($tp_vehicle == 1) $tp_vehicle = $tp_vehicle." Farmer";
        else $tp_vehicle = $tp_vehicle." Farmers";

        $tp_bicycle += (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport='bicycle' AND biodata_cooperative_name LIKE '$branch'");
        if($tp_bicycle == 1) $tp_bicycle = $tp_bicycle." Farmer";
        else $tp_bicycle = $tp_bicycle." Farmers";

        $tp_others += (int)$mCrudFunctions->get_count("$data_table", "mode_of_transport !='motorcycle' && mode_of_transport !='vehicle' && mode_of_transport !='bicycle' AND biodata_cooperative_name LIKE '$branch'");
        if($tp_others == 1) $tp_others = $tp_others." Farmer";
        else $tp_others = $tp_others." Farmers";
    }
}

foreach ($milk_periodic_data as $milk_supply) {
    $account = $milk_supply->account_no;
    $mobile_no = substr($account, 6);

    if($role == 1 || $role == 2 || $role == 3){
        if(in_array($mobile_no, $rushere_farmer_nos)){
            $milk_quantity += $milk_supply->milk_amount;
        }
    }
}

?>

<style type="text/css">

    .data {
        background: none;

    }

    .data a {
        padding: 4px 0 !important;
        display: block;
        margin: 0;
        text-decoration: none;

    }

    .x_panel h4 {
        border-bottom: #ccc solid 1px;
        padding: 5px 0;
        /*color: #888;*/
        font-size: 13pt;
    }

    .tiny {
        font-size: 10pt;
    }

    h3 {
        padding: 0 !important;
        left: 0;
        margin: 0;
        margin-top: 20px;
        color: #555;
        font-size: 16pt;
    }

    .panel {
        height: auto;
        display: table;
        width: 100%;
        padding: 20px;
    }

    .titles {
        color: black;
        font-weight: bolder;
        font-family: "Garamond";
        /*font-size: x-large;*/
        /*font-size: 2em;*/
    }

</style>

<?php
$dataset_name = "";
$total_farmers = 0;
$total_acerage = 0;
$total_gardens = 0;
$total_vas = 0;

$va_token = "";
$va_dataset_id = 0;
$dataset_ = "";

if (isset($_GET['token']) && $_GET['token'] != "" && isset($_GET['category']) && $_GET['category'] != "") {
    $id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
    $type = $util_obj->encrypt_decrypt("decrypt", $_GET['category']);

    if ($type == "Farmer") {
        $dataset_ = $util_obj->encrypt_decrypt("encrypt", $dataset_id);
        $branch_ = $util_obj->encrypt_decrypt("encrypt", $branch);
        $dataset = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id");
        $dataset_name = ucfirst(strtolower(str_replace("_", " ", $dataset[0]["dataset_name"])));

        $total_farmers = number_format($mCrudFunctions->get_count("dataset_" . $id, 1));
        ////////////////////////////////////////////////////////////////////////////////////////////////
        ?>
        <!--<div class="container-fluid" style="background-color: grey;">-->
        <?php include "include/breadcrumb.php" ?>
        <div class="container-fluid">
            <div class='col-xs-12'><h2
                        style='font-size:16pt; color: #1a405b; padding:10px 0px; border-bottom:1px solid #eee;'><i
                            class='fa fa-file'></i><?php echo $branch; ?></h2>
                <div class='clearfix'></div>
            </div>
            <!--    </div>-->

            <div class="col-md-8" style="border-radius: 5px; border: darkgrey thin solid; background-color: white; ">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="x_panel tile  overflow_hidden">
                            <h4 class="titles"><b>Total number of cattle owned by farmers</b></h4>
                            <div class="data">

                                <a><b><?php echo $total_cows;?> Cattle </b><br>
                                    <br/> <strong>Comprising of;</strong>
                                    <br/> &nbsp;<b>Cows: <?php echo $cows; ?> </b>
                                    <br/> &nbsp;&nbsp;<b>Bulls: <?php echo $bulls; ?> </b>
                                    <br/> &nbsp;&nbsp;<b>Calves: <?php echo $calves; ?> </b>
                                </a>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="x_panel tile  overflow_hidden">
                            <h4 class="titles"><b>Lactating cows</b></h4>
                            <div class="data">

                                <a> <b><?php echo $total_lactating; ?> Lactating cows</b></a>
                                <a> <b><?php echo $total_expecting; ?> Expecting cows</b></a>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="x_panel tile  overflow_hidden">
                            <h4 class="titles"><b>Quantity Of Milk Supplied Today</b></h4>
                            <div class="data">
                                <a><b><?php echo $milk_quantity." Ltrs"; ?></b></a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="x_panel tile  overflow_hidden">
                            <h4 class="titles"><b>Transport modes usage</b></h4>
                            <div class="data">
                                <a>
                                    <span><b>Vehicle : <?php echo $tp_vehicle; ?> </b></span> <br>
                                    <span><b>Motorcycle : <?php echo $tp_motorcycle; ?> </b></span> <br>
                                    <span><b>Bicycle : <?php echo $tp_bicycle; ?> </b></span> <br>
                                    <span><b>Others : <?php echo $tp_others; ?> </b></span> <br>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="x_panel tile  overflow_hidden">
                            <h4 class="titles"><b>Expenditure on activities</b></h4>
                            <div class="data">

                                <a>
                                    <span><b>Labour: <?php echo number_format($labor_expenditure) ; ?> UGX</b></span> <br>
                                    <span><b>Injections: <?php echo number_format($injection_expenditure) ; ?> UGX</b></span> <br>
                                    <span><b>Acaricides: <?php echo number_format($acariceides_expenditure) ; ?> UGX</b></span> <br>
                                    <span><b>Tools: <?php echo number_format($tools_expenditure) ; ?> UGX</b></span> <br>
                                    <span><b>Deworming: <?php echo number_format($deworming_expenditure) ; ?> UGX</b></span> <br>
                                    <span><b>Land clearing: <?php echo number_format($clearing_expenditure) ; ?> UGX</b></span> <br>
                                </a>
                            </div>
                        </div>
                    </div>

                </div> <!-- row 2-->
            </div>

            <div class="col-md-3 col-md-offset-1">
                <div class="row" style="padding-bottom: 5em;">

                    <div class="col-xs-12">
                        <?php
                        echo "<div class=\" panel\">
    <div class=\"col-xs-12\">
      <h3>FARMERS</h3>   
    
    <div class=\"content\" style=\"margin-top: 1em;\">
    <h3><span class='tiny'>Total Number:</span> $total_rarmers</h3>
    <p>
    <b><span>Total Cows: $total_cows</span></b></p>
    <br>
    <a style=\"\" class=\"btn btn-sm btn-success\" onclick=\"Export2Csv($dataset_id);\" >Export</a>
    <a style=\"\" href=\"view2.php?token=$dataset_&type=Farmer\" class=\"btn btn-sm btn-info\">View Details &raquo;</a>
        </div>
      </div>
      </div>
    </div>";
                        ?>

                    </div> <!-- row datasets-->
                    <div id="hidden_form_container">
                    </div>
                </div>
            </div>
        </div>


        <?php

    }
    else if ($type == "VA") {
        $dataset_ = $util_obj->encrypt_decrypt("encrypt", $id);
        $dataset = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id");
        $dataset_name = ucfirst(strtolower(str_replace("_", " ", $dataset[0]["dataset_name"])));

        $va_dataset_id = $id;
        $va_token = $util_obj->encrypt_decrypt("encrypt", $id);

        $total_vas = number_format($mCrudFunctions->get_count("dataset_" . $id, 1));
    }
}

?>
<script>
    function Export2Csv(id) {

        var theForm, newInput1;
        // Start by creating a <form>
        theForm = document.createElement('form');
        theForm.action = 'export_dataset2csv.php';
        theForm.method = 'post';
        // Next create the <input>s in the form and give them names and values
        newInput1 = document.createElement('input');
        newInput1.type = 'hidden';
        newInput1.name = 'id';
        newInput1.value = id;


        // Now put everything together...
        theForm.appendChild(newInput1);

        // ...and it to the DOM...
        document.getElementById('hidden_form_container').appendChild(theForm);
        // ...and submit it
        theForm.submit();


    }

</script>


<?php include("include/footer_client.php"); ?>
