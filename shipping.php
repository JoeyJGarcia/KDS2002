<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Shipping Methods</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<script language="javascript1.2">
    function confirmDelete(){
        if(confirm("You are about to make a deletion, continue?")){
            return true;
        }else{
            return false;
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
        <td colspan=3 align="center" class="largeBoldText">S H I P P I N G  &nbsp;&nbsp; M E T H O D S</td>
    </tr>
</table>


<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'shipping_add_start' ){?>
<?php echo my_draw_form('add_shipping',my_href_link('shipping.php', 'action=shipping_add'));?>


<table width=300px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add Shipping Method Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td><?php echo my_draw_input_field('shipping_name','','size=30'); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Alias:</td>
<td><?php echo my_draw_input_field('shipping_alias','','size=30'); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Make Default:</td>
<td><?php echo my_draw_checkbox_field('shipping_default'); ?></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('shipping.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Addition'); ?>
    </td>
</tr>

</table>

<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'shipping_mod_start' ){

    $shipping_mod_sql = "SELECT * FROM shipping WHERE shipping_id=".$_GET['sId'];
    $shipping_mod_query = my_db_query($shipping_mod_sql);
    $shipping_mod = my_db_fetch_array($shipping_mod_query);
    $is_selected = ($shipping_mod['shipping_default'] == 1)? true:false;

?>


<?php echo my_draw_form('mod_shipping',my_href_link('shipping.php', 'action=shipping_mod&sId='.$_GET['sId']));?>


<table width=300px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify Shipping Method Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td><?php echo my_draw_input_field('shipping_name',$shipping_mod['shipping_name'],'size=20'); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Alias:</td>
<td><?php echo my_draw_input_field('shipping_alias',$shipping_mod['shipping_alias'],'size=20'); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Make Default:</td>
<td><?php echo my_draw_checkbox_field('shipping_default','',$is_selected); ?> </td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('shipping.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Modification'); ?>
    </td>
</tr>

</table>


<?php
}else{

//*************************************************************************
//****************************** ADD Shipping Method *************************
//*************************************************************************
    if( $_GET['action'] == 'shipping_add'  ){

        if($_POST['shipping_default'] == "on"){
          $reset_default_sql = "UPDATE `shipping` SET `shipping_default` = 0";
          my_db_query($reset_default_sql);
        }

        $is_default = ($_POST['shipping_default'] == "on" )? 1 : 0;
        $shipping_add_sql = sprintf("INSERT INTO `shipping` 
        (`shipping_name`,`shipping_alias`,`shipping_default`) 
        VALUES ('%s', '%s',%d)", mysql_real_escape_string($_POST['shipping_name']), 
        mysql_real_escape_string($_POST['shipping_alias']), $is_default);
        $shipping_add_query = my_db_query($shipping_add_sql);

        if( $shipping_add_query == 1){
            echo "<div align=center class=\"success\">Shipping Method Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Shipping Method Not Added</div>";
        }
    }
//*************************************************************************
//*************************** DELETE Shipping Method *************************
//*************************************************************************
    if( $_GET['action'] == 'shipping_del'  ){
        $shipping_del_sql ="delete from shipping where shipping_id=".$_GET['sId'];
        $shipping_del_query = my_db_query($shipping_del_sql);
        if( $shipping_del_query == 1){
            echo "<div align=center class=\"success\">Shipping Method Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Shipping Method Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** MODIFY Shipping Method *************************
//*************************************************************************
    if( $_GET['action'] == 'shipping_mod'  ){

        $reset_default_sql = "UPDATE `shipping` SET `shipping_default` = 0";
        my_db_query($reset_default_sql);

        $is_default = ($_POST['shipping_default'] == "on" )? 1 : 0;
        $shipping_mod_sql ="UPDATE `shipping` SET `shipping_name` = '".$_POST['shipping_name']."', `shipping_alias` = '".$_POST['shipping_alias']."', `shipping_default` = $is_default WHERE `shipping_id`=".$_GET['sId'];

        $shipping_mod_query = my_db_query($shipping_mod_sql);
        if( $shipping_mod_query == 1){
            echo "<div align=center class=\"success\">Product Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Product Not Modified</div>";
        }
    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=300 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link('shipping.php', 'action=shipping_add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New Shipping Method'); ?></a>
    </td></tr>
    </table>



<?php

    echo "<table width=500 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=5>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th width=10 colspan=2 valign=bottom>Actions</th>\n";
    echo "\t<th>Name</th>\n";
    echo "\t<th>Alias</th>\n";
    echo "\t<th>Default</th>\n";
    echo "</tr>\n";

    $shipping_view_sql = "SELECT * FROM shipping WHERE 1 ORDER BY shipping_name";
    $shipping_view_query = my_db_query($shipping_view_sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    while($shipping_view = my_db_fetch_array($shipping_view_query)){
        $is_default = ($shipping_view['shipping_default'] == 1)?"*":"";
        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link('shipping.php',
        'action=shipping_mod_start&sId='. $shipping_view['shipping_id']).
        '">' . my_image(DIR_WS_IMAGES.
        'btnModify.gif','Modify Shipping Method') ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link('shipping.php',
        'action=shipping_del&sId='. $shipping_view['shipping_id']).
        '" onClick="return confirmDelete()">' .
        my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete Shipping Method') ."</a></td>";

        echo "<td align=center>". $shipping_view['shipping_name'] ."</td>";
        echo "<td align=center>". $shipping_view['shipping_alias'] ."</td>";
        echo "<td align=center>". $is_default ."</td>";
        echo "</tr>\n";
        $count++;
    }
    echo "</table>\n";
}
?>




</form>
</body>
</html>
