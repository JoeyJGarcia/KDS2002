<?php
	if( isset($_POST['rep_group_action']) && $_POST['rep_group_action'] >= 0)
	$_SESSION['rep_group'] = $_POST['rep_group_action'];


    $arrNewOrders = array();
    $arrOtherNewOrders = array();

    //order_status=10 refers to a New Order
    $new_orders_sql = "SELECT r.rep_groups_id AS grpId, r.rep_groups_name AS grpName
	FROM orders o, accounts a, rep_groups r where order_status=10
	AND o.accounts_number=a.accounts_number
    AND a.accounts_rep_group = r.rep_groups_id";
    $new_orders_query = my_db_query($new_orders_sql);

	while($new_orders = my_db_fetch_array($new_orders_query)){
	  if( $new_orders['grpId'] == $_SESSION['rep_group'] )
	  	$arrNewOrders[] = $new_orders['grpName'];
	  else
	  	$arrOtherNewOrders[] = $new_orders['grpName'];
    }

	$new_orders_num=(count($arrNewOrders)> 0)?"(".count($arrNewOrders) . " new)&nbsp;":"";


    $rush_new_orders_sql = "SELECT COUNT(*)AS Count FROM orders o, accounts a
	 WHERE o.order_status=10 AND o.isRush=1 AND o.accounts_number=a.accounts_number
	  AND a.accounts_rep_group=".$_SESSION['rep_group'];

    $rush_new_orders_query = my_db_query($rush_new_orders_sql);
    $rush_new_orders = my_db_fetch_array($rush_new_orders_query);

    if( $rush_new_orders['Count'] > 0 ){
        $rush_new_orders_num = $rush_new_orders['Count'] . "&nbsp;";
    }else{
        $rush_new_orders_num = "0&nbsp;";
    }


?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<h2 align=center>Admin Dashboard</h2>

<table border=0 width=800 align="center">
<tr>
<th align=left>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Client Links</th>
<th align=left>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Admin Links</th>
<th align=left>&nbsp;&nbsp;&nbsp;&nbsp;
<?php if($_SESSION['userlevel']== "super" ) { ?>
Super Admin Links
<?php } ?>
</th>
</tr>
<tr>
     <td align=left class="mediumBoldText" valign="top">
         <ul>
            <li><a href="<?php echo my_href_link('orders2.php'); ?>">Enter Order</a>
            <li><a href="<?php echo my_href_link('bulk_orders.php'); ?>">Enter Bulk Orders</a>
            <?php if($_SERVER['SERVER_NAME'] == 'localhost') {?>
            <li><a href="<?php echo my_href_link('bulk_orders3.php'); ?>">Enter Bulk Orders v2 - beta</a>
            <?php }?>
            <li><a href="<?php echo my_href_link('show_orders.php'); ?>">Show My Orders</a>
            <li><a href="<?php echo my_href_link('faqs.php'); ?>">FAQs</a>
            <li><a href="<?php echo my_href_link('show_products_onhand.php'); ?>">Show Products On-Hand</a>
         </ul>
     </td>
     <td align=left class="mediumBoldText" valign="top">
     <ul>
       <?php
        if($rush_new_orders_num > 0){
        ?>
        <li><font color=red><strong><?php echo $rush_new_orders_num; ?>Rush Order(s) Pending</font></strong>
        <?php
        }
        ?>

        <li><a href="<?php echo my_href_link('process_order2.php') ."\">". $new_orders_num; ?>Process Orders </a>
        <li><a href="<?php echo my_href_link('summary_sheet.php') ."\">"; ?>Summary Sheet</a>
        <li><a href="<?php echo my_href_link('state_mapping.php'); ?>">State Mappings</a>
        <li><a href="<?php echo my_href_link('download_orders_onefile.php'); ?>">Download Orders</a>
        <li><a href="<?php echo my_href_link('view_contacts.php'); ?>">View Rep. Contacts</a>
        <li><a href="<?php echo my_href_link('products_onhand.php'); ?>">Update Products On-Hand Table</a>
        <li><a href="<?php echo my_href_link('products_inventory_update.php'); ?>">Update New Products On-Hand Table</a>
        <li><a href="<?php echo my_href_link('products_remove_discontinued.php'); ?>">Remove Discontinued Products</a>
    </ul>
        
    <br>
	<?php if($_SESSION['userlevel']== "super" ) { ?>

    <dl>
    <dt><strong>&nbsp;&nbsp;<?php echo count($arrOtherNewOrders); ?> New order(s) in other groups</strong>

	<?php

	echo "<dt>";
	$arrTemp = array();
	$intTemp = 0;
	for($i=0; $i<count($arrOtherNewOrders); $i++){
		if( in_array($arrOtherNewOrders[$i], $arrTemp) ){
		  $arrTemp = array($arrOtherNewOrders[$i]=>1);
		}else{
		  $intTemp = $arrTemp[$arrOtherNewOrders[$i]];
		  $arrTemp[$arrOtherNewOrders[$i]] = $intTemp+1;
		}
	}

	foreach($arrTemp as $grpName=>$grpNum){
		echo "<dd>".$grpNum. " " .$grpName;
	}
	echo "</dt></dl>";
	?>

	<?php } ?>
	<form name="frmRepGroup" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?> ">
	Current Rep Group:
	<?php


	$rep_groups_sql = "SELECT * FROM rep_groups where 1 ORDER BY rep_groups_sort";
    $rep_groups_query = my_db_query($rep_groups_sql);
    $arrRepGroups[] = array('id' => '0','text' => 'All Groups');
    while( $rep_groups = my_db_fetch_array($rep_groups_query)  ){
        $arrRepGroups[] = array('id' => $rep_groups['rep_groups_id'],
                              'text' => $rep_groups['rep_groups_name']);
    }

	echo my_draw_pull_down_menu('rep_groups_id',$arrRepGroups, $_SESSION['rep_group'], "onchange='switchGroup()'");
	echo my_draw_hidden_field('rep_group_action','');
?>

	<script language="javascript1.2" type="text/javascript">
	function switchGroup(){
	  var df = document.frmRepGroup;
	  var selIndex = df.rep_groups_id.selectedIndex;
	  df.rep_group_action.value = df.rep_groups_id[selIndex].value;
	  df.submit();
	}
	</script>


	</form>
    </td>
     <td align=left class="mediumBoldText" valign="top">
     <ul>
        <?php if($_SESSION['userlevel']== "super" ) { ?>
		<li><a href="<?php echo my_href_link('accounts.php'); ?>">Manage Accounts</a>
		<li><a href="<?php echo my_href_link('inventory.php','hide_disabled=1'); ?>">Manage Inventory</a>
        <li><a href="<?php echo my_href_link('shipping.php'); ?>">Manage Shipping Methods</a>
        <li><a href="<?php echo my_href_link('product_status.php'); ?>">Manage Product Status</a>
        <li><a href="<?php echo my_href_link('order_status.php'); ?>">Manage Order Status</a>
        <li><a href="<?php echo my_href_link('sizes.php'); ?>">Manage Sizes</a>
        <li><a href="<?php echo my_href_link('fees.php'); ?>">Manage Fees</a>
        <li><a href="<?php echo my_href_link('term_codes.php'); ?>">Manage Term Codes</a>
        <li><a href="<?php echo my_href_link('rep_groups.php'); ?>">Manage Rep Groups</a>
		<li><a href="<?php echo my_href_link('admin_reports.php'); ?>">Admin Reports</a>
		<li><a href="<?php echo my_href_link('inventory_populate.php'); ?>">Inventory Populate</a>
		<li><a href="<?php echo my_href_link('cat_sizes.php'); ?>">Manage Category Sizes</a>
		<li><a href="<?php echo my_href_link('categories.php'); ?>">Manage Default Categories & Prices</a>
		<li><a href="<?php echo my_href_link('products_customized.php'); ?>">Manage Customized Products Prices</a>
        <li><a href="<?php echo my_href_link('products_customer_prices.php'); ?>">Manage Customer Prices</a>
        <li><a href="<?php echo my_href_link('price_level_discounts.php'); ?>">Manage Price Level Discounts</a>
        <li><a href="<?php echo my_href_link('price_levels.php'); ?>">Manage Price Levels</a>
        <?php } ?>
    </ul>
    </td>
</tr>
</table>
<form name="adminUtils">
<div style="margin-left: 30%;">
    <button id="get_product_price">Get Product Info</button>
    <select name="size" class="get_product_price">
        <option value="">Size</option>
        <option value="NA">NA</option>
        <option value="SM">SM</option>
        <option value="MD">MD</option>
        <option value="LG">LG</option>
        <option value="XL">XL</option>
        <option value="2X">2X</option>
        <option value="3X">3X</option>
        <option value="4X">4X</option>
        <option value="3T">3T</option>
        <option value="4T">4T</option>
        <option value="5T">5T</option>
    </select>
    <input type=text size=10 class="get_product_price" name="model" placeholder="product model">
    <span id="priceCheckResult"></span>
</div>
    </form>
<br><br><br>
<div align=center class="Header"><a href="<?php echo my_href_link('news.php') ."\">"; ?>Add/Edit News Items</a><br>
<font color="#C0C0C0" size=-1>(News Items Seen By Clients)</font>
<br><br>


<?php

    $news_view_sql = "SELECT * FROM news WHERE 1 ORDER BY news_id DESC";

    $news_view_query = my_db_query($news_view_sql);
    while($news_view = my_db_fetch_array($news_view_query)){

    echo "<table width=600 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";
        //NEWS Title Row
        echo "<tr>";
        echo "<th align=center width=80% class=\"newsTitle\">&nbsp;&nbsp;&nbsp;&nbsp;".
        stripslashes($news_view['news_title']) ."</th>";
        echo "<th class=\"newsDate\" align=right>posted:&nbsp;".$news_view['news_postdate']."</th>";
        echo "</tr>\n";

        //NEWS Text Row
        echo "<tr >";
        echo "<th align=left width=100% colspan=2 class=\"newsText\"><p>".
        stripslashes($news_view['news_text']) ."</th>";
        echo "</tr>\n";
    echo "</table>\n";


    echo "<br><br>\n";

    }
?>
</div>

<script>
    $(document).ready(function(){
     console.log("ready");
        $("button").each(function(){
            console.log($(this));
            $(this).click(function(event){
                event.preventDefault();
                 $("#priceCheckResult").text('Retrieving prices ...')
                getProductPrice(event.currentTarget.id);
            });
        });
    });

    function getProductPrice(action){
        var options, productData;

        if( document.forms["adminUtils"].size.value.length == 0 ||
            document.forms["adminUtils"].model.value.length == 0 ){
            alert("A product model AND size are required.  Use NA for products with no sizes.");
            $("#priceCheckResult").text('')
            return;
        }

        productData = {
            "action": "get_product_price",
            "accountNumber": "<?=$_SESSION['client_account_number']?>",
            "model": document.forms["adminUtils"].model.value,
            "size": document.forms["adminUtils"].size.value
        };

        options = {
            type: "POST",
            dataType: "json",
            url: "ajax_controller.php",
            data: productData,
            timeout: 5000,
            success: function(data, status, jqXHR){
               console.log("Status: Success, responseText:  " + jqXHR.responseText);
               $("#priceCheckResult").text('= Custom: $' + data.custom_price + ', Price Level: $' + data.pl_price + ', Quantity: ' + data.onhand)
            },
            error: function(jqXHR, textStatus, errorThrown ){
                console.log("Status: Error, responseText: " + jqXHR.responseText);
                console.log(jqXHR);
            }
        };

        $.ajax(options);
    }
</script>