<?php ob_start();?>
<?php
		include("include/header.html");
		if (!empty($_GET['action'])) { 
			    $action = $_GET['action'];  
			    $action = basename($action);  
				if (!file_exists("$action.php"))
				{
		           include("dashboardAll.php");
		           		}
		  		 	else {
		       include("$action.php"); 
		   				 } 
		   				}
		else {
			     include("dashboardAll.php");
			 }
	include("include/footer.html");	
	?>	
<?php ob_end_flush(); ?>