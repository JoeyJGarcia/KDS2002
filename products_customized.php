<?php
require('includes/application_top.php');

$tableName = "products_customized";

	require_once(DIR_WS_CLASSES ."Category.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Customized Products</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<script type="text/javascript"
	src="includes/js/dojoroot/dojo/dojo.js"
	djConfig="parseOnLoad: true">
</script>

<script type="text/JavaScript">
    function confirmDelete(){
        if(confirm("You are about to make a deletion, continue?")){
            return true;
        }else{
            return false;
        }
    }

    function getProductId(){
        var oForm = document.forms["main_products_customized"];
        var pId = oForm.products_customized_id.value;
        var addPage = "<?php echo my_href_link($tableName.'.php', 'action=add_start'); ?>";
        if( pId == 0){
			alert("Please select a product before you request to add a customized product.");
			return false;
        }else{
        	oForm.action = addPage + "&pId=" +pId;
        	oForm.submit();
        }

        return true;
    }

    function submitCustomizedProduct(formName){
        var oForm = document.forms[formName+"_products_customized"];
        var count = dojo.byId("sizeCount").value;
        var priceCount = 0;

        for(var i=0; i<count; i++){
			if( dojo.byId("customized_price_"+i).value.length > 0 ){
				priceCount++;
			}
        }

        if( priceCount == 0 ){
			alert("No prices were found, you need to have at least one price for this item.");
			return false;
        }else{
        	oForm.submit();
        }

        return true;
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
        <td colspan=3 align="center" class="largeBoldText">C U S T O M I Z E D  &nbsp;&nbsp;  P R O D U C T S </td>
    </tr>
</table>

<div style="float:right;margin-right:15%">
	<div>
	<strong>Legend:</strong>
	</div>
	<div>
	Bold - Denotes an "Absolute Price"
	</div>
</div>

<br />
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
<th align=center colspan=2>Add Customized Product Form</th>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText">Customized Product:&nbsp;</td>
	<td class="mediumLargeBoldText"><?php echo getProductNameById($_GET['pId']); ?>
	<?php echo my_draw_hidden_field("product_id", $_GET['pId']); ?>
	</td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText" colspan=2><hr width="100%" height="1"/></td>
</tr>

<tr class="tableRowColor">
	<td align=center class="mediumBoldText" colspan=2>Set the customized size prices for this product, leave prices empty if they don't apply.
</td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText" colspan=2><hr width="100%" height="1"/></td>
</tr>
<?php 

$customized_query = getCustomizedProductSizesPricesQuery($_GET['pId']);
$count = 0;
while($customized_rs = my_db_fetch_array($customized_query) ){
	       
	$bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";
?>
<tr class="<?php echo $bgcolor; ?>">
	<td align="right" class="mediumBoldText" >
		<div class=""><?php echo $customized_rs['size_name']; 
		?>&nbsp;&nbsp;&nbsp;&nbsp;Price:&nbsp;<?php echo my_draw_input_field("customized_price_".$count,'',"size=5 id='customized_price_".$count."'"); ?>
			<?php echo my_draw_hidden_field("categories_id_".$count,$customized_rs['cat_id']); ?>
		</div>
	</td>
	<td align="left" class="mediumText" >
			<?php echo my_draw_checkbox_field("absolute_price_".$count,"1"); ?>
			&nbsp; Check to force absolute price
	</td>
</tr>
<?php 
$count++;
}
?>
<tr class="tableFooter">
    <td colspan="2" align="center">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','100','1'); ?>
        <?php echo my_image(DIR_WS_IMAGES.'btnSubmit.gif','Submit Addition','','',"onClick=\"return submitCustomizedProduct('add')\""); ?>
		<?php echo my_draw_hidden_field('sizeCount',$count,"id='sizeCount'"); ?>    
	</td>
</tr>

</table>

<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'mod_start' ){    
?>


<?php echo my_draw_form('mod_'.$tableName,my_href_link($tableName.'.php', 'action=mod'));?>


<table width=550px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify Customized Product Form</th>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText">Customized Product:&nbsp;</td>
	<td class="mediumLargeBoldText"><?php echo getProductNameById($_GET['pId']); ?>
	<?php echo my_draw_hidden_field("product_id", $_GET['pId']); ?>
	</td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText" colspan=2><hr width="100%" height="1"/></td>
</tr>

<tr class="tableRowColor">
	<td align=center class="mediumBoldText" colspan=2>Set the customized size prices for this product, leave prices empty if they don't apply.
</td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText" colspan=2><hr width="100%" height="1"/></td>
</tr>
<?php 
$arrCustomizedProduct = getCustomizedProductArray($_GET['pId']);
$arrAbsolutePrice = getAbsolutePricesArray($_GET['pId']);
$customized_query = getCustomizedProductSizesPricesQuery($_GET['pId']);
$count = 0;
while($customized_rs = my_db_fetch_array($customized_query) ){
	$isAbsolute = ($arrAbsolutePrice[$customized_rs['cat_id']])? true : false;
	       
	$bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";
?>
<tr class="<?php echo $bgcolor; ?>">
	<td align="right" class="mediumBoldText" >
		<div class=""><?php echo $customized_rs['size_name']; 
		?>&nbsp;&nbsp;&nbsp;&nbsp;Price:&nbsp;<?php echo my_draw_input_field("customized_price_".$count,$arrCustomizedProduct[$customized_rs['cat_id']],"size=5 id='customized_price_".$count."'"); ?>
			<?php echo my_draw_hidden_field("categories_id_".$count,$customized_rs['cat_id']); ?>
		</div>
	</td>
	<td align="left" class="mediumText" >
			<?php echo my_draw_checkbox_field("absolute_price_".$count,"1", $isAbsolute); ?>
			&nbsp; Check to force absolute price
	</td>
</tr>
	<?php 
$count++;
}
?>

<tr class="tableFooter">
    <td colspan="2" align="center">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','100','1'); ?>
        <?php echo my_image(DIR_WS_IMAGES.'btnSubmit.gif','Submit Addition','','',"onClick=\"return submitCustomizedProduct('mod')\""); ?>
		<?php echo my_draw_hidden_field('sizeCount',$count,"id='sizeCount'"); ?>    
	</td>
</tr>

</table>

<?php
}else{

//*************************************************************************
//****************************** COMMIT ADD *******************************
//*************************************************************************
    if( $_GET['action'] == 'add'  ){
    	
    	for($i=0; $i<$_POST['sizeCount']; $i++){
    		
    		$isAbsolute = ($_POST['absolute_price_'.$i] == "1")? 1 : 0;
    		
    		if( strlen($_POST['customized_price_'.$i]) > 0){
		        $customized_add_sql = sprintf("INSERT INTO `".$tableName."` 
		        (`categories_id`,`product_id`,`customized_price`,
		        `absolute_price` ) 
		        VALUES (%d,%d,%5.2f,%d)", $_POST['categories_id_'.$i],
		        $_POST['product_id'],
		        $_POST['customized_price_'.$i],
		        $isAbsolute);
		        $customized_add_query = my_db_query($customized_add_sql);
    		}
    	}

        if( $customized_add_query == 1){
            echo "<div align=center class=\"success\"> Customized Prices Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Customized Prices Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $cat_del_sql ="delete from ".$tableName." where product_id=".$_GET['pId'];
        $cat_del_query = my_db_query($cat_del_sql);

        
        if( $cat_del_query > 0){
            echo "<div align=center class=\"success\">Customized Prices Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Customized Prices Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){
 
    	//First remove the current rows for this catCode
        $cat_del_sql ="delete from ".$tableName." where product_id=".$_POST['product_id'];
        $cat_del_query = my_db_query($cat_del_sql);
    	    	
        //Now add in the new rows for this catCode
    	for($i=0; $i<$_POST['sizeCount']; $i++){
    		
    		$isAbsolute = ($_POST['absolute_price_'.$i] == "1")? 1 : 0;
    		
    		if( strlen($_POST['customized_price_'.$i]) > 0){
		        $customized_add_sql = sprintf("INSERT INTO `".$tableName."` 
		        (`categories_id`,`product_id`,`customized_price`,
		        `absolute_price` ) 
		        VALUES (%d,%d,%5.2f,%d)", $_POST['categories_id_'.$i],
		        $_POST['product_id'],
		        $_POST['customized_price_'.$i],
		        $isAbsolute);
		        $customized_add_query = my_db_query($customized_add_sql);
    		}
    	}   	
    	
    	
        if( $customized_add_query  == 1){
            echo "<div align=center class=\"success\">Customized Prices Modified Successfully</div>";

		}else{
            echo "<div align=center class=\"fail\">Customized Prices Not Modified</div>";
        }

    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
<?php echo my_draw_form('main_'.$tableName,my_href_link($tableName.'.php'));?>

    <table width=800 align="center" border=0 cellspacing=0>
    <tr><td align=right> <?php echo my_draw_pull_down_menu($tableName.'_id',getProductsArrayLessCustomized()); ?>
    <?php echo my_image(DIR_WS_IMAGES."btnAdd.gif","Add New Size","",""," onClick='getProductId()' style='cursor:pointer;'"); ?>
    </td></tr>
    </table>



<?php

    echo "<table width=800 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=6>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th width=10 colspan=2 valign=bottom>Actions</th>\n";
    echo "\t<th>Name</th>\n";
    echo "\t<th colspan=2>Size &amp; Charge</th>\n";
    echo "</tr>\n";
    

    $distinct_cust_prods_query = getCustomizedProductsDistinctQuery();
    	        
    
    $bgcolor = "#FFFFFF";
 
    $i=0;
	while($distinct_cust_prods = my_db_fetch_array($distinct_cust_prods_query) ){
        $bgcolor = ( fmod($i,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link($tableName.".php",
        "action=mod_start&pId=". $distinct_cust_prods['product_id'] ) . "\">" .
        my_image(DIR_WS_IMAGES.'btnModify.gif','Modify Customized Product') ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link($tableName.".php","action=del&pId=". 
        $distinct_cust_prods['product_id']) . "\" onClick='return confirmDelete()'>" . 
        my_image(DIR_WS_IMAGES."btnDelete.gif", "Delete Customized Product") ."</a></td>";
        
        echo "<td align=left>". stripSlashes(getProductNameById($distinct_cust_prods['product_id'], true)) ."</td>";
        echo "<td align=center colspan=3 width=300><span style=\"color:#ff0000\">". getCustomizedSizesAndPrices($distinct_cust_prods['product_id']) ."</span></td>";
        echo "</tr>\n";
		$i++;
    }
    echo "</table>\n";
}
?>




</form>
</body>
</html>
