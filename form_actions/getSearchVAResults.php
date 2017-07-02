<?php


#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/pagination_class.php";
	

$mCrudFunctions = new CrudFunctions();



if(isset($_POST['id'])&&$_POST['id']!=""){
$per_page= 16;
$id= $_POST['id'];	

$page =!empty($_POST['page'])? (int)$_POST['page'] :1;
$sql="";
$total_count_default=$mCrudFunctions->get_count("dataset_".$id,1);

if(!empty($_POST['search'])){
$search=$_POST['search'];
$sql= " AND  (   name LIKE '%$search%' OR 
                 va_phonenumber ='$search' OR 
				  gender ='$search'
                  )";
}
$sql_=str_replace("AND","",$sql);
$total_count_default=$mCrudFunctions->get_count("dataset_".$id,$sql_);/// $sql_

$total_count =!empty($_POST['total_count'])? (int)$_POST['total_count'] :$total_count_default;
$pagination_obj= new Pagination($page,$per_page,$total_count); 
$offset= $pagination_obj->getOffset();
//$gender=$_POST['gender'];
$sql_=str_replace("AND","",$sql);
if($sql!=""){
  output($id, $sql_ . " GROUP BY va_phonenumber LIMIT $per_page OFFSET $offset " );
}else{
output($id,"  1  GROUP BY va_phonenumber LIMIT $per_page OFFSET $offset " );}
   
}







function output($id,$where){
$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$dataset_=$util_obj->encrypt_decrypt("encrypt", $id);
$dataset= $mCrudFunctions->fetch_rows("datasets_tb","*"," id =$id ");
$dataset_name=$dataset[0]["dataset_name"];
$dataset_type=$dataset[0]["dataset_type"];

$table="dataset_".$id;		 
$columns="*";

$rows= $mCrudFunctions->fetch_rows($table,$columns,$where);

 
if($dataset_type=="VA"){
if(sizeof($rows)==0){  
}else{
foreach($rows as $row ){
$real_id=$row['id'];

$real_id=$util_obj->encrypt_decrypt("encrypt", $real_id);
$name=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['name']));
$gender=$util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['gender']));
$dob='';//$row['biodata_farmer_dob'];
$picture=$util_obj->remove_apostrophes($row['picture']);
$district='';//$row['biodata_farmer_location_farmer_district'];
$phone_number=$util_obj->remove_apostrophes($row['va_phonenumber']); 

 echo"
 <div class=\"col-sm-12 col-md-4 col-lg-3\">
  
  <div class=\"thumbnail card\" id=\"\">
  <div class=\"image-box\" style=\"background-image:url('$picture'); 
  background-color:#e9e9e9; 
  background-position:center; 
  background-repeat:no-repeat;
  background-size:100%\">
  
   <span class=\"gender\">".substr($gender,0,1)."</span>
   </div>
  <div class=\"caption\" style=\"margin:0\"> 
  <p>$name</p><br/>

 <div class=\"row\">
    <div class=\"col-sm-4\">
  <p style=\"margin-top:10px\"><a  href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\" class=\" btn\" style=\"color:#F57C00; margin:0; width:100%;\">View Details &raquo;</a></p>
    </div>
     <div class=\"col-sm-4 col-sm-offset-2\">
     <p style=\"margin-top:10px\"><a href=\"view_farmers.php?s=$dataset_&token=$real_id&type=$dataset_type\" class=\" btn\" style=\"color:teal; margin:0; width:100%;\">View Farmers &raquo;</a></p>
    </div>
    </div>
      </div>
    </div>
  </div>
 
 
 
 ";

  }
 }
}
 
}
  


?>