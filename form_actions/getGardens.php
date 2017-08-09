<?php
 #includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/utility_class.php";

$util_obj= new Utilties();
$db= new DatabaseQueryProcessor();
$mCrudFunctions = new CrudFunctions();
 //$_POST['dataset_id']=5;
 //$_POST['uuid']="uuid:d5d2117b-e148-4fdc-811f-f8ecb21a7e44";
if(isset($_POST['dataset_id'])&&isset($_POST['uuid'])){
    $dataset_id = $_POST['dataset_id'];
    $uuid = $_POST['uuid'];
    $gardens_table = "garden_". $dataset_id;

    if ($mCrudFunctions->check_table_exists($gardens_table) > 0) {

        $gardens = $mCrudFunctions->fetch_rows($gardens_table, " DISTINCT PARENT_KEY_ ", " PARENT_KEY_ LIKE '$uuid%' ");

        if (sizeof($gardens) > 0) {
            echo "<table class=\"light-table\">
                  <thead><tr><th>Garden</th><th>Acreage</th></tr></thead>
                   <tbody>";

            $z = 1;
            $total = 0;
            foreach ($gardens as $garden) {
                $key = $uuid . "/gardens[$z]";
                $data_rows = $mCrudFunctions->fetch_rows($gardens_table, "garden_gps_point_Latitude,garden_gps_point_Longitude", " PARENT_KEY_ ='$key'");

                if (sizeof($data_rows) == 0) {
                    $data_rows = $mCrudFunctions->fetch_rows($gardens_table, "garden_gps_point_Latitude,garden_gps_point_Longitude", " PARENT_KEY_ ='$uuid'");
                }
                $latitudes = array();
                $longitudes = array();
                foreach ($data_rows as $row_) {

                    array_push($longitudes, $row_['garden_gps_point_Longitude']);
                    array_push($latitudes, $row_['garden_gps_point_Latitude']);

                }
                $acerage = $util_obj->get_acerage_from_geo($latitudes, $longitudes);
                $total = $total + $acerage;
                echo "
<tr>
	<td>$z</td>
	<td>$acerage</td>
</tr>";
                $z++;
            }
            echo "</tbody>
</table>  
<input id=\"total_acerage\" type=\"hidden\" value=\"$total\"/>

";
        }
    }

}
?>