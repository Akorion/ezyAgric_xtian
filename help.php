
<?php include("include/header_client.php");?>
<style type="text/css">

    h4
    {
        font-size: 14px;
        font-weight: 100;
        line-height: 2em;
    }
    h3
    {
        background-color: #009688;
        color: white;
    }
    #arrow
    {
        margin-top: 5px;
        font-size: 45px;
    }
</style>
<div class="container down" style="background-color:#fff;">
<h1 class="text-center">ezyagric_v2 Help</h1>
<div class="row">
<div class="col-sm-3">
	<img src="images/data.png" class="img-responsive" style="margin-top:18px;">
</div>
    <div class="col-md-1" id="arrow">&rarr;</div>
<div class="col-md-8">
	<h3>My Datasets</h3>
	<h4>&raquo; click on export to csv button to export the entire dataset in csv format</h4>
	<h4>&raquo;click on view more button to view all farmers in that datatset and region</h4>
	<h4>&raquo;On each farmer there is a view details button which allows you to view individual farmer details. </h4>
	<h4>&raquo;From here you can print the details by hitting the print button</h4>
</div>
    </div>

<div class="row">
    <div class="col-md-12">
        <h3>Filter Bar</h3>
        <img src="images/filter.png" class="img-responsive">
    </div>
        
	<div class="col-sm-12 col-md-12 col-lg-12">
	
	
		<h4> &raquo; Location data filtering shows only those farmers in the selected location for example when you select district such as mayuge
		, you will be presented with only those farmers in mayuge district. You can filter further to view farmers in sub-county, 
		parish and villags in that particular district that you chose.</h4>
		<h4>&raquo; Production data filter allows you to view farmers who use certain farming methods and  other stuffs, 
		for example if a farmer uses chemicals</h4>
		<h4>&raquo; Gender will enable you to view farmers of a specific sex for example male or female</h4>
		<h4>&raquo; To effect your filters you must click on the filter button</h4>
		<h4>&raquo; To export data to csv format, hit on the export to csv button</h4>

	</div>
</div>

</div>

<?php include("include/footer_client.php");?>