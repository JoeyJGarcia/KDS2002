<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Discounts</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
  <style>
  .disabled {
    background-color: rgb(0, 0, 0, .2);
    color: grey; 
  }

  .hidden{
    display: none;
  }
  </style>
<script type="text/javascript"  src="includes/js/jquery_latest.js"></script>
<script language="javascript1.2">
    function confirmDelete(){
        if(confirm("You are about to make a deletion, continue?")){
            return true;
        }else{
            return false;
        }
    }

    function toggleDiscount(selEl) {
        var $selEl = jQuery(selEl),
            selIdx = $selEl[0].selectedIndex,
            discType = $selEl[0].value,
            $amtInpEl = jQuery(document.getElementById("amount_off")),
            $pctInpEl = jQuery(document.getElementById("percent_off")),
            $amtDiv = jQuery(document.getElementById("amount_off_div")),
            $pctDiv = jQuery(document.getElementById("percent_off_div"));

            if (discType === 'AMOUNT') {
                $amtDiv.show();
                $pctInpEl.attr("disabled","true");
                $pctInpEl[0].value = "0";
                $amtInpEl.removeAttr("disabled");
                $pctDiv.addClass("disabled");
                $amtDiv.removeClass("disabled");
                $pctDiv.hide();
            } else if (discType === 'PERCENT') {
                $pctDiv.show();
                $amtInpEl.attr("disabled","true");
                $amtInpEl[0].value = "0";
                $pctInpEl.removeAttr("disabled");
                $amtDiv.addClass("disabled");
                $pctDiv.removeClass("disabled");
                $amtDiv.hide();
            } else {
                $pctInpEl.attr("disabled","true");
                $amtInpEl.attr("disabled","true");                
                $pctDiv.addClass("disabled");
                $amtDiv.addClass("disabled");
                $pctDiv.hide();
                $amtDiv.hide();
            }

    }
</script>
<script language="JavaScript" src="debugInfo.js"></script>
</head>

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
        <td colspan=3 align="center" class="largeBoldText">P R O M O T I O N S</td>
    </tr>
</table>


<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){?>
<?php echo my_draw_form('addDiscount',my_href_link('promotions.php', 'action=add'));?>



<table width=700px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add Promotion</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Discount Name:</td>
<td class="smallText"><?php echo my_draw_input_field('discount_name','','size=30'); ?></td>
</tr>

<?php
    $arrApplyMethod[] = array('id' => '0','text' => 'Select Filter');
    $arrApplyMethod[] = array('id' => 'STARTS-WITH','text' => 'Model No. Starts With');
    $arrApplyMethod[] = array('id' => 'EXACT-MATCH','text' => 'Model No. Exact Match');
?>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Apply Filter:</td>
<td class="smallText"><?php echo my_draw_pull_down_menu('apply_method',$arrApplyMethod); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Category Pattern:</td>
<td class="smallText"><?php echo my_draw_input_field('discount_pattern','','size=10'); ?> <div class="disabled">Example: APT or YTC1228</div></td>
</tr>
<?php
    $arrDiscountTypes[] = array('id' => '0','text' => 'Select Discount Type');
    $arrDiscountTypes[] = array('id' => 'PERCENT','text' => 'Percent Off');
    $arrDiscountTypes[] = array('id' => 'AMOUNT','text' => 'Set Amount');
?>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Discount Type:</td>
<td class="smallText"><?php echo my_draw_pull_down_menu('discount_type',$arrDiscountTypes,'', "onChange='toggleDiscount(this)'"); ?></td>
</tr>

<tr class="tableRowColor">
<td></td>
<td>
    <div id="amount_off_div" style=" display: none;" class="disc-amount">Amount: <input id="amount_off" type="text" name="amount_off" value="0" size="2" disabled='true' /></div>
    <div id="percent_off_div" style="display: none;" class="disc-percent">Percent: <input id="percent_off" type="text" name="percent_off" value="0" size="2" disabled='true' /></div>
</td>
</tr>

<tr class="tableHeader">
<th align=center colspan="2" style="color: #fff;">
Optional: Apply Only to Specific Clients, Choose One
</th>
</tr>

<tr class="tableRowColor" style="padding: 15px 0;">
<td align=right class="mediumBoldText"><span><input type="radio" name="limit_by" value="none" checked="checked"></span> <span style="width: 120px; float:right;">No Limitation:</span></td>
<td class="smallText">Apply Promotions To All Clients</td>
</tr>

<?php
    $arrApplyPLvls[] = array('id' => '0','text' => 'Price Levels');
    $arrApplyPLvls[] = array('id' => '1','text' => '1');
    $arrApplyPLvls[] = array('id' => '2','text' => '2');
    $arrApplyPLvls[] = array('id' => '3','text' => '3');
    $arrApplyPLvls[] = array('id' => '4','text' => '4');
    $arrApplyPLvls[] = array('id' => '5','text' => '5');
    $arrApplyPLvls[] = array('id' => '6','text' => '6');
?>
<tr class="tableRowColor" style="padding: 15px 0;">
<td align=right class="mediumBoldText"><span><input type="radio" name="limit_by" value="price_level"></span> <span style="width: 120px; float:right;">By Price Level:</span></td>
<td class="smallText"><?php echo my_draw_pull_down_menu('by_price_level',$arrApplyPLvls); ?></td>
</tr>

<?php
    $arrAccounts = array();
    $accounts_sql = "SELECT * FROM accounts WHERE 1 ORDER BY accounts_username";
    $accounts_query = my_db_query($accounts_sql);
    while($accounts = my_db_fetch_array($accounts_query)){
       $arrAccounts[] =  array('id' => $accounts['accounts_number'],'text' => $accounts['accounts_username'] . " / ".$accounts['accounts_number']);
    }
?>


<tr class="tableRowColor" style="padding: 15px 0;">
<td align=right class="mediumBoldText"><span><input type="radio" name="limit_by" value="account"></span> <span style="width: 120px; float:right;">By Account:</span></td>
<td class="smallText"><?php echo my_draw_pull_down_menu('by_account_number',$arrAccounts); ?><span style="margin-left: 20px;" class="disabled">[username / account number]</span></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('promotions.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Modification'); ?>
    </td>
</tr>

</table>


<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'mod_start' ){

    $discounts_mod_sql = "SELECT * FROM discounts WHERE discount_id=".$_GET['discountId'];
    $discounts_mod_query = my_db_query($discounts_mod_sql);
    $discounts_mod = my_db_fetch_array($discounts_mod_query);
?>


<?php echo my_draw_form('modDiscounts',my_href_link('promotions.php', 'action=mod&discountId='.$_GET['discountId']));?>


<table width=700px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify Promotion</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Discount Name:</td>
<td class="smallText"><?php echo my_draw_input_field('discount_name',$discounts_mod['discount_name'],'size=30'); ?></td>
</tr>

<?php
    $arrApplyMethod[] = array('id' => '0','text' => 'Select Filter Method');
    $arrApplyMethod[] = array('id' => 'STARTS-WITH','text' => 'Model No. Starts With');
    $arrApplyMethod[] = array('id' => 'EXACT-MATCH','text' => 'Model No. Exact Match');
?>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Filter Method:</td>
<td class="smallText"><?php echo my_draw_pull_down_menu('apply_method',$arrApplyMethod,$discounts_mod['apply_method']); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Category Pattern:</td>
<td class="smallText"><?php echo my_draw_input_field('discount_pattern',$discounts_mod['discount_pattern'],'size=10'); ?> <div class="disabled">Example: APT or YTC1228</div></td>
</tr>
<?php
    $arrDiscountTypes[] = array('id' => '0','text' => 'Select Discount Type');
    $arrDiscountTypes[] = array('id' => 'PERCENT','text' => 'Percent Off');
    $arrDiscountTypes[] = array('id' => 'AMOUNT','text' => 'Set Amount');
?>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Discount Type:</td>
<td class="smallText"><?php echo my_draw_pull_down_menu('discount_type',$arrDiscountTypes,$discounts_mod['discount_type'], "onChange='toggleDiscount(this)'"); ?></td>
</tr>

<tr class="tableRowColor">
<td></td>
<td>
    <div id="amount_off_div"  
    class="disc-amount <?php if($discounts_mod['discount_type'] == "PERCENT") echo "disabled hidden"; ?>">Amount: <input id="amount_off" 
    type="text" name="amount_off" 
    value="<?php 
    if($discounts_mod['discount_type'] == "AMOUNT") { 
        echo $discounts_mod['discount_value']; 
    } else {
        echo "0";
    }
    ?>" 
    size="2" <?php if($discounts_mod['discount_type'] == "PERCENT") { echo "disabled='true'"; } ?> /></div>
    <div id="percent_off_div" 
    class="disc-percent <?php if($discounts_mod['discount_type'] == "AMOUNT") echo "disabled hidden"; ?>">Percent: <input id="percent_off" 
    type="text" name="percent_off" 
    value="<?php 
    if($discounts_mod['discount_type'] == "PERCENT") { 
        echo intval($discounts_mod['discount_value']); 
    } else {
        echo "0";
    }
    ?>" 
    size="2" <?php if($discounts_mod['discount_type'] == "AMOUNT") { echo "disabled='true'"; } ?> /></div>
</td>
</tr>

<tr class="tableHeader">
<th align=center colspan="2" style="color: #fff;">
Optional: Apply Only to Specific Clients, Choose One
</th>
</tr>

<?php

    if ($discounts_mod['by_price_level'] != "") {
        $priceLevelSelected = " checked=\"checked\" ";
        $accountSelected = "";
        $noLimitationSelected = "";
    } elseif ($discounts_mod['by_account'] != "0") {
        $priceLevelSelected = "";
        $accountSelected = " checked=\"checked\" ";
        $noLimitationSelected = "";
    } else {
        $priceLevelSelected = "";
        $accountSelected = "";
        $noLimitationSelected = " checked=\"checked\" ";
    }

    $arrApplyPLvls[] = array('id' => '0','text' => 'Price Levels');
    $arrApplyPLvls[] = array('id' => '1','text' => '1');
    $arrApplyPLvls[] = array('id' => '2','text' => '2');
    $arrApplyPLvls[] = array('id' => '3','text' => '3');
    $arrApplyPLvls[] = array('id' => '4','text' => '4');
    $arrApplyPLvls[] = array('id' => '5','text' => '5');
    $arrApplyPLvls[] = array('id' => '6','text' => '6');
?>
<tr class="tableRowColor" style="padding: 15px 0;">
<td align=right class="mediumBoldText"><span><input type="radio" name="limit_by" value="none" <?=$noLimitationSelected?> ></span> <span style="width: 120px; float:right;">No Limitation:</span></td>
<td class="smallText">Apply Promotions To All Clients</td>
</tr>

<tr class="tableRowColor" style="padding: 15px 0;">
<td align=right class="mediumBoldText"><span><input type="radio" name="limit_by" value="price_level" <?=$priceLevelSelected?> ></span> <span style="width: 120px; float:right;">By Price Level:</span></td>
<td class="smallText"><?php echo my_draw_pull_down_menu('by_price_level',$arrApplyPLvls, $discounts_mod['by_price_level']); ?></td>
</tr>

<?php
    $arrAccounts = array();
    $accounts_sql = "SELECT * FROM accounts WHERE 1 ORDER BY accounts_username";
    $accounts_query = my_db_query($accounts_sql);
    while($accounts = my_db_fetch_array($accounts_query)){
       $arrAccounts[] =  array('id' => $accounts['accounts_number'],'text' => $accounts['accounts_username']." / ".$accounts['accounts_number']);
    }
?>


<tr class="tableRowColor" style="padding: 15px 0;">
<td align=right class="mediumBoldText"><span><input type="radio" name="limit_by" value="account" <?=$accountSelected?> ></span> <span style="width: 120px; float:right;">By Account No.:</span></td>
<td class="smallText"><?php echo my_draw_pull_down_menu('by_account_number',$arrAccounts, $discounts_mod['by_account_number']); ?><span style="margin-left: 20px;" class="disabled">[username / account number]</span></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('promotions.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Modification'); ?>
    </td>
</tr>

</table>


<?php
}else{

//*************************************************************************
//****************************** COMMIT ADD *******************************
//*************************************************************************
    if( $_GET['action'] == 'add'  ){

        $amountOff = $_POST['amount_off'];
        $percentOff = $_POST['percent_off'];
 
        if ($_POST['discount_type'] == "PERCENT") {
            $discountValue = $percentOff;
        } else if ($_POST['discount_type'] == "AMOUNT") {
            $discountValue = $amountOff;
        }

        if ($_POST['limit_by'] == 'price_level') {
            $byPriceLevel = $_POST['by_price_level'];
            $byAccountNumber = "";
        } elseif ($_POST['limit_by'] == 'account')  {
            $byAccountNumber=  $_POST['by_account_number'];
            $byPriceLevel = "";
        } else {
            $byPriceLevel = "";
            $byAccountNumber = "";
        }

        if ($_POST['discount_type'] == "PERCENT"){
            $discountDesc = $discountValue . "% off";
        } else {
            $discountDesc = "Fixed Price of $" . $discountValue;
        }

        if ($_POST['apply_method'] == "STARTS-WITH") {
            $discountDesc = $discountDesc . " for product models that start with " . strtoupper($_POST['discount_pattern']);
        } else {
            $discountDesc = $discountDesc . " for product models that are exactly " . strtoupper($_POST['discount_pattern']);
        }

        if ($_POST['limit_by'] == 'account') {
            $accounts_sql = "SELECT * FROM accounts WHERE accounts_number = '" . $byAccountNumber . "'";
            $accounts_query = my_db_query($accounts_sql);
            $accounts = my_db_fetch_array($accounts_query);
            $discountDesc = $discountDesc . ", applied to account " . $accounts['accounts_username'] . "/". $byAccountNumber . ".";
        } elseif ($_POST['limit_by'] == 'price_level') {
            $discountDesc = $discountDesc . ", applied to Price Level ".$byPriceLevel . " clients.";
        } else {
            $discountDesc = $discountDesc . ", applied to all clients.";
        }

        $discounts_add_sql = sprintf("INSERT INTO `discounts` (
        `discount_name`,`discount_type`, `discount_pattern`, 
        `discount_value`, `apply_method`, `by_price_level`, 
        `by_account_number`, `discount_description`, `enabled` ) 
        VALUES ('%s', '%s', '%s', %01.2f, '%s', '%s', '%s', '%s', %d)", 
        mysql_real_escape_string($_POST['discount_name']),
        mysql_real_escape_string($_POST['discount_type']),
        mysql_real_escape_string(strtoupper($_POST['discount_pattern'])),
        $discountValue,
        $_POST['apply_method'],
        $byPriceLevel,
        $byAccountNumber,
        mysql_real_escape_string($discountDesc),
        1);
       
//echo $discounts_add_sql;

        $discounts_add_query = my_db_query($discounts_add_sql);

        if( $discounts_add_query == 1){
            echo "<div align=center class=\"success\">Promotion Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Promotion Not Added</div>";
        }

    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $discounts_del_sql ="delete from discounts where discount_id=".$_GET['discountId'];
        $discounts_del_query = my_db_query($discounts_del_sql);

        if( $discounts_del_query == 1){
            echo "<div align=center class=\"success\">Promotion Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Promotion Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){

        $amountOff = $_POST['amount_off'];
        $percentOff = $_POST['percent_off'];

        if ($_POST['discount_type'] == "PERCENT") {
            $discountValue = $percentOff;
        } else if ($_POST['discount_type'] == "AMOUNT") {
            $discountValue = $amountOff;
        }

        if ($_POST['limit_by'] == 'price_level') {
            $byPriceLevel = $_POST['by_price_level'];
            $byAccountNumber = "";
        } elseif ($_POST['limit_by'] == 'account')  {
            $byAccountNumber=  $_POST['by_account_number'];
            $byPriceLevel = "";
        } else {
            $byPriceLevel = "";
            $byAccountNumber = "";
        }

        if ($_POST['discount_type'] == "PERCENT"){
            $discountDesc = $discountValue . "% off";
        } else {
            $discountDesc = "Fixed Price of $" . $discountValue;
        }

        if ($_POST['apply_method'] == "STARTS-WITH") {
            $discountDesc = $discountDesc . " for product models that start with " . strtoupper($_POST['discount_pattern']);
        } else {
            $discountDesc = $discountDesc . " for product models that are exactly " . strtoupper($_POST['discount_pattern']);
        }

        if ($_POST['limit_by'] == 'account') {
            $accounts_sql = "SELECT * FROM accounts WHERE accounts_number = '" . $byAccountNumber . "'";
            $accounts_query = my_db_query($accounts_sql);
            $accounts = my_db_fetch_array($accounts_query);
            $discountDesc = $discountDesc . ", applied to account " . $accounts['accounts_username'] . "/". $byAccountNumber . ".";
        } elseif ($_POST['limit_by'] == 'price_level') {
            $discountDesc = $discountDesc . ", applied to Price Level ".$byPriceLevel . " clients.";
        } else {
            $discountDesc = $discountDesc . ", applied to all clients.";
        }

        $discounts_mod_sql ="UPDATE `discounts` SET `discount_name` = '".$_POST['discount_name'].
            "',`discount_type` = '".$_POST['discount_type'].
            "',`discount_pattern` = '".$_POST['discount_pattern'].
            "',`discount_value` = ".$discountValue.
            ",`apply_method` = '".$_POST['apply_method'].
            "',`by_price_level` = '".$byPriceLevel.
            "',`by_account_number` = '".$byAccountNumber.
            "',`discount_description` = '".$discountDesc.
            "',`enabled` = 1".
            " WHERE `discount_id`=".$_GET['discountId'];
        $discounts_mod_query = my_db_query($discounts_mod_sql);
//echo $discounts_mod_sql;
        if( $discounts_mod_query == 1){
            echo "<div align=center class=\"success\">Promotion Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Promotion Not Modified</div>";
        }
    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=800 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link('promotions.php', 'action=add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New Discount'); ?></a>
    </td></tr>
    </table>


    <table width=900 border=0 align=center cellspacing=0 class="thinOutline">
    <tr class="tableHeader">
        <th width=10 colspan=2 valign=bottom>Actions</th>
        <th width=200 >Promo Name</th>
        <th colspan=4 >Promo Description</th>
    </tr>

<?php

    $discounts_view_sql = "SELECT * FROM discounts WHERE 1 ORDER BY discount_name";
    $discounts_view_query = my_db_query($discounts_view_sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    while($discounts_view = my_db_fetch_array($discounts_view_query)){

        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";
?>
    <tr class="<?php echo $bgcolor; ?>">
    <?php
        echo "<td align=center><a href=\"". my_href_link('promotions.php',
        'action=mod_start&discountId='. $discounts_view['discount_id']). '">' .
        my_image(DIR_WS_IMAGES.'btnModify.gif','Modify Discount') ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link('promotions.php','action=del&discountId='.
        $discounts_view['discount_id']). '" onClick="return confirmDelete()">' .
        my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete Discount') ."</a></td>";
    ?>

    <th align=center><?php echo $discounts_view['discount_name']; ?></th>
    <td align=left colspan=4><?php echo $discounts_view['discount_description']; ?></td>
    </tr>
<?php 
        $count++;
    } 
}
?>
    </table>

</form>
</body>
</html>
