<?php include("include/header_client.php");?>
<style type="text/css">
    .img_div{
        background-color: #fafafa;
        border-radius: 10px
    }
    .grad img{
      /*  padding: 10px;*/
        height: 15em;
        margin-top: -1em;
        margin-left: 2em;
      /*  width: 100%;*/
    }

 .grad{
    background: rgba(0,0,0,0.3);
    padding: 20px;
    height: 15em;
    border: 1px solid #fafafa;
    border-radius: 4px;
}
.panel-heading{
    color: brown !important;
    text-align: center;
}
.h400{
    height: 220px;
    vertical-align: middle;
    display: block;
    color: #444 !important;

    }

.no-padding{
 padding: 0 !important;
}
.list-group{
 line-height: 13pt;

}
.form-horizontal .control-label{
   text-align:left !important;

}
.list-group button{
    background:none !important;
    color: #fff !important;
    font-size: 12pt;
     
    font-weight: 300;
    
}
.list-group .list-group-item
{
    border: 1px solid #fff;
     border-radius: 1px;
}
.table {
    width: 100% !important;
}
th ,thead{
    height: 2em !important;
    background:teal;
    color: #fff !important;
}
.danger:nth-child(even){
    background-color: red !important;
}

tr td {
    padding: 0px 5px 0px !important;
    vertical-align: baseline;
    height: -1em !important;
    margin:0 !important;
    text-align: baseline;
}

td  a{
    text-decoration: none !important;
    cursor: pointer;
}
/*
td:nth-child(even){
    background:teal;
}*/


#center{
text-align:center;
background-color:#fff;

}
table,tr,thead,th
{
  /*color:orange;*/
}
th
{
/*color:orange;*/
}
 
    
.card h6{
    min-width:120px;
    width:200px;
    padding-left:10px;
    color:cadetblue !important;}
    
.prodn h6{
    width:300px;
    }
.prodn p{
    color:#777;
    max-width:300px;}
 .card p{
    color:#777;
    width:230px;}

.card{
    padding-bottom:10px;
    padding-top:0px;
    margin-bottom:20px;
    overflow:hidden;
}
    
h5{
    border:none !important;
    color:#fff !important;
    margin:0 !important;
    background-color:#A7B6B1 !important;}
    
.card:nth-child(2) h5{
    color:#fff !important;
    margin:0 !important;
    background-color:#2697B6 !important;}
    
.card:nth-child(1) h5{
    color:#fff !important;
    margin:0 !important;
    background-color:#72B678 !important;}
hr{
    border-width:0;
    display:block;
   
    opacity:0.2;
    margin:0 10px !important;
    width:97% !important;
}
.card hr:last-child{
    display:none;
}
    
    h3{
    font-size:0.9em !important;
    margin:0px !important;
    }

    
    #map-canvas{
    background:#f7f7f7;
    border: 1px #eee solid;
    }
    .card-image{
        position:relative;
    }
    
    #gardens{
     min-height:100px;
     width:200px;
        background:rgba(0,0,0,0.4);
        position:absolute;
        right:10px;
        top:10px;
        border-radius:4px;
        overflow:hidden;
        
    }
    
    .light-table{
     width:100%
    }
    .light-table th,.light-table td{
        padding:3px;
        color:#fff;
    }
    
    .light-table thead tr{
     background:#fff !important;
   
    }
    .light-table thead tr th{
     color:#888;
   
    }
    
    .light-table tbody tr:nth-child(even){
     background:rgba(0,0,0,0.2) !important;
    }
    .light-table td:nth-child(even), .light-table th:nth-child(even){
     background:rgba(0,0,0,0.4) !important;
     color:#fff;
    }
     


</style>

<?php
$latitude=$longitude=$picture=$name=$gender=$phone=$uuid="";
if(isset($_GET['token'])&&$_GET['token']!=""&&isset($_GET['s'])&&$_GET['s']!=""
   &&isset($_GET['type'])&&$_GET['type']!=""){
   
   
$type=$_GET['type'];
$dataset_id=$util_obj->encrypt_decrypt("decrypt", $_GET['s']);
$id=$util_obj->encrypt_decrypt("decrypt", $_GET['token']);


$origin=$util_obj->encrypt_decrypt("decrypt", $_GET['o']);

$table="dataset_".$dataset_id;		 
$columns="*";
$where=" id='$id' ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);
if(sizeof($rows)==0){ 

}else
{

if($type=="VA"){

$latitude=$util_obj->remove_apostrophes($rows[0]['va_location_va_home_gps_Latitude']);
$longitude=$util_obj->remove_apostrophes($rows[0]['va_location_va_home_gps_Longitude']);
$picture=$util_obj->remove_apostrophes($rows[0]['biodata_va_picture']);
$gender=$util_obj->captalizeEachWord($rows[0]['biodata_va_gender']);
$name=$util_obj->captalizeEachWord($rows[0]['biodata_va_name']);
$code=str_replace(".","",$rows[0]['biodata_va_code']);
$code=str_replace(" ","",$code);
$code=strtolower($code);
//echo $code;
$phone=$util_obj->remove_apostrophes($rows[0]['va_phonenumber']);
$uuid=$util_obj->remove_apostrophes($rows[0]['meta_instanceID']);

$birth_date=$rows[0]["biodata_va_dob"];
 $age=$util_obj->getAge( $birth_date,"Africa/Nairobi");
echo"<input id=\"dataset_id\" type=\"hidden\" value=\"$dataset_id\" />";
echo"<input id=\"id\" type=\"hidden\" value=\"$id\" />";
echo"<input id=\"type\" type=\"hidden\" value=\"$type\" />";

}
}





}

?>
<div class="container-fluid" style="margin-bottom: 4em;">
<div class="row">
<div class="col-md-3">
  

     <div class="card" style="height: 22.5em;">
<h5 class="" style="background-color:grey  !important;"><?php echo$name; ?></h5>
          <div class="" style="background:none !important;">
                <?php echo"<img src=\"$picture\" class=\"img-responsive\" style=\"width:100%;\">"; ?>
         </div>
</div>
    
         </div>

<div class="col-md-4">
 <div class="card " style="height: 22.5em;">
<h5 class="" style="background-color:#2697B6  !important;">Personal Profile</h5>

 <h6 >Name</h6><p class="align"><?php echo $name;?></p><hr/>
  <h6>Birth Date</h6><p class="align"><?php echo $birth_date;?></p><hr/>
   <h6>Age</h6><p class="align"><?php echo $age;?></p><hr/>
    <h6>Gender</h6><p class="align"><?php echo $gender;?></p><hr/>
</div>
</div> 


<div class="col-md-5">
    <div class="h400" style="background:none; margin-bottom:100px">   
<div class="card prodn">
<h5 class="">Season Summary</h5>
   <h6 class="trim">Farmers Profiled</h6><p id="profiled_farmers">0</p><hr/>
    <h6 class="trim">Total acrege</h6><p id="farmer_acreage" >0</p><hr/>
     <h6 class="trim">Total Seed</h6><p>1000</p><hr/>
      <h6 class="trim">Total Herbicide</h6><p>1000</p><hr/>
       <h6 class="trim">Total Fertilizer</h6><p>1000</p><hr/>
        <h6 class="trim">Total Produce Supplied</h6><p>1000</p><hr/>
         <h6 class="trim">Total Commission</h6><p>Profiling: 50,0000<br/> Produce :980000</p><hr/>

    
    </div>

  </div>
</div>  



</div>


<div class="row" style="margin-top: 1em;">
<div class="col-md-12">
<div class="h600" style="background:none; width: 100%;">
<table class="table table-bordered table-hover">
  <thead>
    <tr>
	  <th>#</th>
      <th>Name</th>
      <th>Acreage</th>
      <th>Cash of value inputs(tractor and extra cash)</th>
      <th>Produce Supplied</th>
       <th>Cash payable</th>
      <th>Payment status</th>
      <th>Action</th>

    </tr>
  </thead>
  <tbody>
    <?php 
	
       $rows= $mCrudFunctions->fetch_rows("dataset_".$origin," * "," lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) ='$code'  ");
        $x=0;
		$i=0;
		$ttl_acerage=0;
		
        foreach($rows as $row) { 
       
		$farmer_id =$util_obj->encrypt_decrypt("encrypt", $row['id']);
		$real_id=$origin;
		$s=$util_obj->encrypt_decrypt("encrypt", $origin);
        $farmer=$row['biodata_farmer_name'];
		$uuid=$row['meta_instanceID'];
		
        $total_acreage=0;
        $acares =array();
		$gardens_table="garden_".$real_id;
 
        if($mCrudFunctions->check_table_exists($gardens_table)>0){
          //echo $uuid;
         

          $gardens=$mCrudFunctions->fetch_rows($gardens_table," DISTINCT PARENT_KEY_ "," PARENT_KEY_ LIKE '$uuid%' ");

          if(sizeof($gardens)>0){
           $z=1;
           foreach($gardens as $garden){
           $key=$uuid."/gardens[$z]";
           $data_rows=$mCrudFunctions->fetch_rows($gardens_table,"garden_gps_point_Latitude,garden_gps_point_Longitude"," PARENT_KEY_ ='$key'");
           
		   if(sizeof($data_rows)==0){
           $data_rows=$mCrudFunctions->fetch_rows($gardens_table,"garden_gps_point_Latitude,garden_gps_point_Longitude"," PARENT_KEY_ ='$uuid'");
           }
           
		   $latitudes= array();
           $longitudes= array();
 
           foreach($data_rows as $row_){

           array_push($longitudes, $row_['garden_gps_point_Longitude']);
           array_push($latitudes, $row_['garden_gps_point_Latitude']);

          }
          $acerage=$util_obj->get_acerage_from_geo($latitudes,$longitudes);
          array_push( $acares, $acerage);
          $z++;
          }
		  
          $total_gardens=sizeof($gardens);
         
          if($total_gardens!=0){
           $total_acreage=round(array_sum($acares)/*$total_gardens*/,2);
		   
		   $ttl_acerage=$ttl_acerage+ $total_acreage;
           }

          }
          

          } 
       $farmer=$util_obj->captalizeEachWord($farmer);
       $total_acreage= $total_acreage>0? $total_acreage: "N/A";			
       // $class = ($x%2 == 0)? 'green': 'red';
	   $i++;
        echo "<tr>
	  <td>$i</td>
      <td scope=\"row\"><a href=\"#details\" data-toggle =\"modal\" data-target=\"#details\">$farmer</a></td>
      <td>$total_acreage</td>
      <td>2000Kg</td>
      <td>500 kgs</td>
      <td>540,000</td>
      <td class=\"danger\"></td>
      <td><a href=\"user_details.php?s=$s&token=$farmer_id&type=Farmer\" class=\"btn btn-primary btn-xs\" style=\"font-size:1em;\">view profile</a></td>
    </tr>";
        }

		echo"<input type=\"hidden\" id=\"ttl_acerage\" value=\"$ttl_acerage\"/>";
		echo"<input type=\"hidden\" id=\"ttl_farmers\" value=\"$i\"/>";
		
    ?>
    
    
  </tbody>

</table>
          
</div>
</div>  

</div>

    
</div>

<!-- Modal for when user is clicked -->
<div class="modal fade" id="details" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true" style="text-align: left;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close"
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Record inputs,produce and cash paid </h4>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
             <form class="form-horizontal" role="form" method="post" action="" id="" data-toggle="validator">
                <div class="row">
          <div class="col-md-6">

        <fieldset>
          <!-- Form Name -->
          <h2 class="text-center">Record input given out</h2>
           <!-- select input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput" id="">Seed</label>
            <div class="col-sm-8">
             <select class="form-control" name="seed">
             <option>Beans</option>
             <option>Beans</option>
             <option>Beans</option>
             <option>Beans</option>
            </select>
            </div>
          </div>
		  

           <!-- select input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput" id="">Fertilizer</label>
            <div class="col-sm-8">
             <select class="form-control" name="seed">
      
    <option>Beans</option>
    <option>Beans</option>
    <option>Beans</option>
    <option>Beans</option>
            </select>
            </div>
          </div>
           <!-- select input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput" id="">Herbicide</label>
            <div class="col-sm-8">
             <select class="form-control" name="seed">
      
    <option>Beans</option>
    <option>Beans</option>
    <option>Beans</option>
    <option>Beans</option>
            </select>
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Cash taken</label>
            <div class="col-sm-8">
              <input type="text" placeholder="Cash Taken" name="" id="" class="form-control">
            </div>
          </div>

           <!-- file input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Input receipt</label>
            <div class="col-sm-8">
              <input type="file" placeholder="" name="" id="" class="form-control">
            </div>
          </div>

           <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Tractor cost</label>
            <div class="col-sm-8">
              <input type="text" placeholder="Tractor cost" name="" id="" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Input calculated</label>
            <div class="col-sm-8">
              <input type="text" placeholder="input calculated" name="" id="" class="form-control">
            </div>
          </div>



        </fieldset>

    </div><!-- /.col-md 6 -->
<div class="col-md-6">
  <h2 class="text-center">Produce and payments</h2>
      <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Produce Supplied</label>
            <div class="col-sm-8">
              <input type="text" placeholder="produce supplied" class="form-control" name="email" id="email">
            </div>
           <!--  <div class="col-sm-1">
                
            </div> -->
          </div>

      
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Payment status</label>
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-6">
                  <div class = "checkbox">
              <label><input type = "checkbox">&nbsp;&nbsp;Paid</label>
    
              </div>
                </div>
                <div class="col-sm-6">
                  <div class = "checkbox">
              <label><input type = "checkbox">&nbsp;&nbsp;Unpaid</label>
              
              </div>
              </div>
              </div>
    
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Produce receipt</label>
            <div class="col-sm-8">
              <input type="file" placeholder="" name="" id="" class="form-control">
            </div>
          </div>


</div>

    </div><!-- /.row -->

      <div class="modal-footer">
             <button type="button" class="btn btn-primary">
                   Save
                </button>
                <button type="button" class="btn btn-danger"
                        data-dismiss="modal">
                            Cancel
                </button>

            </div> <!-- Modal Footer -->
    </form>

            </div>
        </div>
    </div>
</div>


<?php include("include/footer_client.php");?>
<script>
jQuery(document).ready(function(){
 $("#profiled_farmers").html($("#ttl_farmers").val());
 $("#farmer_acreage").html($("#ttl_acerage").val());

});

</script>
