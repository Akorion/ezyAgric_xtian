<style>
    #center {
        text-align: center;
        background-color: #fff;

    }

    table, tr, thead, th {
        color: orange;
    }

    th {
        color: orange;
    }

    .card h6 {
        min-width: 120px;
        width: 200px;
        padding-left: 10px;
        color: cadetblue !important;
    }

    .prodn h6 {
        width: 300px;
    }

    .prodn p {
        color: #777;
        max-width: 300px;
    }

    .card p {
        color: #777;
        width: 230px;
    }

    .card {
        padding-bottom: 10px;
        padding-top: 0px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    h5 {
        border: none !important;
        color: #fff !important;
        margin: 0 !important;
        background-color: #A7B6B1 !important;
    }

    .card:nth-child(2) h5 {
        color: #fff !important;
        margin: 0 !important;
        background-color: #2697B6 !important;
    }

    .card:nth-child(1) h5 {
        color: #fff !important;
        margin: 0 !important;
        background-color: #72B678 !important;
    }

    hr {
        border-width: 0;
        display: block;

        opacity: 0.2;
        margin: 0 10px !important;
        width: 97% !important;
    }

    .card hr:last-child {
        display: none;
    }

    h3 {
        font-size: 0.9em !important;
        margin: 0px !important;
    }

    #map-canvas {
        background: #f7f7f7;
        border: 1px #eee solid;
    }

    .card-image {
        position: relative;
    }

    #gardens {
        min-height: 100px;
        width: 200px;
        background: rgba(0, 0, 0, 0.4);
        position: absolute;
        right: 10px;
        top: 10px;
        border-radius: 4px;
        overflow: hidden;

    }

    .light-table {
        width: 100%
    }

    .light-table th, .light-table td {
        padding: 3px;
        color: #fff;
    }

    .light-table thead tr {
        background: #fff !important;

    }

    .light-table thead tr th {
        color: #888;

    }

    .light-table tbody tr:nth-child(even) {
        background: rgba(0, 0, 0, 0.2) !important;
    }

    .light-table td:nth-child(even), .light-table th:nth-child(even) {
        background: rgba(0, 0, 0, 0.4) !important;
        color: #fff;
    }

    .right {
        float: right;
        margin: 0 30px;

    }

    .btn-fab {
        position: fixed !important;
        right: 70px;
        bottom: 110px;
        z-index: 100;
    }

    .seed {
        width: 25em !important;
    }

    .striped thead {
        background: #888;
        padding: 5px;
    }

    .striped thead tr th {
        color: #999 !important;
    }

    .striped tr td thead {
        padding: 15px;
    }

    .striped tr td {
        color: #888;
        font-size: 11px;
    }

</style>
<?php include("include/header_client.php"); ?>
<?php include "include/breadcrumb.php" ?>
<div class="container">
    <legend>
        <h3 class="text-center">
            <?php
            $client_id = $_SESSION['client_id'];
            if (isset($_GET['type']) && $_GET['type'] != "") {
                if ($_GET['type'] == "Farmer") {
                    echo "Farmer details";
                }
                if ($_GET['type'] == "VA") {
                    echo "VA details";
                }
            } ?>
        </h3>
    </legend>
    <!--map row-->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="">
                <div class="card-image">
                    <div class="" id="map-canvas"></div>
                    <!--map canvas-->
                    <div class="" id="gardens"></div>
                    <!-- gardens-->
                </div>
            </div>
        </div>
    </div>
    <?php


    if (isset($_GET['token']) && $_GET['token'] != "" && isset($_GET['s']) && $_GET['s'] != "" && isset($_GET['type']) && $_GET['type'] != "") {

        $type = $_GET['type'];
        $dataset_id = $util_obj->encrypt_decrypt("decrypt", $_GET['s']);
        $id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);

        echo "<input id=\"dataset_id\" type=\"hidden\" value=\"$dataset_id\" />";
        echo "<input id=\"id\" type=\"hidden\" value=\"$id\" />";
        echo "<input id=\"type\" type=\"hidden\" value=\"$type\" />";

        $table = "dataset_" .$dataset_id;
        $columns = "*";
        $where = " id='$id' ";
        $rows = $mCrudFunctions->fetch_rows($table, $columns, $where);

        if (sizeof($rows) == 0) {
            echo "details Unavailable";
        } else {

            $id = $rows[0]['id'];

            if ($type == "Farmer") {
                $latitude = $util_obj->remove_apostrophes($rows[0]['biodata_farmer_location_farmer_home_gps_Latitude']);
                $longitude = $util_obj->remove_apostrophes($rows[0]['biodata_farmer_location_farmer_home_gps_Longitude']);
                $picture = $util_obj->remove_apostrophes($rows[0]['biodata_farmer_image']);
                $uuid = $util_obj->remove_apostrophes($rows[0]['meta_instanceID']);

                echo "<input id=\"picture\" type=\"hidden\" value=\"$picture\" />";
                echo "<input id=\"uuid\" type=\"hidden\" value=\"$uuid\" />";

///////////////////////////////////////////// lat_long_pic starts
                echo "<div class=\" row\">
                        <input type=\"hidden\" id=\"latitude\" value=\"$latitude\" />
                        <input type=\"hidden\" id=\"longitude\" value=\"$longitude\" />
                        <div class=\"col-sm-4 col-md-4 col-lg-4\">
                         <span class=\"img\" ><img src=\"$picture\" class=\" on_map\"></span>
                        </div>
                        
                        <div class=\"hide col-sm-5 col-md-5 col-lg-5\">
                        <h6>GPs location: <span style=\"color:#999\">$latitude , $longitude</span></h6>
                        
                        </div>";
                echo "
                          <div class=\"right print_export\">
                          <a class=\"btn btn-danger btn-fab btn-raised mdi-action-print\" onclick=\"PrintPreview();\" ></a>
                        </div>";


                echo "</div>";
////////////////////////////////////////////////// lat_long_pic endss

                $table = "dataset_" . $dataset_id;
                $columns = "*";
                $where = " id='$id' ";
                $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);
//print_r($rows2);
/////////////////////////////////////////////personal data_starts
                echo "<div class=\"row data1\">
                <div class=\"col-sm-12 col-me-5 col-lg-5\">
                <div class=\"card\">
                <h5 class=\"\" style=\"\">Personal Profile</h5>";

                foreach ($rows2 as $row) {

                    $district = $row['biodata_farmer_location_farmer_district'];
                    $subcounty = $row['biodata_farmer_location_farmer_subcounty'];
                    $parish = $row['biodata_farmer_location_farmer_parish'];
                    $village = $row['biodata_farmer_location_farmer_village'];
                    $first_name = $row['biodata_first_name'];
                    $last_name = $row['biodata_last_name'];
                    $gender = $row['biodata_gender'];
                    $gender = $util_obj->capitalizeName($gender);
                    $date = $row['biodata_age'];
                    $age = explode('/',$date);
                    $farmer_age = 2017 - $age[2];
                    $phone = $row['biodata_phonenumber'];
                    $coop = $row['biodata_cooperative_name'];

//                    $age = DateTime::createFromFormat("d/m/Y", $date);
//                    $farmer_age = 2017 - $age->format("Y");

                    echo "<h6>Name:</h6>
                          <p class=\"align\">$first_name $last_name</p><hr/>";
                    echo "<h6>Phone_number :</h6>
                          <p class=\"align\">+256 $phone</p><hr/>";
                    echo "<h6>Gender:</h6>
                          <p class=\"align\">$gender</p><hr/>";
                    echo "<h6>Age:</h6>
                          <p class=\"align\">$farmer_age</p><hr/>";
                    echo "<h6>District:</h6>
                          <p class=\"align\">$district</p><hr/>";
                    echo "<h6>Subcounty:</h6>
                          <p class=\"align\">$subcounty</p><hr/>";
                    echo "<h6>Parish:</h6>
                          <p class=\"align\">$parish</p><hr/>";
                    echo "<h6>Village:</h6>
                          <p class=\"align\">$village</p><hr/>";
                    echo "<h6>Cooperative:</h6>
                          <p class=\"align\">$coop</p><hr/>";

                }

                echo "</div>";
/////////////////////////////////////////////other data_starts
//echo '<div class="col-sm-12 col-me-5 col-lg-5">';
                echo "<div class=\"card\">
<h5 class=\"\">Others</h5>";

                foreach ($rows2 as $row) {
                    $bank_name = $row['sacco_branch_name'];
                    $sacco = $row['other_banks'];
                    $bank_branch = $row['bank_branch_name'];
                    $account_number = $row['account_number'];
                    $ac_name = $row['account_name'];
                    $mm_acount = $row['mobi_sacco_acc_number'];

                    echo "<h6>SACCO Branch:</h6>
                          <p class=\"align\">$bank_name</p><hr/>";
                    echo "<h6>A/c name:</h6>
                          <p class=\"align\">$ac_name</p><hr/>";
                    echo "<h6>A/c number:</h6>
                          <p class=\"align\">$account_number</p><hr/>";
                    echo "<h6>Mobile money a/c:</h6>
                          <p class=\"align\">$mm_acount</p><hr/>";
                }

                echo "<br>";

                $table="general_questions";
                $columns="*";
                $where=" dataset_id='$dataset_id' ";
                $rows6= $mCrudFunctions->fetch_rows($table,$columns,$where);

                foreach($rows6 as $row){
                    $column=$row['columns'];

                    $value=$rows[0][$column];
                    $value=$util_obj->captalizeEachWord($value);
                    $string="general_questions_";
                    $string=str_replace($string,"",$column);
                    $string=str_replace("_"," ",$string);
                    $lable=$util_obj->captalizeEachWord($string);
                    if($value!=null){

                        echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
                    }
                }

                echo "</div>
</div>
";
/////////////////////////////////////////////other data_ends

/////////////////////////////////////////////production data_starts
            echo "
                <div class=\" col-sm-12 col-md-7 col-lg-7\">
                    <div class=\"card prodn\">
                  <h5 class=\"\">Periodic Milk Transactional Data</h5>                  
                  <br>
                  <p style='padding-left: 10px;'><b> Milk Supplied(Kgs) </b></p><p style='padding-left: 60px'><b>Date</b></p><b style='padding-left: 70px'>Description</b><hr/>
                  ";
                $milk_data=file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=2017-10-25&end_date=2017-11-14");
                $milk_periodic_data = json_decode($milk_data);
                foreach ($milk_periodic_data as $milk_supply){
                    $milk_quantity = $milk_supply->milk_amount;
                    $supply_date = new DateTime($milk_supply->created_at);
                    $date = $supply_date->format('d/m/Y');
                    echo "
                        <p style='padding-left: 40px;'>$milk_quantity </p><p style='padding-left: 60px'> $date </p></hr>
                        ";
                }
                echo " 
                </div>
                </div>";

  ////////////////     //////////////////

                echo "
                  <div class=\" col-sm-12 col-md-7 col-lg-7\">
                    <div class=\"card prodn\">
                  <h5 class=\"\">Dairy profile</h5>
                  <div class=\"caption\">";
                $accessed_loan_status = "no";
                foreach ($rows2 as $row) {

                    $breed = $row['cattle_breed'];
                    $other_breeds = $row['other_breed_of_cows'];
                    $cows_number = $row['number_of_cows'] + $row['number_of_calves'] + $row['number_of_bulls'];
                    $lactating_cows = $row['lactating_cows'];
                    $average_lactating = $row['early_lactation'];
                    $cows_expecting = $row['cows_expecting'];

                    $milk_morning = $row['milk_litres_morning'];
                    $milk_evening = $row['milk_litres_evening'];
                    $unit_price = $row['price_per_litre'];
                    $transport_mode = $row['mode_of_transport'];
                    $other_tp_mode = $row['other_transport_mode'];
                    if($other_tp_mode == "") $other_tp_mode = "None";

                    $accessed_loan_status = $row['accessed_loan'];
                    $loan_amount = $row['loan_amount'];

                    echo "<h6>Main breed of cows:</h6>
                          <p class=\"align\"> $breed</p><hr/>";
//                    echo "<h6>Other Breeds:</h6>
//                          <p class=\"align\">$other_breeds</p><hr/>";
                    echo "<h6>Number of cows:</h6>
                          <p class=\"align\">$cows_number</p><hr/>";
                    echo "<h6>Lactating Cows:</h6>
                          <p class=\"align\">$lactating_cows</p><hr/>";
                    echo "<h6>Early lactating Cows:</h6>
                          <p class=\"align\">$average_lactating</p><hr/>";
                    echo "<h6>Cows Expecting:</h6>
                          <p class=\"align\">$cows_expecting</p><hr/>";

                    echo "<h6>Unit price of milk:</h6>
                          <p class=\"align\">$unit_price UGX</p><hr/>";
                    echo "<h6>Milk transportation mode:</h6>
                          <p class=\"align\">$transport_mode</p><hr/>";
//                    echo "<h6>Other transport modes:</h6>
//                          <p class=\"align\">$other_tp_mode</p><hr/>";
//                    echo "<h6>Accessed_loan:</h6>
//                          <p class=\"align\">".$util_obj->capitalizeName($accessed_loan_status)."</p><hr/>";
//                    if($accessed_loan_status == "yes"){
//                        echo "<h6 id=\"loan_status\">Loan Amount:</h6>
//                          <p class=\"align\" id=\"loan_status\">".number_format($loan_amount)." Ugx</p><hr/>";
//                    }
                }

                echo "</div>                
                  </div>
                </div>";

                echo "<div class=\"col-sm-12 col-md- col-lg-7\" style=\"margin-bottom:100px;\">
                    <div class=\"card prodn\">
                    <h5 class=\"\">Production Data</h5>";

                $table = "production_data";
                $columns = "*";
                $where = " dataset_id='$dataset_id' ";
                $rows4 = $mCrudFunctions->fetch_rows($table, $columns, $where);
                $enterprise = "";
                foreach ($rows4 as $row) {
                    $column = $row['columns'];
                    $enterprise = $row['enterprise'];
                    $value = $rows[0][$column];
                    $value = $util_obj->captalizeEachWord($value);
                    $string = $enterprise . "_production_data_";
                    $string = str_replace($string, "", $column);
                    $string = str_replace("_", " ", $string);
                    $lable = $util_obj->captalizeEachWord($string);
                    if ($value != null) {

                        echo "<h6 class=\"trim\">$lable:</h6>
  <p>$value</p><hr/>";

                    }
                }
                echo "<br>";

                foreach ($rows2 as $row) {
                    $acaricides = number_format($row['expenditure_on_acaricides']);
                    $labor = number_format($row['expenditure_on_labor']);
                    $injections = number_format($row['expenditure_on_injections']);
                    $tools = number_format($row['expenditure_on_farm_tools']);
                    $deworning = number_format($row['expenditure_on_deworning']);
                    $land_clearing = number_format($row['expenditure_on_land_clearing']);
                    $animal_feeds = number_format($row['expenditure_on_animal_feeeds']);
                    $fencing = number_format($row['expenditure_on_fencing']);

                    echo "<div class='' style='padding: 10px; width: 100%;'> <b>Expenditure on</b></div>";
                    echo "<h6>Accaricides:</h6>
                          <p class=\"align\">$acaricides Ugx</p><hr/>";
                    echo "<h6>Labour:</h6>
                          <p class=\"align\">$labor Ugx</p><hr/>";
                    echo "<h6>Injections:</h6>
                          <p class=\"align\">$injections Ugx</p><hr/>";
                    echo "<h6>Tools:</h6>
                          <p class=\"align\">$tools Ugx</p><hr/>";
                    echo "<h6>Deworming:</h6>
                          <p class=\"align\">$deworning Ugx</p><hr/>";
                    echo "<h6>Land clearing:</h6>
                          <p class=\"align\">$land_clearing Ugx</p><hr/>";
                    echo "<h6>Animal Feeds:</h6>
                          <p class=\"align\">$animal_feeds Ugx</p><hr/>";
                    echo "<h6>Fencing:</h6>
                          <p class=\"align\">$fencing Ugx</p><hr/>";
                }

                echo "<input type=\"hidden\" id=\"enterprise\" value=\"$enterprise\" /> ";

                echo "</div>
    </div>
  </div>";

                /////////////////////////////////////////////production data_ends


//                } else {
//
/////////////////////////////////////////////// lat_long_pic starts
//                    echo "<div class=\"row\">
//<input type=\"hidden\" id=\"latitude\" value=\"$latitude\" />
//<input type=\"hidden\" id=\"longitude\" value=\"$longitude\" />
//<div class=\"col-sm-4 col-md-4 col-lg-4\">
// <span class=\"img\" ><img src=\"$picture\" class=\"on_map\"></span>
//</div>
//
//<div class=\"col-sm-5 col-md-5 col-lg-5\">
//<h6>GPs location: <span style=\"color:#999\">$latitude , $longitude</span></h6>
//
//</div>";
//                    echo "
//  <div class=\"right print_export\">
//  <a class=\"btn btn-danger btn-fab btn-raised mdi-action-print\" onclick=\"PrintPreview();\" ></a>
//</div>";
//
//
//                    echo "</div>";
//////////////////////////////////////////////////// lat_long_pic endss
//
//                    $table = "bio_data";
//                    $columns = "*";
//                    $where = " dataset_id='$dataset_id' ";
//                    $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);
//
///////////////////////////////////////////////personal data_starts
//                    echo "<div class=\"row data1\">
//<div class=\"col-sm-12 col-me-5 col-lg-5\">
//<div class=\"card\">
//<h5 class=\"\" style=\"\">Personal Profile</h5>";
//
//
//                    foreach ($rows2 as $row) {
//                        $column = $row['columns'];
//                        $value = $rows[0][$column];
//                        $value = $util_obj->captalizeEachWord($value);
//                        $string = str_replace("biodata_farmer_", "", $column);
//                        $string = str_replace("_", " ", $string);
//                        $lable = $util_obj->captalizeEachWord($string);
//                        if ($lable != "Picture") {
//
//                            if ($lable == "Dob") {
//                                $value = str_replace("00:00:000", " ", $value);
//                                $birth_date = date_create($value);
//                                $birth_date = date_format($birth_date, "d/m/Y");
//                                echo "<h6>Date of Birth:</h6>
//
//  <p class=\"align\">$birth_date</p><hr/>";
//                                $age = $util_obj->getAge($value, "Africa/Nairobi");
//                                echo "<h6>Age:</h6>
//  <p class=\"align\">$age</p><hr/>";
//
//                            } else {
//                                echo "<h6>$lable:</h6>
//  <p class=\"align\">$value</p><hr/>";
//                            }
//                        }
//
//                    }
//
//                    $table = "farmer_location";
//                    $columns = "*";
//                    $where = " dataset_id='$dataset_id' ";
//                    $rows3 = $mCrudFunctions->fetch_rows($table, $columns, $where);
//
//                    $va = $rows[0]['interview_particulars_va_name'];
//                    $vaphone = $rows[0]['interview_particulars_va_phone_number'];
//                    $vacode = $rows[0]['interview_particulars_va_code'];
//                    echo "<input type=\"hidden\" id=\"va\" value=\"$va\" />";
//                    echo "<input type=\"hidden\" id=\"vaphone\" value=\"$vaphone\" />";
//                    echo "<input type=\"hidden\" id=\"vacode\" value=\"$vacode\" />";
//
//                    foreach ($rows3 as $row) {
//                        $column = $row['columns'];
//                        $value = $rows[0][$column];
//                        $value = $util_obj->captalizeEachWord($value);
//                        $string = str_replace("biodata_farmer_location_farmer_", "", $column);
//                        $string = str_replace("_", " ", $string);
//                        $lable = $util_obj->captalizeEachWord($string);
//                        if (!strpos($column, '_gps_')) {
//                            echo "<h6>$lable:</h6>
//  <p class=\"align\">$value</p><hr/>";
//                        }
//
//
//                    }
//
//                    echo "</div>";
///////////////////////////////////////////////personal data_ends
//
//
///////////////////////////////////////////////other data_starts
//                    echo "<div class=\"card\">
//<h5 class=\"\">Others</h5>";
//                    $table = "info_on_other_enterprise";
//                    $columns = "*";
//                    $where = " dataset_id='$dataset_id' ";
//                    $rows5 = $mCrudFunctions->fetch_rows($table, $columns, $where);
//
//                    foreach ($rows5 as $row) {
//                        $column = $row['columns'];
//                        $value = $rows[0][$column];
//                        $value = $util_obj->captalizeEachWord($value);
//                        $string = "information_on_other_crops_";
//                        $string = str_replace($string, "", $column);
//                        $string = str_replace("_", " ", $string);
//                        $lable = $util_obj->captalizeEachWord($string);
//                        if ($value != null) {
//                            echo "<h6>$lable:</h6>
//  <p class=\"align\">$value</p><hr/>";
//                        }
//                    }
//
//
//                    $table = "general_questions";
//                    $columns = "*";
//                    $where = " dataset_id='$dataset_id' ";
//                    $rows6 = $mCrudFunctions->fetch_rows($table, $columns, $where);
//
//                    foreach ($rows6 as $row) {
//                        $column = $row['columns'];
//
//                        $value = $rows[0][$column];
//                        $value = $util_obj->captalizeEachWord($value);
//                        $string = "general_questions_";
//                        $string = str_replace($string, "", $column);
//                        $string = str_replace("_", " ", $string);
//                        $lable = $util_obj->captalizeEachWord($string);
//                        if ($value != null) {
//
//                            echo "<h6>$lable:</h6>
//  <p class=\"align\">$value</p><hr/>";
//                        }
//                    }
//
//                    echo "</div>
//</div>
//";
///////////////////////////////////////////////other data_ends
//
///////////////////////////////////////////////production data_starts
//
//
//                    $rows_seeds = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Seed' ");
//                    $rows_fertilizers = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Fertilizer' ");
//                    $rows_herbicide = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Herbicide' ");
//                    $cash_taken = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='cashtaken' ");
//
//                    $tractor_money = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='tractor' ");
//
//                    //$rows_tractor_money_taken= $mCrudFunctions->fetch_rows("tractormoney_".$dataset_id,"*"," farmer_id='$id' ");
//
//                    $rows_yield = $mCrudFunctions->fetch_rows("out_grower_produce_tb", "*", " dataset_id='$dataset_id' AND  meta_id='$id' ");
//
//
//                    $rows_cash_returned = $mCrudFunctions->get_sum("cash_returned_" . $dataset_id, "cash_returned", " farmer_id='$id' ");
//
//                    $rows_tractor_money_returned = $mCrudFunctions->get_sum("tractor_money_returned_" . $dataset_id, "tractor_money_returned", " farmer_id='$id' ");
//
//                    $yield = $rows_yield[0]['qty'] == "" ? "N/A" : $rows_yield[0]['qty'];
//
//                    $tractor_money_taken = $tractor_money;//$rows_tractor_money_taken[0]['tractor_money'];
//                    //$cash_taken=$rows_cash[0]['cash_taken'];
//
//                    $rows_tractor_money_owed = number_format((int)$tractor_money_taken - (int)$rows_tractor_money_returned);
//                    $cash_owed = number_format((int)$cash_taken - (int)$rows_cash_returned);
//                    $cash_taken = number_format($cash_taken);
//                    $rows_cash_returned = number_format($rows_cash_returned);
//
//                    $rows_tractor_money_returned = number_format($rows_tractor_money_returned);
//                    $tractor_money_taken = number_format($tractor_money_taken);
//
//
//                    echo "
//  <div class=\" col-sm-12 col-md-7 col-lg-7\">
//    <div class=\"card prodn\">
//  <h5 class=\"\">Outgrower profile</h5>
//  <div class=\"caption\">
//
//   <h6>Acreage:</h6>
//   <p id=\"total\">N/A</p><hr/>
//   <h6>Crop:</h6>
//   <p id=\"enterprise_\">N/A</p><hr/>
//   <h6>Yield:</h6>
//  <p>$yield KGS</p><hr/>
//  <h6>VA</h6>
//  <p id=\"va_\">N/A</p><hr/>
//  <h6>Cash Taken:</h6>
//  <p>UGX $cash_taken</p><hr/>
//  <!--<h6>Cash Paid:</h6>
//   <p>UGX $rows_cash_returned</p><hr/>
//  <h6>Cash Owed To Farmer:</h6>
//  <p>UGX $cash_owed</p><hr/>
//  -->
//   <h6>Tractor Amount:</h6>
//   <p>UGX $tractor_money_taken</p><hr/>
//  <!--
//   <h6>Tractor Amount Paid:</h6>
//   <p>UGX $rows_tractor_money_returned</p><hr/>
//
//   <h6>Tractor Amount Owed To Farmer:</h6>
//   <p>UGX $rows_tractor_money_owed</p><hr/>-->
//  </div>
//
//  </div>
//</div>";
//
//                    if (sizeof($rows_seeds) > 0) {
//                        echo "
//  <div class=\" col-sm-12 col-md-2 col-lg-2\">
//    <div class=\"card\">
//  <h5>Seed Taken</h5>
//   <table class=\"striped\" style=\"width:98%;\">
//        <thead>
//          <tr style=\"background:#fafafa; padding:5px 5px 5px 5px; height:2em; margin-left:5px; margin-right:5px;\">
//              <th >#</th>
//			  <th>Type</th>
//              <th>Quantity</th>
//          </tr>
//        </thead>
//        <tbody>";
//                        $ss = 0;
//                        foreach ($rows_seeds as $seed) {
//                            $ss++;
//                            $reciept = $seed['reciept_url'];
//                            $type = $seed['item'];
//                            $qty = $seed['qty'];
//                            $units = $seed['units'];
//                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
//		   <td ><a >$ss</a></td>
//            <td >$type</td>
//            <td>$qty $units</td>
//          </tr>";
//
//                        }
//
//                        echo "</tbody>
//      </table>
//  </div>
//</div>";
//
//                    }
//
//
//                    if (sizeof($rows_herbicide) > 0) {
//                        echo "
//  <div class=\" col-sm-12 col-md-2 col-lg-2\">
//    <div class=\"card\">
//  <h5>Herbicide Taken</h5>
//   <table class=\"striped\" style=\"width:100%;\">
//        <thead>
//          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
//              <th>#</th>
//			  <th>Type</th>
//              <th>Quantity</th>
//          </tr>
//        </thead>
//        <tbody>";
//                        $ss = 0;
//                        foreach ($rows_herbicide as $herbicide) {
//                            $ss++;
//                            $reciept = $fertilizer['reciept_url'];
//                            $type = $herbicide['item'];
//                            $qty = $herbicide['qty'];
//                            $units = $herbicide['units'];
//
//                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
//		    <td  ><a >$ss</a></td>
//            <td >$type</td>
//            <td >$qty $units</td>
//          </tr>";
//
//                        }
//
//                        echo "</tbody>
//      </table>
//
//  </div>
//</div>";
//
//                    }
//
//                    if (sizeof($rows_fertilizers) > 0) {
//                        echo "
//  <div class=\" col-sm-12 col-md-2 col-lg-2\">
//    <div class=\"card\">
//  <h5>Fertlizer Taken</h5>
//   <table class=\"striped\" style=\"width:100%;\">
//        <thead>
//          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
//              <th>#</th>
//			  <th>Type</th>
//              <th>Quantity</th>
//          </tr>
//        </thead>
//        <tbody>";
//                        $ss = 0;
//                        foreach ($rows_fertilizers as $fertilizer) {
//                            $ss++;
//                            $reciept = $fertilizer['reciept_url'];
//                            $type = $fertilizer['item'];
//                            $qty = $fertilizer['qty'];
//                            $units = $fertilizer['units'];
//                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
//		   <td ><a >$ss</a></td>
//            <td  >$type</td>
//            <td  >$qty $units</td>
//          </tr>";
//
//                        }
//
//                        echo "</tbody>
//      </table>
//
//  </div>
//</div>";
//
//                    }
//
//
//                    echo "<div class=\"col-sm-12 col-md- col-lg-7\" style=\"margin-bottom:100px;\">
//<div class=\"card prodn\">
//<h5 class=\"\">Production Data</h5>";
//
//
//                    $table = "production_data";
//                    $columns = "*";
//                    $where = " dataset_id='$dataset_id' ";
//                    $rows4 = $mCrudFunctions->fetch_rows($table, $columns, $where);
//                    $enterprise = "";
//                    foreach ($rows4 as $row) {
//                        $column = $row['columns'];
//                        $enterprise = $row['enterprise'];
//                        $value = $rows[0][$column];
//                        $value = $util_obj->captalizeEachWord($value);
//                        $string = $enterprise . "_production_data_";
//                        $string = str_replace($string, "", $column);
//                        $string = str_replace("_", " ", $string);
//                        $lable = $util_obj->captalizeEachWord($string);
//                        if ($value != null) {
//
//                            echo "<h6 class=\"trim\">$lable:</h6>
//  <p>$value</p><hr/>";
//
//                        }
//                    }
//
//                    echo "<input type=\"hidden\" id=\"enterprise\" value=\"$enterprise\" /> ";
//
//                    echo "</div>
//    </div>
//  </div>";
//
//                    /////////////////////////////////////////////production data_ends
//
//
//                }
///////////////////////////////////////////sdrfghjklytdfhjgkhljtrydryfyujgkhtygh


            }

            if ($type == "VA") {

                $latitude = $util_obj->remove_apostrophes($rows[0]['va_location_va_home_gps_Latitude']);
                $longitude = $util_obj->remove_apostrophes($rows[0]['va_location_va_home_gps_Longitude']);
                $picture = $util_obj->remove_apostrophes($rows[0]['biodata_va_picture']);
                $gender = $util_obj->captalizeEachWord($rows[0]['biodata_va_gender']);
                $name = $util_obj->captalizeEachWord($rows[0]['biodata_va_name']);
                $phone = $util_obj->remove_apostrophes($rows[0]['va_phonenumber']);
                $uuid = $util_obj->remove_apostrophes($rows[0]['meta_instanceID']);
                $code = str_replace(".", "", $rows[0]['biodata_va_code']);
                $code = str_replace(" ", "", $code);
                $code = strtolower($code);

                echo "<input id=\"picture\" type=\"hidden\" value=\"$picture\" />";
                echo "<input id=\"uuid\" type=\"hidden\" value=\"$uuid\" />";

///////////////////////////////////////////// lat_long_pic starts
                echo "<div class=\"row\">
                <input type=\"hidden\" id=\"latitude\" value=\"$latitude\" />
                <input type=\"hidden\" id=\"longitude\" value=\"$longitude\" />
                <div class=\"col-sm-4 col-md-4 col-lg-4\">
                 <span class=\"img\" ><img src=\"$picture\" class=\"on_map\"></span>
                </div>
                
                <div class=\"col-sm-5 col-md-5 col-lg-5\">
                <h6>GPs location: <span style=\"color:#999\">$latitude , $longitude</span></h6>
                
                </div>";
                echo "
                  <div class=\"right print_export\">
                  <a class=\"btn btn-danger btn-fab btn-raised mdi-action-print\" onclick=\"PrintPreview();\" ></a>
                </div>";

/////////////////////////////////////////////personal data_starts


                $table = "bio_data";
                $columns = "*";
                $where = " dataset_id='$dataset_id' ";
                $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);

/////////////////////////////////////////////personal data_starts
                echo "
<div class=\"col-sm-12 col-me-5 col-lg-5\">
<div class=\"card\">
<h5 class=\"\" style=\"\">Personal Profile</h5>";

                $table = "va_location";
                $columns = "*";
                $where = " dataset_id='$dataset_id' ";
                $rows3 = $mCrudFunctions->fetch_rows($table, $columns, $where);

                foreach ($rows3 as $row) {
                    $column = $row['columns'];
                    $value = $rows[0][$column];
                    $value = $util_obj->captalizeEachWord($value);
                    $string = str_replace("va_location_va_", "", $column);
                    $string = str_replace("_", " ", $string);
                    $lable = $util_obj->captalizeEachWord($string);
                    if (!strpos($column, '_gps_')) {
                        echo "<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
                    }


                }

                echo "</div>";

                /////////////////////////////////////////////personal data_starts


                $table = "bio_data";
                $columns = "*";
                $where = " dataset_id='$dataset_id' ";
                $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);
                $origin = $util_obj->encrypt_decrypt("decrypt", $_GET['o']);

                $profiled_farmers = $mCrudFunctions->get_count("dataset_" . $origin, " lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) ='$code' ");

                $produce_suplied_ = (int)$mCrudFunctions->get_sum("out_grower_produce_v", "qty", " dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code' ");

                $commision_on_profiling = (int)$mCrudFunctions->fetch_rows("out_grower_threshold_tb", "commission_per_unit", " client_id='$client_id' AND item='Profiling'  AND item_type='Service' ")[0]['commission_per_unit'];

                $profilling_commission_ = $commision_on_profiling * $profiled_farmers;
                $profilling_commission = number_format($profilling_commission_);

                $produce_commission_ = (int)$mCrudFunctions->get_sum("out_grower_produce_v", "commission", " dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code' ");
                $produce_commission = number_format($produce_commission_);

                $total_earnings_ = $profilling_commission_ + $produce_commission_;

                $total_earnings = number_format($total_earnings_);
/////////////////////////////////////////////personal data_starts
                echo "

<div class=\"card\">
<h5 class=\"\" style=\"\">Outgrower profile</h5>";


                echo "
   <h6>Number of farmers profiled:</h6>
   <p>$profiled_farmers</p><hr/>
   <h6>Quantity supplied by farmers:</h6>
   <p>$produce_suplied_ KGS</p><hr/>
  <h6>Profiling commission</h6>
  <p>$profilling_commission UGX</p><hr/>
  <h6>Produce commission:</h6>
   <p>$produce_commission UGX</p><hr/>


  <h6>Total earning:</h6>
  <p>$total_earnings UGX</p><hr/>";

                echo "</div>";


                echo "</div>";


///---------------------------------------personal data_ends

//----------------------------------------personal data_starts
                echo "
<div class=\"col-sm-12 col-me-5 col-lg-7\">
<div class=\"card\">
<h5 class=\"\" style=\"\">Production Data</h5>";
                $table = "va_production_data";
                $columns = "*";
                $where = " dataset_id='$dataset_id' ";
                $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);

                foreach ($rows2 as $row) {
                    $column = $row['columns'];
                    $value = $rows[0][$column];
                    $value = $util_obj->captalizeEachWord($value);
                    $string = str_replace("production_data_va_", "", $column);
                    $string = str_replace("production_data", "", $string);
                    $string = str_replace("_", " ", $string);
                    $lable = $util_obj->captalizeEachWord($string);


                    if ($value != "") {
                        echo "<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
                    }
                }

                $table = "va_farm_production_data";
                $columns = "*";
                $where = " dataset_id='$dataset_id' ";
                $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);

                foreach ($rows2 as $row) {
                    $column = $row['columns'];
                    $value = $rows[0][$column];
                    $value = $util_obj->captalizeEachWord($value);
                    $string = str_replace("farm_production_data_", "", $column);
                    // $string=str_replace("production_data","",$string);
                    $string = str_replace("_", " ", $string);
                    $lable = $util_obj->captalizeEachWord($string);


                    if ($value != "") {
                        echo "<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
                    }


                }

                echo "</div>";
                echo "</div>";

/////////////////////////////////////////////personal data_ends


            }

        }


    } ?>
</div>
<!--end of main content-->
<div id="hidden_form_container"></div>
<?php include("./include/footer_client.php"); ?>
<script>
    $(document).ready(function () {
//        var loan_status = "<?php //echo $accessed_loan_status; ?>//";

        getGardens();

        va = document.getElementById('va').value;
        vacode = document.getElementById('vacode').value;

        $("#va_").html(va + "(" + vacode + ")");
        enterprise = document.getElementById('enterprise').value;

        $("#enterprise_").html(enterprise[0].toUpperCase() + enterprise.substring(1));

    });
    function initialize() {
        lat_holder = document.getElementById('latitude');
        longi_holder = document.getElementById('longitude');

        dataset_id_holder = document.getElementById('dataset_id');
        farmer_id_holder = document.getElementById('id');


        var mapCanvas = document.getElementById('map-canvas');


        var mapOptions = {
            center: new google.maps.LatLng(lat_holder.value, longi_holder.value),
            zoom: 19,
            mapTypeId: google.maps.MapTypeId.HYBRID
        }


        var map = new google.maps.Map(mapCanvas, mapOptions)
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat_holder.value, longi_holder.value),
            map: map,
            title: ''
        });

        $.ajax({
            dataType: "json",
            url: "Acerage.php",
            data: {
                id: dataset_id_holder.value,
                farmer_id: farmer_id_holder.value
            },
            success: function (data) {

                $.each(data.data, function (key, Coordinates) {

                    var colors = ["#FF0000", "#0000FF", "#00FF00"];
                    // window.alert(key);
                    var flightPath = new google.maps.Polygon({
                        path: Coordinates,
                        geodesic: true,
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        //fillColor: colors[key],/*grrr*/
                        strokeWeight: 2
                    });

                    flightPath.setMap(map);

                });
                //window.alert(geo_data);

            }
        });


    }


    google.maps.event.addDomListener(window, 'load', initialize);

    //getGardens();

    function getGardens() {

        var dataset_id = document.getElementById("dataset_id");
        id = dataset_id.value;

        var uu_id = document.getElementById("uuid");
        uuid = uu_id.value;

        //window.alert(uuid);
        var _id = document.getElementById("id");
        id_ = _id.value;

        $.ajax({
            type: "POST",
            url: "form_actions/getGardens.php",
            data: {dataset_id: id, uuid: uuid},
            success: function (data) {

                $("#gardens").html(data);
                var total_acerage = document.getElementById("total_acerage").value;

                $("#total").html(total_acerage);


            }
        });


    }

    function PrintPreview() {

        var dataset_id = document.getElementById("dataset_id");
        datasetid = dataset_id.value;
        var dataset_type = document.getElementById("type");
        datasettype = dataset_type.value;
        var dataset_user = document.getElementById("id");
        datasetuser = dataset_user.value;
        var dataset_picture = document.getElementById("picture");
        picture = dataset_picture.value;

        var theForm, newInput1, newInput2, newInput3, newInput4;
        // Start by creating a <form>
        theForm = document.createElement('form');
        theForm.action = 'details_print.php';
        theForm.method = 'post';
        // Next create the <input>s in the form and give them names and values
        newInput1 = document.createElement('input');
        newInput1.type = 'hidden';
        newInput1.name = 'id';
        newInput1.value = datasetuser;
        newInput2 = document.createElement('input');
        newInput2.type = 'hidden';
        newInput2.name = 'type';
        newInput2.value = datasettype;
        newInput3 = document.createElement('input');
        newInput3.type = 'hidden';
        newInput3.name = 'dataset';
        newInput3.value = datasetid;
        newInput4 = document.createElement('input');
        newInput4.type = 'hidden';
        newInput4.name = 'picture';
        newInput4.value = picture;

        // Now put everything together...
        theForm.appendChild(newInput1);
        theForm.appendChild(newInput2);
        theForm.appendChild(newInput3);
        theForm.appendChild(newInput4);
        // ...and it to the DOM...
        document.getElementById('hidden_form_container').appendChild(theForm);
        // ...and submit it
        theForm.submit();
    }
</script>
