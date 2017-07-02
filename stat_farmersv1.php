<?php include("include/header_client.php");?>
<div class="container farmer">
<!--include filter here-->
<?php include("include/stats_filter.php");?>

<?php include("include/preloader.php");
?>
<div class="row stat ">
<div id="first_table" class="col-md-8 ">

</div>
</div>
<div class="row">
  <div class="col-md-10" style="margin-top:45px;" >
    <div class="well">
  <div id="line-location" style="margin-left:15px;">
   <?php //include("include/empty.php");?>
  </div></div>
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

<div id="line_graph" class="row" style="margin-top:-5px;">
  <h4 id="heading" class="text-center hide">Graph</h4>
 
</div>


</div>










<div id="hidden_form_container" >
</div>
<!--end of main container-->

<?php include("include/footer_client.php");?>

<script type="text/javascript">

$(document).ready(function(){
	hideProgressBar();
    //hideempty();
	getDatasets();

	});

function getDatasets(){

	$.ajax({
     type: "POST",
     url: "form_actions/set_starts_filter.php",
     data: {datasets: "true"},
     success: function(data) {
		  $("#sel_datasets").html(data);
		  loadDistricts();
		  getProductionFilter();
		  filterDATA();
    }
    });

}

function loadSubCounty(){

    var dataset_id = document.getElementById("sel_datasets");
	id= dataset_id .value;
	var district_ = document.getElementById("sel_district");
	district= district_.value;
	//window.alert(id);
	$.ajax({
     type: "POST",
     url: "form_actions/set_starts_filter.php",
     data: {district_subcounty: district,id: id },
     success: function(data) {
       // window.alert(data);
		  $("#sel_sub_county").html(data);
		  loadParish();
    }
    });

}


function loadParish(){

    var dataset_id = document.getElementById("sel_datasets");
	id= dataset_id .value;
	var country_ = document.getElementById("sel_sub_county");
	country= country_.value;

	var district_ = document.getElementById("sel_district");
	district= district_.value;
	//window.alert(id);
	$.ajax({
     type: "POST",
     url: "form_actions/set_starts_filter.php",
     data: {parish_district: district,parish_subcounty: country,id: id },
     success: function(data) {
         //window.alert(data);
		  $("#sel_parish").html(data);
		  loadVillage();
    }
    });

}


function loadVillage(){

    var dataset_id = document.getElementById("sel_datasets");
	id= dataset_id .value;
	var country_ = document.getElementById("sel_sub_county");
	country= country_.value;
	var district_ = document.getElementById("sel_district");
	district= district_.value;
	var parish_ = document.getElementById("sel_parish");
	parish= parish_.value;
	//window.alert(id);
	$.ajax({
     type: "POST",
     url: "form_actions/set_starts_filter.php",
     data: {village_district: district,village_subcounty: country,village_parish: parish,id: id },
     success: function(data) {
        // window.alert(data);
		  $("#sel_village").html(data);
    }
    });

}



function loadDistricts(){

    var dataset_id = document.getElementById("sel_datasets");
	id= dataset_id .value;
	//window.alert(id);
	$.ajax({
     type: "POST",
     url: "form_actions/set_starts_filter.php",
     data: {district: id },
     success: function(data) {
        // window.alert(data);
		  $("#sel_district").html(data);
		  loadSubCounty();
    }
    });

}
function Export2Csv(){

var dataset_id = document.getElementById("sel_datasets");
	id= dataset_id .value;
	var parish_ = document.getElementById("sel_parish");
	parish= parish_.value;
	var country_ = document.getElementById("sel_sub_county");
	country= country_.value;
	var district_ = document.getElementById("sel_district");
	district= district_.value;
	var village_ = document.getElementById("sel_village");
	village= village_.value;
	////
	var production_ = document.getElementById("sel_production_data");
	production= production_.value;
	var gender_ = document.getElementById("sel_gender");
	gender= gender_.value;

    var theForm, newInput1, newInput2, newInput3, newInput4;
    var newInput5, newInput6, newInput7;
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
//function Print(){window.alert(1);}

function PrintPreview() {
    

    var dataset_id = document.getElementById("sel_datasets");
	id= dataset_id .value;
	var parish_ = document.getElementById("sel_parish");
	parish= parish_.value;
	var country_ = document.getElementById("sel_sub_county");
	country= country_.value;
	var district_ = document.getElementById("sel_district");
	district= district_.value;
	var village_ = document.getElementById("sel_village");
	village= village_.value;
	////
	var production_ = document.getElementById("sel_production_data");
	production= production_.value;
	var gender_ = document.getElementById("sel_gender");
	gender= gender_.value;

	
	
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
function getProductionFilter(){

	var dataset_id = document.getElementById("sel_datasets");
	id= dataset_id .value;
	//$("#sel_production_data").html("<option value=\"all\">all</option>");
	$.ajax({
     type: "POST",
     url: "form_actions/set_starts_filter.php",
     data: {prodution_data_id: id, prodution_data: "true"},
     success: function(data) {
           // window.alert(data);
		  $("#sel_production_data").html(data);
    }
    });

}


function filterDATA(){
	 showProgressBar();
	// hideempty();
    var dataset_id = document.getElementById("sel_datasets");
	id= dataset_id .value;
	var parish_ = document.getElementById("sel_parish");
	parish= parish_.value;
	var country_ = document.getElementById("sel_sub_county");
	country= country_.value;
	var district_ = document.getElementById("sel_district");
	district= district_.value;
	var village_ = document.getElementById("sel_village");
	village= village_.value;

	////
	var production_ = document.getElementById("sel_production_data");
	production= production_.value;
	var gender_ = document.getElementById("sel_gender");
	gender= gender_.value;

    $("#line_graph").addClass("hide");
	
	$.ajax({
     type: "POST",
     url: "form_actions/get_stats.php",
     data: {id: id, district: district,country :country,parish : parish , village : village ,production : production, gender : gender },
     success: function(data) {
		 

	      $("#first_table").html("");
		  $("#donut-gender").html("");
		  $("#donut-prodction").html("");
		  $("#line-location").html("");
		  $("#first_table").html(data);
		  

         
		  try{
		  flag=loadGenderChart();
		  loadProductionChart();

		  FormLineGraph(id,  district,country,parish  , village , production, gender,flag );
		  }catch(err ){
		
		   hideProgressBar();
		  }

    }

    });

}

function showProgressBar(){
 $(".cssload-container").show();
}
 function hideProgressBar(){
 $(".cssload-container").hide();
}
function showempty(){
   $("#empty-message").show(); 
    }
function hideempty(){
    $("#empty-message").hide(); 
}



</script>

 


<script type="text/javascript">

  /*
 * Play with this code and it'll update in the panel opposite.
 *
 * Why not try some of the options above?
 */
function loadGenderChart(){
     flag=false;
	try{
    hideProgressBar();
	var gender_males = document.getElementById("gender_males");
	males= gender_males .value;
	var gender_females = document.getElementById("gender_females");
	females= gender_females .value;
	
	
    Morris.Donut({
   element: 'donut-gender',
   data: [
    {label: "Male", value: males},
    {label: "Female", value: females}
   ],
    colors: [

        '#4CAF50',
        '#CDDC39'
   ]
   });

   flag=true;



	}catch(err ){

    //showempty();
    $("#line-location").html("<h1 style=\"text-align:center  ;\" >NO DATA</h1>");
	
	$("#donut-gender").html("");


	}

	return flag;
}

function loadProductionChart(){

if(document.getElementById("production_yes") && document.getElementById("production_no")){
    var production_yes = document.getElementById("production_yes");
	yes= production_yes .value;
	var production_no = document.getElementById("production_no");
	no= production_no .value;

	if(no!=0 && yes!=0){
	Morris.Donut({

element:'donut-prodction',
data:[
  {label:"Yes", value: yes},
  {label: "No", value: no}
  ],
  colors:[
          '#FF9800',
        '#FFEB3B'
  ]
});
	}

}else{

$("#donut-prodction").html("");

}
}



function FormLineGraph(id,  district,country,parish  , village , production, gender ,flag){
      var x=0;
 
     
      $.ajax({
      type: "POST",
      dataType: "json",
      url: "form_actions/get_line_graph.php", //Relative or absolute path to file
      data: {id: id, district: district,country :country,parish : parish , village : village ,production : production, gender : gender },
      success: function(data) {
       
	  try{
	    $("#line_graph").removeClass("hide");
		
	
		loadLineChart(data.data);
		x=1;

	  }catch(err){

      hideProgressBar();
	  }


	  }
      });



}

function loadLineChart(data){
	
	
       var json= {
       element: 'line-location',data,
       xkey: 'x',
       ykeys: 'y',
       labels: ['frequency'],
       parseTime: false
       };
       Morris.Line(json);
}


</script>
