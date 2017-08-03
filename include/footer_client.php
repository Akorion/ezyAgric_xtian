
<!--this modal displays an image from url : used on receipts  -->
<div class="modal fade" id="img-dialog">
        <div class="modal-dialog">
            <div class="modal-body" style="background:white; padding:20px">
             <img alt='cant find preview!' style="width:100%;"/>
            </div>
        </div>

    </div>
 		<footer>
            
            <p>&copy; <?php echo date("Y");?>   EZYAGRIC</p> &nbsp; &nbsp; | &nbsp;&nbsp;
            <p><a href="#">Terms and conditions</a></p> &nbsp; &nbsp;  &nbsp;&nbsp;<p>Powered by: <a href="https://www.akorion.com">Akorion Co LTD</p></a>
        </footer>
<!--<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
<script>
    var buyerData = {
        labels : ["January","February","March","April","May","June"],
        datasets : [
            {
                fillColor : "rgba(172,194,132,0.4)",
                strokeColor : "#ACC26D",
                pointColor : "#fff",
                pointStrokeColor : "#9DB86D",
                data : [203,156,99,251,305,247]
            }
        ]
    };

    var buyers = document.getElementById('buyers').getContext('2d');
    new Chart(buyers).Line(buyerData);
</script>

<script src="js/jquery-2.1.1.min.js"></script>
 <script type="text/javascript" src="moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/material.min.js"></script>
<script type="text/javascript" src="js/sweetalert.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
 <script src="js/raphael-min.js"></script>
  <script src="js/morris.min.js"></script>
  <script src="js/ajax-upload.js"></script>
  <script src="datepicker/js/bootstrap-datepicker.js"></script>
  <!-- HighCharts  -->
   <script src="js/highcharts/highcharts.js"></script>
   <script src="js/highcharts/exporting.js"></script>
   <script type="text/javascript" src="daterangepicker.js"></script>


   <!-- Select2 -->
    <script src="js/select2/dist/js/select2.full.min.js"></script>

    <script type="text/javascript">
$(function() {
    $('input[name="daterange"]').daterangepicker();
});
</script>

	<!-- Select2 -->
    <script>
      $(document).ready(function() {
        
		$(".select2_single").select2({
          placeholder: "Make a selection",
          allowClear: false
        });
		
        
      });
    </script>
    <!-- /Select2 -->
   



<script type="text/javascript">
	$('#example-getting-started').multiselect();
	$.material.init();
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#changepassword').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            oldpass: {
                validators: {
                    notEmpty: {
                        message: 'The old password is required'
                    },
                    stringLength: {
                        min: 6,
                        max: 25,
                        message: 'The password must be more than 6 and less than 25 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'The password can only consist of alphabetical, number and underscore'
                    }
                }
            },
            newpass: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    }
                }
            }

            confirm: {
                validators: {
                    notEmpty: {
                        message: 'The must match is required'
                    }
                }
            }
        }
    });
});
</script>
</body>
</html>

    


<!--modal  window-->
    <!-- Modal HTML -->
    <div id="changepassword" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <div class="modal-body">
                  <!-- <form id="changepassword" method="" action="">-->
    			<div class="form-group">
        		<input id="old_pswd" type="password" class="form-control" name="oldpass" placeholder="old password" />
   				 </div>
    			<div class="form-group">
        		<input id="new_pswd" type="password" class="form-control" name="newpass" placeholder="new Password" />
    			</div>
    			<div class="form-group">
        		<input id="conf_new_pswd"  type="password" class="form-control" name="confirm" data-match="#inputPassword" placeholder="Confirm Password" />
    			</div>
   
				<!--</form>-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button onclick="changePassword();"type="button" class="btn btn-danger" data-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>
	
<script type="text/javascript">

function changePassword(){
  
	var old_pass_holder = document.getElementById("old_pswd");
	old_pass = old_pass_holder.value;
    var new_pass_holder = document.getElementById("new_pswd");
	new_pass = new_pass_holder.value;
    var conf_pass_holder = document.getElementById("conf_new_pswd");
	conf_pass = conf_pass_holder.value;	
	$.ajax({
     type: "POST",
     url: "form_actions/change_cleint_pswd.php",
     data: {old_pass: old_pass,new_pass: new_pass,conf_pass : conf_pass},
     success: function(data) {
		  //$("#dataset_holder").html(data);
		
		  switch(data){
		
		  case '-3':
		    window.alert("Fill in all fields !!!");
			old_pass_holder.value="";
			new_pass_holder.value="";
			conf_pass_holder.value="";
		  break;
		  case '-2':
		   window.alert("New Password didnt match confirm password");
		    old_pass_holder.value="";
			new_pass_holder.value="";
			conf_pass_holder.value="";
		  break;
		  case '-1':
		    window.alert("Something went wrong");
		    old_pass_holder.value="";
			new_pass_holder.value="";
			conf_pass_holder.value="";
		  break;
		  case '0':
		    window.alert("Old password didnt match your input");
		    old_pass_holder.value="";
			new_pass_holder.value="";
			conf_pass_holder.value="";
		  break;
		  case '1':
		    window.alert("Your password has been successfully changed");
		    old_pass_holder.value="";
			new_pass_holder.value="";
			conf_pass_holder.value="";
		  break;
		  
		  }
    }
    });

}

function addItem(){
    var iname = $('#iname').val();
    var itype = $('#itype').val();
    var uname = $('#uname').val();
    var upacre = $('#upacre').val();
    var uprice = $('#uprice').val();
    var cpu = $('#cpu').val();

    $.post("include/ajax.php", {iname:iname, itype:itype, uname:uname, upacre:upacre, uprice:uprice, cpu:cpu}, function(response){
        alert(response);
    });

}
</script>

 <script type="text/javascript">
    
   $(".open-image").click(function(){
            
            var src = $(this).attr('data-target');
            openImage(src);
        })
    function openImage(imgSrc) {

      var d = $("#img-dialog");
        d.modal("show");
        d.find("img").attr("src", "");
        d.find("img").attr("src", imgSrc);

    }


</script> 