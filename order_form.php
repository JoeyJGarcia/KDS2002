<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php


    $all_info_sql = "SELECT * FROM accounts a, orders o WHERE o.order_id=".$_GET['oId']."
    AND o.accounts_number=a.accounts_number";
    $all_info_query = my_db_query($all_info_sql);
    $all_info = my_db_fetch_array($all_info_query);

?>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Order Form</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>


<script type="text/javascript">
<!--

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

//-->
</script>

</head>

<body>
<?php echo my_draw_form('order_form','order_form.php','post');?>

<table height=<?php echo $width;?> cellpadding=0 cellspacing=0 align=left><tr><td valign=top>

<table align="left" width="<?php echo $width;?>" height=170 border=1 cellpadding="3" cellspacing="0">
    <tr>
        <td width=50% align=center valign=middle>
        <font color=#E0E0E0 >Place Tracking Number Here</font>
        </td>
        <td width=<?php echo $rsplit;?>% valign=top align=left>
        <table width=100% height=<?php echo $height;?> border=0 cellpadding="0" cellspacing="0">
            <tr>
                <td valign=top>
                    <div class="orderFormReturnAddress">
                    <?php echo $all_info['accounts_company_name']; ?><br />
                    <?php echo $all_info['accounts_address1']; ?><br />
                    <?php
                        if(strlen($all_info['accounts_address2']) > 0)
                            echo $all_info['accounts_address2']."<br>";
                    ?>
                    <?php echo $all_info['accounts_city']; ?>,
                    <?php echo $all_info['accounts_state']; ?>
                    <?php echo $all_info['accounts_zip']; ?><br />
                    <?php echo $all_info['accounts_country']; ?><br />
                    </div>
                </td>
            </tr>
            <tr>
                <td valign=middle>
                    <div class="orderFormCustomerAddress" align=center>
                    <?php echo $all_info['customer_name']; ?><br />
                    <?php echo $all_info['customer_address1']; ?><br />
                    <?php
                        if( strlen(my_null_replace($all_info['customer_address2'])) > 0)
                            echo $all_info['customer_address2']."<br>";
                    ?>
                    <?php echo $all_info['customer_city']; ?>,
                    <?php echo $all_info['customer_state']; ?>
                    <?php echo $all_info['customer_zip']; ?><br />
                    <?php echo $all_info['customer_country']; ?><br />
                    </div>
                </td>
            </tr>
        </table>
        </td>
    </tr>
</table>


<br />

<table align="left" width="<?php echo $width;?>" border=1 cellpadding="3" cellspacing="0">
    <tr>
        <td class="orderFormHeading" width=33%><strong>Account No:
        <?php echo $all_info['accounts_number']; ?></strong></td>
        <td class="orderFormHeading" align=center width=33%><strong>
        <?php echo $all_info['customer_shipping_method']; ?></strong></td>
        <td class="orderFormHeading" width=33%><strong>Order Date: <?php
            $arrDate = split(" ",$all_info['purchase_date']);
            echo $arrDate[0];
        ?></strong></td>
    </tr>
</table>



<table align="left" width="<?php echo $width;?>" border=1 cellpadding="3" cellspacing="0">
    <tr>
        <td class="orderFormHeading"><strong>Hide</strong></td>
        <td class="orderFormHeading"><strong>Quantity</strong></td>
        <td class="orderFormHeading" align=center><strong>Size</strong></td>
        <td class="orderFormHeading"><strong>Code - Description</strong>
        <?php echo my_image('spacer.gif','','10','1'); ?>
        </td>
        <td class="orderFormHeading" align=center width=10%><strong>Shipped</strong></td>
        <td class="orderFormHeading" align=center width=10%><strong>Out of Stock</strong></td>
    </tr>
<?php
    $all_products_sql = "SELECT * FROM orders_products WHERE order_id=".$_GET['oId'];
    $all_products_query = my_db_query($all_products_sql);
    $count = 0;
    while( $all_products = my_db_fetch_array($all_products_query) ){
    $count++;
?>
<tr id="ssRow<?= $count ?>">
        <td class="orderFormProductText" width=50 align=center><?php echo my_draw_checkbox_field("arrOrderProdId[]",
    $summary['order_product_id'],"","onClick=\"hideRows('ssRow".$count."')\""); ?></td>
        <td class="orderFormProductText" width=50 align=center><?php echo $all_products['order_product_quantity']; ?></td>
        <td class="orderFormProductText" width=100 align=center><?php echo $all_products['order_product_size']; ?></td>
        <td class="orderFormProductText" width=300><?php echo $all_products['order_product_name']; ?></td>
        <td class="orderFormProductText"></td>
        <td class="orderFormProductText"></td>
</tr>

<?php
}
?>

    <tr>
        <td colspan=6>
        <div class="smallText">
        <span  id="hiddenRowsCount"></span>
        <a href="#" onClick="showRows('<?php echo $count;?>');return false">Show All Hidden Rows</a>
        &nbsp;&nbsp; <?php echo $count;?> Total Rows
        </div>

        <?php echo my_draw_hidden_field("product_count",$count)."\n";?>
        <?php echo my_draw_hidden_field("hidden_row_count","0");?>

        </td>
    </tr>

</table>

</td></tr>
<tr><td valign=bottom>





<table align="left" width="<?php echo $width;?>" border=1 cellpadding="3" cellspacing="0">
    <tr>
        <td width=50% class="orderFormHeading">
            <?php echo $all_info['accounts_company_name']; ?><br />
            <?php echo $all_info['accounts_address1']; ?><br />
            <?php
                if(strlen($all_info['accounts_address2']) > 0)
                    echo $all_info['accounts_address2']."<br>";
            ?>
            <?php echo $all_info['accounts_city']; ?>,
            <?php echo $all_info['accounts_state']; ?>
            <?php echo $all_info['accounts_zip']; ?><br />
            <?php echo $all_info['accounts_country']; ?><br />
        </td>
        <td width=50% class="orderFormHeading" align=center>
            <?php echo $all_info['customer_name']; ?><br />
            <?php echo $all_info['customer_address1']; ?><br />
            <?php
                if( strlen(my_null_replace($all_info['customer_address2'])) > 0)
                    echo $all_info['customer_address2']."<br>";
            ?>
            <?php echo $all_info['customer_city']; ?>,
            <?php echo $all_info['customer_state']; ?>
            <?php echo $all_info['customer_zip']; ?><br />
            <?php echo $all_info['customer_country']; ?><br />
            <?php echo my_null_replace($all_info['customer_invoice_number']); ?> /
            <?php echo $all_info['purchase_order_number']; ?><br />
        </td>
    </tr>
    <tr>
        <td colspan="2"  class="orderFormProductText">Comments:
        <?php echo my_unescape_string(my_null_replace($all_info['order_comments'])); ?></td>

    </tr>
</table>

</td></tr></table>

</form>
</body>
</html>
