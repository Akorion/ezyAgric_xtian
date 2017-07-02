<?php
#includes
require_once dirname(dirname(__FILE__))."/config/database_config.php";
/*
This class methods for establishing a connection to the server
*/
Class ServerConnection{

protected $_server_name;
protected $_user_name;
protected $_password;
protected $_database_name;
protected $_connection;

public function __construct(){
	$this->initializeServerConnection(); 
}

public function setServerName($server_name){
    $this->_server_name=$server_name;
}

public function getServerName(){
    return $this->_server_name;
}

public function setUserName($user_name){
    $this->_user_name=$user_name;
}

public function getUserName(){
    return $this->_user_name;
}

public function setPassword($password){
    $this->_password=$password;
}

public function getPassword(){
    return $this->_password;
}

public function setDataBaseName($_database_name){
    $this->_database_name=$_database_name;
}

public function getDataBaseName(){
    return $this->_database_name;
}

public function setServerConnection(){
     $this->_connection=mysqli_connect($this->getServerName(),$this->getUserName(),$this->getPassword(),$this->getDataBaseName());
	 if (mysqli_connect_errno())
     {
     echo "Failed to connect to the server: " . mysqli_connect_error();
     }																
}

public function getServerConnection(){
    return $this->_connection;
}
public function initializeServerConnection(){
   $db_config= new database_config();  
   $this->setServerName($db_config->_server_name);
   $this->setUserName($db_config->_user_name);
   $this->setPassword($db_config->_password);
   $this->setDataBaseName($db_config->_database_name) ;  
   $this->setServerConnection();
}


}

$ServerConnection = new ServerConnection();

?>