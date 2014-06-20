<?php
require('includes/application_top.php');

$tableName = "cat_sizes";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Category Sizes</title>
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
        <td colspan=3 align="center" class="largeBoldText">C A T E G O R Y &nbsp;&nbsp;&nbsp; S I Z E S</td>
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


<table width=550px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add <?php echo ucwords( str_replace("_"," ", $tableName)); ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_name','','size=20'); ?>
 &nbsp;&nbsp;&nbsp;<?php echo my_draw_input_field($tableName.'_sort',$max_sort['maxSort']+1,'size=2');
 ?> Sort No.
</td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="center">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','100','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Addition'); ?>
    </td>
</tr>

</table>

<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'mod_start' ){

    $size_mod_sql = "SELECT * FROM ". $tableName ." WHERE ". $tableName ."_id=".$_GET['szId'];
    $size_mod_query = my_db_query($size_mod_sql);
    $size_mod = my_db_fetch_array($size_mod_query);
?>


<?php echo my_draw_form('mod_'.$tableName,my_href_link($tableName.'.php', 'action=mod&szId='.$_GET['szId']));?>


<table width=550px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify <?php echo ucwords( str_replace("_"," ", $tableName)); ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_name',$size_mod[$tableName.'_name'],'size=20'); ?>
<?php echo my_draw_input_field($tableName.'_sort',$size_mod[$tableName.'_sort'],'size=2'); ?> Sort No.</td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="center">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','100','1'); ?>
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
        $size_add_sql = sprintf("INSERT INTO `".$tableName."` 
        (`".$tableName."_name`,`".$tableName."_sort` ) 
        VALUES ('%s',%d)", mysql_real_escape_string($_POST[$tableName.'_name']),$_POST[$tableName.'_sort']);
        $size_add_query = my_db_query($size_add_sql);

        if( $size_add_query == 1){
            echo "<div align=center class=\"success\">".ucwords( str_replace("_"," ", $tableName))." Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".ucwords( str_replace("_"," ", $tableName))." Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $size_del_sql ="delete from ".$tableName." where ". $tableName."_id=".$_GET['szId'];
        $size_del_query = my_db_query($size_del_sql);
        if( $size_del_query == 1){
            echo "<div align=center class=\"success\">".ucwords( str_replace("_"," ", $tableName))." Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".ucwords( str_replace("_"," ", $tableName))." Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){

        $size_mod_sql ="UPDATE `".$tableName."` SET `".$tableName."_sort` = '".$_POST[$tableName.'_sort']."',`".$tableName."_name` = '".$_POST[$tableName.'_name']."' WHERE `".$tableName."_id`=".$_GET['szId'];
        $size_mod_query = my_db_query($size_mod_sql);
        if( $size_mod_query == 1){
            echo "<div align=center class=\"success\">".ucwords( str_replace("_"," ", $tableName))." Modified Successfully</div>";
		}else{
            echo "<div align=center class=\"fail\">".ucwords( str_replace("_"," ", $tableName))." Not Modified</div>";
        }

    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=400 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link($tableName.'.php', 'action=add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New Size'); ?></a>
    </td></tr>
    </table>



<?php

    echo "<table width=400 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=6>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th width=10 colspan=2 valign=bottom>Actions</th>\n";
    echo "\t<th>Size Name</th>\n";
    echo "\t<th>Sort</th>\n";
    echo "</tr>\n";

    $size_view_sql = "SELECT * FROM ". $tableName ." WHERE 1 ORDER BY ". $tableName ."_sort";
    $size_view_query = my_db_query($size_view_sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    while($size_view = my_db_fetch_array($size_view_query)){

        $is_default = ($size_view[$tableName.'_default'] == 1)?"*":"";
        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link($tableName.'.php',
        'action=mod_start&szId='. $size_view[$tableName.'_id']). '">' .
        my_image(DIR_WS_IMAGES.'btnModify.gif','Modify Size') ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link($tableName.'.php',
        'action=del&szId='. $size_view[$tableName.'_id']).
        '" onClick="return confirmDelete()">' . my_image(DIR_WS_IMAGES.'btnDelete.gif',
        'Delete Size') ."</a></td>";

        echo "<td align=center>". $size_view[$tableName.'_name'] ."</td>";
        echo "<td align=center>". $size_view[$tableName.'_sort'] ."</td>";
        echo "</tr>\n";
        $count++;
    }
    echo "</table>\n";
}
?>




</form>
</body>
</html>
