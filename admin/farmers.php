<div class="container-fluid">
<div class="row">
<?php include("../include/sidebar.php");?>
<!--end of sidenav-->

<!-- main content area-->
<div class="col-sm-12 col-md-9 col-lg-9 data">
<!--include filter here-->

<?php 
	//include("../include/filter.php");
?>
<div class="row">
<h5 class="text-center vas">Number of farmers per sub-county</h5>
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
	<td>100</td>
	<td>10</td>
	</tr>';
	}
?>
</tbody>
</table>
</div>

</div>
</div>
<!--start of  use of chemicals-->
<div class="row">
<div class="col-sm-12 col-md-6 col-lg-6">
<h4 class="vas">Use of  chemicals</h4>
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="info">
	<th>Number</th>
	<th>Response</th>
	<th>chemical use</th>
	<th>%(ge)</th>
	<th>Extract(%)</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="warning">
	<td>1</td>
	<td>Yes</td>
	<td>200</td>
	<td>8.07</td>
	<td>92</td>
	</tr>';
	}
?>
</tbody>
</table>
</div>
</div>
<div class="col-sm-12 col-md-6 col-lg-6">
<h4 class="vas">Money spent on chemicals</h4>
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="info">
	<th>Item</th>
	<th>Money(UGX)</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="warning">
	<td>Fertlizers</td>
	<td>50,000,000</td>
	</tr>';
	}
?>
</tbody>
</table>
</div>

</div>
</div>

<div class="row">
<div class="col-sm-12 col-md-6 col-lg-6">
<h4 class="vas">Average price of producs per KG</h4>
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="info">
	<th>Number</th>
	<th>Amount (UGX)</th>
	<th>Price per produce @kg(UGX)</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="warning">
	<td>N</td>
	<td>111051</td>
	<td>486.449697</td>

	</tr>';
	}
?>
</tbody>
</table>
</div>
</div>

<div class="col-sm-12 col-md-6 col-lg-6">
<h4 class="vas">Average price of producs per KG</h4>
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="info">
	<th>Number</th>
	<th>Item</th>
	<th>Amount</th>
	<th>%ge</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="warning">
	<td>N</td>
	<td>111051</td>
	<td>486.449697</td>
	<td>80.9999</td>
	</tr>';
	}
?>
</tbody>
</table>
</div>

</div>
</div>
<!--row for method of harvest and access to loans-->

<div class="row">
<div class="col-sm-12 col-md-6 col-lg-6">
<h4 class="vas">Method of harvest used by farmers</h4>
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="info">
	<th>Number</th>
	<th>Method of harvest</th>
	<th>Frequency</th>
	<th>%ge</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="warning">
	<td></td>
	<td>Machinery</td>
	<td>6</td>
	<td>0.418</td>

	</tr>';
	}
?>
</tbody>
</table>
</div>
</div>

<div class="col-sm-12 col-md-6 col-lg-6">
<h4 class="vas">Access to loans</h4>
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="info">
	<th>Number</th>
	<th>Access to loans</th>
	<th>Frequency</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="warning">
	<td>1</td>
	<td>Yes</td>
	<td>486</td>
	</tr>';
	}
?>
</tbody>
</table>
</div>

</div>
</div>



<!--end of row for method of harvest-->


<!-- row for disaster and number of farmers based on gender-->
<div class="row">
<div class="col-sm-12 col-md-6 col-lg-6">
<h4 class="vas">Number of farmers based on gender</h4>
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="info">
	<th>Gender</th>
	<th>Frequency (N=)</th>
	<th>Percentage</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="warning">
	<td>Females</td>
	<td>1200</td>
	<td>67</td>
	</tr>';
	}
?>
</tbody>
</table>
</div>
</div>

<div class="col-sm-12 col-md-6 col-lg-6">
<h4 class="vas">Type  of disaster that affects crops</h4>
<div class="table-responsive">
<table class="table table-striped thead-fixed table-bordered">
<thead>
<tr class="info">
	<th>Number</th>
	<th>Disaster</th>
	<th>Fx</th>
	<th>%ge</th>
	</tr>
</thead>
<tbody>
<?php
	for($i =0; $i<=5; $i++)
	{
	echo '<tr class="warning">
	<td>1</td>
	<td>Droughr</td>
	<td>700</td>
	<td>32.4</td>
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

