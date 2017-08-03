
<div class="container-fluid" >
<!--<div class="container-fluid">
<div class="row">-->

<div class="row">
<?php include("../include/sidebar.php");?>
<!--end of sidenav-->
<!-- main content area-->

<div class="col-sm-12 col-md-9 col-lg-9">
<?php 
//echo"<div class=\"col-sm-12 col-md-9 col-lg-9\">";
 #includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/pagination_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();

$token = $_GET['token'];
$type = $_GET['type'];

if(isset($_GET['token'])&&$_GET['token']!="")
{

    $id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
    $dataset_ = $util_obj->encrypt_decrypt("encrypt", $id);
    $dataset = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id");
    $dataset_name = $dataset[0]["dataset_name"];
    $dataset_type = $dataset[0]["dataset_type"];

    if ($dataset_type == "Farmer") {
        include(dirname(dirname(__FILE__)) . "/include/filter.php");
        ///pagination variables

    } else {

    }
}
?>
<div id="filtered_data" class="row down">
    <?php

    if (isset($_GET['token']) && $_GET['token'] != "") {

        $id = $util_obj->encrypt_decrypt("decrypt", $_GET['token']);
        $dataset_ = $util_obj->encrypt_decrypt("encrypt", $id);
        $dataset = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id");
        $dataset_name = $dataset[0]["dataset_name"];
        $dataset_type = $dataset[0]["dataset_type"];
        $table = "dataset_" . $id;
        $columns = "*";
        $where = " 1 LIMIT 16";
        $rows = $mCrudFunctions->fetch_rows($table, $columns, $where);
        if ($dataset_type == "VA") {
            if (sizeof($rows) == 0) {
            } else {
                foreach ($rows as $row) {
                    $real_id = $row['id'];

                    $real_id = $util_obj->encrypt_decrypt("encrypt", $real_id);
                    $name = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['name']));
                    $gender = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['gender']));
                    $dob = '';//$row['biodata_farmer_dob'];
                    $picture = $util_obj->remove_apostrophes($row['picture']);
                    $district = '';//$row['biodata_farmer_location_farmer_district'];
                    $phone_number = $util_obj->remove_apostrophes($row['va_phonenumber']);

                    echo "<div class=\"col-sm-12 col-md-3 col-lg-3\">
  <div class=\"thumbnail card\" id=\"\">
  <img src=\"$picture\" alt=\"Images\" class=\"image\">
  <div class=\"caption\">
  <h6>Name:</h6>
  <p>$name</p><br/>
  <h6>Sex:</h6>
  <p>$gender</p><br/>
  <h6>DOB:</h6>
  <p>$dob</p><br/>
  <h6>District:</h6><p>$district</p><br/>
  <h6>Phone</h6>
  <p>$phone_number</p><br/>
  <p class=\"right\"><a href=\"?action=details&s=$dataset_&token=$real_id&type=$dataset_type\" class=\"btn btn-raised\" >View more &raquo;</a></p>
      </div>
    </div>
  </div>";


                }
            }
        }

    }

    ?>
</div><!--end of row-->

<div class="row container" style="margin-bottom:80px;"><!--pagination row-->
    <div id="pagination_holder" class="col s12 m12 l12">

    </div>
</div>

</div><!--end of main content-->
</div>
</div>
<!--end of main container-->
<script src="../js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/material.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>

<script type="text/javascript">

function getVillage(){
    var sel_district = document.getElementById("sel_district");
    var sel_county = document.getElementById("sel_county");
    var sel_parish = document.getElementById("sel_parish");
	var dataset_id = document.getElementById("dataset_id_holder");

	id= dataset_id .value;
    district= sel_district.value;
    subcounty= sel_county.value;
    parish= sel_parish.value;

	$.ajax({
     type: "POST",
     url: "../form_actions/setFilter.php",
     data: {id: id, village_district: district, village_subcounty: subcounty, village_parish: parish },
     success: function(data) {
         //window.alert(data);
		  $("#sel_village").html(data);
		  console.log(data);
    }
    }).error(function () {
        alert("Ooops, sth went wrong !!!!!");
    });
}

function getParish(){
    var sel_district = document.getElementById("sel_district");
    var sel_county = document.getElementById("sel_county");
	var dataset_id = document.getElementById("dataset_id_holder");

	id= dataset_id .value;
    district= sel_district.value;
	subcounty= sel_county.value; 
	$.ajax({
     type: "POST",
     url: "../form_actions/setFilter.php",
     data: {id: id, parish_subcounty: subcounty, parish_district: district },
     success: function(data) {
         //window.alert(data);
		  $("#sel_parish").html(data);
		  getVillage()
    }
    });

}

function getPagination(page){
    
	var sel_district = document.getElementById("sel_district");
	var sel_county = document.getElementById("sel_county");
	var sel_parish = document.getElementById("sel_parish");
	var sel_village = document.getElementById("sel_village");
	var sel_production_filter = document.getElementById("sel_production_filter");
	var sel_gender=document.getElementById("sel_gender");
	var dataset_id = document.getElementById("dataset_id_holder");
	
	gender=sel_gender.value;
	id= dataset_id .value; 
	sel_production_id=sel_production_filter.value;
	village=sel_village.value;
	parish= sel_parish.value;
	subcounty= sel_county.value;  
	district= sel_district.value; 
	
	
	$.ajax({
     type: "POST",
     url: "../form_actions/set_pagination.php",
     data: {id: id, page: page,district: district, subcounty: subcounty, parish: parish, village: village, gender : gender , sel_production_id : sel_production_id },
     success: function(data) {
         
		  $("#pagination_holder").html(data);
		 
    }
    });

}

function getSubCounties(){
	var sel_district = document.getElementById("sel_district");
	var dataset_id = document.getElementById("dataset_id_holder");
	id= dataset_id .value; 
	district= sel_district.value; 
	
	$.ajax({
     type: "POST",
     url: "../form_actions/setFilter.php",
     data: {id: id, district: district},
     success: function(data) {
         //window.alert(data);
		  $("#sel_county").html(data);
		  getParish();
    }
    });
	
	
	}
	
	$(document).ready(function(){
	
	getProductionFilter();
	getSubCounties();
	filterData();
	getPagination(1);
	});
	
function getProductionFilter(){
  
	var dataset_id = document.getElementById("dataset_id_holder");
	
	id = dataset_id.value; 
	
	$.ajax({
     type: "POST",
     url: "../form_actions/setFilter.php",
     data: {prodution_data_id: id, prodution_data: "true"},
     success: function(data) {
         //window.alert(data);
		  
		  $("#sel_production_filter").html(data);
    }
    });

}

function filterDataPagination(page){
    var sel_total_count = document.getElementById("total_count");
	var sel_per_page = document.getElementById("per_page");
	
	total_count= sel_total_count.value;
	per_page= sel_per_page.value;
	
	var sel_district = document.getElementById("sel_district");
	var sel_county = document.getElementById("sel_county");
	var sel_parish = document.getElementById("sel_parish");
	var sel_village = document.getElementById("sel_village");
	var sel_production_filter = document.getElementById("sel_production_filter");
	var sel_gender=document.getElementById("sel_gender");
	var dataset_id = document.getElementById("dataset_id_holder");
	
	gender=sel_gender.value;
	id= dataset_id .value; 
	sel_production_id=sel_production_filter.value;
	village=sel_village.value;
	parish= sel_parish.value;
	subcounty= sel_county.value;  
	district= sel_district.value;

	var token = "<?php echo $token; ?>";
    var type = "<?php echo $type; ?>";
	
	$.ajax({
     type: "POST",
     url: "../form_actions/getFarmers_admin.php",
     data: { token: token,
             type: type,
             id: id,
	         district: district, 
			 subcounty: subcounty ,
			 parish: parish,
			 village: village,
			 sel_production_id : sel_production_id,
             gender	:gender, page : page, per_page : per_page ,total_count :total_count	 },
             success: function(data) {
             //window.alert(data);
		      $("#filtered_data").html(data);
			  getPagination(page);
              }
    });
	
	
	//document.forms["filter_form"].submit();
}

function filterData(){
    
	var sel_district = document.getElementById("sel_district");
	var sel_county = document.getElementById("sel_county");
	var sel_parish = document.getElementById("sel_parish");
	var sel_village = document.getElementById("sel_village");
	var sel_production_filter = document.getElementById("sel_production_filter");
	var sel_gender=document.getElementById("sel_gender");
	var dataset_id = document.getElementById("dataset_id_holder");
	
	gender=sel_gender.value;
	id= dataset_id .value; 
	sel_production_id=sel_production_filter.value;
	village=sel_village.value;
	parish= sel_parish.value;
	subcounty= sel_county.value;  
	district= sel_district.value;

    var token = "<?php echo $token; ?>";
    var type = "<?php echo $type; ?>";

	$.ajax({
     type: "POST",
     url: "../form_actions/getFarmers_admin.php",
     data: { token: token,
             type: type,

             id: id,
	         district: district, 
			 subcounty: subcounty ,
			 parish: parish,
			 village: village,
			 sel_production_id : sel_production_id,
             gender	:gender		 },
             success: function(data) {
            // window.alert(data);
		      $("#filtered_data").html(data);
              }
    });
	
	
	//document.forms["filter_form"].submit();
}
	
</script>
