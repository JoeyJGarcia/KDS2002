<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Fees</title>
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
        <td colspan=3 align="center" class="largeBoldText">F E E S</td>
    </tr>
</table>


<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){?>
<?php echo my_draw_form('addFees',my_href_link('fees.php', 'action=add'));?>


<table width=350px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add Fees Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td class="smallText"><?php echo my_draw_input_field('fee_name','','size=20'); ?></td>
</tr>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Value:</td>
<td class="smallText"><?php echo my_draw_input_field('fee_value','','size=5'); ?></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('fees.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
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

    $fees_mod_sql = "SELECT * FROM fees WHERE fees_id=".$_GET['fId'];
    $fees_mod_query = my_db_query($fees_mod_sql);
    $fees_mod = my_db_fetch_array($fees_mod_query);
?>


<?php echo my_draw_form('modFees',my_href_link('fees.php', 'action=mod&fId='.$_GET['fId']));?>


<table width=350px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify Fees Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Name:</td>
<td>
<?php echo my_draw_hidden_field('fees_name',$fees_mod['fees_name']); ?>
<?php echo $fees_mod['fees_name']; ?>
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Value:</td>
<td class="smallText"><?php echo my_draw_input_field('fees_value',$fees_mod['fees_value'],'size=5'); ?></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('fees.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
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
        $fees_add_sql = sprintf("INSERT INTO `fees` (`fees_name`,`fees_value` ) 
        VALUES ('%s',%01.2f)", mysql_real_escape_string($_POST['fee_name']),$_POST['fee_value']);
        $fees_add_query = my_db_query($fees_add_sql);

        if( $fees_add_query == 1){
            echo "<div align=center class=\"success\">Fee Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Fee Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $fees_del_sql ="delete from fees where fees_id=".$_GET['fId'];
        $fees_del_query = my_db_query($fees_del_sql);

        if( $fees_del_query == 1){
            echo "<div align=center class=\"success\">Fee Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Fee Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){

        $fees_mod_sql ="UPDATE `fees` SET `fees_name` = '".$_POST['fees_name']."',`fees_value` = ".$_POST['fees_value']." WHERE `fees_id`=".$_GET['fId'];
        $fees_mod_query = my_db_query($fees_mod_sql);

        if( $fees_mod_query == 1){
            echo "<div align=center class=\"success\">Fee Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Fee Not Modified</div>";
        }
    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=300 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link('fees.php', 'action=add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New Fee'); ?></a>
    </td></tr>
    </table>



<?php

    echo "<table width=200 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=4>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th width=10 colspan=2 valign=bottom>Actions</th>\n";
    echo "\t<th>Name</th>\n";
    echo "\t<th>Value</th>\n";
    echo "</tr>\n";

    $fees_view_sql = "SELECT * FROM fees WHERE 1 ORDER BY fees_name";
    $fees_view_query = my_db_query($fees_view_sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    while($fees_view = my_db_fetch_array($fees_view_query)){

        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link('fees.php',
        'action=mod_start&fId='. $fees_view['fees_id']). '">' .
        my_image(DIR_WS_IMAGES.'btnModify.gif','Modify Fee') ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link('fees.php','action=del&fId='.
        $fees_view['fees_id']). '" onClick="return confirmDelete()">' .
        my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete Fee') ."</a></td>";

        echo "<td align=center>". $fees_view['fees_name'] ."</td>";
        echo "<td align=center>". $fees_view['fees_value'] ."</td>";
        echo "</tr>\n";
        $count++;
    }
    echo "</table>\n";
}
?>




</form>
</body>
</html>
