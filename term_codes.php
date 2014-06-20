<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Term Codes</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<script type="text/javascript">
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
        <td colspan=3 align="center" class="largeBoldText">T E R M  &nbsp;&nbsp; C O D E S</td>
    </tr>
</table>


<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){

    $max_sort_sql = "SELECT MAX(sort_order) AS maxSort FROM term_codes";
    $max_sort_query = my_db_query($max_sort_sql);
    $max_sort = my_db_fetch_array($max_sort_query);

?>
<?php echo my_draw_form('addTerms',my_href_link('term_codes.php', 'action=add'));?>


<table width=350px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add Term Codes Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Term Name:</td>
<td class="smallText"><?php echo my_draw_input_field('term_name','','size=20'); ?></td>
</tr>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Term Alias:</td>
<td class="smallText"><?php echo my_draw_input_field('term_alias','','size=20'); ?></td>
</tr>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Term Code:</td>
<td class="smallText"><?php echo my_draw_input_field('term_code','','size=5'); ?></td>
</tr>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Sort No.:</td>
<td class="smallText"><?php echo my_draw_input_field('sort_order',$max_sort['maxSort']+1,'size=5'); ?></td>
</tr>
<tr class="tableRowColor">
<td align=right class="mediumBoldText">Make Default:</td>
<td class="smallText"><?php echo my_draw_checkbox_field('isDefault'); ?></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('term_codes.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
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

    $term_codes_mod_sql = "SELECT * FROM term_codes WHERE term_codes_id=".$_GET['tcId'];
    $term_codes_mod_query = my_db_query($term_codes_mod_sql);
    $term_codes_mod = my_db_fetch_array($term_codes_mod_query);
    $is_selected = ($term_codes_mod['term_codes_default'] == 1)? true:false;
?>


<?php echo my_draw_form('modTerms',my_href_link('term_codes.php', 'action=mod&tcId='.$_GET['tcId']));?>


<table width=350px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify Term Codes Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Term Name:</td>
<td>
<?php echo my_draw_hidden_field('term_name',$term_codes_mod['term_name']); ?>
<?php echo $term_codes_mod['term_name']; ?>
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Term Alias:</td>
<td class="smallText"><?php echo my_draw_input_field('term_alias',$term_codes_mod['term_alias'],'size=5'); ?></td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Term Code:</td>
<td class="smallText"><?php echo my_draw_input_field('term_code',$term_codes_mod['term_code'],'size=5'); ?></td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Sort No.:</td>
<td class="smallText"><?php echo my_draw_input_field('sort_order',$term_codes_mod['sort_order'],'size=5'); ?></td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Make Default:</td>
<td class="smallText"><?php echo my_draw_checkbox_field('isDefault','',$is_selected); ?></td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link('term_codes.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
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
        $term_code_is_default = ($_POST['isDefault'] == "on")? 1 : 0;
        $term_codes_add_sql = sprintf("INSERT INTO `term_codes` (`term_name`,`term_alias`,`term_code`,
        `sort_order`, `term_codes_default` )
        VALUES ('%s','%s','%s',%d,%d)", mysql_real_escape_string($_POST['term_name']),
        mysql_real_escape_string($_POST['term_alias']), mysql_real_escape_string($_POST['term_code']),
        $_POST['sort_order'],$term_code_is_default);
        $term_codes_add_query = my_db_query($term_codes_add_sql);

        if( $term_codes_add_query == 1){
            echo "<div align=center class=\"success\">Term Code Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Term Code Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $term_codes_del_sql ="delete from term_codes where term_codes_id=".$_GET['tcId'];
        $term_codes_del_query = my_db_query($term_codes_del_sql);

        if( $term_codes_del_query == 1){
            echo "<div align=center class=\"success\">Term Code Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Term Code Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){

        $is_default = ($_POST['isDefault'] == "on" )? 1 : 0;
        $term_codes_mod_sql ="UPDATE `term_codes` SET `term_name` = '".$_POST['term_name']."',
                            `term_alias` = '".$_POST['term_alias']."',`term_code` = '".$_POST['term_code']."',
                            `sort_order` = '".$_POST['sort_order']."', `term_codes_default` = $is_default
                            WHERE `term_codes_id`=".$_GET['tcId'];
        $term_codes_mod_query = my_db_query($term_codes_mod_sql);



        //Sets all the rest of the term codes to non-default status
        if( $is_default == 1){
        $term_code_mod_default_sql ="UPDATE `term_codes` SET `term_codes_default` = '0' WHERE `term_codes_id` !=".$_GET['tcId'];
        $term_code_mod_default_query = my_db_query($term_code_mod_default_sql);
        }




        if( $term_codes_mod_query == 1){
            echo "<div align=center class=\"success\">Term Code Modified Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Term Code Not Modified</div>";
        }
    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=500 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link('term_codes.php', 'action=add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New Term Code'); ?></a>
    </td></tr>
    </table>



<?php

    echo "<table width=500 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=7>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th width=10 colspan=2 valign=bottom>Actions</th>\n";
    echo "\t<th>&nbsp;Name&nbsp;</th>\n";
    echo "\t<th>&nbsp;Value&nbsp;</th>\n";
    echo "\t<th>&nbsp;Alias&nbsp;</th>\n";
    echo "\t<th>&nbsp;Sort&nbsp;</th>\n";
    echo "\t<th>&nbsp;Default&nbsp;</th>\n";
    echo "</tr>\n";

    $term_codes_view_sql = "SELECT * FROM term_codes WHERE 1 ORDER BY term_name";
    $term_codes_view_query = my_db_query($term_codes_view_sql);
    $count = 0;
    $is_default = 0;
    $bgcolor = "#FFFFFF";
    while($term_codes_view = my_db_fetch_array($term_codes_view_query)){

        $is_default = ($term_codes_view['term_codes_default'] == 1)?"*":"";
        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link('term_codes.php',
        'action=mod_start&tcId='. $term_codes_view['term_codes_id']). '">' .
        my_image(DIR_WS_IMAGES.'btnModify.gif','Modify Term Code') ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link('term_codes.php','action=del&tcId='.
        $term_codes_view['term_codes_id']). '" onClick="return confirmDelete()">' .
        my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete Term Code') ."</a></td>";

        echo "<td align=center>". $term_codes_view['term_name'] ."</td>";
        echo "<td align=center>". $term_codes_view['term_code'] ."</td>";
        echo "<td align=center>". $term_codes_view['term_alias'] ."</td>";
        echo "<td align=center>". $term_codes_view['sort_order'] ."</td>";
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
