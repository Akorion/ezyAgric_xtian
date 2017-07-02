<?php
#includes
require_once dirname(dirname(__FILE__))."/php_lib/user_functions/crud_functions_class.php";
require_once dirname(dirname(__FILE__))."/php_lib/lib_functions/pagination_class.php";

$mCrudFunctions = new CrudFunctions();
 $page =!empty($_POST['page'])? (int)$_POST['page'] : 1;
 $per_page=16;
 $total_count_default=0;
 if(isset($_POST['id'])){
           $sql="";
		   $id=$_POST['id'];
           if(!empty($_POST['search'])){
             $search=$_POST['search'];
             $sql= " AND  (   name LIKE '%$search%' OR 
                 va_phonenumber ='$search' OR 
				  gender ='$search'
                  ) GROUP BY va_phonenumber";
				   $sql_=str_replace("AND","",$sql);
             $total_count_default=$mCrudFunctions->get_count("dataset_".$id,$sql_);/// $sql_
	      
           }else{
		    
             $total_count_default=$mCrudFunctions->get_count("dataset_".$id,1);/// $sql_
	      
		   
		   }
           
		  
		  // $total_count= $mCrudFunctions->get_count($table,1);
		  
		  
            $pagination_obj= new Pagination($page,$per_page,$total_count_default); 
		  
	       
	      
           $total_pages= $pagination_obj->getTotalPages();
		  //hidden 
          echo"<input id=\"per_page\"type=\"hidden\" value=\"$per_page\" />";
		  //hidden 
          echo"<input id=\"total_count\"type=\"hidden\" value=\"$total_count\" />";
		  //hidden 
          echo"<input id=\"current_page\"type=\"hidden\" value=\"$page\"  />";
		  if($total_pages>0){
		  echo '<ul class="pagination center">';
		  if( $pagination_obj->check4PreviousPage()){
		  $previous_page= $pagination_obj->getPreviousPage();
		   echo"<li class=\"waves-effect\"><a onclick=\"filterDataVAPagination(1);\" style=\"height:35px;\"><i class=\"material-icons\"><<<<</i></a></li>";
		  echo"<li class=\"waves-effect\"><a onclick=\"filterDataVAPagination($previous_page);\" style=\"height:35px;\"><i class=\"material-icons\"><<</i></a></li>";
		  };
		  $j=0;
		  //$offset= $pagination_obj->getOffset();
		  for($i=$page;$i<$total_pages; $i++){
		  
		  if($total_pages>7){
		  if($j==7){
		  break;
		  }
		  }
		  if($page==$i){
		  echo"<li class=\"active\"><a onclick=\"filterDataVAPagination($i);\">$i</a></li>";
		  }else{
		  echo"<li class=\"waves-effect\"><a onclick=\"filterDataVAPagination($i);\">$i</a></li>";
		  }
		  
		  $j++;  
		  }
		  
		  if( $pagination_obj->check4NextPage()){
		  $next_page= $pagination_obj->getNextPage();
		  echo"<li class=\"waves-effect\"><a onclick=\"filterDataVAPagination($next_page)\" style=\"height:35px;\"><i class=\"material-icons\">>></i></a></li>";
		  
		  echo"<li class=\"waves-effect\"><a onclick=\"filterDataVAPagination($total_pages)\" style=\"height:35px;\"><i class=\"material-icons\">>>>></i></a></li>";
		  };
		  echo'</ul>';
		 }

}
          
unset($mCrudFunctions);
?>