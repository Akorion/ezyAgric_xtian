<?php
//connect to database
$con = mysql_connect("localhost","root","");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
//select database
mysql_select_db("highchart", $con) or die('not selecting');

//$id = $_GET['id'];

//define array
//we need two arrays - "male" and "female" so $arr and $arr1 respectively!
$arr 	= array();
$arr1 	= array();
$result = array();

//get the result from the table "highcharts_data"
$sql = "select * from higcharts_data where map_id = 1";
$q	 = mysql_query($sql);
$j = 0;
while($row = mysql_fetch_assoc($q)){

//highcharts needs name, but only once, so give a IF condition
    if($j == 0){
        $arr['name'] = 'Male';
        $arr1['name'] = 'Female';
        $j++;
    }
    //and the data for male and female is here
    $arr['data'][] = $row['male'];
    $arr1['data'][] = $row['female'];
}

//after get the data for male and female, push both of them to an another array called result
array_push($result,$arr);
array_push($result,$arr1);

//now create the json result using "json_encode"
print json_encode($result, JSON_NUMERIC_CHECK);

mysql_close($con);
?>