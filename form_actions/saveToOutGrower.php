<?php

session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

 $mCrudFunctions = new CrudFunctions();
 $util_obj= new Utilties();
 $client_id=$_SESSION['client_id'];
 $o=$_POST['o'];
 $token=$_POST['token'];
 $s=$_POST['s'];
 $dataset_id=$_POST['farmer_dataset_id'];
if(isset($_POST['va_code'])&&$_POST['va_code']!=""
&& isset($_POST['farmer_meta'])&&$_POST['farmer_meta']!=""
&& isset($_POST['farmer_dataset_id'])&&$_POST['farmer_dataset_id']!=""){
$va_code =$_POST['va_code'];
$farmer_meta =$_POST['farmer_meta'];
$farmer_dataset_id=$_POST['farmer_dataset_id'];



if($_POST['seed']!=""){
$item_id=$_POST['seed'];
$qty=$_POST['seed_taken'];
$unit_price=$_POST['seed_unit_price'];

//dataset_id`, `va_code`, `meta_id`, `item_id`, `unit_price`, `qty
$count=$mCrudFunctions->get_count("out_grower_input_tb"," va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND item_id='$item_id'  ");

if($count>0){
$flag=$mCrudFunctions->update("out_grower_input_tb"," unit_price='$unit_price', qty='$qty' ","va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND item_id='$item_id' ");
}else{
$flag=$mCrudFunctions->insert_into_out_grower_input_tb($farmer_dataset_id, $va_code, $farmer_meta, $item_id, $unit_price, $qty);


}


}

if($_POST['fertilizer']!=""){
$item_id=$_POST['fertilizer'];
$qty=$_POST['fertilizer_taken'];
$unit_price=$_POST['fertilizer_unit_price'];

$count=$mCrudFunctions->get_count("out_grower_input_tb"," va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND item_id='$item_id'  ");

if($count>0){
$flag=$mCrudFunctions->update("out_grower_input_tb"," unit_price='$unit_price', qty='$qty' ","va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND item_id='$item_id' ");
}else{
$flag=$mCrudFunctions->insert_into_out_grower_input_tb($farmer_dataset_id, $va_code, $farmer_meta, $item_id, $unit_price, $qty);

}
}

if($_POST['herbicide']!=""){
$item_id=$_POST['herbicide'];
$qty=$_POST['herbicide_taken'];
$unit_price=$_POST['herbicide_unit_price'];

$count=$mCrudFunctions->get_count("out_grower_input_tb"," va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND item_id='$item_id'  ");

if($count>0){
$flag=$mCrudFunctions->update("out_grower_input_tb"," unit_price='$unit_price', qty='$qty' ","va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND item_id='$item_id' ");
}else{
$flag=$mCrudFunctions->insert_into_out_grower_input_tb($farmer_dataset_id, $va_code, $farmer_meta, $item_id, $unit_price, $qty);

}
}

if($_POST['tractor_cost']>0){
$amount=$_POST['tractor_cost'];
$acreage=$_POST['acreage'];
$count=$mCrudFunctions->get_count("out_grower_cashinput_tb"," va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND cash_type='tractor'  ");

if($count>0){
//`dataset_id`, `va_code`, `meta_id`, `amount`, `cash_type`

$flag=$mCrudFunctions->update("out_grower_cashinput_tb","  amount='$amount' acreage='$acreage'","va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND cash_type='tractor' ");

}else{

$flag=$mCrudFunctions->insert_into_out_grower_cashinput_tb($farmer_dataset_id, $va_code, $farmer_meta, $amount, 'tractor',$acreage);

}
}

if($_POST['cash_taken']>0){
$amount=$_POST['cash_taken'];
$acreage=$_POST['acreage'];
$count=$mCrudFunctions->get_count("out_grower_cashinput_tb"," va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND cash_type='cashtaken'  ");

if($count>0){
//`dataset_id`, `va_code`, `meta_id`, `amount`, `cash_type`

$flag=$mCrudFunctions->update("out_grower_cashinput_tb","  amount='$amount' ,acreage='$acreage' ","va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' AND cash_type='cashtaken' ");

}else{
$flag=$mCrudFunctions->insert_into_out_grower_cashinput_tb($farmer_dataset_id, $va_code, $farmer_meta, $amount, 'cashtaken',$acreage);

}

}
 $target="../images/receipt/";
 
 if(isset($_FILES['file']['name'])){
 
    // Path to move uploaded files
     $temp_image = explode(".", $_FILES["file"]["name"]);
	
     $newimagename = "c".round(microtime(true)) . '.' . end($temp_image);
     
     $sizeimage = $_FILES['file']['size'];
	 
	 if(($sizeimage/1024/1024  > 17)){
	   
	 }else{
	    $image_flag=false;
		
		if (move_uploaded_file($_FILES['file']['tmp_name'], $target.$newimagename)) {
          $image_flag=true;
        }
		
		
		if( $image_flag==true){
		   $image_url=str_replace('..',"",$target.$newimagename);
		  
		  //$count=$mCrudFunctions->get_count("outgrower_input_reciept_tb"," va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' ");

		  //if($count > 0){
		  
		  //$flag=$mCrudFunctions->update("outgrower_input_reciept_tb","  receipt='$image_url' ","va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' ");
  
		  //}else{
		  
		  $flag=$mCrudFunctions->insert_into_outgrower_input_reciept_tb($farmer_dataset_id, $va_code, $farmer_meta, $image_url);
		  //}
		  
		 
		   
		}
	}
}

//produce_supplied
if($_POST['produce_supplied']){
$qty=$_POST['produce_supplied'];


$row = $mCrudFunctions->fetch_rows("out_grower_threshold_tb","commission_per_unit"," client_id='$client_id' AND item_type='Service' AND item='produce_supplied' ");
$payment_status=$_POST['paid'];
if($_POST['unpaid']){
$payment_status=$_POST['unpaid'];
}

$commission_per_unit=$row[0]['commission_per_unit'];

$count=$mCrudFunctions->get_count("out_grower_produce_tb"," va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta'   ");
//`dataset_id`, `va_code`, `meta_id`, `qty`, `commission_per_unit`, `receipt`, `payment_status`
if($count>0){

$flag=$mCrudFunctions->update("out_grower_produce_tb","  qty='$qty' ","va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' ");

}else{
$flag=$mCrudFunctions->insert_into_out_grower_produce_tb($farmer_dataset_id, $va_code, $farmer_meta, $qty, $commission_per_unit, "", $payment_status);

}


 $target="../images/receipt/";
 
 if(isset($_FILES['file2']['name'])){
 
    // Path to move uploaded files
     $temp_image = explode(".", $_FILES["file2"]["name"]);
	
     $newimagename = "c".round(microtime(true)) . '.' . end($temp_image);
     
     $sizeimage = $_FILES['file2']['size'];
	 
	 if(($sizeimage/1024/1024  > 17)){
	   
	 }else{
	    $image_flag=false;
		
		if (move_uploaded_file($_FILES['file2']['tmp_name'], $target.$newimagename)) {
          $image_flag=true;
        }
		
		
		if( $image_flag==true){
		   $image_url=str_replace('..',"",$target.$newimagename);
		  
		   $flag=$mCrudFunctions->update("out_grower_produce_tb","  receipt='$image_url' ","va_code='$va_code' AND dataset_id='$farmer_dataset_id'  AND  meta_id='$farmer_meta' ");
  
		}
	}
}
 
 
 }



 
}

$util_obj->redirect_to( "../va_farmers.php?o=$o&s=$s&token=$token&type=VA" );

?>