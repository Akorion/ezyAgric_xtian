<?php
session_start();
$client_id = $_SESSION['client_id'];
#includes
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";

//$db= new DatabaseQueryProcessor();

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    output($client_id, $search);
} else {
    output($client_id, "");
}

function output($client_id, $search)
{
    $util_obj = new Utilties();
    $mCrudFunctions = new CrudFunctions();

    $url = " ";     //seting the destination of open dataset action
    if ($_SESSION["account_name"] == "Rushere SACCO"){
        $url = "loadFarmDash.php";
    }else{
        $url = "dashboard.php";
    }

    $sql = "";
    if ($search != "") {
        $sql = " AND ( dataset_name LIKE '%$search%' OR dataset_type LIKE '%$search%' OR region LIKE '%$search%'   )  ";
    }

    $table = "client_datasets_v";
    $columns = "*";
    $where = " client_id='$client_id' " . $sql . " ORDER BY time_of_creation DESC  ";
    $rows = $mCrudFunctions->fetch_rows($table, $columns, $where);
//    print_r($rows);

    echo "<div class=\"x_titles\">
  <div class=\"clearfix\"></div>
</div>";


    foreach ($rows as $row) {
        $dataset_name = $row['dataset_name'];
        $dataset_type = $row['dataset_type'];
        $dataset_id = $row['id'];
        $region = $row['region'];
        $period = "From: ".$row['start_date'] . "<br>To: " . $row['end_date'];

        $table = "dataset_" . $dataset_id;
        $records = $mCrudFunctions->fetch_rows($table, " * ", 1);

        $district = $records[0]['biodata_farmer_location_farmer_district'];
        $va_district = $records[0]['va_location_va_district'];
        $number = sizeof($records);
//        $type = "";
        $s = $number == 1 ? "" : "s";
        $ern = "ern";
        if ($dataset_type == "Farmer") {
            $total_acerage = 0;
            $total_gardens = 0;

////////////////////////////////////////////////////////////////////////////////////////////////
            if ($mCrudFunctions->check_table_exists("garden_" . $dataset_id)) {
                $gardenz = $mCrudFunctions->fetch_rows("total_acerage_tb", "*", " dataset_id='$dataset_id'");

                if (sizeof($gardenz) > 0) {
                    $total_acerage = $gardenz[0]['ttl_acerage'];
                    $total_gardens = $gardenz[0]['ttl_gardens'];
                } else {
                    $total_acerage_gardens = $mCrudFunctions->getTTLAcerage_Gardens($table, $dataset_id);
                    $total_acerage = $total_acerage_gardens[0];
                    $total_gardens = $total_acerage_gardens[1];
                    $mCrudFunctions->insert_into_total_acerage_tb($dataset_id, $total_gardens, $total_acerage);
                }
////////////////////////////////////////////////////////////////////////////////////////////////

            }


            $key = $util_obj->encrypt_decrypt("encrypt", $dataset_id);
            $type = $util_obj->encrypt_decrypt("encrypt", $dataset_type);

            $dataset_name = strlen($dataset_name) > 40 ? substr($dataset_name, 0, 39) . "..." : $dataset_name;// = substr($dataset_name,0,38).'</br>'.substr($dataset_name,38);

            echo "
             <div class='col-md-3 col-sm-4 col-xs-12 text-center'>
              <div class='x_panel'>
                <div class='col-xs-12'>
                  <div class='panel-title'>
                    <div style='' class='col-lg-12'>
                        <span class='fa fa-folder-open' style='font-size: 40px; margin-top: -12px; color: #0D47A1;'>	</span>
                    </div>
                    <h2 style='color: black;'> <br/>
                      <br/>
                      $dataset_name</h2>
                    <h5 style='background-color: #388ac4; color: white;'>$district District</h5>
                  </div>
                </div>
                <div class='col-xs-12'>
                  <div class='content'>
                    <span style='font-size:15px;'>$period</span>

                    <h6>
                    <a href='$url?token=$key&category=$type' class='btn btn-success btn-sm'>
                    Open</a></h6>
                  </div>
                </div>
              </div>
            </div>
            ";
        } elseif($dataset_type == "VA") {

            $key = $util_obj->encrypt_decrypt("encrypt", $dataset_id);
            $type = $util_obj->encrypt_decrypt("encrypt", $dataset_type);

            $dataset_name = strlen($dataset_name) > 40 ? substr($dataset_name, 0, 39) . "..." : $dataset_name;// = substr($dataset_name,0,38).'</br>'.substr($dataset_name,38);


            echo "
             <div class='col-md-3 col-sm-4 col-xs-12 text-center'>
              <div class='x_panel'>
                <div class='col-xs-12'>
                  <div class='panel-title'>
                    <div style='' class='col-lg-12'>
                        <span class='fa fa-folder-open' style='font-size: 40px; margin-top: -12px; color: #0D47A1;'>	</span>
                    </div>
                    <h2 style='color: black;'> <br/>
                      <br/>
                      $dataset_name</h2>
                    <h5 style='background-color: #388ac4; color: white;'>$va_district District</h5>
                  </div>
                </div>
                <div class='col-xs-12'>
                  <div class='content'>
                    <span style='font-size:15px;'>$period</span>

                    <h6>
                    <a href='$url?token=$key&category=$type' class='btn btn-success btn-sm'>
                    Open</a></h6>
                  </div>
                </div>
              </div>
            </div>
            ";

        }

        /*

          <div class=\"col-md-3 col-sm-4 col-xs-12\">
          <div class=\"x_panel tile fixed_height_400\">

          <div class=\"x_title\">
            <h2 style=\"font-size:12px\">$dataset_name</h2>
            <ul class=\"nav navbar-right\">
              <a><i class=\"fa fa-file\" style=\"font-size:17px;\"></i></a>
            </ul>
            <div class=\"clearfix\"></div>
          </div>

          <div class=\"content\">

          <h6>$region</h6>
          <h6>$number <span>$type$s</span></h6>";
          if($total_gardens>0&&$total_acerage>0){

          echo"<p style='padding: 0px 10px; font-size:13px'><b><span>$total_gardens Gardens</span><span> ($total_acerage acres) </span></b></p>

          ";
          }

          echo"<h6>$period</h6><br/>";
          if($type=="Farmer"){
          echo"<a style=\"margin-left:15px;\" id=\"csv\" class=\"btn btn-xs btn-success\"  onclick=\"Export2Csv($dataset_id);\"  >Export to CSV</a>";
          }

          echo"
          <a style=\"margin-left:25px;\" id=\"view\" href=\"view.php?token=$key&type=$type\" class=\"btn btn-xs btn-info\">View Details &raquo;</a>
              </div>
            </div>
          </div>";*/

    }


}

?>
