<?php
require('includes/application_top.php');

//Global Variables
$arrVisibleTypes[] = array('id'=>1, 'text'=>'DROPSHIPPER ONLY');
$arrVisibleTypes[] = array('id'=>2, 'text'=>'DROPSHIPPER & ADMIN');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Kerusso Drop Ship - Manage Inventory</title>
    <link rel="stylesheet" href="styles.css" type="text/css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
        $.noConflict();
        // Code that uses other library's $ can follow here.
    </script>
    <script type="text/javascript" src="debugInfo.js"></script>
    <script type="text/javascript" src="getSizes.js"></script>
    <script type="text/javascript" src="./prototype.js"></script>

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
        <td colspan=3 align="center" class="largeBoldText">I N V E N T O R Y</td>
    </tr>
    <tr>
        <td colspan=3 align="center" style="height:30px;"><div  class="user_message" id="user_message"></div></td>
    </tr>
</table>

<?php
    if( !isset($_GET['action']) || $_GET['action'] == 'inv_del'  ){
?>
<div style="align:center;">
<div style="float:right;margin-right:15%; border-color:#cococo; border:1px solid #000000; background-color:#cococo;">
    <div style="background:gray; color:white; ">
    <strong>Legend:</strong>
    </div>
    <div style="background:#f5f5f5;">
    Gray Row - Disabled product
    </div>
    <div style="background:#f5f5f5;">
    Red Row - Customized product & prices
    </div>
    <div  style="background:#f5f5f5;">
    Gray Sizes - Non-standard product sizes
    </div>
</div>
</div>
<br />
<br />
<?php
    }
?>


<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'inv_add_start' ){

?>
<?php echo my_draw_form('add_product',my_href_link('inventory.php', 'action=inv_add&hide_disabled=1'));?>

<?php echo my_draw_hidden_field('CBSizesLength'); ?>

<table width=600px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add Product Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td><?php echo my_draw_input_field('product_name','','size=50'); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Model:</td>
<td><?php echo my_draw_input_field('product_model','','size=10'); ?>
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Size Range:</td>
<td>
<?php echo my_draw_input_field('product_sizes','','size=10'); ?>

<?php echo my_draw_input_field('btnGetSizes','Get Category Sizes','onClick="if(document.forms[0].product_model.value.length < 4){alert(\'You need to enter a value for the Product Model before you can get the sizes.\'); return;} ; getAllSizes(document.forms[0].product_model.value, \'model\', this.form.name)"', 'button'); ?>

</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText"></td>
<td align=LEFT class="mediumLargeBoldText" ><span id="showSizesHeader"></span></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText"></td>
<td><span id="showSizes"></span></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Desc:</td>
<td><?php echo my_draw_textarea_field('product_desc','soft','40','5'); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Visibility:</td>
<td><?php echo my_draw_pull_down_menu('visible_type',$arrVisibleTypes); ?></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('inventory.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Addition'); ?>
    </td>
</tr>

</table>

<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'inv_mod_start' ){

    $inv_mod_sql = "SELECT * FROM products WHERE product_id=".$_GET['pId'];
    $inv_mod_query = my_db_query($inv_mod_sql);
    $inv_mod = my_db_fetch_array($inv_mod_query);

?>


<?php echo my_draw_form('mod_product',my_href_link('inventory.php', 'action=inv_mod&hide_disabled=1&pId='.$_GET['pId']));?>

<?php echo my_draw_hidden_field('CBSizesLength'); ?>

<?php echo my_draw_hidden_field('AvailableSizes', $inv_mod['product_avail_sizes']); ?>


<table width=550px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center class="tableHeader" colspan=2>Modify Product Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td><?php echo my_draw_input_field('product_name',$inv_mod['product_name'],'size=50'); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Model:</td>
<td><?php echo my_draw_input_field('product_model',$inv_mod['product_model'],'size=10 onBlur="if(this.value.length>4){document.forms[0].btnGetSizes.disabled=false;}else{document.forms[0].btnGetSizes.disabled=true;}"'); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Size Range:</td>
<td>
<?php echo my_draw_input_field('product_sizes',$inv_mod['product_sizes'],'size=10'); ?>

<?php echo my_draw_input_field('btnGetSizes','Set Specific Sizes','onClick="getAllSizes(document.forms[0].product_model.value, \'model\', this.form.name)"', 'button'); ?>
</td>
</tr>

<tr class="tableRowColor">
<td align=center class="mediumLargeBoldText" colspan=2><span id="showSizesHeader"></span></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText"></td>
<td><span id="showSizes"></span></td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Desc:</td>
<td><?php echo my_draw_textarea_field('product_desc','soft','40','5',$inv_mod['product_desc']); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Visibility:</td>
<td><?php echo my_draw_pull_down_menu('visible_type',$arrVisibleTypes, $inv_mod['visible_type']); ?></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('inventory.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Modification'); ?>
    </td>
</tr>

</table>


<?php
}else{

//*************************************************************************
//****************************** ADD PRODUCT ******************************
//*************************************************************************
    if( $_GET['action'] == 'inv_add'  ){
        $strSizes = "";
         $arrSzTmp = "";

        for($i=0; $i<$_POST['CBSizesLength']; $i++){
          if( isset( $_POST['CBSizesId'.$i] ) ){
            $strSizes .= "[".$_POST['CBSizesId'.$i].",".$_POST['CBSizesText'.$i]."]#";
          }
        }
        $strSizes = substr($strSizes, 0, strlen($strSizes)-1);

        $inv_add_sql = sprintf("INSERT INTO `products` (`product_name` ,
        `product_model` , `product_sizes` , `product_desc` , `product_avail_sizes`,
        `product_mod_date`, `visible_type`)
        VALUES ('%s', '%s', '%s', '%s', '%s', now(), %d)", mysql_real_escape_string($_POST['product_name']),
        mysql_real_escape_string($_POST['product_model']),
        mysql_real_escape_string($_POST['product_sizes']),
        mysql_real_escape_string(str_replace("'","",$_POST['product_desc'])),
        mysql_real_escape_string($strSizes),
        $_POST['visible_type']);
        $inv_add_query = my_db_query($inv_add_sql);
        $pId = mysql_insert_id();


        $delete_once = true;
        for($i=0; $i<$_POST['CBSizesLength']; $i++){
          if( isset( $_POST['CBSizesId'.$i] ) ){
            $arrSzTmp = explode(",", $_POST['CBSizesText'.$i]);

            if($delete_once){
                $del_prod_cust_sql = sprintf("DELETE FROM products_customized WHERE  product_id = %d", $pId);
                my_db_query($del_prod_cust_sql);
                $delete_once = false;
            }

            $prod_cust_sql = sprintf("INSERT INTO products_customized (`customized_price`,
            `categories_id`, `product_id`, `absolute_price`, `insert_date`) VALUES (%01.2f, %d, %d, 0, now() )",
            $arrSzTmp[0], $arrSzTmp[1], $pId);
            my_db_query($prod_cust_sql);
          }
        }


        if( $inv_add_query == 1){
            echo "<div align=center class=\"success\">Product Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Product Not Added</div>";
        }
    }
//*************************************************************************
//*************************** DELETE PRODUCT ******************************
//*************************************************************************
    if( $_GET['action'] == 'inv_del'  ){
        $inv_del_sql ="delete from products where product_id=".$_GET['pId'];
        $inv_del_query = my_db_query($inv_del_sql);

        $del_prod_cust_sql = sprintf("DELETE FROM products_customized WHERE  product_id = %d", $_GET['pId']);
        my_db_query($del_prod_cust_sql);

        if( $inv_del_query == 1){
            echo "<div align=center class=\"success\">Product Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Product Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** TOGGLE PRODUCT ******************************
//*************************************************************************
    if( $_GET['action'] == 'inv_toggle'  ){
        $inv_toggle_sql =sprintf("UPDATE `products` SET `product_enabled` = %d WHERE `product_id`=%d", $_GET['ptoggle'], $_GET['pId']);
        $inv_mod_toggle_query = my_db_query($inv_toggle_sql);

         if( $inv_mod_toggle_query == 1){
            echo "<div align=center class=\"success\">Product Toggled Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Product Not Toggled</div>";
        }
    }
//*************************************************************************
//*************************** MODIFY PRODUCT ******************************
//*************************************************************************
    if( $_GET['action'] == 'inv_mod'  ){

        $strSizes = "";
        $arrSzTmp = "";


        $delete_once = true;
        for($i=0; $i<$_POST['CBSizesLength']; $i++){
          if( isset( $_POST['CBSizesId'.$i] ) ){


            $strSizes .= "[".$_POST['CBSizesId'.$i].",".$_POST['CBSizesText'.$i]."]#";
            $arrSzTmp = explode(",", $_POST['CBSizesText'.$i]);

            if($delete_once){
                $del_prod_cust_sql = sprintf("DELETE FROM products_customized WHERE  product_id = %d", $arrSzTmp[2]);
                my_db_query($del_prod_cust_sql);
                $delete_once = false;
//echo $del_prod_cust_sql . "<br>" ;
            }

            $prod_cust_sql = sprintf("INSERT INTO products_customized (`customized_price`,
            `categories_id`, `product_id`, `absolute_price`, `insert_date`) VALUES (%01.2f, %d, %d, 0, now() )",
            $arrSzTmp[0], $arrSzTmp[1], $arrSzTmp[2]);
            my_db_query($prod_cust_sql);
//echo $prod_cust_sql . "<br>" ;
          }
        }
        $strSizes = substr($strSizes, 0, strlen($strSizes)-1);
//echo "Size Length: ".$_POST['CBSizesLength'];
        $inv_mod_sql =sprintf("UPDATE `products` SET `product_name` = '%s',
        `product_model` = '%s',`product_sizes` = '%s',`product_desc` = '%s',
        `product_avail_sizes` = '%s',
        `visible_type` = %d
        WHERE `product_id`=%d", mysql_real_escape_string($_POST['product_name']),
        mysql_real_escape_string($_POST['product_model']),
        mysql_real_escape_string($_POST['product_sizes']),
        mysql_real_escape_string(str_replace("'","",$_POST['product_desc'])), $strSizes, $_POST['visible_type'], $_GET['pId']);
//echo $inv_mod_sql;
        $inv_mod_query = my_db_query($inv_mod_sql);
        if( $inv_mod_query == 1){
            echo "<div align=center class=\"success\">Product Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Product Not Modified</div>";
        }
    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=800 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link('inventory.php', 'action=inv_add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New Product'); ?></a>
    </td></tr>
    </table>



<?php

    $prod_types_sql = "SELECT * FROM product_types";
    $prod_types_query = my_db_query($prod_types_sql);
    while($prod_types = my_db_fetch_array($prod_types_query)) {
        $arrProdTypes[$prod_types['product_type']] = $prod_types['product_type_name'];
    }

    echo "<table width=900 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=9>". my_image(DIR_WS_IMAGES.'spacer.gif','','700','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th width=10 colspan=3 valign=bottom>Actions</th>\n";
    echo "\t<th>Name</th>\n";
    echo "\t<th>Model</th>\n";
    echo "\t<th>Sizes</th>\n";
    echo "\t<th>Group</th>\n";
    echo "\t<th>Desc</th>\n";
    echo "\t<th>Type</th>\n";
    echo "</tr>\n";


    $inv_view_sql = "SELECT * FROM products WHERE 1 ORDER BY product_model";
    $inv_view_query = my_db_query($inv_view_sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    while($inv_view = my_db_fetch_array($inv_view_query)){
        $flag = ($inv_view['product_enabled'] == 1)? 0 : 1;
        if( $_GET['hide_disabled'] == 1 && $flag == 1) continue;
        $textColor = ( $flag == 1) ?  " disabledText ":  " enabledText ";
        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven_10" : "tableRowColorOdd_10";

        echo "<tr class=\"$bgcolor  $textColor \">";
        echo "<td align=center>";
        echo "<a href=\"";
        echo my_href_link('inventory.php','action=inv_mod_start&pId='. $inv_view['product_id']). '">' . my_image(DIR_WS_IMAGES.'btnModify.gif','Modify Product') ."</a>";
        echo "</td>";

        echo "<td align=center>";
        echo "<a href=\"". my_href_link('inventory.php',  'action=inv_del&pId='. $inv_view['product_id']). '" onClick="return confirmDelete()">' .
        my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete Product') ."</a>";
        echo "</td>";

        echo "<td align=center>";
        echo "<a href=\"". my_href_link('inventory.php',  'action=inv_toggle&ptoggle='. $flag.'&pId='.$inv_view['product_id']). "\"> ";
        if( $flag == 0){
            echo " Enabled ";
        }else{
            echo " Disabled ";
        }
        echo "</a>";
        echo "</td>";

        echo "<td align=center>". stripslashes($inv_view['product_name']) ."</td>";
        echo "<td align=center>". $inv_view['product_model'] ."</td>";
        echo "<td align=center>". getSizeAndPrices(trim($inv_view['product_model'])) ."</td>";
        echo "<td align=center>". getProductGroupsSelectionOptions( $inv_view['product_model'] . "_" . $inv_view['product_group_id'], $inv_view['product_group_id'] )  ."</td>";
        echo "<td align=center>". stripslashes($inv_view['product_desc']) ."</td>";
        echo "<td align=center>". $arrProdTypes[$inv_view['visible_type']] ."</td>";
        echo "</tr>\n";
        $count++;
    }
    echo "</table>\n";
}
?>


</form>

    <script type="text/javascript">
    function confirmDelete(){
        if(confirm("You are about to make a deletion, continue?")){
            return true;
        }else{
            return false;
        }

    }

    function showSizes(sizes){
       var JSONObject = eval('(' + sizes + ')');
       var showSizesSpan = document.getElementById('showSizes');
       var showSizesSpanHeader = document.getElementById('showSizesHeader');
       var arrAvailableSizes = [];
       var arrIDs = [];
       var arrSizeRange = [];
       var pId = <?php echo isset($_GET['pId']) ? $_GET['pId'] : 0; ?>;

       //Getting preset sizes - used for modify form
       if(document.forms[0].AvailableSizes != undefined){
         if( document.forms[0].AvailableSizes.value.length > 0 ){
            arrIDs = document.forms[0].AvailableSizes.value.split(',');
            for(i=0; i<arrIDs.length; i++){
              arrIDs[i] =  arrIDs[i].substring(0, arrIDs[i].indexOf('=')) ;
            }
         }
       }

       sizeCheckBoxes = "";
       isChecked = "";
        for(i=0; i<JSONObject.arrSizes.length;i++){
            if( i == 0 || i == JSONObject.arrSizes.length-1){
                arrSizeRange[arrSizeRange.length] = JSONObject.arrSizes[i].name;
            }

            if( JSONObject.arrSizes[i].isChecked == 1){
                isChecked = "CHECKED";
            }else{
                isChecked = "";
            }

            var arrSz = JSONObject.arrSizes[i];
            var sizeMetadata = arrSz.price +','+ arrSz.cat_id +','+ pId;
            sizeCheckBoxes = sizeCheckBoxes + "<INPUT TYPE='checkbox' VALUE='"+
            JSONObject.arrSizes[i].id+"' NAME='CBSizesId"+i+"' "+isChecked+">"+
            JSONObject.arrSizes[i].name+"<INPUT TYPE='hidden' NAME='CBSizesText"+i+"' VALUE='"+
            sizeMetadata+"' >&nbsp;&nbsp;";
       }
       showSizesSpanHeader.innerHTML = "Select The Available Sizes For This Product";
       showSizesSpan.innerHTML = sizeCheckBoxes;

       document.forms[0].CBSizesLength.value = JSONObject.arrSizes.length;

        document.forms[0].product_sizes.value = arrSizeRange[0] + " - " +arrSizeRange[1];

    }


    function setUserMessage(msg, status){
        jQuery("#user_message").removeClass(status);
        jQuery("#user_message").html(msg);
        jQuery("#user_message").css("display","block");
        jQuery("#user_message").addClass(status);
        jQuery("#user_message").fadeOut(5000, "swing", function(){
            jQuery("#user_message").html("");
            jQuery("#user_message").css("display","block");
            jQuery("#user_message").removeClass(status);
        });
    }


    jQuery(document).ready(function(){
        jQuery("select.product_groups").change(function(el){
            updateProductGroup(el.currentTarget)
        })
    });

    function updateProductGroup(selElement){
        var options, groupData = {};

        groupData.table_id = selElement.name.split("_")[0];//product_model
        groupData.value = selElement.value;
        groupData.column_name = "product_group_id";
        groupData.action = "update_product_group";

        options = {
            type: "POST",
            dataType: "json",
            url: "ajax_controller.php",
            data: groupData,
            success: function(data, status, jqXHR){
                console.log("Status: " + data.status);
                var message = "Updated product group successfully.";
                setUserMessage(message, "ajax_success");
            },
            error: function(jqXHR, textStatus, errorThrown ){
                console.log("Status: Error: " + jqXHR.responseText);
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                var message = "Update attempt failed ";

                setUserMessage(message, "ajax_fail");
            }
        };

        jQuery.ajax(options);
    }

</script>
</body>
</html>
