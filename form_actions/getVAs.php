<?php
session_start();
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/pagination_class.php";
//require_once "http://us2.php.net/manual/en/ref.image.php";

$mCrudFunctions = new CrudFunctions();



if(isset($_POST['id'])&&$_POST['id']!=""){
$origin=$_POST['origin'];

$page =!empty($_POST['page'])? (int)$_POST['page'] :1;

$per_page= 16;
$id= $_POST['id'];	
$total_count_default=$mCrudFunctions->get_count("dataset_".$id,1);

$total_count =!empty($_POST['total_count'])? (int)$_POST['total_count'] :$total_count_default;
 $pagination_obj= new Pagination($page,$per_page,$total_count); 
 $offset= $pagination_obj->getOffset();
 //$gender=$_POST['gender'];
 

output($origin,$id," 1 LIMIT $per_page OFFSET $offset");

}
 
 function output($origin,$id,$where){
     $util_obj = new Utilties();
     $db = new DatabaseQueryProcessor();
     $mCrudFunctions = new CrudFunctions();
     $dataset_ = $util_obj->encrypt_decrypt("encrypt", $id);
     $dataset = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id ");
     $dataset_name = $dataset[0]["dataset_name"];
     $dataset_type = $dataset[0]["dataset_type"];

     $table = "dataset_" . $id;
     $columns = "*";

     $rows = $mCrudFunctions->fetch_rows($table, $columns, $where);

     if ($dataset_type == "VA") {
         if (sizeof($rows) == 0) {
         } else {
             foreach ($rows as $row) {
                 $real_id = $row['id'];

                 $real_id = $util_obj->encrypt_decrypt("encrypt", $real_id);
                 $name = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_va_name']));
                 $gender = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_va_gender']));
                 $dob = '';//$row['biodata_farmer_dob'];
                 $picture = $util_obj->remove_apostrophes($row['biodata_va_picture']);

                 $name = strlen($name) > 18 ? substr($name, 0, 16) . "..." : $name;
                 echo "
 <div class=\"col-sm-12 col-md-4 col-lg-3\">
  
  <div class=\"thumbnail card\" id=\"\">
  <div class=\"image-box\" style=\"background-image:url('$picture'); 
  background-color:#e9e9e9; 
  background-position:center; 
  background-repeat:no-repeat;
  background-size:100%\">
  
   <span class=\"gender\">" . substr($gender, 0, 1) . "</span>
   </div>
  <div class=\"caption\" style=\"margin:0\"> 
  <p style='padding-bottom:20px; border-bottom:1px solid #eee; width:100%;'>$name</p>

 <div class=\"card-foot\">
    ";

                 if ($_SESSION["role"] == 1) {
                     echo "<a title='add a reciept' class='btn attach-file' onclick='attachFile(this); getVa(\"{$real_id}\");'><i class='fa fa-paperclip'></i> Add receipt</a>
  <input name='receipt' type='file' accept='image/*' class='hide'>";
                 }

                 echo "
  <div class=\"btn-group\">
    <a class='dropdown-toggle btn' data-toggle=\"dropdown\" aria-haspopup=\"true\" > <i class='fa fa-ellipsis-v'></i> View More </a>
    <ul class='dropdown-menu'>
        <li><a href='user_details.php?o=$origin&s=$dataset_&token=$real_id&type=$dataset_type'>VA Profile</a></li>
        <li><a href=\"va_farmers.php?o=$origin&s=$dataset_&token=$real_id&type=$dataset_type\" class=''>VA Farmers</a></li>
        <li><a href=\"va_receipts.php?o=$origin&s=$dataset_&token=$real_id&type=$dataset_type\">Receipts</a></li>    
    </ul>
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


