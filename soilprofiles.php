<?php //include("./include/header_client.php"); ?>
<?php include("include/header_client.php"); ?>
<style type="text/css">
    .content h2 {
        color: #ffffff;
        text-align: center;
        background: teal;
        margin-top: -.01em;
        width: 100%;
        font-size: 1em;
        border: 1px #eee solid;
        text-transform: capitalize;
        padding: 6px;
    }

    .content h4:nth-child(3) {
        color: #666;
        font-size: 1.2em;
        margin: 10px 0 !important;
        padding-left: 15px;
        font-weight: lighter;
    }

    .content h4:nth-child(2) {
        color: #666;
        font-size: 1.2em;
        margin: 10px 0 !important;
        padding-left: 15px;
        font-weight: lighter;
    }

    #dataset .thumbnail {
        height: 100px;
        width: 100%;
    }

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

    .tile {
        border-radius: 6px;
    }

    .x_panel {
        position: relative;
        width: 100%;
        margin-bottom: 10px;
        padding: 10px 17px;
        display: inline-block;
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
    }

    .x_title {
        border-bottom: 2px solid #E6E9ED;
        padding: 1px 5px 6px;
        margin-bottom: 10px;
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
        width: 100%;
        float: left;
        clear: both;
        margin-top: 5px;
    }

    .x_content h4 {
        font-size: 16px;
        font-weight: 500;
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

    #dataset {
        background: #ffffff;
        padding-top: 15px;
    }

    #data_2 {
        margin-top: -4.5em;
    }

    .move_data {
        margin-left: 2em !important;
    }

</style>

<?php include "./include/breadcrumb.php" ?>

<div class="container-fluid">
    <div class="col-md-12 col-sm-4 col-xs-12">
        <div id="outgrower_dash" class="row" style='background-color: #F2F2F2; padding-top: 15px;'>

        </div>
    </div>
</div>

<?php
include("include/preloader.php");
?>
<div><h3>Soil test results</h3></div>
<div class="container-fluid">
    <div class="col-md-12" style="box-shadow: 2px 2px 2px 2px lightgray;">
        <div id="soil_samples" class="col-lg-8" style='min-height:300px; padding-top: 10px;'>
            No samples
        </div>
        <div class="col-md-4 vertical-center" style="background: teal; color: white; text-align: justify; height: auto; padding-top: 20px;
   padding-bottom: 20px; margin-top: 10%">
            The soils are generally acidic and low in major nutrients N,P,K,Ca,Mg meaning that the use of agricultural
            lime is a must before any crop ammendment
            can be done. Liming should be done atleast 3 months before planting.

        </div>
    </div>
</div>
<div><h3>Soil test profiles</h3></div>
<div class="container-fluid">
    <div class="col-md-12" style="box-shadow: 2px 2px 2px 2px lightgray;">
        <div id="soil_sample_profiles" class="col-lg-12 row" style='min-height:300px; padding-top: 10px;'>
            No sample profiles
        </div>
    </div>
<!--    <div class="col-md-4 vertical-center" style="background: teal; color: white; text-align: justify; height: auto; padding-top: 5px;-->
<!--   padding-bottom: 5px; max-width: 100%">-->
<!--        <span> <img src=""></span>-->
<!--        <span> <img src=""></span>-->
<!--        <span> <img src=""></span>-->
<!--        <span> <img height="300px" src="http://akorion.ftfcpm.com:7325/ODKAggregate/view/binaryData?blobKey=Soil_Sample_Form%5B%40version%3Dnull+and+%40uiVersion%3Dnull%5D%2FSoil_Sample_Form%5B%40key%3Duuid%3Af3af2f16-3ce2-421b-9c1e-f7c967c86dfa%5D%2Fsoil_sample%3Aanalysis%5B%40ordinal%3D1%5D%2Fprofile_photo"></span>-->
<!--    </div>-->
</div>


<div>
    <br>
    <br>
</div>
<br><br>
<?php include("include/footer_client.php"); ?>

<!--piechart scripting-->

<script type="text/javascript">

    $(document).ready(function () {
        getLoadOutGrowerDash();
        soilSamples();
        soilProfiles();
    });

    $("#search_holder").on("input", function (e) {
        getLoadDataSets();
    });

    function soilSamples() {

        token = "special_soil_samples";
        $.ajax({
            type: "POST",
            url: "form_actions/loadTables.php", //Relative or absolute path to file
            data: {
                token: token
            },
            success: function (data) {
                if (data != null) {
                    // proceed
                    $('#soil_samples').html(data);
                    $('#dt_example').DataTable();
                } else {
                    $('#soil_samples').html("null data detected");
                }

            }
        });
    }
    function soilProfiles() {

        token = "soil_sample_profiles";
        $.ajax({
            type: "POST",
            url: "form_actions/loadTables.php", //Relative or absolute path to file
            data: {
                token: token
            },
            success: function (data) {
                if (data != null) {
                    // proceed
                    $('#soil_sample_profiles').html(data);
                    $('#soil_prof').DataTable();
                    console.log(data);
                } else {
                    $('#soil_sample_profiles').html("null data detected");
                }

            }
        });
        hideProgressBar();
    }

    function macro_nutrients_nitrogen() {

        token = "macro_nutrients_nitrogen";
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "form_actions/loadCharts.php", //Relative or absolute path to file
            data: {
                token: token
            },
            success: function (data) {
                $('#macro_nutrients_nitrogen').highcharts(data);
            }
        });
        hideProgressBar();
    }

    function macro_nutrients_phosphorous() {

        token = "macro_nutrients_phosphorous";
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "form_actions/loadCharts.php", //Relative or absolute path to file
            data: {
                token: token
            },
            success: function (data) {
                $('#macro_nutrients_phosphorous').highcharts(data);
                console.log(data);
            }
        });
        hideProgressBar();
    }

    function macro_nutrients_potassium() {

        token = "macro_nutrients_potassium";
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "form_actions/loadCharts.php", //Relative or absolute path to file
            data: {
                token: token
            },
            success: function (data) {
                $('#macro_nutrients_potassium').highcharts(data);
            }
        });
        hideProgressBar();
    }

    function getLoadOutGrowerDash() {

        token = "soil_test_dash";
        $.ajax({
            type: "POST",
            url: "form_actions/recordOutgrower.php",
            data: {token: token},
            success: function (data) {
                $("#outgrower_dash").html(data);

            }

        });

    }
    //outgrower_dash
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

</script>
