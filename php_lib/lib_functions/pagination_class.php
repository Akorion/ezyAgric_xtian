<?php

class Pagination{

public $current_page;
public $per_page;
public $total_count;

public function __construct($page=1,$per_page=16,$total_count=0){
  $this->current_page=(int)$page;
  $this->per_page=(int)$per_page;
  $this->total_count=(int)$total_count;
}
public function getOffset(){
 return ($this->current_page - 1)*$this->per_page;
}
public function getTotalPages(){
  return ceil($this->total_count/$this->per_page);
}

public function getPreviousPage(){
 return $this->current_page-1;
}

public function getNextPage(){
 return $this->current_page+1;
}

public function check4PreviousPage(){
 return $this->getPreviousPage()>= 1? true : false;
}

public function check4NextPage(){
 return $this->getNextPage() <= $this->getTotalPages()? true : false;
}


}




?>