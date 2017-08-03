<style>
    .card {
        box-shadow: 0 0 0px rgba(0, 0, 0, 0.5);
        border: solid 1px #d8d8d8
    }

    .board {
        height: auto !important;
        padding: 0px;
        position: relative;
        overflow: hidden;
        max-height: 40px;
    }

    .board p, .board select {
        width: auto;
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 4px;
    }

    .board .select2_single {
        /*width: 100px;*/
        /*margin-right:30px !important;*/
        width: 11%;
    }
    .board .pro_data {
        /*width: 100px;*/
        /*margin-right:30px !important;*/
        width: 16%;
    }

    .large-text {
        font-size: 0.9em !important;
        font-weight: 100;
        background: #1B5E20;
        color: #fff;
        height: 40px;
        position: relative;
        padding: 10px;

    }

    .board a.btn, .board button{
        float:right;
        display:inline-block !important;
        height:30px;
        margin:5px;
        padding:4px 40px !important;
        margin-right:30px;

    }
    .board button{
        margin-right:5px;
        padding:4px 20px !important;
    }

    p span {
        border-radius: 30px;

    }

    /*
     * Component: Form
     * ---------------
     */
    .form-control {
        border-radius: 0;
        box-shadow: none;
        border-color: #d2d6de;
    }

    .form-control:focus {
        border-color: #3c8dbc;
        box-shadow: none;
    }

    .form-control::-moz-placeholder,
    .form-control:-ms-input-placeholder,
    .form-control::-webkit-input-placeholder {
        color: #bbb;
        opacity: 1;
    }

    .form-control:not(select) {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .form-group.has-success label {
        color: #00a65a;
    }

    .form-group.has-success .form-control {
        border-color: #00a65a;
        box-shadow: none;
    }

    .form-group.has-warning label {
        color: #f39c12;
    }

    .form-group.has-warning .form-control {
        border-color: #f39c12;
        box-shadow: none;
    }

    .form-group.has-error label {
        color: #dd4b39;
    }

    .form-group.has-error .form-control {
        border-color: #dd4b39;
        box-shadow: none;
    }

    /* Input group */
    .input-group .input-group-addon {
        border-radius: 0;
        border-color: #d2d6de;
        background-color: #fff;
    }
    .filter{
        padding: 10px;
        -webkit-box-shadow: 0px 0px 1px 1px rgba(0,0,0,0.61);
        -moz-box-shadow: 0px 0px 1px 1px rgba(0,0,0,0.61);
        box-shadow: 0px 0px 1px 1px rgba(0,0,0,0.61);
        margin-left: auto;
        margin-right: -10px;
    }

</style>
<div class="filter row">
    <div class=" board form form-group bg bg-default"
         style="background-color:#ffff;margin-bottom:5px;margin-top:5px; margin-left:1px;margin-right:1px; color:#777;">

        <p class="large-text">Location Data</p>

        <span style="margin-left:10px">District:</span>
        <select id="sel_district" onchange="getSubCounties()" class="select2_single form-control">
            <option value="all">all</option>

            <?php if (isset($_GET['token']) && $_GET['token'] != "" && isset($_GET['type']) && $_GET['type'] != "") {

                $id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
                $table = "dataset_" . $id;
                $row0s = $mCrudFunctions->fetch_rows($table, " DISTINCT TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  as biodata_farmer_location_farmer_district", " 1 ORDER BY biodata_farmer_location_farmer_district ASC");
                $temp_array = array();
                foreach ($row0s as $row) {
                    $value = $util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_district']);
                    $value = str_replace(".", "", $value);


                    if (in_array($value, $temp_array)) {

                    } else {
                        array_push($temp_array, $value);
                    }

                    // echo "<option value=\"$value\">$value</option>";
                }
                foreach ($temp_array as $temp) {
                    echo "<option value=\"$temp\">$temp</option>";
                }

                //hidden
                echo "<input id=\"dataset_id_holder\"type=\"hidden\" value=\"$id\" />";

            }
            ?>

        </select>


        <p>Sub county</p>
        <select id="sel_county" onchange="getParish()" class="select2_single form-control">
            <option value="all">all</option>
        </select>

        <p>Parish</p>
        <select id="sel_parish" onchange="getVillage()" class="select2_single form-control">
            <option value="all">all</option>
        </select>

        <p>Village</p>
        <select id="sel_village" class="select2_single form-control">
            <option value="all">all</option>
        </select>


    </div>

    <div class=" board form form-group bg bg-success"
         style="background-color:#ffff;margin-bottom:5px;margin-top:5px; margin-left:1px;margin-right:1px; color:#777;">
        <!--<div class="col-sm-12 col-md-4 col-lg-4">-->
        <p class="large-text">Production Data</p>
        <span class="">Category</span>
        <select id="sel_production_filter" onchange="" class="pro_data col-lg-4 form-control" style=" ">
            <option value="all">all</option>

        </select>
        <!--</div>-->

        <!--<div class="col-sm-12 col-md-4 col-lg-4">-->
        <p class="">Gender</p>
        <!--<div class="form-group">-->
        <select id="sel_gender" class="pro_data col-lg-4 form-control">
            <option value="all">all</option>
            <option value="male">male</option>
            <option value="female">female</option>
        </select>

        <p class="">Village Agent</p>
        <!--<div class="form-group">-->
        <select id="sel_va" class="pro_data col-lg-4 form-control">
            <option value="all">all</option>

        </select>
        <!--  </div>-->
        <!--</div>-->

    </div>
</div>