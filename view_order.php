<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - View Order</title>
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

function saveProcessedOrder(){

    $oldStatus = document.forms[1].original_order_status.value;
    $index = document.forms[1].order_status.selectedIndex;
    $newStatus = document.forms[1].order_status[$index].value;

    if(  $oldStatus+'' == $newStatus+''){
        var orderOK = confirm("You haven't changed the order's status.  Is this correct?");
        if(orderOK){
            document.forms[1].submit();
        }else{
            return;
        }
    }else{
        document.forms[1].submit();
    }
}

function showOrder($oId){
    document.forms[0].oId.value = $oId;
    document.forms[0].submit();
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
        <td colspan=3 align="center" class="largeBoldText">V I E W &nbsp;&nbsp; O R D E R</td>
    </tr>
</table>


<br />
<br />


<?php
//*************************************************************************
//************************ PROCESS SINGLE ORDER ***************************
//*************************************************************************
if( $action == 'view_order' ){

    $order_info_sql = "SELECT * FROM orders WHERE order_id=".$oId;
    $order_info_query = my_db_query($order_info_sql);
    $order_info = my_db_fetch_array($order_info_query);

    if($debug_mode){//DEBUG
        echo "<b>order_info_sql</b> = ".$order_info_sql."<br>";
    }

    $order_products_sql = "SELECT * FROM orders_products WHERE order_id=".$oId;
    $order_products_query = my_db_query($order_products_sql);

    if($debug_mode){//DEBUG
        echo "<b>order_products_sql</b> = ".$order_products_sql."<br>";
    }

    $order_status_sql = "SELECT * FROM order_status";
    $order_status_query = my_db_query($order_status_sql);
    while($order_status = my_db_fetch_array($order_status_query)){
        $arrOrderStatus2[$order_status['order_status_id']] = $order_status['order_status_name'];
    }

    if($debug_mode){//DEBUG
        echo "<b>order_status_sql</b> = ".$order_status_sql."<br>";
    }

    $account_sql = "SELECT * FROM accounts WHERE accounts_number='".$order_info['accounts_number']."'";
    $account_query = my_db_query($account_sql);
    $account = my_db_fetch_array($account_query);

    if($debug_mode){//DEBUG
        echo "<b>account_sql</b> = ".$account_sql."<br>";
    }

    $order_history_sql = "SELECT * FROM orders_history WHERE order_id='".$oId."' ORDER BY order_history_date ASC";
    $order_history_query = my_db_query($order_history_sql);

    if($debug_mode){//DEBUG
        echo "<b>order_history_sql</b> = ".$order_history_sql."<br>";
    }

    $prices_sql = "SELECT * FROM sizes WHERE 1 ORDER BY sizes_sort";
    $prices_query = my_db_query($prices_sql);
    $arrPrices = array();
    while($prices = my_db_fetch_array($prices_query)){
        $arrPrices[$prices['sizes_name']] = $prices['sizes_fee'];
    }

    if($debug_mode){//DEBUG
        echo "<b>prices_sql</b> = ".$prices_sql."<br>";
    }

    $tableWidth=800;
?>




<?php echo my_draw_form('process_order',my_href_link('process_order.php', 'action=update_order'));?>

<?php echo my_draw_hidden_field("status", $sql_status) . "\n"; ?>
<?php echo my_draw_hidden_field("fromDate", $sql_fromDate) . "\n"; ?>
<?php echo my_draw_hidden_field("toDate", $sql_toDate) . "\n"; ?>
<?php echo my_draw_hidden_field("accounts_number", $sql_accounts_number) . "\n"; ?>




<table width=<?php echo$tableWidth; ?>  align="center" border=0  class="thinOutline" cellspacing=0>

<tr class="tableHeader">
    <td class="mediumBoldText" colspan=6 align=center>O R D E R  &nbsp;&nbsp; I N F O R M A T I O N</td>
</tr>


<tr class="tableHeader">
    <td colspan=6>

        <table width=100% align="center" border=0  cellspacing=0>

        <tr class="tableHeader">
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
    <td><?php echo my_draw_input_field('orderstatus',$arrOrderStatus2[$order_info['order_status']],'size=25'); ?></td>
    <td>$<?php echo my_draw_input_field('dropship_fee',$order_info['dropship_fee'],'size=5'); ?></td>
    <td>$<?php echo my_draw_input_field('handling_fee',$order_info['handling_fee'],'size=5'); ?></td>
    <td>$<?php echo my_draw_input_field('shipping_charge',$order_info['shipping_charge'],'size=5'); ?></td>
    <td>$<?php echo my_draw_input_field('misc_fee',$order_info['misc_fee'],'size=5'); ?></td>
    <td align=center><?php echo my_draw_input_field('misc_desc',$order_info['misc_desc'],'size=30'); ?></td>
</tr>

<tr class="tableRowColor">
<td class="mediumBoldText" align=center colspan=6>Tracking&nbsp;Number:
<?php echo my_draw_input_field('order_tracking_number',$order_info['order_tracking_number'],'size=30'); ?>
<?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','30','1'); ?>
Invoice&nbsp;Number:
<?php echo my_draw_input_field('order_invoice_number',$order_info['order_invoice_number'],'size=30'); ?>
</td>
</tr>
</table>

<?php echo my_draw_hidden_field('original_order_status',$order_info['order_status']); ?>



<?php echo my_draw_hidden_field('order_id',$oId); ?>


<table width=<?php echo$tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>

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







<table width=<?php echo$tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
    <td class="mediumBoldText" colspan=5 align=center>P R O D U C T S </td>
</tr>

<tr class="tableRowColor">
    <td align=left class="mediumBoldText" >Quantity</td>
    <td align=left class="mediumBoldText" >Size</td>
    <td align=left class="mediumBoldText" >Product Name</td>
    <td align=left class="mediumBoldText" >Product Status</td>
    <td align=left class="mediumBoldText" >Product Charge</td>
</tr>
<?php
    $counter = 0;
    while( $order_products = my_db_fetch_array($order_products_query) ){
?>

<tr class="tableRowColor">
    <td>&nbsp;&nbsp;<?php echo $order_products['order_product_quantity']; ?></td>
    <td><?php echo $order_products['order_product_size']; ?></td>
    <td><?php echo $order_products['order_product_name']; ?></td>
    <td><?php echo $order_products['order_product_status']; ?></td>
    <td>$<?php
    if( $order_products['order_product_charge'] == 0 ){
        $product_fee = $arrPrices[$order_products['order_product_size']];
    }else{
        $product_fee = $order_products['order_product_charge'];
    }
    echo $product_fee;
    ?></td>
</tr>

<?php
    $counter++;
    }

    if(isset($add_items) && strlen($add_items) > 0 ){
        //creating inventory array
        $ord_inventory_sql = "SELECT * FROM products WHERE 1 ORDER BY product_model";
        $ord_inventory_query = my_db_query($ord_inventory_sql);
        while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
            $productText =$ord_inventory['product_model']." / ".
            $ord_inventory['product_sizes']." / ".substr($ord_inventory['product_name'], 0, 20);
            $arrInventory[] = array('id' => $ord_inventory['product_id'],
                              'text' => $productText);
        }
        //create sizes array
        $ord_sizes_sql = "SELECT * FROM sizes WHERE 1 ORDER BY sizes_sort";
        $ord_sizes_query = my_db_query($ord_sizes_sql);
        $arrPrices = array();
        $sizes_default = 1;
        while($ord_sizes = my_db_fetch_array($ord_sizes_query)){
            $arrSizes[] = array('id' => $ord_sizes['sizes_id'],
                              'text' => $ord_sizes['sizes_name']);
            if( $ord_sizes['sizes_default'] == 1 ) $sizes_default = $ord_sizes['sizes_id'];
            $arrPrices[$ord_sizes['sizes_name']] = $ord_sizes['sizes_fee'];
        }
    }
    //for adding new items to an order
    $newCounter = $counter;
    for($i=0; isset($add_items) && $i<$add_items;$i++){
?>

<tr class="tableRowColor">
    <td><?php echo my_draw_input_field(' order_product_quantity_'.$newCounter,'1','size=2'); ?></td>
    <td><?php echo my_draw_pull_down_menu('sizes_id_'.$newCounter,$arrSizes,$sizes_default); ?></td>
    <td><?php echo my_draw_pull_down_menu('product_id_'.$newCounter,$arrInventory,''); ?></td>
    <td><?php echo my_draw_pull_down_menu('order_product_status_'.$newCounter,$arrProductStatus,$order_products['order_product_status']); ?></td>
    <td>$<?php echo my_draw_input_field('order_product_charge_'.$newCounter,'','size=5');?></td>
</tr>


<?php
    $newCounter++;
    }
?>

</table>
<?php echo my_draw_hidden_field('add_items',$add_items); ?>
<?php echo my_draw_hidden_field('order_size',$counter); ?>



<table width=<?php echo$tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
    <td class="mediumBoldText" colspan=5 align=center>C L I E N T  &nbsp;&nbsp; C O M M E N T S </td>
</tr>
<tr class="tableRowColor">
    <td colspan=5 align=center><?php
    if( $order_info['order_comments'] != "NULL"){
        echo $order_info['order_comments'];
    }else{
        echo "None";
    }
    ?></td>
</tr>
</table>


<table width=<?php echo$tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>
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
    <td align=center class="mediumText"><?php echo $order_history['order_history_date']; ?></td>
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
    <td align=center class="mediumText"><?php echo $order_history['order_history_comments']; ?></td>
</tr>
<?php
    }
?>

</table>




</form>

<?php
}

//*************************************************************************
//*************************** SHOW ORDERS *********************************
//*************************************************************************

        if( $action == 'show_orders' ){
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
                o.customer_shipping_method, o.order_status, o.purchase_order_number, o.customer_invoice_number,
                o.order_comments FROM orders o, accounts a WHERE purchase_date <  '$toDate'
                AND purchase_date >=  '$fromDate' AND o.accounts_number =  '$client_account_number'
                AND a.accounts_number =  '$client_account_number' $status";
            }else{
                $show_orders_sql = "SELECT o.order_id, o.customer_name, o.purchase_date,
                o.customer_shipping_method, o.order_status, o.purchase_order_number, o.customer_invoice_number,
                o.order_comments FROM orders o, accounts a WHERE o.accounts_number =
                '$client_account_number' AND a.accounts_number =  '$client_account_number' $status";
            }
            $show_orders_query = my_db_query($show_orders_sql);

            if($debug_mode){//DEBUG
                echo "<b>show_orders_sql</b> = ".$show_orders_sql."<br>";
            }
?>

<?php echo my_draw_form('view_order',my_href_link('view_order.php')) . "\n";?>

<?php echo my_draw_hidden_field("action","view_order") . "\n"; ?>
<?php echo my_draw_hidden_field("oId") . "\n"; ?>
<?php echo my_draw_hidden_field("sql_status", "$status") . "\n"; ?>
<?php echo my_draw_hidden_field("sql_fromDate", "$fromDate") . "\n"; ?>
<?php echo my_draw_hidden_field("sql_toDate", "$toDate") . "\n"; ?>
<?php echo my_draw_hidden_field("sql_accounts_number", "$accounts_number") . "\n"; ?>

<table align="center"  border="0" class="thinOutline" cellspacing="0">
<tr class="tableHeader">
    <th>Customer<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th>Purchase&nbsp;Date<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th>Shipping<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th>Order&nbsp;Status<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th>PO&nbsp;No.<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th>Invoice&nbsp;No.<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <!-- th>Comments<?php echo my_draw_spacer('spacer.gif',40,1); ?></th -->
</tr>
<?php
     while($show_orders = my_db_fetch_array($show_orders_query)){
?>
    <tr class="tableRowColor">
        <td class="smallText"><?php echo "<a href=\"#\" onClick='showOrder(".
        $show_orders['order_id'].")'>" . $show_orders['customer_name'] ."</a>"; ?></td>
        <td class="smallText"><?php echo "<a href=\"#\" onClick='showOrder(".
        $show_orders['order_id'].")'>" . $show_orders['purchase_date'] ."</a>"; ?></td>
        <td class="smallText"><?php echo "<a href=\"#\" onClick='showOrder(".
        $show_orders['order_id'].")'>" . $show_orders['customer_shipping_method'] ."</a>"; ?></td>
        <td class="smallText" align=center><?php echo "<a href=\"#\" onClick='showOrder(".
        $show_orders['order_id'].")'>" . $arrOrderStatus[$show_orders['order_status']] ."</a>"; ?></td>
        <td class="smallText"><?php echo "<a href=\"#\" onClick='showOrder(".
        $show_orders['order_id'].")'>" . $show_orders['purchase_order_number'] ."</a>"; ?></td>
        <td class="smallText"><?php echo "<a href=\"#\" onClick='showOrder(".
        $show_orders['order_id'].")'>" . my_null_replace($show_orders['customer_invoice_number']) .
        "</a>"; ?></td>
        <!--td class="smallText">
        <?php
        //echo "<a href=\"#\" onClick='showOrder(".$show_orders['order_id'].")'>" .
        //my_null_replace($show_orders['order_comments']) ."</a>";
        ?></td -->
    </tr>
<?php
    }//end of while loop
?>
</table>


<br />
<div align="center"><?php echo "<a href=\"". my_href_link('view_order.php')."\">" . my_image(DIR_WS_IMAGES.'btnBack.gif','Back To Process Menu'); ?></a></div>

<?php
    }//end of "show_orders"
    elseif($action != 'show_orders' && $action != 'view_order' ){

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



    $order_status_sql = "SELECT * FROM order_status where 1 ORDER BY order_status_sort";
    $order_status_query = my_db_query($order_status_sql);
    $arrOrderStatus[] = array('id' => '0','text' => 'All Orders');
    while( $order_status = my_db_fetch_array($order_status_query)  ){
        $arrOrderStatus[] = array('id' => $order_status['order_status_id'],
                              'text' => $order_status['order_status_name']);
    }
?>

<?php echo my_draw_form('view_order',my_href_link('view_order.php', 'action=show_orders'));?>
<br /><br /><br />

<?php echo my_draw_hidden_field('accounts_number',$client_account_number); ?>

<table width="300" align="center" border=0>

<tr><th></th><th colspan=2>Optional Fields</th></tr>
<tr><th>Status</th><th>From</th><th>To</th></tr>
<tr>
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
