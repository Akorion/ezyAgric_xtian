<div class="container-fluid">
<div class="col-xs-12">
<ol class="breadcrumb">

<?php
$path = $_SERVER["PHP_SELF"];

    $parts = explode('/',$path);
	
	$crumb="";
	$_SESSION["pages"];
	switch(end($parts)){
	
	case 'dash.php';
	$crumb .="<li><a href=\"./dash.php\">Home</a></li>";
	
	$_SESSION["pages"][0]=$crumb;
	$_SESSION["pages"][1]=$crumb;
	//unset($_SESSION["pages"][1]);
	unset($_SESSION["pages"][2]);
	unset($_SESSION["pages"][3]);
	unset($_SESSION["pages"][4]);
	break;
	
	case 'dash1.php';
	$crumb .="<li><a href=\"./dash1.php\">Home</a></li>";
	
	$_SESSION["pages"][0]=$crumb;
	
	unset($_SESSION["pages"][1]);
	unset($_SESSION["pages"][2]);
	unset($_SESSION["pages"][3]);
	unset($_SESSION["pages"][4]);
	break;
	
	case 'dashboard.php';
	$token=$_GET['token'];
	$crumb .= $_SESSION["pages"][0]. "<li><a href=\"dashboard.php?token=$token\">Explore Season</a></li>";
	$_SESSION["pages"][1]=$crumb;
	
	unset($_SESSION["pages"][2]);
	unset($_SESSION["pages"][3]);
	unset($_SESSION["pages"][4]);
	break;
	
	case 'view.php';
	$crumb .= $_SESSION["pages"][1];
	
	$token=$_GET['token'];
	$type=$_GET['type'];
	$o=$_GET['o'];
	
	
	if($_GET['type']=='VA'){
	$crumb .=  "<li><a href=\"view.php?o=$o&token=$token&type=$type\">Village Agents</a></li>";
	}else if($_GET['type']=='Farmer'){
	$crumb .=  "<li><a href=\"view.php?token=$token&type=$type\">Farmers</a></li>";
	}
	
	$_SESSION["pages"][2]=$crumb;
	
	unset($_SESSION["pages"][3]);
	unset($_SESSION["pages"][4]);
	break;
	case 'user_details.php';
	
//	o=RVNoOG0vSXU2Ykd6V2ZvUmVLQ1dDdz09&s=VmkvTkN3aUdLditMcDlKYWY2Vkozdz09&token=RWF2T2pILzZTNHY0My9NNEdTUSt2UT09&type=VA
	
	$s=$_GET['s'];
	$token=$_GET['token'];
	$type=$_GET['type'];
	$o=$_GET['o'];
	
	if(strpos($_SESSION["pages"][3],"VA Farmers")){
	
	$crumb .= $_SESSION["pages"][3];
	if($_GET['type']=='VA'){
	$crumb .=  "<li><a href=\"user_details.php?o=$o&s=$s&token=$token&type=$type\">Village Profile</a></li>";
	}else if($_GET['type']=='Farmer'){
	$crumb .=  "<li><a href=\"user_details.php?s=$s&token=$token&type=$type\">Farmer Profile</a></li>";
	}
	$_SESSION["pages"][4]=$crumb;
	
	}else{
	
	$crumb .= $_SESSION["pages"][2];
	if($_GET['type']=='VA'){
	$crumb .=  "<li><a href=\"user_details.php?o=$o&s=$s&token=$token&type=$type\">Village Profile</a></li>";
	}else if($_GET['type']=='Farmer'){
	$crumb .=  "<li><a href=\"user_details.php?s=$s&token=$token&type=$type\">Farmer Profile</a></li>";
	}
	
	$_SESSION["pages"][3]=$crumb;
	}
	
	break;
	
	case 'va_farmers.php';
	
	$s=$_GET['s'];
	$token=$_GET['token'];
	$type=$_GET['type'];
	$o=$_GET['o'];
	$crumb .= $_SESSION["pages"][2];
	
	$crumb .=  "<li><a href=\"va_farmers.php?o=$o&s=$s&token=$token&type=$type\">VA Farmers</a></li>";
	
	
	$_SESSION["pages"][3]=$crumb;
	
	break;
	
	case 'va_receipts.php';
	
	$s=$_GET['s'];
	$token=$_GET['token'];
	$type=$_GET['type'];
	$o=$_GET['o'];
	$crumb .= $_SESSION["pages"][2];
	
	$crumb .=  "<li><a href=\"va_receipts.php?o=$o&s=$s&token=$token&type=$type\">VA Reciepts</a></li>";
	
	
	$_SESSION["pages"][3]=$crumb;
	
	break;
	
	case 'settings.php';
	
	
	$crumb .= end($_SESSION["pages"]);
	
	
	$crumb .=  "<li><a href=\"settings.php\">Settings</a></li>";
	break;
	
	case 'logs.php';
	
	
	$crumb .= end($_SESSION["pages"]);
	
	
	$crumb .=  "<li><a href=\"logs.php\">System Logs</a></li>";
	break;
	
	case 'system_constants.php';
	
	
	$crumb .= end($_SESSION["pages"]);
	
	
	$crumb .=  "<li><a href=\"system_constants.php\">Default Options</a></li>";
	break;
	
	case 'stat_farmers.php';
	
	
	$crumb .= end($_SESSION["pages"]);
	
	
	$crumb .=  "<li><a href=\"stat_farmers.php\">Farmer Statistics</a></li>";
	break;
	
	
	}

	ksort($_SESSION["pages"]);
	echo $crumb;
  


?>

  
</ol>
    
</div>
</div>