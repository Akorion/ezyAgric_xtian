<?php 
error_reporting(0);
class database_config{

public $_server_name;
public $_user_name;
public $_password;
public $_database_name;

public function __construct(){
   $this->_server_name="localhost";
   $this->_user_name="root";
   $this->_password="root";
   $this->_database_name="akorionc";
   ini_set('max_execution_time', 300); //300 seconds = 5 minutes
   ini_set("auto_detect_line_endings", "1");   
}
}
/*

class database_config{

public $_server_name;
public $_user_name;
public $_password;
public $_database_name;

public function __construct(){
   $this->_server_name="localhost";
   $this->_user_name="hcsolut3_ezy";
   $this->_password="AFr1c@AFr1c@";
   $this->_database_name="hcsolut3_ezyagric"; 
   ini_set('max_execution_time', 300); //300 seconds = 5 minutes
   ini_set("auto_detect_line_endings", "1");   
}
}

*/
?>
