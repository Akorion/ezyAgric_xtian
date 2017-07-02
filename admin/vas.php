<div class="container-fluid">
<div class="row">
<?php include("../include/sidebar.php");?>
<!--end of sidenav-->

<!-- main content area-->
<div class="col-sm-12 col-md-9 col-lg-9 data">
<?php 
	include("../include/filter.php");
?>

<div class="row">
<h5 class="text-center vas">Number of VA's per district</h5>
<div class="col-sm-12 col-md-12 col-lg-12">
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="success">
	<th>Number</th>
	<th>Region</th>
	<th>District</th>
	<th>Sub-county</th>
	<th>Parish</th>
	<th>Village</th>
	<th>Frequency</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="success">
	<td>1</td>
	<td>East</td>
	<td>Mbale</td>
	<td>Buyaga</td>
	<td>idungu</td>
	<td>Kuma</td>
	<td>50</td>
	<td>20.50</td>
	</tr>';
	}
?>
</tbody>
</table>
</div>

</div>
</div>
<!--start of va under each farmer-->
<div class="row">
<h4 class="text-center vas">Number of farmers under each VA</h4>
<div class="col-sm-12 col-md-12 col-lg-12">
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="success">
	<th>Number</th>
	<th>Name of VAs</th>
	<th>Number of farmer per VA</th>
	<th>%(ge)</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="warning">
	<td>1</td>
	<td>Afua mutyaba</td>
	<td>98</td>
	<td>20.50</td>
	</tr>';
	}
?>
</tbody>
</table>
</div>

</div>
</div>
<!--end of main content area-->
</div>
</div>
<!--end of main container-->

