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

    h7 {
        padding-left: 10px;
    }

    .prodn h6 {
        width: 300px;
    }

    .prodn p {
        color: #777;
        max-width: 3000px;
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
<div class="container" >

    <legend><h3 class="text-center">
            <?php
            require_once dirname(__FILE__) . "/php_lib/lib_functions/database_query_processor_class.php";

            $db = new DatabaseQueryProcessor();
            $client_id = $_SESSION['client_id'];
            if (isset($_GET['type']) && $_GET['type'] != "") {
                if ($_GET['type'] == "Farmer") {
                    echo "Farmer details";
                }
                if ($_GET['type'] == "VA") {
                    echo "VA details";
                }
            } ?></h3></legend>
    <!--map row-->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="">
                <div class="card-image">
                    <div class="" id="map-canvas"></div><!--map canvas-->
                    <div class="" id="gardens"></div><!-- gardens-->
                </div>
            </div>
        </div>
    </div>

    <?php

    if (isset($_GET['token']) && $_GET['token'] != "" && isset($_GET['s']) && $_GET['s'] != ""
        && isset($_GET['type']) && $_GET['type'] != ""
    ) {

        $type = $_GET['type'];
        $dataset_id = $util_obj->encrypt_decrypt("decrypt", $_GET['s']);
        $id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);

        echo "<input id=\"dataset_id\" type=\"hidden\" value=\"$dataset_id\" />";
        echo "<input id=\"id\" type=\"hidden\" value=\"$id\" />";
        echo "<input id=\"type\" type=\"hidden\" value=\"$type\" />";

        $table = "dataset_" . $dataset_id;
        $columns = "*";
        $where = " id='$id' ";
        $rows = $mCrudFunctions->fetch_rows($table, $columns, $where);
        if (sizeof($rows) == 0) {

        } else {

            $id = $rows[0]['id'];

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


                foreach ($rows2 as $row) {
                    $column = $row['columns'];
                    $value = $rows[0][$column];
                    $value = $util_obj->captalizeEachWord($value);
                    $string = str_replace("biodata_va_id_", "", $column);
                    $string = str_replace("biodata_va_", "", $string);
                    $string = str_replace("_", " ", $string);
                    $lable = $util_obj->captalizeEachWord($string);
                    if ($lable != "Picture") {

                        if ($lable == "Dob") {
                            $value = str_replace("00:00:000", " ", $value);
                            $birth_date = date_create($value);
                            $birth_date = date_format($birth_date, "d/m/Y");
                            echo "<h6>Date of Birth:</h6>
  
  <p class=\"align\">$birth_date</p><hr/>";
                            $age = $util_obj->getAge($value, "Africa/Nairobi");
                            echo "<h6>Age:</h6>
  <p class=\"align\">$age</p><hr/>";

                        } else {
                            if ($value != "") {
                                echo "<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
                            }

                        }
                    }

                }

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
   <!--<h6>Profiling commission paid:</h6>
   <p>xxx</p><hr/>
  <h6>Produce commission paid:</h6>
  <p>xxx</p><hr/>-->

  <h6>Total earning:</h6>
  <p>$total_earnings UGX</p><hr/>
  <!--<h6>Remarks:</h6>
   <p>XXX</p><hr/>
   <h6>Ratings:</h6>
   <p>xxx</p><hr/> -->";

                echo "</div>";


                echo "</div>";


                /////////////////////////////////////////////personal data_ends


/////////////////////////////////////////////personal data_starts  
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

            if ($type == "Farmer") {
                $latitude = $util_obj->remove_apostrophes($rows[0]['biodata_farmer_location_farmer_home_gps_Latitude']);
                $longitude = $util_obj->remove_apostrophes($rows[0]['biodata_farmer_location_farmer_home_gps_Longitude']);
                $picture = $util_obj->remove_apostrophes($rows[0]['biodata_farmer_picture']);
                $uuid = $rows[0]['meta_instanceID'] ? $rows[0]['meta_instanceID'] : 'NA';
                $uuid = $util_obj->remove_apostrophes($uuid);

                echo "<input id=\"picture\" type=\"hidden\" value=\"$picture\" />";
                echo "<input id=\"uuid\" type=\"hidden\" value=\"$uuid\" />";

/////////////////////////////////////////////fghjkl;'fdghjklfghjkl
                if ($_SESSION['client_id'] == 1) {


///////////////////////////////////////////// lat_long_pic starts
                    echo "<div class=\" hide row\">
<input type=\"hidden\" id=\"latitude\" value=\"$latitude\" />
<input type=\"hidden\" id=\"longitude\" value=\"$longitude\" />
<div class=\" hide col-sm-4 col-md-4 col-lg-4\">
 <span class=\"img\" ><img src=\"$picture\" class=\"hide on_map\"></span>
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

                    $table = "bio_data";
                    $columns = "*";
                    $where = " dataset_id='$dataset_id' ";
                    $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);

/////////////////////////////////////////////personal data_starts  
                    echo "<div class=\"row data1\">
<div class=\"col-sm-12 col-me-5 col-lg-5\">
<div class=\"card\">
<h5 class=\"\" style=\"\">Personal Profile</h5>";


                    foreach ($rows2 as $row) {
                        $column = $row['columns'];
                        $value = $rows[0][$column];
                        $value = $util_obj->captalizeEachWord($value);
                        $string = str_replace("biodata_farmer_", "", $column);
                        $string = str_replace("_", " ", $string);
                        $lable = $util_obj->captalizeEachWord($string);
                        if ($lable != "Picture") {
                            if ($lable == "Dob") {
                                $value = $util_obj->remove_apostrophes($value);
                                $dob = explode('-',$value);
                                if(strlen($dob[2]) == 2){
                                    $dob[2] = '19'.$dob[2];
                                    $value = implode('-',$dob);
                                }

//                                $value = str_replace("00:00:000", " ", $value);
                                $birth_date = date_create($value);
                                $birth_date = date_format($birth_date, "d/m/Y");
                                echo "<h6>Date of Birth:</h6>
  
  <p class=\"align\">$birth_date</p><hr/>";
//                                $age = $util_obj->getAge($value, "Africa/Nairobi");
//                                echo "<h6>Age:</h6>
//  <p class=\"align\">$age</p><hr/>";

                            } else {
                                echo "<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
                            }
                        }

                    }

                    $table = "farmer_location";
                    $columns = "*";
                    $where = " dataset_id='$dataset_id' ";
                    $rows3 = $mCrudFunctions->fetch_rows($table, $columns, $where);

                    $va = $rows[0]['interview_particulars_va_name'];
                    $vaphone = $rows[0]['interview_particulars_va_phone_number'];
                    $vacode = $rows[0]['interview_particulars_va_code'];
                    echo "<input type=\"hidden\" id=\"va\" value=\"$va\" />";
                    echo "<input type=\"hidden\" id=\"vaphone\" value=\"$vaphone\" />";
                    echo "<input type=\"hidden\" id=\"vacode\" value=\"$vacode\" />";

                    foreach ($rows3 as $row) {
                        $column = $row['columns'];
                        $value = $rows[0][$column];
                        $value = $util_obj->captalizeEachWord($value);
                        $string = str_replace("biodata_farmer_location_farmer_", "", $column);
                        $string = str_replace("_", " ", $string);
                        $lable = $util_obj->captalizeEachWord($string);
                        if (!strpos($column, '_gps_')) {
                            echo "<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
                        }


                    }

                    echo "</div>";
/////////////////////////////////////////////personal data_ends


///////////////////////////////////////////other data_starts
  echo"<div class=\"card\">
<h5 class=\"\">Others</h5>";
  $table="info_on_other_enterprise";
  $columns="*";
  $where=" dataset_id='$dataset_id' ";
  $rows5= $mCrudFunctions->fetch_rows($table,$columns,$where);

  foreach($rows5 as $row){
   $column=$row['columns'];
  $value=$rows[0][$column];
  $value=$util_obj->captalizeEachWord($value);
  $string="information_on_other_crops_";
   $string=str_replace($string,"",$column);
  $string=str_replace("_"," ",$string);
  $lable=$util_obj->captalizeEachWord($string);
  if($value!=null){
   echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
  }
  }


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

  echo"</div>
</div>
";
///////////////////////////////////////////other data_ends

/////////////////////////////////////////////production data_starts


                    $rows_seeds = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Seed' ");
                    $rows_fertilizers = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Fertilizer' ");
                    $rows_herbicide = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Herbicide' ");
                    $cash_taken = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='cashtaken' ");

                    $tractor_money = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='tractor' ");

                    //$rows_tractor_money_taken= $mCrudFunctions->fetch_rows("tractormoney_".$dataset_id,"*"," farmer_id='$id' ");

                    $rows_yield = $mCrudFunctions->fetch_rows("dataset_".$dataset_id, "*", "id='$id' ");


                    $rows_cash_returned = $mCrudFunctions->get_sum("cash_returned_" . $dataset_id, "cash_returned", " farmer_id='$id' ");

                    $rows_tractor_money_returned = $mCrudFunctions->get_sum("tractor_money_returned_" . $dataset_id, "tractor_money_returned", " farmer_id='$id' ");

                    $yield = $rows_yield[0]['maize_production_data_maize_harvested'] == "" ? "N/A" : $rows_yield[0]['maize_production_data_maize_harvested'];

                    $tractor_money_taken = $tractor_money;//$rows_tractor_money_taken[0]['tractor_money'];
                    //$cash_taken=$rows_cash[0]['cash_taken'];

                    $rows_tractor_money_owed = number_format((int)$tractor_money_taken - (int)$rows_tractor_money_returned);
                    $cash_owed = number_format((int)$cash_taken - (int)$rows_cash_returned);
                    $cash_taken = number_format($cash_taken);
                    $rows_cash_returned = number_format($rows_cash_returned);

                    $rows_tractor_money_returned = number_format($rows_tractor_money_returned);
                    $tractor_money_taken = number_format($tractor_money_taken);


                    echo "
  <div class=\" col-sm-12 col-md-7 col-lg-7\">
    <div class=\"card prodn\">
  <h5 class=\"\">Outgrower profile</h5>
  <div class=\"caption\">
  
   <h6>Acreage:</h6>
   <p id=\"total\">N/A</p><hr/>
   <h6>Crop:</h6>
   <p id=\"enterprise_\">N/A</p><hr/>
   <h6>Yield:</h6>
  <p>$yield KGS</p><hr/>
  <h6>VA</h6>
  <p id=\"va_\">N/A</p><hr/>
  <h6>Cash Taken:</h6>
  <p>UGX $cash_taken</p><hr/>
  <!--<h6>Cash Paid:</h6>
   <p>UGX $rows_cash_returned</p><hr/>
  <h6>Cash Owed To Farmer:</h6>
  <p>UGX $cash_owed</p><hr/>  
  -->
   <h6>Tractor Amount:</h6>
   <p>UGX $tractor_money_taken</p><hr/>
  <!-- 
   <h6>Tractor Amount Paid:</h6>
   <p>UGX $rows_tractor_money_returned</p><hr/>
   
   <h6>Tractor Amount Owed To Farmer:</h6>
   <p>UGX $rows_tractor_money_owed</p><hr/>-->
  </div>

  </div>
</div>";

                    if (sizeof($rows_seeds) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Seed Taken</h5>
   <table class=\"striped\" style=\"width:98%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px 5px 5px 5px; height:2em; margin-left:5px; margin-right:5px;\">
              <th >#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_seeds as $seed) {
                            $ss++;
                            $reciept = $seed['reciept_url'];
                            $type = $seed['item'];
                            $qty = $seed['qty'];
                            $units = $seed['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td >$type</td>
            <td>$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>
  </div>
</div>";

                    }


                    if (sizeof($rows_herbicide) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Herbicide Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_herbicide as $herbicide) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $herbicide['item'];
                            $qty = $herbicide['qty'];
                            $units = $herbicide['units'];

                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		    <td  ><a >$ss</a></td>
            <td >$type</td>
            <td >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

                    if (sizeof($rows_fertilizers) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Fertlizer Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_fertilizers as $fertilizer) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $fertilizer['item'];
                            $qty = $fertilizer['qty'];
                            $units = $fertilizer['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td  >$type</td>
            <td  >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

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

                    echo "<input type=\"hidden\" id=\"enterprise\" value=\"$enterprise\" /> ";

                    echo "</div>
    </div>
  </div>";

                    /////////////////////////////////////////////production data_ends

                }
                elseif ($_SESSION["account_name"] == 'Ankole Coffee Producers Cooperative Union Ltd') {
                    $bio = $mCrudFunctions->fetch_rows("dataset_".$dataset_id, '*', 'id='.$id);

                    $latitude = $util_obj->remove_apostrophes($bio[0]['biodata_farmer_location_farmer_home_gps_Latitude']);
                    $longitude = $util_obj->remove_apostrophes($bio[0]['biodata_farmer_location_farmer_home_gps_Longitude']);
                    $picture = $util_obj->remove_apostrophes($bio[0]['biodata_farmer_picture']);

///////////////////////////////////////////// lat_long_pic starts
                    echo "<div class=\"row\">
<input type=\"hidden\" id=\"latitude\" value=\"$latitude\" />
<input type=\"hidden\" id=\"longitude\" value=\"$longitude\" />
<div class=\" col-sm-4 col-md-4 col-lg-4\">
 <span class=\"hide img\" ><img src=\"$picture\" class=\"on_map\"></span>
</div>

<div class=\"col-sm-5 col-md-5 col-lg-5\">
    <h6>Garden location: <span style=\"color:#999\">$latitude , $longitude</span></h6>
</div>";
                    echo "
  <div class=\"right print_export\">
  <a class=\"btn btn-danger btn-fab btn-raised mdi-action-print\" onclick=\"PrintPreview();\" ></a>
</div>";

                    echo "</div>";
////////////////////////////////////////////////// lat_long_pic endss

                    $table = "bio_data";
                    $columns = "*";
                    $where = " dataset_id='$dataset_id' ";
                    $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);


                    $name = $util_obj->capitalizeName($bio[0]['biodata_farmer_name']);
                    $gender = $util_obj->capitalizeName($bio[0]['biodata_farmer_gender']);
                    $contact = $bio[0]['biodata_farmer_phone_number'];
                    $district = $bio[0]['biodata_farmer_location_farmer_district'];
                    $subcounty = $bio[0]['biodata_farmer_location_farmer_subcounty'];
                    $parish = $bio[0]['biodata_farmer_location_farmer_parish'];
                    $village = $bio[0]['biodata_farmer_location_farmer_village'];
                    $acreage = $bio[0]['production_data_land_size'];

/////////////////////////////////////////////personal data_starts
                    echo "<div class=\"row data1\">
<div class=\"col-sm-12 col-me-5 col-lg-5\">
<div class=\"card\" >
<h5 class=\"\" style=\"\">Personal Profile</h5>";

                    echo "
        <h6>Name:</h6> <p class=\"align\">$name</p><hr/>
        <h6>Gender:</h6> <p class=\"align\">$gender</p><hr/>  
        <h6>District:</h6> <p class=\"align\">$district</p><hr/>
        <h6>Subcounty:</h6> <p class=\"align\">$subcounty</p><hr/>
    <!--    <h6>Parish:</h6> <p class=\"align\">$parish</p><hr/>  -->
        <h6>Village:</h6> <p class=\"align\">$village</p><hr/>
        ";

                    echo "</div>";
/////////////////////////////////////////////personal data_ends


///////////////////////////////////////other data_starts
        echo"<div class=\"card\" hidden='hidden'>
<h5 class=\"\" hidden='hidden'>Others</h5>";

        echo"</div>
</div>
";
///////////////////////////////////////////other data_ends

/////////////////////////////////////////////production data_starts



                    $rows_seeds= $mCrudFunctions->fetch_rows("out_grower_input_v","*","dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Seed' ");
                    $rows_fertilizers= $mCrudFunctions->fetch_rows("out_grower_input_v","*","dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Fertilizer' ");
                    $rows_herbicide= $mCrudFunctions->fetch_rows("out_grower_input_v","*","dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Herbicide' ");
                    $cash_taken= (int) $mCrudFunctions->get_sum("out_grower_cashinput_tb","amount"," dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='cashtaken' ");

                    $tractor_money= (int) $mCrudFunctions->get_sum("out_grower_cashinput_tb","amount"," dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='tractor' ");

                    //$rows_tractor_money_taken= $mCrudFunctions->fetch_rows("tractormoney_".$dataset_id,"*"," farmer_id='$id' ");

                    $rows_yield = $mCrudFunctions->fetch_rows("dataset_" . $dataset_id, "*", " id='$id' ");
                    $acres = $rows_yield[0]['production_data_land_size'] == "" ? "N/A" : $rows_yield[0]['production_data_land_size'];
                    $unik_id = $rows_yield[0]['unique_id'];

                    $samples = $mCrudFunctions->fetch_rows("soil_results_" . $dataset_id, "*", " unique_id='$unik_id' ");

                    /**** ph values and their description ***/
                    $ph = $samples[0]['ph'];    $ph_desc=""; $ph_requiremt=""; $n_requiremt="";
                    if($ph <= 4.5){ $ph_desc = "Extremely acidic"; }
                    elseif($ph>=4.6 && $ph<=5.5){ $ph_desc = "Strongly acidic"; }
                    elseif($ph>=5.6 && $ph<=6.0){ $ph_desc = "Moderately acidic"; }
                    elseif($ph>=6.1 && $ph<=6.5){ $ph_desc = "Slightly acidic"; }
                    elseif($ph>=6.6 && $ph<=7.2){ $ph_desc = "Neutral"; }
                    elseif($ph>=7.3 && $ph<=7.8){ $ph_desc = "Slightly alkaline"; }
                    elseif($ph>=7.9 && $ph<=8.4){ $ph_desc = "Moderately alkaline"; }
                    elseif($ph>=8.5 && $ph<=9.0){ $ph_desc = "Strongly alkaline"; }
                    else{$ph_desc = "Very strongly alkaline";}

                    if($ph<= 5.2){ $ph_requiremt = "Apply Calcium Ammonium Nitrate for the first 2years and Ammonium Sulphate Nitrate in the third year"; }
                    elseif($ph>=5.3 && $ph<= 6.5){ $ph_requiremt = "Apply Calcium Ammonium Nitrate and Ammonium Sulphate Nitrate in alternate years"; }
                    else{ $ph_requiremt = "Apply Sulphate of Ammonia or Urea"; }

                    if($ph<=5.2){ $n_requiremt = "Liming is required"; }
                    else{ $n_requiremt = "No Liming required"; }

                    /** organic matter values and their decsription / meaning **/
                    $om = $samples[0]['om_%'];  $om_desc="";
                    if($om>=0 && $om<=2.5){$om_desc = "Low" ;}
                    elseif($om>2.5 && $om<=3.5){$om_desc = "Moderate" ;}
                    elseif($om>3.5 && $om<=4.9){$om_desc = "High" ;}
                    else{ $om_desc = "Very High"; }

                    /** nitrogen values and their meanings **/
                    $n = $samples[0]['n_%'];    $nitrogen_desc="";
                    if($n < 0.05){$nitrogen_desc = "Very low"; }
                    elseif($n >= 0.05 && $n < 0.15){ $nitrogen_desc = "Low"; }
                    elseif($n >= 0.15 && $n < 0.25){ $nitrogen_desc = "Medium"; }
                    elseif($n >= 0.25 && $n < 0.5){ $nitrogen_desc = "High"; }
                    else { $nitrogen_desc = "Very high"; }

                    /** phosphorous values and their meanings **/
                    $p = $samples[0]['p_ppm'];  $p_desc=""; $p_requiremt="";
                    if($p>=0 && $p<=12){ $p_desc = "Very Low"; }
                    elseif($p>=12.5 && $p<=22.5){$p_desc = "Low"; }
                    elseif($p>=23 && $p<=35.5){$p_desc = "Medium"; }
                    elseif($p>=36 && $p<=68.5){$p_desc = "High"; }
                    elseif($p >= 69){$p_desc = "Very High"; }

                    if($p_desc == "Very High"){ $p_requiremt = "No further addition"; }
                    elseif($p_desc == "High"){ $p_requiremt = "Apply 30g of MOP in a planting hole"; }
                    elseif($p_desc == "Medium"){ $p_requiremt = "Apply 50-60g of SSP in a planting hole"; }
                    elseif( ($p_desc == "Low") || ($p_desc == "Very Low")){ $p_requiremt = "Apply 100g of SSP in a planting hole"; }

                    /** potassium values and their meanings **/
                    $k = $samples[0]['k'];  $k_desc=""; $k_requiremt="";
                    if($k>=0 && $k<0.2){ $k_desc = "Very Low"; }
                    elseif($k>=0.2 && $k<0.3){ $k_desc = "Low"; }
                    elseif($k>=0.3 && $k<0.7){ $k_desc = "Medium"; }
                    elseif($k>=0.7 && $k<2.0){ $k_desc = "High"; }
                    elseif($k>= 2.0){ $k_desc = "Very High"; }
                    elseif($k =="trace"){ $k_desc = "trace"; }

                    if($k<=0.25){$k_requiremt = "Apply 100g of MOP in a planting hole"; }
                    elseif($k>0.25 && $k<=0.75){ $k_requiremt = "Apply 50-60g of MOP in a planting hole"; }
                    elseif($k>=0.8 && $k<=1.9){ $k_requiremt = "Apply 30g of MOP in a planting hole"; }
                    else{$k_requiremt = "No further addition"; }

                    $rows_cash_returned= $mCrudFunctions->get_sum("cash_returned_".$dataset_id,"cash_returned"," farmer_id='$id' ");

                    $rows_tractor_money_returned= $mCrudFunctions->get_sum("tractor_money_returned_".$dataset_id,"tractor_money_returned"," farmer_id='$id' ");

                    $yield=$rows_yield[0]['qty']==""?"N/A": $rows_yield[0]['qty'];

                    $tractor_money_taken=$tractor_money;//$rows_tractor_money_taken[0]['tractor_money'];
                    //$cash_taken=$rows_cash[0]['cash_taken'];

                    $rows_tractor_money_owed= number_format((int)$tractor_money_taken-(int)$rows_tractor_money_returned);
                    $cash_owed= number_format((int)$cash_taken-(int)$rows_cash_returned);
                    $cash_taken=number_format($cash_taken);
                    $rows_cash_returned=number_format($rows_cash_returned);

                    $rows_tractor_money_returned=number_format($rows_tractor_money_returned);
                    $tractor_money_taken=number_format($tractor_money_taken);


                    if( sizeof($rows_seeds)>0){
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Seed Taken</h5>
   <table class=\"striped\" style=\"width:98%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px 5px 5px 5px; height:2em; margin-left:5px; margin-right:5px;\">
              <th >#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss=0;
                        foreach( $rows_seeds as $seed){
                            $ss++;
                            $reciept=$seed['reciept_url'];
                            $type=$seed['item'];
                            $qty=$seed['qty'];
                            $units=$seed['units'];
                            echo"<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td >$type</td>
            <td>$qty $units</td>
          </tr>";

                        }

                        echo"</tbody>
      </table>
  </div>
</div>";

                    }

                    if( sizeof($rows_herbicide)>0){
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Herbicide Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss=0;
                        foreach( $rows_herbicide as $herbicide){
                            $ss++;
                            $reciept=$fertilizer['reciept_url'];
                            $type=$herbicide['item'];
                            $qty=$herbicide['qty'];
                            $units=$herbicide['units'];

                            echo"<tr class='open-image' data-target='images/va_receipt/$reciept'>
		    <td  ><a >$ss</a></td>
            <td >$type</td>
            <td >$qty $units</td>
          </tr>";

                        }

                        echo"</tbody>
      </table>

  </div>
</div>";

                    }

                    if( sizeof($rows_fertilizers)>0){
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Fertlizer Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss=0;
                        foreach( $rows_fertilizers as $fertilizer){
                            $ss++;
                            $reciept=$fertilizer['reciept_url'];
                            $type=$fertilizer['item'];
                            $qty=$fertilizer['qty'];
                            $units=$fertilizer['units'];
                            echo"<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td  >$type</td>
            <td  >$qty $units</td>
          </tr>";

                        }

                        echo"</tbody>
      </table>

  </div>
</div>";

                    }

                    echo"<div class=\"col-sm-12 col-md-12 col-lg-7\" style=\"margin-bottom:100px;\">
<div class=\"card prodn\" style='font-family: Arial'>
<h5 class=\"\">Production Data</h5>";
                    echo "
        <h6>Acreage:</h6><p id=\"total\">$acreage</p><hr/>
        <h6>Crop:</h6><p id=\"enterprise_\">Coffee</p><hr/>
        <h6><b> Soil Properties </b></h6><p><b>Values</b></p>&nbsp;<b>Description</b><hr/>
        <h6 class=\"trim\">pH Value:</h6><p>$ph</p>&nbsp;$ph_desc<hr/>
        <h6 class=\"trim\">Organic Matter(%):</h6><p>$om</p>&nbsp;$om_desc<hr/>
        <h6 class=\"trim\">Nitrogen Content(%):</h6><p>$n</p>&nbsp;$nitrogen_desc<hr/>
        <h6 class=\"trim\">Phosphorous Content(ppm):</h6><p>$p</p>&nbsp;$p_desc<hr/>
        <h6 class=\"trim\">Potassium Content(cmol/kg):</h6><p>$k</p>&nbsp;$k_desc<hr/>    
        <h6><b> Recommendations</b></h6><hr/>
        <h6 class='trim'>pH:</h6><p>$n_requiremt</p><hr/>
       <h6 class='trim'>Nitrogen Requirement:</h6><p>$ph_requiremt</p><hr/>  
        <h6 class='trim'>Potassium Requirement:</h6><p>$k_requiremt</p><hr/>
        <h6 class='trim'>Phosphorous Requirement:</h6><p>$p_requiremt</p><hr/>
        ";

                    echo"</div>
    </div>
  </div>";

                    /////////////////////////////////////////////production data_ends
                }
                elseif ($_SESSION["account_name"] == "Insurance") {


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


                    echo "</div>";
////////////////////////////////////////////////// lat_long_pic endss

                    $table="dataset_".$dataset_id;
                    $columns="*";
                    $where=" id='$id' ";
                    $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);

/////////////////////////////////////////////personal data_starts
                    echo"<div class=\"row data1\">
<div class=\"col-sm-12 col-md-5 col-lg-5\">
<div class=\"card\">
<h5 class=\"\" style=\"\">Personal Profile</h5>";
//    echo"        <h6>Name:</h6><p class=\"align\">$name</p><hr/>";

                    foreach($rows2 as $row){
                        $name = $row['biodata_farmer_name'];
                        $contact = $row['biodata_farmer_phone_number'];
                        $gender = $util_obj->captalizeEachWord($row['biodata_farmer_gender']);
                        $dob = $row['biodata_farmer_dob'];
                        $region = $util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_region']);
                        $district = $util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_district']);
                        $subcounty = $row['biodata_farmer_location_farmer_subcounty'];
                        $parish = $row['biodata_farmer_location_farmer_parish'];
                        $village = $row['biodata_farmer_location_farmer_village'];
                        $ace = $row['biodata_farmer_organization_name'];
                        $ace_person = $row['biodata_ace_contact_person'];
                        $ace_person_contact = $row['biodata_ace_contact'];
                        $ace_county = $row['biodata_ace_county'];
                        $ace_subcounty = $row['biodata_ace_subcounty'];

                        echo"       
             <h6>Name:</h6><p class=\"align\">$name</p><hr>
             <h6>Contact:</h6><p class=\"align\">$contact</p><hr/>
             <h6>Gender:</h6><p class=\"align\">$gender</p><hr/>
             <h6>Date Of Birth:</h6><p class=\"align\">$dob</p><hr/>
             <h6>Region:</h6><p class=\"align\">$region</p><hr/>
             <h6>District:</h6><p class=\"align\">$district</p><hr/>
             <h6>Subcounty:</h6><p class=\"align\">$subcounty</p><hr/>
             <h6>Parish:</h6><p class=\"align\">$parish</p><hr/>
             <h6>Village:</h6><p class=\"align\">$village</p><hr/>
             <h6><b>ACE DETAILS</b></h6><hr/>
             <h6>Name:</h6><p class=\"align\">$ace</p><hr/>
             <h6>Contact Person:</h6><p class=\"align\">$ace_person</p><hr/>
             <h6>Contact:</h6><p class=\"align\">$ace_person_contact</p><hr/>
             <h6>County:</h6><p class=\"align\">$ace_county</p><hr/>
             <h6>Subcounty:</h6><p class=\"align\">$ace_subcounty</p><hr/>
                          
             ";

//        $value=$util_obj->captalizeEachWord($value);
//        $string=str_replace("biodata_farmer_","",$column);
//        $string=str_replace("_"," ",$string);
//        $lable=$util_obj->captalizeEachWord($string);
//        if($lable!="Picture"){
//
//            if($lable=="Dob"){
//                $value=str_replace("00:00:000"," ",$value);
//                $birth_date=date_create( $value);
//                $birth_date=date_format($birth_date,"d/m/Y");
//                echo"<h6>Date of Birth:</h6>
//
//  <p class=\"align\">$birth_date</p><hr/>";
//                $age=$util_obj->getAge( $value,"Africa/Nairobi");
//                echo"<h6>Age:</h6>
//  <p class=\"align\">$age</p><hr/>";
//
//            }elseif ($lable == "Image"){
//                echo"<h6 hidden>$lable:</h6>
//  <p class=\"align\" hidden>$value</p><hr/>";
//            }else{
//                echo"<h6>$lable:</h6>
//  <p class=\"align\">$value</p><hr/>";
//            }
//        }

                    }

//    $table="farmer_location";
//    $columns="*";
//    $where=" dataset_id='$dataset_id' ";
//    $rows3= $mCrudFunctions->fetch_rows($table,$columns,$where);
//
//    $va=$rows[0]['interview_particulars_va_name'];
//    $vaphone=$rows[0]['interview_particulars_va_phone_number'];
//    $vacode=$rows[0]['interview_particulars_va_code'];
//    echo"<input type=\"hidden\" id=\"va\" value=\"$va\" />";
//    echo"<input type=\"hidden\" id=\"vaphone\" value=\"$vaphone\" />";
//    echo"<input type=\"hidden\" id=\"vacode\" value=\"$vacode\" />";
//
//    foreach($rows3 as $row){
//        $column=$row['columns'];
//        $value=$rows[0][$column];
//        $value=$util_obj->captalizeEachWord($value);
//        $string=str_replace("biodata_farmer_location_farmer_","",$column);
//        $string=str_replace("_"," ",$string);
//        $lable=$util_obj->captalizeEachWord($string);
//        if(!strpos($column,'_gps_')){
//            echo"<h6>$lable:</h6>
//  <p class=\"align\">$value</p><hr/>";
//        }
//
//
//    }

                    echo"</div>";
/////////////////////////////////////////////personal data_ends


///////////////////////////////////////////other data_starts
    echo"<div class=\"card\" hidden='hidden'>
<h5 class=\"\" hidden='hidden'>Others</h5>";
    $table="info_on_other_enterprise";
    $columns="*";
    $where=" dataset_id='$dataset_id' ";
    $rows5= $mCrudFunctions->fetch_rows($table,$columns,$where);

    foreach($rows5 as $row){
        $column=$row['columns'];
        $value=$rows[0][$column];
        $value=$util_obj->captalizeEachWord($value);
        $string="information_on_other_crops_";
        $string=str_replace($string,"",$column);
        $string=str_replace("_"," ",$string);
        $lable=$util_obj->captalizeEachWord($string);
        if($value!=null){
            echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
        }
    }


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

    echo"</div>
</div>
";
///////////////////////////////////////////other data_ends

/////////////////////////////////////////////production data_starts


                    $rows_seeds = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Seed' ");
                    $rows_fertilizers = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Fertilizer' ");
                    $rows_herbicide = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Herbicide' ");
                    $cash_taken = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='cashtaken' ");

                    $tractor_money = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='tractor' ");

                    //$rows_tractor_money_taken= $mCrudFunctions->fetch_rows("tractormoney_".$dataset_id,"*"," farmer_id='$id' ");


                    $rows_yield = $mCrudFunctions->fetch_rows("dataset_" . $dataset_id, "*", " id='$id' ");


                    $rows_cash_returned = $mCrudFunctions->get_sum("cash_returned_" . $dataset_id, "cash_returned", " farmer_id='$id' ");

                    $rows_tractor_money_returned = $mCrudFunctions->get_sum("tractor_money_returned_" . $dataset_id, "tractor_money_returned", " farmer_id='$id' ");

                    $yield = $rows_yield[0]['production_data_average_yield_in_kgs_per_acre'] == "" ? "N/A" : $rows_yield[0]['production_data_average_yield_in_kgs_per_acre'];
                    $crop = $rows_yield[0]['production_data_crop_insured'];
                    $crop = $util_obj->captalizeEachWord($crop);

                    $tractor_money_taken = $tractor_money;//$rows_tractor_money_taken[0]['tractor_money'];
                    //$cash_taken=$rows_cash[0]['cash_taken'];

                    $rows_tractor_money_owed = number_format((int)$tractor_money_taken - (int)$rows_tractor_money_returned);
                    $cash_owed = number_format((int)$cash_taken - (int)$rows_cash_returned);
                    $cash_taken = number_format($cash_taken);
                    $rows_cash_returned = number_format($rows_cash_returned);

                    $rows_tractor_money_returned = number_format($rows_tractor_money_returned);
                    $tractor_money_taken = number_format($tractor_money_taken);

                    echo "
  <div class=\" col-sm-12 col-md-7 col-lg-7\">
    <div class=\"card prodn\">
  <h5 class=\"\">Outgrower profile</h5>
  <div class=\"caption\">
  
   <h6>Acreage:</h6>
   <p id=\"total\">N/A</p><hr/>
   <h6>Crop:</h6>
   <p>$crop</p><hr/>
   <h6>Yield:</h6>
  <p>$yield KGS</p><hr/>
  <h6>VA</h6>
  <p id=\"va_\">N/A</p><hr/>
  <h6>Cash Taken:</h6>
  <p>UGX $cash_taken</p><hr/>
  <!--<h6>Cash Paid:</h6>
   <p>UGX $rows_cash_returned</p><hr/>
  <h6>Cash Owed To Farmer:</h6>
  <p>UGX $cash_owed</p><hr/>  
  -->
   <h6>Tractor Amount:</h6>
   <p>UGX $tractor_money_taken</p><hr/>
  <!-- 
   <h6>Tractor Amount Paid:</h6>
   <p>UGX $rows_tractor_money_returned</p><hr/>
   
   <h6>Tractor Amount Owed To Farmer:</h6>
   <p>UGX $rows_tractor_money_owed</p><hr/>-->
  </div>

  </div>
</div>";

                    if (sizeof($rows_seeds) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Seed Taken</h5>
   <table class=\"striped\" style=\"width:98%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px 5px 5px 5px; height:2em; margin-left:5px; margin-right:5px;\">
              <th >#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_seeds as $seed) {
                            $ss++;
                            $reciept = $seed['reciept_url'];
                            $type = $seed['item'];
                            $qty = $seed['qty'];
                            $units = $seed['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td >$type</td>
            <td>$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>
  </div>
</div>";

                    }

                    if (sizeof($rows_herbicide) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Herbicide Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_herbicide as $herbicide) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $herbicide['item'];
                            $qty = $herbicide['qty'];
                            $units = $herbicide['units'];

                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		    <td  ><a >$ss</a></td>
            <td >$type</td>
            <td >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

                    if (sizeof($rows_fertilizers) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Fertlizer Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_fertilizers as $fertilizer) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $fertilizer['item'];
                            $qty = $fertilizer['qty'];
                            $units = $fertilizer['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td  >$type</td>
            <td  >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

                    echo "<div class=\"col-sm-12 col-md- col-lg-7\" style=\"margin-bottom:100px;\">
<div class=\"card prodn\" hidden='hidden'>
<h5 class=\"\" hidden='hidden'>Production Data</h5>";


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

                    echo "<input type=\"hidden\" id=\"enterprise\" value=\"$enterprise\" /> ";

                    echo "</div>
    </div>
  </div>";

                    /////////////////////////////////////////////production data_ends


                }
                elseif ($_SESSION['account_name'] == 'Kiima Foods'){

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


                    echo "</div>";
////////////////////////////////////////////////// lat_long_pic endss

                    $table = "bio_data";
                    $columns = "*";
                    $where = " dataset_id='$dataset_id' ";
                    $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);

/////////////////////////////////////////////personal data_starts
                    echo "<div class=\"row data1\">
<div class=\"col-sm-12 col-me-5 col-lg-5\">
<div class=\"card\">
<h5 class=\"\" style=\"\">Personal Profile</h5>";

                    foreach ($rows2 as $row) {
                        $column = $row['columns'];
                        $value = $rows[0][$column];
                        $value = $util_obj->captalizeEachWord($value);
                        $string = str_replace("biodata_farmer_", "", $column);
                        $string = str_replace("_", " ", $string);
                        $lable = $util_obj->captalizeEachWord($string);
                        if ($lable != "Picture") {

                            if ($lable == "Dob") {
                                $value = str_replace("00:00:000", " ", $value);
                                $birth_date = date_create($value);
                                $birth_date = date_format($birth_date, "d/m/Y");
                                echo "<h6>Date of Birth:</h6>
  
            <p class=\"align\">$birth_date</p><hr/>";
                                $age = $util_obj->getAge($value, "Africa/Nairobi");
                                echo "<h6>Age:</h6>
            <p class=\"align\">$age</p><hr/>";

                            } else {
                                echo "<h6>$lable:</h6>
                <p class=\"align\">$value</p><hr/>";
                            }
                        }

                    }

                    $table = "farmer_location";
                    $columns = "*";
                    $where = " dataset_id='$dataset_id' ";
                    $rows3 = $mCrudFunctions->fetch_rows($table, $columns, $where);

                    $va = $rows[0]['interview_particulars_va_name'];
                    $vaphone = $rows[0]['interview_particulars_va_phone_number'];
                    $vacode = $rows[0]['interview_particulars_va_code'];
                    echo "<input type=\"hidden\" id=\"va\" value=\"$va\" />";
                    echo "<input type=\"hidden\" id=\"vaphone\" value=\"$vaphone\" />";
                    echo "<input type=\"hidden\" id=\"vacode\" value=\"$vacode\" />";

                    foreach ($rows3 as $row) {
                        $column = $row['columns'];
                        $value = $rows[0][$column];
                        $value = $util_obj->captalizeEachWord($value);
                        $string = str_replace("biodata_farmer_location_farmer_", "", $column);
                        $string = str_replace("_", " ", $string);
                        $lable = $util_obj->captalizeEachWord($string);
                        if (!strpos($column, '_gps_')) {
                            echo "<h6>$lable:</h6>
            <p class=\"align\">$value</p><hr/>";
                        }

                    }

                    echo "</div>";
/////////////////////////////////////////////personal data_ends

///////////////////////////////////////////other data_starts
                    echo"<div class=\"card\">
<h5 class=\"\">Others</h5>";
                    $table="info_on_other_enterprise";
                    $columns="*";
                    $where=" dataset_id='$dataset_id' ";
                    $rows5= $mCrudFunctions->fetch_rows($table,$columns,$where);

                    foreach($rows5 as $row){
                        $column=$row['columns'];
                        $value=$rows[0][$column];
                        $value=$util_obj->captalizeEachWord($value);
                        $string="information_on_other_crops_";
                        $string=str_replace($string,"",$column);
                        $string=str_replace("_"," ",$string);
                        $lable=$util_obj->captalizeEachWord($string);
                        if($value!=null){
                            echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
                        }
                    }


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

                    echo"</div>
</div>
";
///////////////////////////////////////////other data_ends

/////////////////////////////////////////////production data_starts

                    $rows_seeds = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Seed' ");
                    $rows_fertilizers = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Fertilizer' ");
                    $rows_herbicide = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Herbicide' ");
                    $cash_taken = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='cashtaken' ");

                    $tractor_money = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='tractor' ");

                    $acres="";   $crop="";

                    $rows_yield = $mCrudFunctions->fetch_rows("dataset_" . $dataset_id, "*", " id='$id' ");
                    $yield = $rows_yield[0]['crop_production_data_maize_harvested'] ? $rows_yield[0]['crop_production_data_maize_harvested'] : "N/A";
                    $crop = $rows_yield[0]['crop_production_data_last_season_main_crop'] ? $rows_yield[0]['crop_production_data_last_season_main_crop'] : "N/A";
                    $acres = $rows_yield[0]['crop_production_data_maize_production_land'] ? $rows_yield[0]['crop_production_data_maize_production_land'] : "N/A";

                    $rows_cash_returned = $mCrudFunctions->get_sum("cash_returned_" . $dataset_id, "cash_returned", " farmer_id='$id' ");

                    $rows_tractor_money_returned = $mCrudFunctions->get_sum("tractor_money_returned_" . $dataset_id, "tractor_money_returned", " farmer_id='$id' ");

                    $tractor_money_taken = $tractor_money;//$rows_tractor_money_taken[0]['tractor_money'];

                    $rows_tractor_money_owed = number_format((int)$tractor_money_taken - (int)$rows_tractor_money_returned);
                    $cash_owed = number_format((int)$cash_taken - (int)$rows_cash_returned);
                    $cash_taken = number_format($cash_taken);
                    $rows_cash_returned = number_format($rows_cash_returned);

                    $rows_tractor_money_returned = number_format($rows_tractor_money_returned);
                    $tractor_money_taken = number_format($tractor_money_taken);

                    echo "
  <div class=\" col-sm-12 col-md-7 col-lg-7\">
    <div class=\"card prodn\">
  <h5 class=\"\">Outgrower profile</h5>
  <div class=\"caption\">
  
   <h6>Acreage:</h6><p>$acres</p><hr/>
   <h6>Crop:</h6><p>$crop</p><hr/>
   <h6>Yield:</h6><p>$yield KGS</p><hr/>
  <h6>VA</h6>
  <p id=\"va_\">N/A</p><hr/>
  <h6>Cash Taken:</h6>
  <p>UGX $cash_taken</p><hr/>
  <!--<h6>Cash Paid:</h6>
   <p>UGX $rows_cash_returned</p><hr/>
  <h6>Cash Owed To Farmer:</h6>
  <p>UGX $cash_owed</p><hr/>  
  -->
   <h6>Tractor Amount:</h6>
   <p>UGX $tractor_money_taken</p><hr/>
  <!-- 
   <h6>Tractor Amount Paid:</h6>
   <p>UGX $rows_tractor_money_returned</p><hr/>
   
   <h6>Tractor Amount Owed To Farmer:</h6>
   <p>UGX $rows_tractor_money_owed</p><hr/>-->
  </div>

  </div>
</div>";

                    if (sizeof($rows_seeds) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Seed Taken</h5>
   <table class=\"striped\" style=\"width:98%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px 5px 5px 5px; height:2em; margin-left:5px; margin-right:5px;\">
              <th >#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_seeds as $seed) {
                            $ss++;
                            $reciept = $seed['reciept_url'];
                            $type = $seed['item'];
                            $qty = $seed['qty'];
                            $units = $seed['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td >$type</td>
            <td>$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>
  </div>
</div>";

                    }

                    if (sizeof($rows_herbicide) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Herbicide Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_herbicide as $herbicide) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $herbicide['item'];
                            $qty = $herbicide['qty'];
                            $units = $herbicide['units'];

                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		    <td  ><a >$ss</a></td>
            <td >$type</td>
            <td >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

                    if (sizeof($rows_fertilizers) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Fertlizer Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_fertilizers as $fertilizer) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $fertilizer['item'];
                            $qty = $fertilizer['qty'];
                            $units = $fertilizer['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td  >$type</td>
            <td  >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

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

                    echo "<input type=\"hidden\" id=\"enterprise\" value=\"$enterprise\" /> ";

                    echo "</div>
    </div>
  </div>";

                    /////////////////////////////////////////////production data_ends


                }
                elseif($_SESSION['account_name'] == "Savannah Commodities") {
                    $bio = $mCrudFunctions->fetch_rows("dataset_".$dataset_id, '*', 'id='.$id);
                    $picture = $util_obj->remove_apostrophes($bio[0]['biodata_farmer_picture']);

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


                    echo "</div>";
////////////////////////////////////////////////// lat_long_pic endss

                    $table = "bio_data";
                    $columns = "*";
                    $where = " dataset_id='$dataset_id' ";
                    $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);

/////////////////////////////////////////////personal data_starts
                    echo "<div class=\"row data1\">
<div class=\"col-sm-12 col-me-5 col-lg-5\">
<div class=\"card\">
<h5 class=\"\" style=\"\">Personal Profile</h5>";

                    foreach ($rows2 as $row) {
                        $column = $row['columns'];
                        $value = $rows[0][$column];
                        $value = $util_obj->captalizeEachWord($value);
                        $string = str_replace("biodata_farmer_", "", $column);
                        $string = str_replace("_", " ", $string);
                        $lable = $util_obj->captalizeEachWord($string);
                        if ($lable != "Picture") {

                            if ($lable == "Dob") {
                                $value = str_replace("00:00:000", " ", $value);
                                $birth_date = date_create($value);
                                $birth_date = date_format($birth_date, "d/m/Y");
                                echo "<h6>Date of Birth:</h6>
  
            <p class=\"align\">$birth_date</p><hr/>";
                                $age = $util_obj->getAge($value, "Africa/Nairobi");
                                echo "<h6>Age:</h6>
            <p class=\"align\">$age</p><hr/>";

                            } else {
                                echo "<h6>$lable:</h6>
                <p class=\"align\">$value</p><hr/>";
                            }
                        }

                    }

                    $table = "farmer_location";
                    $columns = "*";
                    $where = " dataset_id='$dataset_id' ";
                    $rows3 = $mCrudFunctions->fetch_rows($table, $columns, $where);

                    $va = $rows[0]['interview_particulars_va_name'];
                    $vaphone = $rows[0]['interview_particulars_va_phone_number'];
                    $vacode = $rows[0]['interview_particulars_va_code'];
                    echo "<input type=\"hidden\" id=\"va\" value=\"$va\" />";
                    echo "<input type=\"hidden\" id=\"vaphone\" value=\"$vaphone\" />";
                    echo "<input type=\"hidden\" id=\"vacode\" value=\"$vacode\" />";

                    foreach ($rows3 as $row) {
                        $column = $row['columns'];
                        $value = $rows[0][$column];
                        $value = $util_obj->captalizeEachWord($value);
                        $string = str_replace("biodata_farmer_location_farmer_", "", $column);
                        $string = str_replace("_", " ", $string);
                        $lable = $util_obj->captalizeEachWord($string);
                        if (!strpos($column, '_gps_')) {
                            echo "<h6>$lable:</h6>
            <p class=\"align\">$value</p><hr/>";
                        }

                    }

                    echo "</div>";
/////////////////////////////////////////////personal data_ends

///////////////////////////////////////////other data_starts
                    echo"<div class=\"card\">
<h5 class=\"\">Others</h5>";
                    $table="info_on_other_enterprise";
                    $columns="*";
                    $where=" dataset_id='$dataset_id' ";
                    $rows5= $mCrudFunctions->fetch_rows($table,$columns,$where);

                    foreach($rows5 as $row){
                        $column=$row['columns'];
                        $value=$rows[0][$column];
                        $value=$util_obj->captalizeEachWord($value);
                        $string="information_on_other_crops_";
                        $string=str_replace($string,"",$column);
                        $string=str_replace("_"," ",$string);
                        $lable=$util_obj->captalizeEachWord($string);
                        if($value!=null){
                            echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
                        }
                    }

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

                    echo"</div>
</div>
";
///////////////////////////////////////////other data_ends

/////////////////////////////////////////////production data_starts

                    $rows_seeds = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Seed' ");
                    $rows_fertilizers = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Fertilizer' ");
                    $rows_herbicide = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Herbicide' ");
                    $cash_taken = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='cashtaken' ");

                    $tractor_money = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='tractor' ");
//                    echo     $dataset_id."  ".$id;
                    $crop = $mCrudFunctions->fetch_rows("dataset_".$dataset_id, "*", "id='$id'")[0]['crop_production_data_crop_name'];
                    $crop = str_replace("_", " ", $crop);
//$rows_tractor_money_taken= $mCrudFunctions->fetch_rows("tractormoney_".$dataset_id,"*"," farmer_id='$id' ");

                    $rows_yield = $mCrudFunctions->fetch_rows("dataset_" . $dataset_id, "*", " id='$id' ");
                    $yield = $rows_yield[0]['qty'] == "" ? "N/A" : $rows_yield[0]['qty'].'KGS' ;
                    $unik_id = $rows_yield[0]['unique_id'];

                    $samples = $mCrudFunctions->fetch_rows("soil_results_" . $dataset_id, "*", " unique_id='$unik_id' ");
                    /**** ph values and their description ***/
                    $ph = $samples[0]['ph'];    $ph_desc=""; $n_requiremt="";
                    if($ph <= 4.5){ $ph_desc = "Extremely acidic"; }
                    elseif($ph>=4.6 && $ph<=5.5){ $ph_desc = "Strongly acidic"; }
                    elseif($ph>=5.6 && $ph<=6.0){ $ph_desc = "Moderately acidic"; }
                    elseif($ph>=6.1 && $ph<=6.5){ $ph_desc = "Slightly acidic"; }
                    elseif($ph>=6.6 && $ph<=7.2){ $ph_desc = "Neutral"; }
                    elseif($ph>=7.3 && $ph<=7.8){ $ph_desc = "Slightly alkaline"; }
                    elseif($ph>=7.9 && $ph<=8.4){ $ph_desc = "Moderately alkaline"; }
                    elseif($ph>=8.5 && $ph<=9.0){ $ph_desc = "Strongly alkaline"; }
                    else{$ph_desc = "Very strongly alkaline";}

                    /** organic matter values and their decsription / meaning **/
                    $om = $samples[0]['om_%'];  $om_desc="";
                    if($om>=0 && $om<=2.5){$om_desc = "Low" ;}
                    elseif($om>2.5 && $om<=3.5){$om_desc = "Moderate" ;}
                    elseif($om>3.5 && $om<=4.9){$om_desc = "High" ;}
                    else{ $om_desc = "Very High"; }

                    /** nitrogen values and their meanings **/
                    $n = $samples[0]['n_%'];    $nitrogen_desc="";
                    if($n < 0.05){$nitrogen_desc = "Very low"; }
                    elseif($n >= 0.05 && $n < 0.15){ $nitrogen_desc = "Low"; }
                    elseif($n >= 0.15 && $n < 0.25){ $nitrogen_desc = "Medium"; }
                    elseif($n >= 0.25 && $n < 0.5){ $nitrogen_desc = "High"; }
                    else { $nitrogen_desc = "Very high"; }

                    if($nitrogen_desc == "High" || $nitrogen_desc == "Very high"){$n_requiremt = "Apply 100kg/ha of nitrogen for single fertilisers"; }
                    else { $n_requiremt = "No need"; }

                    /** phosphorous values and their meanings **/
                    $p = $samples[0]['p_ppm'];  $p_desc=""; $p_requiremt="";
                    if($p>=0 && $p<=12){ $p_desc = "Very Low"; }
                    elseif($p>=12.5 && $p<=22.5){$p_desc = "Low"; }
                    elseif($p>=23 && $p<=35.5){$p_desc = "Medium"; }
                    elseif($p>=36 && $p<=68.5){$p_desc = "High"; }
                    elseif($p >= 69){$p_desc = "Very High"; }

                    if($p_desc == "Very Low" || $p_desc == "Low"){$p_requiremt = "Apply 45 kg/ha of phosphates for single fertilisers by working it thoroughly in the soil before planting";}
                    else{$p_requiremt = "No need";}

                    /** potassium values and their meanings **/
                    $k = $samples[0]['k'];  $k_desc=""; $k_requiremt="";
                    if($k>=0 && $k<0.2){ $k_desc = "Very Low"; }
                    elseif($k>=0.2 && $k<0.3){ $k_desc = "Low"; }
                    elseif($k>=0.3 && $k<0.7){ $k_desc = "Medium"; }
                    elseif($k>=0.7 && $k<2.0){ $k_desc = "High"; }
                    elseif($k>= 2.0){ $k_desc = "Very High"; }
                    elseif($k =="trace"){ $k_desc = "trace"; }

                    if($k_desc == "Very Low" || $k_desc == "Low" || $k_desc == "trace"){$k_requiremt = "Apply 60 kg/ha of potassium for single fertilisers"; }
                    elseif($k_desc == "Medium"){$k_requiremt = "Apply 30 kg/ha of potassium for single fertilisers"; }
                    else{$k_requiremt = "No need"; }

                    /** magnesium values and their meaning **/
                    $mg = $samples[0]['mg'];    $mg_desc="";
                    if($mg>=0 && $mg<0.3){ $mg_desc = "Very Low"; }
                    elseif($mg>=0.3 && $mg<1.0){ $mg_desc = "Low"; }
                    elseif($mg>=1.0 && $mg<3.0){ $mg_desc = "Medium"; }
                    elseif($mg>=3.0 && $mg<8.0){ $mg_desc = "High"; }
                    elseif($mg>= 8.0){ $mg_desc = "Very High"; }


                    $rows_cash_returned = $mCrudFunctions->get_sum("cash_returned_" . $dataset_id, "cash_returned", " farmer_id='$id' ");
                    $rows_tractor_money_returned = $mCrudFunctions->get_sum("tractor_money_returned_" . $dataset_id, "tractor_money_returned", " farmer_id='$id' ");
                    $tractor_money_taken = $tractor_money;//$rows_tractor_money_taken[0]['tractor_money'];

                    $rows_tractor_money_owed = number_format((int)$tractor_money_taken - (int)$rows_tractor_money_returned);
                    $cash_owed = number_format((int)$cash_taken - (int)$rows_cash_returned);
                    $cash_taken = number_format($cash_taken);
                    $rows_cash_returned = number_format($rows_cash_returned);

                    $rows_tractor_money_returned = number_format($rows_tractor_money_returned);
                    $tractor_money_taken = number_format($tractor_money_taken);

                    echo "
  <div class=\" col-sm-12 col-md-7 col-lg-7\">
    <div class=\"card prodn\">
  <h5 class=\"\">Outgrower profile</h5>
  <div class=\"caption\">
  
   <h6>Crop Acreage:</h6>
   <p id=\"total\">$acres</p><hr/>
   <h6>Crop:</h6>
   <p id=\"\">$crop</p><hr/>
   <h6>Yield:</h6>
  <p>$yield</p><hr/>
  <h6>VA</h6>
  <p id=\"va_\">N/A</p><hr/>
  <h6>Cash Taken:</h6>
  <p>UGX $cash_taken</p><hr/>
  <!--<h6>Cash Paid:</h6>
   <p>UGX $rows_cash_returned</p><hr/>
  <h6>Cash Owed To Farmer:</h6>
  <p>UGX $cash_owed</p><hr/>  
  -->
   <h6>Tractor Amount:</h6>
   <p>UGX $tractor_money_taken</p><hr/>
  <!-- 
   <h6>Tractor Amount Paid:</h6>
   <p>UGX $rows_tractor_money_returned</p><hr/>
   
   <h6>Tractor Amount Owed To Farmer:</h6>
   <p>UGX $rows_tractor_money_owed</p><hr/>-->
  </div>

  </div>
</div>";

                    if (sizeof($rows_seeds) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Seed Taken</h5>
   <table class=\"striped\" style=\"width:98%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px 5px 5px 5px; height:2em; margin-left:5px; margin-right:5px;\">
              <th >#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_seeds as $seed) {
                            $ss++;
                            $reciept = $seed['reciept_url'];
                            $type = $seed['item'];
                            $qty = $seed['qty'];
                            $units = $seed['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td >$type</td>
            <td>$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>
  </div>
</div>";

                    }

                    if (sizeof($rows_herbicide) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Herbicide Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_herbicide as $herbicide) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $herbicide['item'];
                            $qty = $herbicide['qty'];
                            $units = $herbicide['units'];

                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		    <td  ><a >$ss</a></td>
            <td >$type</td>
            <td >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

                    if (sizeof($rows_fertilizers) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Fertlizer Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_fertilizers as $fertilizer) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $fertilizer['item'];
                            $qty = $fertilizer['qty'];
                            $units = $fertilizer['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td  >$type</td>
            <td  >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

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

                            echo "<h6 class=\"trim\">$lable:</h6><p>$value</p><hr/>";

                        }
                    }

                    echo "<input type=\"hidden\" id=\"enterprise\" value=\"$enterprise\" /> ";
                    echo "  <br>     
                        <h6><b style='font-size: medium'> Soil Results </b></h6><hr/>
                        <p style='padding-left: 10px;'><b> Soil Properties </b></p><p style='padding-left: 60px'><b>Values</b></p><b style='padding-left: 70px'>Description</b><hr/>
                        <h6 class=\"trim\">pH:</h6><p>$ph</p>&nbsp;$ph_desc<br/>
                        <h6 class=\"trim\">Organic Matter(%):</h6><p>$om</p>&nbsp;$om_desc<br/>
                        <h6 class=\"trim\">Nitrogen Content(%):</h6><p>$n</p>&nbsp;$nitrogen_desc<br/>
                        <h6 class=\"trim\">Phosphorous Content(ppm):</h6><p>$p</p>&nbsp;$p_desc<br/>
                        <h6 class=\"trim\">Potassium Content(cmol/kg):</h6><p>$k</p>&nbsp;$k_desc<br/> 
                        <h6 class=\"trim\">Magnesium Content(cmol/kg):</h6><p>$mg</p>&nbsp;$mg_desc<hr/> 
                        <br>
                        <h6><b style='font-size: medium'>Recommendations:</b></h6><br>
                        <h7 class='trim'><b>Nitrogen Requirement:</b></h7><p style='width: 600px; padding-left: 10px'>$n_requiremt</p><hr/>                          
                        <h7 class='trim'><b>Phosphorous Requirement:</b></h7><p style='width: 600px; padding-left: 10px'>$p_requiremt</p><hr/>
                        <h7 class='trim'><b>Potassium Requirement:</b></h7><p style='width: 600px; padding-left: 10px'>$k_requiremt</p><hr/>
                        ";
                    echo "</div>
    </div>
  </div>";

                    /////////////////////////////////////////////production data_ends


                }
                else {

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


                    echo "</div>";
////////////////////////////////////////////////// lat_long_pic endss

                    $table = "bio_data";
                    $columns = "*";
                    $where = " dataset_id='$dataset_id' ";
                    $rows2 = $mCrudFunctions->fetch_rows($table, $columns, $where);

/////////////////////////////////////////////personal data_starts  
                    echo "<div class=\"row data1\">
<div class=\"col-sm-12 col-me-5 col-lg-5\">
<div class=\"card\">
<h5 class=\"\" style=\"\">Personal Profile</h5>";

foreach ($rows2 as $row) {
    $column = $row['columns'];
    $value = $rows[0][$column];
    $value = $util_obj->captalizeEachWord($value);
    $string = str_replace("biodata_farmer_", "", $column);
    $string = str_replace("_", " ", $string);
    $lable = $util_obj->captalizeEachWord($string);
    if ($lable != "Picture") {

        if ($lable == "Dob") {
            $value = str_replace("00:00:000", " ", $value);
            $birth_date = date_create($value);
            $birth_date = date_format($birth_date, "d/m/Y");
            echo "<h6>Date of Birth:</h6>
  
            <p class=\"align\">$birth_date</p><hr/>";
            $age = $util_obj->getAge($value, "Africa/Nairobi");
            echo "<h6>Age:</h6>
            <p class=\"align\">$age</p><hr/>";

        } else {
              echo "<h6>$lable:</h6>
                <p class=\"align\">$value</p><hr/>";
        }
    }

}

$table = "farmer_location";
$columns = "*";
$where = " dataset_id='$dataset_id' ";
$rows3 = $mCrudFunctions->fetch_rows($table, $columns, $where);

$va = $rows[0]['interview_particulars_va_name'];
$vaphone = $rows[0]['interview_particulars_va_phone_number'];
$vacode = $rows[0]['interview_particulars_va_code'];
echo "<input type=\"hidden\" id=\"va\" value=\"$va\" />";
echo "<input type=\"hidden\" id=\"vaphone\" value=\"$vaphone\" />";
echo "<input type=\"hidden\" id=\"vacode\" value=\"$vacode\" />";

foreach ($rows3 as $row) {
    $column = $row['columns'];
    $value = $rows[0][$column];
    $value = $util_obj->captalizeEachWord($value);
    $string = str_replace("biodata_farmer_location_farmer_", "", $column);
    $string = str_replace("_", " ", $string);
    $lable = $util_obj->captalizeEachWord($string);
    if (!strpos($column, '_gps_')) {
        echo "<h6>$lable:</h6>
            <p class=\"align\">$value</p><hr/>";
    }

}

echo "</div>";
/////////////////////////////////////////////personal data_ends

///////////////////////////////////////////other data_starts
  echo"<div class=\"card\">
<h5 class=\"\">Others</h5>";
  $table="info_on_other_enterprise";
  $columns="*";
  $where=" dataset_id='$dataset_id' ";
  $rows5= $mCrudFunctions->fetch_rows($table,$columns,$where);

  foreach($rows5 as $row){
   $column=$row['columns'];
  $value=$rows[0][$column];
  $value=$util_obj->captalizeEachWord($value);
  $string="information_on_other_crops_";
   $string=str_replace($string,"",$column);
  $string=str_replace("_"," ",$string);
  $lable=$util_obj->captalizeEachWord($string);
  if($value!=null){
   echo"<h6>$lable:</h6>
  <p class=\"align\">$value</p><hr/>";
  }
  }


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

  echo"</div>
</div>
";
///////////////////////////////////////////other data_ends

/////////////////////////////////////////////production data_starts

$rows_seeds = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Seed' ");
$rows_fertilizers = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Fertilizer' ");
$rows_herbicide = $mCrudFunctions->fetch_rows("out_grower_input_v", "*", "dataset_id='$dataset_id'AND meta_id='$id' AND item_type='Herbicide' ");
$cash_taken = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='cashtaken' ");

$tractor_money = (int)$mCrudFunctions->get_sum("out_grower_cashinput_tb", "amount", " dataset_id='$dataset_id' AND  meta_id='$id' AND cash_type='tractor' ");

$acres="";   $crop="";
//$rows_tractor_money_taken= $mCrudFunctions->fetch_rows("tractormoney_".$dataset_id,"*"," farmer_id='$id' ");
if($_SESSION['account_name'] == "Kiima Foods"){
    $rows_yield = $mCrudFunctions->fetch_rows("dataset_" . $dataset_id, "*", " id='$id' ");
    $yield = $rows_yield[0]['crop_production_data_maize_harvested'] ? $rows_yield[0]['crop_production_data_maize_harvested'] : "N/A";
    $crop = $rows_yield[0]['crop_production_data_last_season_main_crop'] ? $rows_yield[0]['crop_production_data_last_season_main_crop'] : "N/A";
    $acres = $rows_yield[0]['crop_production_data_maize_production_land'] ? $rows_yield[0]['crop_production_data_maize_production_land'] : "N/A";
} else {
    $rows_yield = $mCrudFunctions->fetch_rows("out_grower_produce_tb", "*", " dataset_id='$dataset_id' AND  meta_id='$id' ");
    $yield = $rows_yield[0]['qty'] == "" ? "N/A" : $rows_yield[0]['qty'];
}

$rows_cash_returned = $mCrudFunctions->get_sum("cash_returned_" . $dataset_id, "cash_returned", " farmer_id='$id' ");

$rows_tractor_money_returned = $mCrudFunctions->get_sum("tractor_money_returned_" . $dataset_id, "tractor_money_returned", " farmer_id='$id' ");

$tractor_money_taken = $tractor_money;//$rows_tractor_money_taken[0]['tractor_money'];
//$cash_taken=$rows_cash[0]['cash_taken'];

$rows_tractor_money_owed = number_format((int)$tractor_money_taken - (int)$rows_tractor_money_returned);
$cash_owed = number_format((int)$cash_taken - (int)$rows_cash_returned);
$cash_taken = number_format($cash_taken);
$rows_cash_returned = number_format($rows_cash_returned);

$rows_tractor_money_returned = number_format($rows_tractor_money_returned);
$tractor_money_taken = number_format($tractor_money_taken);

                    echo "
  <div class=\" col-sm-12 col-md-7 col-lg-7\">
    <div class=\"card prodn\">
  <h5 class=\"\">Outgrower profile</h5>
  <div class=\"caption\">
  
   <h6>Acreage:</h6>
   <p id=\"total\">$acres</p><hr/>
   <h6>Crop:</h6>
   <p id=\"enterprise_\">$crop</p><hr/>
   <h6>Yield:</h6>
  <p>$yield KGS</p><hr/>
  <h6>VA</h6>
  <p id=\"va_\">N/A</p><hr/>
  <h6>Cash Taken:</h6>
  <p>UGX $cash_taken</p><hr/>
  <!--<h6>Cash Paid:</h6>
   <p>UGX $rows_cash_returned</p><hr/>
  <h6>Cash Owed To Farmer:</h6>
  <p>UGX $cash_owed</p><hr/>  
  -->
   <h6>Tractor Amount:</h6>
   <p>UGX $tractor_money_taken</p><hr/>
  <!-- 
   <h6>Tractor Amount Paid:</h6>
   <p>UGX $rows_tractor_money_returned</p><hr/>
   
   <h6>Tractor Amount Owed To Farmer:</h6>
   <p>UGX $rows_tractor_money_owed</p><hr/>-->
  </div>

  </div>
</div>";

                    if (sizeof($rows_seeds) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Seed Taken</h5>
   <table class=\"striped\" style=\"width:98%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px 5px 5px 5px; height:2em; margin-left:5px; margin-right:5px;\">
              <th >#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_seeds as $seed) {
                            $ss++;
                            $reciept = $seed['reciept_url'];
                            $type = $seed['item'];
                            $qty = $seed['qty'];
                            $units = $seed['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td >$type</td>
            <td>$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>
  </div>
</div>";

                    }

                    if (sizeof($rows_herbicide) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Herbicide Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_herbicide as $herbicide) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $herbicide['item'];
                            $qty = $herbicide['qty'];
                            $units = $herbicide['units'];

                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		    <td  ><a >$ss</a></td>
            <td >$type</td>
            <td >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

                    if (sizeof($rows_fertilizers) > 0) {
                        echo "
  <div class=\" col-sm-12 col-md-2 col-lg-2\">
    <div class=\"card\">
  <h5>Fertlizer Taken</h5>
   <table class=\"striped\" style=\"width:100%;\">
        <thead>
          <tr style=\"background:#fafafa; padding:5px; height:2em;\">
              <th>#</th>
			  <th>Type</th>
              <th>Quantity</th>
          </tr>
        </thead>
        <tbody>";
                        $ss = 0;
                        foreach ($rows_fertilizers as $fertilizer) {
                            $ss++;
                            $reciept = $fertilizer['reciept_url'];
                            $type = $fertilizer['item'];
                            $qty = $fertilizer['qty'];
                            $units = $fertilizer['units'];
                            echo "<tr class='open-image' data-target='images/va_receipt/$reciept'>
		   <td ><a >$ss</a></td>
            <td  >$type</td>
            <td  >$qty $units</td>
          </tr>";

                        }

                        echo "</tbody>
      </table>

  </div>
</div>";

                    }

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

                    echo "<input type=\"hidden\" id=\"enterprise\" value=\"$enterprise\" /> ";

                    echo "</div>
    </div>
  </div>";

                    /////////////////////////////////////////////production data_ends


                }
///////////////////////////////////////////sdrfghjklytdfhjgkhljtrydryfyujgkhtygh
            }
        }

    } ?>


</div><!--end of main content-->
<div id="hidden_form_container">
</div>
<?php include("./include/footer_client.php"); ?>
<script>
    $(document).ready(function () {
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
