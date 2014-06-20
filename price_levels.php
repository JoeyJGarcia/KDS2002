<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Kerusso Drop Ship - Manage Price Levels</title>
    <link rel="stylesheet" href="styles.css" type="text/css"/>
    <script language="JavaScript" src="debugInfo.js"></script>
    <script language="JavaScript" src="includes/js/mustache.js"></script>
</head>

<body>
<?php
require('navigation.php');
?>
<table align=right><tr><td><div onClick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');
?>

<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText">P R I C E&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;L E V E L S</td>
    </tr>
    <tr>
        <td colspan=3 align="center" style="height:30px;"><div  class="user_message" id="user_message"></div></td>
    </tr>
</table>


<br />
<br />

<form name="priceLevelForm">

<div class="tableTitle largeFont">Price Level:  <select id="price_levels_list"></select> <button id="btnReload">Reload Data</button></div>

<div id="group_container">
</div>


</form>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script >
var PL;

$(document).ready(function(){
    getPriceLevelData()
});

$("#price_levels_list").change(function(){
    $("#group_container").html("");
    var message = "Loading new Price Level data. ";                 
    setUserMessage(message, "ajax_success");
     buildGroups();
});

$("#btnReload").click(function(){
    getPriceLevelData();
});

function getPriceLevelData(){
    var options, priceLevelData;

    priceLevelData = {
        "action":"get_price_levels"
    };

    options = {
        type: "POST",
        dataType: "json",
        url: "ajax_controller.php",
        data: priceLevelData,
        timeout: 5000,
        success: function(data, status, jqXHR){
           console.log("Status: Success, responseText:  " + jqXHR.responseText);
           PL = data;
            buildPriceLevelList();
        },
        error: function(jqXHR, textStatus, errorThrown ){
            console.log("Status: Error, responseText: " + jqXHR.responseText);
            console.log(jqXHR);
        }
    };

    $.ajax(options);
}


function buildPriceLevelList(){
    if( $("#price_levels_list")[0].options.length > 0) {
        $("#price_levels_list").html("");
    }

    for(i=1; i<= PL.price_levels; i++){
        var option = document.createElement("OPTION")
        option.value = i;
        option.text = i;
        $("#price_levels_list").append(option);
    }    

    buildGroups();
}

function buildGroups(){
    if( $("#group_container").length > 0) {
        $("#group_container").html("");
    }

    var selectedIndex, html, currPL, currPLJSON, currPLGroupCount, categoryCount, categoryRow,categoryRowJSON, rowTemplate, groupLabel, groupLabelJSON, headerRow, headerRowJSON, categoryRowsJSON;
    selectedIndex = $("#price_levels_list")[0].selectedIndex;
    currPL =  $("#price_levels_list")[0].options[selectedIndex].value;
    currPLJSON = PL["PL_"+currPL];
    currPLGroupCount =  PL["PL_"+currPL].groups;

    //Render group title and header rows
    for(g=1; g<= currPLGroupCount; g++){
        html = "<div class=\"table800\" id=\"category_container\"></div>"
         $("#group_container").append(html);

        groupLabel = "<div class=\"tableTitle groupLabel\">Group:  {{group_title}}</div>";
        groupLabelJSON = currPLJSON["GRP_" + g];
        html = Mustache.to_html(groupLabel, groupLabelJSON);
        $("#category_container").append(html);

 
        headerRowJSON = {};
        headerRow = "<div class=\"tr tableHeader\"><div class=\"th\">Price&nbsp;Level</div><div class=\"th\">Category</div><div class=\"th\">Price</div><div class=\"th colspan2\">Description</div> </div>"
        var html = Mustache.to_html(headerRow, headerRowJSON);
        $("#category_container").append(html);

        //Render category rows
        categoryCount =  groupLabelJSON.categories;
        for(c=1; c<=categoryCount; c++ ){
            categoryRow = "<div class=\"tr tableRowColorOdd\"><div class=\"th center\">"+currPL+"</div><div class=\"th center\">{{category}}</div><div class=\"th\">$<input type=\"text\" value=\"{{price}}\" size=\"5\" data-table_id=\"{{table_id}}\" data-column_name=\"price\" ></div><div class=\"th colspan2\">{{description}}</div></div>";
            categoryRowJSON = groupLabelJSON["sort_" + c];
            var html = Mustache.to_html(categoryRow, categoryRowJSON);
            $("#category_container").append(html);
        }

    }

    bindPriceFields();
}

function bindPriceFields(){
    //Bind to price text boxes
    $("input[type=text]").change(function( el ){
        if(  parseInt(el.target.value) ){
            updatePriceLevelPrice (el);
        }else{
            var message = "Only Numbers allowed! ";
            setUserMessage(message, "ajax_fail");
        }
    });
}

function updatePriceLevelPrice(priceElement){
    var options, priceData = {};

    priceData.table_id = priceElement.target.dataset.table_id;
    priceData.column_name = priceElement.target.dataset.column_name;
    priceData.value = priceElement.target.value;
    priceData.action = "update_price_level_price";

    options = {
        type: "POST",
        dataType: "json",
        url: "ajax_controller.php",
        data: priceData,
        success: function(data, status, jqXHR){
            console.log("Status: " + data.status);
            console.log("Return Status: " + data.return_status);
            var message = "Updated Price Level field successfully. ";                 
            setUserMessage(message, "ajax_success");
        },
        error: function(jqXHR, textStatus, errorThrown ){
            console.log("Status: Error: " + jqXHR.responseText);
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            var message = "Update attempt failed ";
            setUserMessage(message, "ajax_fail");
        }
    };

    $.ajax(options);
}

function setUserMessage(msg, status){
    $("#user_message").removeClass(status);
    $("#user_message").html(msg);
    $("#user_message").css("display","block");
    $("#user_message").addClass(status);
    $("#user_message").fadeOut(5000, "swing", function(){
        $("#user_message").html("");
        $("#user_message").css("display","block");
        $("#user_message").removeClass(status);
    });
}


</script>
</body>
</html>
