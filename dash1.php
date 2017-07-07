<?php include("include/header_client.php"); ?>
<style type="text/css">
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
        min-height: 300px;
        margin-bottom: 10px;
        padding: 10px 17px;
        display: inline-block;
        background: #fff;
        border: thin solid #E6E9ED;
        border-radius: 5px;
        -webkit-column-break-inside: avoid;
        -moz-column-break-inside: avoid;
        column-break-inside: avoid;
        opacity: 1;
        -moz-transition: all .2s ease;
        -o-transition: all .2s ease;
        -webkit-transition: all .2s ease;
        -ms-transition: all .2s ease;
        transition: all .2s ease;

        -moz-box-shadow: 0 0 5px #999;
        -webkit-box-shadow: 0 0 5px #999;
        box-shadow: 0 0 5px #999;
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

    .fixed_height_400 {
        height: 200px;
    }

    #row_one .x_title {
        background-color: #ffffff;
    }

    .x_title h2:nth-child(1) {
        color: #fff;
        text-align: left;
        font-size: 12px !important;
        line-height: 12pt !important;
    }

    #row_one .content h6:nth-child(1) {
        font-size: 2em !important;
        text-align: center !important;
        font-weight: 100 !important;
        /*color: #999;*/
    }

    #row_one .content h6:nth-child(2) {
        font-size: 1em !important;
        text-align: center !important;
        color: #2196F3 !important;
        font-weight: 100 !important;
    }

    #row_one .content h6:nth-child(3) {
        font-size: 1.2em !important;
        text-align: center !important;
    }

    #row_2 .x_title {
        background-color: #99d987;
    }

    #row_2 .x_title h2 {
        /* background-color: orange;*/
        color: #fff;
        text-align: center;
        font-size: 22px !important;
    }

    #row_2 .content h6:nth-child(1) {
        font-size: 2em !important;
        text-align: center !important;
        font-weight: 100 !important;
        color: #999;
    }

    #row_2 .content h6:nth-child(2) {
        font-size: 1em !important;
        text-align: center !important;
        color: #2196F3 !important;
        font-weight: 100 !important;
    }

    #row_2 .content h6:nth-child(3) {
        font-size: 1.2em !important;
        text-align: center !important;
    }

    #row_3 .x_title {
        background-color: #f5b23d;
    }

    #row_3 .x_title h2 {
        color: #fff;
        text-align: center;
        font-size: 22px !important;
    }

    #row_3 .content h6:nth-child(1) {
        font-size: 2em !important;
        text-align: center !important;
        font-weight: 100 !important;
        color: #999;
    }

    #row_3 .content h6:nth-child(2) {
        font-size: 1em !important;
        text-align: center !important;
        color: #2196F3 !important;
        font-weight: 100 !important;
    }

    #row_3 .content h6:nth-child(3) {
        font-size: 1.2em !important;
        text-align: center !important;
    }


</style>
<?php include "include/breadcrumb.php" ?>
<div class="container-fluid" style="margin-bottom: 4em !important;">
    <div class=\"x_titles\">
        <h2 style="font-size:16px; padding-left:10px;">Seasons</h2>
    </div>
    <div class="row" id="row_one">
        <h1 class="text-center"><br/><br/><br/><i class="fa fa-spinner fa-spin"></i> Loading datasets...</h1>

    </div>

    <?php include("include/footer_client.php"); ?>
    <script type="text/javascript">
        $(document).ready(function () {

            getLoadDataSets();
        });

        function getLoadDataSets() {
            showProgressBar();
            //showempty();
            //var search_id = document.getElementById("search_holder");
            //search = search_id.value;
            $.ajax({
                type: "POST",
                url: "form_actions/loadSpecialDash.php",
                data: {search: ""},
                success: function (data) {
                    $("#row_one").html(data);
                    hideProgressBar();
                    //hideempty();
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