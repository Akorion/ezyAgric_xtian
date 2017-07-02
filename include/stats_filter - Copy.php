<div class="row card board"  style="background-color:#fff; margin-bottom:5px;margin-top:5px; margin-left:1px;margin-right:1px; color:#666; padding:0 0px;">
    <p class="large-text">Datasets</p>
    <select id="sel_datasets"  class="select2_single form-control" style="">
   
  	
	</select>
     </div>
<div class="row card board" style="background-color:#fff; margin-bottom:5px;margin-top:5px; margin-left:1px;margin-right:1px; color:#666;">
<!--	<legend class="" style="margin-left:18%;">Location Data</legend>-->
<!--	<div class="col-sm-12 col-md-2 col-lg-2">-->
<!--	<p class="">Datasets</p>-->
	
<!--
	</div>
	<div class="col-sm-12 col-md-2 col-lg-2">
-->
    <p class="large-text">Location data</p>
	<p>District</p>
	<select id="sel_district" onchange="loadSubCounty();" class="select2_single form-control" style="">
  	<option  value="all">all</option>
  	
	</select>
<!--
	</div>
	<div class="col-sm-12 col-md-3 col-lg-3">
-->
	<p>Sub county</p>
	<select id="sel_sub_county" onchange="loadParish();" class="select2_single form-control" style="">
  	<option  value="all">all</option>
  	
	</select>
<!--
	</div>
	<div class="col-sm-12 col-md-2 col-lg-2">
-->
	<p>Parish</p>
	<select id="sel_parish" onchange="loadVillage();" class="select2_single form-control" style="">
  	<option  value="all">all</option>
 
	</select>
<!--
	</div>
	<div class="col-sm-12 col-md-3 col-lg-3">
-->
	<p>Village</p>
	<select id="sel_village" class="select2_single form-control" style="c" >
  	<option  value="all">all</option>
	</select>
<!--	</div>-->
</div>


<div class="row card board" style="background-color:#fafafa; margin-bottom:5px;margin-top:5px; margin-left:1px;margin-right:1px; color:#ffffff;">
<!--<div class="col-sm-12 col-md-3 col-lg-3">-->
    <p class="large-text">Production Data</p>
	<select id="sel_production_data" onchange="loadProductionOptions();" class="select2_single form-control" style="">
  	<option  value="all">all</option>
  
	</select>
<!--
</div>

<div class="col-sm-12 col-md-2 col-lg-2">
-->
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

<!-- <a onclick="filterDATA();" class="btn btn-danger btn-raised">filter</a>-->

  <!--<button class=" btn btn-primary right"   onclick="Export2Csv();"  >Export to CSV</button>-->
  <button onclick="PrintPreview() ;" class=" btn btn-primary right"    >Print Preview</button>
<!--</div>-->

</div>
