<style>
    .right {
        float: right;
        margin: 0 40px;

    }

    .btn-fab {
        position: fixed !important;
        right: 60px;
        bottom: 90px;
        z-index: 100;
    }

    .attach-file.selected {
        background: darkgreen !important;
        box-shadow: none !important;
        color: white !important
    }

    .col-x {
        padding: 1px !important;
        transition-durarion: 1s !important;
        -webkit-transition-durarion: 1s;
        background: none;
        visibility: gone;
    }

    .col-x:hover .card {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
        cursor: pointer;
        border-color: green;
        position: relative;
        z-index: 1;
    }
</style>
<style type="text/css">
    .dropdown i, a i, a.fa {
        color: #FF5722;

        text-orientation: none !important;
    }

    .dropdown a:hover, ul li a:hover, li a:hover {

        text-orientation: none !important;
    }

    a, a:link, a:visited, a:active, a:hover {
        border: 0 !important;
        text-orientation: none !important;

    }

    .badge1 {
        /*    position: absolute;*/
        font-size: .6em;
        color: orange;
        bottom: 10px;
        right: 2px;
        height: 20px;
        width: 30px;
        padding: 5px 5px;
        border-radius: 50px;

    }

    .containers {
        padding-right: 0px;
        padding-left: 0px;
        margin-right: auto;
        margin-left: auto;
    }

    .down {
        margin-top: -3em !important;
    }
</style>

<?php include("./include/header_client.php");

#includes
require_once dirname(__FILE__) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__) . "/php_lib/lib_functions/utility_class.php";

$util_obj = new Utilties();
$db = new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
?>
<?php include "./include/breadcrumb.php" ?>
<div class="containers">

    <?php

    if (isset($_GET['token']) && $_GET['token'] != "") {
        $id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
        $dataset_ = $util_obj->encrypt_decrypt("encrypt", $id);
        $dataset = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id");
        $dataset_name = ucfirst(strtolower(str_replace("_", " ", $dataset[0]["dataset_name"])));;
        $dataset_type = $dataset[0]["dataset_type"];

        echo "<div class=\"row hide\">
<div  class=\"col-md-12\">
<p class=\"text-center\">
<span><h3 style=\"color:#3F51B5;display:block; text-align:center; top:-5px; margin:0\">$dataset_name</h3></span></p>
<input id=\"dataset_type\" type=\"hidden\" value=\"$dataset_type\"/>
<input id=\"dataset_id_container\" type=\"hidden\" value=\"$id\"/>
</div>
</div>
";
        if ($dataset_type == "Farmer") {
            include("include/filtered.php");

        } else {
            include("include/va_filtered.php");

            $origin = $_GET['o'];
            echo "<input id=\"origin\" type=\"hidden\" value=\"$origin\"/>";

        }
        echo "
  <div class=\"right print_export container\">
  <a href=\"#export\" data-toggle=\"modal\" title=\"Export CSV\" data-target=\"#export\" class=\"btn btn-success
  btn-fab btn-raised mdi-action-class\"></a>
</div>";

        /*<a hred=\"#export\" data-toggle=\"model\"  data-target=\"#export\" class=\"btn btn-success btn-fab btn-raised mdi-action-class\" onclick=\"Export2Csv();\" ></a>*/

        include("include/preloader.php");
        include("include/empty.php");
    }
    ?>

            <div id="filtered_data" class="containers down"><!--data grid goes here-->


            </div><!--end of data grid-->

    <div class="container" style="margin-bottom:80px;"><!--start of pagination row-->
        <div id="pagination_holder" class="col s12 m12 l12"><!--pagination starts-->


        </div><!--ends of pagination -->
        <input type="hidden" id="acerage"/>
        <input type="hidden" id="farmer_id"/>
    </div><!--end of pagination row-->

</div><!--end of main content-->

<div id="hidden_form_container">
</div>

<!-- Modal add extra casg -->
<div class="modal fade" id="cash" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Record Cash Returned</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <label class="control-label col-sm-6">Enter Cash Returned</label>&nbsp;
                    <div class="form-group">
                        <input id="cash_returned" text="number" name="" class="form-control col-sm-6"
                               placeholder="Cash Returned">
                    </div>
                    </br>

                    <label class="control-label col-sm-6">Enter Tractor Money Returned</label>&nbsp;
                    <div class="form-group">
                        <input id="tractor_money_returned" text="number" name="" class="form-control col-sm-6"
                               placeholder="Tractor Money">
                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <button id="dismiss_c" type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button onclick="recordCashReturns();" type="button" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal produce -->
<div class="modal fade" id="inputs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Record Farm Inputs</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <label class="control-label col-sm-4">Seed Taken</label>
                    <div class="form-group">
                        <select id="seed_selected" onchange="seedInput();" class="form-control col-sm-6">
                            <option value="">Select Seed</option>
                            <option value="1">Hybrid Maize Seed</option>
                        </select>
                        <input id="seed_taken" type="text" name="" class="form-control" placeholder="Seed Quantity">
                    </div>
                    </br>

                    <label class="control-label col-sm-4">Fertilizer Taken</label>

                    <div class="form-group">
                        <select id="fertilizer_selected" onchange="fertilizerInput();" class="form-control col-sm-8">
                            <option value="">Select Fertilizer</option>
                            <option value="0">DAP</option>
                            <option value="1">Foliar</option>
                        </select>
                        <input id="fertilizer_taken" type="text" name="" class="form-control" placeholder="Quantity">
                    </div>
                    </br>


                    <label class="control-label col-sm-4">Herbicide Taken</label>

                    <div class="form-group">
                        <select id="herbicide_selected" onchange="herbicideInput();" class="form-control col-sm-8">
                            <option value="">Select Herbicide</option>
                            <option value="0">Greenfire</option>
                            <option value="1">Weedround</option>
                            <option value="2">Weedall</option>
                            <option value="3">Weedmaster granular</option>
                            <option value="4">24D</option>

                        </select>
                        <input id="herbicide_taken" type="text" name="" class="form-control" placeholder="Quantity">
                    </div>
                    </br>

                    <label class="control-label col-sm-4">Cash Taken</label>
                    <div class="form-group">
                        <input id="cash_taken" text="number" name="" class="form-control col-sm-8"
                               placeholder="Cash Taken">
                    </div>
                    <br>

                    <label class="control-label col-sm-4">Tractor Money</label>
                    <div class="form-group">
                        <input id="tractor_taken" text="number" name="" class="form-control col-sm-8"
                               placeholder="Tractor Money">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button id="dismiss" type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button onclick="recordFarmInputs();" type="button" class="btn btn-success">Save</button>

            </div>
        </div>
    </div>
</div>

<!-- Modal add extra casg -->
<div class="modal fade" id="yield" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Record Farmer's Yield</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline">
                    <!--<label class="control-label">Projected Yield</label>&nbsp;
                    <div class="form-group">
                      <input text="number" name="" readonly value="3.45" class="form-control" placeholder="Actual Yield">
                    </div></br>-->
                    <label class="control-label">Enter Actual Yield</label>&nbsp;
                    <div class="form-group">
                        <input id="yield_value" text="number" name="" class="form-control" placeholder="Actual Yield"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="dismiss_y" type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button onclick="recordFarmYields();" type="button" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal adding export fields -->
<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Choose fileds to include in exported data</h4>
            </div>
            <div class="modal-body">
                <div class="">
                    <label>
                        <input id="check-all" type="checkbox" class="check-all"> Select All
                    </label>
                </div>
                <hr>

                <div id="csv_columnsr" class="row check-panel">

                    <?php
                    unset($_SESSION['fields']);
                    $key = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);;
                    $enterprise = $mCrudFunctions->fetch_rows("production_data", "enterprise", " dataset_id='$key'")[0]['enterprise'];

                    $bio_data_rows = $mCrudFunctions->fetch_rows("bio_data", "*", " dataset_id='$key' GROUP BY id");//

                    $production_data_rows = $mCrudFunctions->fetch_rows("production_data", "*", " dataset_id='$key' GROUP BY id");//

                    $general_questions_rows = $mCrudFunctions->fetch_rows("general_questions", "*", " dataset_id='$key' GROUP BY id");

                    $farmer_location_rows = $mCrudFunctions->fetch_rows("farmer_location", "*", " dataset_id='$key' GROUP BY id");

                    $info_on_other_enterprise_rows = $mCrudFunctions->fetch_rows("info_on_other_enterprise", "*", " dataset_id='$key' GROUP BY id");

                    $index = 0;

                    echo "<div class=\"col-md-6\">";

                    foreach ($bio_data_rows as $row) {

                        $index = $index + 1;
                        $column = $row['columns'];

                        $id__ = "bio_data_" . $row['id'];


                        $column = str_replace("biodata_", "", $column);
                        $column = str_replace("_", " ", $column);

                        if (strpos($column, 'dob') !== false) {
                            $column = $util_obj->captalizeEachWord("farmer birth date");
                        }
                        echo "<div class=\"\">
          <label>
            <input id=\"$index\" type=\"checkbox\" value=\"$id__\"  onclick=\"selection($index); UncheckButton();\"/>  $column
        </label>
          </div>";

                    }
                    echo " </div>";

                    echo "<div class=\"col-md-6\">";
                    foreach ($farmer_location_rows as $row) {
                        $index = $index + 1;
                        $column = $row['columns'];

                        $id__ = "farmer_location_" . $row['id'];

                        $column = str_replace("biodata_farmer_location_", "", $column);
                        $column = str_replace("farmer_home_gps_", "", $column);

                        $column = $util_obj->captalizeEachWord(str_replace("_", " ", $column));
                        echo "<div class=\"\">
          <label>
            <input id=\"$index\" type=\"checkbox\"  value=\"$id__\" onclick=\"selection($index); UncheckButton();\"/>   $column
        </label>
          </div>";

                    }
                    echo " </div>";

                    echo "<div class=\"col-md-6\">";

                    $gardens_table = "garden_" . $key;

                    if ($mCrudFunctions->check_table_exists($gardens_table) > 0) {


                        $index = $index + 1;
                        $id__ = "acreage_data";

                        echo "<div class=\"\">
          <label>
            <input id=\"$index\" type=\"checkbox\" value=\"$id__\"  onclick=\"selection($index); UncheckButton();\" />  Acreage
        </label>
          </div>";

                    }

                    foreach ($production_data_rows as $row) {
                        $index = $index + 1;
                        $column = $row['columns'];

                        $id__ = "production_data_" . $row['id'];

                        $column = str_replace($enterprise . "_production_data_", "", $column);
                        $column = $util_obj->captalizeEachWord(str_replace("_", " ", $column));
                        echo "<div class=\"\">
          <label>
            <input id=\"$index\" type=\"checkbox\" value=\"$id__\"  onclick=\"selection($index); UncheckButton();\" />  $column
        </label>
          </div>";

                    }
                    echo " </div>";

                    echo "<div class=\"col-md-6\">";
                    foreach ($general_questions_rows as $row) {
                        $index = $index + 1;
                        $column = $row['columns'];

                        $id__ = "general_questions_" . $row['id'];


                        $column = str_replace("general_questions_", "", $column);
                        $column = $util_obj->captalizeEachWord(str_replace("_", " ", $column));
                        echo "<div class=\"\">
          <label>
            <input id=\"$index\" type=\"checkbox\" value=\"$id__\" onclick=\"selection($index); UncheckButton();\" />   $column
        </label>
          </div>";

                    }
                    echo " </div>";


                    echo "<div class=\"col-md-6\">";
                    foreach ($info_on_other_enterprise_rows as $row) {
                        $index = $index + 1;
                        $column = $row['columns'];

                        $id__ = "info_on_other_enterprise_" . $row['id'];

                        $column = str_replace("information_on_other_crops_", "", $column);
                        $column = $util_obj->captalizeEachWord(str_replace("_", " ", $column));
                        echo "<div class=\"\">
          <label >
            <input id=\"$index\" type=\"checkbox\" value=\"$id__\" onclick=\"selection($index); UncheckButton();\" />  $column
        </label>
          </div>";

                    }
                    echo " </div>";

                    ?>
                </div>
                <div class="modal-footer">
                    <button id="dismiss_y" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="Export2Csv();" class="btn btn-success">Confirm</button>
                </div>
            </div>
        </div>
    </div>


</div>

<div>
    <input id="va_id" type="hidden"/>
</div>

<?php include("include/footer_client.php"); ?>


<script type="text/javascript">
    jQuery(document).ready(function () {
        hideProgressBar();
        hideempty();
        checkAll();
    });

    function getVillage() {
        var sel_district = document.getElementById("sel_district");
        var sel_county = document.getElementById("sel_county");
        var sel_parish = document.getElementById("sel_parish");
        var dataset_id = document.getElementById("dataset_id_holder");
        id = dataset_id.value;
        subcounty = sel_county.value;
        district = sel_district.value;
        parish = sel_parish.value;
        $.ajax({
            type: "POST",
            url: "form_actions/setFilter.php",
            data: {id: id, village_district: district, village_subcounty: subcounty, parish: parish},
            success: function (data) {
                //window.alert(data);
                $("#sel_village").html(data);
                // $("#filter_go").click();
            }
        });

    }

    function getVAs() {

        var va_district = document.getElementById("sel_district").value;
        var va_county = document.getElementById("sel_county").value;
        var va_parish = document.getElementById("sel_parish").value;
        var va_village = document.getElementById("sel_village").value;
        var sel_va = document.getElementById("sel_va");
        var ace = document.getElementById("sel_ace").value;

        var dataset_id = document.getElementById("dataset_id_holder");
        id = dataset_id.value;
        va = sel_va.value;

        $.ajax({
            type: "POST",
            url: "form_actions/setFilter.php",
            data: {
                id: id,
                va: va,
                va_district: va_district,
                va_county: va_county,
                va_parish: va_parish,
                va_village: va_village,
                ace: ace
            },
            success: function (data) {
                //window.alert(data);
                $("#sel_va").html(data);
                // $("#filter_go").click();

            }
        });

    }

    function getParish() {
        var sel_district = document.getElementById("sel_district");
        var sel_county = document.getElementById("sel_county");
        var dataset_id = document.getElementById("dataset_id_holder");
        id = dataset_id.value;
        subcounty = sel_county.value;
        district = sel_district.value;
        $.ajax({
            type: "POST",
            url: "form_actions/setFilter.php",
            data: {id: id, parish_district: district, parish_subcounty: subcounty},
            success: function (data) {
                //window.alert(data);
                $("#sel_parish").html(data);
                getVillage();
                //$("#filter_go").click();
            }
        });

    }

    function getPagination(page) {

        var sel_va = document.getElementById("sel_va");//v2

        var sel_district = document.getElementById("sel_district");
        var sel_county = document.getElementById("sel_county");
        var sel_parish = document.getElementById("sel_parish");
        var sel_village = document.getElementById("sel_village");
        var sel_production_filter = document.getElementById("sel_production_filter");
        var sel_gender = document.getElementById("sel_gender");
        var dataset_id = document.getElementById("dataset_id_holder");
        var ace = document.getElementById("sel_ace").value;

        va = sel_va.value;
        gender = sel_gender.value;

        id = dataset_id.value;
        sel_production_id = sel_production_filter.value;
        village = sel_village.value;
        parish = sel_parish.value;
        subcounty = sel_county.value;
        district = sel_district.value;

        age_min = document.getElementById("age_min").value;
        age_max = document.getElementById("age_max").value;
        age = document.getElementById("age_").value;


        $.ajax({
            type: "POST",
            url: "form_actions/set_pagination.php",
            data: {
                id: id,
                va: va,
                age_min: age_min,
                age: age,
                age_max: age_max,
                page: page,
                district: district,
                subcounty: subcounty,
                parish: parish,
                village: village,
                gender: gender,
                ace: ace,
                sel_production_id: sel_production_id
            },
            success: function (data) {

                $("#pagination_holder").html(data);

            }
        });

    }

    function getPaginationVASearch(page) {

        showProgressBar();
        var dataset_id = document.getElementById("dataset_id_container");
        id = dataset_id.value;
        var search_id = document.getElementById("search_holder");
        search = search_id.value;
        //window.alert(1);
        $.ajax({
            type: "POST",
            url: "form_actions/set_va_pagination_search.php",
            data: {search: search, id: id, page: page},
            success: function (data) {


                $("#pagination_holder").html(data);
                hideProgressBar();
            }
        });


    }

    function getPaginationSearch(page) {

        showProgressBar();
        var sel_district = document.getElementById("sel_district");
        var sel_county = document.getElementById("sel_county");
        var sel_parish = document.getElementById("sel_parish");
        var sel_village = document.getElementById("sel_village");
        var sel_production_filter = document.getElementById("sel_production_filter");
        var sel_gender = document.getElementById("sel_gender");
        var dataset_id = document.getElementById("dataset_id_holder");

        gender = sel_gender.value;
        id = dataset_id.value;
        sel_production_id = sel_production_filter.value;
        village = sel_village.value;
        parish = sel_parish.value;
        subcounty = sel_county.value;
        district = sel_district.value;
        var search_id = document.getElementById("search_holder");
        search = search_id.value;

        $.ajax({
            type: "POST",
            url: "form_actions/set_pagination_search.php",
            data: {
                search: search,
                id: id,
                page: page,
                district: district,
                subcounty: subcounty,
                parish: parish,
                village: village,
                gender: gender,
                sel_production_id: sel_production_id
            },
            success: function (data) {
                //window.alert(data);
                hideProgressBar();
                $("#pagination_holder").html(data);

            }
        });

    }

    function getSubCounties() {
        var sel_district = document.getElementById("sel_district");
        var dataset_id = document.getElementById("dataset_id_holder");
        id = dataset_id.value;
        district = sel_district.value;
        $.ajax({
            type: "POST",
            url: "form_actions/setFilter.php",
            data: {id: id, district: district},
            success: function (data) {
                //window.alert(data);
                $("#sel_county").html(data);
                getParish();
            }
        });


    }

    $(document).ready(function () {
        dataset_type = document.getElementById("dataset_type").value;
        if (dataset_type == "Farmer") {

            getProductionFilter();
            getSubCounties();
            getVAs();
            filterData();
            getPagination(1);

        } else {
            getProductionFilter();

            getVADATA();

            getVAPagination(1);

        }

    });

    function getProductionFilter() {
        dataset_type = document.getElementById("dataset_type").value;
        var dataset_id = document.getElementById("dataset_id_holder");
        id = dataset_id.value;
        $.ajax({
            type: "POST",
            url: "form_actions/setFilter.php",
            data: {prodution_data_id: id, prodution_data: "true", dataset_type: dataset_type},
            success: function (data) {
                // window.alert(data);
                $("#sel_production_filter").html(data);

            }
        });

    }

    function filterDataPagination(page) {

        showProgressBar();
        if (document.getElementById('search_holder').value === "") {

            var sel_total_count = document.getElementById("total_count");
            var sel_per_page = document.getElementById("per_page");


            total_count = sel_total_count.value;
            per_page = sel_per_page.value;


            var sel_va = document.getElementById("sel_va");

            va = sel_va.value; //v2

            var sel_district = document.getElementById("sel_district");
            var sel_county = document.getElementById("sel_county");
            var sel_parish = document.getElementById("sel_parish");
            var sel_village = document.getElementById("sel_village");
            var sel_production_filter = document.getElementById("sel_production_filter");
            var sel_gender = document.getElementById("sel_gender");
            var dataset_id = document.getElementById("dataset_id_holder");
            var ace = document.getElementById("sel_ace").value;

            gender = sel_gender.value;
            id = dataset_id.value;
            sel_production_id = sel_production_filter.value;
            village = sel_village.value;
            parish = sel_parish.value;
            subcounty = sel_county.value;
            district = sel_district.value;

            age_min = document.getElementById("age_min").value;
            age_max = document.getElementById("age_max").value;
            age = document.getElementById("age_").value;
//            ace = document.getElementById("sel_ace").value;

            $.ajax({
                type: "POST",
                url: "form_actions/getFarmers.php",
                data: {
                    id: id,
                    va: va,//
                    age_min: age_min,
                    age: age,
                    age_max: age_max,
                    district: district,
                    subcounty: subcounty,
                    parish: parish,
                    village: village,
                    sel_production_id: sel_production_id,
                    gender: gender, page: page, per_page: per_page, total_count: total_count, ace: ace
                },
                success: function (data) {
                    //window.alert(data);
                    $("#filtered_data").html(data);
                    getPagination(page);
                    hideProgressBar();
                }
            });

        } else {
            getLoadData(page);
        }
    }

    function getVAPagination(page) {
        var dataset_id = document.getElementById("dataset_id_container");
        id = dataset_id.value;


        $.ajax({
            type: "POST",
            url: "form_actions/set_va_pagination.php",
            data: {id: id, page: page},
            success: function (data) {
                //window.alert(data);
                $("#pagination_holder").html(data);

            }
        });
    }

    function filterDataVAPagination(page) {
        showProgressBar();
        if (document.getElementById('search_holder').value === "") {

            var sel_total_count = document.getElementById("total_count");
            var sel_per_page = document.getElementById("per_page");

            total_count = sel_total_count.value;
            per_page = sel_per_page.value;


            var dataset_id = document.getElementById("dataset_id_container");


            id = dataset_id.value;


            $.ajax({
                type: "POST",
                url: "form_actions/getVAs.php",
                data: {id: id, page: page, per_page: per_page, total_count: total_count},
                success: function (data) {
                    //window.alert(data);
                    $("#filtered_data").html(data);
                    getVAPagination(page);
                    hideProgressBar();


                }
            });

        } else {

            getVAsearchResults(page);

        }

    }

    function getVADATA() {
        showProgressBar();

        var dataset_id = document.getElementById("dataset_id_container");
        origin = document.getElementById("origin").value;
        id = dataset_id.value;


        $.ajax({
            type: "POST",
            url: "form_actions/getVAs.php",
            data: {id: id, origin: origin},
            success: function (data) {
                //window.alert(origin );
                $("#filtered_data").html(data);
                hideProgressBar();
            }
        });

    }

    function filterData() {
        showProgressBar();
        var sel_va = document.getElementById("sel_va");

        var sel_district = document.getElementById("sel_district");
        var sel_county = document.getElementById("sel_county");
        var sel_parish = document.getElementById("sel_parish");
        var sel_village = document.getElementById("sel_village");
        var sel_production_filter = document.getElementById("sel_production_filter");
        var dataset_id = document.getElementById("dataset_id_holder");

        var sel_gender = document.getElementById("sel_gender");

        gender = sel_gender.value;

        id = dataset_id.value;
        sel_production_id = sel_production_filter.value;
        village = sel_village.value;
        parish = sel_parish.value;
        subcounty = sel_county.value;
        district = sel_district.value;

        age_min = document.getElementById("age_min").value;
        age_max = document.getElementById("age_max").value;
        age = document.getElementById("age_").value;
        var ace = document.getElementById("sel_ace").value;

        va = sel_va.value; //v2

        $.ajax({
            type: "POST",
            url: "form_actions/getFarmers.php",
            data: {
                id: id,
                va: va,//v2
                age_min: age_min,//
                age: age,//
                age_max: age_max,//
                district: district,
                subcounty: subcounty,
                parish: parish,
                village: village,
                sel_production_id: sel_production_id, gender: gender, ace: ace
            },
            success: function (data) {
                //window.alert(data);
                $("#filtered_data").html(data);
                hideProgressBar();
            }
        });

    }

    $("#search_holder").on("input", function (e) {

//window.alert(1);
        dataset_type = document.getElementById("dataset_type").value;
        if (dataset_type == "Farmer") {
            getLoadData(1);

        } else {

            getVAsearchResults(1);
        }

    });

    function getVAsearchResults(page) {

        var search_id = document.getElementById("search_holder");
        search = search_id.value;

        var dataset_id = document.getElementById("dataset_id_container");

        id = dataset_id.value;
        //showempty();
        $.ajax({
            type: "POST",
            url: "form_actions/getSearchVAResults.php",
            data: {
                id: id,
                search: search
            },
            success: function (data) {
                //window.alert(data);
                $("#filtered_data").html(data);

                if (data == "") {
                    showempty()
                } else {

                    hideempty()
                }
                getPaginationVASearch(page);
            }
        });

    }

    function getLoadData(page) {

        var search_id = document.getElementById("search_holder");
        search = search_id.value;
        var sel_district = document.getElementById("sel_district");
        var sel_county = document.getElementById("sel_county");
        var sel_parish = document.getElementById("sel_parish");
        var sel_village = document.getElementById("sel_village");
        var sel_production_filter = document.getElementById("sel_production_filter");
        var dataset_id = document.getElementById("dataset_id_holder");
        var ace = document.getElementById("sel_ace").value;
        var sel_gender = document.getElementById("sel_gender");

        gender = sel_gender.value;

        id = dataset_id.value;
        sel_production_id = sel_production_filter.value;
        village = sel_village.value;
        parish = sel_parish.value;
        subcounty = sel_county.value;
        district = sel_district.value;

        $.ajax({
            type: "POST",
            url: "form_actions/getSearchResults.php",
            data: {
                id: id,
                district: district,
                subcounty: subcounty,
                parish: parish,
                village: village,
                sel_production_id: sel_production_id,
                gender: gender,
                ace: ace,
                search: search
            },
            success: function (data) {
                $("#filtered_data").html(data);
                getPaginationSearch(page);
            }
        });

    }

    function Export2Csv() {


        var sel_district = document.getElementById("sel_district");
        var sel_county = document.getElementById("sel_county");
        var sel_parish = document.getElementById("sel_parish");
        var sel_village = document.getElementById("sel_village");
        var sel_production_filter = document.getElementById("sel_production_filter");
        var sel_gender = document.getElementById("sel_gender");
        var dataset_id = document.getElementById("dataset_id_holder");

        gender = sel_gender.value;
        id = dataset_id.value;
        sel_production_id = sel_production_filter.value;
        village = sel_village.value;
        parish = sel_parish.value;
        subcounty = sel_county.value;
        district = sel_district.value;

        age_min = document.getElementById("age_min").value;
        age_max = document.getElementById("age_max").value;
        age = document.getElementById("age_").value;

        var sel_va = document.getElementById("sel_va");
        va = sel_va.value; //v2

        var theForm, newInput1, newInput2, newInput3, newInput4;
        var newInput5, newInput6, newInput7;
        // Start by creating a <form>
        theForm = document.createElement('form');
        theForm.action = 'export_dataset2filteredcsv.php';
        theForm.method = 'post';
        // Next create the <input>s in the form and give them names and values
        newInput1 = document.createElement('input');
        newInput1.type = 'hidden';
        newInput1.name = 'id';
        newInput1.value = id;
        newInput2 = document.createElement('input');
        newInput2.type = 'hidden';
        newInput2.name = 'district';
        newInput2.value = district;
        newInput3 = document.createElement('input');
        newInput3.type = 'hidden';
        newInput3.name = 'subcounty';
        newInput3.value = subcounty;
        newInput4 = document.createElement('input');
        newInput4.type = 'hidden';
        newInput4.name = 'parish';
        newInput4.value = parish;
        newInput5 = document.createElement('input');
        newInput5.type = 'hidden';
        newInput5.name = 'village';
        newInput5.value = village;
        newInput6 = document.createElement('input');
        newInput6.type = 'hidden';
        newInput6.name = 'sel_production_id';
        newInput6.value = sel_production_id;
        newInput7 = document.createElement('input');
        newInput7.type = 'hidden';
        newInput7.name = 'gender';
        newInput7.value = gender;

        newInput8 = document.createElement('input');
        newInput8.type = 'hidden';
        newInput8.name = 'age_min';
        newInput8.value = age_min;


        newInput9 = document.createElement('input');
        newInput9.type = 'hidden';
        newInput9.name = 'age_max';
        newInput9.value = age_max;

        newInput10 = document.createElement('input');
        newInput10.type = 'hidden';
        newInput10.name = 'age';
        newInput10.value = age;

        newInput11 = document.createElement('input');
        newInput11.type = 'hidden';
        newInput11.name = 'va';
        newInput11.value = va;


        // Now put everything together...
        theForm.appendChild(newInput1);
        theForm.appendChild(newInput2);
        theForm.appendChild(newInput3);
        theForm.appendChild(newInput4);
        theForm.appendChild(newInput5);
        theForm.appendChild(newInput6);
        theForm.appendChild(newInput7);


        theForm.appendChild(newInput8);
        theForm.appendChild(newInput9);
        theForm.appendChild(newInput10);
        theForm.appendChild(newInput11);

        // ...and it to the DOM...
        document.getElementById('hidden_form_container').appendChild(theForm);
        // ...and submit it
        theForm.submit();


    }

    $("#sel_va").change(function () {
        filterDataPagination(1);
    });

    $("#sel_village").change(function () {
        $("#sel_va").val("all");
        filterDataPagination(1);
        getVAs();
    });

    $("#sel_gender").change(function () {
        filterDataPagination(1);
        //getVAs();
    });

    $("#sel_production_filter").change(function () {
        filterDataPagination(1);

    });

    $("#sel_parish").change(function () {
        $("#sel_va").val("all");
        filterDataPagination(1);
        getVAs();

    });

    $("#sel_county").change(function () {
        $("#sel_va").val("all");
        filterDataPagination(1);
        getVAs();

    });

    $("#sel_district").change(function () {
        $("#sel_va").val("all");
        filterDataPagination(1);
        getVAs();
    });

    $("#sel_ace").change(function () {
        filterDataPagination(1);
    });

    function predictor(farmer_id, acerage) {

        $("#acerage").val(acerage);
        $("#farmer_id").val(farmer_id);
        seedInput();
        fertilizerInput();
        herbicideInput();
        //window.alert(acerage);
    }

    function seedInput() {
        acerage = document.getElementById('acerage').value;
        seed_selected = document.getElementById('seed_selected').value;
        if (seed_selected == 1) {
            document.getElementById("seed_taken").readOnly = false;
            $("#seed_taken").val(Math.round((10) * acerage) + " KGS");

        } else {
            $("#seed_taken").val("N/A");
            document.getElementById("seed_taken").readOnly = true;
        }

    }

    function fertilizerInput() {
        acerage = document.getElementById('acerage').value;
        fertilizer_selected = document.getElementById('fertilizer_selected').value;
        if (fertilizer_selected == "") {
            document.getElementById("fertilizer_taken").readOnly = true;
            $("#fertilizer_taken").val("N/A");

        } else if (fertilizer_selected == 0) {
            document.getElementById("fertilizer_taken").readOnly = false
            $("#fertilizer_taken").val(Math.round((50) * acerage) + " KGS");

        } else if (fertilizer_selected == 1) {
            document.getElementById("fertilizer_taken").readOnly = false;
            $("#fertilizer_taken").val(Math.round((1.5) * acerage) + " LITRES");
        }
        //
    }

    function herbicideInput() {

        acerage = document.getElementById('acerage').value;
        var herbicide_selected = document.getElementById('herbicide_selected').value;

        if (herbicide_selected == "") {
            document.getElementById("herbicide_taken").readOnly = true;
            $("#herbicide_taken").val("N/A");
        }
        else if (herbicide_selected == 0) {
            document.getElementById("herbicide_taken").readOnly = false;
            $("#herbicide_taken").val(Math.round((3) * acerage) + " LITRES");
        } else if (herbicide_selected == 1) {
            document.getElementById("herbicide_taken").readOnly = false;
            $("#herbicide_taken").val(Math.round((3) * acerage) + " LITRES");

        } else if (herbicide_selected == 2) {
            document.getElementById("herbicide_taken").readOnly = false;
            $("#herbicide_taken").val(Math.round((3) * acerage) + " LITRES");
        } else if (herbicide_selected == 3) {
            document.getElementById("herbicide_taken").readOnly = false;
            $("#herbicide_taken").val(Math.round((1.5) * acerage) + " KGS");
        } else if (herbicide_selected == 4) {
            document.getElementById("herbicide_taken").readOnly = false;
            $("#herbicide_taken").val(Math.round((1.5) * acerage) + " LITRES");
        }

    }

    function recordFarmInputs() {


        var dataset_id = document.getElementById("dataset_id_container").value;
        var farmer_id = document.getElementById("farmer_id").value;

        var herbicide_e = document.getElementById("herbicide_selected");
        var herbicide_selected = herbicide_e.options[herbicide_e.selectedIndex].text;

        var herbicide_taken = document.getElementById("herbicide_taken").value;

        var seed_e = document.getElementById('seed_selected');
        var seed_selected = seed_e.options[seed_e.selectedIndex].text;

        var seed_taken = document.getElementById('seed_taken').value;


        var fertilizer_e = document.getElementById('fertilizer_selected');
        var fertilizer_selected = fertilizer_e.options[fertilizer_e.selectedIndex].text;

        var fertilizer_taken = document.getElementById('fertilizer_taken').value;


        var tractor_money = document.getElementById('tractor_taken').value;

        var cash_taken = document.getElementById('cash_taken').value;
        //window.alert(1);

        token = "farm_inputs";
        $.ajax({
            type: "POST",
            url: "form_actions/recordOutgrower.php",
            data: {
                dataset_id: dataset_id,
                farmer_id: farmer_id,
                fertilizer_selected: fertilizer_selected,
                herbicide_taken: herbicide_taken,
                seed_taken: seed_taken,
                fertilizer_taken: fertilizer_taken,
                seed_selected: seed_selected,
                herbicide_selected: herbicide_selected,
                tractor_money: tractor_money,
                cash_taken: cash_taken,
                token: token
            },
            success: function (data) {

                if (data > 0) {
                    recordAcerage();
                    $('#dismiss').click();
                }

            }
        });

    }

    function recordFarmYields() {

        var dataset_id = document.getElementById("dataset_id_container").value;
        var farmer_id = document.getElementById("farmer_id").value;

        var yield = document.getElementById('yield_value').value;

        //window.alert(1);
        token = "farm_yield";
        $.ajax({
            type: "POST",
            url: "form_actions/recordOutgrower.php",
            data: {
                dataset_id: dataset_id,
                farmer_id: farmer_id,
                yield: yield,
                token: token
            },
            success: function (data) {
                //window.alert(data);
                if (data > 0) {

                    recordAcerage();
                    $('#dismiss_y').click();
                }

            }
        });

    }

    function recordAcerage() {


        var dataset_id = document.getElementById("dataset_id_container").value;
        var farmer_id = document.getElementById("farmer_id").value;

        var acerage = document.getElementById("acerage").value;

        token = "farm_acerage";
        $.ajax({
            type: "POST",
            url: "form_actions/recordOutgrower.php",
            data: {
                dataset_id: dataset_id,
                farmer_id: farmer_id,
                acerage: acerage,
                token: token
            },
            success: function (data) {

                //window.alert(data);
            }
        });

    }

    function recordCashReturns() {


        var dataset_id = document.getElementById("dataset_id_container").value;
        var farmer_id = document.getElementById("farmer_id").value;


        var tractor_money_returned = document.getElementById('tractor_money_returned').value;
        var cash_returned = document.getElementById('cash_returned').value;

        //window.alert(1);
        token = "farm_cash_returns";
        $.ajax({
            type: "POST",
            url: "form_actions/recordOutgrower.php",
            data: {
                dataset_id: dataset_id,
                farmer_id: farmer_id,
                tractor_money_returned: tractor_money_returned,
                cash_returned: cash_returned,
                token: token
            },
            success: function (data) {
                //window.alert(data);
                if (data > 0) {
                    recordAcerage();
                    $('#dismiss_c').click();

                }

            }
        });

    }

    function showProgressBar() {
        $(".cssload-container").show();
    }

    function hideProgressBar() {
        $(".cssload-container").hide();
    }

    function showempty() {
        $("#empty-message").show();
    }

    function hideempty() {
        $("#empty-message").hide();
    }

    function UncheckButton() {
        $('#check-all').prop('checked', false);

        count = 0;

        $(".check-panel input[type=checkbox]").each(function () {
            var checkbox = $(this);

            //checkbox.attr("checked", true);
            if (!checkbox.is(":checked")) {

                //window.alert(checkbox.val());

                count = count + 1;
            }
            //checkbox.prop('checked', true);

        });

        if (count == 0) {

            $('#check-all').prop('checked', true);


        }
//window.alert(count);
    }

    function checkAll() {
        $('#check-all').prop('checked', true);

        $(".check-panel input[type=checkbox]").each(function () {
            var checkbox = $(this);

            checkbox.prop('checked', true);

            if (checkbox.is(":checked")) {
                get_export_columns(checkbox.prop('id'), checkbox.val(), "add");
            } else {
                get_export_columns(checkbox.prop('id'), checkbox.val(), "remove");
            }

        });

    }


    //get_export_columns


    function get_export_columns(index, id, token) {


        var dataset_id = document.getElementById("dataset_id_container").value;

        $.ajax({
            type: "POST",
            url: "form_actions/get_export_columns.php",
            data: {
                dataset_id: dataset_id,
                index: index,
                id: id,
                token: token
            },
            success: function (data) {
                //$("#csv_columns").html(data);
                //window.alert(data);
            }
        });

    }

    function selection(index) {
//window.alert(index);
        var checkedValue = $("#" + index).val();

        if ($("#" + index).is(":checked")) {

//window.alert("true");
            get_export_columns(index, checkedValue, "add");
        } else {
//window.alert("false");
            get_export_columns(index, checkedValue, "remove");

        }


    }

    function uncheckAll() {
        $(".check-panel input[type=checkbox]").each(function () {
            var checkbox = $(this);
            //checkbox.attr("checked", true);
            checkbox.prop('checked', false);


        });

        get_export_columns("", "", "removeAll");

    }

    $(".check-all").on("change", function () {
        if (!$(this).is(":checked")) {
            uncheckAll();
        }
        else {
            checkAll();
        }
    })
</script>

<script>
    function setminrange() {

        age_min = parseInt(document.getElementById("age_min").value);

        age_max = parseInt(document.getElementById("age_max").value);

        age = document.getElementById("age_").value;

        if (age != "" && adocument.getElementById("age_min").value != "") {
            document.getElementById("age_").value = "";
        }


        if (document.getElementById("age_min").value != "" && document.getElementById("age_max").value != "" && age_min > age_max) {
            document.getElementById("age_max").value = "";
        }

        if (document.getElementById("age_min").value != "" && document.getElementById("age_max").value == "" && age_min < 0) {
            window.alert("Minimun age must be greater than zero");
            document.getElementById("age_min").value = "";
        }
        else if (document.getElementById("age_min").value != "" && document.getElementById("age_max").value != "" && age_min > age_max) {
            window.alert("Minimun age must be less than maximum age");
            document.getElementById("age_min").value = "";
        } else if (document.getElementById("age_min").value != "" && document.getElementById("age_max").value != "" && age_min <= age_max) {
//alert("success");
        }


        filterDataPagination(1);

    }

    function setmaxrange() {

        age_min = parseInt(document.getElementById("age_min").value);

        age_max = parseInt(document.getElementById("age_max").value);

        age = document.getElementById("age_").value;

        if (age != "" && document.getElementById("age_max").value != "") {
            document.getElementById("age_").value = "";
        }

        if (document.getElementById("age_min").value == "" && document.getElementById("age_max").value != "") {
            document.getElementById("age_min").value = 0;

//alert("success");
        }
        else if (document.getElementById("age_min").value != "" && document.getElementById("age_max").value != "" && age_min > age_max || age_max == 0) {
            window.alert("Maxmum age must be greater than minimum age");
            document.getElementById("age_max").value = "";
        } else if (document.getElementById("age_min").value != "" && document.getElementById("age_max").value != "" && age_min <= age_max) {
//alert("success");
        }

        filterDataPagination(1);

    }

    function setage() {

        age_min = document.getElementById("age_min").value;

        age_max = document.getElementById("age_max").value;

        age = document.getElementById("age_").value;

        if (age != "" && (age_min != "" || age_max != "")) {
            document.getElementById("age_min").value = "";
            document.getElementById("age_max").value = "";
        }

//if(age!="" &&  parseInt(age)>0 ){

        filterDataPagination(1);
//}


    }

    function attachFile(element) {

        if (!$(element).hasClass("selected")) {
            $(element).parent().find("input[type=file]").val("");
            $(element).parent().find("input[type=file]").trigger("click");
        }

    }

    function getVa(va_id) {


        document.getElementById("va_id").value = va_id;

    }

    $(document).on("change", "input[type=file]", function () {
        var attachBtn = $(this).parent().find(".attach-file");
        ////after file has been selected

        attachBtn.html('<i class="fa fa-spin fa-spinner"></i> uploading...')
        attachBtn.addClass("selected");
        var s = getQueryVariable('token');
        var id = document.getElementById("va_id").value;

        $(this).upload("form_actions/upload_va_reciept.php", {s: s, id: id}, function (response) {
                attachBtn.removeClass("selected");
                attachBtn.html('<i class="fa fa-paperclip"></i> Add Receipt');
                //alert("Receipt added")

                swal("Done", "Receipt was added successfully!", "success");
            },
            function (progress, value) {
            }
        )
    })

    function getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }

    }

</script>
    
    