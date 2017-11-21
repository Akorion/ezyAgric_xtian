<?php
#includes
require_once dirname(__FILE__)."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";

$newline ="\n";
$nextcell="";
$header="";
$body="";

if(isset($_POST['id'])&&$_POST['id']!=""){
    $id=$_POST['id'];
    $client_id = $_SESSION["client_id"];
    $role =  $_SESSION['role'];
    $branch = $_SESSION['user_account'];

$mCrudFunctions = new CrudFunctions();
$util_obj= new Utilties();

$dataset_r = $mCrudFunctions->fetch_rows("datasets_tb","dataset_name"," id ='$id' AND client_id = '$client_id'");
$dataset=$dataset_r[0]["dataset_name"];

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$dataset.".csv");

if($_SESSION["account_name"] == "Rushere SACCO") {
    if($role == 1){
        $bio_data_columns = $mCrudFunctions->fetch_rows("bio_data","columns"," dataset_id ='$id'");

        $farmer_location_columns = $mCrudFunctions->fetch_rows("farmer_location","columns"," dataset_id ='$id'");

        $production_data_columns = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," dataset_id ='$id'");

        $info_on_other_enterprise_columns = $mCrudFunctions->fetch_rows("info_on_other_enterprise","columns"," dataset_id ='$id'");

        $general_questions_columns = $mCrudFunctions->fetch_rows("general_questions","columns"," dataset_id ='$id'");

        foreach($bio_data_columns as $column){

            $value=str_replace("biodata_farmer_","",$column['columns']);
            $value = str_replace("biodata_","",$column['columns']);
            if($value=='picture'){}else{
                $value=str_replace("_"," ",strtoupper($value));
                $header .='"'.$util_obj->escape_csv_value($value).'",';
            }

        }

        foreach($farmer_location_columns as $column){

            $value=str_replace("biodata_farmer_location_farmer_","",$column['columns']);
            $value=str_replace("home_gps_","",$value);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($production_data_columns as $column){

            $enterprise=$column['enterprise'];
            $value=str_replace($enterprise."_production_data_","",$column['columns']);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($info_on_other_enterprise_columns as $column){
            $value=str_replace("information_on_other_crops_","",$column['columns']);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($general_questions_columns as $column){
            $value=str_replace("general_questions_","",$column['columns']);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }
        $body .=$header;
        $body .=$newline ;
        $rows=$mCrudFunctions->fetch_rows("dataset_".$id,"*",1);

        foreach($rows as $row){

            foreach($bio_data_columns as $column){

                $value=str_replace("biodata_farmer_","",$column['columns']);
                $value=str_replace("biodata_","",$column['columns']);
                if($value=='picture'){}else{
                    $key=$column['columns'];
                    $final=$row[$key];
                    $final=str_replace("_"," ",$final);
                    $body  .='"'.$util_obj->escape_csv_value($final).'",';
                }

            }

            foreach($farmer_location_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }

            foreach($production_data_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }

            foreach($info_on_other_enterprise_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }

            foreach($general_questions_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }
            $body .=$newline ;
        }

    } elseif ($role == 2) {
        $bio_data_columns = $mCrudFunctions->fetch_rows("bio_data","columns"," dataset_id ='$id'");

        $farmer_location_columns = $mCrudFunctions->fetch_rows("farmer_location","columns"," dataset_id ='$id'");

        $production_data_columns = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," dataset_id ='$id'");

        $info_on_other_enterprise_columns = $mCrudFunctions->fetch_rows("info_on_other_enterprise","columns"," dataset_id ='$id'");

        $general_questions_columns = $mCrudFunctions->fetch_rows("general_questions","columns"," dataset_id ='$id'");

        foreach($bio_data_columns as $column){

            $value=str_replace("biodata_farmer_","",$column['columns']);
            $value = str_replace("biodata_","",$column['columns']);
            if($value=='picture'){}else{
                $value=str_replace("_"," ",strtoupper($value));
                $header .='"'.$util_obj->escape_csv_value($value).'",';
            }

        }

        foreach($farmer_location_columns as $column){

            $value=str_replace("biodata_farmer_location_farmer_","",$column['columns']);
            $value=str_replace("home_gps_","",$value);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($production_data_columns as $column){

            $enterprise=$column['enterprise'];
            $value=str_replace($enterprise."_production_data_","",$column['columns']);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($info_on_other_enterprise_columns as $column){
            $value=str_replace("information_on_other_crops_","",$column['columns']);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($general_questions_columns as $column){
            $value=str_replace("general_questions_","",$column['columns']);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }
        $body .=$header;
        $body .=$newline ;
        $rows=$mCrudFunctions->fetch_rows("dataset_".$id,"*","sacco_branch_name LIKE '$branch' ");

        foreach($rows as $row){

            foreach($bio_data_columns as $column){

                $value=str_replace("biodata_farmer_","",$column['columns']);
                $value=str_replace("biodata_","",$column['columns']);
                if($value=='picture'){}else{
                    $key=$column['columns'];
                    $final=$row[$key];
                    $final=str_replace("_"," ",$final);
                    $body  .='"'.$util_obj->escape_csv_value($final).'",';
                }

            }

            foreach($farmer_location_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }

            foreach($production_data_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }

            foreach($info_on_other_enterprise_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }

            foreach($general_questions_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }
            $body .=$newline ;
        }
    } else {
        $bio_data_columns = $mCrudFunctions->fetch_rows("bio_data","columns"," dataset_id ='$id'");

        $farmer_location_columns = $mCrudFunctions->fetch_rows("farmer_location","columns"," dataset_id ='$id'");

        $production_data_columns = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," dataset_id ='$id'");

        $info_on_other_enterprise_columns = $mCrudFunctions->fetch_rows("info_on_other_enterprise","columns"," dataset_id ='$id'");

        $general_questions_columns = $mCrudFunctions->fetch_rows("general_questions","columns"," dataset_id ='$id'");

        foreach($bio_data_columns as $column){

            $value=str_replace("biodata_farmer_","",$column['columns']);
            $value = str_replace("biodata_","",$column['columns']);
            if($value=='picture'){}else{
                $value=str_replace("_"," ",strtoupper($value));
                $header .='"'.$util_obj->escape_csv_value($value).'",';
            }

        }

        foreach($farmer_location_columns as $column){

            $value=str_replace("biodata_farmer_location_farmer_","",$column['columns']);
            $value=str_replace("home_gps_","",$value);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($production_data_columns as $column){

            $enterprise=$column['enterprise'];
            $value=str_replace($enterprise."_production_data_","",$column['columns']);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($info_on_other_enterprise_columns as $column){
            $value=str_replace("information_on_other_crops_","",$column['columns']);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }

        foreach($general_questions_columns as $column){
            $value=str_replace("general_questions_","",$column['columns']);
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';

        }
        $body .=$header;
        $body .=$newline ;
        $rows=$mCrudFunctions->fetch_rows("dataset_".$id,"*","biodata_cooperative_name LIKE '$branch' ");

        foreach($rows as $row){

            foreach($bio_data_columns as $column){

                $value=str_replace("biodata_farmer_","",$column['columns']);
                $value=str_replace("biodata_","",$column['columns']);
                if($value=='picture'){}else{
                    $key=$column['columns'];
                    $final=$row[$key];
                    $final=str_replace("_"," ",$final);
                    $body  .='"'.$util_obj->escape_csv_value($final).'",';
                }

            }

            foreach($farmer_location_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }

            foreach($production_data_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }

            foreach($info_on_other_enterprise_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }

            foreach($general_questions_columns as $column){

                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';

            }
            $body .=$newline ;
        }
    }

} else {
    $bio_data_columns = $mCrudFunctions->fetch_rows("bio_data","columns"," dataset_id ='$id'");

    $farmer_location_columns = $mCrudFunctions->fetch_rows("farmer_location","columns"," dataset_id ='$id'");

    $production_data_columns = $mCrudFunctions->fetch_rows("production_data","enterprise,columns"," dataset_id ='$id'");

    $info_on_other_enterprise_columns = $mCrudFunctions->fetch_rows("info_on_other_enterprise","columns"," dataset_id ='$id'");

    $general_questions_columns = $mCrudFunctions->fetch_rows("general_questions","columns"," dataset_id ='$id'");

    foreach($bio_data_columns as $column){

        $value=str_replace("biodata_farmer_","",$column['columns']);
        $value = str_replace("biodata_","",$column['columns']);
        if($value=='picture'){}else{
            $value=str_replace("_"," ",strtoupper($value));
            $header .='"'.$util_obj->escape_csv_value($value).'",';
        }

    }

    foreach($farmer_location_columns as $column){

        $value=str_replace("biodata_farmer_location_farmer_","",$column['columns']);
        $value=str_replace("home_gps_","",$value);
        $value=str_replace("_"," ",strtoupper($value));
        $header .='"'.$util_obj->escape_csv_value($value).'",';

    }

    foreach($production_data_columns as $column){

        $enterprise=$column['enterprise'];
        $value=str_replace($enterprise."_production_data_","",$column['columns']);
        $value=str_replace("_"," ",strtoupper($value));
        $header .='"'.$util_obj->escape_csv_value($value).'",';

    }

    foreach($info_on_other_enterprise_columns as $column){
        $value=str_replace("information_on_other_crops_","",$column['columns']);
        $value=str_replace("_"," ",strtoupper($value));
        $header .='"'.$util_obj->escape_csv_value($value).'",';

    }

    foreach($general_questions_columns as $column){
        $value=str_replace("general_questions_","",$column['columns']);
        $value=str_replace("_"," ",strtoupper($value));
        $header .='"'.$util_obj->escape_csv_value($value).'",';

    }
    $body .=$header;
    $body .=$newline ;
    $rows=$mCrudFunctions->fetch_rows("dataset_".$id,"*","sacco_branch_name LIKE '$branch' ");

    foreach($rows as $row){

        foreach($bio_data_columns as $column){

            $value=str_replace("biodata_farmer_","",$column['columns']);
            $value=str_replace("biodata_","",$column['columns']);
            if($value=='picture'){}else{
                $key=$column['columns'];
                $final=$row[$key];
                $final=str_replace("_"," ",$final);
                $body  .='"'.$util_obj->escape_csv_value($final).'",';
            }

        }

        foreach($farmer_location_columns as $column){

            $key=$column['columns'];
            $final=$row[$key];
            $final=str_replace("_"," ",$final);
            $body  .='"'.$util_obj->escape_csv_value($final).'",';

        }

        foreach($production_data_columns as $column){

            $key=$column['columns'];
            $final=$row[$key];
            $final=str_replace("_"," ",$final);
            $body  .='"'.$util_obj->escape_csv_value($final).'",';

        }

        foreach($info_on_other_enterprise_columns as $column){

            $key=$column['columns'];
            $final=$row[$key];
            $final=str_replace("_"," ",$final);
            $body  .='"'.$util_obj->escape_csv_value($final).'",';

        }

        foreach($general_questions_columns as $column){

            $key=$column['columns'];
            $final=$row[$key];
            $final=str_replace("_"," ",$final);
            $body  .='"'.$util_obj->escape_csv_value($final).'",';

        }
        $body .=$newline ;
    }
}

echo $body ;

}


?>