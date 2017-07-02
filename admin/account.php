<div class="container-fluid">
<div class="row">
<?php include("../include/sidebar.php");?>
<!--end of sidenav-->
<!-- main content area-->
<div class="col-sm-12 col-md-9 col-lg-9">
<div class="row">
<h3 class="" style="color:#e91e63; padding-left:85px;">Create New  Account</h3>
<hr/>
<div class="col-sm-12 col-md-6 col-lg-6" style="margin-top:15px;">
<h4 class="text-center">Client information</h4>
<form  action="../form_actions/create_account.php" method="Post">
      <div class="form-horizontal" >
      <div class="form-group">
      <label for="fname" class="col-sm-3 control-label">Full Name:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="text" name="c_name" class="form-control" id="fname" placeholder="Full Name">
      </div>
    </div>
    <div class="form-group">
      <label for="address" class="col-sm-3 control-label">Physical address:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="text" name="c_address" class="form-control" id="addr" placeholder="Physical address">
      </div>
    </div>

    <div class="form-group">
      <label for="email" class="col-sm-3 control-label">Email address:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="email" name="c_email" class="form-control" id="addr" placeholder="Email address">
      </div>
    </div>

    <div class="form-group">
      <label for="email" class="col-sm-3 control-label">Value Chain:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="email" name="c_valuechain"class="form-control" id="addr" placeholder="Value Chain">
      </div>
    </div>

    <div class="form-group">
      <label for="password" class="col-sm-3 control-label">Password:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="password" name="password" class="form-control" id="addr" placeholder="Password">
      </div>
    </div>

    <div class="form-group">
      <label for="password" class="col-sm-3 control-label">Confirm Password:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="password" name="c_password" class="form-control" id="addr" placeholder="Confirm-Password">
      </div>
    </div>
  <div class="form-group">
      <div class="col-sm-12 col-md-6 col-lg-6 center">
        <input type="submit" value="Create Account" class="btn btn-large btn-primary btn-block">
      </div>
    </div>
  </div>
  </div>
  <!--contact personel-->

  <div class="col-sm-12 col-md-6 col-lg-6" style="margin-top:15px;">
  <h4 class="text-center">Contact Personel information</h4>
    <div class="form-horizontal">
    <div class="form-group">
      <label for="fname" class="col-sm-3 control-label">Full Name:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="text" name="contact_name" class="form-control" placeholder="Full Name">
      </div>
    </div>
    <div class="form-group">
      <label for="address" class="col-sm-3 control-label">Number:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="phone" class="form-control" name="contact_phone"  placeholder="Telephone">
      </div>
    </div>

    <div class="form-group">
      <label for="email" class="col-sm-3 control-label">Email address:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="email" class="form-control" name="contact_email"  placeholder="Email address">
      </div>
    </div>
    <div class="form-group">
      <label for="email" class="col-sm-3 control-label">Office Location:</label>
      <div class="col-sm-12 col-md-6 col-lg-6">
        <input type="email" class="form-control" name="contact_office"  placeholder="Office Location">
      </div>
    </div>
    </div>
</form>
</div>
</div>
<!--end of main content-->
</div>
</div>
<!--end of main container-->
