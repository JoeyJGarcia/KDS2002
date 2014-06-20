<?php
require('includes/application_top.php');

$tableName = "product_status";
$displayName = "Product Status";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Product Status</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<script type="text/JavaScript">
    function confirmDelete(){
        if(confirm("You are about to make a deletion, continue?")){
            return true;
        }else{
            return false;
        }

    }
</script>
<script type="text/JavaScript" src="debugInfo.js"></script>
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
        <td colspan=3 align="center" class="largeBoldText">P R O D U C T &nbsp;&nbsp; S T A T U S</td>
    </tr>
</table>


<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){?>
<?php echo my_draw_form('add_'.$tableName,my_href_link($tableName.'.php', 'action=add'));


    $max_sort_sql = "SELECT MAX(". $tableName ."_sort) AS maxSort FROM ". $tableName;
    $max_sort_query = my_db_query($max_sort_sql);
    $max_sort = my_db_fetch_array($max_sort_query);

?>


<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add <?php echo $displayName; ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_name','','size=20'); ?>
&nbsp;&nbsp;<?php echo my_draw_checkbox_field($tableName.'_default'); ?> Make Default
 &nbsp;<?php echo my_draw_input_field($tableName.'_sort',$max_sort['maxSort']+1,'size=2'); ?> Sort No.
</td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Addition'); ?>
    </td>
</tr>

</table>

<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'mod_start' ){

    $product_status_mod_sql = "SELECT * FROM ". $tableName ." WHERE ". $tableName ."_id=".$_GET['psId'];
    $product_status_mod_query = my_db_query($product_status_mod_sql);
    $product_status_mod = my_db_fetch_array($product_status_mod_query);
    $is_selected = ($product_status_mod[$tableName.'_default'] == 1)? true:false;
echo $is_selected;
?>


<?php echo my_draw_form('mod_'.$tableName,my_href_link($tableName.'.php', 'action=mod&psId='.$_GET['psId']));?>


<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify <?php echo $displayName; ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_name',$product_status_mod[$tableName.'_name'],'size=20'); ?>
&nbsp;&nbsp;<?php echo my_draw_checkbox_field($tableName.'_default','',$is_selected); ?> Make Default &nbsp;<?php echo my_draw_input_field($tableName.'_sort',$product_status_mod['product_status_sort'],'size=2'); ?> Sort No.</td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
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
        $product_status_is_default = ($_POST['product_status_default'] == "on")? 1 : 0;
        $product_status_add_sql = sprintf("INSERT INTO `".$tableName."` (`".$tableName."_name`,`".$tableName."_default`,`".$tableName."_sort` ) VALUES ('%s',%d,%d)", mysql_real_escape_string($_POST['product_status_name']),$product_status_is_default,$_POST['product_status_sort']);
        $product_status_add_query = my_db_query($product_status_add_sql);

        if( $product_status_add_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $product_status_del_sql ="delete from ".$tableName." where ". $tableName."_id=".$_GET['psId'];
        $product_status_del_query = my_db_query($product_status_del_sql);
        if( $product_status_del_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){
    $is_default = ($_POST['product_status_default'] == "on" )? 1 : 0;

        $product_status_mod_sql ="UPDATE `".$tableName."` SET `".$tableName."_sort` = '".$_POST['product_status_sort']."',`".$tableName."_name` = '".$_POST['product_status_name']."',`".$tableName."_default` = $is_default WHERE `".$tableName."_id`=".$_GET['psId'];
        $product_status_mod_query = my_db_query($product_status_mod_sql);
        if( $product_status_mod_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Modified</div>";
        }

        //Sets all the rest of the Product Status to non-default status
        if( $is_default == 1){
        $product_status_mod_default_sql ="UPDATE `".$tableName."` SET `".$tableName."_default` = '0' WHERE `".$tableName."_id` !=".$_GET['psId'];
        $product_status_mod_default_query = my_db_query($product_status_mod_default_sql);
        }

    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=400 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link($tableName.'.php', 'action=add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New '.$displayName); ?></a>
    </td></tr>
    </table>



<?php

    echo "<table width=400 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=5>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th width=10 colspan=2 valign=bottom>Actions</th>\n";
    echo "\t<th>Name</th>\n";
    echo "\t<th>Sort</th>\n";
    echo "\t<th>Default</th>\n";
    echo "</tr>\n";

    $product_status_view_sql = "SELECT * FROM ". $tableName ." WHERE 1 ORDER BY ". $tableName ."_sort";
    $product_status_view_query = my_db_query($product_status_view_sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    while($product_status_view = my_db_fetch_array($product_status_view_query)){

        $is_default = ($product_status_view[$tableName.'_default'] == 1)?"*":"";
        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link($tableName.'.php',
        'action=mod_start&psId='. $product_status_view[$tableName.'_id']). '">'
        . my_image(DIR_WS_IMAGES.'btnModify.gif','Modify '.$displayName) ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link($tableName.'.php',
        'action=del&psId='. $product_status_view[$tableName.'_id']).
        '" onClick="return confirmDelete()">' .
        my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete '.$displayName) ."</a></td>";

        echo "<td align=center>". $product_status_view[$tableName.'_name'] ."</td>";
        echo "<td align=center>". $product_status_view[$tableName.'_sort'] ."</td>";
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
