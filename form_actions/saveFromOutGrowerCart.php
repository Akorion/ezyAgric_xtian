<?php
session_start();

#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";;

$mCrudFunctions = new CrudFunctions();
 $util_obj= new Utilties();
 

foreach($_SESSION['inputs']['seed'] as $seed_array){


//print_r($seed_array);
$client_id =$seed_array['client_id'];

$user_id = $seed_array['user_id'];
$va_dataset_id =$seed_array['va_dataset_id'];
$va_id = $seed_array['va_id'];

$va_rows= $mCrudFunctions->fetch_rows("dataset_".$va_dataset_id,"*"," id='$va_id' ");
$va_name=$va_rows[0]['biodata_va_name'];

$code=str_replace(".","",$va_rows[0]['biodata_va_code']);
$code=str_replace(" ","",$code);
$code=strtolower($code);
$va_code=$code;


$dataset_id = (int)$seed_array['farmer_dataset_id'];
$meta_id = $seed_array['farmer_id'];

$farmer_name=$mCrudFunctions->fetch_rows("dataset_".$dataset_id,"*"," id='$meta_id' ")[0]['biodata_farmer_name'];


$item_id =(int)$seed_array['id'];
$row=$mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","id='$item_id'");
$unit_price=(int)$row[0]['unit_price'];
$qty = (double)$seed_array['qty'];
$date=str_replace("/", ".", $seed_array['date']);



$reciept_rows = $mCrudFunctions->fetch_rows("input_reciept_temp_tb","*","client_id='$client_id' AND user_id='$user_id' AND va_dataset_id='$va_dataset_id' AND va_id='$va_code' AND farmer_dataset_id='$dataset_id' AND
farmer_id='$meta_id' AND input_id='$item_id' AND input_type='seed'");

if(sizeof($reciept_rows)>0){
$reciept_id=$reciept_rows[0]['id'];
$reciept_url=$reciept_rows[0]['reciept_url'];
if($date!=""){

$flag = $mCrudFunctions->insert_into_out_grower_input_tb($dataset_id, $va_code, $meta_id, $item_id, $unit_price, $qty,$date,$reciept_url);
//echo $flag;

 $user_id=$_SESSION["user_id"];
 $client_id=$_SESSION["client_id"];
 $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
 $ip_address=$util_obj->getClientIpV4();
 $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Saved seed information under VA:$va_name and Farmer: $farmer_name ", $time_of_activity, $ip_address);

if($flag==1){
unset($_SESSION['inputs']['seed']['seed'.$item_id]);
$mCrudFunctions->delete('input_reciept_temp_tb',"id='$reciept_id'");
}

}

}



//echo "</br>";
}


foreach($_SESSION['inputs']['herbicide'] as $herbicide_array){


print_r($herbicide_array);
$client_id =$herbicide_array['client_id'];

$user_id = $herbicide_array['user_id'];
$va_dataset_id =$herbicide_array['va_dataset_id'];
$va_id = $herbicide_array['va_id'];


$va_rows= $mCrudFunctions->fetch_rows("dataset_".$va_dataset_id,"*"," id='$va_id' ");
$va_name=$va_rows[0]['biodata_va_name'];


$code=str_replace(".","",$va_rows[0]['biodata_va_code']);
$code=str_replace(" ","",$code);
$code=strtolower($code);
$va_code=$code;

$dataset_id = (int)$herbicide_array['farmer_dataset_id'];
$meta_id = $herbicide_array['farmer_id'];

$farmer_name=$mCrudFunctions->fetch_rows("dataset_".$dataset_id,"*"," id='$meta_id' ")[0]['biodata_farmer_name'];


$item_id =(int)$herbicide_array['id'];
$row=$mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","id='$item_id'");
$unit_price=(int)$row[0]['unit_price'];
$qty = (double)$herbicide_array['qty'];
$date=str_replace("/", ".", $herbicide_array['date']);


$reciept_rows = $mCrudFunctions->fetch_rows("input_reciept_temp_tb","*","client_id='$client_id' AND user_id='$user_id' AND va_dataset_id='$va_dataset_id' AND va_id='$va_code' AND farmer_dataset_id='$dataset_id' AND
farmer_id='$meta_id' AND input_id='$item_id' AND input_type='herbicide'");
echo sizeof($reciept_rows);
if(sizeof($reciept_rows)>0){
$reciept_id=$reciept_rows[0]['id'];
$reciept_url=$reciept_rows[0]['reciept_url'];

if($date!=""){

$flag = $mCrudFunctions->insert_into_out_grower_input_tb($dataset_id, $va_code, $meta_id, $item_id, $unit_price, $qty,$date,$reciept_url);

if($flag==1){
 unset($_SESSION['inputs']['herbicide']['herbicide'.$item_id]);
 $mCrudFunctions->delete('input_reciept_temp_tb',"id='$reciept_id'");

 $user_id=$_SESSION["user_id"];
 $client_id=$_SESSION["client_id"];
 $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
 $ip_address=$util_obj->getClientIpV4();
 $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Saved herbicide information under VA:$va_name and Farmer: $farmer_name ", $time_of_activity, $ip_address);

}

}

}

//echo "</br>";
}


foreach($_SESSION['inputs']['fertilizer'] as $fertilizer_array){


//print_r($fertilizer_array);
$client_id =$fertilizer_array['client_id'];

$user_id = $fertilizer_array['user_id'];
$va_dataset_id =$fertilizer_array['va_dataset_id'];
$va_id = $fertilizer_array['va_id'];

$va_rows= $mCrudFunctions->fetch_rows("dataset_".$va_dataset_id,"*"," id='$va_id' ");
$va_name=$va_rows[0]['biodata_va_name'];

$code=str_replace(".","",$va_rows[0]['biodata_va_code']);
$code=str_replace(" ","",$code);
$code=strtolower($code);
$va_code=$code;

$dataset_id = (int)$fertilizer_array['farmer_dataset_id'];
$meta_id = $fertilizer_array['farmer_id'];

$farmer_name=$mCrudFunctions->fetch_rows("dataset_".$dataset_id,"*"," id='$meta_id' ")[0]['biodata_farmer_name'];


$item_id =(int)$fertilizer_array['id'];
$row=$mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","id='$item_id'");
$unit_price=(int)$row[0]['unit_price'];
$qty = (double)$fertilizer_array['qty'];
$date=str_replace("/", ".", $fertilizer_array['date']);

$reciept_rows = $mCrudFunctions->fetch_rows("input_reciept_temp_tb","*","client_id='$client_id' AND user_id='$user_id' AND va_dataset_id='$va_dataset_id' AND va_id='$va_code' AND farmer_dataset_id='$dataset_id' AND
farmer_id='$meta_id' AND input_id='$item_id' AND input_type='fertilizer'");

if(sizeof($reciept_rows)>0){
$reciept_id=$reciept_rows[0]['id'];
$reciept_url=$reciept_rows[0]['reciept_url'];
if($date!=""){

$flag = $mCrudFunctions->insert_into_out_grower_input_tb($dataset_id, $va_code, $meta_id, $item_id, $unit_price, $qty,$date,$reciept_url);
echo $flag;
if($flag==1){
unset($_SESSION['inputs']['fertilizer']['fertilizer'.$item_id]);
$mCrudFunctions->delete('input_reciept_temp_tb',"id='$reciept_id'");

 $user_id=$_SESSION["user_id"];
 $client_id=$_SESSION["client_id"];
 $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
 $ip_address=$util_obj->getClientIpV4();
 $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Saved fertilizer information under VA:$va_name and Farmer: $farmer_name ", $time_of_activity, $ip_address);

}

}
}
//echo "</br>";
}


foreach($_SESSION['inputs']['cash'] as $cash_array){


print_r($_SESSION['inputs']['cash']);
$client_id =$cash_array['client_id'];

$user_id = $cash_array['user_id'];
$va_dataset_id =$cash_array['va_dataset_id'];

$va_id = $cash_array['va_id']; //
$va_rows= $mCrudFunctions->fetch_rows("dataset_".$va_dataset_id,"*"," id='$va_id' ");
$va_name=$va_rows[0]['biodata_va_name'];

$code=str_replace(".","",$va_rows[0]['biodata_va_code']);
$code=str_replace(" ","",$code);
$code=strtolower($code);
$va_code=$code;

$dataset_id = (int)$cash_array['farmer_dataset_id'];
$meta_id = $cash_array['farmer_id'];

$farmer_name=$mCrudFunctions->fetch_rows("dataset_".$dataset_id,"*"," id='$meta_id' ")[0]['biodata_farmer_name'];


$acreage =(double)$cash_array['id'];
//$row=$mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","id='$item_id'");
//$unit_price=(int)$row[0]['unit_price'];
$amount = (double)$cash_array['qty'];
$date=str_replace("/", ".", $cash_array['date']);

$reciept_rows = $mCrudFunctions->fetch_rows("input_reciept_temp_tb","*","client_id='$client_id' AND va_dataset_id='$va_dataset_id' AND va_id='$va_code' AND farmer_dataset_id='$dataset_id' AND
farmer_id='$meta_id' AND input_id='0' AND input_type='cashtaken' AND user_id='$user_id' ");

echo sizeof($reciept_rows);
if(sizeof($reciept_rows)>0){
$reciept_id=$reciept_rows[0]['id'];
$reciept_url=$reciept_rows[0]['reciept_url'];

if($date!=""){

$flag = $mCrudFunctions->insert_into_out_grower_cashinput_tb($dataset_id, $va_code, $meta_id, $amount, 'cashtaken',$acreage,$date,$reciept_url);
echo $flag;
if($flag==1){
unset($_SESSION['inputs']['cash']['cash'.$acreage]);
$mCrudFunctions->delete('input_reciept_temp_tb',"id='$reciept_id'");

 $user_id=$_SESSION["user_id"];
 $client_id=$_SESSION["client_id"];
 $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
 $ip_address=$util_obj->getClientIpV4();
 $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Saved cashtaken information under VA:$va_name and Farmer: $farmer_name ", $time_of_activity, $ip_address);


}

}
}
//echo "</br>";

}

foreach($_SESSION['inputs']['tractor'] as $tractor_array){


print_r($_SESSION['inputs']['tractor']);
$client_id =$tractor_array['client_id'];

$user_id = $tractor_array['user_id'];
$va_dataset_id =$tractor_array['va_dataset_id'];

$va_id = $tractor_array['va_id']; //
$va_rows= $mCrudFunctions->fetch_rows("dataset_".$va_dataset_id,"*"," id='$va_id' ");

$va_name=$va_rows[0]['biodata_va_name'];


$code=str_replace(".","",$va_rows[0]['biodata_va_code']);
$code=str_replace(" ","",$code);
$code=strtolower($code);
$va_code=$code;

$dataset_id = (int)$tractor_array['farmer_dataset_id'];
$meta_id = $tractor_array['farmer_id'];

$farmer_name=$mCrudFunctions->fetch_rows("dataset_".$dataset_id,"*"," id='$meta_id' ")[0]['biodata_farmer_name'];


$acreage =(double)$tractor_array['id'];
//$row=$mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","id='$item_id'");
//$unit_price=(int)$row[0]['unit_price'];
$amount = (double)$tractor_array['qty'];
$date=str_replace("/", ".", $tractor_array['date']);

$reciept_rows = $mCrudFunctions->fetch_rows("input_reciept_temp_tb","*","client_id='$client_id' AND user_id='$user_id' AND va_dataset_id='$va_dataset_id' AND va_id='$va_code' AND farmer_dataset_id='$dataset_id' AND
farmer_id='$meta_id' AND input_id='0' AND input_type='tractor'");

if(sizeof($reciept_rows)>0){
$reciept_id=$reciept_rows[0]['id'];
$reciept_url=$reciept_rows[0]['reciept_url'];

if($date!=""){

$flag = $mCrudFunctions->insert_into_out_grower_cashinput_tb($dataset_id, $va_code, $meta_id, $amount, 'tractor',$acreage,$date,$reciept_url);
echo $flag;
if($flag==1){
unset($_SESSION['inputs']['tractor']['tractor'.$acreage]);
$mCrudFunctions->delete('input_reciept_temp_tb',"id='$reciept_id'");

 $user_id=$_SESSION["user_id"];
 $client_id=$_SESSION["client_id"];
 $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
 $ip_address=$util_obj->getClientIpV4();
 $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Saved tractor information under VA:$va_name and Farmer: $farmer_name ", $time_of_activity, $ip_address);


}

}

}

//echo "</br>";
}


foreach($_SESSION['inputs']['produce'] as $produce_array){


print_r($_SESSION['inputs']['produce']);
$client_id =$produce_array['client_id'];

$user_id = $produce_array['user_id'];
$va_dataset_id =$produce_array['va_dataset_id'];

$va_id = $produce_array['va_id']; //
$va_rows= $mCrudFunctions->fetch_rows("dataset_".$va_dataset_id,"*"," id='$va_id' ");
$va_name=$va_rows[0]['biodata_va_name'];


$code=str_replace(".","",$va_rows[0]['biodata_va_code']);
$code=str_replace(" ","",$code);
$code=strtolower($code);
$va_code=$code;

$dataset_id = (int)$produce_array['farmer_dataset_id'];
$meta_id = $produce_array['farmer_id'];

$farmer_name=$mCrudFunctions->fetch_rows("dataset_".$dataset_id,"*"," id='$meta_id' ")[0]['biodata_farmer_name'];



$id =(double)$produce_array['id'];
$row=$mCrudFunctions->fetch_rows("out_grower_threshold_tb","*"," client_id='$client_id' AND item='produce_supplied'  ");
$unit_price=(int)$row[0]['unit_price'];
$commission_per_unit= $row[0]['commission_per_unit'];
$qty = (double)$produce_array['qty'];
$date=str_replace("/", ".", $produce_array['date']);

$payment_status='no';

$reciept_rows = $mCrudFunctions->fetch_rows("input_reciept_temp_tb","*","client_id='$client_id' AND va_dataset_id='$va_dataset_id' AND va_id='$va_code' AND farmer_dataset_id='$dataset_id' AND
farmer_id='$meta_id' AND input_id='0' AND input_type='produce' AND user_id='$user_id' ");

echo sizeof($reciept_rows);
if(sizeof($reciept_rows)>0){
$reciept_id=$reciept_rows[0]['id'];
$reciept_url=$reciept_rows[0]['reciept_url'];


$flag = $mCrudFunctions->insert_into_out_grower_produce_tb($dataset_id, $va_code, $meta_id, $qty, $commission_per_unit, $reciept_url, $payment_status,$unit_price,$date);
echo $flag;
if($flag==1){
unset($_SESSION['inputs']['produce']['produce'.$id]);
$mCrudFunctions->delete('input_reciept_temp_tb',"id='$reciept_id'");

 $user_id=$_SESSION["user_id"];
 $client_id=$_SESSION["client_id"];
 $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
 $ip_address=$util_obj->getClientIpV4();
 $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Saved produce information under VA:$va_name and Farmer: $farmer_name ", $time_of_activity, $ip_address);


}

}

//echo "</br>";
}

foreach($_SESSION['inputs']['paid'] as $paid_array){


print_r($_SESSION['inputs']['paid']);
$client_id =$paid_array['client_id'];

$user_id = $paid_array['user_id'];
$va_dataset_id =$paid_array['va_dataset_id'];

$va_id = $paid_array['va_id']; //
$va_rows= $mCrudFunctions->fetch_rows("dataset_".$va_dataset_id,"*"," id='$va_id' ");
$va_name=$va_rows[0]['biodata_va_name'];



$code=str_replace(".","",$va_rows[0]['biodata_va_code']);
$code=str_replace(" ","",$code);
$code=strtolower($code);
$va_code=$code;

$dataset_id = (int)$paid_array['farmer_dataset_id'];
$meta_id = $paid_array['farmer_id'];

$farmer_name=$mCrudFunctions->fetch_rows("dataset_".$dataset_id,"*"," id='$meta_id' ")[0]['biodata_farmer_name'];


$id =(double)$paid_array['id'];
//$row=$mCrudFunctions->fetch_rows("out_grower_threshold_tb","*","client_id='$client_id' AND item='produce_supplied'  ");
//$unit_price=(int)$row[0]['unit_price'];
//$commission_per_unit= $row[0]['commission_per_unit'];
$qty = (double)$paid_array['qty'];
//$date=str_replace("/", ".", $tractor_array['date']);
$payment_status='no';
if($qty==1){
$payment_status='yes';


}

$receipt="";

$flag = $mCrudFunctions->update('out_grower_produce_tb',"payment_status='$payment_status'","dataset_id='$dataset_id' AND meta_id='$meta_id' AND va_code='$va_code' AND payment_status!='yes' ");
echo $flag;
if($flag==1){
unset($_SESSION['inputs']['produce']['produce'.$id]);
 $user_id=$_SESSION["user_id"];
 $client_id=$_SESSION["client_id"];
 $time_of_activity= $util_obj->getTimeForLogs('Africa/Nairobi');
 $ip_address=$util_obj->getClientIpV4();
 $mCrudFunctions->insert_into_clients_logs_tb($client_id, $user_id, "Saved payment status information under VA:$va_name and Farmer: $farmer_name ", $time_of_activity, $ip_address);

}



//echo "</br>";
}

?>