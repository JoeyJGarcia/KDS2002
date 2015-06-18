<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Rep Codes</title>
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
        <td colspan=3 align="center" class="largeBoldText">R E P &nbsp;&nbsp; C O D E S</td>
    </tr>
</table>


<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){?>
<?php echo my_draw_form('addRepcode',my_href_link('repcodes_mgr.php', 'action=add'));?>


<table width=350px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add Rep Code Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Rep Name:</td>
<td class="smallText"><?php echo my_draw_input_field('rep_name','','size=20'); ?></td>
</tr>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Rep Code:</td>
<td class="smallText"><?php echo my_draw_input_field('rep_code','','size=5'); ?></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('repcodes_mgr.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
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

    $repcode_mod_sql = "SELECT * FROM rep_codes WHERE rep_code='".$_GET['rcId'] ."'";
    $repcode_mod_query = my_db_query($repcode_mod_sql);
    $repcode_mod = my_db_fetch_array($repcode_mod_query);
?>


<?php echo my_draw_form('modRepcode',my_href_link('repcodes_mgr.php', 'action=mod&rcId='.$_GET['rcId']));?>


<table width=350px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify Rep Code Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Rep Name:</td>
<td>
<?php echo my_draw_input_field('rep_name',$repcode_mod['rep_name'],'size=25'); ?>
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Rep Code:</td>
<td class="smallText"><?php echo my_draw_input_field('rep_code',$repcode_mod['rep_code'],'size=5'); ?></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('repcodes_mgr.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
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
        $repcode_add_sql = sprintf("INSERT INTO `rep_codes` (`rep_name`,`rep_code` )
        VALUES ('%s','%s')", mysql_real_escape_string(trim($_POST['rep_name'])), trim(strtoupper($_POST['rep_code'])));
        $repcode_add_query = my_db_query($repcode_add_sql);

        if( $repcode_add_query == 1){
            echo "<div align=center class=\"success\">Rep Code Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Rep Code Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $repcode_del_sql ="delete from rep_codes where rep_code='".$_GET['rcId']."'";
        $repcode_del_query = my_db_query($repcode_del_sql);

        if( $repcode_del_query == 1){
            echo "<div align=center class=\"success\">Rep Code Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Rep Code Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){

        $repcode_mod_sql ="UPDATE `rep_codes` SET `rep_name` = '".trim($_POST['rep_name'])."',`rep_code` = '". trim(strtoupper($_POST['rep_code'])) ."' WHERE `rep_code`='".$_GET['rcId'] ."'";
        $repcode_mod_query = my_db_query($repcode_mod_sql);

        if( $repcode_mod_query == 1){
            echo "<div align=center class=\"success\">Rep Code Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Rep Code Not Modified</div>";
        }
    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=300 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link('repcodes_mgr.php', 'action=add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New Rep Code'); ?></a>
    </td></tr>
    </table>



<?php

    echo "<table width=400 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=4>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th width=10 colspan=2 valign=bottom>Actions</th>\n";
    echo "\t<th>Rep Name</th>\n";
    echo "\t<th>Rep Code</th>\n";
    echo "</tr>\n";

    $repcode_view_sql = "SELECT * FROM rep_codes WHERE 1 ORDER BY rep_name";
    $repcode_view_query = my_db_query($repcode_view_sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    while($repcode_view = my_db_fetch_array($repcode_view_query)){

        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link('repcodes_mgr.php',
        'action=mod_start&rcId='. $repcode_view['rep_code']). '">' .
        my_image(DIR_WS_IMAGES.'btnModify.gif','Modify Rep Code') ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link('repcodes_mgr.php','action=del&rcId='.
        $repcode_view['rep_code']). '" onClick="return confirmDelete()">' .
        my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete Rep Code') ."</a></td>";

        echo "<td align=center>". $repcode_view['rep_name'] ."</td>";
        echo "<td align=center>". $repcode_view['rep_code'] ."</td>";
        echo "</tr>\n";
        $count++;
    }
    echo "</table>\n";
}
?>




</form>
</body>
</html>
