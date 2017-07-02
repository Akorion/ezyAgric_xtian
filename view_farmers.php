<style>
#center{
text-align:center;
background-color:#fff;

}
table,tr,thead,th
{
    color:orange;
}
th
{
color:orange;
}
 .table{
 width:100% !important;
 
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
   /* 
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
    */
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
     .table-condensed{
        background: #fff !important;
        color: #444;
     }
     .table-condensed tr:nth-child(odd){
        color: #444 !important;
        background: #E0E0E0;
         height: 2em !important;
     }
     .table-condensed tr:nth-child(even){
        color: #444 !important;
        background: #BDBDBD;
        height: 2em !important;
     }
     .table-condensed thead tr:nth-child(odd){
        background: #fff !important;
        color: #795548;
        height: 3em !important;
     }
    .right{
    float:right;
    margin:0 30px;
    
    }
    .btn-fab{
    position:fixed !important;
    right:70px;
    bottom:110px;
    z-index:100;
    }

.thumbnail1 img{
    display: block;
    max-width: 100%;
    height: 30%;
   /* padding-left: .5em;
    padding-top: .5em;*/
    box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);
    background: #fff;
} 
.card1{

}
h5{
    border:none !important;
    color:#fff !important;
    margin:0 !important;
    background-color:#A7B6B1 !important;
}
</style>

<?php include("./include/header_client.php");?>
<?php

if(isset($_GET['token'])&&$_GET['token']!=""&&isset($_GET['s'])&&$_GET['s']!=""
   &&isset($_GET['type'])&&$_GET['type']!=""){
   
   
$type=$_GET['type'];
$dataset_id=$util_obj->encrypt_decrypt("decrypt", $_GET['s']);
$id=$util_obj->encrypt_decrypt("decrypt", $_GET['token']);


echo"<input id=\"dataset_id\" type=\"hidden\" value=\"$dataset_id\" />";
echo"<input id=\"id\" type=\"hidden\" value=\"$id\" />";
echo"<input id=\"type\" type=\"hidden\" value=\"$type\" />";



$table="dataset_".$dataset_id;		 
$columns="*";
$where=" id='$id' ";
$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);


if(sizeof($rows)==0){ }else
{

$id=$rows[0]['id'];

if($type=="VA"){

$latitude=$util_obj->remove_apostrophes($rows[0]['va_home_location_Latitude']);
$longitude=$util_obj->remove_apostrophes($rows[0]['va_home_location_Longitude']);
$picture=$util_obj->remove_apostrophes($rows[0]['picture']);
$gender=$util_obj->captalizeEachWord($rows[0]['gender']);
$name=$util_obj->captalizeEachWord($rows[0]['name']);
$phone=$util_obj->remove_apostrophes($rows[0]['va_phonenumber']);



echo"<input id=\"picture\" type=\"hidden\" value=\"$picture\" />";
echo"<input id=\"uuid\" type=\"hidden\" value=\"$uuid\" />";

///////////////////////////////////////////// lat_long_pic starts

 /////////////////////////////////////////////personal data_ends 
}
}
}
?>
<div class="container">
    <div class="row data1">
    <div class="col-md-5">
    <div class="thumbnail1 card1">
  <div class="">
  <img src="<?php echo $picture ;?>" class="img-responsive">

    </div>
</div>
</div>

    <div class="col-md-7">
   <div class="card">
    <h5>BIO DATA</h5>
   <h6>Name:</h6>
   <p><?php echo $name;?></p><hr/>
   <h6>Gender:</h6>
   <p><?php echo $gender;?></p><hr/>
  <h6>Phone:</h6>
   <p><?php echo $phone?></p><hr/>

</div> 
  </div>
</div> <!-- end of row data1-->

<div style="margin-top: -3em;">
    <h5 class="">List of VA's Farmers</h5>
</div>
<div class="row data1" style="margin-top: -.1em;">
    <div class="col-lg-12 col-md-12 col-sm-12">
   <table style="width: 100%;" class="table-condensed responsive-table centered striped">
        <thead>
          <tr>
              <th>Name</th>
              <th>Acerage</th>
              <th>Inputs Taken</th>
              <th>Cash value of inputs</th>
              <th>Tractor usage</th>
              <th>Extra cash given to farmer</th>
              <th>Produce Suplied</th>
              <th>Cash owed</th>
             <!--  <th>Inputs Taken</th> -->
          </tr>
        </thead>

        <tbody>
        <?php for ($i=1; $i <10 ; $i++) { 
            # code...
            echo '
                    <tr>
            <td>Marvin</td>
            <td>100</td>
            <td>Maize</td>
            <td>100</td>
            <td>Yes</td>
            <td>200</td>
            <td>Marvin</td>
            <td>100</td>
          </tr>
            ';
        } ?>
          
          
         
        </tbody>
      </table>
</div>

    </div>
</div>
  

</div>
<?php include("./include/footer_client.php"); ?>
