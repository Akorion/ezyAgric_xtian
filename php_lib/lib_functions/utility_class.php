<?php
require_once dirname(dirname(__FILE__))."/lib_functions/server_connection_class.php";
require_once dirname(dirname(__FILE__))."/user_functions/crud_functions_class.php";
Class Utilties{

public function __construct(){
	
}

public function getJsonArrayFromUrl($url,$format){
    
	$out=array();
	$content = $this->get_url_content(str_replace('{format}',$format,$url)); 
	$out= null;
	if($content) {
	
		if($format == "json") {
			$json = json_decode($content,true);
			$out=$json['data'];
		}
	}
	return $out;
}

 public function deliver_response($status,$status_message,$data ){

    header("HTTP/1.1 $status $status_message");
    
   
	if($data!=null){
	 $response=$data;
	}else{
	$response['status']=$status;
    $response['status_message']=$status_message;
	}

    $json_response=json_encode($response);

    echo $json_response;
	
    }

public function get_url_content($url) {
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}

public function redirect_to( $location = NULL ) {
		if ($location != NULL) {
			header("Location: {$location}");
			exit;
		}
}

public function getHostIp() {
	return $_SERVER['SERVER_ADDR'];//getenv("SERVER_ADDR");
}

public function getClientIp() {
   return $_SERVER['REMOTE_ADDR'];//getenv("REMOTE_ADDR");
}

public function getTimeForIntrests2Date( $date,$date2 ) {

$date=date_create(str_replace(":000"," ",$date));
$date=date_format($date,"Y-m-d");

$date2=date_create(str_replace(":000"," ",$date2));
$date2=date_format($date2,"Y-m-d");

return $this->daysBetween($date,$date2)/30.4167;

}

public function getTimeForIntrests( $date,$time_zone ) {

$date=date_create(str_replace(":000"," ",$date));
$date=date_format($date,"Y-m-d");

$current_date= Date('Y-m-d');

$current_date=$this->ChangeTimeZone( $current_date,$time_zone);

return $this->daysBetween($date,$current_date)/30;

}


public function getTimeForLogs($time_zone ) {

$current_date= Date('Y-m-d h:i a');

$default_time_zone= date_default_timezone_get();
$date= new DateTime($current_date, new DateTimeZone($default_time_zone));
$date->setTimeZone( new DateTimeZone($time_zone));

return $date->format('Y-m-d H:i a');;

}

public function getAge( $birth_date,$time_zone ) {

$birth_date=date_create(str_replace(":000"," ",$birth_date));
$birth_date=date_format($birth_date,"Y-m-d");

$current_date= Date('Y-m-d');

$current_date=$this->ChangeTimeZone( $current_date,$time_zone);

return round($this->daysBetween($birth_date,$current_date)/365,0);

}


public function ChangeTimeZone( $current_date,$to_time_zone ) {
$default_time_zone= date_default_timezone_get();
$date= new DateTime($current_date, new DateTimeZone($default_time_zone));
$date->setTimeZone( new DateTimeZone($to_time_zone));
return $date->format('Y-m-d H:i:s');
}


/*
* @param $value
 * @return mixed
 */
public function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}

public function mysql_prep( $value ) {
		return addslashes($value);
}
public function escape_csv_value($value){
    $value=str_replace('"','""',$value);// escape all " and make them ""
     if(preg_match('/,/',$value) or preg_match("/\n/",$value) or preg_match('/"/',$value)){// check if any commas or new lines
        return '"'.$value.'"';// if new line or commas escape them
     }else{
        return $value;
     }

}

// Generates a strong password of N length containing at least one lower case letter,
// one uppercase letter, one digit, and one special character. The remaining characters
// in the password are chosen at random from those four sets.
//
// The available characters in each set are user friendly - there are no ambiguous
// characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
// makes it much easier for users to manually type or speak their passwords.
//
// Note: the $add_dashes option will increase the length of the password by
// floor(sqrt(N)) characters.
 
public function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
	$sets = array();
	if(strpos($available_sets, 'l') !== false)
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	if(strpos($available_sets, 'u') !== false)
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	if(strpos($available_sets, 'd') !== false)
		$sets[] = '23456789';
	//if(strpos($available_sets, 's') !== false)
		//$sets[] = '!@#$%&*?';
 
	$all = '';
	$password = '';
	foreach($sets as $set)
	{
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}
 
	$all = str_split($all);
	for($i = 0; $i < $length - count($sets); $i++)
		$password .= $all[array_rand($all)];
 
	$password = str_shuffle($password);
 
	if(!$add_dashes)
		return $password;
 
	$dash_len = floor(sqrt($length));
	$dash_str = '';
	while(strlen($password) > $dash_len)
	{
		$dash_str .= substr($password, 0, $dash_len) . '-';
		$password = substr($password, $dash_len);
	}
	$dash_str .= $password;
	return $dash_str;
}

public function dateBefore($date){
    $one_day= strtotime(date("Y-m-d"))-strtotime("-1 days");
    $day_before=strtotime($date)-$one_day;
	return getdate($day_before);
}

public function dateAfter($date){
    $one_day= strtotime(date("Y-m-d"))-strtotime("-2 days");
    $day_after=strtotime($date)+$one_day;
	return getdate($day_after);
}

public function daysBetween($date1,$date2){

    $diff_days = floor((strtotime($date2)-strtotime($date1))/86400);
	return $diff_days;
}

public function insert_into($db,$table,$columns,$values)
{
    $insert_query="INSERT INTO ".$table."(".$columns.") VALUES(".$values.")"; 
	#do the insertion
    return  $db->setResultForQuery($insert_query);
}

public function update_table($db,$table,$columns_value,$where)
{
    $insert_query="UPDATE ".$table."  SET ".$columns_value." WHERE ".$where." ";
	#do the insertion
    return $db->setResultForQuery($insert_query);
}


public function get_result_array($db,$table,$columns,$where)
{
   $query="SELECT ".$columns." FROM ".$table." WHERE ".$where ; 
   $db->setResultForQuery($query);
   return $db->getFullResultArray();;
}

    public function get_farmer_result_array($db,$table,$columns)
    {
        $query="SELECT ".$columns." FROM ".$table;
        $db->setResultForQuery($query);
        return $db->getFullResultArray();;
    }

public function getColumnsString($db,$table,$where)
{

    $query="SELECT * FROM ".$table." WHERE ".$where ; 
    $db->setResultForQuery($query);
	$fields = $db->getAllFieldsInResult();

	$values = array();	$sql_array = array();
	foreach($fields as $key){
		array_push($values, $key->name);
	}

//	echo sizeof($values)."<br/>";
	for($j=1; $j<sizeof($values); $j++){
		$sql_array[] = $values[$j];
	}
//	$this->debug_to_console($values)."<br>";
    return $sql_array;
}

public function insertCSVintoTable($db,$table,$column_string,$handle){
	$con = new ServerConnection();
   $counter=0;

	$data_array = array();
   while(($data = fgetcsv($handle,0,";"," ")) !== FALSE){
	   array_push($data_array, $data);
       $counter = sizeof($data);
   }

//	$this->debug_to_console($data_array)."<br>";
	$values = array();
	foreach($data_array as $real_data){
		foreach($real_data as $key => $value){
			$real_data[$key] = mysqli_real_escape_string($con->getServerConnection(), $real_data[$key]);
		}
		$values[] = "('" . implode("', '", $real_data). "')";
	}

	$query= " INSERT INTO ".$table." (".implode(', ', $column_string).") VALUES " .implode(', ', $values);

//    $this->debug_to_console($query);
    $flagx = $db->setResultForQuery($query);
    if (!$flagx) {
        return mysqli_error($con->getServerConnection());
    } else {
        return $counter;
    }
}

public function get_acerage_from_geo($latitudes,$longitudes){

    if (sizeof($latitudes) > 0 && sizeof($longitudes)) {
        $first_lat = $latitudes[0];
        $first_long = $longitudes[0];

        array_push($latitudes, $first_lat);
        array_push($longitudes, $first_long);

        $acerage_constant = 0.00024711;
        $e_circum = 40091147;
        if (sizeof($latitudes) == sizeof($longitudes) && $latitudes[0] == $latitudes[sizeof($latitudes) - 1] && $longitudes[0] == $longitudes[sizeof($longitudes) - 1]) {
            $y = array();
            for ($i = 0; $i < sizeof($latitudes) - 2; $i++) {
                $y_value = (($latitudes[$i + 1] - $latitudes[0]) / 360) * $e_circum;
                array_push($y, $y_value);
            }

//print_r($y);
            $x = array();
            for ($i = 0; $i < sizeof($longitudes) - 2; $i++) {
                $x_cosine = cos(($latitudes[$i + 1] / 180) * (22 / 7));
                $x_value = (($longitudes[$i + 1] - $longitudes[0]) / 360) * $e_circum * $x_cosine;
                array_push($x, $x_value);
            }
//print_r($x);

            $area = array();
            for ($i = 0; $i < sizeof($x) - 1; $i++) {
                $area_value = (($y[$i] * $x[$i + 1]) - ($x[$i] * $y[$i + 1])) / 2;
                array_push($area, $area_value);
            }
//print_r($area);
            $total_squared = pow(array_sum($area), 2);
            $final = sqrt($total_squared) * $acerage_constant;

            return round($final/*array_sum($area)*/, 3);

        } else {

            return "InputError";

        }
    } else {

        return 0;
    }
}

/*

public function get_ip_address getClientIpV4() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if ($this->validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}
/**
 * Ensures an ip address is both a valid IP and does not fall within
 * a private network range.
 */
public function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}


function getClientIpV4() {
		//Just get the headers if we can or else use the SERVER global
		if ( function_exists( 'apache_request_headers' ) ) {
			$headers = apache_request_headers();
		} else {
			$headers = $_SERVER;
		}
		//Get the forwarded IP if it exists
		if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$the_ip = $headers['X-Forwarded-For'];
		} elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
		) {
			$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
		} else {
			
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}
	return $the_ip;
}	
	
public function createTableFromCSV($db,$handle,$table){

       $data = fgetcsv($handle,0,";"," ");
	   $columns= sizeof($data);
       $query ="";
	   if($columns>0){
	   
	   $query = "create table $table (";
	   $query  .= " id INT NOT NULL AUTO_INCREMENT PRIMARY KEY  , ";
        
		for ($i = 0; $i < $columns; $i++) 
        {
	    /* $validate_=  str_replace(" ","_",$data[$i]);
		 $validate_=  str_replace("\""," ",$validate_);
		 $validate_=  str_replace(":","_",$validate_);
		 $query  .= "$validate_ text ";*/
		 $validate_=  str_replace(" ","_",$data[$i]);
		 $validate_=  str_replace("\"","",$validate_);
		 $validate_=  str_replace(":","_",$validate_);
		 $validate_=  str_replace("-","_",$validate_);
		 $validate_=  str_replace("KEY","KEY_",$validate_);
		 $query  .= "$validate_ text ";
		 
		 if(($i+1) != $columns){ $query .=","; }
   
	    }
         $query .= ")";
       }
    return $db->setResultForQuery($query);
}
public function remove_apostrophes($string)
{
    return str_replace('"'," ",$string);
}

public function get_date_string($date_time)
{
    return date('j / m/  Y', strtotime($date_time));
}

public function get_time_string($date_time)
{
    return date('g:i a', strtotime($date_time));
}

public function captalizeEachWord($string){
$string= strtolower($string) ;
$string=str_replace(".","",$string);
$string=str_replace("_"," ",$string);
$string=str_replace('"'," ",$string);
return ucwords($string);
}


public function encrypt_decrypt($action, $string){ 
    $output = false; 

    $encrypt_method = "AES-256-CBC"; 
    $secret_key = '230591'; 
    $secret_iv = '233593'; 

    // hash 
    $key = hash('sha256', $secret_key); 
     
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning 
    $iv = substr(hash('sha256', $secret_iv), 0, 16); 

    if( $action == 'encrypt' ) { 
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv); 
        $output = base64_encode($output); 
    } 
    else if( $action == 'decrypt' ){ 
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv); 
    } 

    return $output; 
}

public function insert_into_farmers_bio_data($db,$table){
	$mCrudFunctions = new CrudFunctions();
	$columns= "*";
	$where = 1;
	$rows = $mCrudFunctions->fetch_rows($table,$columns,$where);
	foreach($rows as $row){
		$value_name = $row['biodata_farmer_name'];
		$value_contact = $row['biodata_farmer_phone_number'];
		$contact = $row[0][$value_contact];
		$fname = $row[0][$value_name];
		

	}

	$query = "SELECT * FROM ".$table;
	$db->setResultForQuery($query);
	$farmer_data = array();
	while($data = $db->getResult()){
		array_push($farmer_data, $data);
	}
	foreach($farmer_data as $farmer){
		$farmer_name[] = $farmer['biodata_farmer_name'];
		$contact[] = $farmer['biodata_farmer_phone_number'];
		$photo_url[] = $farmer['biodata_farmer_picture'];
		$district[] = $farmer['biodata_farmer_location_farmer_district'];
		$sub_county[] = $farmer['biodata_farmer_location_farmer_subcounty'];
		$parish[] = $farmer['biodata_farmer_location_farmer_parish'];
		$village[] = $farmer['biodata_farmer_location_farmer_village'];
//		$region[] = $farmer['biodata_farmer_location_farmer_region'];
		$dob[] = $farmer['biodata_farmer_dob'];
		$gender[] = $farmer['biodata_farmer_gender'];
	}
	$this->debug_to_console($farmer_name)."<br/>";

	$tbl = "farmers_bio_data";
	$result="";
//	$values="";
//	$sql = "INSERT farmer_name,contact FROM ".$tbl;
	for($i=0; $i<sizeof($farmer_data); $i++){
		$columns_value = "photo_url =".$photo_url[$i];
		$where = "farmer_name =".$farmer_name[$i]."AND contact =".$contact[$i];
		$result = $this->update_table($db,$tbl,$columns_value,$where);
		if(!$result){
			$columns = "farmer_name,contact,photo_url,district,subcounty,parish,village,dob,gender";
			$values = "'$farmer_name[$i]','$contact[$i]','$photo_url[$i]','$district[$i]','$sub_county[$i]','$parish[$i]','$village[$i]','$dob[$i]','$gender[$i]'";
			$result = $this->insert_into($db,$tbl,$columns,$values);
		}
	}
	return $result;
}

public function debug_to_console($data) {
	if(is_array($data) || is_object($data)) {
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: $data');</script>");
	}
}

}
?>