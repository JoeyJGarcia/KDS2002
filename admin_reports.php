<?php
require('includes/application_top.php');
$debug=false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Admin Reports</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
    <!-- American format mm/dd/yyyy -->
    <script language="JavaScript" src="calendar2.js"></script><!-- Date only with year scrolling -->
<script language="JavaScript" src="debugInfo.js"></script>

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
        <td colspan=3 align="center" class="largeBoldText">A D M I N  &nbsp;&nbsp; R E P O R T S</td>
    </tr>
</table>


<br />
<br />


<?php
if($_GET['action'] != 'show_report'  ){

//*************************************************************************
//**************************** MAIN MENU **********************************
//*************************************************************************
	if($_SESSION['rep_group'] == 0)
	  $repGroupsClause = "";
	else
	  $repGroupsClause = "AND a.accounts_rep_group=".$_SESSION['rep_group'];


    $arrNewOrders = array();
    $new_orders_sql = "SELECT a.accounts_username, COUNT(o.order_status) AS NewOrders
    FROM orders o, accounts a WHERE o.accounts_number = a.accounts_number
    AND o.order_status=10 ".$repGroupsClause." GROUP BY a.accounts_username";
    $new_orders_query = my_db_query($new_orders_sql);
    while($new_orders = my_db_fetch_array($new_orders_query) ){
        $arrNewOrders[$new_orders['accounts_username']] = $new_orders['NewOrders'];
    }

    $accounts_sql = "SELECT * FROM accounts a WHERE 1 ".$repGroupsClause."
	ORDER BY accounts_username";
    $accounts_query = my_db_query($accounts_sql);
    $arrAccounts[] = array('id' => 0,'text' => 'Show All Clients');
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
        $arrOrderStatus[] = array('id' => 0,'text' => 'Show All Orders');
    while( $order_status = my_db_fetch_array($order_status_query)  ){
        $arrOrderStatus[] = array('id' => $order_status['order_status_id'],
                              'text' => $order_status['order_status_name']);
    }

    $sizes_sql = "SELECT distinct sizes_name, sizes_id FROM sizes where 1 ORDER BY sizes_name";
    $sizes_query = my_db_query($sizes_sql);
    $arrSizes[] = array('id' => 0,'text' => 'Show All Sizes');
    while( $sizes = my_db_fetch_array($sizes_query)  ){
        $arrSizes[] = array('id' => $sizes['sizes_name'],
                              'text' => $sizes['sizes_name']);
    }


      $ord_inventory_sql = "SELECT DISTINCT order_product_name AS Name FROM orders_products
      WHERE 1 ORDER BY order_product_name";
      $ord_inventory_query = my_db_query($ord_inventory_sql);
      $arrInventory[] = array('id' => 0,'text' => 'Show All Products');
      while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
        $arrInventory[] =  array('id' => $ord_inventory['Name'],'text' => $ord_inventory['Name']);
      }

?>

<?php echo my_draw_form('admin_reports',my_href_link('admin_reports.php', 'action=show_report'));?>
<br /><br /><br />


<table width="500" align="center" border=0>

<tr><th>

    <table border=0 width=100% class="thinOutline" cellspacing=0>

    <tr class="tableHeader">
    <th align=left colspan=3>Select Report</th>
    </tr>

    <tr>
    <td align=right><?php echo my_draw_radio_field('report','order_total','checked');?></td>
    <td align=left colspan=2>Order Total</td>
    </tr>

    <tr>
    <td align=right><?php echo my_draw_radio_field('report','number_orders');?></td>
    <td align=left colspan=2>Number Of Orders</td>
    </tr>


    <tr>
    <td align=right><?php echo my_draw_radio_field('report','number_products_client');?></td>
    <td align=left colspan=2>Products Sold - By Client</td>
    </tr>

    <tr>
    <td align=right><?php echo my_draw_radio_field('report','number_products_name');?></td>
    <td align=left>Products Sold - By Name</td>
    <td align=left><?php echo my_draw_pull_down_menu('order_product_name',$arrInventory,'Show All Products'); ?></td>
    </tr>

    <tr>
    <td align=right><?php echo my_draw_radio_field('report','all_sizes');?></td>
    <td align=left>Sizes Report</td>
    <td align=left><?php echo my_draw_pull_down_menu('sizesId',$arrSizes,'Show All Sizes'); ?></td>
    </tr>

    </table>

</th></tr>
<tr><th>
    <table border=0 width=100% class="thinOutline" cellspacing=0>
    <tr class="tableHeader"><th align=left colspan=4>Optional Filter Fields</th></tr>
    <tr><th>Accounts</th><th>Status</th><th>From</th><th>To</th></tr>
    <tr>
    <th valign=top><?php echo my_draw_pull_down_menu('accounts_number',$arrAccounts); ?></th>
    <td valign=top><?php echo my_draw_pull_down_menu('order_status_id',$arrOrderStatus); ?></td>

    <td align=center><input type=text name="fromDate" size=10 /><a href="javascript:cal2FROM.popup();"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date"></a></td>

    <td align=center><input type=text name="toDate" size=10 /><a href="javascript:cal2TO.popup();"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date"></a></td>
    </tr>
    </table>
</th></tr>
<tr>
<td align=center>
<br/>
<?php echo my_image_submit('btnSubmitOnWhite.gif','Show Orders'); ?>
</td>
</tr>
</table>

<script type="text/JavaScript">
<!-- // create calendar object(s) just after form tag closed
 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
 // note: you can have as many calendar objects as you need for your application
//var cal1 = new calendar1(document.forms['process_order'].elements['fromDate']);
//cal1.year_scroll = true;
//cal1.time_comp = false;
var cal2FROM = new calendar2(document.forms['admin_reports'].elements['fromDate']);
cal2FROM.year_scroll = false;
cal2FROM.time_comp = false;
var cal2TO = new calendar2(document.forms['admin_reports'].elements['toDate']);
cal2TO.year_scroll = false;
cal2TO.time_comp = false;
//-->
</script>

<?php
//end of Main Menu
    }else{
if($debug){
	echo "in else :  "."<br>";
}


    $tableWidth=800;


        $whereClause = "";
        $whereClause_acct = "";
        $whereClause_status = "";
        $whereClause_dates = "";

        if( $_POST['accounts_number'] != "0"){
            $whereClause_acct = " o.accounts_number ='".$_POST['accounts_number']."' ";
        }
        if( $_POST['order_status_id'] != "0"){
            $whereClause_status = " o.order_status =".$_POST['order_status_id'];
        }
        if( (strlen($_POST['fromDate']) > 0) && (strlen($_POST['toDate']) > 0) ){
            $whereClause_dates = " o.purchase_date > '".formatDate($_POST['fromDate'])."'
            AND o.purchase_date < '".formatDate($_POST['toDate'])."'";
        }

        if(strlen($whereClause_acct) != 0){
            $whereClause .= (strlen($whereClause) == 0)?$whereClause_acct:" AND ".$whereClause_acct;
        }
        if(strlen($whereClause_status) != 0){
            $whereClause .= (strlen($whereClause) == 0)?$whereClause_status:" AND ".$whereClause_status;
        }
        if(strlen($whereClause_dates) != 0){
            $whereClause .= (strlen($whereClause) == 0)?$whereClause_dates:" AND ".$whereClause_dates;
        }


//**********************************************************
//************* Show Total Orders (SQL) ********************
//**********************************************************
    if( $_POST['report'] == "order_total"){

        $reportInfo1_sql = "SELECT a.accounts_company_name AS Name, SUM(o.dropship_fee) AS DSF,
        SUM(o.handling_fee) AS HF, SUM(o.shipping_charge) AS SC, SUM(o.misc_fee) AS MF,
        o.order_id AS OID
        FROM orders o, accounts a WHERE o.accounts_number=a.accounts_number ";
        $whereClause = (strlen($whereClause) > 0)? " AND " .$whereClause: $whereClause ;

        $reportInfo1_sql .= $whereClause . " GROUP BY Name  ORDER BY DSF DESC";

        $reportInfo2_sql = "SELECT SUM( op.order_product_charge ) AS OPC,
        a.accounts_company_name AS Name
        FROM orders o, orders_products op, accounts a
        WHERE o.order_id = op.order_id AND o.accounts_number = a.accounts_number ";

        $reportInfo2_sql .= $whereClause . " GROUP BY Name ORDER BY OPC DESC";
if($debug){
	echo "reportInfo2_sql :  ".$reportInfo2_sql."<br>";
}

        $reportInfo2_query = my_db_query($reportInfo2_sql);
        while( $reportInfo2 = my_db_fetch_array($reportInfo2_query) ){
            $arrOrderTotal[$reportInfo2['Name']] = $reportInfo2['OPC'];
        }

//**********************************************************
//******** Show Total Orders  - By Client (SQL) ************
//**********************************************************
    }elseif( $_POST['report'] == "number_orders" ){

        $reportInfo_sql = "SELECT a.accounts_company_name AS Name, COUNT(o.order_id) AS OrderCount
        FROM orders o, accounts a WHERE o.accounts_number = a.accounts_number ";

        $whereClause = (strlen($whereClause) > 0)? " AND " .$whereClause: $whereClause ;

        $whereClause .= " GROUP BY Name ORDER BY OrderCount DESC";


//**********************************************************
//****** Show Total Products  - By Client (SQL) ************
//**********************************************************
    }elseif( $_POST['report'] == "number_products_client" ){

        $reportInfo_sql = "SELECT a.accounts_company_name AS Name,
        SUM(op.order_product_quantity) AS Quantity
        FROM orders o, orders_products op, accounts a WHERE
        o.accounts_number = a.accounts_number AND a.accounts_rep_group=".$_SESSION['rep_group']." AND
        o.order_id=op.order_id ";
        $whereClause = (strlen($whereClause) > 0)? " AND " .$whereClause: $whereClause ;
        $whereClause .= " GROUP BY Name  ORDER BY Quantity DESC";

//**********************************************************
//******** Show Total Number of Sizes Sold (SQL) ***********
//**********************************************************
    }elseif( $_POST['report'] == "all_sizes" ){

        if( strlen(trim($_POST['sizesId'])) == 1 && strlen($whereClause) == 0 ){
        //show all sizes, no client or date restrictions
            $reportInfo_sql = "SELECT order_product_size AS Size, order_product_quantity AS
            Quantity FROM orders_products op WHERE 1 ORDER BY Quantity";
        }elseif( strlen(trim($_POST['sizesId'])) > 1 && strlen($whereClause) == 0 ){
        //show specific size, no client or date restrictions
            $reportInfo_sql = "SELECT order_product_size AS Size, order_product_quantity AS
            Quantity FROM orders_products op WHERE  op.order_product_size
            LIKE '".trim($_POST['sizesId'])."'  ORDER BY Quantity";
        }elseif( strlen(trim($_POST['sizesId'])) == 1 && strlen($whereClause) > 0 ){
        //show all sizes, using client or date restrictions
            $reportInfo_sql = "SELECT order_product_size AS Size, order_product_quantity AS
            Quantity FROM orders_products op, orders o
            WHERE op.order_id=o.order_id AND ".$whereClause." ORDER BY Quantity";
        }elseif( strlen(trim($_POST['sizesId'])) > 1 && strlen($whereClause) > 0 ){
        //show specific size, using client or date restrictions
            $reportInfo_sql = "SELECT order_product_size AS Size, order_product_quantity AS
            Quantity FROM orders_products op, orders o
            WHERE   op.order_product_size LIKE '".trim($_POST['sizesId'])."' AND
            op.order_id=o.order_id AND ".$whereClause." ORDER BY Quantity";
        }
//**********************************************************
//****** Show Total Orders  - By Product Name  (SQL) *******
//**********************************************************
    }elseif( $_POST['report'] == "number_products_name" ){

        if( strlen($_POST['order_product_name']) == 1 && strlen($whereClause) == 0 ){
            //show all products, no client or date restrictions
            $reportInfo_sql = "SELECT order_product_name AS Name, order_product_quantity AS
            Quantity FROM orders_products op WHERE 1 ORDER BY order_product_quantity";
        }elseif( strlen($_POST['order_product_name']) > 1 && strlen($whereClause) == 0 ){
            //show specific product, no client or date restrictions
            $reportInfo_sql = "SELECT order_product_name AS Name, order_product_quantity AS
            Quantity FROM orders_products op WHERE op.order_product_name
            LIKE '".$_POST['order_product_name']."' ORDER BY order_product_quantity";
        }elseif( strlen($_POST['order_product_name']) == 1 && strlen($whereClause) > 0 ){
            //show all products, using client or date restrictions
                $reportInfo_sql = "SELECT order_product_name AS Name, order_product_quantity AS
                Quantity FROM orders_products op, orders o WHERE op.order_id=o.order_id AND
                ".$whereClause." ORDER BY order_product_quantity";
        }elseif( strlen($_POST['order_product_name']) > 1 && strlen($whereClause) > 0 ){
            //show specific product, using client or date restrictions
            $reportInfo_sql = "SELECT order_product_name AS Name, order_product_quantity AS
            Quantity FROM orders_products op, orders o  WHERE op.order_id=o.order_id AND
            op.order_product_name LIKE '".$_POST['order_product_name']."' AND ".$whereClause." ORDER BY order_product_quantity";
        }
    }


//debug code
if(false){
    echo "whereClause_acct(".strlen($whereClause_acct).") = ".$whereClause_acct."<br>";
    echo "whereClause_status(".strlen($whereClause_status).") = ".$whereClause_status."<br>";
    echo "whereClause_dates(".strlen($whereClause_dates).") = ".$whereClause_dates."<br><br><br>";
    echo $reportInfo_sql;
}

?>


<table width=<?php echo$tableWidth; ?>  align="center" border=1  class="thinOutline" cellspacing=0>
<?php
//echo "SQL: ".$reportInfo1_sql."<br>";
//**********************************************************
//************** Show Total Orders (HTML) ******************
//**********************************************************
    if($_POST['report'] == "order_total"){
?>
<tr class="tableHeader">
    <td class="mediumText" align=center><strong>Company</strong></td>
    <td class="mediumText" align=center><strong>Total</strong></td>
    <td class="mediumText" align=center><strong>Product Charge</strong></td>
    <td class="mediumText" align=center><strong>Dropship Charge</strong></td>
    <td class="mediumText" align=center><strong>Handling Charge</strong></td>
    <td class="mediumText" align=center><strong>Shipping Charge</strong></td>
    <td class="mediumText" align=center><strong>Misc Charge</strong></td>
</tr>
<?php
if($debug){
	echo "reportInfo1_sql :  ".$reportInfo1_sql."<br>";
}

	$reportInfo1_query = my_db_query($reportInfo1_sql);
    if( mysql_num_rows($reportInfo1_query) == 0){
?>

<tr>
    <td class="mediumText" align="center" colspan=7>No Records Return</td>
</tr>

<?php
    }else{
    while( $reportInfo = my_db_fetch_array($reportInfo1_query) ){

    $GT = $reportInfo['DSF'] + $reportInfo['HF'] + $reportInfo['SC'] +
    $reportInfo['MF'] + $arrOrderTotal[$reportInfo['Name']];

?>
<tr>
    <td class="mediumText" align="center" bgcolor="#EEEEEE"><strong><?php echo $reportInfo['Name'];?></strong></td>
    <td class="mediumText" align="center" bgcolor="#CCCCCC">
    <strong>$<?php echo number_format($GT,2); ?></strong></td>
    <td class="mediumText" align="center" bgcolor="#EEEEEE">$<?php echo $arrOrderTotal[$reportInfo['Name']];?></td>
    <td class="mediumText" align="center" bgcolor="#EEEEEE">$<?php echo $reportInfo['DSF'];?></td>
    <td class="mediumText" align="center" bgcolor="#EEEEEE">$<?php echo $reportInfo['HF'];?></td>
    <td class="mediumText" align="center" bgcolor="#EEEEEE">$<?php echo $reportInfo['SC'];?></td>
    <td class="mediumText" align="center" bgcolor="#EEEEEE">$<?php echo $reportInfo['MF'];?></td>
</tr>
<?php
    }//end of while loop
    }//end of else for mysql_num_fields($reportInfo1_query) == 0
}//end of if statement - if $report == 'order_total'

//**********************************************************
//******** Show Total Orders  - By Client (HTML) ***********
//**********************************************************
elseif($_POST['report'] == "number_orders"){
?>
<tr class="tableHeader">
    <td class="mediumText" align=center><strong>Company</strong></td>
    <td class="mediumText" align=center><strong>Total Number Of Orders</strong></td>
</tr>
<?php
if($debug){
	echo "reportInfo_sql :  ".$reportInfo_sql. $whereClause."<br>";
}

$reportInfo_query = my_db_query($reportInfo_sql . $whereClause);
    if( mysql_num_rows($reportInfo_query) == 0){
?>

<tr>
    <td class="mediumText" align="center" colspan=2>No Records Return</td>
</tr>

<?php
    }else{
    while( $reportInfo = my_db_fetch_array($reportInfo_query) ){
?>
<tr>
    <td class="mediumText" align="center" bgcolor="#EEEEEE"><strong><?php echo $reportInfo['Name'];?></strong></td>
    <td class="mediumText" align="center" bgcolor="#CCCCCC"><strong><?php echo $reportInfo['OrderCount']; ?></strong></td>
</tr>

<?php
    }//end of while loop
    }//end of else for mysql_num_fields($reportInfo_query) == 0
}//end of if statement - if $report == 'number_orders'

//**********************************************************
//****** Show Total Products  - By Client (HTML) ***********
//**********************************************************
elseif($_POST['report'] == "number_products_client"){
?>
<tr class="tableHeader">
    <td class="mediumText" align=center><strong>Company</strong></td>
    <td class="mediumText" align=center><strong>Total Number Of Products Sold</strong></td>
</tr>
<?php
if($debug){
	echo "reportInfo_sql. :  ".$reportInfo_sql. $whereClause."<br>";
}

$reportInfo_query = my_db_query($reportInfo_sql . $whereClause);
    $grandTotal = 0;
    if( mysql_num_rows($reportInfo_query) == 0){
?>

<tr>
    <td class="mediumText" align="center" colspan=2>No Records Return</td>
</tr>

<?php
    }else{

    while( $reportInfo = my_db_fetch_array($reportInfo_query) ){
    $grandTotal = $grandTotal + $reportInfo['Quantity'];
?>
<tr>
    <td class="mediumText" align="center" bgcolor="#EEEEEE"><strong><?php echo $reportInfo['Name'];?></strong></td>
    <td class="mediumText" align="center" bgcolor="#CCCCCC"><strong><?php echo $reportInfo['Quantity']; ?></strong></td>
</tr>

<?php
    }//end of while loop
?>

<tr>
    <td class="mediumText" align="center" bgcolor="#808080"><strong>Grand Total</strong></td>
    <td class="mediumText" align="center" bgcolor="#808080"><strong><?php echo $grandTotal; ?></strong></td>
</tr>

<?php
    }//end of else for mysql_num_fields($reportInfo_query) == 0
}//end of if statement - if $report == 'number_products'

//**********************************************************
//******** Show Total Number of Sizes Sold (HTML) **********
//**********************************************************
elseif($_POST['report'] == "all_sizes"){
?>

<tr class="tableHeader">
    <td class="mediumText" align=center><strong>Size</strong></td>
    <td class="mediumText" align=center><strong>Total Number Of Sizes Sold</strong></td>
</tr>
<?php
if($debug){
	echo "reportInfo_sql :  ".$reportInfo_sql."<br>";
}

$reportInfo_query = my_db_query($reportInfo_sql);
    if( mysql_num_rows($reportInfo_query) == 0){
?>

<tr>
    <td class="mediumText" align="center" colspan=2>No Records Return</td>
</tr>

<?php
    }else{

    $arrSizesCount = array();
    while( $reportInfo = my_db_fetch_array($reportInfo_query) ){
        $key = $reportInfo['Size'];
        $val = $reportInfo['Quantity'];

        if( array_key_exists($key,$arrSizesCount) ){
            $arrSizesCount[$key] = $arrSizesCount[$key] + $val;
        }else{
            $arrSizesCount[$key] = $val;
        }

    }//end of first while loop

    while ( list($key, $val) = each($arrSizesCount)) {
?>
<tr>
    <td class="mediumText" align="center" bgcolor="#EEEEEE"><strong><?php echo $key; ?></strong></td>
    <td class="mediumText" align="center" bgcolor="#CCCCCC"><strong><?php echo $val; ?></strong></td>
</tr>

<?php
    }//end of second while loop
    }//end of else for mysql_num_fields($reportInfo_query) == 0
}//end of if statement - if $report == 'all_sizes'

//**********************************************************
//****** Show Products Sold By  Name  (HTML) ******
//**********************************************************
elseif($_POST['report'] == "number_products_name"){
?>

<tr class="tableHeader">
    <td class="mediumText" align=center><strong>Product Name</strong></td>
    <td class="mediumText" align=center><strong>Total Of All Products Sold</strong></td>
</tr>
<?php
if($debug){
	echo "reportInfo_sql :  ".$reportInfo_sql. "<br>";
}

$reportInfo_query = my_db_query($reportInfo_sql);
    if( mysql_num_rows($reportInfo_query) == 0){
?>

<tr>
    <td class="mediumText" align="center" colspan=2>No Records Return</td>
</tr>

<?php
    }else{
    $arrProductCount = array();
    while( $reportInfo = my_db_fetch_array($reportInfo_query) ){
        $key = $reportInfo['Name'];
        $val = $reportInfo['Quantity'];

        if( array_key_exists($key,$arrProductCount) ){
            $arrProductCount[$key] = $arrProductCount[$key] + $val;
        }else{
            $arrProductCount[$key] = $val;
        }

    }//end of first while loop

    while ( list($key, $val) = each($arrProductCount)) {

?>
<tr>
    <td class="mediumText" align="center" bgcolor="#EEEEEE"><strong><?php echo $key;?></strong></td>
    <td class="mediumText" align="center" bgcolor="#CCCCCC"><strong><?php echo $val; ?></strong></td>
</tr>




<?php
    }//end of while loop
    }//end of else for mysql_num_fields($reportInfo_query) == 0
}//end of if statement - if $report == 'all_products'
}//end of else statement
?>
</table>

<?php
if(isset($_POST['report'])){
?>
<div align="center"><?php echo "<a href=\"". my_href_link('admin_reports.php')."\">" . my_image(DIR_WS_IMAGES.'btnBack.gif','Back To Admin Reports Menu'); ?></a></div>
<?php
}
?>

</form>
</body>
</html>
