<?php
ini_set('upload_max_filesize', '60M');
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
$util_obj= new Utilties();

$token = $_GET['token'];
$type = $_GET['type'];
$farmer_id = $_POST['id'];
$dataset_id = $_GET['dataset_id'];

if(isset($_POST["id"]) &&$_POST["id"]!=''){

//echo $size;
    if ($_FILES["csv"]["size"] > 0)
    {

        $fileinfo = pathinfo($_FILES["csv"]["name"]);
        $filetype = $_FILES["csv"]["type"];
        $ext = strtolower(trim($fileinfo["extension"]));

        //Validate File Type
        if( !in_array($ext, array('csv','txt','docx','xlsx','pdf')))
        {
            $util_obj->redirect_to( "../admin/home.php?action=view&token=$token&type=$type&success=0&flag=-1" );
        }
        else
        {
//            $farmer_id = $_POST["id"];
            $id = $util_obj->encrypt_decrypt("encrypt", $_POST["id"]);
            $file_path = $_FILES["csv"]["tmp_name"];
            $targetPath =  "../uploads/farmer".$farmer_id."_".$fileinfo["basename"];
//            echo "success: <br> target_path: ".$targetPath."<br> tmp_name: ".$file_path;
            if(move_uploaded_file($file_path,$targetPath)) {
//            echo "success: <br> target_path:".$targetPath."<br> tmp_name:".$file_path;
                $response = $mCrudFunctions->insert_into_soil_testing_results($dataset_id, $targetPath, $farmer_id);
                if($response) $util_obj->redirect_to( "../admin/home.php?action=view&token=$token&type=$type&success=1&farmer=$id");
            }else{
                $util_obj->redirect_to( "../admin/home.php?action=view&token=$token&type=$type&success=0&flag=-2" );
            }
        }
    }else{
        $size = $_FILES["csv"]["size"];
        //print_r($_FILES);
        $util_obj->redirect_to( "../admin/home.php?action=view&token=$token&type=$type&success=0&flag=3&size=$size" );
    }

} else{
    $util_obj->redirect_to( "../admin/home.php?action=view&token=$token&type=$type&success=0&flag=0" );
}



?>