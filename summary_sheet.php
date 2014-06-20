<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Summary Sheet</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
<?php
if($action != 'show_orders' && $action != 'process_order' && $action != 'update_order' ){
?>
    <!-- American format mm/dd/yyyy -->
    <script language="JavaScript" src="calendar2.js"></script><!-- Date only with year scrolling -->
<?php
}
?>
<script language="JavaScript" src="debugInfo.js"></script>

<script language="javascript1.2">

function showSummarySheet(){

    $orderCount = parseInt(document.forms['summary_sheet'].orderCount.value) + 2;//add two for the hidden form fields
    $checkedBoxes = 0;
    for($i=0; $i<$orderCount; $i++){
        if(document.forms['summary_sheet'].elements[$i].checked == true ){
            $checkedBoxes++;
        }
    }//end of for loop
    if( $checkedBoxes == 0){
        alert("No orders selected.  No summary sheet to show.");
        return;
    }else{
        document.forms['summary_sheet'].submit();
    }
}

function selectAll($flag){

    for( $i=0; $i<document.forms[0].elements.length; $i++){

        if( $i%2 != 0 )continue;

        if(document.forms[0].elements[$i].name.indexOf("arrOrderProdId") >= 0){
            document.forms[0].elements[$i].checked=$flag;
        }
    }

    return false;
}


function hideRows($id){
    document.getElementById($id).style.display='none';

    $hiddenRowCount = parseInt(document.forms[0].hidden_row_count.value);

    document.getElementById($id).style.display='none';

    $hiddenRowCount++;
    document.forms[0].hidden_row_count.value = $hiddenRowCount;

    document.getElementById('hiddenRowsCount').innerHTML = "("+ $hiddenRowCount +")";
}

function showRows($count){
    //$count = 0+document.forms[0].product_count.value;
    for($i=1; $i<=$count; $i++){
        document.getElementById(eval("'ssRow"+$i+"'")).style.display='';
    }

    for( $i=0; $i<document.forms[0].elements.length; $i++){
        if(document.forms[0].elements[$i].name.indexOf("arrOrderProdId") >= 0){
            document.forms[0].elements[$i].checked=false;
        }
    }

    document.forms[0].hidden_row_count.value = 0;
    document.getElementById('hiddenRowsCount').innerHTML = "";

}


</script>

</head>

<body >
<?php
require('navigation.php');
?>

<table align=right><tr><td><div onClick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');
?>


<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText">S U M M A R Y &nbsp;&nbsp; S H E E T</td>
    </tr>
</table>


<br />
<br />


<?php
//*************************************************************************
//************************ SHOW SUMMARY SHEET *****************************
//*************************************************************************
if( $action == 'summary_sheet' ){

    $tableWidth=800;

    $order_ids = "";
    for($i=0; $i<count($arrOrderId); $i++){
        $order_ids = (strlen($order_ids) == 0)? $arrOrderId[$i] : $order_ids.','.$arrOrderId[$i];
    }


    $summary_sql = "SELECT op.order_product_id, op.order_product_quantity,
    op.order_product_size, op.order_product_name, o.accounts_number,
    o.purchase_order_number, o.purchase_date, o.order_tracking_number
    FROM orders_products op, orders o
    WHERE op.order_id IN ($order_ids) AND
    op.order_id = o.order_id AND o.accounts_number='".$accounts_number."'
    ORDER BY op.order_product_name";
    $summary_query = my_db_query($summary_sql);

//debug code
if(false){
    echo "Summary SQL: ".$summary_sql."<br>";
}

?>
<?php echo my_draw_form('summary_sheet','summary_sheet.php','post','action=save_summary');?>

<table width=<?php echo$tableWidth; ?>  align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
    <td class="mediumText" align=center><strong>Hide</strong></td>
    <td class="mediumText" align=center><strong>Purchase Date</strong></td>
    <td class="mediumText" align=center><strong>Account No.</strong></td>
    <td class="mediumText" align=center><strong>PO No.</strong></td>
    <td class="mediumText">&nbsp;&nbsp;<strong>Product Code</strong></td>
    <td class="mediumText" align=center><strong>Quantity</strong></td>
    <td class="mediumText" align=center><strong>Size</strong></td>
    <td class="mediumText" align=center><strong>Tracking No.</strong></td>
</tr>

<?php
$count = 0;
while($summary = my_db_fetch_array($summary_query)){
    $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";
    $count++;

?>
<tr class="<?php echo $bgcolor; ?>" id="ssRow<?= $count ?>">
    <td align=center><?php echo my_draw_checkbox_field("arrOrderProdId[]",
    $summary['order_product_id'],"","onClick=\"hideRows('ssRow".$count."')\""); ?></td>
    <td class="mediumText"> &nbsp;&nbsp;<?php
            $arrDate = split(" ",$summary['purchase_date']);
            echo $arrDate[0];
        ?>
    <?php echo my_draw_hidden_field("order_product_id_".$i,$summary['order_product_id']);?>
    </td>
    <td class="mediumText"> &nbsp;&nbsp; <?php echo $summary['accounts_number'];?></td>
    <td class="mediumText"> &nbsp;&nbsp; <?php echo $summary['purchase_order_number'];?></td>
    <td class="mediumText" align=center><?php

    $arrProductCode = split("]", $summary['order_product_name']);
    echo trim(substr($arrProductCode[0], 2));

?></td>
    <td class="mediumText" align=center><?php echo $summary['order_product_quantity'];?></td>
    <td class="mediumText"><?php echo $summary['order_product_size'];?></td>
    <td class="mediumText"> &nbsp;&nbsp; <?php echo $summary['order_tracking_number'];?></td>
</tr>
<?php
}
?>
</table>
<table width=<?php echo$tableWidth; ?>  align="center" border=0 cellspacing=0>
<tr><td class="smallText"><!--
<a href="#" onClick="selectAll(true);return false">Check All</a>
&nbsp;/&nbsp; <a href="#" onClick="selectAll(false);return false">Uncheck All</a>
&nbsp;/&nbsp;
--><span id="hiddenRowsCount"></span>
<a href="#" onClick="showRows('<?php echo $count;?>');return false">Show All Hidden Rows</a>
&nbsp;&nbsp; <?php echo $count;?> Total Rows
</td></tr>
</table>

<div align="center"><?php echo "<a href=\"". my_href_link('summary_sheet.php')."\">" . my_image(DIR_WS_IMAGES.'btnBack.gif','Back To Summary Sheet Menu'); ?></a></div>

<?php echo my_draw_hidden_field("product_count",$count)."\n";?>
<?php echo my_draw_hidden_field("hidden_row_count","0");?>

<?php
}
//*************************************************************************
//*************************** SHOW ORDERS *********************************
//*************************************************************************

elseif( $action == 'show_orders' ){
	if($rep_group == 0)
	  $repGroupsClause = "";
	else
	  $repGroupsClause = "AND a.accounts_rep_group=".$rep_group;


    $arrOrderStatus = array();
    $order_status_sql = "SELECT * from order_status";
    $order_status_query = my_db_query($order_status_sql);
    while($order_status = my_db_fetch_array($order_status_query)){
        $arrOrderStatus[$order_status['order_status_id']] = $order_status['order_status_name'];
    }

    if($debug_mode){//DEBUG
        echo "accounts_number = $client_account_number<br>";
        echo "<b>order_status_sql</b> = ".$order_status_sql."<br>";
    }

    //The $status variable filters by order_status, if required by the user
    $status = ($order_status_id == 0 )? "" : " AND o.order_status=$order_status_id" ;

    if( (strlen($toDate)>0) && (strlen($fromDate)>0) ){
        $arrToDate = split("/",$toDate);
        $arrFromDate = split("/",$fromDate);
        $toDate = $arrToDate[2]."-".$arrToDate[0]."-".$arrToDate[1];
        $fromDate = $arrFromDate[2]."-".$arrFromDate[0]."-".$arrFromDate[1];

        $show_orders_sql = "SELECT o.order_id, o.customer_name, o.purchase_date,
        o.customer_shipping_method, o.order_status, o.purchase_order_number,
        o.customer_invoice_number,
        o.order_comments FROM orders o, accounts a WHERE purchase_date <  '$toDate'
        AND purchase_date >=  '$fromDate' AND o.accounts_number =  '$accounts_number' 
        ".$repGroupsClause."
        AND a.accounts_number =  o.accounts_number $status";
    }else{
        $show_orders_sql = "SELECT o.order_id, o.customer_name, o.purchase_date,
        o.customer_shipping_method, o.order_status, o.purchase_order_number,
        o.customer_invoice_number,
        o.order_comments FROM orders o, accounts a WHERE o.accounts_number =
        '$accounts_number' ".$repGroupsClause." 
         AND a.accounts_number =  o.accounts_number $status";
    }
    $show_orders_query = my_db_query($show_orders_sql);

    if($debug_mode){//DEBUG
        echo "<b>show_orders_sql</b> = ".$show_orders_sql."<br>";
    }

?>

<?php echo my_draw_form('summary_sheet',my_href_link('summary_sheet.php','action=summary_sheet'), 'post' ) . "\n";?>
<?php echo my_draw_hidden_field('accounts_number',$accounts_number); ?>
<?php echo my_draw_hidden_field("orderCount", mysql_num_rows($show_orders_query)); ?>


<table align="center"  border="0" class="thinOutline" cellspacing="0" cellpadding="5">
<tr class="tableHeader">
    <th class="mediumBoldText">Select<?php echo my_draw_spacer('spacer.gif',10,1); ?></th>
    <th class="mediumBoldText">Customer<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText">Purchase&nbsp;Date<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText">Shipping<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText">Order&nbsp;Status<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText">PO&nbsp;No.<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText">Invoice&nbsp;No.<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
</tr>
<?php
    if( mysql_num_rows($show_orders_query) == 0){
?>

<tr>
    <td class="mediumText" align="center" colspan=7>No Records Return</td>
</tr>

<?php
    }else{
     while($show_orders = my_db_fetch_array($show_orders_query)){
?>
    <tr class="tableRowColor">
        <td align=center><?php echo my_draw_checkbox_field("arrOrderId[]",$show_orders['order_id']); ?></td>
        <td class="smallText"><?php echo $show_orders['customer_name']; ?></td>
        <td class="smallText"><?php echo $show_orders['purchase_date']; ?></td>
        <td class="smallText"><?php echo $show_orders['customer_shipping_method']; ?></td>
        <td class="smallText" align=center><?php echo $arrOrderStatus[$show_orders['order_status']]; ?></td>
        <td class="smallText"><?php echo $show_orders['purchase_order_number']; ?></td>
        <td class="smallText" align="center"><?php echo my_null_replace($show_orders['customer_invoice_number']); ?></td>
    </tr>
<?php
    }//end of while loop
    }//end of else for mysql_num_fields($show_orders_query) == 0
?>
</table>

<br />
<div align="center">
    <?php echo "<a href=\"". my_href_link('summary_sheet.php')."\">" . my_image(DIR_WS_IMAGES.'btnBack.gif','Back To Summary Sheet Menu'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo "<a href=\"#\" onClick='showSummarySheet();return true'>" .
        my_image(DIR_WS_IMAGES.'btnSubmitOnWhite.gif','Submit'); ?></a>

</div>

<?php
    }//end of "show_orders"
    elseif($action != 'show_orders' && $action != 'summary_sheet' ){

//*************************************************************************
//**************************** MAIN MENU **********************************
//*************************************************************************
	if($rep_group == 0)
	  $repGroupsClause = "";
	else
	  $repGroupsClause = "AND a.accounts_rep_group=".$rep_group;



    $arrNewOrders = array();
    $new_orders_sql = "SELECT a.accounts_username, COUNT(o.order_status) AS NewOrders
    FROM orders o, accounts a WHERE o.accounts_number = a.accounts_number
    AND a.accounts_rep_group=".$rep_group." AND o.order_status=10 GROUP BY a.accounts_username";
    $new_orders_query = my_db_query($new_orders_sql);
    while($new_orders = my_db_fetch_array($new_orders_query) ){
        $arrNewOrders[$new_orders['accounts_username']] = $new_orders['NewOrders'];
    }

    $accounts_sql = "SELECT * FROM accounts a WHERE 1 ".$repGroupsClause." ORDER BY a.accounts_username";
    $accounts_query = my_db_query($accounts_sql);
    while( $accounts = my_db_fetch_array($accounts_query)  ){

        if( $arrNewOrders[$accounts['accounts_username']] > 0 ){
            $accounts['accounts_username'] = "(".$arrNewOrders[$accounts['accounts_username']].
            ")".$accounts['accounts_username'];
        }
        $arrAccounts[] = array('id' => $accounts['accounts_number'],
                              'text' => $accounts['accounts_username']);
    }


    $order_status_sql = "SELECT * FROM order_status where 1 ORDER BY order_status_sort";
    $order_status_query = my_db_query($order_status_sql);
    $arrOrderStatus[] = array('id' => '0','text' => 'All Orders');
    while( $order_status = my_db_fetch_array($order_status_query)  ){
        $arrOrderStatus[] = array('id' => $order_status['order_status_id'],
                              'text' => $order_status['order_status_name']);
    }
?>

<?php echo my_draw_form('summary_sheet',my_href_link('summary_sheet.php', 'action=show_orders'));?>
<br /><br /><br />


<table width="300" align="center" border=0>

<tr><th colspan=2></th><th colspan=2>Optional Fields</th></tr>
<tr><th>Accounts</th><th>Status</th><th>From</th><th>To</th></tr>
<tr>
<th valign=top><?php echo my_draw_pull_down_menu('accounts_number',$arrAccounts); ?></th>
<td valign=top><?php echo my_draw_pull_down_menu('order_status_id',$arrOrderStatus); ?></td>

<td align=center><input type=text name="fromDate" size=10 /><a href="javascript:cal2FROM.popup();"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date"></a></td>

<td align=center><input type=text name="toDate" size=10 /><a href="javascript:cal2TO.popup();"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date"></a></td>
</tr>


<tr>
<td align=center colspan="4">
<br/>
<?php echo my_image_submit('btnSubmitOnWhite.gif','Show Orders'); ?>
</td>
</tr>
</table>

<script language="JavaScript">
<!-- // create calendar object(s) just after form tag closed
 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
 // note: you can have as many calendar objects as you need for your application
//var cal1 = new calendar1(document.forms['process_order'].elements['fromDate']);
//cal1.year_scroll = true;
//cal1.time_comp = false;
var cal2FROM = new calendar2(document.forms['summary_sheet'].elements['fromDate']);
cal2FROM.year_scroll = false;
cal2FROM.time_comp = false;
var cal2TO = new calendar2(document.forms['summary_sheet'].elements['toDate']);
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
