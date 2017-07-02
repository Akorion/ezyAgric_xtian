<div class="container-fluid">
<div class="row">
<?php include("../include/sidebar.php");?>
<!--end of sidenav-->
<!-- main content area-->
<div class="col-sm-12 col-md-9 col-lg-9 data">
<h3 class="text-center" style="color:#e91e63;">Clients</h3>
<h6 class="text-left">Total Clients:</h6><p>1000</p>
<div class="row dash">
<div class="col-sm-12 col-md-12 col-lg-12">
<div class="table-responsive">
<table class="table table-striped">
<thead>
<tr class="success">
	<th>Value chain</th>
	<th>Number</th>
	<th>Percent (%)</th>
	</tr>
</thead>
<tbody>

<?php for($n=0;$n<=10;$n++)
{

echo '
<tr class="danger">
<td>Exporter</td>
<td>350</td>
<td>20</td>

</tr>';

}

?>
</tbody>
	
</table>
</div>

</div>
</div>

<!--end of main content-->


</div>
</div>
<!--end of main container-->

<!--modal window-->
    <!-- Modal HTML -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this datatset??</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>
    