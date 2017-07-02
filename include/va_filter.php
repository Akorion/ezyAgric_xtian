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
    
    
</style>

<div class="row card height board" style="background-color:#ffff; margin-bottom:5px;margin-top:5px; margin-left:1px;margin-right:1px; color:#777;">
	
<!--	<h4 class="">Location Data</h4>-->
<!--	<div class="col-sm-12 col-md-2 col-lg-2">-->
    <p class="large-text">Location Data</p>
	<p style="margin-left:10px">District:</p>
	
	<select id="sel_district" onchange="getSubCounties()" class="form-control">
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
	<select id="sel_county" onchange="getParish()" class="form-control">
  	<option  value="all">all</option>
	</select>
	
<!--	<div class="col-sm-12 col-md-2 col-lg-2">-->
	<p>Parish</p>
	<select id="sel_parish" onchange="getVillage()"  class="form-control">
  	<option  value="all">all</option>
  	
	</select>
<!--	</div>-->
<!--	<div class="col-sm-12 col-md-2 col-lg-2">-->
	<p>Village</p>
	<select id="sel_village" class="form-control">
  	<option  value="all">all</option>
  	
	</select>
<!--	</div>-->

</div>

<div class="row card board" style="background-color:#fefefef; margin-bottom:5px;margin-top:5px; margin-left:1px;margin-right:1px; color:#ffffff;">
<!--<div class="col-sm-12 col-md-4 col-lg-4">-->
<p class="large-text">Production Data</p>
	<select id="sel_production_filter" onchange=""  class="form-control" style="min-width:300px">
	<option  value="all">all</option>
  	
	</select>
<!--</div>-->



<!--<div class="col-sm-12 col-md-4 col-lg-4">-->
<p class="large-text">Gender</p>
<!--<div class="form-group">-->
<select id="sel_gender"  class="form-control">
<option value="all">all</option>
<option value="male">male</option>
<option value="female">female</option>
</select>

<p class="large-text"  >Village Agent</p>
<!--<div class="form-group">-->
<select id="sel_va"  class="form-control">
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

