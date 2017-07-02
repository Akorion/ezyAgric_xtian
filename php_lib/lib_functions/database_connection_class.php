<?php
#includes
require_once dirname(dirname(__FILE__))."/config/database_config.php";
require_once('server_connection_class.php');
/*
This class methods for establishing a connection to the database
*/
Class DatabaseConnection {

protected $_database_name;
protected $_connection_object;

public function __construct(){  
   $this->intializeDatabaseConnection();
}

public function setDatabaseName($database_name){
    $this->_database_name=$database_name;
}

public function getDatabaseName(){
    return $this->_database_name;
}

public function setDatabaseConnection($database,$connection){
   mysqli_select_db( $database,$connection) or
   die('Could not connect to the database: '.$this->getDatabaseName().' error: '. mysqli_connect_errno());
}

public function intializeDatabaseConnection(){
 
   $db_config= new database_config(); 
   $this->setDatabaseName($db_config->_database_name);
   $this->_connection_object= new ServerConnection();
   $this->setDatabaseConnection($this->getDatabaseName(),$this->_connection_object->getServerConnection());
}

public function getDB(){
$db_config= new database_config();
  return  $db_config->_database_name;
}

public function getDatabaseConnection(){
  return $this->_connection_object->getServerConnection();
}

}
?>