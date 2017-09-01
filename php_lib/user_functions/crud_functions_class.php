<?php
require_once dirname(dirname(__FILE__)) . "/lib_functions/utility_class.php";
require_once dirname(dirname(__FILE__)) . "/lib_functions/database_query_processor_class.php";


class CrudFunctions
{
    public function __construct()
    {

    }


    public function get_sum($table, $column, $where)
    {
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $query = "SELECT SUM($column) AS SUM FROM " . $table . " WHERE " . $where;
        #do the query
        $mDatabaseQueryProcessor->setResultForQuery($query);
        $row = $mDatabaseQueryProcessor->getResultArray();
        return array_shift($row);
    }


//////////////////////////////////////////////////////////////////////////////////////////////
    public function create_table_tb($table_name, $dataset_id, $columns)
    {

        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();

        $table = $table_name . "_" . $dataset_id;
        if ($this->check_table_exists($table) == 0) {


            $query = "";

            if (sizeof($columns) > 0) {

                $query = "create table $table (";
                $query .= " id INT NOT NULL AUTO_INCREMENT PRIMARY KEY  , ";

                for ($i = 0; $i < sizeof($columns); $i++) {

                    $validate_ = str_replace(" ", "_", $columns[$i]);
                    $validate_ = str_replace("\"", "", $validate_);
                    $validate_ = str_replace(":", "_", $validate_);
                    $validate_ = str_replace("-", "_", $validate_);
                    $validate_ = str_replace("KEY", "KEY_", $validate_);
                    $query .= "$validate_ text ";

                    if (($i + 1) != sizeof($columns)) {
                        $query .= ",";
                    }

                }
                $query .= " )";
            }

            return $mDatabaseQueryProcessor->setResultForQuery($query);

        } else {
            return 0;

        }

    }

///////////////////////////////////////////////////////////////////////////////////////////////

    public function insert_into_table_tb($table, $columns, $values)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();

        if (sizeof($columns) > 0) {
            $columns_ = "";
            $values_ = "";
            for ($i = 0; $i < sizeof($columns); $i++) {

                $columns_ .= $columns[$i];
                $values__ = $values[$i];
                $values_ .= "'$values__'";

                if (($i + 1) != sizeof($columns)) {
                    $columns_ .= ",";
                    $values_ .= ",";
                }

            }

        }

        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns_, $values_);
    }

//////////////////////////////////////////////////////////////////////////////////////////////

    public function insert_into_clients_users_tb($client_id, $username, $useremail, $userpassword, $role)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "clients_users_tb";
        $columns = "client_id, username, user_email, password, role";
        $values = "'$client_id', '$username', '$useremail', '$userpassword', '$role'";

        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

/////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////

    public function insert_into_input_reciept_temp_tb(
        $client_id, $user_id, $va_dataset_id, $va_id, $farmer_dataset_id, $farmer_id, $input_id, $input_type, $reciept_url)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "input_reciept_temp_tb";
        $columns = "client_id, user_id, va_dataset_id, va_id, farmer_dataset_id, farmer_id, input_id, input_type, reciept_url";
        $values = "'$client_id', '$user_id', '$va_dataset_id', '$va_id', '$farmer_dataset_id', '$farmer_id', '$input_id', '$input_type', '$reciept_url'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

/////////////////////////////////////////////////////////////////////////////////////////////


//

//////////////////////////////////////////////////////////////////////////////////////////////

    public function update_clients_users_tb($id, $username, $useremail, $userpassword, $role)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "clients_users_tb";
        $columns_values = "username='$username', user_email='$useremail', password='$userpassword', role='$role'";
        $where = "id=$id";
        return $util_obj->update_table($mDatabaseQueryProcessor, $table, $columns_values, $where);
    }


/////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////

    public function update_datasets_tb($id, $global_id)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "datasets_tb";
        $columns_values = "va_dataset_id='$id'";
        $where = "id=$global_id";
        return $util_obj->update_table($mDatabaseQueryProcessor, $table, $columns_values, $where);
    }

/////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////

    public function update_out_grower_threshold_tb($id, $iname, $itype, $uname, $upacre, $uprice, $cpu)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "out_grower_threshold_tb";
        $columns_values = "item='$iname', item_type='$itype', units='$uname', unit_per_acre='$upacre', unit_price='$uprice', commission_per_unit='$cpu'";
        $where = "id=$id";
        return $util_obj->update_table($mDatabaseQueryProcessor, $table, $columns_values, $where);
    }


/////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////

    public function delete_from_clients_users_tb($id)
    {

        $table = "clients_users_tb";

        $where = "id=$id";
        $result = $this->delete($table, $where);
    }

/////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////

    public function delete_from_out_grower_threshold_tb($id)
    {

        $table = "out_grower_threshold_tb";

        $where = "id=$id";
        $result = $this->delete($table, $where);
    }

/////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////

    public function insert_into_out_grower_threshold_tb($client_id, $item, $item_type, $units, $unit_per_acre, $unit_price, $commission_per_unit)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "out_grower_threshold_tb";
        $columns = "client_id, item, item_type, units, unit_per_acre, unit_price, commission_per_unit";
        $values = "'$client_id', '$item', '$item_type', '$units', '$unit_per_acre', '$unit_price', '$commission_per_unit'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

/////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////

    public function insert_into_clients_logs_tb($client_id, $user_id, $activity, $time_of_activity, $ip_address)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "clients_logs_tb";
        $columns = "client_id, user_id, activity, time_of_activity, ip_address";
        $values = "'$client_id', '$user_id', '$activity', '$time_of_activity', '$ip_address'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

/////////////////////////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////
    public function insert_into_total_acerage_tb($dataset_id, $ttl_gardens, $ttl_acerage)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "total_acerage_tb";
        $columns = "dataset_id, ttl_gardens, ttl_acerage";
        $values = "'$dataset_id', '$ttl_gardens', '$ttl_acerage'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }
///////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////
    public function select_from_clients_users_tb($id)
    {
        $table = "clients_users_tb";
        $columns = "*";
        $where = "id=$id";
        $user_data = $this->fetch_rows($table, $columns, $where);
        return $user_data;
    }
///////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////
    public function select_name_from_datasets_tb($id)
    {
        $table = "datasets_tb";
        $columns = "dataset_name";
        $where = "id='$id'";
        $dataset_name_data = $this->fetch_rows($table, $columns, $where);
        return $dataset_name_data;
    }
///////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////
    public function select_from_datasets_tb($id)
    {
        $table = "datasets_tb";
        $columns = "id,dataset_name,va_dataset_id";
        $where = "dataset_type='VA' AND client_id='$id'";
        $dataset_data = $this->fetch_rows($table, $columns, $where);
        return $dataset_data;
    }
///////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////
    public function select_from_out_grower_threshold_tb($id)
    {
        $table = "out_grower_threshold_tb";
        $columns = "*";
        $where = "id=$id";
        $item_data = $this->fetch_rows($table, $columns, $where);
        return $item_data;
    }
///////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
    public function insert_into_out_grower_input_tb($va_dataset_id,$dataset_id, $va_code, $meta_id, $item_id, $unit_price, $qty,$doc_date,$reciept_url){
        $util_obj= new Utilties();
        $mDatabaseQueryProcessor =new DatabaseQueryProcessor();
        $table="out_grower_input_tb";
        $columns="id,dataset_id, va_code, meta_id, item_id, unit_price, qty, doc_date ,reciept_url ";
        $values="'$va_dataset_id','$dataset_id', '$va_code', '$meta_id', '$item_id', '$unit_price', '$qty' , '$doc_date', '$reciept_url' ";
        return $util_obj->insert_into($mDatabaseQueryProcessor,$table,$columns, $values);
    }

////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
    public function insert_into_out_grower_cashinput_tb($dataset_id, $va_code, $meta_id, $amount, $cash_type, $acreage, $doc_date, $reciept_url)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "out_grower_cashinput_tb";
        $columns = "dataset_id, va_code, meta_id, amount, cash_type, acreage, doc_date, reciept_url";
        $values = "'$dataset_id', '$va_code', '$meta_id', '$amount', '$cash_type', '$acreage','$doc_date', '$reciept_url' ";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
    public function insert_into_out_grower_produce_tb($va_dataset_id,$dataset_id, $va_code, $meta_id, $qty, $commission_per_unit, $receipt, $payment_status,$unit_price,$doc_date){
        $util_obj= new Utilties();
        $mDatabaseQueryProcessor =new DatabaseQueryProcessor();
        $table="out_grower_produce_tb";
        $columns="id,dataset_id, va_code, meta_id, qty, commission_per_unit, receipt, payment_status ,unit_price ,doc_date";
        $values="'$va_dataset_id','$dataset_id', '$va_code', '$meta_id', '$qty', '$commission_per_unit', '$receipt', '$payment_status', '$unit_price', '$doc_date' ";
        return $util_obj->insert_into($mDatabaseQueryProcessor,$table,$columns, $values);
    }

///////////////////////////////////////////////////////////////////////////////


    public function insert_into_outgrower_input_reciept_tb($dataset_id, $va_code, $meta_id, $reciept)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "outgrower_input_reciept_tb";
        $columns = "dataset_id, va_code, meta_id, reciept";
        $values = "'$dataset_id', '$va_code', '$meta_id', '$reciept' ";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

////////////////////////////////////////////////////////////////////////////////
//`dataset_id`, `va_code`, `meta_id`, `qty`, `commission_per_unit`, `receipt`, `payment_status`
//////////////////////////////////////////////////////////////////////////////////////////
    public function insert_into_datasets_tb($name, $client_id, $type, $region, $from, $to, $va_dataset_id)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "datasets_tb";
        $columns = "dataset_name,client_id,dataset_type,region,start_date , end_date, va_dataset_id";
        $values = "'$name','$client_id','$type','$region','$from','$to', '$va_dataset_id'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function insert_into_client_accounts_tb($c_name, $c_address, $c_email, $c_value_chain, $c_password, $contact_name, $contact_number, $contact_email, $contact_loc)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "client_accounts_tb";
        $columns = "c_name,c_address,c_email,c_value_chain,c_password,contact_name,contact_number,contact_email,contact_loc";
        $values = "'$c_name','$c_address','$c_email','$c_value_chain','$c_password','$contact_name','$contact_number','$contact_email','$contact_loc'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function insert_into_admin_logs_tb($user_id, $login_time, $ip_address)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "admin_logs_tb";
        $columns = "user_id,login_time, ip_address";
        $values = "'$user_id','$login_time', '$ip_address'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }


////////////////////////////////////////////////////////////////////////////////////////
    public function insert_into_va_reciept_tb($client_id, $dataset_id, $va_id, $reciept_url)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "va_reciept_tb";
        $columns = "client_id, dataset_id, va_id, reciept_url";
        $values = "'$client_id', '$dataset_id', '$va_id', '$reciept_url'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }
////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
    public function insert_into_farmer_location($dataset_id, $column_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "farmer_location";
        $columns = "dataset_id,columns";
        $values = "'$dataset_id','$column_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function insert_into_general_questions($dataset_id, $column_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "general_questions";
        $columns = "dataset_id,columns";
        $values = "'$dataset_id','$column_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function insert_into_production_data($dataset_id, $enterprise, $column_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "production_data";
        $columns = "dataset_id,enterprise,columns";
        $values = "'$dataset_id','$enterprise','$column_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function insert_into_production_data_va($dataset_id, $column_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "va_production_data";
        $columns = "dataset_id,columns";
        $values = "'$dataset_id','$column_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }


    public function insert_into_va_farm_production_data($dataset_id, $column_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "va_farm_production_data";
        $columns = "dataset_id,columns";
        $values = "'$dataset_id','$column_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

//va_location
    public function insert_into_va_location($dataset_id, $column_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "va_location";
        $columns = "dataset_id,columns";
        $values = "'$dataset_id','$column_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }


    public function insert_into_bio_data($dataset_id, $columns_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "bio_data";
        $columns = "dataset_id,columns";
        $values = "'$dataset_id','$columns_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function insert_into_interview_particulars($dataset_id, $column_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "interview_particulars";
        $columns = "dataset_id,columns";
        $values = "'$dataset_id','$column_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function insert_into_info_on_other_enterprise($dataset_id, $column_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "info_on_other_enterprise";
        $columns = "dataset_id,columns";
        $values = "'$dataset_id','$column_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function insert_into_garden_gps($dataset_id, $column_data)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "garden_gps";
        $columns = "dataset_id,columns";
        $values = "'$dataset_id','$column_data'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function insert_into_soil_testing_results($dataset_id, $document_url, $farmers_id)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $table = "soil_testing_results";
        $columns = "dataset_id, document_url, farmers_id";
        $values = "'$dataset_id','$document_url','$farmers_id'";
        return $util_obj->insert_into($mDatabaseQueryProcessor, $table, $columns, $values);
    }

    public function get_count($table, $where)
    {
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $query = "SELECT COUNT(*) FROM " . $table . " WHERE " . $where;
        #do the query
        $mDatabaseQueryProcessor->setResultForQuery($query);
        $row = $mDatabaseQueryProcessor->getResultArray();
        return array_shift($row);
    }

    public function get_distinct_count($table,$column, $where)
    {
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $query = "SELECT " .$column. " FROM " . $table . " WHERE " . $where;
        #do the query
        $mDatabaseQueryProcessor->setResultForQuery($query);
        $row = $mDatabaseQueryProcessor->getResultArray();
        return array_shift($row);
    }

    public function check_table_exists($table)
    {
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        $query = "DESCRIBE $table";
        $result = $mDatabaseQueryProcessor->setResultForQuery($query);
        if ($result) {
            return 2017;
        } else {
            return 0;
        }

//	$mDatabaseQueryProcessor = new DatabaseQueryProcessor();
//	$db=$mDatabaseQueryProcessor->getDataBaseName();
//    $query="SELECT count(*) FROM information_schema.TABLES
//             WHERE (TABLE_SCHEMA = '$db') AND (TABLE_NAME = '$table')";

        #do the query
//	 $mDatabaseQueryProcessor ->setResultForQuery($query);
//	$row=$mDatabaseQueryProcessor->getResultArray();
//    return array_shift($row);
    }

    public function insertColumnsIntoTractTables($dataset_id, $handle)
    {

        $data = fgetcsv($handle, 0, ";", " ");
        $columns = sizeof($data);
        $column = "";
        if ($columns > 0) {

            for ($i = 0; $i < $columns; $i++) {
                $validate_ = str_replace('"', "", $data[$i]);
                $validate_ = str_replace(" ", "_", $validate_);
                $validate_ = str_replace("\"", " ", $validate_);
                $column = str_replace(":", "_", $validate_);
                $column = str_replace("-", "_", $column);

                $intial_data = str_replace('"', "", $data[$i]);
                $matches = explode(":", $intial_data);
                // count number of segments
                $numMatches = sizeof($matches);

                // extract substring preceding first match
                if ($matches[0] == "biodata" && $matches[1] != "farmer_location") {
                    $this->insert_into_bio_data($dataset_id, $column);
                }

                if ($matches[0] == "biodata" && $matches[1] == "farmer_location") {
                    $this->insert_into_farmer_location($dataset_id, $column);
                }

                if ($matches[0] == "general_questions") {
                    $this->insert_into_general_questions($dataset_id, $column);
                }

                if($matches[0] == "production_data") {
                    $this->insert_into_garden_gps($dataset_id, $column);
                }

//                if($matches[0] == "production_data" && $matches[1] == "garden_gps") {
//                    $this->insert_into_garden_gps($dataset_id, $column);
//                }

                $matches2 = explode("_on_other_", $intial_data);
                if ($matches2[0] == "information") {
                    $this->insert_into_info_on_other_enterprise($dataset_id, $column);
                }

                if ($matches[0] == "interview_particulars") {
                    $this->insert_into_interview_particulars($dataset_id, $column);
                }
                $matches3 = null;

                if (strpos($data[$i], '_production_data:') !== false) {
                    $matches3 = explode("_", $data[$i]);
                    $enterprise = str_replace('"', "", $matches3[0]);
                    $this->insert_into_production_data($dataset_id, $enterprise, $column);
                }


                /////////////
                $matches_va = explode("-", $intial_data);

                if ($matches_va[0] == "biodata") {
                    $this->insert_into_bio_data($dataset_id, $column);
                }

                if ($matches_va[0] == "va_location") {
                    $this->insert_into_va_location($dataset_id, $column);//($dataset_id,$column);
                }

                if ($matches_va[0] == "production_data") {
                    $this->insert_into_production_data_va($dataset_id, $column);//insert_into_bio_data($dataset_id,$column);
                }

                if ($matches_va[0] == "farm_production_data") {
                    $this->insert_into_va_farm_production_data($dataset_id, $column);//($dataset_id,$column);
                }
            }
        }
        return $matches;
    }

    public function getTTLAcerage_Gardens($table, $id)
    {
        $util_obj = new Utilties();
        $total_acerage_bundle = array();
        $total_acerage_ = 0;
        $total_gardens_ = 0;
        $rows = $this->fetch_rows($table, "*", 1);
        if (sizeof($rows) > 0) {

            foreach ($rows as $row) {
                $real_id = $row['id'];

                $uuid = $util_obj->remove_apostrophes($row['meta_instanceID']);

                $latitudes = array();
                $longitudes = array();
                $acares = array();

                $gardens_table = "garden_" . $id;

                if ($this->check_table_exists($gardens_table) > 0) {

                    $gardens = $this->fetch_rows($gardens_table, " DISTINCT PARENT_KEY_ ", " PARENT_KEY_ LIKE '$uuid%' ");

                    if (sizeof($gardens) < 0) {

                    } else {
                        $z = 1;
                        foreach ($gardens as $garden) {
                            $key = $uuid . "/gardens[$z]";
                            $data_rows = $this->fetch_rows($gardens_table, "garden_gps_point_Latitude,garden_gps_point_Longitude", " PARENT_KEY_ ='$key'");
                            if (sizeof($data_rows) == 0) {
                                $data_rows = $this->fetch_rows($gardens_table, "garden_gps_point_Latitude,garden_gps_point_Longitude", " PARENT_KEY_ ='$uuid'");
                            }
                            foreach ($data_rows as $row_) {

                                array_push($longitudes, $row_['garden_gps_point_Longitude']);
                                array_push($latitudes, $row_['garden_gps_point_Latitude']);

                            }
                            $acerage = $util_obj->get_acerage_from_geo($latitudes, $longitudes);
                            array_push($acares, $acerage);
                            $z++;
                        }
                        $total_gardens = sizeof($gardens);
                        $average = 0;
                        if ($total_gardens != 0) {
                            $total_acerage_ = $total_acerage_ + array_sum($acares);
                            $total_gardens_ = $total_gardens_ + $total_gardens;
                            //$average=round(array_sum($acares)/$total_gardens,3);
                        }


                    }

                }


            }

        }


        $total_acerage_bundle[0] = $total_acerage_;
        $total_acerage_bundle[1] = $total_gardens_;
        unset($util_obj);

        return $total_acerage_bundle;
    }

    public function delete($table, $where)
    {
        $query = "DELETE FROM " . $table . " WHERE " . $where;
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        return $mDatabaseQueryProcessor->setResultForQuery($query);
    }

    public function fetch_rows($table, $columns, $where)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        return $util_obj->get_result_array($mDatabaseQueryProcessor, $table, $columns, $where);
    }

    public function fetch_farmer_rows($table, $columns)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        return $util_obj->get_farmer_result_array($mDatabaseQueryProcessor, $table, $columns);
    }

    public function update($table, $columns_value, $where)
    {
        $util_obj = new Utilties();
        $mDatabaseQueryProcessor = new DatabaseQueryProcessor();
        return $util_obj->update_table($mDatabaseQueryProcessor, $table, $columns_value, $where);
    }

}


?>