<?php
require('includes/application_top.php');

$tableName = "categories";

	require_once(DIR_WS_CLASSES ."Category.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Categories</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<script type="text/JavaScript">
    function confirmDelete(){
        if(confirm("This will delete the chosen category size from other areas that use this specific category size, continue?")){
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
        <td colspan=3 align="center" class="largeBoldText">C A T E G O R I E S</td>
    </tr>
</table>

<div style="float:right;margin-right:15%">
	<div>
	<strong>Legend:</strong>
	</div>
	<div>
	* - Price is Non-Standard Size
	</div>
</div>
<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){?>
<?php echo my_draw_form('add_'.$tableName,my_href_link($tableName.'.php', 'action=add'));?>


<table width=550px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add <?php echo ucfirst($tableName); ?> Form</th>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText">Category Name:&nbsp;</td>
	<td class="smallText"><?php echo my_draw_input_field($tableName.'_name','','size=40'); ?></td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText">Prefix Code:&nbsp;</td>
	<td class="smallText"><?php echo my_draw_input_field($tableName.'_code','','size=10 maxlength=10'); ?></td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText" colspan=2><hr width="100%" height="1"/></td>
</tr>

<tr class="tableRowColor">
	<td align=center class="mediumBoldText" colspan=2>Set default size prices for this category, leave prices empty if they don't apply.
	<p>If this product doesn't come in sizes, just enter a price in the "NA" field.</p></td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText" colspan=2><hr width="100%" height="1"/></td>
</tr>
<?php 

$categories_sql = "SELECT * FROM cat_sizes order by cat_sizes_sort";
$categories_query = my_db_query($categories_sql);
$count = 0;
while($categories_rs = my_db_fetch_array($categories_query) ){
	       
	$bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";
?>
<tr class="<?php echo $bgcolor; ?>">
	<td align="center" class="mediumBoldText" >
		<div class=""><?php echo $categories_rs['cat_sizes_name']; 
		?>&nbsp;&nbsp;&nbsp;&nbsp;Price:&nbsp;<?php echo my_draw_input_field($tableName.'_price_'.$count,'','size=5'); ?>
			<?php echo my_draw_hidden_field($tableName.'_size_id_'.$count,$categories_rs['cat_sizes_id'],'size=5'); ?>
		</div>
	</td>
	<td align="left" class="mediumBoldText">
	<?php echo my_draw_checkbox_field($tableName.'_std_size_'.$count,'1'); ?> Check to make this a Standard Size.
	</td>
<?php 
$count++;
}
?>
<tr class="tableFooter">
    <td colspan="2" align="center">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','100','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Addition'); ?>
		<?php echo my_draw_hidden_field('sizeCount',$count); ?>    </td>
</tr>

</table>

<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'mod_start' ){

    $cat_mod_sql = "SELECT categories_name AS name, categories_code AS code,
    categories_size_price AS price, cat_sizes_name AS size  FROM categories c, cat_sizes cs 
    WHERE c.categories_size_id=cs.cat_sizes_id AND categories_id=".$_GET['catId'];
    $cat_mod_query = my_db_query($cat_mod_sql);
    $cat_mod = my_db_fetch_array($cat_mod_query);    
?>
		<?php echo my_draw_form('mod_'.$tableName,my_href_link($tableName.'.php', 'action=mod&catId='.$_GET['catId']));?>

		<table width=550px align="center" border=0  class="thinOutline" cellspacing=0 cellpadding=5>
		<tr class="tableHeader">
		<th align=center colspan=2>Modify <?php echo ucwords(str_replace("_"," ",$tableName)); ?> Size Price Form</th>
		</tr>
		
		<tr class="tableRowColor">
			<td align=right class="mediumBoldText">Categories Name:&nbsp;</td>
			<td class="mediumText">
			<?php echo $cat_mod['name']; ?> 
			<?php echo my_draw_hidden_field('categories_id',$_GET['catId']); ?>
			</td>
		</tr>
		
		<tr class="tableRowColor">
			<td align=right class="mediumBoldText">Categories Size:&nbsp;</td>
			<td class="mediumText">
			<?php echo $cat_mod['code']." - ".$cat_mod['size']?> 
			</td>
		</tr>
		
		<tr class="tableRowColor">
			<td align=right class="mediumBoldText">
			 Price:&nbsp;
			</td>
			<td class="smallText">
			<?php echo my_draw_input_field('categories_price',$cat_mod['price'],'size=5 maxlength=5 id="categories_price"'); ?> 
			</td>
		</tr>
		
		<tr class="tableFooter">
		    <td colspan="2" align="center">
		        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
		        <?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','100','1'); ?>
		        <?php echo my_image_submit('btnSubmit.gif','Submit Modification', 'onClick="return submitForm()"'); ?>
			</td>
		</tr>
		
		</table>
		
<?php
}else{

//*************************************************************************
//****************************** COMMIT ADD *******************************
//*************************************************************************
    if( $_GET['action'] == 'add'  ){
//echo "SC: ". $_POST['sizeCount'] . "<br>";
    	
    	for($i=0; $i<$_POST['sizeCount']; $i++){

    		$isStandard = ( $_POST[$tableName.'_std_size_'.$i] == "1")? 1 : 0;
    		
    		if( strlen($_POST[$tableName.'_price'.'_'.$i]) > 0){
		        $category_add_sql = sprintf("INSERT INTO `".$tableName."` 
		        (`".$tableName."_code`,`".$tableName."_name`,`".$tableName."_size_id`,
		        `".$tableName."_std_size`,`".$tableName."_size_price` ) 
		        VALUES ('%s','%s',%d,%d,%5.2f)", mysql_real_escape_string($_POST[$tableName.'_code']),$_POST[$tableName.'_name'],
		        $_POST[$tableName.'_size_id_'.$i], $isStandard, $_POST[$tableName.'_price_'.$i]);
		        $category_add_query = my_db_query($category_add_sql);
//echo $category_add_sql."<br><br>";
    		}
    	}

        if( $category_add_query == 1){
            echo "<div align=center class=\"success\">".ucfirst($tableName)." Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".ucfirst($tableName)." Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){

        $cat_del_sql3 ="delete from products_customized where categories_id=".$_GET['catId'];
        $cat_del_query = my_db_query($cat_del_sql3);

        $cat_del_sql2 ="delete from products_customer_prices where categories_id=".$_GET['catId']; 
        $cat_del_query = my_db_query($cat_del_sql2);
                
        $cat_del_sql1 ="delete from ".$tableName." where categories_id=".$_GET['catId']; 
        $cat_del_query = my_db_query($cat_del_sql1);
        
        if( $cat_del_query > 0){
            echo "<div align=center class=\"success\">".ucfirst($tableName)." Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".ucfirst($tableName)." Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){
 
        $cat_update_sql = sprintf("UPDATE `".$tableName."` 
        SET categories_size_price = %5.2f WHERE categories_id = %d", 
        $_POST['categories_price'], $_POST['categories_id']);
        
		$category_mod_query = my_db_query($cat_update_sql);
    	
        if( $category_mod_query  == 1){
            echo "<div align=center class=\"success\">".ucfirst($tableName)." Modified Successfully</div>";

		}else{
            echo "<div align=center class=\"fail\">".ucfirst($tableName)." Not Modified</div>";
        }
    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=800 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link($tableName.'.php', 'action=add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New Size'); ?></a>
    </td></tr>
    </table>



<?php
	
	    echo "<table width=800 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";
	
	    echo "<tr class=\"tableHeader\"><td colspan=6>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";
	
	    echo "<tr class=\"tableHeader\">\n";
	    echo "<th width='1%' colspan=2 valign=bottom>Actions</th>\n";
	    echo "\t<th width='20%'>Category Name</th>\n";
	    echo "\t<th width='1%'>Code</th>\n";
	    echo "\t<th width='5%'>Size </th>\n";
	    echo "\t<th width='5%'>Charge</th>\n";
	    echo "</tr>\n";
    
		$cat_view_sql = "SELECT categories_name AS name, categories_code AS code, cat_sizes_name AS size, 
		categories_size_price AS price, categories_std_size AS std, categories_id AS id 
		FROM categories c, cat_sizes cs 
		WHERE c.categories_size_id=cs.cat_sizes_id
		ORDER BY categories_code, cat_sizes_sort";
	    
	    
	    $cat_view_query = my_db_query($cat_view_sql);
	    
    	$i=0;	    
	while($cat = my_db_fetch_array($cat_view_query)){    
    
        $isStdColor = ($cat['std'] == 1)?"#000000":"#888888";
        $isStdAsterisk = ($cat['std'] == 1)?"":"*";
        $bgcolor = "#FFFFFF";
        $bgcolor = ( fmod($i,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link($tableName.'.php',
        'action=mod_start&catId='.$cat['id'] ). '">' .
        my_image(DIR_WS_IMAGES.'btnModify.gif','Modify Category') ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link($tableName.'.php','action=del&catId='. $cat['id']) .
        '" onClick="return confirmDelete()">' . my_image(DIR_WS_IMAGES.'btnDelete.gif',
        'Delete Category') ."</a></td>";
        
        echo "<td align=center style=\"color:$isStdColor\">". $cat['name'] ."</td>";
        echo "<td align=center style=\"color:$isStdColor\">". $cat['code'] ."</td>";
        echo "<td align=center style=\"color:$isStdColor\">". $cat['size'] ."</td>";
        echo "<td align=center style=\"color:$isStdColor\">". $cat['price'] .$isStdAsterisk."</td>";
        echo "</tr>\n";
		$i++;
    }
    echo "</table>\n";
    echo "<div align=\"center\">* - Price is Non-Standard Size</div>\n";
    
    
}
?>




</form>
</body>
</html>
