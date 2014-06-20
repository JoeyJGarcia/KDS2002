<?php
require('includes/application_top.php');
require('includes/clsOrders.php');
require('includes/clsProducts.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Download Orders</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
    <!-- American format mm/dd/yyyy -->
    <script language="JavaScript" src="calendar2.js"></script><!-- Date only with year scrolling -->
<script type="text/javascript" src="debugInfo.js"></script>
<script type="text/javascript">

function validateForm(){

    var df = document.dl_orders;

    if( (df.fromDate.value.length == 0 ) &&
        (df.toDate.value.length == 0 ) &&
        (df.order_status.selectedIndex == 0 )  ){
        alert("You need to select at least one of the available options. \n" +
        " From Date, To Date, Order Status");
        return;
    }


    var whereValue = "";
    if( df.fromDate.value.length != 0)
        whereValue += "fromDate#";
    if( df.toDate.value.length != 0)
        whereValue += "toDate#";
    if( df.order_status.selectedIndex != 0)
        whereValue += "order_status#";
    if( df.accounts_number.selectedIndex != 0)
        whereValue += "accounts_number#";

    df.useWhereClause.value = whereValue;
    df.submit();
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
        <td colspan=3 align="center" class="largeBoldText">D O W N L O A D   &nbsp;&nbsp; O R D E R S</td>
    </tr>
</table>


<br />
<br />


<?php
if($_GET['action'] != 'writeOrders'  ){

//*************************************************************************
//**************************** MAIN MENU - Begin **************************
//*************************************************************************
	if( !isset($_SESSION['rep_group']) || $_SESSION['rep_group'] == 0)
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

    $arrOrderStatus = array();
        $arrOrderStatus[] = array('id' => "*",
                              'text' => "Select Order Status");
    $order_status_sql = "SELECT DISTINCT os.order_status_name, os.order_status_id
    FROM orders o, order_status os
    WHERE o.order_status=os.order_status_id ORDER BY os.order_status_name";
    $order_status_query = my_db_query($order_status_sql);
    while($order_status = my_db_fetch_array($order_status_query) ){
        $arrOrderStatus[] = array('id' => $order_status['order_status_id'],
                              'text' => $order_status['order_status_name']);
    }



    $accounts_sql = "SELECT * FROM accounts a where 1 ".$repGroupsClause." ORDER BY accounts_username";
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


?>

<?php echo my_draw_form('dl_orders',my_href_link('download_orders_onefile.php', 'action=writeOrders'));?>
<input type="hidden" name="useWhereClause"/>
<br /><br /><br />


<table width="500" align="center" border=0>

<tr><th>



</th></tr>
<tr><th>
    <table border=0 width=100% class="thinOutline" cellspacing=0>
    <tr class="tableHeader"><th></th><th align=center colspan=2><font size=-1>Leave date fields empty to show all orders</font></th></tr>
    <tr><th>Accounts</th><th>From</th><th>To</th></tr>
    <tr>
    <th valign=top><?php echo my_draw_pull_down_menu('accounts_number',$arrAccounts); ?></th>

    <td align=left><img src="images/spacer.gif" width=25 height=1>
    <input type=text name="fromDate" size=10 /><a href="javascript:cal2FROM.popup();"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date"></a></td>

    <td align=center><input type=text name="toDate" size=10 /><a href="javascript:cal2TO.popup();"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date"></a></td>
    </tr>
    <tr>
    <th valign=top></th>

    <td align=left colspan=2><img src="images/spacer.gif" width=25 height=1>
            <?php echo my_draw_pull_down_menu('order_status',$arrOrderStatus); ?>
    </td>
    </tr>
    </table>
</th></tr>
<tr>
<td align=center>
<br/>
<a href="#" onClick="validateForm();return false"><img src="images/btnSubmitOnWhite.gif" title="Submit" border=0></a>

</td>
</tr>
</table>

<script type="text/javascript">
<!-- // create calendar object(s) just after form tag closed
 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
 // note: you can have as many calendar objects as you need for your application
//var cal1 = new calendar1(document.forms['process_order'].elements['fromDate']);
//cal1.year_scroll = true;
//cal1.time_comp = false;
var cal2FROM = new calendar2(document.forms['dl_orders'].elements['fromDate']);
cal2FROM.year_scroll = false;
cal2FROM.time_comp = false;
var cal2TO = new calendar2(document.forms['dl_orders'].elements['toDate']);
cal2TO.year_scroll = false;
cal2TO.time_comp = false;
//-->
</script>

<?php
//*************************************************************************
//**************************** MAIN MENU - End **************************
//*************************************************************************
    }else{

//**********************************************************
//************* Build CSV Lines (SQL) ********************
//**********************************************************



//**********************************************************
//************* Build ORDERS INFO ********************
//**********************************************************

		$arrShippingAlias = array();
		$shipping_alias_query = my_db_query("SELECT shipping_alias, shipping_id FROM `shipping` WHERE 1");
		while($arr_shipping_alias = my_db_fetch_array($shipping_alias_query) ){
			$arrShippingAlias[ $arr_shipping_alias['shipping_id'] ] = $arr_shipping_alias['shipping_alias'];
		}

		$arrPaymentAlias = array();
		$payment_alias_query = my_db_query("SELECT term_alias, term_code FROM `term_codes` WHERE 1");
		while($arr_payment_alias = my_db_fetch_array($payment_alias_query) ){
			$arrPaymentAlias[ $arr_payment_alias['term_code'] ] = $arr_payment_alias['term_alias'];
		}

		$arrStateNames = array();
		$states_query = my_db_query("SELECT state_mapping_shortName AS ShortName,state_mapping_longName AS LongName FROM `state_mapping` WHERE 1");
		while($states = my_db_fetch_array($states_query) ){
			$arrStateNames[ strtoupper($states['LongName']) ] = strtoupper($states['ShortName']);
		}


		$arrRepGroupNames = array();
		$rep_groups_sql = "SELECT * FROM rep_groups ";
		$rep_groups_query = my_db_query($rep_groups_sql);
		while( $rep_groups = my_db_fetch_array($rep_groups_query) ){
		    $arrRepGroupNames[$rep_groups['rep_groups_id']] = $rep_groups['rep_groups_name'];
		}


            $arrWhereClause = split("#",$_POST['useWhereClause']);
            $whereClause = "";

            if( in_array("fromDate",$arrWhereClause) ){
                $useAND = ( strlen($whereClause) > 0 )? " AND ": "";
                $whereClause .= $useAND ." o.purchase_date > '".formatDate($_POST['fromDate'])." 00:00:00' ";
            }

            if( in_array("toDate",$arrWhereClause) ){
                $useAND = ( strlen($whereClause) > 0 )? " AND ": "";
                $whereClause .= $useAND ." o.purchase_date < '".formatDate($_POST['toDate'])." 23:59:59' ";
            }

            if( in_array("order_status",$arrWhereClause) ){
                $useAND = ( strlen($whereClause) > 0 )? " AND ": "";
                $whereClause .= $useAND ." o.order_status = ".$_POST['order_status']." ";
            }

            if( in_array("accounts_number",$arrWhereClause) ){
                $useAND = ( strlen($whereClause) > 0 )? " AND ": "";
                $whereClause .= $useAND ." o.accounts_number = ".$_POST['accounts_number']." ";
            }


		$boolMappingIsMissing = false;


        $orderInfo_sql = "SELECT o.order_id as oID,
        o.accounts_number as AccountNo,
        o.purchase_date AS Purchase_Date,
        o.customer_name AS Ship_Name,
        o.customer_address1 AS Ship_Address1,
        o.customer_address2 AS Ship_Address2,
        o.customer_intl_phone AS Intl_Phone,
        o.customer_city AS Ship_City,
        o.customer_state AS Ship_State,
        o.customer_zip AS Ship_Zip,
        o.customer_country AS Ship_Country,
        o.customer_country_number AS Ship_Country_Number,
		o.customer_invoice_number AS CustInvoiceNumber,
        a.accounts_company_name AS Billing_Name,
        a.accounts_address1 AS Billing_Address1,
        a.accounts_address2 AS Billing_Address2,
        a.accounts_city AS Billing_City,
        a.accounts_state AS Billing_State,
        a.accounts_zip AS Billing_Zip,
        a.accounts_country AS Billing_Country,
        a.accounts_email AS Email,
        a.accounts_ship_id AS ShipID,
        a.accounts_term_code AS PaymentMethod,
        a.accounts_rep_group AS Rep,
        o.customer_shipping_method AS Shipping,
        o.customer_shipping_id AS ShippingID,
        o.purchase_order_number AS PONumber,
        o.order_comments AS Comments,
        o.customer_invoice_number AS Sales_Order_Number,
        o.dropship_fee AS Dropship_Fee,
        o.rush_fee AS Rush_Fee,
        o.isRush  AS isRush,
        o.handling_fee AS Handling_Fee,
        o.shipping_charge AS Shipping_Fee,
        o.misc_fee AS Misc_Fee,
        o.customer_invoice_number AS coID
        FROM orders o, accounts a WHERE $whereClause
        AND o.accounts_number=a.accounts_number ORDER BY oID ASC";

//echo $orderInfo_sql."<p>";

        $inClause = "";
        $repRate = "2.00";
        $arrOrderDate = array();
        $arrOrderInfo = array();
        $orderInfo_query = my_db_query($orderInfo_sql);
        if( mysql_num_rows($orderInfo_query)> 0 ){
        while($orderInfo = my_db_fetch_array($orderInfo_query) ){

        	$shippingState = $orderInfo['Ship_State'];
        	$billingState = $orderInfo['Billing_State'];

        	if( strlen( trim($orderInfo['Ship_State']) ) == 2 ){
        		$shippingState = trim($orderInfo['Ship_State']);
        	}else{
	        	if(array_key_exists(strtoupper($orderInfo['Ship_State']), $arrStateNames) ){
	          		$shippingState = $arrStateNames[strtoupper($orderInfo['Ship_State'])];
	        	}else{
	        		echo "<font color=red>";
					echo "Mapping not found for Shipping State (".$orderInfo['Ship_State']." / ".$orderInfo['oID'].")";
					echo "</font><br>";
					$boolMappingIsMissing = true;
	        	}
        	}

        	if( strlen( trim($orderInfo['Billing_State']) ) == 2 ){
        		$billingState = trim($orderInfo['Billing_State']);
        	}else{
	        	if(array_key_exists(strtoupper($orderInfo['Billing_State']), $arrStateNames) ){
	          		$billingState = $arrStateNames[strtoupper($orderInfo['Billing_State'])];
	        	}else{
	        		echo "<font color=red>";
	        		echo "Mapping not found for Billing State (".$orderInfo['Billing_State']." / ".$orderInfo['oID'].")";
					echo "</font><br>";
					$boolMappingIsMissing = true;
	        	}
	        }


            $arrDateTemp = split(" ",$orderInfo['Purchase_Date']);
            $theDate = $arrDateTemp[0];
            $arrDate = split("-",$theDate);
            $theYear = $arrDate[0];
            $theMonth = $arrDate[1];
            $theDay = $arrDate[2];

            $dropShipFee = ( intval($orderInfo['Dropship_Fee']) == 0 )?"":$orderInfo['Dropship_Fee'];
            $rushFee = ( intval($orderInfo['isRush']) == 1 )?$orderInfo['Rush_Fee']:"0";


             $objOrder = new Order;
             $objOrder->setOrderNumber( prepareDLOrdersText($orderInfo['oID']) );
             $objOrder->setCustomerName( prepareDLOrdersText($orderInfo['Ship_Name']) );
             $objOrder->setAddress1( prepareDLOrdersText($orderInfo['Ship_Address1']) );
             $objOrder->setAddress2( prepareDLOrdersText($orderInfo['Ship_Address2']) );
             $objOrder->setIntlPhone( prepareDLOrdersText($orderInfo['Intl_Phone']) );
             $objOrder->setCity( prepareDLOrdersText($orderInfo['Ship_City']) );
             $objOrder->setState( prepareDLOrdersText($shippingState) );
             $objOrder->setCountry( prepareDLOrdersText($orderInfo['Ship_Country']) );
             $objOrder->setCountryNumber( prepareDLOrdersText($orderInfo['Ship_Country_Number']) );
             $objOrder->setZipcode( prepareDLOrdersText($orderInfo['Ship_Zip']) );
             $objOrder->setClientEmail( strtoupper($orderInfo['Email']) );
             $objOrder->setShippingMethod( prepareDLOrdersText($arrShippingAlias[$orderInfo['ShippingID']]) );
             $objOrder->setPaymentMethod( prepareDLOrdersText($arrPaymentAlias[$orderInfo['PaymentMethod']]) );
             $objOrder->setComments( prepareDLOrdersText($orderInfo['Comments']) );
             $objOrder->setRepID( "HOU" );
             $objOrder->setPONumber( prepareDLOrdersText($orderInfo['PONumber']) .'#'. prepareDLOrdersText($orderInfo['CustInvoiceNumber']) );
             $objOrder->setDropShipID( "ORDER ID# ".$orderInfo['coID'] );
             $objOrder->setDropShipFee( prepareDLOrdersText($dropShipFee) );
             $objOrder->setRushFee( prepareDLOrdersText($rushFee) );
             $objOrder->setAccountNumber( $orderInfo['ShipID'] );
             $objOrder->setRep2Name( prepareDLOrdersText($arrRepGroupNames[$orderInfo['Rep']]) );
             $objOrder->setRep2Rate( prepareDLOrdersText($repRate) );
             $objOrder->setOrderDate( $theMonth."/".$theDay."/".$theYear );



            $arrOrderInfo[$orderInfo['oID']] = $objOrder;
            $orderID = $orderInfo['oID'];

            if( strlen($inClause) == 0 ){
                $inClause = $orderInfo['oID'];
            }else{
                $inClause = $inClause .",". $orderInfo['oID'];
            }

        }


//**********************************************************
//************* Build ITEMS INFO ********************
//**********************************************************


        $orderProducts_sql = "SELECT op.order_id AS oID,
        op.order_product_size AS Size,
        op.order_product_quantity AS Quantity,
        op.order_product_charge AS Unit_Price,
        op.order_product_name AS Product_ID
        FROM orders_products op
        WHERE  op.order_id IN (".$inClause.") ORDER BY oID ASC";



        $strOrderProducts = "";
        $arrOrderProducts = array();
        $orderProducts_query = my_db_query($orderProducts_sql);
        $lineNumber = 0;
        $lastOrderID = 0;

                while($orderProducts = my_db_fetch_array($orderProducts_query) ){
            if( $lastOrderID != (int)$orderProducts['oID'] ){
                $lineNumber = 0;
            }
            $lineNumber++;

            if( strpos($orderProducts['Size'],"NA") > -1){
                $size = "";
            }else{
                $start = strpos($orderProducts['Size'],"-")+1;
                $length = strlen($orderProducts['Size']) - $start;
                $size = trim(substr($orderProducts['Size'], $start,$length));
            }

            $start = 1;
            $length = strpos($orderProducts['Product_ID'],"]")-1;
            $productModel = substr($orderProducts['Product_ID'],$start,$length);
            $productModel = trim(str_replace("[","",$productModel));

            $objOrder = $arrOrderInfo[$orderProducts['oID']];

            $objProduct = new Product(strtoupper(prepareDLOrdersText($productModel.$size)),
            prepareDLOrdersText($orderProducts['Quantity']),
            prepareDLOrdersText($size), prepareDLOrdersText($lineNumber),
            prepareDLOrdersText($orderProducts['oID']),
            prepareDLOrdersText($orderProducts['Unit_Price']));

            $objOrder->setProducts($objProduct);
            $arrOrderInfo[$orderProducts['oID']] = $objOrder;

            $lastOrderID = (int)$orderProducts['oID'];
      }


        //************** Write ORDERS.XML File **************** Begin
       $ordersFilename = "orders.xml";
        if (!$ordersHandle = fopen("./order_files/".$ordersFilename, 'wb')) {
             echo "Cannot open file ($ordersFilename)";
             exit;
        }else{
        	if( !chmod("./order_files/".$ordersFilename, 0666)){
            echo "Cannot change file permissions for $ordersFilename";
        	}
        }
        //****************** KDS Orders Info - Begin **********************


        $tempStart = "<KDS_ORDERS>\n";
         if (fwrite($ordersHandle, $tempStart ) === FALSE) {
             echo "Cannot write to file ($ordersFilename)";
             exit;
         }

      foreach($arrOrderInfo  as $oID => $objOrder ){

          //echo  $objOrder->toXML();
         if (fwrite($ordersHandle, $objOrder->toXML() ) === FALSE) {
             echo "Cannot write to file ($ordersFilename)";
             exit;
         }

       }//end of Foreach Loop

        $tempEnd = "</KDS_ORDERS>\n";
         if (fwrite($ordersHandle, $tempEnd ) === FALSE) {
             echo "Cannot write to file ($ordersFilename)";
             exit;
         }
       if (fclose($ordersHandle) === FALSE) {
           echo "Cannot write to file ($ordersFilename)";
           exit;
       }
        //************** Write ORDERS.XML File **************** End


		if( boolMappingIsMissing){
		echo "<p><strong>Note:</strong><br>Missing Mappings can be eliminated by adding them <a href='".my_href_link("./state_mapping.php")."'>here</a>. <br>Remember Mappings ignore character case.<br>";
		}


        echo "<h3 align=center >".count($arrOrderInfo)." Orders Captured</h3>";

        echo "<h3 align=center >File: <a href='".my_href_link("order_files/".$ordersFilename)."'>".
        strtoupper($ordersFilename)."</a></h3>";


        echo "<div align=center > Click to open text file in the browser<br> or<br> do a right-click over the link to save the file to your computer.</div>";

        echo "<br><h4 align=center > <a href='".my_href_link("download_orders_onefile.php")."'> &lt;&nbsp;&lt; < Return</a></a>";

}else{//end of mysql_num_rows If Test

        echo "<h3 align=center > No Orders Returned</h3>";
        echo "<h4 align=center > <a href='".my_href_link("download_orders_onefile.php")."'>Try Again</a></h3>";
}


}//end of action If Test



?>


</form>
</body>
</html>
