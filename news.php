<?php
require('includes/application_top.php');

$tableName = "news";
$displayName = "News";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - News</title>
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
<script type="text/javascript" src="includes/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	theme : "simple"
});
</script>
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
        <td colspan=3 align="center" class="largeBoldText">N E W S</td>
		
    </tr>
    <tr>
        <td colspan=3 align="center" class="mediumBoldText"><p>Spell check your News.</p>
        <p>Re-Read your News and make sure that it makes sense.</p>
        <p>Mis-spelled words, and poorly written sentances will give our customers a bad impression of Kerusso.  </p></td>
		
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

?>


<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add <?php echo $displayName; ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Page&nbsp;Position:</td>
<td class="mediumBoldText">&nbsp;Newest On Top</td>
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

    $news_mod_sql = "SELECT * FROM ". $tableName ." WHERE ". $tableName ."_id=".$_GET['nId'];
    $news_mod_query = my_db_query($news_mod_sql);
    $news_mod = my_db_fetch_array($news_mod_query);
?>


<?php echo my_draw_form('mod_'.$tableName,my_href_link($tableName.'.php', 'action=mod&nId='.$_GET['nId']));?>
<?php echo my_draw_hidden_field($tableName.'_id',$_GET['nId']); ?>


<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify <?php echo $displayName; ?> Item</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Page&nbsp;Position:</td>
<td class="smallText">Newest On Top</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Headline:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_title',$news_mod['news_title'],'size=100'); ?>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Text:</td>
<td>
<?php echo my_draw_textarea_field($tableName.'_text','soft','74','5',$news_mod['news_text']); ?>
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
//****************************** COMMIT NEWS ADD ***************************
//*************************************************************************
    if( $_GET['action'] == 'add'  ){
        $mydate = date("y-m-d");
        $news_add_sql = sprintf("INSERT INTO `".$tableName."` (`".$tableName."_title`,`".$tableName."_text`,".$tableName."_postdate) VALUES ('%s','%s','%s')", mysql_real_escape_string($_POST['news_title']),$_POST['news_text'],$mydate);
        $news_add_query = my_db_query($news_add_sql);

        if( $news_add_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT NEWS DELETE ***************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $news_del_sql ="delete from ".$tableName." where ". $tableName."_id=".$_GET['nId'];
        $news_del_query = my_db_query($news_del_sql);
        if( $news_del_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT NEWS MODIFY ***************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){

        $news_mod_sql =sprintf("UPDATE `".$tableName."`
        SET `".$tableName."_postdate` = '%s',
        `".$tableName."_title` = '%s',`".$tableName."_text` = '%s'
        WHERE `".$tableName."_id`=".$_GET['nId'], date("y-m-d"),
        mysql_real_escape_string($_POST['news_title']),
        mysql_real_escape_string($_POST['news_text']));
        $news_mod_query = my_db_query($news_mod_sql);

        if( $news_mod_query == 1){
            echo "<div align=center class=\"success\">".$displayName." Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".$displayName." Not Modified</div>";
        }
    }
//*************************************************************************
//****************************** MAIN NEWS PAGE ****************************
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


    $news_view_sql = "SELECT * FROM ". $tableName ." WHERE 1 ORDER BY news_id DESC";

    $news_view_query = my_db_query($news_view_sql);
    while($news_view = my_db_fetch_array($news_view_query)){

    echo "<table  align=center cellspacing=0 border=0><tr><td valign=bottom>\n";
        if($_SESSION['userlevel'] == 'super' || $_SESSION['userlevel'] == 'admin'){
            echo "<a href=\"". my_href_link($tableName.'.php',
            'action=mod_start&nId='. $news_view[$tableName.'_id']). '">'
            . my_image(DIR_WS_IMAGES.'btnModify.gif','Modify '.$displayName)."</a>";
        }
        echo "</td>";
        echo "<td valign=bottom>";
        if($_SESSION['userlevel'] == 'super' || $_SESSION['userlevel'] == 'admin'){
            echo "<a href=\"". my_href_link($tableName.'.php',
            'action=del&nId='. $news_view[$tableName.'_id']).
            '" onClick="return confirmDelete()">' .
            my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete '.$displayName) ."</a>";
        }
        echo "</td>";
        echo "<th>";

    echo "<table width=600 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";
        //NEWS Title Row
        echo "<tr>";
        echo "<th class=\"newsTitle\"></th>";
        echo "<th align=center width=80% class=\"newsTitle\">&nbsp;&nbsp;&nbsp;&nbsp;".
        stripslashes($news_view[$tableName.'_title']) ."</th>";
        echo "<th class=\"newsDate\" align=right>posted:&nbsp;".$news_view[$tableName.'_postdate']."</th>";
        echo "</tr>\n";

        //NEWS Text Row
        echo "<tr >";
        echo "<th align=left width=100% colspan=3 class=\"newsText\"><p>".
        stripslashes($news_view[$tableName.'_text']) ."</th>";
        echo "</tr>\n";
    echo "</table>\n";

    echo "</th></tr></table >\n";

    echo "<br><br>\n";

    }
}
?>




</form>
</body>
</html>
