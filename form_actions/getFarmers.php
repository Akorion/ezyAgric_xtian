<?php
#includes
session_start();
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/pagination_class.php";
//require_once "http://us2.php.net/manual/en/ref.image.php";

$mCrudFunctions = new CrudFunctions();


if (isset($_POST['id']) && $_POST['id'] != "") {

    $page = !empty($_POST['page']) ? (int)$_POST['page'] : 1;

    $per_page = 16;
    $id = $_POST['id'];
    $total_count_default = $mCrudFunctions->get_count("dataset_" . $id, 1);

    $total_count = !empty($_POST['total_count']) ? (int)$_POST['total_count'] : $total_count_default;
    $pagination_obj = new Pagination($page, $per_page, $total_count);
    $offset = $pagination_obj->getOffset();
    $gender = $_POST['gender'];


    $va = $_POST['va'];;
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
                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                            output($id, " 1 LIMIT $per_page OFFSET $offset ");
                            echo "</table>";

                        } else {
                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                            output($id, " lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset  ");
                            echo "</table>";
                        }


                    } else {
                        //
                        //echo $gender;

                        if ($_POST['va'] == "all") {
                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                            output($id, " lower(biodata_farmer_gender) = '$gender' LIMIT $per_page OFFSET $offset ");
                            echo "</table>";
                        } else {
                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                            output($id, " lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' AND lower(biodata_farmer_gender) = '$gender' LIMIT $per_page OFFSET $offset ");
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
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " " . $column . " LIKE 'no'  LIMIT $per_page OFFSET $offset");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset  ");
                                            echo "</table>";
                                        }

                                    } else {
                                        //

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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

                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, "  " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {

                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }

                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, "  " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                } else {
                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, "  " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                }

                            } else {


                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";

                                } else {
                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, "  " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                echo "</table>";
                            } else {
                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, "  " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                echo "</table>";
                            }


                        } else {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                            if ($_POST['va'] == "all") {
                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                echo "</table>";

                            } else {
                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, " lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  LIMIT $per_page OFFSET $offset");
                                echo "</table>";

                            } else {
                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, "  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset");
                                echo "</table>";
                            }


                        } else {

                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                            if ($_POST['va'] == "all") {
                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                echo "<table>" . output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' LIMIT $per_page OFFSET $offset ") . "</table>";
                                echo "</table>";
                            } else {
                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            } else {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            }


                                        } else {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            } else {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='table table-hover'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr>";
                                            echo "<table>" . output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        }


                                    } else {

                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='table table-hover'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='table table-hover'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }


                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";

                                    } else {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";

                                } else {
                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district'  AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";

                                }


                            } else {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='table table-hover'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                } else {
                                    echo "<table class='table table-hover'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";

                                } else {
                                    echo "<table class='table table-hover'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                }

                            } else {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                } else {
                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                } else {
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                }


                                            } else {
                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                } else {
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    echo "<table>" . output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ") . "</table>";
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
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            } else {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            }


                                        } else {
                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                            if ($_POST['va'] == "all") {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            } else {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        }

                                    } else {
                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset  ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty'  AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset  ");
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
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='table table-hover'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }


                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                    if ($_POST['va'] == "all") {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";

                                    } else {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND lower(biodata_farmer_gender) = '$gender' AND " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }


                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' LIMIT $per_page OFFSET $offset  ");
                                        echo "</table>";

                                    } else {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND biodata_farmer_location_farmer_parish LIKE '%$parish%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset  ");
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
                                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";
                                                    } else {
                                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    }


                                                } else {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                    if ($_POST['va'] == "all") {
                                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    } else {
                                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                } else {
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                }

                                            } else {
                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";

                                                } else {
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender'  AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            } else {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            }

                                        } else {
                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                            if ($_POST['va'] == "all") {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            } else {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        }

                                    } else {
                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";

                                        } else {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    }


                                } else {
                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                    if ($_POST['va'] == "all") {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'  LIMIT $per_page OFFSET $offset ");
                                        echo "</table>";
                                    } else {
                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    } else {
                                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    }


                                                } else {

                                                    //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'


                                                    if ($_POST['va'] == "all") {
                                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                        echo "</table>";

                                                    } else {
                                                        echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";

                                                        output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender'  AND " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                } else {
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";
                                                }


                                            } else {

                                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                                if ($_POST['va'] == "all") {
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                                    echo "</table>";

                                                } else {
                                                    echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                    output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village'  AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'no' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";

                                            } else {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'no' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            }


                                        } else {

                                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                            if ($_POST['va'] == "all") {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no'  LIMIT $per_page OFFSET $offset ");
                                                echo "</table>";
                                            } else {
                                                echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                                output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'no'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";

                                        } else {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";

                                        }


                                    } else {
                                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                        if ($_POST['va'] == "all") {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' LIMIT $per_page OFFSET $offset ");
                                            echo "</table>";
                                        } else {
                                            echo "<table class='table table-hover'><thead class='bg bg-success'><tr><th>Gender</th> <th>Name</th> <th>Age</th> <th>Gardens</th> <th>Acreage</th> <th>Details</th></tr></thead>";
                                            output($id, " TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_district) LIKE '$district' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM biodata_farmer_location_farmer_village) LIKE '$village' AND lower(biodata_farmer_gender) = '$gender' AND   " . $column . " LIKE 'Yes' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' LIMIT $per_page OFFSET $offset ");
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
//$where =$where ." ORDER BY ageInYrs ASC";
//floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)
    if ($age != "" && $age != 0) {
        $where = "floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  AND " . $where;
    }

    if ($age_min <= $age_max && $age_max != 0) {
        $where = " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  AND " . $where;
    }

    if ($age_min > 0 && $age_max == 0) {
        $where = " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  AND " . $where;
    }

//global $age=$_POST['age'];//
//global $age_max=$_POST['age_max'];//

    $rows = $mCrudFunctions->fetch_rows($table, $columns, $where);//
    if ($dataset_type == "Farmer") {
        if (sizeof($rows) == 0) {
            echo "<p style=\"font-size:1.4em; width:100%; text-align:center; color:#999; margin-top:100px\">No Data</p>";
        } else {
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
                $age_ = $util_obj->getAge($dob, "Africa/Nairobi");;
                $name = strlen($name) <= 15 ? $name : substr($name, 0, 14) . "...";


////////////////////////////////////////////////////////////////////////gfhsfaghjfasj
                if ($_SESSION['client_id'] == 1) {
                    echo "<tr>";
                    echo "
                
                   <td>" . substr($gender, 0, 1) . "</td>
                
                  
                  <td style='line-height:12pt; align:center'>$name</td>
                  <td style='line-height:12pt; align:center'>$age_</td>";
//                    <td style='line-height:12pt; align:center'>$district</td>

                    $acares = array();

                    $gardens_table = "garden_" . $id;

                    if ($mCrudFunctions->check_table_exists($gardens_table) > 0) {
//echo $uuid;

                        $gardens = $mCrudFunctions->fetch_rows($gardens_table, " DISTINCT PARENT_KEY_ ", " PARENT_KEY_ LIKE '$uuid%' ");

                        if (sizeof($gardens) < 0) {
//echo"<p>Gardens: $total_gardens ,AverageAcerage: $average </p><br/>";

                        } else {
                            $z = 1;
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
                                array_push($acares, $acerage);
                                $z++;
                            }
                            $total_gardens = sizeof($gardens);
                            $average = 0;
                            if ($total_gardens != 0) {
                                $average = round(array_sum($acares)/*$total_gardens*/, 2);
                            }

                            $_gardens = $total_gardens == 1 ? " Garden" : " Gardens";
                            echo "<td>$total_gardens$_gardens</td>
                                    <td> <b>Acreage:</b> $average</td>";
                        }


                    } //<i class=\"fa fa-navicon fa-1x\" aria-hidden=\"true\"></i>
                    echo "<td>
    
  <a  href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\" class=\" btn \" style=\"color:#F57C00; margin:0; width:100%;\">View Details &raquo;</a>
    </td>";

                    $client_id = $_SESSION['client_id'];
                    if ($client_id == 4324524) {

                        echo "
     <div class=\"col-sm-6 col-sm-offset-1 \">
    <p style=\"margin-top:0px\" style=\"color:#F57C00; margin:0; width:100%;\">
        <div class=\"dropdown\">
        <a href=\"#\" data-toggle=\"dropdown\" class=\"dropdown-toggle btn \" style=\"color:teal; margin:0; width:100%;\">Add Details &raquo;</a>
            <ul class=\"dropdown-menu\">
			<li><a href=\"#inputs\"  onclick=\"predictor($real_id_,$average);\" data-toggle=\"modal\" data-target=\"#inputs\">Record Farm Inputs</a></li>
            <li><a href=\"#yield\" onclick=\"predictor($real_id_,$average);\" data-toggle=\"modal\" data-target=\"#yield\">Record Farmer's Yield</a></li>
			<li><a href=\"#cash\" onclick=\"predictor($real_id_,$average);\" data-toggle=\"modal\" data-target=\"#cash\">Record Cash Returned</a></li>
          </ul>
        </div>
    </p>
    </div>
	";
                    }


                    echo "
    </div>
      </div>
    </div>
  </div> </a>";

                    echo "</tr>";
                } else {
                    echo "<tr>

                           <td>" . substr($gender, 0, 1) . "</td>
                          <td>$name</td> <td>$age_ yrs</td>
                          ";
//                    echo "<a href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\">
//                          <div class=\"col-sm-12 col-md-3 col-lg-3 col-xs-12\" style=\" margin-top:5px;\">
//                          <div class=\"thumbnabil card\" id=\"space\">
//                          <div class=\"image-box\" style=\"background-image:url('$picture');
//                          background-color:#e9e9e9;
//                          background-position:center;
//                          background-repeat:no-repeat;
//                          background-size:100%\">
//
//                           <span class=\"gender\">" . substr($gender, 0, 1) . "</span>
//                           </div>
//                          <div class=\"caption\" style=\"margin:0; padding:10px;\">
//                          <p style='line-height:12pt;'>$name <span class=\"badge1\">$age_ yrs</span></p><br/>
//                          ";

                    $acares = array();

                    $gardens_table = "garden_" . $id;

                    if ($mCrudFunctions->check_table_exists($gardens_table) > 0) {
//echo $uuid;

                        $gardens = $mCrudFunctions->fetch_rows($gardens_table, " DISTINCT PARENT_KEY_ ", " PARENT_KEY_ LIKE '$uuid%' ");

                        if (sizeof($gardens) < 0) {
//echo"<p>Gardens: $total_gardens ,AverageAcerage: $average </p><br/>";

                        } else {
                            $z = 1;
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
                                array_push($acares, $acerage);
                                $z++;
                            }
                            $total_gardens = sizeof($gardens);
                            $average = 0;
                            if ($total_gardens != 0) {
                                $average = round(array_sum($acares)/*$total_gardens*/, 2);
                            }

                            $_gardens = $total_gardens == 1 ? " Garden" : " Gardens";
                            echo "<td><h6>$total_gardens$_gardens</h6></td>
                                    <td style=\"color:#888\"> <b>Acreage:</b> $average</td>";
                        }


                    } //<i class=\"fa fa-navicon fa-1x\" aria-hidden=\"true\"></i>
                    echo "
    <div class=\"row\">
    <td class=\"col-sm-4\">
  <td>
  <a  href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\" class=\" btn \" style=\"color:#F57C00; padding: 1px; width:100%;\">Details &raquo;</a></td>
    </td>";

                    $client_id = $_SESSION['client_id'];
                    if ($client_id == 4324524) {

                        echo "
                         <div class=\"col-sm-6 col-sm-offset-1 \">
                        <p style=\"margin-top:0px\" style=\"color:#F57C00; margin:0; width:100%;\">
                            <div class=\"dropdown\">
                            <a href=\"#\" data-toggle=\"dropdown\" class=\"dropdown-toggle btn \" style=\"color:teal; margin:0; width:100%;\">Add Details &raquo;</a>
                                <ul class=\"dropdown-menu\">
                                <li><a href=\"#inputs\"  onclick=\"predictor($real_id_,$average);\" data-toggle=\"modal\" data-target=\"#inputs\">Record Farm Inputs</a></li>
                                <li><a href=\"#yield\" onclick=\"predictor($real_id_,$average);\" data-toggle=\"modal\" data-target=\"#yield\">Record Farmer's Yield</a></li>
                                <li><a href=\"#cash\" onclick=\"predictor($real_id_,$average);\" data-toggle=\"modal\" data-target=\"#cash\">Record Cash Returned</a></li>
                              </ul>
                            </div>
                        </p>
                        </div>
                        ";
                    }

                    echo "

  </tr>";

                }
                ///////////////////////////////////////////////////////////////////////////////////////


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


