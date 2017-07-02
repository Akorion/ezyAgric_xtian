<?php
require_once dirname(__FILE__)."/php_lib/lib_functions/database_query_processor_class.php";
require_once dirname(__FILE__)."/php_lib/lib_functions/utility_class.php";
/*function mysql_prep( $value ) {
		$magic_quotes_active = get_magic_quotes_gpc();
		$new_enough_php = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
		if( $new_enough_php ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $magic_quotes_active ) { $value = stripslashes( $value ); }
			$value = mysql_real_escape_string( $value );
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}*/
$db= new DatabaseQueryProcessor();
$util_obj= new Utilties();
if(isset($_POST["Submit"])){



if ($_FILES["csv"]["size"] > 0) {
    
    
     $fileinfo = pathinfo($_FILES["csv"]["name"]);
	 $filetype = $_FILES["csv"]["type"];
    

    //Validate File Type
	if(strtolower(trim($fileinfo["extension"])) != "csv")
	{
         echo"bad file";
	}
	else
	{
		$file_path = $_FILES["csv"]["tmp_name"];
        $handle = fopen($file_path,"r"); 
        $i=0;
		$columns=0;
		
		$data = fgetcsv($handle,0,",","'");
		$columns=sizeof($data);
		
	    echo "rows: ".$i ." cols: ".$columns ;
	 
	    /*$db = null;
	   
	    $conn = mysql_connect("localhost", "root", "")
	    or die("Could not connect.");
		
        $rs = mysql_select_db("ezyagric_v2", $conn)
	    or die("Could not select database.");*/
       
	   if($columns>0){
	   $table="dataset_33";
	   $sql = "create table $table (";
	    $sql .= " id INT NOT NULL AUTO_INCREMENT PRIMARY KEY  , ";
        for ($i = 0; $i < $columns; $i++) 
        {
	     $validate_=  str_replace(" ","_",$data[$i]);
		 $validate_=  str_replace("\""," ",$validate_);
		 $validate_=  str_replace(":","_",$validate_);
		
		 $sql .= "$validate_ text ";
		
		 if(($i+1) != $columns){ $sql.=","; }
        
       
	   }
         $sql .= ")";

       echo("SQL COMMAND: $sql <hr>");

      $result =$db->setResultForQuery($sql);// mysql_query($sql,$conn)
	  // or die("Could not execute SQL query");

       if ($result) {   
	   $column_string= $util_obj->getColumnsString($db,$table,1);
	   $util_obj->insertCSVintoTable($db,$table,$column_string,$handle);
	   /*
       $result1 = mysql_query(" SELECT * FROM $table",$conn);	   
	   echo("RESULT: table \"$table\" has been created");
	   $i = 0;
	   $columns__="";
	   while ($i < mysql_num_fields($result1))
	   {
		$meta = mysql_fetch_field($result1, $i);
		echo  $meta->name . '</br>';
		if($i==mysql_num_fields($result1)-1){$columns__.= $meta->name." ";}else{
		if($i==0){

		}else{
		$columns__.= $meta->name." ,";
		}
		
		}
		
		$i = $i + 1;
	   */
	   
	   }
	   
	   
	/*
	  while($data = fgetcsv($handle,0,",","'")){
         if($i==0){
         
         } else{
		 $insert="";
		 $j=0;
		 for($j=0;$j< sizeof($data);$j++) {
		
		 if($j==sizeof($data)-1){ 
		 $insert.=" '$data[$j]' "	;
		 }else{$insert.="'$data[$j]',"	;}
		 		 
		 }
		 $insert_data= " INSERT INTO ".$table." (". $columns__.") VALUES ( " .$insert.") " ;
		 
		 
		 
		  $flag= mysql_query(" INSERT INTO ".$table." (". $columns__.") VALUES ( " .$insert.") ",$conn);
		  echo $insert_data ." ".$flag;
		 }
	  }*/
	
	
	
	
      }
	   
	   
	   }
	   
       
   }

} else{
         echo "anmam";
		
}

/*
 $array= array(1,2,3);
 $string = implode (" ,", $array);
 echo $string;
 $array2 =explode (" ,", $string);
 print_r( $array2);
 */

?>