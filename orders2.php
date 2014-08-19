<?php
require('includes/application_top.php');

if( isset($_GET['action']) ){
	$action = $_GET['action'];
}

if( isset($_POST['order_size']) ){
	$order_size = $_POST['order_size'];
}

if( isset($_POST['order_comments']) ){
	$order_comments = $_POST['order_comments'];
}

if( isset($_POST['product_size_']) ){
	$product_size_ = $_POST['product_size_'];
}

if( isset($_POST['product_name_']) ){
	$product_name_ = $_POST['product_name_'];
}

if( isset($_POST['product_quantity_']) ){
	$product_quantity_ = $_POST['product_quantity_'];
}

if( isset($_POST['isRush']) ){
	$isRush = $_POST['isRush'];
}

if( isset($_POST['customer_order_no']) ){
	$customer_order_no = $_POST['customer_order_no'];
}

if( isset($_POST['shipping_method']) ){
	$shipping_method = $_POST['shipping_method'];
}

if( isset($_POST['customer_country']) ){
	$customer_country = $_POST['customer_country'];
}

if( isset($_POST['customer_zip']) ){
	$customer_zip = $_POST['customer_zip'];
}

if( isset($_POST['customer_state']) ){
	$customer_state = $_POST['customer_state'];
}

if( isset($_POST['customer_city']) ){
	$customer_city = $_POST['customer_city'];
}

if( isset($_POST['customer_address2']) ){
	$customer_address2 = $_POST['customer_address2'];
}

if( isset($_POST['customer_address1']) ){
	$customer_address1 = $_POST['customer_address1'];
}

if( isset($_POST['customer_intl_phone']) ){
	$customer_intl_phone = $_POST['customer_intl_phone'];
}

if( isset($_POST['customer_name']) ){
	$customer_name = $_POST['customer_name'];
}

if( isset($_POST['purchase_order_number']) ){
	$purchase_order_number = $_POST['purchase_order_number'];
}

if( isset($_POST['accounts_number']) ){
	$accounts_number = $_POST['accounts_number'];
}

if( isset($_POST['rep1_name']) ){
	$rep1_name = $_POST['rep1_name'];
}

if( isset($_POST['rep1_code']) ){
	$rep1_code = $_POST['rep1_code'];
}

if( isset($_POST['rep2_name']) ){
	$rep2_name = $_POST['rep2_name'];
}

if( isset($_POST['rep2_code']) ){
	$rep2_code = $_POST['rep2_code'];
}

if( isset($_POST['rep3_name']) ){
	$rep3_name = $_POST['rep3_name'];
}

if( isset($_POST['rep3_code']) ){
	$rep3_code = $_POST['rep3_code'];
}

if( isset($_POST['rep4_name']) ){
	$rep4_name = $_POST['rep4_name'];
}

if( isset($_POST['rep4_code']) ){
	$rep4_code = $_POST['rep4_code'];
}

if( isset($_POST['rep5_name']) ){
	$rep5_name = $_POST['rep5_name'];
}

if( isset($_POST['rep5_code']) ){
	$rep5_code = $_POST['rep5_code'];
}

if( isset($_POST['rep6_name']) ){
	$rep6_name = $_POST['rep6_name'];
}

if( isset($_POST['rep6_code']) ){
	$rep6_code = $_POST['rep6_code'];
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<head>
  <title>Kerusso Drop Ship - Order Entry</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
  <script language="JavaScript" src="debugInfo.js"></script>
</head>

<style type="text/css">
	@import "includes/js/dojoroot/dijit/themes/tundra/tundra.css";
	@import "includes/js/dojoroot/dojo/resources/dojo.css"
</style>

<script type="text/javascript"
	src="includes/js/dojoroot/dojo/dojo.js"
	djConfig="parseOnLoad: true">
</script>
<script type="text/javascript" 	src="includes/js/jquery_latest.js"></script>

<script type="text/javascript">
    dojo.require("dojo._base.xhr");
</script>


<script type="text/javascript" >
var commentLimit = 200;
$(document).ready(function(){
	$("#commentsArea").keypress(countChars);
	$("#charsLimit").html(commentLimit);
	$("#charsLeft").html(commentLimit);
});

function countChars(){
	var charsLeft = commentLimit - $("#commentsArea")[0].value.length;
	
	if(charsLeft < 0){
		$("#commentsArea")[0].value = $("#commentsArea")[0].value.substring(0,commentLimit);
	}else if(charsLeft < 20){
		$("#charsLeft").html(charsLeft);
		$("#charsLeft").css("color","#ff0000");
		$("#charsLeft").css("font-weight","bold");
	}else{
		$("#charsLeft").html(charsLeft);
		$("#charsLeft").css("color","#000000");
	}

}

function isNumber(value){
	var isNumber = true;
	var validNumbers = "9876543210";
	for(var i=0; i<value.length; i++){
		if(validNumbers.indexOf(value.charAt(i)) == -1){
			isNumber = false;
			break;
		}
	}

	return isNumber;
}

function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}


function setOnHand(){
	var sizeText = null;
	var productCode = arguments[1];
	var index = arguments[0];
	if(arguments.length == 3){
		sizeText = arguments[2];
	}else{
		var sizeNode = document.forms[0].elements['product_size_'+index];
		sizeText = sizeNode[sizeNode.selectedIndex].text;
	}
	var spanOnhandId = "onhand_"+index;


dojo.xhrGet({
    <?php if($_SERVER['SERVER_NAME'] == 'localhost') {?>
		url:"http://localhost/kds/ajax_controller.php?action=get_onhand&size="+sizeText+"&pCode="+productCode+"&kdssid=<?php echo $_GET['kdssid'];?>",
    <?php }else{ ?>
		url:"http://www.kerussods.com/ajax_controller.php?action=get_onhand&size="+sizeText+"&pCode="+productCode+"&kdssid=<?php echo $_GET['kdssid'];?>",
    <?php } ?>

		handleAs: "text",//

		content:{"spanOnhandId":spanOnhandId},

		load:function(response, ioArgs){

		var onhandNode = dojo.byId(spanOnhandId);
		var quantity = (parseInt(trim(response)) < 20)? "<font color='red'>"+trim(response)+"</font>" : trim(response);

		onhandNode.innerHTML=quantity;
	},

	error: function(response){
		dojo.byId("statusMsg").innerHTML =
		"An error occurred, with response: " + response;
		alert(response);
	}

	});

}

function setSize(formElement){

var selectIndex = formElement.selectedIndex;
if (selectIndex == 0 ) return;

var endLoc = formElement.options[selectIndex].text.indexOf("/");
var product_code = trim(formElement.options[selectIndex].text.substring(0,endLoc));
var index = formElement.name.substring(formElement.name.lastIndexOf("_")+1);
var spanOnhandId = "onhand_"+index;
dojo.byId(spanOnhandId).innerHTML = "";

dojo.xhrGet({
    <?php if($_SERVER['SERVER_NAME'] == 'localhost') {?>
		url:"http://localhost/kds/ajax_controller.php?action=get_sizes&pCode="+product_code+"&kdssid=<?php echo $_GET['kdssid'];?>",
    <?php }else{ ?>
		url:"http://www.kerussods.com/ajax_controller.php?action=get_sizes&pCode="+product_code+"&kdssid=<?php echo $_GET['kdssid'];?>",
    <?php } ?>

		handleAs: "json",// IMPORTANT: tells Dojo to automatically parse the HTTP response into a JSON object

		content:{"productName":formElement.name, "product_code":product_code,"index":index},

		load:function(request, ioArgs){

		var selSizeName = ioArgs.args.content.productName.replace("name","size");
		var selProductName = ioArgs.args.content.productName;
		var selSizes = dojo.byId(selSizeName);
		selSizes.disabled=false;
//		selSizes.setAttribute("onChange","setOnHand('"+ioArgs.args.content.index+"','"+ioArgs.args.content.product_code+"')");
		//Added to make work in IE
		selSizes.onchange=function(){setOnHand(ioArgs.args.content.index,ioArgs.args.content.product_code);};


		for(i=selSizes.options.length; i>0; i--){
			selSizes.remove(i-1);
		}

		if(request.arrSizes.length > 1){
			var sizeDefaultOption = document.createElement("OPTION");
			sizeDefaultOption.value = "0";
			sizeDefaultOption.text = "Select Size";
			try
			{
				selSizes.add(sizeDefaultOption); //IE
			}catch (ex){
				selSizes.add(sizeDefaultOption, null); //Firefox et autres
			}
		}
		for(i=0; i<request.arrSizes.length; i++){

			 var currentOption = document.createElement("OPTION");
			 currentOption.setAttribute('value',request.arrSizes[i].id);
			 currentOption.text = request.arrSizes[i].name;

			try
			{
				selSizes.add(currentOption); //IE
			}catch (ex){
				selSizes.add(currentOption, null); //Firefox
			}
		}

		if(request.arrSizes.length == 1){
			setOnHand(ioArgs.args.content.index,ioArgs.args.content.product_code,"NA");//Used for NA sizes
		}

	},

	error: function(response){
		dojo.byId("statusMsg").innerHTML =
		"An error occurred, with response: " + response;
		alert(response);
	}

	});

}


function submitAddOrder(){
var orderIsReady = true;

    //International Phone Validation
	var CountrySelectedIndex = document.forms[0].customer_country.selectedIndex;
    if( document.forms[0].customer_country.options[CountrySelectedIndex].value != '1#United States' ){
		if(document.forms[0].customer_intl_phone.value.length == 0){
			alert("Missing value found for a International Phone Number.  \n Please fix field value and then re-submit.\nReminder: All International orders now require a phone number for delivery.");
			document.forms[0].customer_intl_phone.focus();
			orderIsReady = false;
			return false;
		}
    }

    //Customer Name Validation
    if( document.forms[0].customer_name.value.length == 0 ){
        alert("Missing value found for a Customer Name.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_name.focus();
        orderIsReady = false;
        return false;
    }

    //Customer Address 1 Validation
    if( document.forms[0].customer_address1.value.length == 0 ){
        alert("Missing value found for a Customer Address Information.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_address1.focus();
        orderIsReady = false;
        return false;
    }

    //Customer City Validation
    if( document.forms[0].customer_city.value.length == 0 ){
        alert("Missing value found for a Customer City.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_city.focus();
        orderIsReady = false;
        return false;
    }

    //Customer City State
    if( document.forms[0].customer_state.value.length == 0 ){
        alert("Missing value found for a Customer State.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_state.focus();
        orderIsReady = false;
        return false;
    }


    //Customer City Zip
    if( document.forms[0].customer_zip.value.length == 0 ){
        alert("Missing value found for a Customer Zip.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_zip.focus();
        orderIsReady = false;
        return false;
    }


    var orderSize = parseInt(document.forms[0].order_size.value);
    for(i=0; i<document.forms[0].elements.length; i++){

    	var elName = document.forms[0].elements[i];
    	var select = null;
    	if(document.forms[0].elements[i].type.toLowerCase().indexOf("select") > -1){
    		select = document.forms[0].elements[i];
    	}

    	if(elName.name.indexOf("product_quantity_") > -1 ||
           elName.name.indexOf("product_size_") > -1 ||
           elName.name.indexOf("product_name_") > -1 ){

			if(elName.name.indexOf("product_quantity_") > -1){
				if( elName.value.length == 0){
					alert("A product is missing a value for quantity. Order cannot be submitted yet.");
					orderIsReady = false;
					return false;
				}

				if( !isNumber(elName.value) ){
					alert("A quantity value is not a number. Order cannot be submitted yet.");
					orderIsReady = false;
					return false;
				}

				continue;
			}

        	if(select.options.selectedIndex == 0){
        		if(elName.name.toLowerCase().indexOf("product_size_") > -1 && parseInt(elName.value) == 0){
            		alert("A size is missing for one of the products. Order cannot be submitted yet.");
					orderIsReady = false;
					return false;
        		}
        		if(elName.name.toLowerCase().indexOf("product_name_") > -1){
            		alert("A product has not been selected from the list, please. Order cannot be submitted yet.");
					orderIsReady = false;
					return false;
        		}
        	}
        }
     }

    if(  document.forms[0].customer_order_no.value.length == 0 ){
        var orderOK = confirm("You don't enter a value for the Order Number.  \nIf this is ok, click OK, otherwise click Cancel.");
        if(orderOK){
            orderIsReady = true;
        }else{
            orderIsReady = false;
            document.forms[0].customer_order_no.focus();
            return false;
        }
    }

    if(orderIsReady){
    	document.forms[0].submit();
    }

}//end of submitAddOrder function

function addItemRow(){
	var prodTable = dojo.byId("productTable");
	var tbod = dojo.byId("productTable").tBodies[0];
	var newRow = document.createElement("TR");
	var tdQuantity = document.createElement("TD");
	var tdOnHand = document.createElement("TD");
	var tdSize = document.createElement("TD");
	var tdProducts = document.createElement("TD");
	var productsSelectNode = prodTable.tBodies[0].rows[0].cells[3].childNodes[0].cloneNode(true);
	var newRowNum = prodTable.rows.length-2;

	var order_size = dojo.byId("order_size");
	order_size.value = newRowNum + 1;

	productsSelectNode.setAttribute("name","product_name_"+newRowNum);
	productsSelectNode.setAttribute("id","product_name_"+newRowNum);
	tdProducts.appendChild(productsSelectNode);
	tdProducts.setAttribute("align","center");

	newRow.setAttribute("className","tableRowColor");
	newRow.setAttribute("class","tableRowColor");
	tdQuantity.setAttribute("class","mediumBoldText");
	tdQuantity.setAttribute("align","center");

	var textQuantity = document.createElement("INPUT");
	textQuantity.setAttribute("name","product_quantity_"+newRowNum);
	textQuantity.setAttribute("size","2");
	tdQuantity.appendChild(textQuantity);

	var spanOnHand = document.createElement("SPAN");
	spanOnHand.setAttribute("id","onhand_"+newRowNum);
	tdOnHand.setAttribute("align","center");
	tdOnHand.appendChild(spanOnHand);

	var selectSize = document.createElement("SELECT");
	selectSize.setAttribute("id","product_size_"+newRowNum);
	selectSize.setAttribute("name","product_size_"+newRowNum);
	selectSize.setAttribute("disabled","true");
	selectSize.setAttribute("onChange","setOnHand("+newRowNum+")");
	var sizeOption = document.createElement("OPTION");
	sizeOption.value = "0";
	sizeOption.text = "Select Size";
	selectSize.appendChild(sizeOption);
	tdSize.appendChild(selectSize);
	tdSize.setAttribute("align","center");

	newRow.appendChild(tdQuantity);
	newRow.appendChild(tdSize);
	newRow.appendChild(tdOnHand);
	newRow.appendChild(tdProducts);
	tbod.appendChild(newRow);

}
</script>

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
        <td colspan=3 align="center" class="largeBoldText">O R D E R &nbsp;&nbsp; E N T R Y</td>
    </tr>
</table>


<br />
<br />


<?php
if( !isset($action)){
//*************************************************************************
//************************ ORDER ADD FORM *********************************
//*************************************************************************

    $ord_inventory_sql = "SELECT * FROM products WHERE product_enabled = 1 ORDER BY product_model";
    $ord_inventory_query = my_db_query($ord_inventory_sql);
    $arrInventory[] = array('id' => '0','text' => 'Select Product');
    while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
        $productText =$ord_inventory['product_model']." / ".
        stripslashes(substr($ord_inventory['product_name'], 0, 25))." / ".
        stripslashes(substr(str_replace("'","",$ord_inventory['product_desc']), 0, 15));
        $arrInventory[] = array('id' => $ord_inventory['product_id'],
                          'text' => $productText);
    }


    $ord_shipping_sql = "SELECT * FROM shipping WHERE 1 ORDER BY shipping_name";
    $ord_shipping_query = my_db_query($ord_shipping_sql);
    $shipping_default = "";
    while($ord_shipping = my_db_fetch_array($ord_shipping_query)){
        $arrShipping[] = array('id' => $ord_shipping['shipping_id'],
                          'text' => $ord_shipping['shipping_name']);
        if($ord_shipping['shipping_default'] == 1) $shipping_default = $ord_shipping['shipping_id'];
    }

    $ord_countries_sql = "SELECT countries_id, countries_name, countries_iso_code_3, countries_number FROM countries order by countries_name";
    $ord_countries_query = my_db_query($ord_countries_sql);
    $countries_default = "1#United States";
    while($ord_countries = my_db_fetch_array($ord_countries_query)){
        $arrCountries[] = array('id' => $ord_countries['countries_number'] . '#' . $ord_countries['countries_name'],
                          'text' => $ord_countries['countries_name']);
    }

    $arrRepCodes = array();
    $rep_codes_sql = "SELECT * FROM rep_codes";
    $rep_codes_query = my_db_query($rep_codes_sql);
    while( $rep_codes = my_db_fetch_array($rep_codes_query) ){
        $arrRepCodes[$rep_codes['rep_name']] = $rep_codes['rep_code'];
    }

    $arrReps = array();
    $reps_sql = "SELECT * FROM reps r WHERE r.accounts_number = " . $_SESSION['client_account_number'];
    $reps_query = my_db_query($reps_sql);
    $reps = my_db_fetch_array($reps_query);

echo my_draw_form('add_order',my_href_link('orders2.php', 'action=ord_add','POST',' id="add_order"'));

$po_number = strtoupper($_SESSION['client_prefix']).date("mdyHis");
echo my_draw_hidden_field('purchase_order_number',$po_number);
echo my_draw_hidden_field('order_size','1','id=\'order_size\'');
echo my_draw_hidden_field('accounts_number',$_SESSION['client_account_number']);

echo my_draw_hidden_field('rep1_name',$reps['field_rep']);
echo my_draw_hidden_field('rep1_code',$arrRepCodes[$reps['field_rep']]);
echo my_draw_hidden_field('rep2_name',$reps['inside_rep']);
echo my_draw_hidden_field('rep2_code',$arrRepCodes[$reps['inside_rep']]);
echo my_draw_hidden_field('rep3_name',$reps['field_group']);
echo my_draw_hidden_field('rep3_code',$arrRepCodes[$reps['field_group']]);
echo my_draw_hidden_field('rep4_name',$reps['national_group']);
echo my_draw_hidden_field('rep4_code',$arrRepCodes[$reps['national_group']]);
echo my_draw_hidden_field('rep5_name',$reps['national_rep']);
echo my_draw_hidden_field('rep5_code',$arrRepCodes[$reps['national_rep']]);
echo my_draw_hidden_field('rep6_name',$reps['sales_mgr']);
echo my_draw_hidden_field('rep6_code',$arrRepCodes[$reps['sales_mgr']]);

?>


<table width=600px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
    <td class="mediumBoldText" colspan=2 align=center>S H I P P I N G  &nbsp;&nbsp; I N F O R M A T A T I O N</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Customer Name:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_name','','size=30'); ?>&nbsp;*</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Address Information:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_address1','','size=30 maxlength=30'); ?>&nbsp;*</td>
 </tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Add'l Address Information:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_address2','','size=30 maxlength=30'); ?></td>
 </tr>
<tr class="tableRowColor" >
    <td align=right class="mediumBoldText" >International Phone No.:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_intl_phone','','size=30 maxlength=30'); ?>
	</td>
 </tr>
<tr class="tableRowColor" >
    <td align=right class="mediumBoldText" ></td>
    <td align=left class="mediumBoldText" ><span style="font-size:0.75em"><span style="color:red;">NOTE:</span>  International phone numbers are now required for international orders.</span>
	</td>
 </tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >City:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_city','','size=30'); ?>&nbsp;*</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >State:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_state','','size=30'); ?>&nbsp;*</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Zip/Postal Code:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_zip','','size=30'); ?>&nbsp;*</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Country:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_pull_down_menu('customer_country',$arrCountries, $countries_default); ?>&nbsp;*</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Shipping Method:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_pull_down_menu('shipping_method',$arrShipping, $shipping_default); ?></td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Your Order Number:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_order_no','','size=30'); ?><br>
	</td>
</tr>
<tr class="tableRowColor" >
    <td align=right class="mediumBoldText" ></td>
    <td align=left class="mediumBoldText" ><span style="font-size:0.75em"><span style="color:red;">NOTE:</span> This Order Number is very important now since it will be <br>added to the PO Number on your Invoices to aid in your book keeping.</span>
	<br><br>
	</td>
 </tr>

<tr class="tableRowColor">
    <td align=center class="mediumBoldText" colspan=2 ><?php echo my_draw_checkbox_field("isRush"); ?> Check to <font color="red"><strong><em>RUSH</em></strong></font> this Order (fees will be applied)

    </td>
</tr>
</table>
<br />

<div id="statusMsg"></div>

<table width=600px align="center" border=0  class="thinOutline" cellspacing=0 id="productTable" name="productTable">
<THEAD>
<tr class="tableHeader">
<td colspan=4 class="mediumBoldText" align="center">
P R O D U C T S
</td>
</tr>

<tr class="tableRowColor">
    <td align=center class="mediumBoldText" >Quantity</td>
    <td align="center" class="mediumBoldText" >Size</td>
    <td align="center" class="mediumBoldText" >On-Hand&nbsp;</td>
    <td align="center" class="mediumBoldText" >Product Code / Name</td>
</tr>
</THEAD>
<TBODY>

<?php

$order_size = 1;

$arrSizes[] = array('id' => 0,
	                'text' => "Select size");


    for($i=0; $i<$order_size;$i++){
?>
<tr class="tableRowColor">
    <td align="center" class="mediumBoldText"><?php echo my_draw_input_field('product_quantity_'.$i,'','size=2'); ?></td>
    <td align="center"><?php echo my_draw_pull_down_menu('product_size_'.$i,$arrSizes,'','disabled=true id=\'product_size_'.$i.'\' '); ?></td>
    <td align="center"><span id="onhand_<?php echo $i;?>"></span></td>
    <td align="center" ><?php echo my_draw_pull_down_menu('product_name_'.$i,$arrInventory,'',"onChange='setSize(this)' id=\"product_name_$i\""); ?></td>
</tr>
<?php
}
?>
</TBODY>
</table>

<table width=600px align="center" border=0  cellspacing=0 cellpadding=0>
<tr><td align="right"><img src="images/btnAddProductOnWhite.gif" alt="Add Another Item To This Order" title="Add Another Item To This Order" onclick="addItemRow()" class="actLikeLink"></td></tr>
</table>




<br/><br/><br/>
<table width=600px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<td colspan=3 class="mediumBoldText" align="center">
C O M M E N T S
</td>
</tr>

<tr class="tableRowColor">
<td colspan=3 align="center">
<?php echo my_draw_textarea_field('order_comments','soft','40','5','','id="commentsArea"'); ?>
</td>
</tr>

<tr class="tableRowColor">
<td colspan=3 align="center">
<div style="margin:5px;">
	<strong>Comments Character Limit: <span id="charsLimit"></span> characters</strong> (<span id="charsLeft" style="" ></span> characters left)
</div>
</td>
</tr>

<tr  class="tableFooter">
    <td colspan="3" align="center">
        <a href="<?php echo my_href_link('orders.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo "<a href=\"#\" onClick='submitAddOrder();return false'>" .
        my_image(DIR_WS_IMAGES.'btnSubmit.gif','Submit Order'); ?></a>
    </td>
</tr>

</table>

<?php
}
// *************************************************************************
// *************************** MODIFY FORM ******************************
// *************************************************************************
if( $action == 'ord_mod_start' ){

//Intentionally not doing a modify
?>


<?php

}else{
// *************************************************************************
// ****************************** ADD ORDER ******************************
// **************************************************************************

    if( $action == 'ord_add'  ){
        //Nullable Fields
        $customer_address2 =
            ( strlen($customer_address2) > 0 )? mysql_real_escape_string($customer_address2) : "NULL" ;
        $order_comments =
            ( strlen($order_comments) > 0 )? mysql_real_escape_string($order_comments) : "NULL" ;
        $customer_order_no =
            ( strlen($customer_order_no) > 0 )? mysql_real_escape_string($customer_order_no) : "NULL" ;


        //Non-Nullable fields Check
        $order_has_required_data = true;
        $order_has_required_data = ( strlen($customer_address1) != 0 ) &&
                                    ( strlen($customer_city) != 0 ) &&
                                    ( strlen($customer_state) != 0 )  &&
                                    ( strlen($customer_zip) != 0 ) &&
                                    ( strlen($customer_country) != 0 ) &&
                                    ( strlen($customer_name) != 0 );


        for($i=0; $i<$order_size; $i++){
            $order_has_required_data =
            strlen($_POST['product_quantity_'.$i]) != 0 && $order_has_required_data;
        }



        if($order_has_required_data){
                $ord_sizes_sql = "SELECT * FROM cat_sizes WHERE 1 ORDER BY cat_sizes_sort";
                $ord_sizes_query = my_db_query($ord_sizes_sql);
                while($ord_sizes = my_db_fetch_array($ord_sizes_query)){
                    $arrSizes[$ord_sizes['cat_sizes_id']] =  $ord_sizes['cat_sizes_name'];
 //                   $arrPrices[$ord_sizes['sizes_name']] =  $ord_sizes['sizes_fee'];
                }


                $arrFees = array();
                $fees_sql = "SELECT * FROM fees ";
                $fees_query = my_db_query($fees_sql);
                while($fees = my_db_fetch_array($fees_query)){
                    $arrFees[$fees['fees_name']]= $fees['fees_value'];
                }

                $new_order_id_sql = "SELECT order_status_id FROM order_status
                WHERE order_status_name = 'New Order'";
                $new_order_id_query = my_db_query($new_order_id_sql);
                $fees = my_db_fetch_array($new_order_id_query);

                $arrShipping = array();
                $arrShippingAlias = array();
                $shipping_sql = "SELECT * FROM shipping ";
                $shipping_query = my_db_query($shipping_sql);
                while($shipping = my_db_fetch_array($shipping_query)){
                    $arrShipping[$shipping['shipping_id']]= $shipping['shipping_name'];
                    $arrShippingAlias[$shipping['shipping_id']]= $shipping['shipping_alias'];
                }


				$arrStateNames = array();
				$states_query = my_db_query("SELECT state_mapping_shortName AS ShortName,state_mapping_longName
				AS LongName FROM `state_mapping` WHERE 1");
				while($states = my_db_fetch_array($states_query) ){
					$arrStateNames[ strtoupper($states['LongName']) ] = strtoupper($states['ShortName']);
				}



                $customer_city = str_replace(",","",$customer_city);
                $arrCustomerCountry = explode("#", $customer_country);
                $customer_country_number = $arrCustomerCountry[0];
                $customer_country_name = $arrCustomerCountry[1];

                $isRush = ($isRush == "on")? 1 : 0 ;
                $rushFee = ($isRush > 0)?"5":"0";

				if($shortShippingState = array_key_exists(strtoupper(trim($customer_state)), $arrStateNames)){
					$shortShippingState = $arrStateNames[strtoupper(trim($customer_state))];
				}else{
					$shortShippingState = $customer_state;
				}


                $ord_add_sql = sprintf("INSERT INTO `orders` (`customer_name` ,
                `customer_address1` , `customer_address2`, `customer_intl_phone`
				,`customer_city` , `customer_state` ,
                `customer_zip` , `customer_country` , `customer_country_number` , `customer_shipping_method` ,
                `customer_shipping_id` ,
                `customer_invoice_number` , `purchase_date` , `accounts_number`,
                `purchase_order_number` , 
                `order_comments`, 
                `order_status`,
                `dropship_fee`,
                `handling_fee`, 
                `isRush`, 
                `rush_fee`, 
                `misc_desc`,
                `rep1_name`, 
                `rep1_code`, 
                `rep2_name`, 
                `rep2_code`, 
                `rep3_name`, 
                `rep3_code`, 
                `rep4_name`, 
                `rep4_code`, 
                `rep5_name`, 
                `rep5_code`, 
                `rep6_name`, 
                `rep6_code`) VALUES
                (%s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %d, %s, '".date("y-m-d h:i:s")."', %s, %s, %s, %d, %01.2f,%01.2f,%d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                "'".str_replace(",","",mysql_real_escape_string($customer_name))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_address1))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_address2))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_intl_phone))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_city))."'",
                "'".str_replace(",","",mysql_real_escape_string($shortShippingState))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_zip))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_country_name))."'",
                str_replace(",","",mysql_real_escape_string($customer_country_number)),
                "'".str_replace(",","",mysql_real_escape_string($arrShipping[$shipping_method]))."'",
                $shipping_method,
                "'".str_replace(",","",$customer_order_no)."'",
                "'".mysql_real_escape_string($accounts_number)."'",
                "'".mysql_real_escape_string($purchase_order_number)."'",
                "'".str_replace(",","",mysql_real_escape_string($order_comments))."'",
                $fees['order_status_id'],
                $arrFees['Drop Ship'],
                $arrFees['Handling'], 
                $isRush,
                $rushFee,
                "''",
                "'".mysql_real_escape_string($rep1_name)."'",
                "'".mysql_real_escape_string($rep1_code)."'",
                "'".mysql_real_escape_string($rep2_name)."'",
                "'".mysql_real_escape_string($rep2_code)."'",
                "'".mysql_real_escape_string($rep3_name)."'",
                "'".mysql_real_escape_string($rep3_code)."'",
                "'".mysql_real_escape_string($rep4_name)."'",
                "'".mysql_real_escape_string($rep4_code)."'",
                "'".mysql_real_escape_string($rep5_name)."'",
                "'".mysql_real_escape_string($rep5_code)."'",
                "'".mysql_real_escape_string($rep6_name)."'",
                "'".mysql_real_escape_string($rep6_code)."'" );

//echo $ord_add_sql ."<br><br>";

//echo "accounts_number: ".$accounts_number ."<br><br>";

                  my_db_query($ord_add_sql);

                  $ord_add_insert_id = my_db_insert_id( );

                  $ord_inventory_sql = "SELECT * FROM products WHERE 1
                  ORDER BY product_model";
                  $ord_inventory_query = my_db_query($ord_inventory_sql);
                  while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
                    $productText = " [ ".$ord_inventory['product_model']." ] ".
                    substr($ord_inventory['product_name'], 0, 20);
                    $arrInventory[ $ord_inventory['product_id'] ] = $productText;
                  }

                $newOrderStatusID = 10;
                $order_history_sql = "INSERT into orders_history (order_id,
                order_history_date, order_history_status)
                VALUES ('$ord_add_insert_id','".date("y-m-d h:i:s")."',
                $newOrderStatusID)";
                $order_history_query = my_db_query($order_history_sql);


                for($i=0; $i<$order_size; $i++){


		            $start = strpos($updateModel['order_product_name'],"[") + 2;
		            $length = strpos($arrInventory[$_POST['product_name_'.$i]],"]")-$start;
		            $productModel = trim(substr($arrInventory[$_POST['product_name_'.$i]],$start,$length));
		            $generic_size = $arrSizes[$_POST['product_size_'.$i]];
		            $category = trim(substr($productModel, 0, 3));

                    $ord_add_product_sql = sprintf("INSERT INTO
                    `orders_products` (`order_id` , `order_product_quantity` ,
					`order_product_size` , `order_product_name`,
					`order_product_model`, `order_product_charge`
                     )VALUES (%d, %d, %s, %s, %s, %f)", $ord_add_insert_id,
                     $_POST['product_quantity_'.$i],
                    "'".strtoupper($category . " - " .mysql_real_escape_string($arrSizes[$_POST['product_size_'.$i]]))."'",
                    "'".mysql_real_escape_string(trim($arrInventory[$_POST['product_name_'.$i]]))."'",
					"'".$productModel."'",
					getPriceBySize($_POST['accounts_number'],$productModel, $arrSizes[$_POST['product_size_'.$i]]) );
if( $_SESSION['userlevel'] == 'super'){
//	echo "-------------------------------------<br>";
//	echo "Debug Info for Super Users <br>";
//	echo "-------------------------------------<br>";
//	echo "Old System Price: " . getPriceBySize($_POST['accounts_number'],$productModel, $arrSizes[$_POST['product_size_'.$i]]);
}
                    my_db_query($ord_add_product_sql);

                }


                if( $ord_add_insert_id != 0 ){
                    $orderNum = (strlen($_POST['customer_order_no']) > 0 && $_POST['customer_order_no'] != "NULL")?"<small>(Order No.: ".
                    $_POST['customer_order_no'].")</small>":"";
                    echo "<div align=center class=\"success\">Order
                    Submitted Successfully for ".$_POST['customer_name']." ".$_POST['orderNum'];
                    echo "<div align=center class=\"smallText\">A copy will be emailed to you for your records</div>";
                    echo "<br><br><br><br> <a href=\"".my_href_link('orders2.php')."\">Submit another order?</a></div>";


                    my_mail_order($ord_add_insert_id,$_SESSION['client_account_number']);

                }else{
                    echo "<div align=center class=\"fail\">Order Not Submitted</div>";
                }
        }else{
            echo "<div align=center class=\"fail\">Order Not Submitted!<br/>
            Empty Required Field(s) Found!</div>";
            echo "<div align=center class=\"fail\">";
            if( strlen($customer_address1) == 0 ) echo "<br>Customer Address";
            if( strlen($customer_city) == 0 ) echo "<br>Customer City";
            if( strlen($customer_state) == 0 ) echo "<br>Customer State";
            if( strlen($customer_zip) == 0 ) echo "<br>Customer Zip";
            if( strlen($customer_country) == 0 ) echo "<br>Customer Country";
            if( strlen($customer_name) == 0 ) echo "<br>Customer Name";

            for($i=0; $i<$order_size; $i++){
                $temp = $i + 1;
                if( strlen($_POST['product_quantity_'.$i]) == 0 )
                    echo "<br>Product Quantity #$temp was empty";
            }
            echo "</div>";
        }
    }
}
?>
</form>
</body>
</html>
