<?php include("include/header_client.php");?>
 
<div class="container">

<div class="row">
<h2 class="page-header">Messages</h2>

<div class="col-md-4">
	<div class="alert alert-warning">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<strong>Warning!</strong> There was a problem with your network connection.
	</div>
</div>

<div class="col-md-4">
	<div class="alert alert-success">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<strong>Success!</strong> Logged in successfully, welcome.
	</div>

</div>

<div class="col-md-4">
	<div class="alert alert-danger">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<strong>Danger!</strong> Error retrieving data.
	</div>
</div>

</div>
<div class="row">
	<div class="col-md-4">
		<div class="progress progress-striped">
    <div class="progress-bar" style="width: 60%;">
        <span class="sr-only">60% Complete</span>
    </div>
</div>
	</div>
	<div class="col-md-4">
		<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
    <span class="sr-only">45% Complete</span>
  </div>
</div>
	</div>
</div>
</div><!--end of main content-->

<?php include("include/footer_client.php"); ?>

