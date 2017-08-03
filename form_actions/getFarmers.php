<style type="text/css">
    th, thead {
        height: 2em !important;
        background: teal;
        color: #fff !important;
    }
    /*td > a > .btn .btn-primary {*/
    /*background: #03a9f4;*/
    /*}*/
</style>
<?php
error_reporting(0);

#includes
session_start();
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/pagination_class.php";
//require_once "http://us2.php.net/manual/en/ref.image.php";

$mCrudFunctions = new CrudFunctions();

if (isset($_POST['id']) && $_POST['id'] != "") {
    $class = 'table table-bordered table-hover';

    $page = !empty($_POST['page']) ? (int)$_POST['page'] : 1;

    $per_page = 16;
    $id = $_POST['id'];
    $dataset_get_name = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id ");
    $dt_st_name = $dataset_get_name[0]['dataset_name'];
    $total_count_default = $mCrudFunctions->get_count("dataset_" . $id, 1);

    $total_count = !empty($_POST['total_count']) ? (int)$_POST['total_count'] : $total_count_default;
    $pagination_obj = new Pagination($page, $per_page, $total_count);
    $offset = $pagination_obj->getOffset();
    $gender = $_POST['gender'];
    $va = $_POST['va'];
    $va = str_replace("(", "-", $va);
    $va = str_replace(")", "", $va);
//$string="hyderabad-india";
    $strings = explode('-', $va);
//echo $strings[0]; // outputs: hyderabad
//echo "<br>"; // new line
    $va = strtolower($strings[1]); // outputs: india

    if (isset($_POST['district']) && $_POST['district'] != "") {

        switch ($_POST['district']) {
            case  "all" :

////////////////////////////////////////////////////////district all starts
                if ($_POST['sel_production_id'] == "all") {
                    if ($_POST['gender'] == "all") {
                        ///v2 code interview_particulars_va_code
                        if ($_POST['va'] == "all") {
//                            echo "Dataset: $dt_st_name";
                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Number of Gardens</th><th>Acreage</th><th>Soil Testing Results</th><th>Details</th></tr></thead>";
                            output($id, " 1 ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                            echo "</table>";

                        } else {
//                            echo "Dataset: $dt_st_name";
                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Number of Gardens</th> <th>Acreage</th><th>Soil Testing Results</th><th>Details</th></tr></thead>";
                            output($id, " lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset  ");
                            echo "</table>";
                        }


                    } else {
                        //
                        //echo $gender;

                        if ($_POST['va'] == "all") {
                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                            output($id, " lower(biodata_farmer_gender) = '$gender' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                            echo "</table>";
                        } else {
                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                            output($id, " lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND lower(biodata_farmer_gender) = '$gender' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                            echo "</table>";
                        }


                    }

                } else {


                    $string = $_POST['sel_production_id'];


                    if (strpos($string, "productionyes") === false) {
                        if (strpos($string, "productionno") === false) {

                            if (strpos($string, "generalyes") === false) {

                                if (strpos($string, "generalno") === false) {
                                } else {
                                    $p_id = str_replace("generalno", "", $string);
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id'  ");
                                    $column = $row[0]['columns'];

                                    if ($_POST['gender'] == "all") {


                                        ///v2 code interview_particulars_va_code
                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " " . $column . " LIKE 'no' ORDER BY biodata_farmer_name  LIMIT $per_page OFFSET $offset");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset  ");
                                            echo "</table>";
                                        }

                                    } else {
                                        //

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        }

                                    }

                                }

                            } else {
                                $p_id = str_replace("generalyes", "", $string);
                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];


                                if ($_POST['gender'] == "all") {


                                    ////lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {

                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, "  " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {

                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }

                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";

                                    }


                                }

                            }

                        } else {
                            $p_id = str_replace("productionno", "", $string);
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];

                            if ($_POST['gender'] == "all") {

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, "  " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                }

                            } else {


                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";

                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                }


                            }


                        }
                    } else {
                        $p_id = str_replace("productionyes", "", $string);
                        //echo $string;
                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id'  ");
                        $column = $row[0]['columns'];
                        // echo $column;
                        // output($id,"  ".$column." LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                        //echo $per_page;
                        if ($_POST['gender'] == "all") {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, "  " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                echo "</table>";
                            } else {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                echo "</table>";
                            }


                        } else {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                            if ($_POST['va'] == "all") {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                echo "</table>";

                            } else {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                echo "</table>";

                            }

                        }

                    }


                }

/////////////////////////////////////////////////////////////////////district all ends
                break;
            default:

                if ($_POST['subcounty'] == "all") {
                    $district = $_POST['district'];

///////////////////////////////////////////////////////////////////// subcounty all starts
                    if ($_POST['sel_production_id'] == "all") {


                        if ($_POST['gender'] == "all") {
                            //echo $per_page;
                            //$snt= filter_var($district, FILTER_SANITIZE_STRING);
                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' ORDER BY biodata_farmer_name  LIMIT $per_page OFFSET $offset");
                                echo "</table>";

                            } else {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset");
                                echo "</table>";
                            }


                        } else {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                echo "<table>" . output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ") . "</table>";
                                echo "</table>";
                            } else {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                echo "</table>";
                            }

                        }

                    } else {

                        $mCrudFunctions = new CrudFunctions();
                        $string = $_POST['sel_production_id'];


                        if (strpos($string, "productionyes") === false) {
                            if (strpos($string, "productionno") === false) {
                                if (strpos($string, "generalyes") === false) {
                                    if (strpos($string, "generalno") === false) {
                                    } else {
                                        $p_id = str_replace("generalno", "", $string);
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];


                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            } else {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            }


                                        } else {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            } else {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            }


                                        }

                                    }

                                } else {
                                    $p_id = str_replace("generalyes", "", $string);
                                    $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];


                                    if ($_POST['gender'] == "all") {
                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            echo "<table>" . output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        }


                                    } else {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        }


                                    }


                                }

                            } else {
                                $p_id = str_replace("productionno", "", $string);
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];


                                if ($_POST['gender'] == "all") {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }


                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";

                                    } else {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";

                                    }


                                }


                            }
                        } else {
                            $p_id = str_replace("productionyes", "", $string);
                            //echo $string;
                            $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                            $column = $row[0]['columns'];
                            //echo $column;

                            if ($_POST['gender'] == "all") {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";

                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";

                                }


                            } else {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                }

                            }


                        }

                    }
//////////////////////////////////////////////////////////////////// subcounty all ends
                } else {
                    $subcounty = $_POST['subcounty'];
                    $district = $_POST['district'];
                    if ($_POST['parish'] == "all") {

                        ////////////////////////////////////////////////////////////////////// parish all starts
                        if ($_POST['sel_production_id'] == "all") {

                            if ($_POST['gender'] == "all") {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";

                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                }

                            } else {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                }


                            }


                        } else {

                            $mCrudFunctions = new CrudFunctions();
                            $string = $_POST['sel_production_id'];


                            if (strpos($string, "productionyes") === false) {
                                if (strpos($string, "productionno") === false) {
                                    if (strpos($string, "generalyes") === false) {
                                        if (strpos($string, "generalno") === false) {
                                        } else {
                                            $p_id = str_replace("generalno", "", $string);
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];


                                            if ($_POST['gender'] == "all") {
                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                } else {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                }


                                            } else {
                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                } else {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    echo "<table>" . output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ") . "</table>";
                                                    echo "</table>";
                                                }


                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("generalyes", "", $string);
                                        $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];


                                        if ($_POST['gender'] == "all") {
                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            } else {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            }


                                        } else {
                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                            if ($_POST['va'] == "all") {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            } else {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            }


                                        }

                                    }

                                } else {
                                    $p_id = str_replace("productionno", "", $string);
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];


                                    if ($_POST['gender'] == "all") {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        }

                                    } else {
                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset  ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset  ");
                                            echo "</table>";
                                        }


                                    }

                                }
                            } else {
                                $p_id = str_replace("productionyes", "", $string);
                                //echo $string;
                                $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                $column = $row[0]['columns'];
                                // echo $column;

                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }


                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";

                                    } else {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";

                                    }


                                }

                            }

                        }
///////////////////////////////////////////////////////////////////////////////// parish all ends
                    } else {
                        $subcounty = $_POST['subcounty'];
                        $district = $_POST['district'];
                        $parish = $_POST['parish'];

                        if ($_POST['village'] == "all") {

                            //////////////////////////////////////////////////////////////////////////// village all starts......onmonday
                            if ($_POST['sel_production_id'] == "all") {


                                if ($_POST['gender'] == "all") {

                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }


                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset  ");
                                        echo "</table>";

                                    } else {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset  ");
                                        echo "</table>";

                                    }


                                }
                            } else {

                                $mCrudFunctions = new CrudFunctions();
                                $string = $_POST['sel_production_id'];


                                if (strpos($string, "productionyes") === false) {
                                    if (strpos($string, "productionno") === false) {
                                        if (strpos($string, "generalyes") === false) {
                                            if (strpos($string, "generalno") === false) {
                                            } else {
                                                $p_id = str_replace("generalno", "", $string);
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];


                                                if ($_POST['gender'] == "all") {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                    if ($_POST['va'] == "all") {
                                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";
                                                    } else {
                                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    }


                                                } else {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                    if ($_POST['va'] == "all") {
                                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    } else {
                                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " ORDER BY biodata_farmer_name LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    }


                                                }

                                            }
                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];


                                            if ($_POST['gender'] == "all") {
                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                } else {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                }

                                            } else {
                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";

                                                } else {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";

                                                }


                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];


                                        if ($_POST['gender'] == "all") {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            } else {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            }

                                        } else {
                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                            if ($_POST['va'] == "all") {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            } else {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            }

                                        }


                                    }
                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    // echo $string;
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    //echo $column;

                                    if ($_POST['gender'] == "all") {
                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        }

                                    } else {
                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";

                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";

                                        }


                                    }

                                }

                            }

                            /////////////////////////////////////////////////////////////////////////////
                        } else {
                            $subcounty = $_POST['subcounty'];
                            $district = $_POST['district'];
                            $parish = $_POST['parish'];
                            $village = $_POST['village'];


                            if ($_POST['sel_production_id'] == "all") {


                                if ($_POST['gender'] == "all") {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }


                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";

                                    }


                                }


                            } else {

                                $mCrudFunctions = new CrudFunctions();
                                $string = $_POST['sel_production_id'];

                                if (strpos($string, "productionyes") === false) {
                                    if (strpos($string, "productionno") === false) {
                                        if (strpos($string, "generalyes") === false) {
                                            if (strpos($string, "generalno") === false) {
                                            } else {
                                                $p_id = str_replace("generalno", "", $string);
                                                $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                                $column = $row[0]['columns'];

                                                if ($_POST['gender'] == "all") {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                    if ($_POST['va'] == "all") {
                                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    } else {
                                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    }


                                                } else {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                                    if ($_POST['va'] == "all") {
                                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    } else {
                                                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";

                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    }

                                                }

                                            }
                                        } else {
                                            $p_id = str_replace("generalyes", "", $string);
                                            $row = $mCrudFunctions->fetch_rows("general_questions", "columns", " id='$p_id' ");
                                            $column = $row[0]['columns'];


                                            if ($_POST['gender'] == "all") {
                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                } else {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                }


                                            } else {

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";

                                                } else {
                                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";

                                                }


                                            }

                                        }

                                    } else {
                                        $p_id = str_replace("productionno", "", $string);
                                        $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                        $column = $row[0]['columns'];


                                        if ($_POST['gender'] == "all") {
                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            } else {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            }


                                        } else {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            } else {
                                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            }


                                        }

                                    }
                                } else {
                                    $p_id = str_replace("productionyes", "", $string);
                                    // echo $string;
                                    $row = $mCrudFunctions->fetch_rows("production_data", "columns", " id='$p_id' ");
                                    $column = $row[0]['columns'];
                                    //echo $column;


                                    if ($_POST['gender'] == "all") {
                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";

                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";

                                        }


                                    } else {
                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th>  <th>Number of Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY biodata_farmer_name LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";

                                        }


                                    }

                                }

                            }

                        }

                    }

                }

                break;
        }

    }


}

function output($id, $where)
{
    $util_obj = new Utilties();
    $db = new DatabaseQueryProcessor();
    $mCrudFunctions = new CrudFunctions();
    $dataset_ = $util_obj->encrypt_decrypt("encrypt", $id);
    $dataset = $mCrudFunctions->fetch_rows("datasets_tb", "*", " id =$id ");
    $dataset_name = $dataset[0]["dataset_name"];
    $dataset_type = $dataset[0]["dataset_type"];

    $table = "dataset_" . $id;
    $columns = "* , (DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) as actualAgeInYrs , floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) as ageInYrs ";

    $age_min = !empty($_POST['age_min']) ? (int)$_POST['age_min'] : 0;
    $age = !empty($_POST['age']) ? (int)$_POST['age'] : 0;
    $age_max = !empty($_POST['age_max']) ? (int)$_POST['age_max'] : 0;

    if ($age != "" && $age != 0) {
        $where = "floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  AND " . $where;
    }

    if ($age_min <= $age_max && $age_max != 0) {
        $where = " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  AND " . $where;
    }

    if ($age_min > 0 && $age_max == 0) {
        $where = " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  AND " . $where;
    }

    $rows = $mCrudFunctions->fetch_rows($table, $columns, $where);

    /** this will use two variables i.e.
     * 1. to capture .docx and
     * 2. to capture .pdf  **/
//    $soil_test_results_docx = array();  $soil_test_results_pdf = array();
//    $soil_test_results  = $mCrudFunctions->fetch_rows("soil_testing_results", "document_url", "dataset_id = $id");
    $soil_test_results_docx = $mCrudFunctions->fetch_rows("soil_testing_results", "document_url", "dataset_id = $id AND document_url LIKE '%docx'");
    $soil_test_results_pdf = $mCrudFunctions->fetch_rows("soil_testing_results", "document_url", "dataset_id = $id AND document_url LIKE '%pdf'");

    if ($dataset_type == "Farmer") {
        if (sizeof($rows) == 0) {
            echo "<p style=\"font-size:1.4em; width:100%; text-align:center; color:#999; margin-top:100px\">No Data</p>";
        } else {            $counter = 1;
            foreach ($rows as $row) {
                $real_id = $row['id'];
                $real_id_ = $row['id'];

                $real_id = $util_obj->encrypt_decrypt("encrypt", $real_id);
                $name = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_farmer_name']));
                $gender = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_farmer_gender']));
                $dob = $util_obj->remove_apostrophes($row['biodata_farmer_dob']);
                $picture = $util_obj->remove_apostrophes($row['biodata_farmer_picture']);
                $district = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['biodata_farmer_location_farmer_district']));
                $phone_number = $util_obj->remove_apostrophes($row['biodata_farmer_phone_number']);

                $uuid = $util_obj->remove_apostrophes($row['meta_instanceID']);
                $age_ = $util_obj->getAge($dob, "Africa/Nairobi");
                $name = strlen($name) <= 15 ? $name : substr($name, 0, 14) . "...";

////////////////////////////////////////////////////////////////////////gfhsfaghjfasj
                if ($_SESSION['client_id'] == 1) {
                    echo "<tr>";
                    echo "
                        <td>$counter</td>                          
                  
                  <td style='line-height:12pt; align:center'>$name</td>
                  <td style='line-height:12pt; align:center'>$age_</td>

                    <td>" . substr($gender, 0, 1) . "</td>";
//                    <td style='line-height:12pt; align:center'>$district</td>

                    $acares = array();

                    $gardens_table = "garden_" . $id;

                    if ($mCrudFunctions->check_table_exists($gardens_table) > 0) {
//echo $uuid;

                        $gardens = $mCrudFunctions->fetch_rows($gardens_table, " DISTINCT PARENT_KEY_ ", " PARENT_KEY_ LIKE '$uuid%' ");

                        if (sizeof($gardens) < 0) {
//echo"<p>Number of Gardens: $total_gardens ,AverageAcerage: $average </p><br/>";

                        } else {
                            $z = 1;
                            foreach ($gardens as $garden) {
                                $key = $uuid . "/Number of Gardens[$z]";
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
                                array_push($acares, $acerage);
                                $z++;
                            }
                            $total_gardens = sizeof($gardens);
                            $average = 0;
                            if ($total_gardens != 0) {
                                $average = round(array_sum($acares)/*$total_gardens*/, 2);
                            }

//                            $_gardens = $total_gardens == 1 ? " Garden" : " Number of Gardens";
                            echo "<td>$total_gardens</td>
                                    <td> $average</td>";
                        }

                    } //<i class=\"fa fa-navicon fa-1x\" aria-hidden=\"true\"></i>
                    echo " 
 <td>    
  <a class='btn btn-success' href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\" style=\"color:#FFFFFF; padding: 8px; width:50%; margin: 0;\">View Details &raquo;</a>
 </td>";
                    echo "
      </div>
    </div>
   </div>
 </div> </a>";

                    echo "</tr>";
                } else {
                    echo "<tr>
                           <td>$counter</td>                   
                           <td>$name</td> <td>$age_ yrs</td>  
                           <td>" . substr($gender, 0, 1) . "</td>                          
                          ";

                    $acares = array();
                    $gardens_table = "garden_" . $id;

                    if ($mCrudFunctions->check_table_exists($gardens_table) > 0) {
//echo $uuid;

                        $gardens = $mCrudFunctions->fetch_rows($gardens_table, " DISTINCT PARENT_KEY_ ", " PARENT_KEY_ LIKE '$uuid%' ");

                        if (sizeof($gardens) < 0) {
//echo"<p>Number of Gardens: $total_gardens ,AverageAcerage: $average </p><br/>";

                        } else {
                            $z = 1;
                            foreach ($gardens as $garden) {
                                $key = $uuid . "/Number of Gardens[$z]";
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
                                array_push($acares, $acerage);
                                $z++;
                            }
                            $total_gardens = sizeof($gardens);
                            $average = 0;
                            if ($total_gardens != 0) {
                                $average = round(array_sum($acares)/*$total_gardens*/, 2);
                            }

//                            $_gardens = $total_gardens == 1 ? " Garden" : " Number of Gardens";
                            echo "<td><h6>$total_gardens</h6></td>
                                    <td style=\"color:#888\"> $average</td>";
                        }


                    } //<i class=\"fa fa-navicon fa-1x\" aria-hidden=\"true\"></i>
                    $count = $counter - 1;
                    $docx = $util_obj->remove_apostrophes($soil_test_results_docx[$count]['document_url']);
                    $pdf = $util_obj->remove_apostrophes($soil_test_results_pdf[$count]['document_url']);

//                    print_r($pdf);
                    echo "
  <div class='row'>
    <!--  <td class=\"col-sm-4\">  -->
    <td>  
        <!--   <a class='btn btn-primary' href='../uploads/ANAKA%20SOIL%20RESULTS.pdf' style=\"background: #03a9f4; color:#FFFFFF; padding: 8px; width:50%; margin: 0;\">View Results &raquo;</a> -->
        <div class='btn-group'>
            <a class='dropdown-toggle btn btn-default' data-toggle='dropdown' aria-haspopup='true' style=\"background: #03a9f4; color: white; padding: 10px; width:100%; margin: 0; \" > <i class='fa fa-ellipsis-v'></i> Results </a>
            <ul class='dropdown-menu'>
                <li><a href=\"$pdf\"> View Results </a></li>
                <li><a href=\"$docx\"> Download Results </a></li>
            </ul>
        </div>
    </td>
    <td>  
        <a class='btn btn-success' href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\" style=\"color:#FFFFFF; padding: 8px; width:50%; margin: 0;\">View Details &raquo;</a>
    </td>
  </div>";

                    echo "</tr>";
                }
                ///////////////////////////////////////////////////////////////////////////////////////

                $counter++;
            }
        }

        if ($dataset_type == "VA") {
            if (sizeof($rows) == 0) {
                echo "<p style=\"font-size:2em; text-align:center; color:#777; margin-top:100px\">No Data</p>";
            } else {
                foreach ($rows as $row) {
                    $real_id = $row['id'];

                    $real_id = $util_obj->encrypt_decrypt("encrypt", $real_id);
                    $name = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['name']));
                    $gender = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['gender']));
                    $dob = '';//$row['biodata_farmer_dob'];
                    $picture = $util_obj->remove_apostrophes($row['picture']);
                    $district = '';//$row['biodata_farmer_location_farmer_district'];
                    $phone_number = $util_obj->remove_apostrophes($row['va_phonenumber']);

                    echo "<div class=\"col-sm-12 col-md-4 col-lg-3\">

                          <div class=\"thumbnail card\" id=\"\">
                          <div class=\"image-box\" style=\"background-image:url('$picture');
                          background-color:#e9e9e9;
                          background-position:center;
                          background-repeat:no-repeat;
                          background-size:100%\">

                           <span class=\"gender\">" . substr($gender, 0, 1) . "</span>
                           </div>
                          <div class=\"caption\" style=\"margin:0\">
                          <p>$name</p><br/>



 <div class=\"row\">
    <div class=\"col-sm-4\">
  <p style=\"margin-top:10px\"><a  href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\" class=\" btn\" style=\"color:#F57C00; margin:0; width:100%;\">View Details &raquo;</a></p>
    </div>
     <div class=\"col-sm-4 col-sm-offset-3\">
    <p style=\"margin-top:10px\" style=\"color:#F57C00; margin:0; width:100%;\">
        <div class=\"dropdown\">
        <a href=\"#\" data-toggle=\"dropdown\" class=\"dropdown-toggle\">
        <i class=\"fa fa-navicon fa-1x\" aria-hidden=\"true\"></i>
        </a>
            <ul class=\"dropdown-menu\">
            <li><a href=\"#cash\" data-toggle=\"modal\" data-target=\"#cash\">Record  Extra Cash</a></li>
            <li><a href=\"#tractor\" data-toggle=\"modal\" data-target=\"#tractor\">Record Tractor Money</a></li>
            <li><a href=\"#produce\" data-toggle=\"modal\" data-target=\"#produce\">Record Produce Supplied</a></li>
          </ul>
        </div>
    </p>
    </div>
    </div>
      </div>
    </div>
  </div>";

                }
            }
        }

    }
}

?>


