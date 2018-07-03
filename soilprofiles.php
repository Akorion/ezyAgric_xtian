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
    #soil_sample_profiles{
        background-color: lightgray; padding: 10px;
    }
    #soil_samples{
        background-color: lightgray; padding: 10px;
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


    .x_content h4 {
        font-size: 16px;
        font-weight: 500;
    }

    legend {
        padding-bottom: 7px;
    }


</style>

<?php include "./include/breadcrumb.php" ?>

<div class="container-fluid" style="padding:15px; border-radius:2px; margin-top: -20px;">
    <div class="col-md-12 col-sm-4 col-xs-12" style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>
        <div class="col-md-5">
            <h2>Profile</h2>
            <table class="table table-hover table-bordered">
                <tr>
                    <td>
                        Name
                    </td>
                    <td>
                        Jim Tersteeg
                    </td>
                </tr>
                <tr>
                    <td>
                        Farming District
                    </td>
                    <td>
                        Mubende
                    </td>
                </tr>
                <tr>
                    <td>
                        Crop
                    </td>
                    <td>
                        Coffee
                    </td>
                </tr>
                <tr>
                    <td>
                        Acreage
                    </td>
                    <td>
                        80
                    </td>

                </tr>
            </table>
        </div>
        <div class="col-md-5 vertical-center" style="text-align: justify; height: auto; margin-top: 1px; padding-top: 2px; padding-bottom: 20px">
            <h2>Recomendation</h2>
            In conclusion, as I earlier communicated 80% of land doesn’t have rocks as you were interested to
            know but the 20%  has stone particles  starting at 1.5ft  which may not pose a big threat to rooting
            system of coffee. On soil fertility, the pH is low which affects the availability and uptake of plant
            nutrients. Also  major nutrients N, P, K, Ca, Mg  are low. This means that there is a need to improve
            the nutrient content  by use of organic  manure  and inorganic fertilizer.    Use of agricultural lime can
            improve soil  pH.  Liming can be done at least  3 months before planting.  However,  coffee can thrive
            in the pH of 4.5
        </div>

        <div class="col-md-2 text-center">
            <a href="./uploads/soil-sample-results.xlsx">
                <img src="images/download.png" style="width: 40%; margin-top: 10%"/><br> Download results <br>
            </a>
            <a href="./uploads/soil-results-report.pdf">
                <img src="images/download.png" style="width: 40%;"/> <br> Download report
            </a>
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
        <div class="col-md-4 vertical-center" style="">
           <h2> Title</h2>
            <table class="table" style="margin-top: 5%;">
                <thead>
                <td> Description</td>
                <td>pH</td>
                <td>Rating</td>
                </thead>
                <tr>
                    <td> &nbsp;</td>
                    <td>< 4.5</td>
                    <td>Very Low</td>
                </tr>
                <tr>
                    <td> Strongly acid
                    </td>
                    <td>4.6-5.5
                    </td>
                    <td>Low
                    </td>
                </tr>
                <tr>
                    <td> Moderately acid
                    </td>
                    <td>5.6-6.0
                    </td>
                    <td>Medium
                    </td>
                </tr>
                <tr>
                    <td> Slightly acid
                    </td>
                    <td>6.1-6.5
                    </td>
                    <td>Medium
                    </td>
                </tr>
                <tr>
                    <td>Neutral
                    </td>
                    <td>6.6-7.2
                    </td>
                    <td>High
                    </td>
                </tr>
                <tr>
                    <td>Slightly alkaline
                    </td>
                    <td>7.3-7.8
                    </td>
                    <td>High
                    </td>
                </tr>
                <tr>
                    <td>Moderately alkaline
                    </td>
                    <td>7.9-8.4
                    </td>
                    <td>Very high
                    </td>
                </tr>
                <tr>
                    <td>Strongly alkaline
                    </td>
                    <td>8.5-9.0
                    </td>
                    <td>Very high
                    </td>
                </tr>
                <tr>
                    <td>Very strongly alkaline
                    </td>
                    <td>> 9.0
                    </td>
                    <td>Very high
                    </td>
                </tr>


            </table>

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
