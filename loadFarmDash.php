<?php include("include/header_client.php");
#includes
require_once dirname(__FILE__) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/utility_class.php";

$util_obj = new Utilties();
$db = new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$token = $_GET['token'];
$category = $_GET['category'];

$total_acreage = 0;
$dataset_id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
$dataset_type = $util_obj->encrypt_decrypt("decrypt", $_GET['category']);

$data_table = "dataset_" . $dataset_id;
//print_r($data_table);

//$total_cows = (int)$mCrudFunctions->get_sum("$data_table", "number_of_cows", "1");
$cows = (int)$mCrudFunctions->get_sum("$data_table", "number_of_cows", "1");
$calves = (int)$mCrudFunctions->get_sum("$data_table", "number_of_calves", "1");
$bulls = (int)$mCrudFunctions->get_sum("$data_table", "number_of_bulls", "1");
$total_cows = $cows +   $calves +    $bulls;

//if($total_cows = 0){
//    $cows = (int)$mCrudFunctions->get_sum("$data_table", "cattle_number_cows", "1");
//    $calves = (int)$mCrudFunctions->get_sum("$data_table", "cattle_number_calves", "1");
//    $bulls = (int)$mCrudFunctions->get_sum("$data_table", "cattle_number_bulls", "1");
//    $total_cows =   $cows ;
//}

//$total_cows
$total_rarmers = (int)$mCrudFunctions->get_count("$data_table", "1");

$total_lactating = (int)$mCrudFunctions->get_sum("$data_table", "lactating_cows", "1");
$total_expecting = (int)$mCrudFunctions->get_sum("$data_table", "cows_expecting", "1");

$labor_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_labor", "1");
$injection_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_injections", "1");
$acariceides_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_acaricides", "1");
$tools_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_farm_tools", "1");
$deworming_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_deworning", "1");
$clearing_expenditure = (int)$mCrudFunctions->get_sum("$data_table", "expenditure_on_land_clearing", "1");

$total_loans = (int)$mCrudFunctions->get_count("$data_table", "accessed_loan='yes'");

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

$total_herbicide_solid = (int)$mCrudFunctions->get_sum("out_grower_input_v", "qty", " dataset_id='$dataset_id' AND item_type='Herbicide' AND units='KGS' ");
$total_herbicide_liquid = (int)$mCrudFunctions->get_sum("out_grower_input_v", "qty", " dataset_id='$dataset_id'  AND item_type='Herbicide' AND units='LITRES' ");
//out_grower_produce_tb
$total_tractor_cash = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND cash_type='tractor' ");
$total_cash_given_to_farmers = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND cash_type='cashtaken' ");
$total_produce_supplied = (int)$mCrudFunctions->get_sum("out_grower_produce_v", "qty", " dataset_id='$dataset_id'  ");
$total_produce_commission = (double)$mCrudFunctions->get_sum("out_grower_produce_v", "commission", " dataset_id='$dataset_id'  ");


$client_id = $_SESSION["client_id"];
$profiling_constant = (double)$mCrudFunctions->fetch_rows("out_grower_threshold_tb", "*", " client_id='$client_id' AND item='Profiling' AND item_type='Service' ")[0]['commission_per_unit'];

//echo $client_id;

$total_acreage = $mCrudFunctions->get_sum("out_grower_cashinput_tb", "acreage", " dataset_id='$dataset_id'  AND cash_type='tractor' ");

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

//    switch ($type){
//        case "Farmer"
//    }
    if ($type == "Farmer") {

        $dataset_ = $util_obj->encrypt_decrypt("encrypt", $id);
        $dataset = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id");
        $dataset_name = ucfirst(strtolower(str_replace("_", " ", $dataset[0]["dataset_name"])));
        // $dataset_type=$dataset[0]["dataset_type"];
        $total_farmers = number_format($mCrudFunctions->get_count("dataset_" . $id, 1));

        $profiling_commission = $profiling_constant * $total_farmers;

        $va_tb_id = $dataset[0]['va_dataset_id'];
        $va_dataset_id = $va_tb_id;
        $va_token = $util_obj->encrypt_decrypt("encrypt", $va_tb_id);

        $total_vas = number_format($mCrudFunctions->get_count("dataset_" . $va_tb_id, 1));


        ////////////////////////////////////////////////////////////////////////////////////////////////
        if ($mCrudFunctions->check_table_exists("garden_" . $id)) {
            $gardenz = $mCrudFunctions->fetch_rows("total_acerage_tb", "*", " dataset_id='$id'");

            if (sizeof($gardenz) > 0) {
                $total_acerage = $gardenz[0]['ttl_acerage'];
                $total_gardens = $gardenz[0]['ttl_gardens'];
            } else {
                $total_acerage_gardens = $mCrudFunctions->getTTLAcerage_Gardens($table, $dataset_id);
                $total_acerage = $total_acerage_gardens[0];
                $total_gardens = $total_acerage_gardens[1];
                $mCrudFunctions->insert_into_total_acerage_tb($dataset_id, $total_gardens, $total_acerage);
            }
        }

        $total_acerage = number_format($total_acerage);
        $total_gardens = number_format($total_gardens);


        ////////////////////////////////////////////////////////////////////////////////////////////////
        ?>
        <!--<div class="container-fluid" style="background-color: grey;">-->
        <?php include "include/breadcrumb.php" ?>
        <div class="container-fluid">
            <div class='col-xs-12'><h2
                        style='font-size:16pt; color: #1a405b; padding:10px 0px; border-bottom:1px solid #eee;'><i
                            class='fa fa-file'></i><?php echo $dataset_name; ?></h2>
                <div class='clearfix'></div>
            </div>
            <!--    </div>-->

            <div class="col-md-8" style="border-radius: 5px; border: darkgrey thin solid; background-color: white; ">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="x_panel tile  overflow_hidden">
                            <h4 class="titles"><b>Total number of cattle owned by farmers</b></h4>
                            <div class="data">

                                <a><b><?php echo $total_cows; ?> Cattle </b><br>
                                    <br/> <strong>Comprising of;</strong>
                                    <br/> &nbsp; <b>Cows: <?php echo $cows; ?> </b>
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
                            <h4 class="titles"><b>Total Farmers who got loans</b></h4>
                            <div class="data">
                                <a><b><?php echo $total_loans; ?> Farmers</b></a>

                            </div>
                        </div>
                    </div>

                    <!--                    <div class="col-md-4 col-sm-4 col-xs-12">-->
                    <!--                        <div class="x_panel tile  overflow_hidden">-->
                    <!--                            <h4 class="titles"><b>Total Acreage Ploughed</b></h4>-->
                    <!--                            <div class="data">-->
                    <!--                                <a><b>--><?php //echo $total_acerage; ?><!-- Acres</b></a> <br>-->
                    <!---->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->

                </div>
                <div class="row">
                    <!--                    <div class="col-md-4 col-sm-4 col-xs-12">-->
                    <!--                        <div class="x_panel tile  overflow_hidden">-->
                    <!--                            <h4 class="titles"><b>Total Herbicide Given Out</b></h4>-->
                    <!--                            <div class="data">-->
                    <!---->
                    <!--                                <a><b>Solid</b>: <b>--><?php //echo $total_herbicide_solid; ?><!-- KGS</b></a>-->
                    <!--                                <a><b>Liquid</b>:<b> --><?php //echo $total_herbicide_liquid ?><!-- LITRES</b></a>-->
                    <!---->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->

                    <!--                    <div class="col-md-4 col-sm-4 col-xs-12">-->
                    <!--                        <div class="x_panel tile  overflow_hidden">-->
                    <!--                            <h4 class="titles"><b>Total Cost Of Tractor Services</b></h4>-->
                    <!--                            <div class="data">-->
                    <!--                                <a><b>--><?php //echo number_format($total_tractor_cash); ?><!-- UGX</b></a>-->
                    <!---->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->





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
    <h3><span class='tiny'>Total Number:</span> $total_farmers</h3>
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

<!--                        <div class="col-xs-12">-->
<!--                            <div class="panel">-->
<!--                                <div class="col-xs-12">-->
<!--                                    <div class="x_title">-->
<!--                                        <h3>VILLAGE AGENTS</h3>-->
<!--                                        <!--  <div class="clearfix"></div> -->
<!--                                    </div>-->
<!--                                    <div class="content" style="margin-top: 1em;">-->
<!--                                        --><?php //echo "<h3><span class='tiny'>Total Number: </span> $total_vas</h3>" ?>
<!--                                        <br>-->
<!--                                        <br>-->
<!---->
<!--                                        --><?php
//                                        if ($total_vas > 0) {//
//                                            echo "<a style=\"\" class=\"btn btn-sm btn-success\" onclick=\"Export2Csv($va_dataset_id);\" >Export</a>";
//                                            echo "<a style=\"\" href=\"view.php?o=$dataset_&token=$va_token&type=VA\" class=\"btn btn-sm btn-info\">View More &raquo;</a>";
//                                        }
//                                        ?>
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                    </div> <!-- row datasets-->
                    <div id="hidden_form_container">
                    </div>
                </div>
            </div>
        </div>


        <?php

    } else if ($type == "VA") {
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
