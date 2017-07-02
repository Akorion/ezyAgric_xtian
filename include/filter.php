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

</style>


<!--
<div class="container-fluid">
<div class="col-md-12 col-sm-4 col-xs-12">
<div class="row">


<div class="col-sm-2">
    <label for="inputPassword3" class="control-label">District</label>
     <div>
        <select id="sel_district" onchange="getSubCounties()" class="form-control">
        <option  value="all">All</option>
        <?php //sif(isset($_GET['token'])&&$_GET['token']!=""&&isset($_GET['type'])&&$_GET['type']!=""){

      	/* $id=$util_obj->encrypt_decrypt("decrypt", $_GET['token']);
      	 $table="dataset_".$id;
      	 $row0s= $mCrudFunctions->fetch_rows($table," DISTINCT TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  as biodata_farmer_location_farmer_district"," 1 ORDER BY biodata_farmer_location_farmer_district ASC");
      	 $temp_array= array();
      	 foreach($row0s as $row){
      	 $value=$util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_district']);
      	 $value=str_replace(".","",$value);


      	 if (in_array($value, $temp_array)) {

           }else{
      	 array_push($temp_array, $value);
      	 }

      	// echo "<option value=\"$value\">$value</option>";
      	 }
      	 foreach($temp_array as $temp){
      	  echo "<option value=\"$temp\">$temp</option>";
      	 }

      	 //hidden
           echo"<input id=\"dataset_id_holder\"type=\"hidden\" value=\"$id\" />";



         }*/
      	 ?>
        </select>
    </div>
    </div>


    <!--subcounty-->
    <!--<div class="col-sm-2">
        <label for="inputPassword3" class="control-label">Sub-county</label>
        <div>
        <select id="sel_county" onchange="getParish()"  class="form-control">
        <option  value="all">All</option>

        </select>
        </div>
    </div>
    <!--subcounty-->

<!--parish-->
<!--<div class="col-sm-2">
    <label for="inputPassword3" class="control-label">Parish</label>
    <div>
    <select id="sel_parish" onchange="getVillage()"  class="form-control">
    <option  value="all">All</option>

    </select>
    </div>
</div>
<!--parish-->

<!--village-->
<!--<div class="col-sm-2">
    <label for="inputPassword3" class="control-label">Village</label>
    <div>
    <select id="sel_village" class="form-control">
    <option  value="all">All</option>

    </select>
    </div>
</div>
<!--village-->

<!--production data-->
<!--<div class="col-sm-2">
    <label for="inputPassword3" class="control-label">Production Data</label>
    <div>
    <select id="sel_production_filter" class="form-control">
    <option  value="all">All</option>

    </select>
    </div>
</div>
<!--production data-->

<!--production data-->
<!--<div class="col-sm-2">
    <label for="inputPassword3" class="control-label">Gender</label>
    <div>
      <select id="sel_gender"  class="form-control">
      <option value="all">All</option>
      <option value="male">Male</option>
      <option value="female">Female</option>
      </select>
    </div>
</div>
<!--production data-->


<!--  </div>
  </div>
</div>-->









<div class="row card height board" style="background-color:#ffff;
margin-bottom:5px;margin-top:5px; margin-left:1px;margin-right:1px; color:#777;">

<!--	<h4 class="">Location Data</h4>-->
<!--	<div class="col-sm-12 col-md-2 col-lg-2">-->





    <p class="large-text">Location Data</p>
	<p style="margin-left:10px">District:</p>

	<select id="sel_district" onchange="getSubCounties()" class="select2_single form-control">
  	<option  value="all">all</option>

	<?php if(isset($_GET['token'])&&$_GET['token']!=""&&isset($_GET['type'])&&$_GET['type']!=""){

	 $id=$util_obj->encrypt_decrypt("decrypt", $_GET['token']);
	 $table="dataset_".$id;
	 $row0s= $mCrudFunctions->fetch_rows($table," DISTINCT TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district)  as biodata_farmer_location_farmer_district"," 1 ORDER BY biodata_farmer_location_farmer_district ASC");
	 $temp_array= array();
	 foreach($row0s as $row){
	 $value=$util_obj->captalizeEachWord($row['biodata_farmer_location_farmer_district']);
	 $value=str_replace(".","",$value);


	 if (in_array($value, $temp_array)) {

     }else{
	 array_push($temp_array, $value);
	 }

	// echo "<option value=\"$value\">$value</option>";
	 }
	 foreach($temp_array as $temp){
	  echo "<option value=\"$temp\">$temp</option>";
	 }

	 //hidden
     echo"<input id=\"dataset_id_holder\"type=\"hidden\" value=\"$id\" />";



     }
	 ?>

	</select>
<!--
	</div>
	<div class="col-sm-12 col-md-2 col-lg-2">
-->
	<p>Sub county</p>
	<select id="sel_county" onchange="getParish()" class="select2_single form-control">
  	<option  value="all">all</option>
	</select>

<!--	<div class="col-sm-12 col-md-2 col-lg-2">-->
	<p>Parish</p>
	<select id="sel_parish" onchange="getVillage()"  class="select2_single form-control">
  	<option  value="all">all</option>

	</select>
<!--	</div>-->
<!--	<div class="col-sm-12 col-md-2 col-lg-2">-->
	<p>Village</p>
	<select id="sel_village" class="select2_single form-control">
  	<option  value="all">all</option>

	</select>
<!--	</div>-->

</div>

<div class="row card board" style="background-color:#fefefef; margin-bottom:5px;margin-top:5px; margin-left:1px;margin-right:1px; color:#ffffff;">
<!--<div class="col-sm-12 col-md-4 col-lg-4">-->
<p class="large-text">Production Data</p>
	<select id="sel_production_filter" onchange=""  class="select2_single form-control" style="min-width:300px">
	<option  value="all">all</option>

	</select>
<!--</div>-->



<!--<div class="col-sm-12 col-md-4 col-lg-4">-->
<p class="large-text">Gender</p>
<!--<div class="form-group">-->
<select id="sel_gender"  class="select2_single form-control">
<option value="all">all</option>
<option value="male">male</option>
<option value="female">female</option>
</select>

<p class="large-text"  >Village Agent</p>
<!--<div class="form-group">-->
<select id="sel_va"  class="select2_single form-control">
<option value="all">all</option>

</select>
<!--  </div>-->
<!--</div>-->


<!--<div class="col-sm-12 col-md-2 col-lg-2 go">-->
   <!-- <button class=" btn btn-primary"   onclick="Export2Csv();"  >Export to CSV</button>-->
	<!--<a id="filter_go" onclick="filterDataPagination(1); showProgessBar()" class="btn btn-danger btn-raised">filter</a>-->
<!--</div>-->

<!--<div class="col-sm-12 col-md-2 col-lg-2 print_export">-->

<!--</div>-->
    </div>
