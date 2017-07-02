<?php include("include/header_client.php");?>
<?php
 function escapeJavaScriptText2($string) 
{ 
    return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\"))); 
} 
?>

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
    
    .hide, [hidden]{
        display: none;
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
<?php include "include/breadcrumb.php"?>
<?php
 $client_id=$_SESSION['client_id'];
$latitude=$longitude=$picture=$name=$gender=$phone=$uuid="";
$total_seed_solid=0;
$total_seed_liquid=0;

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

$total_seed=(int) $mCrudFunctions->get_sum("out_grower_input_v","qty"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code' AND item_type='Seed' ");


$total_fertilizer_solid=(int) $mCrudFunctions->get_sum("out_grower_input_v","qty"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code' AND item_type='Fertilizer' AND units='KGS' ");

$total_fertilizer_liquid=(int) $mCrudFunctions->get_sum("out_grower_input_v","qty"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code' AND item_type='Fertilizer' AND units='LITRES' ");


$total_herbicide_solid=(int) $mCrudFunctions->get_sum("out_grower_input_v","qty"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code' AND item_type='Herbicide' AND units='KGS' ");

$total_herbicide_liquid=(int) $mCrudFunctions->get_sum("out_grower_input_v","qty"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code' AND item_type='Herbicide' AND units='LITRES' ");
//out_grower_produce_tb
$total_produce_supplied=(int) $mCrudFunctions->get_sum("out_grower_produce_tb","qty"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code'  ");

//out_grower_produce_v

$total_commision_on_produce_supplied=(int) $mCrudFunctions->get_sum("out_grower_produce_v","commission"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code'  ");


$total_commision_on_profiling= (int)$mCrudFunctions->fetch_rows("out_grower_threshold_tb","commission_per_unit"," client_id='$client_id' AND item='Profiling'  AND item_type='Service' ")[0]['commission_per_unit'];

}
}





}

?>
<div class="container-fluid" style="margin-bottom: 4em;">
<div class="row">
<div class="col-md-3">
  

     <div class="card" style="height: 22.5em;">
<h5 class="" style="background-color:grey  !important;">Profile Picture </h5>
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
     <h6 class="trim">Total Seed</h6><p><?php echo $total_seed. " KGS";?></p><hr/>
      <h6 class="trim">Total Herbicide</h6><p><?php echo $total_herbicide_solid ." KGS ," .$total_herbicide_liquid ." LITRES ";?></p><hr/>
       <h6 class="trim">Total Fertilizer</h6><p><?php echo $total_fertilizer_solid ." KGS ," .$total_fertilizer_liquid ." LITRES ";?></p><hr/>
        <h6 class="trim">Total Produce Supplied</h6><p><?php echo $total_produce_supplied ." KGS"; ?></p><hr/>
         <input id="unit_profiling_commission" type="hidden" value="<?php echo$total_commision_on_profiling; ?>"/>
		 <h6 class="trim">Total Commission</h6><p>Profiling: <span id="profilling_commision">0</span><br/> Produce :<?php echo number_format($total_commision_on_produce_supplied) ." UGX";?></p><hr/>

    
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
      <th>Cash of value inputs(UGX)</th>
	   <th>Intrest(UGX)</th>
      <th>Produce Supplied</th>
	  <th>Price Per Kilo (UGX)</th>
       <th>Cash payable (UGX)</th>
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
		$fid=$row['id']; 
	   $total_cash_inputs= (int) $mCrudFunctions->get_sum("out_grower_input_v","input_monetary_value"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code'  AND meta_id='$fid' ")+(int) $mCrudFunctions->get_sum("out_grower_cashinput_tb","amount"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code'  AND meta_id='$fid' ");
  
       $produce_suplied_=(int) $mCrudFunctions->get_sum("out_grower_produce_v","qty"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code'  AND meta_id='$fid' ");
  
	   
	   $rate=((double) $mCrudFunctions->fetch_rows("out_grower_threshold_tb","unit_price"," client_id='$client_id' AND item='Interest Rate'  ")[0]['unit_price'])/100;
        
		
		$produce_rows= $mCrudFunctions->fetch_rows("out_grower_produce_tb","*"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code'  AND meta_id='$fid' AND receipt <>'' ");
       
		$receipt =$produce_rows[0]['receipt'];
		
	   $inputs_rows= $mCrudFunctions->fetch_rows("out_grower_input_tb","*"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code'  AND meta_id='$fid' AND reciept_url <>'' ");
       
	   $intrest_on_inputs=0;
	   
	   foreach( $inputs_rows as  $inputs_row){
	   $inputs_date= $inputs_row['doc_date'];
	   $inputs_qty= (double)$inputs_row['qty'];
	   $inputs_unit_price= (double)$inputs_row['unit_price'];
	   $inputs_cash_price=$inputs_qty*$inputs_unit_price;
	   
	   
	   if($inputs_date!=''&& sizeof($produce_rows)==0){
	   
	    $parts = explode('.',$inputs_date);
	   
	   
	    $time=$util_obj->getTimeForIntrests( $parts[2]."/".$parts[0]."/".$parts[1],"Africa/Nairobi" ); 
	   //$time ." ".$parts[2]."/".$parts[0]."/".$parts[1];//
	     $intrest_on_inputs= $intrest_on_inputs + $inputs_cash_price*$rate*$time;
      ///-------------------echo "$inputs_cash_price $rate $time = $intrest_on_input";
	    echo "</br>";
	   }elseif(sizeof($produce_rows)!=0){
	   
	    $parts_1 = explode('.',$inputs_date);
		
		$parts_2 = explode('.',$produce_rows[0]['doc_date']);
		
		$date1=$parts_1[2]."/".$parts_1[0]."/".$parts_1[1];
		
		$date2=$parts_2[2]."/".$parts_2[0]."/".$parts_2[1];
		
		
	    $time=$util_obj->getTimeForIntrests2Date( $date1,$date2 );
		
		$intrest_on_inputs= $intrest_on_inputs + $inputs_cash_price*$rate*$time;
	   //echo "$inputs_cash_price $rate $time = $intrest_on_input";
	   }
	  
	   }
	   
	   $cash_inputs_rows= $mCrudFunctions->fetch_rows("out_grower_cashinput_tb","*"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code'  AND        meta_id='$fid' AND reciept_url <>'' ");
       
	   $intrest_on_cash_inputs=0;
	   
	   foreach( $cash_inputs_rows as  $cash_inputs_row){
	   $inputs_date= $cash_inputs_row['doc_date'];
	  
	   $ammount_= (double)$cash_inputs_row['amount'];
	  
	   
	   if($inputs_date!='' && sizeof($produce_rows)==0){
	   
	    
	    $parts = explode('.',$inputs_date);
	   
	   
	    $time=$util_obj->getTimeForIntrests( $parts[2]."/".$parts[0]."/".$parts[1],"Africa/Nairobi" ); 
	   //$time ." ".$parts[2]."/".$parts[0]."/".$parts[1];//
	     $intrest_on_cash_inputs= $intrest_on_cash_inputs + $ammount_*$rate*$time;
	   // echo "</br>";
	   }elseif(sizeof($produce_rows)!=0){
	   
	    $parts_1 = explode('.',$inputs_date);
		
		$parts_2 = explode('.',$produce_rows[0]['doc_date']);
		
		$date1=$parts_1[2]."/".$parts_1[0]."/".$parts_1[1];
		
		$date2=$parts_2[2]."/".$parts_2[0]."/".$parts_2[1];
		
		
	    $time=$util_obj->getTimeForIntrests2Date( $date1,$date2 );
		
	    
		$intrest_on_cash_inputs= $intrest_on_cash_inputs + $ammount_*$rate*$time;
	     //echo " $date1 $date2  $time";
	   }
	  
	   }
	   
	   $amount_with_intrest= round(($intrest_on_inputs+ $intrest_on_cash_inputs),2);
	   
	   //echo " $intrest_on_inputs $intrest_on_cash_inputs  $intrest---</br>";
	  	//  echo " ---</br>";
     // $intrest= floor(($total_cash_inputs* $rate*$time)); 
  
       $payment_status=$mCrudFunctions->fetch_rows("out_grower_produce_v","payment_status"," dataset_id='$origin' AND lower(REPLACE(REPLACE(va_code,' ',''),'.','')) ='$code'  AND meta_id='$fid' ")[0]['payment_status'];
  
       $class = ($payment_status =="" or $payment_status =="no" )? 'danger': 'success';
        
	   $produce_unit_price=$mCrudFunctions->fetch_rows("out_grower_produce_tb","unit_price"," dataset_id='$origin' AND meta_id='$fid' AND va_code='$code'  ")[0]['unit_price'];
        
       $produce_cash=$produce_unit_price*$produce_suplied_;


	 $cash_payable= $produce_cash>0? number_format( (($produce_unit_price*$produce_suplied_)-($total_cash_inputs + $amount_with_intrest))): "N/A";       
       $farmer=$util_obj->captalizeEachWord($farmer);

	   $total_acreage_=$total_acreage;
       $total_acreage= $total_acreage>0? $total_acreage: "N/A";	

       $produce_unit_price= $produce_unit_price>0? $produce_unit_price: "N/A";	    	   
      
	  $intrest= $amount_with_intrest;
	   
	    $url="images/va_receipt/$receipt";
	  
	   $i++;
	   $meta=$row['id'];
	   
	   $farmer_=escapeJavaScriptText2($farmer);
	  //\"{$farmer}\"
        echo "<tr>
	  <td>$i<input id=\"$meta\" type=\"hidden\" value=\"$farmer\"/>
	 
	  <input id=\"farmer_dataset_id\" type=\"hidden\" value=\"$real_id\"/>
	  </td>
      <td scope=\"row\"><a href=\"#details\" onclick=\"setAcreage($total_acreage_,$meta,$real_id); \" data-toggle =\"modal\" data-target=\"#details\" title='Add Records for $farmer'>$farmer</a></td>
      <td>$total_acreage</td>
      <td>".number_format($total_cash_inputs)."  </td>
	  <td>".number_format($intrest)."  </td>";
	  
	  if($receipt!=""){
	   echo "<td class='open-image' data-target='$url' ><a >$produce_suplied_ KGS</a></td>";
	  }else{
	   echo "<td class='open-image' data-target='$url' >$produce_suplied_ KGS</td>";
	  }
	  
      
	  
	  echo"
	  <td>$produce_unit_price</td>
      <td>".$cash_payable."  </td>
      <td class=\"$class\"></td>
      <td><a href=\"user_details.php?s=$s&token=$farmer_id&type=Farmer\" title=\"View Profile\" class=\"btn btn-primary btn-xs\" style=\"font-size:0.8em;\">
      Profile</a>
      
      <a href=\"user_details.php?s=$s&token=$farmer_id&type=Farmer\"  title=\"View Receipts\" class=\"btn btn-primary btn-xs\" style=\"font-size:0.8em;\">
      Receipts</a></td>
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
                    <style>
    table{
        
        width: 100%;
    }
    caption{
        text-align: left;
       
        color: #333;
        padding-top: 10px;
        border-bottom: 1px solid #eee;
        font-weight: bold;
    }
    
    td.units{
        color: #888;
    }
td{
    background: #fff;
    vertical-align: middle;
    padding: 4px;
                            
} 

                        
                        
                        .modal td{
                            font-size: 11pt;
                        }
.modal tbody table tr{
    background: none;
    text-align: left;
}
.modal tr td:first-child{
   width: 160px;
                        }
.modal tr td:last-child{
      width: 140px;
    }

    td.price{
    font-size: 20px;
    color: orange
                        }
table input[type=text]{
    border-radius: 2px;
    padding: 2px 4px;
    width: 100%;
    border: 1px solid #aaa;
}

                        
  tr.title td{
     background: #eee;
    padding: 5px !important;
  }
table .input{
    width: 100px;
    padding:2px 0 !important;
}
                        td{
                            vertical-align: middle !important;
                        }
                        
                        a.attach-file.active{
                            background: orange;
                            color: white;
                            padding: 4px;
                            border-radius: 4px;
                        }
                        a.attach-file:hover{
                            cursor: pointer;
                            text-decoration: none;  
                        }
                         a.attach-file{
                           margin-top: -2em;
                        }
                        a.attach-file h2{
                          display: inline-table !important;     
                            
                        }
                        
                        a.attach-file .remove{
                             padding: 3px;
                             
                        }
                        a.attach-file .remove:hover{
                             background: firebrick
                             
                        }
                        
                        
                        .dark td{
                            color: #444;
                            border-bottom: 1px solid red;
                        }
                        
                        .modal h2{
                            font-size: 14pt;
                            padding: 10px 0;
                            color: firebrick
                        }
                        
                        .farmer-name{
                            color: #999;
                            font-size: 12pt;
                        }
</style>
<!-- Modal for when user is clicked -->
<div class="modal fade" id="details" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true" style="text-align: left;">
    <div class="modal-dialog " style="width:100%">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="margin:0">
                <button type="button" class="close"
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
				<input id="farmer_id" type="hidden"/>
<!--                <h4 class="modal-title" id="myModalLabel">Record inputs,produce and cash paid </h4>-->
                <h4 class="modal-title" id="myModalLabel" >Inputs, Produces and Cash Given - <span  class="farmer-name" id="fname" >Farmer Name</span></h4>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
               <div class="row" >
                 <div class="col-md-7">

<h2>Record input given out</h2> 
                    <div>
                         <a title="attach receipt" class="attach-file pull-right" onclick="attachFile(this)"><i class="fa fa-paperclip"></i> receipt</a>
                      <input type="file" name="receipt" class="file hide" accept="images/*"/>
                       <input type='hidden' class='id' value='$id'/>
                     </div>
                        
<table class='table-bordered'>
    <thead class="dark">
     <tr><td>Item</td><td>Quantity</td><td colspan="2">Quantity</td><td>Cost (ugx)</td><td class="hide">Reciept</td><td>Receipt Date</td></tr>
    </thead>
<!-- <caption>Seeds</caption>-->
    <tbody >
	 <tr class='title'><td colspan = '6'><b>Seeds</b></td></tr>
	 <?php
               $client_id=$_SESSION['client_id'];
			   $seeds= $mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","client_id='$client_id' AND item_type='Seed' ");//item

			   //echo "<tr class='title'><td colspan = '6'><b>Seeds</b></td></tr>";
			   foreach($seeds as $seed){
			   $id=$seed['id'];
			   $item=$seed['item'];
			   $units=$seed['units'];
			   
			   $units=$seed['units'];
			   
			   $qty_per=$seed['unit_per_acre'];
			   
			   $unit_price=$seed['unit_price'];
			    $type="seed";
		        echo"<tr>
                   
			         <td><input id='seed_$id' onclick='seedSelected($id);' type='checkbox' />$item <input type='hidden' class='unit-price-seed' value='$unit_price'/>
					 <input type='hidden' class='per-acre-seed' value='$qty_per'/>
					 </td>
                      <td>5kg</td>
                     <td class=\"input\"><input oninput='updateSeedcost($id,$unit_price)' id='seed_qty_$id' class='qty-seed' type=\"text\" placeholder=\"quantity\"/></td>
                     <td class=\"units\">$units</td>
                     <td id='$id'  class='price-seed' >0</td>
                     <td class =\"hide\">
                     <a title=\"attach receipt\" class=\"attach-file\" onclick=\"attachFile(this)\"><i class=\"fa fa-paperclip\"></i> receipt</a>
                     <input type=\"file\" name=\"receipt\" class=\"file hide\" accept=\"images/*\"/>
					 <input type='hidden' class='id' value='$id'/>
					 <input type='hidden' class='type' value='$type'/>
                     </td>
                     <td class='input'><input  id='seed_date_$id' onchange='checkSeed($id);' class='date-input' placeholder='select date'/></td>
                     </tr>";
			   
			    }
			 ?>
        
        
<!--
    </tbody>
</table>


<table>
 <caption>Fertlizers</caption>
    <tbody>
-->
	<tr class='title'><td colspan = '6'><b>Fertilizers</b></td></tr>
	<?php
               $client_id=$_SESSION['client_id'];
			   $fertilizers= $mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","client_id='$client_id' AND item_type='Fertilizer'");//item
			  
			   foreach($fertilizers as $fertilizer){
			   $id=$fertilizer['id'];
			   $item=$fertilizer['item'];
			   $units=$fertilizer['units'];
			   
			   $qty_per=$fertilizer['unit_per_acre'];
			   $unit_price=$fertilizer['unit_price']; 
			   
			   $type="fertilizer";
			   echo"<tr>
                        
			         <td><input id='fertilizer_$id' onclick='fertilizerSelected($id);' type='checkbox' />$item 
					 <input type='hidden' class='unit-price-fertilizer' value='$unit_price'/>
					 <input type='hidden' class='per-acre-fertilizer' value='$qty_per'/></td>
                     <td>5 ltrs</td>
                     <td class=\"input\"><input  oninput='updateFertilizercost($id,$unit_price)' id='fertilizer_qty_$id' class='qty-fertilizer' type=\"text\" placeholder=\"quantity\"/></td>
                     <td class=\"units\">$units</td>
                     <td id='$id' class='price-fertilizer' >0</td>
                     <td class =\"hide\">
                     <a title=\"attach receipt\" class=\"attach-file\" onclick=\"attachFile(this)\"><i class=\"fa fa-paperclip\"></i> receipt</a>
                     <input type=\"file\" name=\"receipt\" class=\"file hide\" accept=\"images/*\"/>
                       <input type='hidden' class='id' value='$id'/>
					 <input type='hidden' class='type' value='$type'/>
                     </td>
                     <td class='input'><input id='fertilizer_date_$id' onchange='checkFertilizer($id);' class='date-input'  placeholder='select date' /></td>
                     </tr>";
			   }
			 ?>
       
        
<!--
    </tbody>
</table>
                     
                     
<table>
 <caption>Herbicides</caption>
    <tbody>
	
-->
        
        <tr class='title'><td colspan = '6'><b>Herbicides</b></td></tr>
	
             <?php
               $client_id=$_SESSION['client_id'];
			   $herbicides= $mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","client_id='$client_id' AND item_type='Herbicide' ");//item

			  
			   foreach($herbicides as $herbicide){
			   $id=$herbicide['id'];
			   $item=$herbicide['item'];
			   $units=$herbicide['units'];
			  
			   $qty_per=$herbicide['unit_per_acre'];
			   $unit_price=$herbicide['unit_price']; 
			   $type="herbicide";
			    echo"<tr>
                     
			         <td><input id='herbicide_$id' onclick='herbicideSelected($id);' type='checkbox' />$item 
					 <input type='hidden' class='unit-price-herbicide' value='$unit_price'/>
					 <input type='hidden' class='per-acre-herbicide' value='$qty_per'/></td>
                     <td>15 ltrs</td>
                     <td class=\"input\"><input oninput='updateHerbicidecost($id,$unit_price)' id='herbicide_qty_$id'class='qty-herbicide' type=\"text\" placeholder=\"quantity\"/></td>
                     <td class=\"units\">$units</td>
                     <td id='$id' class='price-herbicide' >0</td>
                     <td class =\"hide\">
                     <a title=\"attach receipt\" class=\"attach-file\" onclick=\"attachFile(this)\"><i class=\"fa fa-paperclip\"></i> receipt</a>
                     <input type=\"file\" name=\"receipt\" class=\"file hide\" accept=\"images/*\"/>
					 <input type='hidden' class='id' value='$id'/>
					 <input type='hidden' class='type' value='$type'/>					 
                     </td>
                     <td class='input'><input id='herbicide_date_$id'  onchange='checkHerbicide($id);' class='date-input' placeholder='select date'/></td>
                     </tr>";
			   }
			 ?>

    </tbody>
</table>
                     <br/>
                     <br/>
                  <!-- <table class="table">
                      <tr>
                          <td> <strong>Total Cost Of Inputs:  </strong></td>
                          <td class="price">900,000</td>
                       </tr>
                     </table>-->
                     
                     
                  
                   </div>
                   
                   
                   <div class="col-md-5">
                   <br/>
                   <br/>
                   <br/>
                     <table>
<!-- <caption>Herbicides</caption>-->
                     <tbody>
        <tr>
        <td>Tractor Cost</td>
        <td class="input"  style=""><input id='tractor_amount' oninput="tractorSelected();" type="text" placeholder="amount"/></td>
         
        <td><a title="attach receipt" class="attach-file" onclick="attachFile(this)"><i class="fa fa-paperclip"></i> receipt</a>
                        <input type="file" name="receipt" class="hide" accept="image/*"/>
						<input type='hidden' class='id' value='0'/>
					 <input type='hidden' class='type' value='tractor'/>
					 <input id='acerage_tractor' type='hidden' class='type' />

            </td>
			<td class='input'><input id='tractor_date'  onchange='tractorSelected()' class='date-input' placeholder='select date'/></td>
                   
        </tr>
        
        <tr>
        <td>Cash Taken </td>
        <td class="input"><input  id='cash_amount' oninput="cashSelected();"type="text"  placeholder="ammount"/></td>
        <td>
            <a title="attach receipt" class="attach-file" onclick="attachFile(this)"><i class="fa fa-paperclip"></i> receipt</a>
            <input type="file" class="hide" name="receipt" accept="image/*"/>
			<input type='hidden' class='id' value='0'/>
					 <input type='hidden' class='type' value='cashtaken'/>
            </td>
            <td class='input'><input id='cash_date'  onchange='cashSelected()' class='date-input' placeholder='select date'/></td>
            
        
        </tr>
        
        
    </tbody>
</table>  
                       
                      <br/>
                       <hr/>    
                       
                    <h2 class="">Produce and payments</h2>
      <div class="form-group row">
            <label class="col-sm-4 control-label" for="textinput">Produce Supplied</label>
            <div class="col-sm-4">
              <input oninput="produceSelected();" type="text" placeholder="produce supplied" class="form-control" name="produce_supplied" id="produce_supplied"/>
              
			  <input id='produce_date'  onchange='produceSelected()' class='date-input' placeholder='select date'/>
          
			</div>
			 <!--  <div class="col-sm-1">
                
            </div> -->
          </div>

      <br/>
          <div class="form-group row">
            <label class="col-sm-4 control-label" for="textinput">Payment status</label>
            <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-6">
                  <div class = "checkbox">
              <label><input onclick="check1();"  name="paid" id="paid" type = "checkbox">&nbsp;&nbsp;Paid</label>
    
              </div>
                </div>
                <div class="col-sm-6">
                  <div class = "checkbox">
              <label><input onclick="check2();" name="unpaid" id="unpaid" type = "checkbox">&nbsp;&nbsp;Unpaid</label>
              
              </div>
              </div>
              </div>
    
          </div>
              <br/>
            
          <div class="form-group">
                <br/>
                <br/>
                <br/>
            <label class="col-sm-4 control-label" for="textinput">Produce receipt</label>
            <div class="col-sm-8">
                <a class="attach-file" onclick="attachFile(this)" title="attach receipt"><i class="fa fa-paperclip"></i> receipt</a>
              <input type="file" class="hide" name="receipt" accept="image/*"/>
			<input type='hidden' class='id' value='0'/>
					 <input type='hidden' class='type' value='produce'/>
            </div>
          </div>

                   
                   </div>
                       
                       <hr/>
                         <br/>
                       <br/>
   
                       <br/>
                       <br/>
                       <button onclick='saveRecords()' class="btn btn-success "><i class="fa fa-check"></i> Save Records</button>
                </div>
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
             <form class="form-horizontal" role="form" method="post" action="form_actions/saveToOutGrower.php" enctype="multipart/form-data" id="post_form" data-toggle="validator">
			 <input id="acerage" type="hidden" />
			 <input name="va_code" type="hidden" value="<?php echo $code;?>"/>
			 <input id="farmer_meta" type="hidden" name="farmer_meta" />
			 <input id="farmer_dataset_id" type="hidden" name="farmer_dataset_id" />
			 <input   name="o" type="hidden" value="<?php echo $_GET['o']; ?>"/>
			 <input   name="s" type="hidden" value="<?php echo $_GET['s']; ?>"/>
			 <input   name="token" type="hidden" value="<?php echo $_GET['token'];?>"/>
			 
			 <input id="seed_acre_unit" type="hidden" name="seed_unit_price" />
             <input id="fertilizer_acre_unit" type="hidden" name="fertilizer_unit_price" />
             <input id="herbicide_acre_unit"type="hidden"  name="herbicide_unit_price"/>
			 
			 <input id="seed_com_unit" type="hidden" name="seed_com_unit" />
             <input id="fertilizer_com_unit" type="hidden" name="fertilizer_com_unit"/>
             <input id="herbicide_com_unit" type="hidden" name="herbicide_com_unit" />
			 
                <div class="row">
             <div class="col-md-6">

            <fieldset>
             <!-- Form Name -->
            <h2 class="">Record input given out</h2>
             <!-- select input-->
            <div class="form-group">
           
            <div class="col-sm-8">
             <select id="seed_selected" onchange="seedInput();" class="form-control" name="seed">
			 <?php
               $client_id=$_SESSION['client_id'];
			   $seeds= $mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","client_id='$client_id' AND item_type='Seed' ");//item

			   echo" <option value=\"\" >Select  a seed</option>";
			   foreach($seeds as $seed){
			   $id=$seed['id'];
			   $item=$seed['item'];
			   echo" <option value=\"$id\" >$item</option>";
			   }
			 ?>
        
            </select>
            </div>
          </div>
		  
		  <div class="form-group">
            <div class="col-sm-8">
              <input id="seed_taken" oninput="calculateMonetary()" type="text" placeholder="Quantity of seed" name="seed_taken" id="" class="form-control">
            </div>
          </div>
           <!-- select input-->
          <div class="form-group">
          
            <div class="col-sm-8">
             <select id="fertilizer_selected" onchange="fertilizerInput();" class="form-control" name="fertilizer">      
            
             <?php
               $client_id=$_SESSION['client_id'];
			   $fertilizers= $mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","client_id='$client_id' AND item_type='Fertilizer'");//item

			   echo" <option value=\"\" >Select  a fertilizer</option>";
			   foreach($fertilizers as $fertilizer){
			   $id=$fertilizer['id'];
			   $item=$fertilizer['item'];
			   echo" <option value=\"$id\" >$item</option>";
			   }
			 ?>
             
            </select>
            </div>
          </div>
		  
		  <div class="form-group">
            <div class="col-sm-8">
              <input id="fertilizer_taken" oninput="calculateMonetary()" type="text" placeholder="Quantity of fertilizer" name="fertilizer_taken"  class="form-control">
            </div>
          </div>
           <!-- select input-->
          <div class="form-group">
            <div class="col-sm-8">
             <select id="herbicide_selected" onchange="herbicideInput();" class="form-control" name="herbicide" >
			 
			  <?php
               $client_id=$_SESSION['client_id'];
			   $herbicides= $mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","client_id='$client_id' AND item_type='Herbicide' ");//item

			   echo" <option value=\"\" >Select  a herbicide</option>";
			   foreach($herbicides as $herbicide){
			   $id=$herbicide['id'];
			   $item=$herbicide['item'];
			   echo" <option value=\"$id\" >$item</option>";
			   }
			 ?>
              
            </select>
            </div>
          </div>
           <div class="form-group">
            <div class="col-sm-8">
              <input id="herbicide_taken" oninput="calculateMonetary()" type="text" placeholder="Quantity of herbicide" name="herbicide_taken" class="form-control">
            </div>
          </div>
		  
		    <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Input monetary Value</label>
            <div class="col-sm-8">
              <input type="text" readonly placeholder="0" name="input_monetary_value" id="input_monetary_value" class="form-control"/>
            </div>
          </div>
		  
		   <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Tractor cost</label>
            <div class="col-sm-8">
              <input type="text" placeholder="Tractor cost" name="tractor_cost" id="tractor_cost" class="form-control">
            </div>
          </div>
		  
          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Cash taken</label>
            <div class="col-sm-8">
              <input type="text" placeholder="Cash Taken" name="cash_taken" id="cash_taken"  class="form-control">
            </div>
          </div>

           <!-- file input-->
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Input receipt</label>
            <div class="col-sm-8">
              <input type="file" placeholder="" name="file"  id="file" class="form-control">
            </div>
          </div>

          

        



        </fieldset>

    </div><!-- /.col-md 6 -->
<div class="col-md-6">
  <h2 class="">Produce and payments</h2>
      <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Produce Supplied</label>
            <div class="col-sm-8">
              <input type="text" placeholder="produce supplied" class="form-control" name="produce_supplied" id="produce_supplied">
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
              <label><input onclick="check1();"  name="paid" id="paid" type = "checkbox">&nbsp;&nbsp;Paid</label>
    
              </div>
                </div>
                <div class="col-sm-6">
                  <div class = "checkbox">
              <label><input onclick="check2();" name="unpaid" id="unpaid" type = "checkbox">&nbsp;&nbsp;Unpaid</label>
              
              </div>
              </div>
              </div>
    
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label" for="textinput">Produce receipt</label>
            <div class="col-sm-8">
              <input type="file" placeholder="" name="file2" id="file2" class="form-control">
            </div>
          </div>


</div>

    </div><!-- /.row -->

      <div class="modal-footer">
	  <?php
            if($_SESSION["role"]==1){
			echo"<button onclick='document.getElementById(\"post_form\").submit()' type=\"button\" class=\"btn btn-primary\">
                   Save
                </button>";
				}
				?>
                <button type="button" class="btn btn-danger"
                        data-dismiss="modal">
                            Cancel
                </button>

            </div> <!-- Modal Footer -->
    </form>

	
	
	<input type="hidden" id="seed_monetary_unit"/>
    <input type="hidden" id="fertilizer_monetary_unit"/>
    <input type="hidden" id="herbicide_monetary_unit"/>
	
            </div>
        </div>
    </div>
</div>

</div>

<?php include("include/footer_client.php");?>
<?php
function escapeJavaScriptText($string)
{
    return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
}
?>

<script>
jQuery(document).ready(function(){
 $("#profiled_farmers").html($("#ttl_farmers").val());
 $("#farmer_acreage").html($("#ttl_acerage").val());
 setProfilingCommission();
    
    
$('input.date-input').datepicker({
});
});

function check1(){
  if($("#paid").is(":checked")){
  
   $("#unpaid").prop('checked', false);
   
   
   
        var qty=1;
	    var date="";
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:0,type:'paid',qty:qty, date:date , token:'add' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
   
  }else{
  
  
        var qty=0;
	    var date="";
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:0,type:'paid',qty:qty, date:date , token:'add' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
   
  
  }
 
}
function check2(){
  if($("#unpaid").is(":checked")){
   //$("#paid").prop('checked', true);
   
   
   $("#paid").prop('checked', false);
   
        var qty=0;
	    var date="";
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:0,type:'paid',qty:qty, date:date , token:'add' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
		
		//window.alert(1);
  }
}

function setProfilingCommission(){

 $("#profilling_commision").html($("#ttl_farmers").val()*$("#unit_profiling_commission").val()+" UGX ");

}

function setAcreage(acerage,meta,dataset_id){
    document.getElementById('acerage_tractor').value = acerage;
	 document.getElementById('farmer_id').value = meta;

   $("#fname").html(document.getElementById(meta).value);
	setSeed(acerage);
	setFertilizer(acerage);
	setHerbicide(acerage);
	
	$.ajax({
     type: "POST",
     url: "form_actions/clear_input_session.php",
     data: { token: 'clear',
	         
			 },
     success: function(data) {
	 
	 }}
	 );
}

function seedInput(){
	acerage=document.getElementById('acerage').value;
	//seed_selected=document.getElementById('seed_id').value;
	//id=seed_selected;
	$.ajax({
     type: "POST",
     url: "form_actions/get_prediction.php",
     data: { token: 'getdefaults',
	         id: id,
			 },
     success: function(data) {
		
		if(data!=""){
		document.getElementById("seed_taken").readOnly = false;
		
		$("#seed_acre_unit").val( Math.round((data.unit_price)));
		 $("#seed_monetary_unit").val( Math.round((data.unit_per_acre)*acerage)*data.unit_price);
		 $("#seed_com_unit").val( Math.round(data.commission_per_unit));
		
	   $("#seed_taken").val( Math.round((data.unit_per_acre)*acerage)+" "+data.units);
	   calculateMonetary();
        }else{
		$("#seed_taken").val("N/A");
	     document.getElementById("seed_taken").readOnly = true;
		  $("#seed_monetary_unit").val(0);
		  $("#seed_acre_unit").val( 0);
		   $("#seed_com_unit").val( 0);
		  calculateMonetary();
		}
    }
    });
	
	}

	function fertilizerInput(){
	acerage=document.getElementById('acerage').value;
	fertilizer_selected=document.getElementById('fertilizer_selected').value;
	id=fertilizer_selected;
	$.ajax({
     type: "POST",
     url: "form_actions/get_prediction.php",
     data: { token: 'getdefaults',
	         id: id,
			 },
     success: function(data) {
		
		if(data!=""){
		document.getElementById("fertilizer_taken").readOnly = false
		$("#fertilizer_monetary_unit").val( Math.round((data.unit_per_acre)*acerage)*data.unit_price);
		$("#fertilizer_acre_unit").val( Math.round((data.unit_price)));
	   $("#fertilizer_taken").val( Math.round((data.unit_per_acre)*acerage)+" "+data.units);
	    $("#fertilizer_com_unit").val( Math.round(data.commission_per_unit));
	   calculateMonetary();
	    
		
        }else{
		$("#fertilizer_taken").val("N/A");
	     document.getElementById("fertilizer_taken").readOnly = true;
		 $("#fertilizer_monetary_unit").val( 0);
		 $("#fertilizer_acre_unit").val(0);
		  $("#fertilizer_com_unit").val( 0);
		 calculateMonetary();
		}
    }
    });
	
	}

	function herbicideInput(){

	acerage=document.getElementById('acerage').value;
	var herbicide_selected=document.getElementById('herbicide_selected').value;

	id=herbicide_selected;
	$.ajax({
     type: "POST",
     url: "form_actions/get_prediction.php",
     data: { token: 'getdefaults',
	         id: id,
			 },
     success: function(data) {
		
		if(data!=""){
		document.getElementById("herbicide_taken").readOnly = false
		$("#herbicide_monetary_unit").val( Math.round((data.unit_per_acre)*acerage)*data.unit_price);
		$("#herbicide_acre_unit").val( Math.round((data.unit_price)));
	   $("#herbicide_taken").val( Math.round((data.unit_per_acre)*acerage)+" "+data.units);
	    $("#herbicide_com_unit").val( Math.round(data.commission_per_unit));
	   calculateMonetary();
        }else{
		document.getElementById("herbicide_taken").readOnly = true;
        $("#herbicide_taken").val( "N/A");
		$("#herbicide_monetary_unit").val( 0);
		$("#herbicide_acre_unit").val( 0);
		 $("#herbicide_com_unit").val( 0);
		calculateMonetary();
		}
    }
    });
	
	}
	
	function calculateMonetary(){
	acerage=document.getElementById('acerage').value;
	var r = /\d+/;
    seed=(document.getElementById('seed_taken').value).match(r);
	fertilizer=(document.getElementById('fertilizer_taken').value).match(r);
	herbicide=(document.getElementById('herbicide_taken').value).match(r);
	
	seed_acre=(document.getElementById('seed_acre_unit').value).match(r);
	fertilizer_acre=(document.getElementById('fertilizer_acre_unit').value).match(r);
	herbicide_acre=(document.getElementById('herbicide_acre_unit').value).match(r);
	
	$("#input_monetary_value").val( seed_acre*seed+ fertilizer*fertilizer_acre + herbicide*herbicide_acre );
	}
	
	
    
    
   
    
    /////uploading files
    
    function attachFile(el){
        $(el).parent().find("input[type=file]").val(""); 
        $(el).parent().find("input[type=file]").trigger("click"); 
        ///$(el).parent().find("input[type=file]").trigger("click"); 
    }
    
    $("input[type=file]").change(function(e){
        
        var target = $(this).parent().find(".attach-file");
		
		
        
        target.html("<i class='fa fa-paperclip fa-spin'></i> attaching... ");
        //target.addClass("info");
		target.addClass("active");
		
		var id = $(this).parent().find("input.id").val();
		
		var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		var type = $(this).parent().find("input.type").val();
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
		
		$(this).upload("form_actions/upload_fa_reciept.php", { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:id,type:type}, function(data){  
           
           //upload done
		 // window.alert(data);
		  var txt = "Attached";
          target.html("<i class='fa fa-paperclip'></i> "+txt+" <i class='fa fa-remove remove' title='remove reciept' onclick='deleteFile(this, event)'></i>");
          //target.removeClass("info");
           // location.reload();
        },
      
         function(progress, value){
         //do something on progress
      })
		
    });
    
    
    
    function deleteFile(element, event){
        
        
        var el = $(element), par = el.parent(), fileinput = par.parent().find("input[type=file]");
        
		event.stopPropagation();
		
		var id = par.parent().find("input.id").val();
		
		var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		var type = par.parent().find("input.type").val();
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
		
		
		$.ajax({
        type: "POST",
        url: "form_actions/delete_receipt.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:id,type:type},
        success: function(data) {		  
           //upload done
		   
		   par.removeClass("active");
		  fileinput.val("");
           par.html("<i class='fa fa-paperclip'></i> receipt");
		  //window.alert(data);
		  
         
		  
        }});
        
    }
	
	
	function setSeed(acres){
	//alert(22)
	   $(".modal#details table tr").each(function(i, tr){
	     var perAcre = $(tr).find("input.per-acre-seed").val();
		  var unitPrice = $(tr).find("input.unit-price-seed").val();
		  var textBox = $(tr).find("input.qty-seed");
		  
		  var priceBox = $(tr).find("td.price-seed");
		 
		 
		  var qty = acres * perAcre;
		  qty = qty.toFixed(1);
          textBox.val(qty);	

		  priceBox.html((qty*unitPrice).toFixed(0) );
		 
	   
	   })
	
	}
	
	function setFertilizer(acres){
	//alert(22)
	   $(".modal#details table tr").each(function(i, tr){
	     var perAcre = $(tr).find("input.per-acre-fertilizer").val();
		 var unitPrice = $(tr).find("input.unit-price-fertilizer").val();
		 var textBox = $(tr).find("input.qty-fertilizer");
		 
		var priceBox = $(tr).find("td.price-fertilizer");
		 
		 var qty = acres * perAcre;
		 qty = qty.toFixed(1);
         textBox.val(qty);		 
	    priceBox.html((qty*unitPrice).toFixed(0) );
	   })
	
	}
	
	function setHerbicide(acres){

	
	   $(".modal#details table tr").each(function(i, tr){
	     var perAcre = $(tr).find("input.per-acre-herbicide").val();
		 var unitPrice = $(tr).find("input.unit-price-herbicide").val();
		 
		 var textBox = $(tr).find("input.qty-herbicide");
		 
		 var priceBox = $(tr).find("td.price-herbicide");
		 
		 var qty = acres * perAcre;
		 qty = qty.toFixed(1);
         textBox.val(qty);		 
	      
		 priceBox.html((qty*unitPrice).toFixed(0) );
		 
	   })
	
	}
    
	function seedSelected(id){
	
	if($("#seed_"+id).is(":checked")){
	
	    //window.alert(document.getElementById('seed_qty_'+id).value);
       
	    var qty=document.getElementById('seed_qty_'+id).value;
	    var date=document.getElementById('seed_date_'+id).value;
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:id,type:'seed',qty:qty, date:date , token:'add' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
	   
	}else{
	
	 // window.alert(document.getElementById('seed_qty_'+id).value);
       
	    var qty=document.getElementById('seed_qty_'+id).value;
	    var date=document.getElementById('seed_date_'+id).value;
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:id,type:'seed',qty:qty, date:date , token:'remove' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
	}
	
	
	}
	
	function updateSeedcost(id,unit_price){
	
	
	$(".price-seed#"+id).html((document.getElementById('seed_qty_'+id).value)*(unit_price));
	seedSelected(id);
	
	}
	
	function updateFertilizercost(id,unit_price){
	
	$(".price-fertilizer#"+id).html((document.getElementById('fertilizer_qty_'+id).value)*(unit_price));
	fertilizerSelected(id);
	}
	
	function updateHerbicidecost(id,unit_price){
	
	$(".price-herbicide#"+id).html((document.getElementById('herbicide_qty_'+id).value)*(unit_price));
	herbicideSelected(id);
	}
	
	
	function checkSeed(id){
	
	var date=document.getElementById('seed_date_'+id).value;
	
	if(date!=''){
	$("#seed_"+id).prop('checked', true);
	seedSelected(id);
	}
	
	}
	
	
	function checkFertilizer(id){
	
	var date=document.getElementById('fertilizer_date_'+id).value;
	if(date!=''){
	$("#fertilizer_"+id).prop('checked', true);
	fertilizerSelected(id);
	}
	
	
	}
	
	
	function checkHerbicide(id){
	
	var date=document.getElementById('herbicide_date_'+id).value;
    if(date!=''){
	$("#herbicide_"+id).prop('checked', true);
	herbicideSelected(id);
	}
	}
	
	
	function fertilizerSelected(id){
	
	if($("#fertilizer_"+id).is(":checked")){
	
	  // window.alert(document.getElementById('fertilizer_qty_'+id).value);
        var qty=document.getElementById('fertilizer_qty_'+id).value;
	    var date=document.getElementById('fertilizer_date_'+id).value;
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:id,type:'fertilizer',qty:qty, date:date , token:'add' },
        success: function(data) {		  
          
		  //upload done
		   
		 // window.alert(data);
		  
         
		  
        }});
	}else{
	
	  //window.alert(document.getElementById('fertilizer_qty_'+id).value +"  h");
	  
	  var qty=document.getElementById('fertilizer_qty_'+id).value;
	    var date=document.getElementById('fertilizer_date_'+id).value;
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:id,type:'fertilizer',qty:qty, date:date , token:'remove' },
        success: function(data) {		  
          
		  //upload done
		   
		 // window.alert(data);
		  
         
		  
        }});
	}
	}
	
	function  tractorSelected(){
	var acerage =document.getElementById('acerage_tractor').value;
	var ammount =document.getElementById('tractor_amount').value;
	var date =document.getElementById('tractor_date').value;
	
	if((ammount!="")&& (date!="")){
	

	   var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	    
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:acerage,type:'tractor',qty:ammount, date:date , token:'add' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
	}else{
	
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	    
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:0,type:'tractor',qty:ammount, date:date , token:'remove' },
        success: function(data) {		  
          
		  //upload done
		   
		 // window.alert(data);
		  
         
		  
        }});
	
	
	
	}
	
	
	}
	
	
	function  cashSelected(){
	
	var ammount =document.getElementById('cash_amount').value;
	var date =document.getElementById('cash_date').value;
	var acerage =document.getElementById('acerage_tractor').value;
	if((ammount!="")&& (date!="")){
	

	   var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	    
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:acerage,type:'cash',qty:ammount, date:date , token:'add' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
	}else{
	
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	    
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:acerage,type:'cash',qty:ammount, date:date , token:'remove' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
	
	
	
	}
	
	
	}
	
	
	function produceSelected(){
	
	 var qty=document.getElementById('produce_supplied').value;
	var date=document.getElementById('produce_date').value;
	if(qty>0 && date!=""){
	
	    
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:0,type:'produce',qty:qty, date:date , token:'add' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
	
	
	}else{
	
	    
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:0,type:'produce',qty:qty, date:date , token:'remove' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  
         
		  
        }});
	
	
	}
	
	}
	
	function saveRecords(){
	
	 $.ajax({
        type: "POST",
        url: "form_actions/saveFromOutGrowerCart.php",
        data: { token:'save' },
        success: function(data) {		  
          
		  //upload done
		   
		  //window.alert(data);
		  location.reload();
         
		  
        }});
	
	
	}
	
	
	
	function herbicideSelected(id){
	
	if($("#herbicide_"+id).is(":checked")){
	
	   

	   var qty=document.getElementById('herbicide_qty_'+id).value;
	    var date=document.getElementById('herbicide_date_'+id).value;
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:id,type:'herbicide',qty:qty, date:date , token:'add' },
        success: function(data) {		  
          
		  //upload done
		   
		 // window.alert(data);
		  
         
		  
        }});
		
		//window.alert(document.getElementById('herbicide_qty_'+id).value);
	   
	}else{
	
	  //window.alert(document.getElementById('herbicide_qty_'+id).value +"  h");
	  
	  var qty=document.getElementById('herbicide_qty_'+id).value;
	    var date=document.getElementById('herbicide_date_'+id).value;
	   
	    var va_dataset_id = getQueryVariable("s");
		var va_id = getQueryVariable("token");
		
		var farmer_dataset_id= document.getElementById('farmer_dataset_id').value;		
		var farmer_id= document.getElementById('farmer_id').value;
	   
	   $.ajax({
        type: "POST",
        url: "form_actions/set_input_cart.php",
        data: { farmer_dataset_id:farmer_dataset_id,va_dataset_id:va_dataset_id,va_id:va_id,farmer_id:farmer_id,id:id,type:'herbicide',qty:qty, date:date , token:'remove' },
        success: function(data) {		  
          
		  //upload done
		   
		 // window.alert(data);
		  
         
		  
        }});
	}
	}
	
	function getQueryVariable(variable) {
         var query = window.location.search.substring(1);
         var vars = query.split("&");
         for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
         return pair[1];
         }
         }

    }
    
</script>
