<?php
require('includes/application_top.php');

$tableName = "faqs";
$displayName = "FAQs";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - FAQs</title>
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
        <td colspan=3 align="center" class="largeBoldText">F A Q S</td>
    </tr>
</table>

<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FAQ FORM *****************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){?>
<?php echo my_draw_form('add_'.$tableName,my_href_link($tableName.'.php', 'action=add'));


    $max_sort_sql = "SELECT MAX(". $tableName ."_sort_order) AS maxSort FROM ". $tableName;
    $max_sort_query = my_db_query($max_sort_sql);
    $max_sort = my_db_fetch_array($max_sort_query);

?>


<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add <?php echo $displayName; ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Title:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_title','','size=100'); ?>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Text:</td>
<td>
<?php echo my_draw_textarea_field($tableName.'_text','soft','74','5'); ?>
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Order:</td>
<td>
<?php echo my_draw_input_field($tableName.'_sort_order',$max_sort['maxSort']+1,'size=2'); ?>
<font color="#888888">Note: FAQs will be sorted by this field</font>
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
//*************************** MODIFY FAQ FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'mod_start' ){

    $faqs_mod_sql = "SELECT * FROM ". $tableName ." WHERE ". $tableName ."_id=".$_GET['fId'];
    $faqs_mod_query = my_db_query($faqs_mod_sql);
    $faqs_mod = my_db_fetch_array($faqs_mod_query);
?>


<?php echo my_draw_form('mod_'.$tableName,my_href_link($tableName.'.php', 'action=mod&fId='.$_GET['fId']));?>
<?php echo my_draw_hidden_field($tableName.'_id',$_GET['fId']); ?>

<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify <?php echo $displayName; ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Title:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_title',$faqs_mod['faqs_title'],'size=100'); ?>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Text:</td>
<td>
<?php echo my_draw_textarea_field($tableName.'_text','soft','74','5',$faqs_mod['faqs_text']); ?>
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Order:</td>
<td>
<?php echo my_draw_input_field($tableName.'_sort_order',$faqs_mod['faqs_sort_order'],'size=2'); ?>
<font color="#888888">Note: FAQs will be sorted by this field</font>
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
}else{

//*************************************************************************
//****************************** COMMIT FAQ ADD ***************************
//*************************************************************************
    if( $_GET['action'] == 'add'  ){
        $faqs_add_sql = sprintf("INSERT INTO `".$tableName."` (`".$tableName."_title`,`".$tableName."_text`,".$tableName."_sort_order ) VALUES ('%s','%s',%d)", mysql_real_escape_string($_POST['faqs_title']),$_POST['faqs_text'],$_POST['faqs_sort_order']);

        $faqs_add_query = my_db_query($faqs_add_sql);

        if( $faqs_add_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT FAQ DELETE ***************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $faqs_del_sql ="delete from ".$tableName." where ". $tableName."_id=".$_GET['fId'];
        $faqs_del_query = my_db_query($faqs_del_sql);
        if( $faqs_del_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT FAQ MODIFY ***************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){

        $faqs_mod_sql ="UPDATE `".$tableName."` SET `".$tableName."_sort_order` = ".$_POST['faqs_sort_order'].",`".$tableName."_title` = '".$_POST['faqs_title']."',`".$tableName."_text` = '".$_POST['faqs_text']."' WHERE `".$tableName."_id`=".$_GET['fId'];
        $faqs_mod_query = my_db_query($faqs_mod_sql);
        if( $faqs_mod_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Modified</div>";
        }
    }
//*************************************************************************
//****************************** MAIN FAQ PAGE ****************************
//*************************************************************************

if( $_SESSION['userlevel'] == 'super' || $_SESSION['userlevel'] == 'admin'){
?>
    <table width=600 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link($tableName.'.php', 'action=add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New '.$displayName); ?></a>
    </td></tr>
    </table>
<?php
}


    $faqs_view_sql = "SELECT * FROM ". $tableName ." WHERE 1 ORDER BY ". $tableName ."_sort_order";

    $faqs_view_query = my_db_query($faqs_view_sql);
    $count = 1;
    while($faqs_view = my_db_fetch_array($faqs_view_query)){

    echo "<table  align=center cellspacing=0><tr><td>\n";
        if( $_SESSION['userlevel'] == 'super' || $_SESSION['userlevel'] == 'admin'){
            echo "<a href=\"". my_href_link($tableName.'.php',
            'action=mod_start&fId='. $faqs_view[$tableName.'_id']). '">'
            . my_image(DIR_WS_IMAGES.'btnModify.gif','Modify '.$displayName)."</a>";
        }
        echo "</td>";
        echo "<td>";
        if( $_SESSION['userlevel'] == 'super' || $_SESSION['userlevel'] == 'admin'){
            echo "<a href=\"". my_href_link($tableName.'.php',
            'action=del&fId='. $faqs_view[$tableName.'_id']).
            '" onClick="return confirmDelete()">' .
            my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete '.$displayName) ."</a>";
        }
        echo "</td>";
        echo "<td>";


    echo "<table width=600 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";
        //FAQ Title Row
        echo "<tr>";
        echo "<th align=center valign=top colspan=2 bgcolor=\"#c5c5c5\"><span class=\"smallText\">".
        $count.")</span></th>";
        echo "<th align=left width=100% class=\"faqTitle\">&nbsp;&nbsp;&nbsp;&nbsp;".
        $faqs_view[$tableName.'_title'] ."</th>";
        echo "</tr>\n";

        //FAQ Text Row
        echo "<tr >";
        echo "<td rowspan=2 align=center bgcolor=\"#c5c5c5\">";
        echo "</td>";
        echo "<td  rowspan=2 align=center bgcolor=\"#c5c5c5\">";
        echo "</td>";
        echo "<th align=left width=100%  class=\"faqText\">&nbsp;&nbsp;&nbsp;&nbsp;". $faqs_view[$tableName.'_text'] ."</th>";
        echo "</tr>\n";
        echo "<tr >";
        echo "<th align=right width=100%  class=\"faqText\">&nbsp;&nbsp;&nbsp;&nbsp;";
		if( $_SESSION['userlevel'] == 'super' || $_SESSION['userlevel'] == 'admin'){
			echo "[sort order: ". $faqs_view[$tableName.'_sort_order'] ."]";
        }
		echo "</th>";
 		echo "</tr>\n";
   echo "</table>\n";

    echo "</td></tr></table >\n";

    echo "<br><br>\n";
    $count++;
    }
}
?>


</form>
</body>
</html>
