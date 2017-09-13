<?php //include("./include/header_client.php"); ?>
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
    .graph_box{
        padding: 10px; -moz-box-shadow: 0 0 5px #999; -webkit-box-shadow: 0 0 5px #999; box-shadow: 0 0 5px #999;
    }
</style>

<?php include "./include/breadcrumb.php" ?>

<div class="h4 container" style="padding-top: 1.5em;">Cooperatives</div>
<!--<div id="topfriends">-->
<!--    <h3>Top 4 Most Friendable Friends</h3>-->
<!--    <ol id="top4list">-->
<!--        <li>Brady</li>-->
<!--        <li>Graham</li>-->
<!--        <li>Josh</li>-->
<!--        <li>Sean</li>-->
<!--    </ol>-->
<!--</div>-->

<div class="container-fluid">

    <div class="col-md-12 col-sm-4 col-lg-4">
        <div id="coopl">
            <div id="acpcu_cooperatives_list" class="col-lg-12 row" style='height:400px; padding-top: 10px; overflow-y: auto; overflow-x: hidden;'>

            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="col-md-12"
             style="box-shadow: 2px 2px 2px 2px lightgray; height: 400px; overflow-y: auto; overflow-x: hidden;">
            <div id="cop_farmers" class="col-lg-12 row" style=" height:300px; padding-top: 10px;">
            </div>
        </div>
    </div>

</div>
<?php
include("include/preloader.php");
//include("include/empty.php");
?>
<div>
    <br>
    <br>
</div>

<?php include("include/footer_client.php"); ?>


<script type="text/javascript">

    $(document).ready(function () {
        getLoadOutGrowerDash();
        getAjaxData();
        FormGenderGraph('Baziriyo');


//            $('#dt_example').DataTable();
//        hideProgressBar();
//        $('#dt_example').DataTable();
    });

    $("#search_holder").on("input", function (e) {
        getLoadDataSets();
    });

    //---------------------------------------- allowing the list click farmers ------------------
    function doStuff() {
        var lis = document.getElementById("acpcu_cooperatives_list").getElementsByTagName('span');
        for (var i = 0; i < lis.length; i++) {
            lis[i].addEventListener('click', consoleCop, false);
        }
    }
    //---------------------------------------- setting the cooperative to check farmers ------------------
    function consoleCop() {
        console.log(this.innerHTML);
        FormGenderGraph(this.innerHTML);
    }

    function FormGenderGraph(cooperative) {

        token = "farmers_cop_table";
        condtn = cooperative;
//        console.log(cooperative);
        $.ajax({
            type: "POST",
            url: "form_actions/loadCharts.php",
            data: {token: token, coop: condtn},
            success: function (data) {
                $("#cop_farmers").html(data);


                    $('#dt_example').DataTable();
//                console.log(data);
            }

        });

        hideProgressBar();

    }

    //-------------------------getting ajax data directly-- trial
    function getAjaxData() {
        token = "get_cooperative_list";
        $.ajax({
            type: "POST",
            url: "form_actions/loadCharts.php",
            data: {token: token},
            success: function (data) {
                $("#acpcu_cooperatives_list").html(data);
//                console.log(data);
            }

        });

    }



    function getLoadOutGrowerDash() {
        token = "acpcu_cooperatives";
        $.ajax({
            type: "POST",
            url: "form_actions/recordOutgrower.php",
            data: {token: token},
            success: function (data) {
                $("#acpcu_cooperatives").html(data);

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