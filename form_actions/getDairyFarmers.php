<style type="text/css">
    th, thead {
        height: 2em !important;
        background: teal;
        color: #fff !important;
    }

</style>
<?php
error_reporting(0);

#includes
session_start();
require_once dirname(dirname(__FILE__)) . "/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/utility_class.php";
require_once dirname(dirname(__FILE__)) . "/php_lib/lib_functions/pagination_class.php";

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
    $strings = explode('-', $va);

    $va = strtolower($strings[1]); // outputs: india

    if (isset($_POST['district']) && $_POST['district'] != "") {

        switch ($_POST['district']) {
            case  "all" :
                if ($_POST['gender'] == "all") {
                    ///v2 code interview_particulars_va_code
                    if ($_POST['va'] == "all") {

                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                        output($id, " 1 ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                        echo "</table>";

                    } else {
//                            echo "Dataset: $dt_st_name";
                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                        output($id, " lower(REPLACE(REPLACE(va_code,' ',''),'.','')) = '$va' ORDER BY first_name LIMIT $per_page OFFSET $offset  ");
                        echo "</table>";
                    }

                } else {        //gender else option
                    //
                    //echo $gender;

                    if ($_POST['va'] == "all") {
                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                        output($id, " lower(gender) = '$gender' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                        echo "</table>";
                    } else {
                        echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                        output($id, " lower(REPLACE(REPLACE(va_code,' ',''),'.','')) = '$va' AND lower(gender) = '$gender' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                        echo "</table>";
                    }

                }

/////////////////////////////////////////////////////////////////////district all ends
                break;
            default:

                if ($_POST['subcounty'] == "all") {
                    $district = $_POST['district'];

///////////////////////////////////////////////////////////////////// subcounty all starts

                    if ($_POST['gender'] == "all") {
                        //echo $per_page;
                        //$snt= filter_var($district, FILTER_SANITIZE_STRING);
                        //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                        if ($_POST['va'] == "all") {
                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                            output($id, "  TRIM(TRAILING '.' FROM district) LIKE '$district' ORDER BY first_name  LIMIT $per_page OFFSET $offset");
                            echo "</table>";

                        } else {
                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                            output($id, "  TRIM(TRAILING '.' FROM district) LIKE '$district' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY first_name LIMIT $per_page OFFSET $offset");
                            echo "</table>";
                        }

                    } else {

                        if ($_POST['va'] == "all") {
                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                            echo "<table>" . output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND lower(gender) = '$gender' ORDER BY first_name LIMIT $per_page OFFSET $offset ") . "</table>";
                            echo "</table>";
                        } else {
                            echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                            output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND lower(gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                            echo "</table>";
                        }

                    }

//////////////////////////////////////////////////////////////////// subcounty all ends
                } else {
                    $subcounty = $_POST['subcounty'];
                    $district = $_POST['district'];
                    if ($_POST['parish'] == "all") {

                        ////////////////////////////////////////////////////////////////////// parish all starts

                        if ($_POST['gender'] == "all") {

                            if ($_POST['va'] == "all") {

                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                output($id, "  TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                echo "</table>";

                            } else {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                echo "</table>";
                            }

                        } else {
                            //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
//                            echo "parish not all";

                            if ($_POST['va'] == "all") {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND lower(gender) = '$gender' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                echo "</table>";
                            } else {
                                echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND lower(gender) = '$gender' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                echo "</table>";
                            }

                        }

///////////////////////////////////////////////////////////////////////////////// parish all ends
                    } else {
                        $subcounty = $_POST['subcounty'];
                        $district = $_POST['district'];
                        $parish = $_POST['parish'];

                        if ($_POST['village'] == "all") {

                            if ($_POST['gender'] == "all") {

                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM parish) LIKE '$parish' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM parish) LIKE '$parish' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                }

                            } else {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM parish) LIKE '$parish' AND lower(gender) = '$gender' AND parish LIKE '%$parish%' ORDER BY first_name LIMIT $per_page OFFSET $offset  ");
                                    echo "</table>";

                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM parish) LIKE '$parish' AND lower(gender) = '$gender' AND parish LIKE '%$parish%' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY first_name LIMIT $per_page OFFSET $offset  ");
                                    echo "</table>";
                                }

                            }
                            /////////////////////////////////////////////////////////////////////////////
                        } else {
                            $subcounty = $_POST['subcounty'];
                            $district = $_POST['district'];
                            $parish = $_POST['parish'];
                            $village = $_POST['village'];

                            if ($_POST['gender'] == "all") {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'

                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM village) LIKE '$village' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM village) LIKE '$village' AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                }


                            } else {
                                //lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va'
                                if ($_POST['va'] == "all") {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM village) LIKE '$village' AND lower(gender) = '$gender' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";
                                } else {
                                    echo "<table class='$class'><thead class='bg bg-success'><tr><th>#</th><th>Name</th> <th>Age</th><th>Gender</th><th>Contact</th><th>Cows owned</th><th>Lactating Cows</th><th>Last milk supply</th><th>Date of last supply</th><th>Details</th></tr></thead>";
                                    output($id, " TRIM(TRAILING '.' FROM district) LIKE '$district' AND TRIM(TRAILING '.' FROM subcounty) LIKE '$subcounty' AND  TRIM(TRAILING '.' FROM parish) LIKE '$parish' AND TRIM(TRAILING '.' FROM village) LIKE '$village' AND lower(gender) = '$gender'  AND lower(REPLACE(REPLACE(interview_particulars_va_code,' ',''),'.','')) = '$va' ORDER BY first_name LIMIT $per_page OFFSET $offset ");
                                    echo "</table>";

                                }


                            }


                        }

                    }

                }

                break;
        }

    }

}

//generating table as output
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
    $columns = "* , (DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(age))/365.25) as actualAgeInYrs , floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(age))/365.25) as ageInYrs ";

//    $age_min = !empty($_POST['age_min']) ? (int)$_POST['age_min'] : 0;
//    $age = !empty($_POST['age']) ? (int)$_POST['age'] : 0;
//    $age_max = !empty($_POST['age_max']) ? (int)$_POST['age_max'] : 0;

//    if ($age != "" && $age != 0) {
//        $where = "floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25)=$age  AND " . $where;
//    }
//
//    if ($age_min <= $age_max && $age_max != 0) {
//        $where = " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) BETWEEN $age_min AND $age_max )  AND " . $where;
//    }
//
//    if ($age_min > 0 && $age_max == 0) {
//        $where = " (floor(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE(biodata_farmer_dob))/365.25) > $age_min )  AND " . $where;
//    }

    $rows = $mCrudFunctions->fetch_rows($table, "*", $where);


    if ($dataset_type == "Farmer") {
        if (sizeof($rows) == 0) {
            echo "<p style=\"font-size:1.4em; width:100%; text-align:center; color:#999; margin-top:100px\">There are no farmers in the dataset</p>";
        } else {
            $counter = 1;
//            echo "inside table ........";
            foreach ($rows as $row) {

//                print_r($row);
                $real_id = $row['id'];

                $real_id = $util_obj->encrypt_decrypt("encrypt", $real_id);
                $name = $row['first_name'];
//                echo $name;
                $last_name = $row['last_name'];
                $gender = $row['gender'];
                $date = $row['age'];
                $district = $row['district'];
                $milk = '';
                $last_trans_date = '';
                $phone_number = $row['phonenumber'];
                $cows = $row['number_of_cows'] + $row['number_of_bulls']+ $row['number_of_calves'];
                $lactating_cows = $row['lactating_cows'];
                $age = explode('/',$date);
                $farmer_age = 2017 - $age[2];
//                echo  "<br>".$farmer_age;

//                print_r($name);
//                $gender = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['gender']));
//                $dob = $util_obj->remove_apostrophes($row['age']);
////                $picture = $util_obj->remove_apostrophes($row['farmer_image']);
//                $district = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['district']));
//                $phone_number = $util_obj->remove_apostrophes($row['phonenumber']);
//
//                //$uuid = $util_obj->remove_apostrophes($row['meta_instanceID']);
//                $age_ = $util_obj->getAge($dob, "Africa/Nairobi");
                //$name = strlen($name) <= 15 ? $name : substr($name, 0, 14) . "...";

////////////////////////////////////////////////////////////////////////
//                if ($_SESSION['client_id'] == 1) {
                echo "<tr>";
                echo "
                        <td>$counter</td>

                  <td style='line-height:12pt; align:center'>$name $last_name</td>
                  <td style='line-height:12pt; align:center'>$farmer_age</td>
                  <td style='line-height:12pt; align:center'>$gender</td>
                  <td style='line-height:12pt; align:center'>$phone_number</td>
                  <td style='line-height:12pt; align:center'>$cows</td>
                  <td style='line-height:12pt; align:center'>$lactating_cows</td>
                  <td style='line-height:12pt; align:center'>$milk</td>
                  <td style='line-height:12pt; align:center'>$last_trans_date</td>";

                echo " <td> <a class='btn btn-success' href=\"dairyDetails.php?s=$dataset_&token=$real_id&type=$dataset_type\" style=\"color:#FFFFFF; padding: 8px; margin: 0;\">View Details</a>
                       </td>";
                echo "</tr>";
//                echo "
//                      </div>
//                    </div>
//                   </div>
//                 </div> </a>";

                $counter++;
            }

//        if ($dataset_type == "VA") {
//            if (sizeof($rows) == 0) {
//                echo "<p style=\"font-size:2em; text-align:center; color:#777; margin-top:100px\">No Data</p>";
//            } else {
//                foreach ($rows as $row) {
//                    $real_id = $row['id'];
//
//                    $real_id = $util_obj->encrypt_decrypt("encrypt", $real_id);
//                    $name = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['first_name']));
//                    $gender = $util_obj->captalizeEachWord($util_obj->remove_apostrophes($row['gender']));
//                    $picture = $util_obj->remove_apostrophes($row['farmer_image']);
//
//                    echo "<div class=\"col-sm-12 col-md-4 col-lg-3\">
//
//                          <div class=\"thumbnail card\" id=\"\">
//                          <div class=\"image-box\" style=\"background-image:url('$picture');
//                          background-color:#e9e9e9;
//                          background-position:center;
//                          background-repeat:no-repeat;
//                          background-size:100%\">
//
//                           <span class=\"gender\">" . substr($gender, 0, 1) . "</span>
//                           </div>
//                          <div class=\"caption\" style=\"margin:0\">
//                          <p>$name</p><br/>
//
//
//
// <div class=\"row\">
//    <div class=\"col-sm-4\">
//  <p style=\"margin-top:10px\"><a  href=\"user_details.php?s=$dataset_&token=$real_id&type=$dataset_type\" class=\" btn\" style=\"color:#F57C00; margin:0; width:100%;\">View Details &raquo;</a></p>
//    </div>
//
//    </div>
//      </div>
//    </div>
//  </div>";
//
//                }
//            }
//        }

        }
    }
}

?>
