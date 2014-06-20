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

if( isset($_POST['customer_name']) ){
	$customer_name = $_POST['customer_name'];
}

if( isset($_POST['purchase_order_number']) ){
	$purchase_order_number = $_POST['purchase_order_number'];
}

if( isset($_POST['accounts_number']) ){
	$accounts_number = $_POST['accounts_number'];
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Order Entry</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
  <script language="JavaScript" src="debugInfo.js"></script>
</head>



<script language="javascript1.2">

function submitAddOrder(){
$orderIsReady = true;

    //Customer Name Validation
    if( document.forms[0].customer_name.value.length == 0 ){
        alert("Missing value found for a Customer Name.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_name.focus();
        $orderIsReady = false;
        return;
    }



    //Customer Address 1 Validation
    if( document.forms[0].customer_address1.value.length == 0 ){
        alert("Missing value found for a Customer Address Information.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_address1.focus();
        $orderIsReady = false;
        return;
    }


    //Customer City Validation
    if( document.forms[0].customer_city.value.length == 0 ){
        alert("Missing value found for a Customer City.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_city.focus();
        $orderIsReady = false;
        return;
    }

    //Customer City State
    if( document.forms[0].customer_state.value.length == 0 ){
        alert("Missing value found for a Customer State.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_state.focus();
        $orderIsReady = false;
        return;
    }


    //Customer City Zip
    if( document.forms[0].customer_zip.value.length == 0 ){
        alert("Missing value found for a Customer Zip.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_zip.focus();
        $orderIsReady = false;
        return;
    }


    $orderSize = parseInt(document.forms[0].order_size.value);
    for($i=0; $i<$orderSize; $i++){

        //validate quantities are not empty fields
        $quantity = eval("document.forms[0].product_quantity_"+$i+".value");
        if( $quantity.length == 0 ){
            alert("Empty quantities are not allowed.  Please check the quantity fields.");
            eval("document.forms[0].product_quantity_"+$i+".focus()");
            $orderIsReady = false;
            return;
        }

        //validate sizes match the corresponding products
        $sIndex = parseInt(eval("document.forms[0].product_size_"+$i+".selectedIndex"));
        $productSize = eval("document.forms[0].product_size_"+$i+".options["+$sIndex+"].text").replace(" ","");

        $sIndex = parseInt(eval("document.forms[0].product_name_"+$i+".selectedIndex"));
        $productName = eval("document.forms[0].product_name_"+$i+".options["+$sIndex+"].text");

        $arrProductSizeCode = $productSize.split("-");


        if( !($productName.indexOf($arrProductSizeCode[0]) >= 0) &&
                $arrProductSizeCode[0].indexOf("NA") == -1){
            $msg = "Size: " + $productSize + " doesn't go with Product: "+$productName + "\nYou need to match the size with the appropriate product or use NA for hats, gifts, etc....\nExample: Match a youth size with youth product, not an adult product."
            alert($msg);
            $orderIsReady = false;
            return;
        }

    }

    if(  document.forms[0].customer_order_no.value.length == 0 ){
        var orderOK = confirm("You don't have a value for the Order Number.  \nIf this is correct click OK, otherwise click Cancel.");
        if(orderOK){
            $orderIsReady = true;
        }else{
            $orderIsReady = false;
            document.forms[0].customer_order_no.focus();
            return;
        }
    }

//**********************************************************************
//********** Preventing Duplicate Products in Order ********************
//**************************** Begin ***********************************
	$arrProducts = new Array($orderSize);
	$df = document.forms["add_order"];
	$sizeIndex = 0;
	$nameIndex = 0;
	for($i=0; $i<$orderSize; $i++){
		$sizeIndex = $df.elements["product_size_"+$i].selectedIndex;
		$nameIndex = $df.elements["product_name_"+$i].selectedIndex;
		$sizeValue = $df.elements["product_size_"+$i].value;
		$nameValue = $df.elements["product_name_"+$i].value;
	 	$arrProducts[$i] = $sizeValue+$nameValue;
	}

	for($i=0; $i<$orderSize; $i++){
	 	$arrProducts[$i]

		for($j=0; $j<$orderSize; $j++){
		 	if($i == $j){
				continue;
			}else{
				if( $orderIsReady && ($arrProducts[$i] == $arrProducts[$j]) ){
				 	$orderIsReady = false;
				 	alert("Two or more products have identical sizes and names. Each product row has to be unique in order to submit an order.  You may have to start over again and set the unique item count in this order properly.");
				 	break;
				 }
			}
		}
	 	if( !$orderIsReady )
	 	break;
	}
//**************************** End ***********************************



    if($orderIsReady){
        document.forms[0].submit();
    }

}//end of submitAddOrder function
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
//*************************************************************************
//************************ ORDER ADD FORM *********************************
//*************************************************************************
if( $action == 'ord_add_start' &&  is_numeric($order_size) ){

    intval($order_size);

    $ord_inventory_sql = "SELECT * FROM products WHERE product_enabled = 1 ORDER BY product_model";
    $ord_inventory_query = my_db_query($ord_inventory_sql);
    while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
        $productText =$ord_inventory['product_model']." / ".
        stripslashes(substr($ord_inventory['product_name'], 0, 25))." / ".
        stripslashes(substr($ord_inventory['product_desc'], 0, 15));
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

    $ord_sizes_sql = "SELECT * FROM sizes WHERE 1 ORDER BY sizes_sort";
    $ord_sizes_query = my_db_query($ord_sizes_sql);
    $sizes_default = 1;
    while($ord_sizes = my_db_fetch_array($ord_sizes_query)){
        $arrSizes[] = array('id' => $ord_sizes['sizes_id'],
                          'text' => $ord_sizes['sizes_name']);
        if( $ord_sizes['sizes_default'] == 1 ) $sizes_default = $ord_sizes['sizes_id'];
    }

?>
<?php echo my_draw_form('add_order',my_href_link('orders.php', 'action=ord_add'));?>

<?php echo my_draw_hidden_field('order_size',$order_size);?>
<?php echo my_draw_hidden_field('accounts_number',$_SESSION['client_account_number']);?>
<?php
$po_number = strtoupper($_SESSION['client_prefix']).date("mdyHis");
echo my_draw_hidden_field('purchase_order_number',$po_number);
?>


<table width=500px align="center" border=0  class="thinOutline" cellspacing=0>
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
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_country','United States','size=30'); ?>&nbsp;*</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Shipping Method:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_pull_down_menu('shipping_method',$arrShipping, $shipping_default); ?></td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Your Order Number:</td>
    <td align=left class="mediumBoldText" ><?php echo my_draw_input_field('customer_order_no','','size=30'); ?></td>
</tr>

<tr class="tableRowColor">
    <td align=center class="mediumBoldText" colspan=2 ><?php echo my_draw_checkbox_field("isRush"); ?> Check to <font color="red"><strong><em>RUSH</em></strong></font> this Order (fees will be applied)

    </td>
</tr>
</table>
<br />

<div align="center" class="smallText"><font color="red"><strong>Note: Use size "NA" for items where size doesn't apply, such as gifts, hats, etc...</strong></font></div>
<table width=500px align="center" border=0  class="thinOutline" cellspacing=0>

<tr class="tableHeader">
<td colspan=3 class="mediumBoldText" align="center">
P R O D U C T S
</td>
</tr>


<tr class="tableRowColor">
    <td align=center class="mediumBoldText" >Quantity</td>
    <td align=left class="mediumBoldText" >Size</td>
    <td align=left class="mediumBoldText" >Product Code / Name</td>
</tr>

<?php
    for($i=0; $i<$order_size;$i++){
?>
<tr class="tableRowColor">
    <td align=center class="mediumBoldText"><?php echo my_draw_input_field('product_quantity_'.$i,'','size=2'); ?></td>
    <td><?php echo my_draw_pull_down_menu('product_size_'.$i,$arrSizes,$sizes_default); ?></td>
    <td><?php echo my_draw_pull_down_menu('product_name_'.$i,$arrInventory,''); ?></td>
</tr>
<?php
}
?>
</table>
<br />

<table width=500px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<td colspan=3 class="mediumBoldText" align="center">
C O M M E N T S
</td>
</tr>

<tr class="tableRowColor">
<td colspan=3 align="center">
<?php echo my_draw_textarea_field('order_comments','soft','40','5'); ?>
</td>
</tr>

<tr  class="tableFooter">
    <td colspan="3" align="CENTER">
        <a href="<?php echo my_href_link('orders.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo "<a href=\"#\" onClick='submitAddOrder();return true'>" .
        my_image(DIR_WS_IMAGES.'btnSubmit.gif','Submit Order'); ?></a>
    </td>
</tr>

</table>

<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $action == 'ord_mod_start' ){

//Intentionally not doing a modify
?>


<?php

}else{
//*************************************************************************
//****************************** ADD ORDER ******************************
//*************************************************************************

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
                $ord_sizes_sql = "SELECT * FROM sizes WHERE 1 ORDER BY sizes_sort";
                $ord_sizes_query = my_db_query($ord_sizes_sql);
                while($ord_sizes = my_db_fetch_array($ord_sizes_query)){
                    $arrSizes[$ord_sizes['sizes_id']] =  $ord_sizes['sizes_name'];
                    $arrPrices[$ord_sizes['sizes_name']] =  $ord_sizes['sizes_fee'];
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

                $isRush = ($isRush == "on")? 1 : 0 ;
                $rushFee = ($isRush > 0)?"5":"0";

				if($shortShippingState = array_key_exists(strtoupper(trim($customer_state)), $arrStateNames)){
					$shortShippingState = $arrStateNames[strtoupper(trim($customer_state))];
				}else{
					$shortShippingState = $customer_state;
				}


                $ord_add_sql = sprintf("INSERT INTO `orders` (`customer_name` ,
                `customer_address1` , `customer_address2` ,`customer_city` ,
                 `customer_state` ,
                `customer_zip` , `customer_country` , `customer_shipping_method` , 
                `customer_shipping_id` ,
                `customer_invoice_number` , `purchase_date` , `accounts_number`,
                `purchase_order_number` , `order_comments`, `order_status`,
                `dropship_fee`,
                `handling_fee`, `isRush`, `rush_fee`, `misc_desc` ) VALUES
                (%s, %s, %s, %s, %s, %s, %s, %s, %d, %s, '".date("y-m-d h:i:s")."', %s, %s, %s, %d, %01.2f,%01.2f,%d, %d, %s)",
                "'".str_replace(",","",mysql_real_escape_string($customer_name))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_address1))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_address2))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_city))."'",
                "'".str_replace(",","",mysql_real_escape_string($shortShippingState))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_zip))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_country))."'",
                "'".str_replace(",","",mysql_real_escape_string($arrShipping[$shipping_method]))."'",
                $shipping_method,
                "'".str_replace(",","",$customer_order_no)."'",
                "'".mysql_real_escape_string($accounts_number)."'",
                "'".mysql_real_escape_string($purchase_order_number)."'",
                "'".str_replace(",","",mysql_real_escape_string($order_comments))."'",
                $fees['order_status_id'],
                $arrFees['Drop Ship'],$arrFees['Handling'], $isRush,$rushFee,"''");

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





                    $ord_add_product_sql = sprintf("INSERT INTO
                    `orders_products` (`order_id` , `order_product_quantity` ,
					`order_product_size` , `order_product_name`,
					`order_product_model`, `order_product_charge`
                     )VALUES (%d, %d, %s, %s, %s, %f)", $ord_add_insert_id,
                     $_POST['product_quantity_'.$i],
                    "'".strtoupper(mysql_real_escape_string($arrSizes[$_POST['product_size_'.$i]]))."'",
                    "'".mysql_real_escape_string(trim($arrInventory[$_POST['product_name_'.$i]]))."'",
					"'".$productModel."'",
					$arrPrices[$arrSizes[$_POST['product_size_'.$i]]]);

                    my_db_query($ord_add_product_sql);
                }


                if( $ord_add_insert_id != 0 ){
                    $orderNum = (strlen($_POST['customer_order_no']) > 0 && $_POST['customer_order_no'] != "NULL")?"<small>(Order No.: ".
                    $_POST['customer_order_no'].")</small>":"";
                    echo "<div align=center class=\"success\">Order
                    Submitted Successfully for ".$_POST['customer_name']." ".$_POST['orderNum']."
                    <br><br><br><br> Return to the Main Menu</div>";
                    echo "<div align=center class=\"smallText\">A copy will be
                    emailed to you for your records</div>";

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
//*************************************************************************
//*************************** DELETE ORDER ******************************
//*************************************************************************
    if( $action == 'ord_del'  ){
        $ord_del_sql ="delete from products where product_id=".$pId;
        $ord_del_query = my_db_query($ord_del_sql);
        if( $ord_del_query == 1){
            echo "<div align=center class=\"success\">Product Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Product Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** MODIFY ORDER ******************************
//*************************************************************************
    if( $action == 'ord_mod'  ){
        $ord_mod_sql ="UPDATE `products` SET `product_name` = '$product_name',
        `product_model` = '$product_model',`product_type` = '$product_type',
        `product_sizes` = '$product_sizes',`product_desc` = '$product_desc'
        WHERE `product_id`=".$pId;
        $ord_mod_query = my_db_query($ord_mod_sql);
        if( $ord_mod_query == 1){
            echo "<div align=center class=\"success\">Product Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Product Not Modified</div>";
        }
    }
//*************************************************************************
//*************************** SET ORDER SIZE ******************************
//*************************************************************************


if( $_GET['action']  != 'ord_add'  ){
?>
<?php echo my_draw_form('order_size',my_href_link('orders.php', 'action=ord_add_start'));?>
<br /><br /><br />


<table width="500" align="center" border=0>
<tr>
<td align="center" class="mediumBoldText">How many unique products are in this order?:
<input name=order_size type=text size=2 /></td>
</tr>
<tr>
<td align="center" class="mediumBoldText"><font color="#D0D0D0">(Quantities are added for each unique product on the next page.)</font></td>
</tr>
<tr>
<td align=center>
<?php echo my_image_submit('btnSubmitOnWhite.gif','Set Order Size'); ?></a>
</td>
</tr>
</table>

<?php
}//end of if $action != 'ord_add'
}
?>
</form>
</body>
</html>
