<?php include("include/header_client.php");
#includes
require_once dirname(__FILE__) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/utility_class.php";

$util_obj = new Utilties();
$db = new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$token = $_GET['token'];

$total_acreage = 0;

$dataset_id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);

$total_seed = (int)$mCrudFunctions->get_sum("out_grower_input_v", "qty", " dataset_id='$dataset_id' AND  item_type='Seed'  ");


$total_fertilizer_solid = (int)$mCrudFunctions->get_sum("out_grower_input_v", "qty", " dataset_id='$dataset_id' AND  item_type='Fertilizer' AND units='KGS' ");

$total_fertilizer_liquid = (int)$mCrudFunctions->get_sum("out_grower_input_v", "qty", " dataset_id='$dataset_id' AND  item_type='Fertilizer' AND units='LITRES' ");

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
    /*
      body.nav-md .container.body .right_col {
        width: 100%;
        padding-right: 0
      }
      .right_col {
        padding: 10px !important;
      }
      .navbar-right {
      margin-right: 0;
    }
    .tile{
        border-radius: 4px;
    }
      .x_panel {
        position: relative;
        width: 100%;
        margin-bottom: 10px;
        padding: 10px 17px;
        display: inline-block;
          text-align: center;
        background: #fff;
        border: 1px solid #E6E9ED;
        -webkit-column-break-inside: avoid;
        -moz-column-break-inside: avoid;
        column-break-inside: avoid;
        opacity: 1;
        -moz-transition: all .2s ease;
        -o-transition: all .2s ease;
        -webkit-transition: all .2s ease;
        -ms-transition: all .2s ease;
        transition: all .2s ease;
        height: auto !important;
      }

      .x_title {

        padding: 1px 5px 6px;

       text-align: color;
       vertical-align: bottom;
       color: #ee654e;
      }

      .x_title h2 {

       text-align: color;
       font-size: 1.2em !important;
       text-align: color !important;
       color: #0069ff;
       border-bottom: 2px solid #E6E9ED;
       width: 100% !important;
      }

      .x_titles {
        border-bottom: 2px solid #E6E9ED;
        padding: 1px 5px 6px;
        margin-bottom: 10px;
        margin-left: 13px;
        width: 98%;
      }

      .x_title .filter {
        width: 40%;
        float: right;
      }
      .x_title h2 {
        margin: 5px 0 6px;
        float: left;
        display: block;

        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
      }
      .x_title h2 small {
        margin-left: 10px;
      }
      .x_title span {
        color: #BDBDBD;
      }
      .x_content {
        padding: 0 5px 6px;
        position: relative;

        float: left;
        clear: both;
        margin-top: 5px;
      }
      .content{
        vertical-align: bottom;

      }

      .x_content h4 {
        font-size: 12px;
        font-weight: 300;
          display: none;
      }
      legend {
        padding-bottom: 7px;
      }
      .modal-title {
        margin: 0;
        line-height: 1.42857143;
      }
      .demo-placeholder {
        height: 280px;
      }
    .fixed_height_400{
        height: 300px;
    }

    .fixed_height_300{
      height: 300px;
    }
    h4:nth-child(1){
     background-color: #aaa;
    color:#fff;
     margin: 0;

      font-weight: 400;
        font-size: 12pt;
        padding: 10pt;
    }


      .data{
        margin-top: 1em;
        text-align: center;
        background-color: #fff !important;
      }
      .data a{
        background-color: none !important;
        vertical-align: bottom;
         color: #777 !important;
         font-size: 13pt;
          text-align: left !important;
         font-weight: 100;
      }
        #first_row  h4{
            background: #f5b23d !important;
            color: white;
            padding: none;
            margin: none;
        }
     #third_row  h4{
            background: #f5b23d !important;
            color: white;
            padding: none;
            margin: none;
        }
        .data a:hover{
          text-decoration: none !important;
          cursor: pointer;
        }*/
    /*-----------------------------------------------------------Styling starts here. up is commented*/
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

if (isset($_GET['token']) && $_GET['token'] != "") {
    $id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
    $dataset_ = $util_obj->encrypt_decrypt("encrypt", $id);
    $dataset = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id");
    $dataset_name = ucfirst(strtolower(str_replace("_", " ", $dataset[0]["dataset_name"])));;
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

}

?>

<!--<div class="container-fluid" style="background-color: grey;">-->
<?php include "include/breadcrumb.php" ?>
<div class="container-fluid">
    <div class='col-xs-12'><h2
                style='font-size:16pt; color: #1a405b; padding:10px 0px; border-bottom:1px solid #eee;'><i
                    class='glyphicon glyphicon-folder-open'></i> <?php echo " &nbsp; ".$dataset_name; ?></h2>
        <div class='clearfix'></div>
    </div>
    <!--    </div>-->

    <div class="col-md-8" style="border-radius: 5px; border: darkgrey thin solid; background-color: white; ">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <h4 class="titles"><b>Total Seeds Given Out</b></h4>
                    <div class="data">

                        <a><b><?php echo $total_seed; ?> KGS </b></a>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <h4 class="titles"><b>Total Fertilizers Given Out</b></h4>
                    <div class="data">

                        <a><b>Solid:</b> <b><?php echo $total_fertilizer_solid; ?> KGS</b></a>
                        <a><b>Folia:</b> <b><?php echo $total_fertilizer_liquid; ?> LITRES</b></a>

                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <h4 class="titles"><b>Total Acreage Ploughed</b></h4>
                    <div class="data">
                        <a><b><?php echo $total_acerage; ?> Acres</b></a> <br>

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <h4 class="titles"><b>Total Herbicide Given Out</b></h4>
                    <div class="data">

                        <a><b>Solid</b>: <b><?php echo $total_herbicide_solid; ?> KGS</b></a>
                        <a><b>Liquid</b>:<b> <?php echo $total_herbicide_liquid ?> LITRES</b></a>

                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <h4 class="titles"><b>Total Cost Of Tractor Services</b></h4>
                    <div class="data">
                        <a><b><?php echo number_format($total_tractor_cash); ?> UGX</b></a>

                    </div>
                </div>
            </div>


            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <h4 class="titles"><b>Total Cash Given To Farmers</b></h4>
                    <div class="data">
                        <a><b><?php echo number_format($total_cash_given_to_farmers); ?> UGX</b></a>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <h4 class="titles"><b>Total Produce Supplied</b></h4>
                    <div class="data">
                        <a><b><?php echo $total_produce_supplied; ?> KGS</b></a> <br>

                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="x_panel tile  overflow_hidden">
                    <h4 class="titles"><b>Total Commission</b></h4>
                    <div class="data">

                        <a><b>Profiling: <?php echo number_format($profiling_commission); ?> UGX</b></a> <br>
                        <a><b>Produce: <?php echo number_format($total_produce_commission); ?> UGX</b></a> <br>

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
    <b><span>Total Acreage: $total_acerage</span></b></p>
    <br>
    <a style=\"\" class=\"btn btn-sm btn-success\" onclick=\"Export2Csv($dataset_id);\" >Export</a>
    <a style=\"\" href=\"view.php?token=$dataset_&type=Farmer\" class=\"btn btn-sm btn-info\">View Details &raquo;</a>
        </div>
      </div>
      </div>
    </div>";


                ?>

                <div class="col-xs-12">
                    <div class="panel">
                        <div class="col-xs-12">
                            <div class="x_title">
                                <h3>VILLAGE AGENTS</h3>
                                <!--  <div class="clearfix"></div> -->
                            </div>
                            <div class="content" style="margin-top: 1em;">
                                <?php echo "<h3><span class='tiny'>Total Number: </span> $total_vas</h3>" ?>
                                <br>
                                <br>

                                <?php
                                if ($total_vas > 0) {//
                                    echo "<a style=\"\" class=\"btn btn-sm btn-success\" onclick=\"Export2Csv($va_dataset_id);\" >Export</a>";
                                    echo "<a style=\"\" href=\"view.php?o=$dataset_&token=$va_token&type=VA\" class=\"btn btn-sm btn-info\">View More &raquo;</a>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- row datasets-->
            <div id="hidden_form_container">
            </div>
        </div>
    </div>
</div>
<div class="container-fluid hide">
    <div class="row" id="first_row">
        <?php echo "<h3 class=\" text-center \" style=\"margin-left:12px !important;\">$dataset_name</h3>"; ?>

    </div>

    <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="x_panel tile  overflow_hidden">
                <h4>Total Fertilizers Given Out</h4>
                <div class="data">

                    <a><b>Solid: <?php echo $total_fertilizer_solid; ?> KGS</b></a> <br>
                    <a><b>Folia: <?php echo $total_fertilizer_liquid; ?> LITRES</b></a> <br>

                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="x_panel tile  overflow_hidden">
                <h4>Total Acreage Ploughed</h4>
                <div class="data">
                    <a><b><?php echo $total_acreage; ?> Acres</b></a> <br>

                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="x_panel tile  overflow_hidden">
                <h4>Total Herbicide Given Out</h4>
                <div class="data">

                    <a><b>Solid: <?php echo $total_herbicide_solid; ?> KGS</b></a> <br>
                    <a><b>Liquid: <?php echo $total_herbicide_liquid ?> LITRES</b></a> <br>

                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="x_panel tile  overflow_hidden">
                <h4>Total Cost Of Tractor Services</h4>
                <div class="data">
                    <a><b><?php echo number_format($total_tractor_cash); ?> UGX</b></a> <br>

                </div>
            </div>
        </div>


        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="x_panel tile  overflow_hidden">
                <h4>Total Cash Given To Farmers</h4>
                <div class="data">
                    <a><b><?php echo number_format($total_cash_given_to_farmers); ?> UGX</b></a> <br>

                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="x_panel tile  overflow_hidden">
                <h4>Total Produce Supplied</h4>
                <div class="data">
                    <a><b><?php echo $total_produce_supplied; ?> KGS</b></a> <br>

                </div>
            </div>
        </div>


    </div> <!-- row 2-->


    <div class="row" style="padding-bottom: 5em;">

        <div class="col-md-4 col-sm-4 col-xs-12">
            <?php
            echo "<div class=\"x_panel tile fixed_height_300\">
    <div class=\"x_title\">
      <h2>FARMERS</h2>
     <!--  <div class=\"clearfix\"></div> -->
    </div>
    <div class=\"content\" style=\"margin-top: 4em;\">
    <h6>Total Number : $total_farmers</h6>
    <p style=\"padding: 0px 10px; font-size:13px;\">
    <b><span>Total Acreage: $total_acerage</span></p>
    <br>
    <a style=\"margin-left:15px;\" class=\"btn btn-xs btn-success\" onclick=\"Export2Csv($dataset_id);\" >Export</a>
    <a style=\"margin-left:25px;\" href=\"view.php?token=$dataset_&type=Farmer\" class=\"btn btn-xs btn-info\">View Details &raquo;</a>
        </div>
      </div>
    </div>";


            ?>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="x_panel tile fixed_height_300">
                    <div class="x_title">
                        <h2>VILLAGE AGENTS</h2>
                        <!--  <div class="clearfix"></div> -->
                    </div>
                    <div class="content" style="margin-top: 4em;">
                        <?php echo "<h6>Total Number : $total_vas</h6>" ?>
                        <br>
                        <br>

                        <?php
                        if ($total_vas > 0) {//
                            echo "<a style=\"margin-left:15px;\" class=\"btn btn-xs btn-success\" onclick=\"Export2Csv($va_dataset_id);\" >Export</a>";
                            echo "<a style=\"margin-left:25px;\" href=\"view.php?o=$dataset_&token=$va_token&type=VA\" class=\"btn btn-xs btn-info\">View More &raquo;</a>";
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div> <!-- row datasets-->
        <div id="hidden_form_container">
        </div>
    </div>
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
