<?php include("include/header_client.php");?>
<div class="container farmer">
<legend><h5 class="text-center vas">Search results</h5></legend>
<div class="row" style="margin-top:30px;">
<?php  for($k=0;$k<=4;$k++)
{

echo '
<div class="col-sm-12 col-md-3 col-lg-3">
<div class="media">
  <div class="media-left media-middle">
    <a href="#">
      <img class="media-object" src="images/th.jpg" alt="search image" style="height:150px; width:150px;">
    </a>
  </div>
  <div class="media-body">
    <h4 class="media-heading">Name here</h4>
    <p>Phone</p><br/>
    <p>Region</p><br/>
    <p>District</p><br/>
    <p class="text-right"><a href="#">view more &raquo;</a></p>
  </div>
</div>
</div>';
}
?>
</div>
</div>
<!--end of main container-->

<?php include("include/footer_client.php");?>