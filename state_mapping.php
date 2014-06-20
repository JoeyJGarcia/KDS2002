<?php
require('includes/application_top.php');


//This page is not a good template, it is a one-off type
$tableName = "state_mapping";
$displayName = "State Mappings";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage <?php echo $displayName; ?></title>
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
<script type="text/javascript" src="debugInfo.js"></script>
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
        <td colspan=3 align="center" class="largeBoldText">S T A T E  &nbsp;&nbsp; M A P P I N G S</td>
    </tr>
</table>


<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){?>
<?php echo my_draw_form('add_'.$tableName,my_href_link($tableName.'.php', 'action=add'));?>


<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add <?php echo $displayName; ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Short Name:&nbsp;&nbsp;</td>
<td class="smallText">
<?php echo my_draw_input_field($tableName.'_shortName','','size=2'); ?>
&nbsp;&nbsp; 2 Character limit
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Long Name:&nbsp;&nbsp;</td>
<td class="smallText">
<?php echo my_draw_input_field($tableName.'_longName','','size=30'); ?>
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

    $state_mod_sql = "SELECT * FROM ". $tableName ." WHERE ". $tableName ."_id=".$_GET['xId'];
    $state_mod_query = my_db_query($state_mod_sql);
    $state_mod = my_db_fetch_array($state_mod_query);
?>


<?php echo my_draw_form('mod_'.$tableName,my_href_link($tableName.'.php', 'action=mod&xId='.$_GET['xId']));?>


<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify <?php echo $displayName; ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Short Name:</td>
<td class="smallText">
	<?php echo my_draw_input_field($tableName.'_shortName',$state_mod[$tableName.'_shortName'],'size=2'); ?>
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Long Name:</td>
<td class="smallText">
<?php echo my_draw_input_field($tableName.'_longName',$state_mod[$tableName.'_longName'],'size=30'); ?>
</td>
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

        $state_is_default = ($state_default == "on")? 1 : 0;
        $state_add_sql = sprintf("INSERT INTO `".$tableName."` (`".$tableName."_shortName`,`".$tableName."_longName` ) VALUES ('%s','%s')", mysql_real_escape_string($_POST['state_mapping_shortName']),mysql_real_escape_string($_POST['state_mapping_longName']));
        $state_add_query = my_db_query($state_add_sql);
        //echo $state_add_sql;
        if( $state_add_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $state_del_sql ="delete from ".$tableName." where ". $tableName."_id=".$_GET['xId'];
        $state_del_query = my_db_query($state_del_sql);
        if( $state_del_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){

        $state_mod_sql ="UPDATE `".$tableName."` SET `".$tableName."_shortName` = '".$_POST['state_mapping_shortName']."',`".$tableName."_longName` = '".$_POST['state_mapping_longName']."' WHERE `".$tableName."_id`=".$_GET['xId'];
        $state_mod_query = my_db_query($state_mod_sql);
	    if( $state_mod_query == 1){
	    	echo "<div align=center class=\"success\">".$displayName." Modified Successfully</div>";
    	}else{
        	echo "<div align=center class=\"fail\">".$displayName." Not Modified</div>";
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
    echo "<th width=10 colspan=2 valign=bottom>Actions&nbsp;&nbsp;</th>\n";
    echo "\t<th>Short&nbsp;Name</th>\n";
    echo "\t<th>Long&nbsp;Name</th>\n";
    echo "</tr>\n";

    $state_view_sql = "SELECT * FROM ". $tableName ." WHERE 1 ORDER BY ". $tableName ."_longName";
    $state_view_query = my_db_query($state_view_sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    while($state_view = my_db_fetch_array($state_view_query)){

        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";

        echo "<td align=center><a href=\"".
        my_href_link($tableName.'.php','action=mod_start&xId='.
        $state_view[$tableName.'_id']). '">' .
        my_image(DIR_WS_IMAGES.'btnModify.gif','Modify '.$displayName) ."</a></td>";
        echo "<td align=center><a href=\"". my_href_link($tableName.'.php',
        'action=del&xId='. $state_view[$tableName.'_id']).
        '" onClick="return confirmDelete()">' .
        my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete '.$displayName) ."</a></td>";
        echo "<td align=center>". $state_view[$tableName.'_shortName'] ."</td>";
        echo "<td align=center>". $state_view[$tableName.'_longName'] ."</td>";
        echo "</tr>\n";
        $count++;
    }
    echo "</table>\n";
}
?>




</form>
</body>
</html>
