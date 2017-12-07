<style>
    .card{
        box-shadow:0 0 0px rgba(0,0,0,0.5);
        border:solid 1px #d8d8d8}
    .board{
        height:auto !important;
        padding:0px;
        position:relative;
        overflow:hidden;
        max-height:40px;
    }

    .board p, .board select {
        width:auto;
        display:inline-block;
        margin-right:10px;
        margin-bottom:4px;
    }

    .board select{
        width:150px;
        margin-right:30px !important;}

    .large-text{
        font-size:0.9em !important;
        font-weight:100;
        background:#1B5E20;
        color:#fff;
        height:40px;
        position:relative;
        padding:10px;

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

    p span{
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
        border-bottom: none !important;
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

    .containers {
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    #age input{
        width: 6em;
    }
</style>

<div class="containers">
    <div class="col-md-12 col-sm-4 col-xs-12"
         style="border: thin solid black; padding: 10px 15px 25px 15px; border-radius: 5px;">
        <div class="row">
            <!-- Dataset(s) -->
            <div class="col-sm-2">
                <label for="inputPassword3" class="control-label">Datasets</label>
                <div>
                    <select id="sel_datasets" class="select2_single form-control">

                    </select>
                </div>
            </div>
            <!-- Dataset(s) -->

            <!-- District -->
            <div class="col-sm-2">
                <label for="inputPassword3" class="control-label">District</label>
                <div>
                    <select id="sel_district" onchange="loadSubCounty();" class="select2_single form-control">
                        <option value="all">all</option>

                    </select>
                </div>
            </div>
            <!-- District -->

            <!--subcounty-->
            <div class="col-sm-2">
                <label for="inputPassword3" class="control-label">Sub-county</label>
                <div>
                    <select id="sel_sub_county" onchange="loadParish();" class="select2_single form-control">
                        <option value="all">all</option>

                    </select>
                </div>
            </div>
            <!--subcounty-->

            <!--parish-->
            <div class="col-sm-2">
                <label for="inputPassword3" class="control-label">Parish</label>
                <div>
                    <select id="sel_parish" onchange="loadVillage();" class="select2_single form-control">
                        <option value="all">all</option>

                    </select>
                </div>
            </div>
            <!--parish-->

            <!--village-->
            <div class="col-sm-2">
                <label for="inputPassword3" class="control-label">Village</label>
                <div>
                    <select id="sel_village" class="select2_single form-control">
                        <option value="all">all</option>

                    </select>
                </div>
            </div>
            <!--village-->

            <!--production data-->
            <div class="col-sm-2">
                <label for="inputPassword3" class="control-label">Production Data</label>
                <div>
                    <select id="sel_production_data" onchange="loadProductionOptions();"
                            class="select2_single form-control">
                        <option value="all">all</option>

                    </select>
                </div>
            </div>
            <!--production data-->

        </div>
        <div class="row">

            <!--production data-->
            <div class="col-sm-2">
                <label style=" padding-top:10px;" for="inputPassword3" class="control-label">Gender</label>
                <div>
                    <select id="sel_gender" class="select2_single form-control">
                        <option value="all">all</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>

                    </select>
                </div>
            </div>
            <!--production data-->
            <div class="col-sm-2 hide">
                <label style=" padding-top:10px;" for="inputPassword3" class="control-label">Village
                    Agent</label>
                <div>
                    <select id="sel_va" class="select2_single form-control">
                        <option value="all">all</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-2 hide">
                <label style=" padding-top:10px;" class="control-label">Age Range</label>
                <div class="row" id="age">
                    <div class="col-sm-2">
                        <input onblur="setminrange()" id="age_min" type="number" class="form-control" name="min"
                               placeholder="Min" style="background-color:#fff !important;
       border: 1px solid #d2d6de;border-radius: 4px;height:2em;">
                    </div>
                    <div class="col-sm-2" style="margin-left: 4em;">
                        <input onblur="setmaxrange()" id="age_max" type="number" name="max" class="form-control"
                               placeholder="Max" style="background-color:#fff !important;
       border: 1px solid #d2d6de;border-radius: 4px;height:2em;">
                    </div>
                </div>
            </div>

            <div class="col-sm-2 hide">
                <label style=" padding-top:10px;" class="control-label">Age</label>
                <input oninput="setage()" onblur="setage()" id="age_" type="number" class="form-control"
                       name="min" placeholder="Age" style="background-color: #fff; border: 1px solid #d2d6de; border-radius: 4px;height:2em;">
            </div>

            <div class="col-sm-2">
                <label class="control-label" style=" padding-top:10px;"> Date From</label>
                <input id="date_from" type="date" class="form-control" style="background-color:#fff !important;
border: 1px solid #d2d6de;border-radius: 4px;height:2em;">
            </div>
            <div class="col-sm-2">
                <label class="contrl-label" style=" padding-top:10px;">Date To</label>
                <input class="form-control" type="date" id="date_to" style="background-color:#fff !important;
border: 1px solid #d2d6de;border-radius: 4px;height:2em;">
            </div>

            <div class="col-sm-2 hide">
                <label class="control-label" style=" padding-top:10px;">Supply Period</label>
                <select id="date_range_" class="select2_single form-control">
                    <option value="1 week">1 Week</option>
                    <option value="1 month">1 Month</option>
                    <option value="1 Year">1 Year</option>
                </select>
            </div>

            <div class="col-sm-2">
                <label style=" padding-top:10px;" class="control-label">Date</label>
                <input type="date" class="form-control" style="background-color: #fff; border: 1px solid #d2d6de; border-radius: 4px;height:2em;" id="date_" >
            </div>


            <div class="col-sm-2 hide">
                <label style=" padding-top:10px;" class="control-label ">Date Range</label>
                <input type="text" name="daterange" value="01/01/2015 - 01/31/2015"/>
            </div>

        </div>

    </div>
</div>