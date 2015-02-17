<?php
require('includes/application_top.php');
$_GET['debug_mode'] =0;
$debug = true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Process Order</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
<?php
if(isset($_GET['debug_mode']) && $_GET['debug_mode'] == 1){
	$debug_mode = true;
}else{
	$debug_mode = false;
}
//echo "action: ".$_GET['action'];
if($_GET['action'] != 'show_orders' && $_GET['action'] != 'update_order' ){
?>
    <!-- American format mm/dd/yyyy -->
    <script type="text/JavaScript" src="calendar2.js"></script>
	<!-- Date only with year scrolling -->
<?php
}
?>
<script type="text/JavaScript" src="debugInfo.js"></script>
<script type="text/javascript"  src="includes/js/jquery_latest.js"></script>

<script type="text/JavaScript">

function saveProcessedOrder($command){
    var $oldStatus = document.forms[0].original_order_status.value,
        $index = document.forms[0].order_status.selectedIndex,
        $newStatus = document.forms[0].order_status[$index].value,
        $orderSize = parseInt(document.forms[0].order_size.value),
        $shipping = document.getElementById('customer_shipping_id'),
        orderOK = true,
        unitPrice = null,
        row = 0;


    if(  $oldStatus+'' == $newStatus+'' && $command == "save"){
        orderOK = confirm("You haven't changed the order's status.  Is this correct?");
    }

    $('select','#productsTable').each(function(idx, sel){
        row = sel.id.split('_')[3];
        unitPrice = document.getElementById('order_product_charge_'+row);

        if (orderOK && typeof unitPrice.value !== 'undefined' && unitPrice.value === '') {
            alert("Unit Prices can not be saved with blank values.\n You need to select a product and a size before saving order.");
            orderOK = false;
        }

        if (orderOK && $(sel).val() === '0') {
            if (sel.name.indexOf('size') > -1) {
                alert("Sizes can not be saved with blank values.\n Select a size before saving order.");
                orderOK = false;
            } else if (sel.name.indexOf('model') > -1) {
                alert("The Product Model can not be saved with blank values.\n Select a Product Model before saving order.");
                orderOK = false;
            }
        }
    });

    if (jQuery($shipping).val() === "0") {
        alert("A Shipping Method needs to be selected before saving order.\n  Please select a shipping method to continue.");
        orderOK = false;
    }

    if( !orderOK){
        return;
    }

    if($command == "save"){
    	document.process_order_form.action.value = "update_order";
    }else{

    	if(document.process_order_form.radReturnTo[0].checked == true){
    		document.process_order_form.action.value = "show_orders";
            document.process_order_form.returnTo.value = "show_orders";
    	}else{
    		document.process_order_form.action.value = null;
    	}
    }
    document.process_order_form.submit();

}

function blankDSFee(){
	document.process_order_form.dropship_fee.value="0.00";
}

function getProductPrice(el) {

    var $el = jQuery(el),
        url = "http://www.kerussods.com/ajax_controller.php?action=get_product_price_v1&kdssid=<?php echo $_GET['kdssid'];?>",
        accountId = document.getElementById('order_account_number').value,
        rowNum = $el[0].name.split('_')[3],
        priceLvl = '<?=$_SESSION['price_level']?>',
        $productElement = jQuery(document.getElementById('product_name_' + rowNum)),
        size = $(document.getElementById('order_product_size_'+rowNum))[0].value.split(' ')[2],
        productModel = $(document.getElementById('order_product_model_'+rowNum))[0].value;

        url += "&product_size="+size+"&product_code="+productModel+"&accounts_number="+accountId;

        jQuery.ajax({
            url: url,
            dataType: 'json',
            data: {row: rowNum, acctNumber: accountId, priceLvl: priceLvl},
            success: function(data){
                var $inpPriceEl = jQuery(document.getElementById('order_product_charge_'+data.row)),
                    $spnPriceEl = jQuery(document.getElementById('span_order_product_charge_'+data.row));

                if (typeof data.discount !== 'undefined' && data.discount !== null &&
                    typeof data.discount.msg !== 'undefined' && data.discount.msg !== null &&
                    typeof data.discount.price !== 'undefined' && data.discount.price !== null) {

                    $inpPriceEl[0].value = data.discount.price;
                    $spnPriceEl.text(data.discount.price);
                } else {
                    $inpPriceEl[0].value = data.results;
                    $spnPriceEl.text(data.results);
                }
                updateProductCharge(data.row);
            }});
}

function updateProductCharge(index) {
    var unitPrice,
        qtyXcharge,
        qty;

    qty = document.getElementById('order_product_quantity_'+index)
    unitPrice = document.getElementById('order_product_charge_'+index);
    $qtyXcharge = jQuery(document.getElementById('qtyXcharge_'+index));
    $qtyXcharge.text((parseInt(qty.value)*parseFloat(unitPrice.value)).toFixed(2));

}

function saveChanges(el) {
    var newProductName,
        action = 'Order Changes',
        $prodModelId,
        $prodModelName,
        field = '',
        $qtyXcharge,
        index,
        $display,
        original,
        unitPrice,
        $notes,
        value,
        text,
        temp;

    index = el.name.split('_')[3];
    $display = jQuery(document.getElementById('display_order_product_changes_'+index));
    $notes = jQuery(document.getElementById('order_notes'));
    text = ($display.html().length >0) ? $display.html() + '<br>' : '';



    if (el.name.indexOf('quantity') > -1) {
        original = document.getElementById('original_quantity_'+index);
        if (original.value !== '') {
            value = 'Qty_'+index+': ' + original.value + ' -> ' + el.value;
            text += 'Qty: ' + el.value;
            field = 'qty';
        } else {
            original.value = el.value;
        }

    } else if (el.name.indexOf('size') > -1) {
        original = document.getElementById('original_size_'+index);
        if (original.value !== '') {
            value = 'Size_'+index+': ' + original.value + ' -> ' + el.value;
            text += 'Size: ' + el.value;
            field = 'size';
        } else {
            text += 'Size: ' + el.value;
            original.value = el.value;
            value = 'Size_'+index+': ' + original.value + ' -> ' + el.value;
            //Must be the case where a new row was added, therefore set original values for all three elements
            document.getElementById('original_model_'+index).value = document.getElementById('order_product_model_'+index).value;
            document.getElementById('original_quantity_'+index).value = document.getElementById('order_product_quantity_'+index).value;
        }
    } else if (el.name.indexOf('model') > -1) {
        if ( el.value === "1") {
            return;
        }
        temp = el[el.selectedIndex].text.split('/');
        newProductName = ' [ ' + temp[0] + ' ] ' + temp[1] + '/' + temp[2];
        original = document.getElementById('original_model_'+index);
        $prodModelName = jQuery(document.getElementById('order_product_name_'+index));
        $prodModelName.val(newProductName);

        if (original.value !== '') {
            value = 'Model_'+index+': ' + original.value + ' -> ' + el.value;
            text += 'Model: ' + el.value;
            field = 'model';
        } else {
            original.value = el.value;
            text += 'Model: ' + el.value;
            value = 'Model_'+index+': ' + original.value + ' -> ' + el.value;
            $prodModelId = jQuery(document.getElementById('order_product_id_'+index));
            $prodModelId.val('ADDED_PROD');
            action = 'Product Added'
        }
    }

    if (field === 'size' || field === 'model') {
        getProductPrice(el);
    } else if (field === 'qty' ) {
        updateProductCharge(index);
    }

    $display.html(text);
    $notes[0].value = ($notes[0].value.length > 0) ? $notes[0].value + '\n' +  '[** ' + action + ' **]\n' + value : '[** ' + action + ' **]\n' +  value ;
}

function setInvalidProduct(el) {
    var $el = jQuery(el),
        rowNum = $el[0].name.split('_')[3],
        productModel = $(document.getElementById('order_product_model_'+rowNum))[0].value,
        $selSizes,
        option,
        rowNum,
        unitPrice,
        qtyXcharge,
        $productCharge,
        $spanProductCharge,
        qty;

    $selSizes = jQuery(document.getElementById('order_product_size_'+rowNum));
    $selSizes.empty();
    option = document.createElement('option');
    option.value = '1';
    option.text = 'NA';
    $selSizes.append(option.cloneNode(true));
    $selSizes.removeAttr('disabled');


    qty = document.getElementById('order_product_quantity_'+rowNum);
    qty.value = "1";
    unitPrice = document.getElementById('order_product_charge_'+rowNum);
    unitPrice.value = "0";
    $qtyXcharge = jQuery(document.getElementById('qtyXcharge_'+rowNum));
    $qtyXcharge.text("0.00");
    $productCharge = jQuery(document.getElementById('order_product_charge_'+rowNum));
    $productCharge[0].value = "0";
    $spanProductCharge = jQuery(document.getElementById('span_order_product_charge_'+rowNum));
    $spanProductCharge.html("0.00");

}

function getSizes(el) {
    var $el = jQuery(el),
        url = "http://www.kerussods.com/ajax_controller.php?action=get_sizes_v1&kdssid=<?php echo $_GET['kdssid'];?>",
        rowNum = $el[0].name.split('_')[3],
        productModel = $(document.getElementById('order_product_model_'+rowNum))[0].value;

        if (productModel === "1") {
            setInvalidProduct(el);

            return;
        }

        url += "&product_code="+productModel+"&row="+rowNum;

    jQuery.ajax({
        url: url,
        dataType: 'json',
        success: function(data){
            var options = [],
                $selSizes = jQuery(document.getElementById('order_product_size_'+rowNum)),
                option = document.createElement('option');

                $selSizes.empty();

                if (data.sizes.length > 1) {
                    option.value = '0';
                    option.text = 'Select Size'
                    $selSizes.append(option.cloneNode(true));
                }

            if (typeof data.sizes !== 'undefined' &&
                data.sizes !== null &&
                Array.isArray(data.sizes)) {

                for (var i = 0; i < data.sizes.length; i++) {
                    option.value = data.sizes[i].id;
                    option.text = data.sizes[i].text;

                    $selSizes.append(option.cloneNode(true));
                }

                if ( data.sizes.length > 0) {
                    $selSizes.removeAttr('disabled')
                }

            }

            getProductPrice($selSizes);
        }});
}

function addItemRow() {
    var order_size = parseInt(jQuery(document.getElementById('order_size')).val()),
        prodTable = document.getElementById('productsTable'),
        prodTableBody = prodTable.tBodies[0],
        prodRow = document.createElement('tr'),
        cellQty = document.createElement('td'),
        cellUnitPrice = document.createElement('td'),
        cellCharge = document.createElement('td'),
        cellSize = document.createElement('td'),
        cellModel = document.createElement('td'),
        cellStatus = document.createElement('td'),
        cellChanges = document.createElement('td'),
        index = parseInt(document.getElementById('order_size').value),
        rowColor = (index%2 === 0) ? 'tableRowColorEven' : 'tableRowColorOdd',
        elHidden = document.createElement('input'),
        elInpTextQty = document.createElement('input'),
        productRow = prodTable.lastChild.cloneNode(),
        elHiddenOrigModel = document.createElement('input'),
        elHiddenOrigQty = document.createElement('input'),
        elHiddenOrigSize = document.createElement('input'),
        elSpanProdCharge= document.createElement('span'),
        elSpanX = document.createElement('span'),
        elSpanEqual = document.createElement('span'),
        elSpanQtyXCharge = document.createElement('span'),
        elHiddenProdCharge = document.createElement('input'),
        selectSize = document.createElement("SELECT"),
        sizeOption = document.createElement("OPTION"),
        selectModel = document.getElementById('order_product_model_0').cloneNode(true),
        elHiddenProdId = document.createElement('input'),
        elHiddenProdName = document.createElement('input'),
        selectStatus = document.getElementById('order_product_status_0').cloneNode(true),
        elSpanChanges = document.createElement('span');


        prodRow.setAttribute('class',rowColor);
        cellQty.setAttribute('class','align-center');
        cellUnitPrice.setAttribute('class','mediumText');
        cellCharge.setAttribute('class','mediumText');
        elInpTextQty.setAttribute('type','text');
        elInpTextQty.setAttribute('name','order_product_quantity_'+index);
        elInpTextQty.setAttribute('id','order_product_quantity_'+index);
        elInpTextQty.setAttribute('value','1');
        elInpTextQty.setAttribute('size','3');
        elInpTextQty.setAttribute('onchange','saveChanges(this)');
        elHiddenOrigModel.setAttribute('type','hidden');
        elHiddenOrigModel.setAttribute('name','original_model_'+index);
        elHiddenOrigModel.setAttribute('id','original_model_'+index);
        elHiddenOrigModel.setAttribute('value','');
        elHiddenOrigSize.setAttribute('type','hidden');
        elHiddenOrigSize.setAttribute('name','original_size_'+index);
        elHiddenOrigSize.setAttribute('id','original_size_'+index);
        elHiddenOrigSize.setAttribute('value','');
        elHiddenOrigQty.setAttribute('type','hidden');
        elHiddenOrigQty.setAttribute('name','original_quantity_'+index);
        elHiddenOrigQty.setAttribute('id','original_quantity_'+index);
        elHiddenOrigQty.setAttribute('value','');
        elHiddenProdCharge.setAttribute('type','hidden');
        elHiddenProdCharge.setAttribute('name','order_product_charge_'+index);
        elHiddenProdCharge.setAttribute('id','order_product_charge_'+index);
        elHiddenProdCharge.setAttribute('value','');
        jQuery(elSpanX).text('x ');
        elSpanProdCharge.setAttribute('id','span_order_product_charge_'+index);
        jQuery(elSpanEqual).text('= $ ');
        elSpanQtyXCharge.setAttribute('id','qtyXcharge_'+index);
        selectSize.setAttribute("id","order_product_size_"+index);
        selectSize.setAttribute("name","order_product_size_"+index);
        selectSize.setAttribute("disabled","true");
        selectSize.setAttribute('onchange','getProductPrice(this);saveChanges(this)');
        sizeOption.value = "0";
        jQuery(sizeOption).text('Select Size');
        selectSize.appendChild(sizeOption);
        selectModel.setAttribute('name','order_product_model_'+index);
        selectModel.setAttribute('id','order_product_model_'+index);
        selectModel.setAttribute('onchange','getSizes(this);saveChanges(this)');
        selectModel.selectedIndex = 0;
        elHiddenProdId.setAttribute('type','hidden');
        elHiddenProdId.setAttribute('id','order_product_id_'+index);
        elHiddenProdId.setAttribute('name','order_product_id_'+index);
        elHiddenProdName.setAttribute('type','hidden');
        elHiddenProdName.setAttribute('id','order_product_name_'+index);
        elHiddenProdName.setAttribute('name','order_product_name_'+index);
        selectStatus.setAttribute('name','order_product_status_'+index);
        selectStatus.setAttribute('id','order_product_status_'+index);
        selectStatus.selectedIndex = 0;
        elSpanChanges.setAttribute('id','display_order_product_changes_'+index);
        elSpanChanges.setAttribute('class','smallRedText');


        cellQty.appendChild(elInpTextQty);
        cellQty.appendChild(elHiddenOrigModel);
        cellQty.appendChild(elHiddenOrigSize);
        cellQty.appendChild(elHiddenOrigQty);
        cellUnitPrice.appendChild(elSpanX);
        cellUnitPrice.appendChild(elSpanProdCharge);
        cellUnitPrice.appendChild(elHiddenProdCharge);
        cellCharge.appendChild(elSpanEqual);
        cellCharge.appendChild(elSpanQtyXCharge);
        cellSize.appendChild(selectSize);
        cellModel.appendChild(selectModel);
        cellModel.appendChild(elHiddenProdId);
        cellModel.appendChild(elHiddenProdName);
        cellStatus.appendChild(selectStatus);
        cellChanges.appendChild(elSpanChanges);

        prodRow.appendChild(cellQty);
        prodRow.appendChild(cellUnitPrice);
        prodRow.appendChild(cellCharge);
        prodRow.appendChild(cellSize);
        prodRow.appendChild(cellModel);
        prodRow.appendChild(cellStatus);
        prodRow.appendChild(cellChanges);

        prodTableBody.appendChild(prodRow);
        prodTable.appendChild(prodTableBody);

        jQuery('#order_size')[0].value = index + 1;
}
</script>

</head>

<body >
<?php
require('navigation.php');
?>


<table align=right><tr><td><div onclick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');
?>


<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText">P R O C E S S &nbsp;&nbsp; O R D E R</td>
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

    if( $order_info['isRush'] > 0 ){
        $miscColorBegin = "<font color=red><strong>";
        $miscColorEnd = "</font></strong>";
    }else{
        $miscColorBegin = "";
        $miscColorEnd = "";
    }

    if($debug_mode){//DEBUG
        echo "<b>order_info_sql.</b> = ".$order_info_sql."<br>";
    }

    $order_products_sql = "SELECT * FROM orders_products WHERE order_id=".$_GET['oId'];
    $order_products_query = my_db_query($order_products_sql);

    if($debug_mode){//DEBUG
        echo "<b>order_products_sql</b> = ".$order_products_sql."<br>";
    }

    $product_status_sql = "SELECT * FROM product_status";
    $product_status_query = my_db_query($product_status_sql);
    while($product_status = my_db_fetch_array($product_status_query)){
        $arrProductStatus[] = array('id' => $product_status['product_status_id'],
                          'text' => $product_status['product_status_name']);
        $arrProductStatusID[$product_status['product_status_name']] =
            $product_status['product_status_id'];
    }

    $ord_inventory_sql = "SELECT * FROM products WHERE product_enabled = 1 ORDER BY product_model";
    $ord_inventory_query = my_db_query($ord_inventory_sql);
    $arrInventory[] = array('id' => '0','text' => 'Select Product');
    while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
        $productText = $ord_inventory['product_model']." / ".
        stripslashes(substr($ord_inventory['product_name'], 0, 25))." / ".
        stripslashes(substr(str_replace("'","",$ord_inventory['product_desc']), 0, 15));
        $arrInventory[] = array('id' => $ord_inventory['product_model'],
                          'text' => $productText);
    }
    $arrInventory[] = array('id' => '1','text' => '******* INVALID PRODUCT *******');

    if($debug_mode){//DEBUG
        echo "<b>product_status_sql</b> = ".$product_status_sql."<br>";
    }

    $order_status_sql = "SELECT * FROM order_status";
    $order_status_query = my_db_query($order_status_sql);
    while($order_status = my_db_fetch_array($order_status_query)){
        $arrOrderStatus[] = array('id' => $order_status['order_status_id'],
                          'text' => $order_status['order_status_name']);
        $arrOrderStatus2[$order_status['order_status_id']] = $order_status['order_status_name'];
    }

    $ord_shipping_sql = "SELECT * FROM shipping WHERE 1 ORDER BY shipping_name";
    $ord_shipping_query = my_db_query($ord_shipping_sql);
    $arrShipping[] = array('id' => 0, 'text' => "Select Shipping Method");
    while($ord_shipping = my_db_fetch_array($ord_shipping_query)){
        $arrShipping[] = array('id' => $ord_shipping['shipping_id'],
                          'text' => $ord_shipping['shipping_name']);
    }

    if($debug_mode){//DEBUG
        echo "<b>order_status_sql</b> = ".$order_status_sql."<br>";
    }

    $account_sql = "SELECT * FROM accounts WHERE accounts_number=".$order_info['accounts_number'];
    $account_query = my_db_query($account_sql);
    $account = my_db_fetch_array($account_query);

    if($debug_mode){//DEBUG
        echo "<b>account_sql</b> = ".$account_sql."<br>";
    }

    $order_history_sql = "SELECT * FROM orders_history WHERE order_id=".$_GET['oId']." ORDER BY order_history_date DESC";
    $order_history_query = my_db_query($order_history_sql);

    if($debug_mode){//DEBUG
        echo "<b>order_history_sql</b> = ".$order_history_sql."<br>";
    }

    $tableWidth=900;
?>

<?php echo my_draw_form('process_order_form',my_href_link('process_order3.php','oId='.$_GET['oId']));?>

<?php echo my_draw_hidden_field("status", $_POST['status']) . "\n"; ?>
<?php echo my_draw_hidden_field("radWhereClause", $_POST['radWhereClause']) . "\n"; ?>
<?php echo my_draw_hidden_field("days_back", $_POST['days_back']) . "\n"; ?>
<?php echo my_draw_hidden_field("fromDate", $_POST['fromDate']) . "\n"; ?>
<?php echo my_draw_hidden_field("toDate", $_POST['toDate']) . "\n"; ?>
<?php echo my_draw_hidden_field("accounts_number", $_POST['accounts_number'], 'id="accounts_number" ') . "\n"; ?>
<?php echo my_draw_hidden_field("action") . "\n"; ?>
<?php echo my_draw_hidden_field("returnTo") . "\n"; ?>



<?php
if($order_info['isRush'] > 0){
    echo "<div align=center>";
    echo my_image(DIR_WS_IMAGES.'rush.gif','RUSH this order!');
    echo my_image(DIR_WS_IMAGES.'spacer.gif','','150','1');
    echo my_image(DIR_WS_IMAGES.'rush.gif','RUSH this order!');
    echo my_image(DIR_WS_IMAGES.'spacer.gif','','150','1');
    echo my_image(DIR_WS_IMAGES.'rush.gif','RUSH this order!');
    echo "</div>";
}
?>

<table width=<?php echo $tableWidth; ?>  align="center" border=0  class="thinOutline" cellspacing=0>

<tr class="tableHeader">
    <td class="mediumBoldText" colspan=6 align=center>O R D E R  &nbsp;&nbsp; I N F O R M A T I O N</td>
</tr>


<tr class="tableHeader">
    <td colspan=6>

        <table width=100% align="center" border=0  cellspacing=0>

        <tr class="tableHeader">
            <td class="mediumBoldText" colspan=2 align=center>Order Id: &nbsp; <font color="#FFCC00"><?php echo my_null_replace($order_info['customer_invoice_number']);?></font></td>
            <td class="mediumBoldText" colspan=2 align=center>Company: &nbsp; <font color="#FFCC00"><?php echo $account['accounts_company_name'];?></font></td>
            <td class="mediumBoldText" colspan=2 align=center>Account No.: &nbsp; <font color="#FFCC00"><?php echo $order_info['accounts_number'];?></font>
<?php echo my_draw_hidden_field("order_account_number",$order_info['accounts_number'], 'id="order_account_number" '); ?>
</td>
            <td class="mediumBoldText" colspan=2 align=center>PO No.: &nbsp; <font color="#FFCC00"><?php echo $order_info['purchase_order_number'];?></font></td>
        </tr>
        </table>

    </td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText"  colspan=2><div style="margin: 10px 0 10px 0;">Order Status</div></td>
    <td align=left class="mediumBoldText"  colspan=2>
		<div style="margin: 10px 0 10px 0;"><?php echo my_draw_pull_down_menu('order_status',$arrOrderStatus,$order_info['order_status']); ?></div>
	</td>
</tr>
<tr class="tableRowColor">
    <td align=left class="mediumBoldText" colspan=2>Shipping Information</td>
    <td align=left class="mediumBoldText"  colspan=2>Select Applicable Fees</td>
</tr>
<tr class="tableRowColor">
    <td align=left class="mediumBoldText" colspan=2 valign=top>

        <table width=100% border=0>
            <tr>
                <td>Shipping&nbsp;Fee: </td>
                <td>$<?php echo my_draw_input_field('shipping_charge',$order_info['shipping_charge'],'size=5'); ?></td>
            </tr>
            <tr>
                <td>Ship&nbsp;Date: </td>
                <td>
                    <?php
                    if( strlen($order_info['ship_date']) > 4 &&
                    	$order_info['ship_date'] != "0000-00-00 00:00:00" ){
                        $arrShipDate = split("-",justDate($order_info['ship_date']));
                        $ship_date = $arrShipDate[1]."/".$arrShipDate[2]."/".$arrShipDate[0];
                    }else{
                        $ship_date = "";
                    }

                    echo my_draw_input_field('ship_date',$ship_date,'size=10');
                    ?>

                    <a href="javascript:cal2SHIPDATE.popup();"><img
                    src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date"></a>
                </td>
            </tr>
            <tr>
                <td>Sales&nbsp;Order&nbsp;No:</td>
                <td><?php echo my_draw_input_field('order_invoice_number',$order_info['order_invoice_number'],'size=20'); ?> </td>
            </tr>
            <tr>
                <td>Tracking&nbsp;No:</td>
                <td><?php echo my_draw_input_field('order_tracking_number',$order_info['order_tracking_number'],'size=30'); ?></td>
            </tr>
            <tr>
                <td colspan=2><?php echo my_draw_checkbox_field('notify_client'); ?> <span class="mediumBoldText">&nbsp;
                Send copy of this order and notes to client.</td>
            </tr>
        </table>
    </td>

    <td align=left class="mediumBoldText"  colspan=2 valign=top>
        <table width=100% border=0>
            <?php
                if ($order_info['isRush'] > 0){
                  $isRushOrder = true;
                  $checkDSFee = false;
                }else{
                  $isRushOrder = false;
                  $checkDSFee = true;
                }
            ?>
            <tr>

                <?php
					$getFee_sql = "SELECT * FROM fees WHERE fees_name = 'Rush' ";
					$getFee_query = my_db_query($getFee_sql);
					$getFee = my_db_fetch_array($getFee_query);
					$rushFee = $getFee['fees_value'];

                if( $isRushOrder ){// 10 == New Order
                    $dsFee = "0.00";
                    if($order_info['order_status'] == 10)
                        $dsFeeOnClickValue =  $account['accounts_dropship_fee'];
                    else
                        $dsFeeOnClickValue =  $order_info['dropship_fee'];
                }elseif( $order_info['order_status'] == 10 ){
                    $dsFee = $account['accounts_dropship_fee'];
                    $rushFee = "0.00";
                    $dsFeeOnClickValue =  $account['accounts_dropship_fee'];
                }elseif( $order_info['order_status'] != 10){
                    $dsFee = $order_info['dropship_fee'];
                    $rushFee = $order_info['rush_fee'];
                    $dsFeeOnClickValue =  $order_info['dropship_fee'];
                }
                ?>

                <td><?php echo my_draw_radio_field('dsFee', 'drop', $checkDSFee, 'onclick="this.form.dropship_fee.value=\''.$dsFeeOnClickValue.'\';this.form.rush_fee.value=\'0.00\';"' ); ?></td>
                <td>Dropship&nbsp;Fee</td>
                <td width=100%>$<?php echo my_draw_input_field('dropship_fee',$dsFee,'size=5'); ?></td>
            </tr>
            <tr>
                <td><?php echo my_draw_radio_field('dsFee', 'rush', $isRushOrder, 'onclick="this.form.rush_fee.value=\''.$rushFee.'\'; this.form.dropship_fee.value=\'0.00\'"'); ?></td>
                <td>Rush&nbsp;Fee</td>
                <td>$<?php echo my_draw_input_field('rush_fee',$rushFee,'size=5'); ?></td>
            </tr>
            <tr>
                <td><?php echo my_draw_checkbox_field('hfBox', '', true, 'onclick="if(!this.form.hfBox.checked)this.form.handling_fee.value=\'0.00\'"'); ?></td>
                <td>Handling&nbsp;Fee</td>
                <?php
                if( is_null($order_info['handling_fee']) ){
                    $hFee = $account['accounts_handling_fee'];
                } elseif ( $order_info['handling_fee'] == $account['accounts_handling_fee'] ){
                    $hFee = $account['accounts_handling_fee'];
                }else{
                    $hFee = $order_info['handling_fee'];
                }
                ?>
                <td>$<?php echo my_draw_input_field('handling_fee',$hFee,'size=5'); ?></td>
            </tr>
            <tr>

                <td><?php

                    if(intval($order_info['misc_fee']) > 0){
                        echo my_draw_checkbox_field('mfBox', '', true, 'onclick="if(!this.form.mfBox.checked){this.form.misc_fee.value=\'0.00\';this.form.misc_desc.value=\'\'}"');
                    }else{
                        echo my_draw_checkbox_field('mfBox', '', false, 'onclick="if(!this.form.mfBox.checked){this.form.misc_fee.value=\'0.00\';this.form.misc_desc.value=\'\'}"');
                    }

                ?></td>
                <td>Misc&nbsp;Fee</td>
                <td>$<?php echo my_draw_input_field('misc_fee',$order_info['misc_fee'],'size=5'); ?></td>
            </tr>
            <tr>
                <td colspan=3 align=left>Misc&nbsp;Desc&nbsp;<?php echo my_draw_input_field('misc_desc',$order_info['misc_desc'],'size=30'); ?></td>
            </tr>
        </table>

    </td>
</tr>
</table>


<script type="text/JavaScript">
var cal2SHIPDATE = new calendar2(document.forms[0].elements['ship_date']);
cal2SHIPDATE.year_scroll = false;
cal2SHIPDATE.time_comp = false;
</script>


<?php echo my_draw_hidden_field('original_order_status',$order_info['order_status']); ?>

<div align="center">
	<a href="#"
    onclick="saveProcessedOrder('cancel')"><?php
    echo my_image(DIR_WS_IMAGES.'btnCancelOnWhite.gif','Back To Process Menu');
    ?></a>
    <?php echo my_draw_spacer('spacer.gif',80,1); ?>
    <a href="#" onclick="saveProcessedOrder('save')">
        <?php echo my_image(DIR_WS_IMAGES.'btnSave.gif','Save Order Modifications'); ?></a>
    <br />
    <span align=left style="position:relative; top:-10px">
        <table>
            <tr>
                <td><?php echo my_draw_radio_field("radReturnTo","show_orders");?></td><td>Return to Previous List of Orders </td>
            </tr>
            <tr>
                <td><?php echo my_draw_radio_field("radReturnTo","default_page","true");?></td><td>Return to Process Orders Form</td>
            </tr>
        </table>
    </span>

</div>


<br />

<?php echo my_draw_hidden_field('order_id',$_GET['oId']); ?>


<table width=<?php echo $tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>

<tr class="tableHeader">
    <td class="mediumBoldText" colspan=2 align=center>C U S T O M E R</td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" style="width: 40%;">Customer Name:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_input_field('customer_name',$order_info['customer_name'],'size=30');
    ?>
    </td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Address Information:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_input_field('customer_address1',$order_info['customer_address1'],'size=30');
    ?>
    </td>
 </tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Add'l Address Information:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_input_field('customer_address2',$order_info['customer_address2'],'size=30');
    ?>
    </td>
 </tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >City:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_input_field('customer_city',$order_info['customer_city'],'size=30');
    ?>
    </td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >State:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_input_field('customer_state',$order_info['customer_state'],'size=30');
    ?>
    </td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Zip/Postal Code:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_input_field('customer_zip',$order_info['customer_zip'],'size=10');
    ?>
    </td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Country:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_input_field('customer_country',$order_info['customer_country'],'size=30');
    ?>
    </td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >International Phone No.:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_input_field('customer_intl_phone',$order_info['customer_intl_phone'],'size=30');
    ?>
    </td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Shipping Method:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_pull_down_menu('customer_shipping_id',$arrShipping, $order_info['customer_shipping_id'], 'id="customer_shipping_id" ');
    ?>
    </td>
</tr>
<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Order Id:</td>
    <td align=left class="mediumBoldText" >
    <?php
    echo my_draw_spacer('spacer.gif',20,1);
    echo my_draw_input_field('customer_invoice_number',$order_info['customer_invoice_number'],'size=30');
    ?>
    </td>
</tr>

</table>
<br />






<table width=<?php echo $tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0 id="productsTable">
<tr class="tableHeader">
    <td class="mediumBoldText" colspan=7 align=center>P R O D U C T S </td>
</tr>

<tr class="tableRowColor">
    <td align=right class="mediumBoldText" >Quantity</td>
    <td align=right class="mediumBoldText align-center" >Unit Price</td>
    <td align=right class="mediumBoldText align-center" >Sub-Totals</td>
    <td align=center class="mediumBoldText" >Size</td>
    <td align=left class="mediumBoldText" >Product Name</td>
    <td align=left class="mediumBoldText" >Product Status</td>
    <td align=left class="mediumBoldText" >Changes</td>
</tr>
<?php
    $counter = 0;
    $customerNo = $order_info['accounts_number'];
    while( $order_products = my_db_fetch_array($order_products_query) ){
        $arrTemp1[0] = array('id' => '0','text' => 'Select Size');
        $arrTemp2 = getProductSizeArray($order_products['order_product_model']);
        $arrProductSizes = array_merge($arrTemp1, $arrTemp2);

    	$varSize = $order_products['order_product_size'];
    	$startLoc = (strpos($varSize, "-"))? strpos($varSize, "-")+1: strlen($varSize);
    	$generic_size = trim(substr($varSize, $startLoc));

        //NOTE: Using this method to utilize the Promotions feature
        $product_price = $order_products['order_product_charge'];
        //$product_price = getPriceBySize($customerNo, $order_products['order_product_model'], $generic_size);

        // if ($_GET['usePromo']  == 1) {
        //     $product_price = $order_products['order_product_charge'];
        // } else {
        //     $product_price = getPriceBySize($customerNo, $order_products['order_product_model'], $generic_size);
        // }

        $bgcolor = ( fmod($counter,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";
?>

<tr class="<?= $bgcolor?>" id="product_row_<?=$counter?>">
    <td class="align-center">
        <?php
            echo my_draw_input_field('order_product_quantity_'.$counter,$order_products['order_product_quantity'],'size=3 id="order_product_quantity_'.$counter.'" onchange=saveChanges(this) ');
            echo my_draw_hidden_field('original_model_'.$counter, $order_products['order_product_model'], 'id="original_model_'.$counter.'"');
            echo my_draw_hidden_field('original_size_'.$counter, $order_products['order_product_size'], 'id="original_size_'.$counter.'"');
            echo my_draw_hidden_field('original_quantity_'.$counter,$order_products['order_product_quantity'], 'id="original_quantity_'.$counter.'"');
        ?>
    </td>
    <td class="mediumText">
        <span>x</span>
        <span id="span_order_product_charge_<?=$counter?>"><?php echo $product_price; ?></span>
        <?php
            echo my_draw_hidden_field('order_product_charge_'.$counter,$product_price, 'id="order_product_charge_'.$counter.'"');
        ?>
    </td>
    <td class="mediumText">
        <span>= $</span>
        <span id="qtyXcharge_<?=$counter?>"><?php echo number_format($product_price*$order_products['order_product_quantity'], 2, '.', 'id="order_product_quantity_'.$counter."'"); ?></span>
    </td>
    <td>
        <?php
            echo my_draw_pull_down_menu('order_product_size_'.$counter,$arrProductSizes,$order_products['order_product_size'], 'id="order_product_size_'.$counter.'" onchange=saveChanges(this) ');
        ?>
    </td>
    <td>
        <?php
            echo my_draw_hidden_field('order_product_id_'.$counter,$order_products['order_product_id'], 'id="order_product_id_'.$counter.'"');
            echo my_draw_hidden_field('order_product_name_'.$counter,$order_products['order_product_name'], 'id="order_product_name_'.$counter.'"');
            echo my_draw_pull_down_menu('order_product_model_'.$counter,$arrInventory,$order_products['order_product_model'], 'id="order_product_model_'.$counter.'" onchange=getSizes(this);saveChanges(this) ');
        ?>
    </td>
    <td>
        <?php
            echo my_draw_pull_down_menu('order_product_status_'.
                $counter, $arrProductStatus,$arrProductStatusID[$order_products['order_product_status']], 'id="order_product_status_'.$counter.'"');
        ?>
    </td>
    <td class="smallText">
        <span class="smallRedText" id="display_order_product_changes_<?=$counter?>"></span>
    </td>
</tr>

<?php
    $counter++;
    }
?>
</table>

<table width=<?php echo $tableWidth; ?> align="center" border=0  cellspacing=0 cellpadding=0>
    <tr><td align="right"><img src="images/btnAddProductOnWhite.gif" alt="Add Another Item To This Order" title="Add Another Item To This Order" onclick="addItemRow()" class="actLikeLink"></td></tr>
</table>


<?php echo my_draw_hidden_field('add_items',$_POST['add_items']); ?>
<?php echo my_draw_hidden_field('order_size',$counter, 'id="order_size" '); ?>
<br />


<table width=<?php echo $tableWidth; ?> align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
    <td class="mediumBoldText" colspan=5 align=center>C L I E N T  &nbsp;&nbsp; C O M M E N T S </td>
</tr>
<tr class="tableRowColor">
    <td colspan=5 align=center><?php
    if( $order_info['order_comments'] != "NULL"){
        echo stripslashes(my_unescape_string($order_info['order_comments']));
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
    <td align=center class="mediumBoldText">Order Status</td>
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
    <td align=center class="mediumText"><?php echo stripslashes($arrOrderStatus2[$order_history['order_history_status']]); ?></td>
    <td class="mediumText"><?php echo stripslashes($order_history['order_history_comments']); ?></td>
</tr>
<?php
    }
?>

<tr>
<th colspan="4" align=center>
<hr width=100% />
Add Notes
</th>
</tr>
<tr>
<td colspan="4" align=center>
    <?php echo my_draw_textarea_field('order_history_comments','soft','80','8', '', 'id="order_notes"'); ?><br />

    <br /><br />
</td>
</tr>

</table>

</form>

<?php
}//end of action == 'show_order' test
//*************************************************************************
//********************** SAVED UPDATED ORDER ******************************
//*************************************************************************
    elseif( $_POST['action'] == 'update_order' ){

        $product_status_sql = "SELECT * FROM product_status";
        $product_status_query = my_db_query($product_status_sql);
        while($product_status = my_db_fetch_array($product_status_query)){
          $arrProductStatus[$product_status['product_status_id']] = $product_status['product_status_name'];
        }

        $arrShipping = array();
        $shipping_sql = "SELECT * FROM shipping ";
        $shipping_query = my_db_query($shipping_sql);
        while($shipping = my_db_fetch_array($shipping_query)){
            $arrShipping[$shipping['shipping_id']]= $shipping['shipping_name'];
        }

          $arrShip_date = split("/",$_POST['ship_date']);

          //Save the order status
          $order_update_sql = "UPDATE orders set dropship_fee=".$_POST['dropship_fee'].",
          customer_name='".$_POST['customer_name']."',
          customer_address1='".$_POST['customer_address1']."',
          customer_address2='".$_POST['customer_address2']."',
          customer_intl_phone='".$_POST['customer_intl_phone']."',
          customer_city='".$_POST['customer_city']."',
          customer_state='".$_POST['customer_state']."',
          customer_zip='".$_POST['customer_zip']."',
          customer_country='".$_POST['customer_country']."',
          customer_shipping_method='".$arrShipping[$_POST['customer_shipping_id']]."',
          customer_shipping_id=".$_POST['customer_shipping_id'].",
          customer_invoice_number='".$_POST['customer_invoice_number']."',
          handling_fee=".$_POST['handling_fee'].",
          rush_fee=".$_POST['rush_fee'].",
          ship_date='".$arrShip_date[2]."-".$arrShip_date[0]."-".$arrShip_date[1]."',
          shipping_charge=".$_POST['shipping_charge'].",
          misc_fee=".$_POST['misc_fee'].", misc_desc='".$_POST['misc_desc']."', order_status=".$_POST['order_status'].",
          order_tracking_number='".$_POST['order_tracking_number']."',
          order_invoice_number='".$_POST['order_invoice_number']."',
          last_modified='".date("y-m-d h:i:s")."'
          WHERE order_id=".$_POST['order_id'];

//echo $order_update_sql;
        $order_update_query = my_db_query($order_update_sql);

          //Save the ordered products
          for($i=0; $i<$_POST['order_size']; $i++){

                if ($_POST['order_product_id_'.$i] != "ADDED_PROD") {
                    $orders_products_status_sql =
                    "UPDATE orders_products set order_product_status='".
                    $arrProductStatus[$_POST['order_product_status_'.$i]] .
                    "', order_product_quantity=". $_POST['order_product_quantity_'.$i] .
                    ", order_product_charge=". $_POST['order_product_charge_'.$i] .
                    ", order_product_model='". $_POST['order_product_model_'.$i] .
                    "', order_product_name='". $_POST['order_product_name_'.$i] .
                    "', order_product_size='".$_POST['order_product_size_'.$i]."'
                    WHERE order_product_id=".
                    $_POST['order_product_id_'.$i];
//echo $orders_products_status_sql . "<br><br>";

                    $orders_products_status_query = my_db_query($orders_products_status_sql);
                } else {
                    $ord_add_product_sql = sprintf("INSERT INTO
                    `orders_products` (`order_id` , `order_product_quantity` ,
                    `order_product_size` , `order_product_name`,
                    `order_product_model`, `order_product_charge`
                     )VALUES (%d, %d, %s, %s, %s, %f)", $_POST['order_id'],
                     $_POST['order_product_quantity_'.$i],
                    "'".$_POST['order_product_size_'.$i]."'",
                    "'".$_POST['order_product_name_'.$i]."'",
                    "'".$_POST['order_product_model_'.$i]."'",
                    $_POST['order_product_charge_'.$i] );

                    my_db_query($ord_add_product_sql);
                }
         }

          //Save comments, if there are any
          $notifiedClient = ($_POST['notify_client'] == "on")? 1 : 0 ;

        if(  strlen($_POST['order_history_comments']) > 0 ||
        	   $_POST['original_order_status'] != $_POST['order_status']){
            $order_history_sql = sprintf("INSERT into orders_history (order_id,
            order_history_date, order_history_is_notified, order_history_status,
            order_history_comments) VALUES (%d,'".date("y-m-d h:i:s")."',
            %d,'%s','%s')", $_POST['order_id'],$notifiedClient,
            $_POST['order_status'], str_replace(",","",mysql_real_escape_string($_POST['order_history_comments'])));
            $order_history_query = my_db_query($order_history_sql);
//echo $order_history_sql ."<BR><BR>";
        }


          if($_POST['notify_client'] == "on"){
              my_mail_order($_POST['order_id'], $_POST['order_account_number']);
          }

          if( $order_update_query == 1){
              echo "<div align=center class=\"success\"> Order Updated Successfully</div>\n";
              if($_POST['notify_client'] == "on"){
                  echo "<div align=center class=\"smallText\">Client was sent a
                  copy of this order</div>\n";
              }
          }else{
              echo "<div align=center class=\"fail\"> Order Was Not Updated</div>";
          }

    }//end of action = "update_order" test
//*************************************************************************
//*************************** SHOW ORDERS *********************************
//*************************************************************************

        elseif( $_GET['action'] == 'show_orders' || $returnTo == 'show_orders' ){

        	if($_GET['action'] == 'show_orders'){
	            my_session_register('selected_account');
	            $_SESSION['selected_account'] = $_POST['accounts_number'];
        	}

			if($_SESSION['rep_group'] == 0)
			  $repGroupsClause = "";
			else
			  $repGroupsClause = " AND a.accounts_rep_group=".$_SESSION['rep_group'];

            $arrRushOrders = array();
            $rush_orders_sql = "SELECT o.order_id FROM orders o, accounts a WHERE
            o.accounts_number=a.accounts_number
			".$repGroupsClause." AND o.isRush=1 AND o.order_status=10";
            $rush_orders_query = my_db_query($rush_orders_sql);
// echo $rush_orders_sql ."<br><br>";

 			while( $rush_orders = my_db_fetch_array($rush_orders_query)  ){
                $arrRushOrders[] = $rush_orders['order_id'];
            }

            $arrOrderStatus = array();
            $order_status_sql = "SELECT * from order_status";
            $order_status_query = my_db_query($order_status_sql);
            while($order_status = my_db_fetch_array($order_status_query)){
                $arrOrderStatus[$order_status['order_status_id']] = $order_status['order_status_name'];
            }

            //The $status variable filters by order_status, if required by the user
            $status = ($_POST['order_status_id'] == 0 )? "" : " AND o.order_status=".$_POST['order_status_id'];



            if( $_POST['radWhereClause'] == "1" && (strlen($_POST['toDate'])>0) && (strlen($_POST['fromDate'])>0) ){
                $arrToDate = split("/",$_POST['toDate']);
                $arrFromDate = split("/",$_POST['fromDate']);
                $toDate = $arrToDate[2]."-".$arrToDate[0]."-".$arrToDate[1];
                $fromDate = $arrFromDate[2]."-".$arrFromDate[0]."-".$arrFromDate[1];

                $show_orders_sql = "SELECT o.order_id, o.customer_name,
                date_format(o.purchase_date, '%Y-%m-%d %H:%m:%s') as purchase_date, o.customer_shipping_method, o.order_status,
                o.purchase_order_number, o.customer_invoice_number,
                o.order_comments FROM orders o, accounts a WHERE
                purchase_date <  '".$toDate."' AND purchase_date >=  '".$fromDate."'
                AND o.accounts_number =  a.accounts_number
                AND a.accounts_number =  '".$_POST['accounts_number']."' " . $status.
                $repGroupsClause ." ORDER BY purchase_date DESC";
            }else{

                if($_POST['radWhereClause'] == "2"){
                    $daysInPast = " AND o.purchase_date > DATE_ADD( CURDATE(  ) ,  INTERVAL  -".
                    				$_POST['days_back']." DAY  ) ";
                }else{
                    $daysInPast = "";
                }

				$whereClauseAcct = ($_POST['accounts_number'] == '*')?'':" AND a.accounts_number =  '".$_POST['accounts_number']."' ";

                $show_orders_sql = "SELECT o.order_id, o.customer_name,
                date_format(o.purchase_date, '%Y-%m-%d %H:%m:%s ') as purchase_date, o.customer_shipping_method, o.order_status,
                o.purchase_order_number, o.customer_invoice_number,
                o.order_comments, a.accounts_username FROM orders o,
				accounts a WHERE o.accounts_number =
                a.accounts_number ".$whereClauseAcct.$repGroupsClause.
                $status." ". $daysInPast." ORDER BY a.accounts_username ASC";
            }

// echo $show_orders_sql ."<br><br>";
            $show_orders_query = my_db_query($show_orders_sql);

            if($debug_mode){//DEBUG
                echo "<b>Account Number</b> = ".$_POST['accounts_number']."<br>";
                echo "<b>show_orders_sql</b> = ".$show_orders_sql."<br>";
            }
?>
<?php echo my_draw_form('process_order',my_href_link('process_order3.php')) . "\n";?>

<?php echo my_draw_hidden_field("action",$_GET['action']) . "\n"; ?>
<?php echo my_draw_hidden_field("oId") . "\n"; ?>
<?php echo my_draw_hidden_field("status", $_POST['order_status_id']) . "\n"; ?>
<?php echo my_draw_hidden_field("radWhereClause", $_POST['radWhereClause']) . "\n"; ?>
<?php echo my_draw_hidden_field("days_back", $_POST['days_back']) . "\n"; ?>
<?php echo my_draw_hidden_field("fromDate", $_POST['fromDate']) . "\n"; ?>
<?php echo my_draw_hidden_field("toDate", $_POST['toDate']) . "\n"; ?>
<?php echo my_draw_hidden_field("accounts_number", $_POST['accounts_number']) . "\n"; ?>






<table align="center"  border="0" class="thinOutline" cellspacing="0" cellpadding="5">
<tr class="tableHeader">
    <th class="mediumBoldText" >Account<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText" >Customer<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText" >Purchase&nbsp;Date<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText" >Shipping<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText" >Order&nbsp;Status<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText" >PO&nbsp;No.<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <th class="mediumBoldText" >Order&nbsp;Id<?php echo my_draw_spacer('spacer.gif',20,1); ?></th>
    <!-- th>Comments<?php echo my_draw_spacer('spacer.gif',40,1); ?></th -->
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
       $isRushOrder = ( in_Array($show_orders['order_id'], $arrRushOrders) )?"<strong><big>*</big></strong>":"";
?>
    <tr class="tableRowColor">
        <td class="smallText" align=center>
        <a href="<?php echo my_href_link('process_order3.php', 'action=show_order&oId='.$show_orders['order_id']); ?>" >
        <?php echo $show_orders['accounts_username']; ?>
        </a>
        </td>

        <td class="smallText" align=center>
        <a href="<?php echo my_href_link('process_order3.php', 'action=show_order&oId='.$show_orders['order_id']); ?>" >
        <?php echo $show_orders['customer_name']; ?>
        </a>
        </td>

        <td class="smallText" align=center>
        <a href="<?php echo my_href_link('process_order3.php', 'action=show_order&oId='.$show_orders['order_id']); ?>" >
        <?php echo $show_orders['purchase_date']; ?>
        </a>
        </td>

        <td class="smallText" align=center>
        <a href="<?php echo my_href_link('process_order3.php', 'action=show_order&oId='.$show_orders['order_id']); ?>" >
        <?php echo $show_orders['customer_shipping_method']; ?>
        </a>
        </td>

        <td class="smallText" align=center>
        <a href="<?php echo my_href_link('process_order3.php', 'action=show_order&oId='.$show_orders['order_id']); ?>" >
        <?php echo $arrOrderStatus[$show_orders['order_status']] .$isRushOrder; ?>
        </a>
        </td>

        <td class="smallText" align=center>
        <a href="<?php echo my_href_link('process_order3.php', 'action=show_order&oId='.$show_orders['order_id']); ?>" >
        <?php echo $show_orders['purchase_order_number']; ?>
        </a>
        </td>

        <td class="smallText" align=center>
        <a href="<?php echo my_href_link('process_order3.php', 'action=show_order&oId='.$show_orders['order_id']); ?>" >
        <?php echo $show_orders['customer_invoice_number']; ?>
        </a>
        </td>
    </tr>
<?php
    }//end of while loop
    }//end of else for mysql_num_fields($show_orders_query) == 0
?>
</table>


<br />
<div align="center"><?php echo "<a href=\"". my_href_link('process_order3.php')."\">" . my_image(DIR_WS_IMAGES.'btnBack.gif','Back To Process Menu'); ?></a></div>


<?php
    }//end of  action == 'show_orders' ||  returnTo == 'show_orders' test
		if($_GET['action'] != 'show_orders' &&
			$_GET['action'] != 'show_order' &&
			$_GET['action'] != 'process_order'){

//*************************************************************************
//**************************** MAIN MENU **********************************
//*************************************************************************
	if($rep_group == 0)
	  $repGroupsClause = "";
	else
	  $repGroupsClause = "AND a.accounts_rep_group=".$_SESSION['rep_group'];


    $arrRushOrders = array();
    $rush_orders_sql = "SELECT a.accounts_username AS username FROM orders o, accounts a
    where o.accounts_number = a.accounts_number AND
    isRush=1 AND order_status=10";

    $rush_orders_query = my_db_query($rush_orders_sql);
    while( $rush_orders = my_db_fetch_array($rush_orders_query)  ){
        $arrRushOrders[] = $rush_orders['username'];
    }

//print_r($arrRushOrders);
    $arrNewOrders = array();
    $new_orders_sql = "SELECT a.accounts_username, COUNT(o.order_status) AS NewOrders, o.order_id
    FROM orders o, accounts a WHERE o.accounts_number = a.accounts_number
    AND o.order_status=10 GROUP BY a.accounts_username";
    $new_orders_query = my_db_query($new_orders_sql);
    while($new_orders = my_db_fetch_array($new_orders_query) ){
        $rushMarker = ( in_array($new_orders['accounts_username'], $arrRushOrders) )?"*":"";

        $arrNewOrders[$new_orders['accounts_username']] = $new_orders['NewOrders'].$rushMarker;
    }

    $accounts_sql = "SELECT * FROM accounts a WHERE 1 ".$repGroupsClause." ORDER BY a.accounts_username";
    $accounts_query = my_db_query($accounts_sql);
    $arrAccounts[] = array('id' => '*',
                              'text' => 'Show All');
    while( $accounts = my_db_fetch_array($accounts_query)  ){

        if( str_replace("*","",$arrNewOrders[$accounts['accounts_username']]) > 0 ){
            $accounts['accounts_username'] = "(".$arrNewOrders[$accounts['accounts_username']].
            ")".$accounts['accounts_username'];
        }
        $arrAccounts[] = array('id' => $accounts['accounts_number'],
                              'text' => my_unescape_string($accounts['accounts_username']));
    }


    $order_status_sql = "SELECT * FROM order_status where 1 ORDER BY order_status_sort";
    $order_status_query = my_db_query($order_status_sql);
    $arrOrderStatus[] = array('id' => '0','text' => 'All Orders');
    while( $order_status = my_db_fetch_array($order_status_query)  ){
        $arrOrderStatus[] = array('id' => $order_status['order_status_id'],
                              'text' => $order_status['order_status_name']);
    }
?>

<?php echo my_draw_form('process_order',my_href_link('process_order3.php', 'action=show_orders'));?>
<br /><br /><br />


<table width="600" align="center" border=0>

<tr><th colspan=2></th><th colspan=3>Optional Fields</th></tr>
<tr><th>Accounts</th><th>Status</th><td>&nbsp;</td><th>From</th><th>To</th></tr>
<tr>
<th valign=top><?php echo my_draw_pull_down_menu('accounts_number',$arrAccounts, $_SESSION['selected_account']); ?></th>
<td valign=top><?php echo my_draw_pull_down_menu('order_status_id',$arrOrderStatus); ?></td>
<td><?php echo my_draw_radio_field("radWhereClause","1");?></td>
<td align=center><input type=text name="fromDate" size=10 /><a href="javascript:cal2FROM.popup();"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date"></a></td>

<td align=center><input type=text name="toDate" size=10 /><a href="javascript:cal2TO.popup();"><img src="images/cal.gif" width="16" height="16" border="0" alt="Click Here to pick the date"></a></td>
</tr>

<tr>
<td colspan=2 align=left>
</td>
<td><?php echo my_draw_radio_field("radWhereClause","2","true");?></td>
<th colspan=2>
<?php

$arrDays = array();
$arrDays[0] = array('id' => "5",'text' => "5");
$arrDays[1] = array('id' => "10",'text' => "10");
$arrDays[2] = array('id' => "20",'text' => "20");
$arrDays[3] = array('id' => "30",'text' => "30");
$arrDays[4] = array('id' => "60",'text' => "60");

echo "<br/>Show orders ".my_draw_pull_down_menu('days_back',$arrDays)." days back"; ?>
</th>
</tr>

<tr>
<td align=center colspan="5">
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
