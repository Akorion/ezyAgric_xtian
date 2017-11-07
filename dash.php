<?php
session_start();
$client_id = $_SESSION['client_id'];

if (($_SESSION['client_id'] == 2) || ($_SESSION["account_name"] == 'demo') || $_SESSION['client_id'] == 15 || $_SESSION["account_name"] == "Rushere SACCO") {
    //$util_obj-
    header("Location: dash1.php");
} else {
    session_abort();
//    header("Location: dash.php");
}
?>
<?php include("include/header_client.php"); ?>
<style type="text/css">
    .content h2 {
        color: #ffffff;
        text-align: center;
        background: teal;
        margin-top: -.01em;
        width: 100%;
        font-size: 1em;
        border: 1px #eee solid;
        text-transform: capitalize;
        padding: 6px;
    }

    /*.content h2:nth-child(3) {
      color: #999;
      font-size: 3.2em !important;
      margin: 10px 0 !important;
      padding-left: 15px;
      font-weight: lighter;
  }*/

    .content h4:nth-child(3) {
        color: #666;
        font-size: 1.2em;
        margin: 10px 0 !important;
        padding-left: 15px;
        font-weight: lighter;
    }

    .content h4:nth-child(2) {
        color: #666;
        font-size: 1.2em;
        margin: 10px 0 !important;
        padding-left: 15px;
        font-weight: lighter;
    }

    #dataset .thumbnail {
        height: 100px;
        width: 100%;
    }

    body.nav-md .container.body .right_col {
        width: 100%;
        padding-right: 0
    }

    .right_col {
        padding: 10px !important;
    }

    .navbar-right {
        margin-right: 0;
    }

    .tile {
        border-radius: 6px;
    }

    .x_panel {
        position: relative;
        width: 100%;
        margin-bottom: 10px;
        padding: 10px 17px;
        display: inline-block;
        background: #fff;
        border: 1px solid #E6E9ED;
        -webkit-column-break-inside: avoid;
        -moz-column-break-inside: avoid;
        column-break-inside: avoid;
        opacity: 1;
        -moz-transition: all .2s ease;
        -o-transition: all .2s ease;
        -webkit-transition: all .2s ease;
        -ms-transition: all .2s ease;
        transition: all .2s ease;
    }

    .x_title {
        border-bottom: 2px solid #E6E9ED;
        padding: 1px 5px 6px;
        margin-bottom: 10px;
    }

    .x_titles {
        border-bottom: 2px solid #E6E9ED;
        padding: 1px 5px 6px;

        margin-bottom: 10px;
        margin-left: 13px;
        width: 98%;
    }

    .x_title .filter {
        width: 40%;
        float: right;
    }

    .x_title h2 {
        margin: 5px 0 6px;
        float: left;
        display: block;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }

    .x_title h2 small {
        margin-left: 10px;
    }

    .x_title span {
        color: #BDBDBD;
    }

    .x_content {
        padding: 0 5px 6px;
        position: relative;
        width: 100%;
        float: left;
        clear: both;
        margin-top: 5px;
    }

    .x_content h4 {
        font-size: 16px;
        font-weight: 500;
    }

    legend {
        padding-bottom: 7px;
    }

    .modal-title {
        margin: 0;
        line-height: 1.42857143;
    }

    .demo-placeholder {
        height: 280px;
    }

    /*#dataset h6{
      font-size: 1.1em;
      text-align: center;
    }
  #dataset h2{
     padding: 6px;
  }
  */
    #dataset {
        background: #ffffff;
        padding-top: 15px;
    }

    #data_2 {
        margin-top: -4.5em;
    }

    .move_data {
        margin-left: 2em !important;
    }
</style>

<?php include "include/breadcrumb.php" ?>
<div class="container-fluid">
    <div class="col-md-12 col-sm-4 col-xs-12">
        <div id="outgrower_dash" class="row">


        </div>
    </div>
</div>


<!--
<div class="container down">

<div id="dataset" class="row">
  <div class="col-sm-6 col-md-2 col-lg-2">
  <div class="thumbnail card" id="cardc">
  <div class="content">
  <h2>Total Seeds</h2>
  <h6>300 KGs<span></span></h6>

      </div>
    </div>
  </div>
    <div class="col-sm-6 col-md-2 col-lg-2">
  <div class="thumbnail card" id="cardc">
  <div class="content">
  <h2>Total Fertiliser</h2>
  <h6>300 KGs<span></span></h6>

      </div>
    </div>
  </div>
    <div class="col-sm-6 col-md-2 col-lg-2">
  <div class="thumbnail card" id="cardc">
  <div class="content">
  <h2>Total Cash Given</h2>
  <h6>3000,000 Ugx<span></span></h6>

      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-2 col-lg-2">
  <div class="thumbnail card" id="cardc">
  <div class="content">
  <h2>Total Acrage Ploughed</h2>
  <h6>300 Acres<span></span></h6>

      </div>
    </div>
  </div>

   <div class="col-sm-6 col-md-2 col-lg-2">
  <div class="thumbnail card" id="cardc">
  <div class="content">

  <h2>Total Commission</h2>
  <h4>Profiling:30m</h4>
  <h4>Produce: 6m</h4>
      </div>
    </div>
  </div>

     <div class="col-sm-6 col-md-2 col-lg-2">
  <div class="thumbnail card" id="cardc">
  <div class="content">
  <h2>Total Cash Given</h2>
  <h6>3000,000 Ugx<span></span></h6>

      </div>
    </div>
  </div>
</div>
<!-- second row-->

<!-- <div id="dataset" class="row second_row">

</div> -->


<?php
include("include/preloader.php");
//include("include/empty.php");

?>


<!--
<div class="col-md-3 col-sm-4 col-xs-12">
            <div class="x_panel tile fixed_height_400">
              <div class="x_title">
                <h2>Learning Progress</h2>
                <ul class="nav navbar-right">
                  <a><i class="fa fa-question-circle" style="font-size:17px;"></i></a>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
               <div class="col-xs-5">

                   <span class="chart" id="chart_learning" data-percent="16">
                      <span class="percent" ></span>
                    </span>
               </div>
                  <div class="col-xs-7">
                      <h4 style="margin:0px;"><b>Beginner</b></h4>
                      <p>Your Estimated Learning progress.</p>
                      <a class="btn btn-xs btn-success">Take A Tour</a>
               </div>
              </div>
            </div>

          </div>-->


<div class="container-fluid" id="data_2" style="margin-bottom:2em;">
    <div class="col-md-12 col-sm-4 col-xs-12">
        <div id="dataset_holder" class="row">

        </div>

        <div id="hidden_form_container">
        </div>

    </div>
</div>


<?php include("include/footer_client.php"); ?>
<script type="text/javascript">


    $(document).ready(function () {
//        getLoadOutGrowerDash();
//        hideProgressBar();

        getLoadDataSets();

    });

    $("#search_holder").on("input", function (e) {
        getLoadDataSets();
    });
    function getLoadDataSets() {
//        showProgressBar();
        hideProgressBar();

        //showempty();
//        var search_id = document.getElementById("search_holder");
//        search = search_id.value;
        $.ajax({
            type: "POST",
            url: "form_actions/loadDashBoard.php",
            data: {search: "farmer"},
            success: function (data) {
                $("#dataset_holder").html(data);
                hideProgressBar();
                console.log(data);

            }
        });

    }

    function getLoadOutGrowerDash() {

        token = "outgrower_dash";
        $.ajax({
            type: "POST",
            url: "form_actions/recordOutgrower.php",
            data: {token: token},
            success: function (data) {
                $("#outgrower_dash").html(data);

            }
        });

    }
    //outgrower_dash
    function Export2Csv(id) {


        var theForm, newInput1;
        // Start by creating a <form>
        theForm = document.createElement('form');
        theForm.action = 'export_dataset2csv.php';
        theForm.method = 'post';
        // Next create the <input>s in the form and give them names and values
        newInput1 = document.createElement('input');
        newInput1.type = 'hidden';
        newInput1.name = 'id';
        newInput1.value = id;


        // Now put everything together...
        theForm.appendChild(newInput1);

        // ...and it to the DOM...
        document.getElementById('hidden_form_container').appendChild(theForm);
        // ...and submit it
        theForm.submit();


    }
    function showProgressBar() {
        $(".cssload-container").show();
    }
    function hideProgressBar() {
        $(".cssload-container").hide();
    }
    function showempty() {
        $("#empty-message").show();
    }
    function hideempty() {
        $("#empty-message").hide();
    }

</script>
