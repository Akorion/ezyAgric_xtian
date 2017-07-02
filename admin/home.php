<?php ob_start();?>
<?php
		include("../include/header.php");
		if (!empty($_GET['action'])) { 
			    $action = $_GET['action'];  
			    $action = basename($action);  
				if (!file_exists("$action.php"))
				{
		           include("dashboard.php");							
		           		}
		  		 	else {
		       include("$action.php"); 
		   				 } 
		   				}
		else {
			     include("dashboard.php");
			 }
	include("../include/footer.php");	
	?>	
<?php ob_end_flush(); ?>