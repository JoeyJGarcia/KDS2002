<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Show Orders</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<style type="text/css">
	@import "includes/js/dojoroot/dijit/themes/tundra/tundra.css";
	@import "includes/js/dojoroot/dojo/resources/dojo.css"
</style>

<script type="text/javascript"
	src="includes/js/dojoroot/dojo/dojo.js"
	djConfig="parseOnLoad: true">
</script>

<script type="text/javascript">
    dojo.require("dijit.form.Button");
    dojo.require("dijit.form.TextBox");
    dojo.require("dijit.form.Textarea");
    dojo.require("dijit.layout.ContentPane");
    dojo.require("dijit.Dialog");
    dojo.require("dojo._base.xhr");
</script>
<script type="text/javascript">

function sendRequest(){
	dojo.xhrPost({
		url:"http://www.kerussods.com/ajax_controller.php?kdssid=<?php echo $_GET['kdssid'];?>",

		handleAs: "text",

		load:function(response, ioArgs){
			dojo.byId("statusMsg").innerHTML = response;
			return response;
		},

		error: function(response, ioArgs){
			dojo.byId("statusMsg").innerHTML =
			"An error occurred, with response: " + response;
			return response;
		},

		form:dojo.byId("requestFrm")

		});
	
}

</script>





<?php
if($_GET['action'] == 'show_order' ){
?>
    <!-- American format mm/dd/yyyy -->
    <script type="text/JavaScript" src="calendar2.js"></script>
    <!-- Date only with year scrolling -->
<?php
}
?>
<script type="text/JavaScript" src="debugInfo.js"></script>
</head>

<body class="tundra">
<?php
require('navigation.php');
?>


<table align="right"><tr><td><div onclick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');
?>


<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText">S H O W &nbsp;&nbsp; O R D E R S</td>
    </tr>
</table>


<br />
<br />


<?php
//*************************************************************************
//************************ SHOW SINGLE ORDER ***************************
//*************************************************************************
if( $_GET['action'] == 'show_order' ){

    $order_info_sql = "SELECT * FROM orders WHERE order_id=".$_GET['oId'];
    $order_info_query = my_db_query($order_info_sql);
    $order_info = my_db_fetch_array($order_info_query);

    if($_GET['debug_mode']){//DEBUG
        echo "<b>order_info_sql</b> = ".$order_info_sql."<br>";
    }

    $order_products_sql = "SELECT * FROM orders_products WHERE order_id=".$_GET['oId'];
    $order_products_query = my_db_query($order_products_sql);

    if($_GET['debug_mode']){//DEBUG
        echo "<b>order_products_sql</b> = ".$order_products_sql."<br>";
    }

    $order_status_sql = "SELECT * FROM order_status";
    $order_status_query = my_db_query($order_status_sql);
    while($order_status = my_db_fetch_array($order_status_query)){
        $arrOrderStatus2[$order_status['order_status_id']] = $order_status['order_status_name'];
    }

    if($_GET['debug_mode']){//DEBUG
        echo "<b>order_status_sql</b> = ".$order_status_sql."<br>";
    }

    $account_sql = "SELECT * FROM accounts WHERE accounts_number='".$order_info['accounts_number']."'";
    $account_query = my_db_query($account_sql);
    $account = my_db_fetch_array($account_query);

    if($_GET['debug_mode']){//DEBUG
        echo "<b>account_sql</b> = ".$account_sql."<br>";
    }

    $order_history_sql = "SELECT * FROM orders_history WHERE order_id='".$_GET['oId']."' ORDER BY order_history_date ASC";
    $order_history_query = my_db_query($order_history_sql);

    if($_GET['debug_mode']){//DEBUG
        echo "<b>order_history_sql</b> = ".$order_history_sql."<br>";
    }

    $prices_sql = "SELECT * FROM sizes WHERE 1 ORDER BY sizes_sort";
    $prices_query = my_db_query($prices_sql);
    $arrPrices = array();
    while($prices = my_db_fetch_array($prices_query)){
        $arrPrices[$prices['sizes_name']] = $prices['sizes_fee'];
    }

    if($_GET['debug_mode']){//DEBUG
        echo "<b>prices_sql</b> = ".$prices_sql."<br>";
    }

    $tableWidth=800;
    $orderStatus = $arrOrderStatus2[$order_info['order_status']];

?>

<table cellpadding=0 cellspacing=0 align="center" border="0" width=<?php echo $tableWidth; ?>>
<tr><td>
    <a href="javascript:history.go(-1)">
    <?php echo my_image(DIR_WS_IMAGES.'btnBack.gif','Return To Lookup');?>
    </a>&nbsp;&nbsp;
</td>
<td align=right class="mediumBoldText">

</td>
<td align="right">
Questions/RMA/Discrepancies?
<div id="contactDialog" dojoType="dijit.Dialog" title="Contact Kerusso">
	<div dojoType="dijit.layout.ContentPane" title="foo" style="width:500px;text-align:center;">
	<form action="" name="requestFrm" id="requestFrm" method="post" >
		<table border="0" width="100%" align="center">
		<tr>
			<th align="right">Company Name: </th>
			<td><img src="images/spacer.gif" width="20px" height="1px"></td>
			<td><?php echo $account['accounts_company_name'];?></td>
		</tr>
		<tr>
			<th align="right">Customer Name: </th>
			<td><img src="images/spacer.gif" width="20px" height="1px"></td>
			<td align="center"><?php echo $order_info['customer_name'];?></td>
		</tr>
		<tr>
			<th align="right">Customer Invoice: </th>
			<td><img src="images/spacer.gif" width="20px" height="1px"></td>
			<td align="center"><?php echo $order_info['customer_invoice_number'];?></td>
		</tr>
		<tr>
			<th align="right">PO No.: </th>
			<td><img src="images/spacer.gif" width="20px" height="1px"></td>
			<td align="center"><?php echo $order_info['purchase_order_number'];?></td>
		</tr>
		<tr>
			<th align="right">Account No.: </th>
			<td><img src="images/spacer.gif" width="20px" height="1px"></td>
			<td align="center"><?php echo $order_info['accounts_number'];?></td>
		</tr>
		<tr>
			<th align="right">Select Contact Type: </th>
			<td><img src="images/spacer.gif" width="20px" height="1px"></td>
			<td align="left">
				<input type="radio" name="email_type" value="question" > QUESTION<br>
				<input type="radio" name="email_type" value="rma" > RMA<br>
				<input type="radio" name="email_type" value="discrepancy" > DISCREPANCY
			</td>
		</tr>
		<tr>
			<td align="center" colspan="3" class="smallRedText">
			<table width="100%">
				<tr>
					<td align="right">Note:</td>
					<td align="left">Enter the details of your request below, the box will grow larger if needed.</td>
				</tr>
				<tr>
					<td></td>
					<td align="left">A copy of this request will be sent to the email address on file.</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="left">
			<div style="width:400px">
			<textarea  type="text" name="request_details" id="request_details"
			dojoType="dijit.form.Textarea"  focus() maxLength="255"
			style="width: 90%; overflow-y: hidden;"></textarea>
			</div>

            <input type="hidden" name="order_id" value="<?php echo $order_info['order_id'];?>"/>
            <input type="hidden" name="order_status_id" value="<?php echo $order_info['order_status'];?>"/>
            <input type="hidden" name="customer_invoice_number" value="<?php echo $order_info['customer_invoice_number'];?>"/>
            <input type="hidden" name="customer_name" value="<?php echo $order_info['customer_name'];?>"/>
            <input type="hidden" name="accounts_company_name" value="<?php echo $account['accounts_company_name'];?>"/>
            <input type="hidden" name="accounts_number" value="<?php echo $order_info['accounts_number'];?>"/>
            <input type="hidden" name="purchase_order_number" value="<?php echo $order_info['purchase_order_number'];?>"/>
            <input type="hidden" name="accounts_email" value="<?php echo $account['accounts_email'];?>"/>
            <input type="hidden" name="action" value="send_email"/>

			</td>
		</tr>
		<tr>
			<td align="center" colspan="3">
				<button id="sendButton" name="sendButton" dojoType="dijit.form.Button">Send Request
				    <script type="dojo/method" event="onClick" args="evt">
						var frm = document.getElementById('requestFrm');
						var isTypeChosen = false;
						var isDetailsEntered = false;

						for(i=0; i<frm.elements['email_type'].length; i++){
							if(frm.elements['email_type'][i].checked){
								isTypeChosen = true;
								break;
							}
						}
//						alert(dojo.byId("request_details").text);
//						if(dojo.byId("request_details").value.length > 5){
//							isDetailsEntered = true;
//						}

						if(isTypeChosen){
//							alert(frm.elements['sendButton']);
							sendRequest();
							dijit.byId("contactDialog").hide();
						}else if( !isTypeChosen ){
							alert("You need to select a contact type before this will be sent. ");
						}else if( !isDetailsEntered ){
							alert("You need to enter more details before this will be sent.");
						}
    				</script>
				</button>
			</td>
		</tr>
		</table>
	</form>
	</div>
</div>


<button id="contactButton" dojoType="dijit.form.Button">Contact Kerusso
    <script type="dojo/method" event="onClick" args="evt">
        // Show the Dialog:
        dijit.byId("contactDialog").show();
    </script>
</button>
<div id="statusMsg"></div>
</td>
</tr>
</table>

<?php echo my_draw_form('show_order',my_href_link('show_orders.php'));?>
<?php echo my_draw_hidden_field("status", $_POST['sql_status']) . "\n"; ?>
<?php echo my_draw_hidden_field("fromDate", $_POST['sql_fromDate']) . "\n"; ?>
<?php echo my_draw_hidden_field("toDate", $_POST['sql_toDate']) . "\n"; ?>
<?php echo my_draw_hidden_field("accounts_number", $_POST['sql_accounts_number']) . "\n"; ?>


<table width=<?php echo $tableWidth; ?>  align="center" border=0  class="thinOutline" cellspacing=0>

<tr class="tableHeader">
    <td class="mediumBoldText" colspan=6 align=center>O R D E R  &nbsp;&nbsp; I N F O R M A T I O N</td>
</tr>

<tr class="tableHeader">
    <td colspan=6>

        <table width=100% align="center" border=0  cellspacing=0>

        <tr class="tableHeader">
            <td class="mediumBoldText" colspan=2 align=center>Invoice No.: &nbsp; <font color="#FFCC00"><?php echo $order_info['customer_invoice_number'];?></font></td>
            <td class="mediumBoldText" colspan=2 align=center>Company: &nbsp; <font color="#FFCC00"><?php echo $account['accounts_company_name'];?></font></td>
            <td class="mediumBoldText" colspan=2 align=center>Account No.: &nbsp; <font color="#FFCC00"><?php echo $order_info['accounts_number'];?></font></td>
            <td class="mediumBoldText" colspan=2 align=center>PO No.: &nbsp; <font color="#FFCC00"><?php echo $order_info['purchase_order_number'];?></font></td>
        </tr>
        </table>

    </td>
</tr>
<tr class="tableRowColor">
    <td align=left class="mediumBoldText" >Order Status</td>
    <td align=left class="mediumBoldText" >Dropship Fee</td>
    <td align=left class="mediumBoldText" >Handling Fee</td>
    <td align=left class="mediumBoldText" >Shipping Fee</td>
    <td align=left class="mediumBoldText" >Misc Fee</td>
    <td align=center class="mediumBoldText" >Misc Fee Desc</td>
</tr>
<tr class="tableRowColor">
    <td> <?php echo my_draw_input_field('orderStatus',$orderStatus,'size=20'); ?></td>
    <td>$<?php echo my_draw_input_field('dropship_fee',$order_info['dropship_fee'],'size=5'); ?></td>
    <td>$<?php echo my_draw_input_field('handling_fee',$order_info['handling_fee'],'size=5'); ?></td>
    <td>Varies</td>
    <td>$<?php echo my_draw_input_field('misc_fee',$order_info['misc_fee'],'size=5'); ?></td>
    <td align=center><?php echo my_draw_input_field('misc_desc',$order_info['misc_desc'],'size=30'); ?></td>
</tr>

<tr class="tableRowColor">
<td class="mediumBoldText" align=center colspan=6>
Ship&nbsp;Date:
<?php

if(strlen($order_info['ship_date']) > 4 ){
    $ship_date = justDate($order_info['ship_date']);
}else{
    $ship_date = "Not Shipped Yet";
}
echo "<font color=\"#0000FF\">".$ship_date."</font>";

?>
<?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','10','1'); ?>
Tracking&nbsp;Number:
<?php echo my_draw_input_field('order_tracking_number',$order_info['order_tracking_number'],'size=30'); ?>
<?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','10','1'); ?>
Kerusso&nbsp;Sales&nbsp;Id:
<?php echo my_draw_input_field('order_invoice_number',$order_info['order_invoice_number'],'size=20'); ?>
</td>
</tr>
</table>

<?php echo my_draw_hidden_field('original_order_status',$order_info['order_status']); ?>
<?php echo my_draw_hidden_field('returnTo','show_orders'); ?>

<br/>

<?php echo my_draw_hidden_field('order_id',$_GET['oId']); ?>


<table width=<?php echo $tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>

<tr class="tableHeader">
    <td class="mediumBoldText" colspan=2 align=center>C U S T O M E R</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Customer Name:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    $order_info['customer_name']; ?>&nbsp;</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Address Information:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    $order_info['customer_address1']; ?>&nbsp;</td>
 </tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Add'l Address Information:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    my_null_replace($order_info['customer_address2']); ?></td>
 </tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >International Phone No.:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    my_null_replace($order_info['customer_intl_phone']); ?></td>
 </tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >City:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    $order_info['customer_city']; ?>&nbsp;</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >State:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    $order_info['customer_state']; ?>&nbsp;</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Zip/Postal Code:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    $order_info['customer_zip']; ?>&nbsp;</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Country:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    $order_info['customer_country']; ?>&nbsp;</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Shipping Method:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    $order_info['customer_shipping_method']; ?></td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Order/Invoice Number:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_spacer('spacer.gif',20,1) .
    my_null_replace($order_info['customer_invoice_number']); ?></td>
</tr>

</table>
<br />


<table width=<?php echo $tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
    <td class="mediumBoldText" colspan=6 align=center>P R O D U C T S </td>
</tr>

<tr class="tableRowColor">
    <td align=left class="mediumBoldText" >Quantity</td>
    <td align=left class="mediumBoldText" >Unit Price</td>
    <td align=left class="mediumBoldText" >Product Charge</td>
    <td align=left class="mediumBoldText" >Size</td>
    <td align=left class="mediumBoldText" >Product Name</td>
    <td align=left class="mediumBoldText" >Product Status</td>
</tr>
<?php
    $counter = 0;
    while( $order_products = my_db_fetch_array($order_products_query) ){
	    if( $order_products['order_product_charge'] == 0 ){
	        $product_fee = $arrPrices[$order_products['order_product_size']];
	    }else{
	        $product_fee = $order_products['order_product_charge'];
	    }
?>

<tr class="tableRowColor">
    <td align=center>
        <?php echo my_draw_hidden_field('order_product_id_'.$counter,$order_products['order_product_id']); ?>
        <?php echo $order_products['order_product_quantity']; ?>
         x
    </td>
    <td>$
			<?php echo $product_fee; ?>
    </td>
    <td> = &nbsp;$<?php echo number_format($product_fee * $order_products['order_product_quantity'], 2, '.', ''); ?></td>
    <td><?php echo $order_products['order_product_size']; ?></td>
    <td><?php echo stripslashes($order_products['order_product_name']); ?></td>
    <td><?php echo $order_products['order_product_status']; ?></td>
</tr>

<?php
    $counter++;
    }//end of while loop
?>

</table>
<br />


<table width=<?php echo $tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
    <td class="mediumBoldText" colspan=5 align="center">C L I E N T  &nbsp;&nbsp; C O M M E N T S </td>
</tr>
<tr class="tableRowColor">
    <td colspan=5 align=center><?php
    if( $order_info['order_comments'] != "NULL"){
        echo my_unescape_string($order_info['order_comments']);
    }else{
        echo "None";
    }
    ?></td>
</tr>
</table>



<br />

<table width=<?php echo $tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
    <td class="mediumBoldText" colspan=5 align=center>N O T E S</td>
</tr>
<tr class="tableRowColor">
    <td align=center class="mediumBoldText">Date Added</td>
    <td align=center class="mediumBoldText">Client Notified</td>
    <td align=center class="mediumBoldText">Status</td>
    <td align=center class="mediumBoldText">Comments</td>
</tr>
<?php
    $count = 0;
    while( $order_history = my_db_fetch_array($order_history_query) ){
    $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";
    $count++;
?>
<tr class="<?php echo $bgcolor; ?>">
    <td align=center class="mediumText" style="width: 100px;"><?php echo $order_history['order_history_date']; ?></td>
    <td align=center class="mediumText">
    <?php
        if( $order_history['order_history_is_notified']== 1 ){
            echo my_image(DIR_WS_IMAGES.'check.gif','Client Notified');
        }else{
            echo my_image(DIR_WS_IMAGES.'cross.gif','Client Not Notified');
        }
    ?>
    </td>
    <td align=center class="mediumText"><?php echo $arrOrderStatus2[$order_history['order_history_status']]; ?></td>
    <td align=center class="mediumText"><?php echo my_unescape_string($order_history['order_history_comments']); ?></td>
</tr>
<?php
    }
?>
</table>
</form>

<?php
}//End of action=show_orders html portion
//*************************************************************************
//*************************** SHOW ORDERS *********************************
//*************************************************************************

            if($_GET['debug_mode']){//DEBUG
                echo "accounts_number = ".$_SESSION['client_account_number']."<br>";
                echo "<b>order_status_sql</b> = ".$order_status_sql."<br>";
                echo sprintf("<b>radWhereClause</b> = %s<br>", $_POST['radWhereClause']);
                echo sprintf("<b>order_status_id</b> = %s<br>", $_POST['order_status_id']);
                echo sprintf("<b>returnTo</b> = %s<br>", $_POST['returnTo']);
                echo sprintf("<b>action</b> = %s<br>", $_GET['action']);

            }


        if( $_GET['action'] == 'show_orders' || $_POST['returnTo'] == 'show_orders' ){
            $arrOrderStatus = array();
            $order_status_sql = "SELECT * from order_status";
            $order_status_query = my_db_query($order_status_sql);
            while($order_status = my_db_fetch_array($order_status_query)){
                $arrOrderStatus[$order_status['order_status_id']] =
                $order_status['order_status_name'];
            }


            //The $status variable filters by order_status, if required by the user
            $status = ($_POST['order_status_id'] == 0 )? "" : " AND o.order_status=".$_POST['order_status_id'] ;

            if( $_POST['radWhereClause'] == 1 && (strlen($_POST['toDate'])>0) && (strlen($_POST['fromDate'])>0) ){
                $arrToDate = split("/",$_POST['toDate']);
                $arrFromDate = split("/",$_POST['fromDate']);
                $toDate = $arrToDate[2]."-".$arrToDate[0]."-".$arrToDate[1];
                $fromDate = $arrFromDate[2]."-".$arrFromDate[0]."-".$arrFromDate[1];

                if(strlen($_POST['custOrderId']) > 0){
                    $clauseOrderId = " AND o.customer_invoice_number='".$_POST['custOrderId']."'";
                }else{
                    $clauseOrderId = "";
                }

                $show_orders_sql = "SELECT o.order_id, (select count(*) from orders_products op where op.order_id=o.order_id) as cnt, o.customer_name,
                o.purchase_date, o.customer_shipping_method, o.order_status,
                o.purchase_order_number, o.customer_invoice_number,
                o.order_comments, o.ship_date FROM orders o, accounts a WHERE
                purchase_date <  '$toDate' AND purchase_date >=  '$fromDate'
                AND o.accounts_number =  a.accounts_number
                AND a.accounts_number =  '".$_SESSION['client_account_number']."' $status $clauseOrderId
                ORDER BY o.customer_invoice_number DESC";

            if($_GET['debug_mode']){//DEBUG
                echo sprintf("<b>show_orders_sql.</b> = %s<br>", $show_orders_sql);
            	echo "<br>Using dates for search criteria<br>";
            }

            }elseif($_POST['radWhereClause'] == "3" || $_POST['radWhereClause'] == "2" ){

            if($_GET['debug_mode']){//DEBUG
                echo sprintf("<b>custOrderId.</b> = %s<br>", $_POST['custOrderId']);
                echo sprintf("<b>status.</b> = %s<br>", $status);
            	echo "<br>Not using dates for search criteria<br>";
            }
                if(strlen($_POST['custOrderId']) > 0){
                    $clauseOrderId = " AND o.customer_invoice_number='".$_POST['custOrderId']."'";
                }else{
                    $clauseOrderId = "";
                }

                if($_POST['radWhereClause'] == "2"){
                    $daysInPast = " AND o.purchase_date >
                    DATE_ADD( CURDATE(  ) ,  INTERVAL  -".$_POST['days_back']." DAY  ) ";
                }else{
                    $daysInPast = "";
                }

                $show_orders_sql = "SELECT o.order_id, (select count(*) from orders_products op where op.order_id=o.order_id) as cnt, o.customer_name,
                o.purchase_date, o.customer_shipping_method, o.order_status,
                o.purchase_order_number, o.customer_invoice_number,
                o.order_comments, o.ship_date, o.shipping_charge
                FROM orders o, accounts a WHERE
                o.accounts_number = a.accounts_number AND
                a.accounts_number =  '".$_SESSION['client_account_number']."' $status $clauseOrderId $daysInPast
                ORDER BY o.customer_invoice_number DESC";

            }

            if($_GET['debug_mode']){//DEBUG
                echo sprintf("<b>show_orders_sql..</b> = %s<br>", $show_orders_sql);
            }

            $show_orders_query = my_db_query($show_orders_sql);
//echo $show_orders_sql;

if(mysql_num_rows($show_orders_query) != 0){
?>

<?php echo my_draw_form('show_orders',my_href_link('show_orders.php')) . "\n";?>

<?php echo my_draw_hidden_field("action","show_order") . "\n"; ?>
<?php echo my_draw_hidden_field("oId") . "\n"; ?>
<?php echo my_draw_hidden_field("sql_status", "\"".$_POST['status']."\"") . "\n"; ?>
<?php echo my_draw_hidden_field("sql_fromDate", "\"".$_POST['fromDate']."\"") . "\n"; ?>
<?php echo my_draw_hidden_field("sql_toDate", "\"".$_POST['toDate']."\"") . "\n"; ?>
<?php echo my_draw_hidden_field("sql_accounts_number", "\"".$_POST['accounts_number']."\"") . "\n"; ?>

<table id="showMyOrdersTable" align="center"  border="0" class="thinOutline" cellspacing="0">
<tr class="tableHeader">
    <th>Invoice&nbsp;No. (count)<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th align="left"><?php echo my_draw_spacer('spacer.gif',20,1); ?>Customer<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th>Purchase&nbsp;Date</th>
    <th><?php echo my_draw_spacer('spacer.gif',20,1); ?>Shipping Method</th>
    <th><?php echo my_draw_spacer('spacer.gif',80,1); ?>Order&nbsp;Status</th>
    <th><?php echo my_draw_spacer('spacer.gif',20,1); ?>Ship&nbsp;Date<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th><?php echo my_draw_spacer('spacer.gif',20,1); ?>PO&nbsp;No.</th>
</tr>
<?php
     while($show_orders = my_db_fetch_array($show_orders_query)){

    if( strlen($show_orders['ship_date']) > 4){
        $ship_date = justDate($show_orders['ship_date']);
    }else{
        $ship_date = "Not Shipped Yet";
    }
?>
    <tr class="tableRowColor">
        <td class="smallText" align=left>&nbsp;&nbsp;&nbsp;
        <?php echo "<a href=\"".my_href_link("show_orders.php","action=show_order&oId=".$show_orders['order_id'])."\">";?>
		<?php echo my_null_replace($show_orders['customer_invoice_number']) ." (".$show_orders['cnt'].")"; ?>
		<?php echo "</a>";?>
		</td>

        <td class="smallText" align=left>
        <?php echo "<a href=\"".my_href_link("show_orders.php","action=show_order&oId=".$show_orders['order_id'])."\">";?>
		<?php echo my_null_replace($show_orders['customer_name']); ?>
		<?php echo "</a>";?>
		</td>

        <td class="smallText" align=center>
        <?php echo "<a href=\"".my_href_link("show_orders.php","action=show_order&oId=".$show_orders['order_id'])."\">";?>
		<?php echo justDate($show_orders['purchase_date']); ?>
		<?php echo "</a>";?>
		</td>

        <td class="smallText" align=center>
        <?php echo "<a href=\"".my_href_link("show_orders.php","action=show_order&oId=".$show_orders['order_id'])."\">";?>
		<?php echo my_null_replace($show_orders['customer_shipping_method']); ?>
		<?php echo "</a>";?>
		</td>

        <td class="smallText" align=center>
        <?php echo "<a href=\"".my_href_link("show_orders.php","action=show_order&oId=".$show_orders['order_id'])."\">";?>
		<?php echo $arrOrderStatus[$show_orders['order_status']]; ?>
		<?php echo "</a>";?>
		</td>

        <td class="smallText" align=center>
        <?php echo "<a href=\"".my_href_link("show_orders.php","action=show_order&oId=".$show_orders['order_id'])."\">";?>
		<?php echo $ship_date; ?>
		<?php echo "</a>";?>
		</td>

        <td class="smallText" align=center>
        <?php echo "<a href=\"".my_href_link("show_orders.php","action=show_order&oId=".$show_orders['order_id'])."\">";?>
		<?php echo my_null_replace($show_orders['purchase_order_number']); ?>
		<?php echo "</a>";?>
		</td>

    </tr>
<?php
    }//end of while loop
    }else{ //end of if statement - any results?
        echo "<h4 align=center>No Orders Returned.</h4>";
    }
?>
</table>


<br />
<div align="center">
<a href="<?php echo my_href_link('show_orders.php'); ?>">
<?php echo my_image(DIR_WS_IMAGES.'btnBack.gif','Back To Show Order Menu'); ?>
</a>
</div>

<?php
    }//end of "show_orders"
    elseif($_GET['action'] != 'show_orders' && $_GET['action'] != 'show_order' ){

//*************************************************************************
//**************************** MAIN MENU **********************************
//*************************************************************************
    $arrNewOrders = array();
    $new_orders_sql = "SELECT a.accounts_username, COUNT(o.order_status) AS NewOrders
    FROM orders o, accounts a WHERE o.accounts_number = a.accounts_number
    AND o.order_status=10 GROUP BY a.accounts_username";
    $new_orders_query = my_db_query($new_orders_sql);
    while($new_orders = my_db_fetch_array($new_orders_query) ){
        $arrNewOrders[$new_orders['accounts_username']] = $new_orders['NewOrders'];
    }

    $accounts_sql = "SELECT * FROM accounts where 1 ORDER BY accounts_username";
    $accounts_query = my_db_query($accounts_sql);
    while( $accounts = my_db_fetch_array($accounts_query)  ){

        if( $arrNewOrders[$accounts['accounts_username']] > 0 ){
            $accounts['accounts_username'] = "(".$arrNewOrders[$accounts['accounts_username']].
            ")".$accounts['accounts_username'];
        }
    }


    $order_status_sql = "SELECT * FROM order_status where 1 ORDER BY order_status_sort";
    $order_status_query = my_db_query($order_status_sql);
    $arrOrderStatus[] = array('id' => '0','text' => 'Show All Orders');
    while( $order_status = my_db_fetch_array($order_status_query)  ){
        $arrOrderStatus[] = array('id' => $order_status['order_status_id'],
                              'text' => $order_status['order_status_name']);
    }
?>

<?php echo my_draw_form('process_order',my_href_link('show_orders.php', 'action=show_orders'));?>
<br /><br /><br />


<table width="600" align="center" border=0>

<tr><th></th><th colspan=3>Optional Fields</th></tr>
<tr><th>Select Status</th><td>&nbsp;</td>
<th>From</th><th>To</th></tr>

<tr>
<td valign=top align=left>
    <?php echo my_draw_hidden_field('accounts_number',$_POST['accounts_number']); ?>
    <?php echo my_draw_pull_down_menu('order_status_id',$arrOrderStatus); ?>
</td>
<td><?php echo my_draw_radio_field("radWhereClause","1");?><br /><br /></td>
<td  align=left>
<input type=text name="fromDate" size=10 />
<a href="javascript:cal2FROM.popup();">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date">
</a><br /><br />
</td>

<td align=center>
<input type=text name="toDate" size=10 />
<a href="javascript:cal2TO.popup();">
<img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date">
</a><br /><br />
</td>

</tr>

<tr>
<td align="center">
<td><?php echo my_draw_radio_field("radWhereClause","2","true");?></td>
<td align=left colspan="2">
<?php

$arrDays = array();
$arrDays[0] = array('id' => "5",'text' => "5");
$arrDays[1] = array('id' => "10",'text' => "10");
$arrDays[2] = array('id' => "20",'text' => "20");
$arrDays[3] = array('id' => "30",'text' => "30");
$arrDays[4] = array('id' => "60",'text' => "60");

echo "<br/>Show orders ".my_draw_pull_down_menu('days_back',$arrDays)." days back"; ?>
<br /><br /></td>
</tr>

<tr>
<th></th>
<td><?php echo my_draw_radio_field("radWhereClause","3");?><br /><br /></td>
<th align=left>Order Id<br /><br /></th>
<td align=left valign=bottom>
    <?php echo my_draw_input_field('custOrderId'); ?>
<br /><br /></td>
</tr>

<tr>
<th colspan="4"><br /><br />
<?php echo my_image_submit('btnSubmitOnWhite.gif','Show Orders'); ?>
</th>
</tr>
</table>

<script type="text/JavaScript">
<!-- // create calendar object(s) just after form tag closed
 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
 // note: you can have as many calendar objects as you need for your application
//var cal1 = new calendar1(document.forms['process_order'].elements['fromDate']);
//cal1.year_scroll = true;
//cal1.time_comp = false;
var cal2FROM = new calendar2(document.forms['process_order'].elements['fromDate']);
cal2FROM.year_scroll = false;
cal2FROM.time_comp = false;
var cal2TO = new calendar2(document.forms['process_order'].elements['toDate']);
cal2TO.year_scroll = false;
cal2TO.time_comp = false;
//-->
</script>

<?php
    }//end of Main Menu
?>


</form>

</body>
</html>
