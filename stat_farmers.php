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
    .graph_box{
        padding: 20px; -moz-box-shadow: 0 0 5px #999; -webkit-box-shadow: 0 0 5px #999; box-shadow: 0 0 5px #999;
    }


</style>

<?php include("include/header_client.php"); ?>
<?php include "include/breadcrumb.php" ?>
<div class="container farmer">
    <!--include filter here-->
    <?php include("include/stats_filter.php");
    ##include("include/filtered.php");
    ?>

    <?php include("include/preloader.php");
    echo "
  <div class=\"right print_export\">
  <a class=\"btn btn-success btn-fab btn-raised mdi-action-class\" onclick=\"Export2Csv();\"  title=\" Export CSV\"></a>
</div>";


    ?>
    <div class="row stat ">
        <div id="first_table" class="col-md-8">

        </div>
    </div>
    <div class="row">
        <div class="col-md-10" style="margin-top:45px;">
            <!-- <div class="well">
  <div id="line-location" style="margin-left:15px;">
   <?php //include("include/empty.php");?>
  </div></div>-->
        </div>
        <div class="col-md-2">
            <div class="row">
                <div class="col-md-12" style="margin-top:-45px;">
                    <div id="donut-gender"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="margin-top:-180px;">
                    <div id="donut-prodction"></div>
                </div>
            </div>
        </div>


    </div><!--end of stat row-->

    <div class="row" style="margin-top:-5px;">
        <!--<h4 id="heading" class="text-center hide">Graph</h4>id="line_graph"-->
        <div class="col-md-8" style=" padding: 20px; -moz-box-shadow: 0 0 5px #999;
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;">
            <div id="line_graph" style=" height:500px">
            </div>
        </div>


        <div class="col-md-4">

            <div class="row">
                <div class="col-md-12">
                    <div class="graph_box h4" style="margin-top: 0px;background: rgba(10,134,48,0.8); color: white;">
                       <center> Total farmers : <span id="total_farmers"></span></center>
                    </div>
                    <br>
                    <div id="gender_graph" class="graph_box" style="min-height: 450px;">
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!--  <div id="production_graph" style=" height:250px"  >-->
                    <!--  </div>-->
                </div>
            </div>
        </div>


    </div>


</div>


<div id="hidden_form_container">
</div>
<!--end of main container-->

<?php include("include/footer_client.php"); ?>

<script type="text/javascript">

    $(document).ready(function () {
        hideProgressBar();
        //hideempty();
        getDatasets();

    });

    function getDatasets() {

        $.ajax({
            type: "POST",
            url: "form_actions/set_starts_filter.php",
            data: {datasets: "true"},
            success: function (data) {
                $("#sel_datasets").html(data);
                loadDistricts();
                getProductionFilter();
                getVAs();
                filterDATA();
            }
        });

    }

    function loadSubCounty() {

        var dataset_id = document.getElementById("sel_datasets");
        id = dataset_id.value;
        var district_ = document.getElementById("sel_district");
        district = district_.value;
        //window.alert(id);
        $.ajax({
            type: "POST",
            url: "form_actions/set_starts_filter.php",
            data: {district_subcounty: district, id: id},
            success: function (data) {
                // window.alert(data);
                $("#sel_sub_county").html(data);
                loadParish();
            }
        });

    }


    function loadParish() {

        var dataset_id = document.getElementById("sel_datasets");
        id = dataset_id.value;
        var country_ = document.getElementById("sel_sub_county");
        country = country_.value;

        var district_ = document.getElementById("sel_district");
        district = district_.value;
        //window.alert(id);
        $.ajax({
            type: "POST",
            url: "form_actions/set_starts_filter.php",
            data: {parish_district: district, parish_subcounty: country, id: id},
            success: function (data) {
                //window.alert(data);
                $("#sel_parish").html(data);
                loadVillage();
            }
        });

    }

    function getVAs() {
        var sel_va = document.getElementById("sel_va");

        var dataset_id = document.getElementById("sel_datasets");
        id = dataset_id.value;
        va = sel_va.value;

        $.ajax({
            type: "POST",
            url: "form_actions/set_starts_filter.php",
            data: {id: id, va: va},
            success: function (data) {

                $("#sel_va").html(data);

            }
        });

    }

    function loadVillage() {

        var dataset_id = document.getElementById("sel_datasets");
        id = dataset_id.value;
        var country_ = document.getElementById("sel_sub_county");
        country = country_.value;
        var district_ = document.getElementById("sel_district");
        district = district_.value;
        var parish_ = document.getElementById("sel_parish");
        parish = parish_.value;
        //window.alert(id);
        $.ajax({
            type: "POST",
            url: "form_actions/set_starts_filter.php",
            data: {village_district: district, village_subcounty: country, village_parish: parish, id: id},
            success: function (data) {
                // window.alert(data);
                $("#sel_village").html(data);
            }
        });
    }


    function loadDistricts() {

        var dataset_id = document.getElementById("sel_datasets");
        id = dataset_id.value;
        //window.alert(id);
        $.ajax({
            type: "POST",
            url: "form_actions/set_starts_filter.php",
            data: {district: id},
            success: function (data) {
                // window.alert(data);
                $("#sel_district").html(data);
                loadSubCounty();
            }
        });

    }
    function Export2Csv() {

        var dataset_id = document.getElementById("sel_datasets");
        id = dataset_id.value;
        var parish_ = document.getElementById("sel_parish");
        parish = parish_.value;
        var country_ = document.getElementById("sel_sub_county");
        country = country_.value;
        var district_ = document.getElementById("sel_district");
        district = district_.value;
        var village_ = document.getElementById("sel_village");
        village = village_.value;
        ////
        var production_ = document.getElementById("sel_production_data");
        production = production_.value;
        var gender_ = document.getElementById("sel_gender");
        gender = gender_.value;

        age_min = document.getElementById("age_min").value;
        age_max = document.getElementById("age_max").value;
        age = document.getElementById("age_").value;

        va = document.getElementById('sel_va').value;
        var theForm, newInput1, newInput2, newInput3, newInput4;
        var newInput5, newInput6, newInput7, newInput8, newInput9, newInput10, newInput11;
        // Start by creating a <form>
        theForm = document.createElement('form');
        theForm.action = 'export_stat2csv.php';
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
        newInput3.name = 'country';
        newInput3.value = country;
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
        newInput6.name = 'production';
        newInput6.value = production;
        newInput7 = document.createElement('input');
        newInput7.type = 'hidden';
        newInput7.name = 'gender';
        newInput7.value = gender;

        /*

         age_min  = document.getElementById("age_min").value;
         age_max  = document.getElementById("age_max").value;
         age = document.getElementById("age_").value;
         */
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
        $('#hidden_form_container').empty();
        document.getElementById('hidden_form_container').appendChild(theForm);
        // ...and submit it
        theForm.submit();


    }
    //function Print(){window.alert(1);}

    function PrintPreview() {


        var dataset_id = document.getElementById("sel_datasets");
        id = dataset_id.value;
        var parish_ = document.getElementById("sel_parish");
        parish = parish_.value;
        var country_ = document.getElementById("sel_sub_county");
        country = country_.value;
        var district_ = document.getElementById("sel_district");
        district = district_.value;
        var village_ = document.getElementById("sel_village");
        village = village_.value;
        ////
        var production_ = document.getElementById("sel_production_data");
        production = production_.value;
        var gender_ = document.getElementById("sel_gender");
        gender = gender_.value;


        var theForm, newInput1, newInput2, newInput3, newInput4;
        var newInput5, newInput6, newInput7;
        // Start by creating a <form>
        theForm = document.createElement('form');
        theForm.action = 'print.php';
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
        newInput3.name = 'country';
        newInput3.value = country;
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
        newInput6.name = 'production';
        newInput6.value = production;
        newInput7 = document.createElement('input');
        newInput7.type = 'hidden';
        newInput7.name = 'gender';
        newInput7.value = gender;

        // Now put everything together...
        theForm.appendChild(newInput1);
        theForm.appendChild(newInput2);
        theForm.appendChild(newInput3);
        theForm.appendChild(newInput4);
        theForm.appendChild(newInput5);
        theForm.appendChild(newInput6);
        theForm.appendChild(newInput7);
        // ...and it to the DOM...
        $('#hidden_form_container').empty();
        document.getElementById('hidden_form_container').appendChild(theForm);
        // ...and submit it
        theForm.submit();
    }
    function getProductionFilter() {

        var dataset_id = document.getElementById("sel_datasets");
        id = dataset_id.value;
        //$("#sel_production_data").html("<option value=\"all\">all</option>");
        $.ajax({
            type: "POST",
            url: "form_actions/set_starts_filter.php",
            data: {prodution_data_id: id, prodution_data: "true"},
            success: function (data) {
                // window.alert(data);
                $("#sel_production_data").html(data);
            }
        });

    }

    function filterDATA() {
        showProgressBar();
        // hideempty();
        var dataset_id = document.getElementById("sel_datasets");
        id = dataset_id.value;
        var parish_ = document.getElementById("sel_parish");
        parish = parish_.value;
        var country_ = document.getElementById("sel_sub_county");
        country = country_.value;
        var district_ = document.getElementById("sel_district");
        district = district_.value;
        var village_ = document.getElementById("sel_village");
        village = village_.value;

        var va = document.getElementById('sel_va').value;

        var production_ = document.getElementById("sel_production_data");
        production = production_.value;
        var gender_ = document.getElementById("sel_gender");
        gender = gender_.value;

        age_min = document.getElementById("age_min").value;
        age_max = document.getElementById("age_max").value;
        age = document.getElementById("age_").value;


        $.ajax({
            type: "POST",
            url: "form_actions/get_stats.php",
            data: {
                id: id,
                va: va,
                age_min: age_min,
                age_max: age_max,
                age: age,
                district: district,
                country: country,
                parish: parish,
                village: village,
                production: production,
                gender: gender
            },
            success: function (data) {
                // window.alert(va);
                FormLineGraph(id, va, age_min, age_max, age, district, country, parish, village, production, gender);
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


</script>


<script type="text/javascript">


    function FormLineGraph(id, va, age_min, age_max, age, district, country, parish, village, production, gender) {

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "form_actions/get_line_graph2.php", //Relative or absolute path to file
            data: {
                id: id,
                va: va,
                age_min: age_min,
                age_max: age_max,
                age: age,
                district: district,
                country: country,
                parish: parish,
                village: village,
                production: production,
                gender: gender
            },
            success: function (data) {
                if (data != 0) {
                    $('#line_graph').highcharts(data);

                    FormGenderGraph(id, va, age_min, age_max, age, district, country, parish, village, production, gender);
                    FormProductionGraph(id, va, age_min, age_max, age, district, country, parish, village, production, gender);

                }
                else {
                    $('#line_graph').html("<p>No data</p>");
                    $('#gender_graph').html("<p></p>");
                    $('#production_graph').html("<p></p>");

                }
                hideProgressBar();

            }
        });

    }


    function FormGenderGraph(id, va, age_min, age_max, age, district, country, parish, village, production, gender) {

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "form_actions/get_gender_graph.php", //Relative or absolute path to file
            data: {
                id: id,
                va: va,
                age_min: age_min,
                age_max: age_max,
                age: age,
                district: district,
                country: country,
                parish: parish,
                village: village,
                production: production,
                gender: gender
            },
            success: function (data) {

                $('#gender_graph').highcharts(data);
//                console.log(data.series);
                var male = data.series[0].data[0][1];
                var female = data.series[0].data[1][1];

                    $('#total_farmers').html(male+female);
//                    if($('#total_farmers').val() < 1){
//                        var ttl_farmers = data.series[0].data[2][1];
//                        $('#total_farmers').html(ttl_farmers);
//                    }
//                console.log(male+female);

            }
        });

    }

    function FormProductionGraph(id, va, age_min, age_max, age, district, country, parish, village, production, gender) {


        $.ajax({
            type: "POST",
            dataType: "json",
            url: "form_actions/get_production_graph.php", //Relative or absolute path to file
            data: {
                id: id,
                va: va,
                age_min: age_min,
                age_max: age_max,
                age: age,
                district: district,
                country: country,
                parish: parish,
                village: village,
                production: production,
                gender: gender
            },
            success: function (data) {

                $('#production_graph').highcharts(data);

            }
        });


    }


    $("#sel_datasets").change(function () {
        $('#sel_va').val("all");
        loadDistricts();
        getProductionFilter();
        getVAs();
        filterDATA();

    });

    $("#sel_village").change(function () {
        filterDATA();
    });

    $("#sel_gender").change(function () {
        filterDATA();
    });
    $("#sel_production_data").change(function () {
        filterDATA();
    });
    $("#sel_parish").change(function () {
        filterDATA();
    });

    $("#sel_sub_county").change(function () {
        filterDATA();
    });

    $("#sel_district").change(function () {
        filterDATA();
    });

    $("#sel_va").change(function () {
        //window.alert(2);
        filterDATA();
    });

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

        filterDATA();
//filterDataPagination(1);

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

//filterDataPagination(1);
        filterDATA();

    }


    function setage() {

        age_min = document.getElementById("age_min").value;

        age_max = document.getElementById("age_max").value;

        age = document.getElementById("age_").value;

        if (age != "" && (age_min != "" || age_max != "")) {
            document.getElementById("age_min").value = "";
            document.getElementById("age_max").value = "";
        }

        filterDATA();
//if(age!="" &&  parseInt(age)>0 ){

//filterDataPagination(1);
//}


    }

</script>