<?php include("include/header_client.php");?>
<?php 

 #includes
require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();

$mCrudFunctions = new CrudFunctions();

?>
<?php include "include/breadcrumb.php"?>
<?php
if(isset($_GET['token'])&&$_GET['token']!=""){
$id=$util_obj->encrypt_decrypt("decrypt", $_GET['token']);
$origin=$util_obj->encrypt_decrypt("decrypt", $_GET['o']);
$s=$util_obj->encrypt_decrypt("decrypt", $_GET['s']);
//$dataset_=$util_obj->encrypt_decrypt("encrypt", $id);

$dataset= $mCrudFunctions->fetch_rows("dataset_".$s,"*"," id =$id");
$name=ucfirst(strtolower(str_replace("_"," ",$dataset[0]["biodata_va_name"])));;

$client_id=$_SESSION["client_id"];


//echo $origin." ".$id." ".$s."  ".$client_id;
$reciepts=$mCrudFunctions->fetch_rows("va_reciept_tb","*"," client_id ='$client_id' AND dataset_id='$s' AND va_id='$id' ");


}
?>
<style>
    .list-group-item-heading{
        font-size: 12pt !important;
    }
</style>
<div class="container">
 <h3 style="border-bottom:solid 1px #eee; padding:0" class="row">Receipts - <a><?php echo $name; ?></a> <a class="pull-right btn attach-file" onclick="attachFile(this)"> <i class="tiny fa fa-plus"></i> Add Reciept</a>
    <input type="file" class="hide" name="receipt"/>
    </h3>
</div>
<div class="container">
 <div class="col-md-4">
    
   <?php
   $i =0;
   foreach($reciepts as $reciept){
   $i++;
   $id=$reciept['id'];
   $url=$reciept['reciept_url'];
   echo"<div class=\"list-group\">
    <a href=\"#\" onclick='selectedImg( $i,$id,\"{$url}\");' class=\"list-group-item active\">
     <h4 class=\"list-group-item-heading\"><img  src=\"images/va_receipt/$url\" style=\"width:50px;\"/> Reciept#$i</h4>
     </a>
   </div>
   ";
   
   }
   
   ?>
  
         

    </div>
    <div class="col-md-4" style="border-left:solid 1px #ddd; min-height:60vh">
     
        <img id="view_big" src="" style="width:100%"/>
         
    </div>
	<div class="col-md-2">
	<p id="img"></p>
	<input id="id" type="hidden" />


<?php

if($_SESSION['role']==1){
	
echo'
 <a class="btn btn-danger hide btn-delete" onclick="deleteReceipt()"> <i class="fa fa-close"></i> Delete Receipt</a>';
}
	
?>
</div>
</div>

<?php include("include/footer_client.php");?>
<script type="text/javascript">
   
    function selectedImg(i,id,img){
	document.getElementById("view_big").src='images/va_receipt/'+img;
	$('#img').html("Reciept#"+i);
	$('#id').val(id);
	//added by ibrah
	
	$(".btn-delete").removeClass("hide");
	}
	
	
    function attachFile(el){
        $(el).parent().find("input[type=file]").val(""); 
        $(el).parent().find("input[type=file]").trigger("click"); 
    }
    
    $("input[type=file]").change(function(e){
        
        var target = $(this).parent().find(".attach-file");    
       
        target.html("<a class='fa fa-spin fa-spinner'> </a> Uploading..");
        
        
		var s= getQueryVariable('s');
		var id= getQueryVariable('token');
        //upload file
        
       $(this).upload("form_actions/upload_va_reciept.php", { s:s, id:id}, function(data){  
           
           //upload done
		  
            location.reload();
        },
      
         function(progress, value){
         //do something on progress
      })
    });
    
	
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
 
 function deleteReceipt(){
 swal(
 
 {title:"delete receipt?",
 text:"are you sure?", 
 type:"warning", 
 showCancelButton: true,   
 confirmButtonColor: "#DD6B55"},
  
    function(isConfirm)
    {   if (isConfirm) { 

	///delete code here
	
	var id = document.getElementById("id").value;
	
	$.ajax({
     type: "POST",
     url: "form_actions/delete_va_reciept.php",
     data: { 
	         id:id
			 },
     success: function(data) {
       
	  // window.alert(data);
	  swal("Done", "Receipt was deleted successfully!", "success");
	   location.reload();
    }
    });
	
	}});
	
	
	}
</script>


