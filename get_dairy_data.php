<?php
/**
 * Created by PhpStorm.
 * User: TO-002
 * Date: 23/01/2018
 * Time: 1:33 AM
 */
$phone = $_POST['phone_no'];
echo "<p style='padding-left: 10px;'><b> Milk Supplied(Ltrs) </b></p><p style='padding-left: 60px'><b>Date</b></p><hr/>";
      $today = date("Y-m-d", strtotime('today'));
      $svndays = date("Y-m-d", strtotime('-5 days'));

      $milk_data = file_get_contents("https://mcash.ug/farmers/?query=milkdata&access_token=b31ff5eb07171e028e7af6920bbbccab0b43136e08af525fd2cd40333db2ab31&start_date=$svndays&end_date=$today");
      $milk_periodic_data = json_decode($milk_data);
      foreach ($milk_periodic_data as $milk_supply) {
          $milk_quantity = $milk_supply->milk_amount;
          $supply_date = new DateTime($milk_supply->created_at);
          $date = $supply_date->format('d/m/Y');

          /**introduce farmer phone to get their specific data**/
          $account = $milk_supply->account_no;
          $mobile_no = substr($account, 6);
          if($mobile_no == $phone){
              echo "
                <p style='padding-left: 40px;'>$milk_quantity </p><p style='padding-left: 60px'> $date </p></hr>
              ";
          }
      }