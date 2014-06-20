<?php
require('includes/application_top.php');

$tableName = "products_customer_prices";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Customer Prices</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<script type="text/javascript"
	src="includes/js/dojoroot/dojo/dojo.js"
	djConfig="parseOnLoad: true">
</script>
<script type="text/javascript">
    dojo.require("dojo._base.xhr");
</script>

<script type="text/JavaScript">
    function confirmDelete(){
        if(confirm("You are about to make a deletion, continue?")){
            return true;
        }else{
            return false;
        }
    }

    function submitForm(){
        var oForm = document.forms['mod_products_customer_prices'];
        var custPrice = dojo.byId("customer_price");

        if( custPrice.value.length == 0 ||  parseInt(custPrice.value) == 0){
			alert("Blank prices are not allowed.  Please enter a price or delete this price if you want to remove it.");
			return false;
        }else{
			oForm.submit();
			return false;
        }
	
    }
</script>
<script type="text/JavaScript" src="debugInfo.js"></script>

<script type="text/javascript">

function enableCategories(){
	var customerList = dojo.byId("accounts_id");
	var categoriesList = dojo.byId("categories_name");

	if(customerList.selectedIndex != 0){
		categoriesList.disabled = false;
	}else{
		categoriesList.disabled = true;
	}
}

function enableSizeButton(){
	var categoriesList = dojo.byId("categories_name");
	var sizeButton = dojo.byId("butSizes");

	if(categoriesList.selectedIndex != 0){
		sizeButton.disabled = false;
	}else{
		sizeButton.disabled = true;
	}
}

function getCatSizes(){
	var categoriesList = dojo.byId("categories_name");


	dojo.xhrPost({
	 <?php if($_SERVER['SERVER_NAME'] == 'localhost') {?>
		url:"http://localhost/kds/ajax_controller.php?action=get_cat_sizes&kdssid=<?php echo $_GET['kdssid'];?>",
    <?php }else{ ?>
		url:"http://www.kerussods.com/ajax_controller.php?action=get_cat_sizes&kdssid=<?php echo $_GET['kdssid'];?>",
    <?php } ?>
		
		handleAs: "json",// IMPORTANT: tells Dojo to automatically parse the HTTP response into a JSON object
		
		content:{"categoryName":categoriesList.value},
		
		load:function(response, ioArgs){

			var tBody = dojo.byId("addTable").tBodies[0];
			for(var i=tBody.rows.length-1; i>=0; i--){
				tBody.rows[i].parentNode.removeChild(tBody.rows[i]);
			}

			for( var i=0; i<response.arrSizes.length; i++){
				var tr = document.createElement("TR");
				if(i%2 == 0){
					tr.setAttribute("class", "tableRowColorEven");
					tr.setAttribute("className", "tableRowColorEven");
				}else{
					tr.setAttribute("class", "tableRowColorOdd");
					tr.setAttribute("className", "tableRowColorOdd");
				}
				var td1 = document.createElement("TD");
				var td2 = document.createElement("TD");
				td1.setAttribute('align','right');
				td1.setAttribute('class', 'mediumBoldText');
				td1.setAttribute('className', 'mediumBoldText');
				
				td2.setAttribute('align','right');
				td2.setAttribute('class', 'smallText');
				td2.setAttribute('className', 'smallText');
				
				var hidCatId = document.createElement("INPUT");
				hidCatId.setAttribute('type','hidden');
				hidCatId.setAttribute('name','categories_id_'+i);
				hidCatId.setAttribute('value',response.arrSizes[i].id);
				
				var hidCatSzId = document.createElement("INPUT");
				hidCatSzId.setAttribute('type','hidden');
				hidCatSzId.setAttribute('name','categories_size_id_'+i);
				hidCatSzId.setAttribute('value',response.arrSizes[i].sizeId);
				
				var inpPrice = document.createElement("INPUT");
				inpPrice.setAttribute('type','text');
				inpPrice.setAttribute('name','products_customer_price_'+i);
				inpPrice.setAttribute('value',response.arrSizes[i].price);
				inpPrice.setAttribute('size', '5');

				var sizeName = document.createTextNode(response.arrSizes[i].code + " - " + response.arrSizes[i].size + "  Price: ");

//				if(response.arrSizes.length > 2){
					var blankMsg = document.createTextNode("[Note: Fill in only what you need, blank price fields will be skipped]");
//				}
				td1.appendChild(sizeName);
				td1.appendChild(inpPrice);
				td1.appendChild(hidCatId);
				td1.appendChild(hidCatSzId);
				td2.appendChild(blankMsg);
				tr.appendChild(td1);
				tr.appendChild(td2);
				tBody.appendChild(tr);
			}

			var sc = dojo.byId("sizeCount");
			sc.value = response.arrSizes.length

			
		},

		error: function(response){
//			dojo.byId("statusMessage").innerHTML = 	"An error occurred with response: " + response;
		}

	});
	
}

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
        <td colspan=3 align="center" class="largeBoldText">C U S T O M E R &nbsp;&nbsp; P R I C E S</td>
    </tr>
</table>


<br />
<br />

<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){
?>
<?php echo my_draw_form('add_'.$tableName,my_href_link($tableName.'.php', 'action=add'));?>
<div id="statusMessage"></div>
<table width=550px align="center" border=0  class="thinOutline" cellspacing=0 id="addTable">
<THEAD>
<tr class="tableHeader">
<th align=center colspan=2>Add <?php echo ucwords(str_replace("_"," ",$tableName)); ?> Form</th>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText">Customer:&nbsp;</td>
	<td class="smallText"><?php echo my_draw_pull_down_menu("accounts_id", getAccountsArray(),"0", " id=\"accounts_id\" onchange=\"enableCategories()\" "); ?></td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText">Category:&nbsp;</td>
	<td class="smallText">
	<?php echo my_draw_pull_down_menu("categories_name", getCategoriesArray(),"0", " id=\"categories_name\" disabled=true onchange=\"enableSizeButton()\""); ?>
	<input type="button" value="Get Sizes" onClick="getCatSizes()" disabled=true id="butSizes"/>
	</td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText" colspan=2><hr width="100%" height="1"/></td>
</tr>
</THEAD>
<TBODY>
</TBODY>
<tr class="tableFooter">
    <td colspan="2" align="center">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','100','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Addition'); ?>
		<?php echo my_draw_hidden_field('sizeCount','','id=\'sizeCount\''); ?>    </td>
</tr>
</table>

<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'mod_start' ){

    $customer_mod_sql = "SELECT pc.categories_id, a.accounts_id, 
    a.accounts_company_name, a.accounts_number, c.categories_size_id, 
    pc.customer_price, cs.cat_sizes_name, c.categories_code 
	FROM products_customer_prices pc, accounts a, 
	cat_sizes cs, categories c
	WHERE pc.accounts_id = a.accounts_id
	AND pc.categories_id = c.categories_id
	AND c.categories_size_id = cs.cat_sizes_id
	AND pc.categories_id =".$_GET['cId'] ."
	AND pc.accounts_id =".$_GET['aId'] ."
	AND c.categories_size_id =".$_GET['sId'];
    
//    echo $customer_mod_sql;
    
    $customer_mod_query = my_db_query($customer_mod_sql);
    $customer_mod = my_db_fetch_array($customer_mod_query);
   
?>


<?php echo my_draw_form('mod_'.$tableName,my_href_link($tableName.'.php', 'action=mod'));?>


<table width=550px align="center" border=0  class="thinOutline" cellspacing=0 cellpadding=5>
<tr class="tableHeader">
<th align=center colspan=2>Modify <?php echo ucwords(str_replace("_"," ",$tableName)); ?> Form</th>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText">Customer Number/Name:&nbsp;</td>
	<td class="mediumText">
	<?php echo "[".$customer_mod['accounts_number'] . "] ".$customer_mod['accounts_company_name']; ?> 
	<?php echo my_draw_hidden_field('accounts_id',$customer_mod['accounts_id']); ?>
	<?php echo my_draw_hidden_field('categories_id',$customer_mod['categories_id']); ?>
	</td>
</tr>

<tr class="tableRowColor">
	<td align=right class="mediumBoldText">
	<?php echo $customer_mod['categories_code']." - ".$customer_mod['cat_sizes_name']?>&nbsp;&nbsp;Price:&nbsp;
	<?php echo my_draw_hidden_field('categories_size_id',$customer_mod['categories_size_id']); ?>
	</td>
	<td class="smallText">
	<?php echo my_draw_input_field('customer_price',$customer_mod['customer_price'],'size=5 maxlength=5 id="customer_price"'); ?> 
	</td>
</tr>

<tr class="tableFooter">
    <td colspan="2" align="center">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image(DIR_WS_IMAGES.'spacer.gif','','100','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Modification', 'onClick="return submitForm()"'); ?>
		<?php echo my_draw_hidden_field('sizeCount',$count); ?>    </td>
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
    		
    		if( strlen($_POST['products_customer_price_'.$i]) > 0){
		        $customer_add_sql = sprintf("INSERT INTO `".$tableName."` 
		        (`accounts_id`,`categories_id`,`categories_size_id`,
		        `customer_price`) 
		        VALUES (%d,%d,%d,%5.2f)", $_POST['accounts_id'],$_POST['categories_id_'.$i],
		        $_POST['categories_size_id_'.$i], $_POST['products_customer_price_'.$i]);
		        $customer_add_query = my_db_query($customer_add_sql);
//echo $category_add_sql."<br><br>";
    		}
    	}

        if( $customer_add_query == 1){
            echo "<div align=center class=\"success\">".ucwords(str_replace("_"," ",$tableName))." Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".ucwords(str_replace("_"," ",$tableName))." Not Added</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $cust_del_sql ="delete from ".$tableName." 
        where accounts_id=".$_GET['aId']." and 
        categories_id=".$_GET['cId']." and
        categories_size_id=".$_GET['sId'];
        $cust_del_query = my_db_query($cust_del_sql);

        
        if( $cust_del_query > 0){
            echo "<div align=center class=\"success\">".ucwords(str_replace("_"," ",$tableName))." Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">".ucwords(str_replace("_"," ",$tableName))."  Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){
     	
		        $customer_update_sql = sprintf("UPDATE `".$tableName."` 
		        SET customer_price = %5.2f 
		            WHERE accounts_id = %d AND categories_id = %d AND categories_size_id = %d", 
		        $_POST['customer_price'], 
		        $_POST['accounts_id'], 
		        $_POST['categories_id'],
		        $_POST['categories_size_id'] );
		        
		        $customer_update_query = my_db_query($customer_update_sql);
//echo $customer_update_sql . "<br>";
    	
        if( $customer_update_query  == 1){
            echo "<div align=center class=\"success\">".ucwords(str_replace("_"," ",$tableName))." Modified Successfully</div>";

		}else{
            echo "<div align=center class=\"fail\">".ucwords(str_replace("_"," ",$tableName))." Not Modified</div>";
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
    echo "<th width=10 colspan=2 valign=bottom>Actions</th>\n";
    echo "\t<th>Account No.</th>\n";
    echo "\t<th>Customer Name</th>\n";
    echo "\t<th>Size</th>\n";
    echo "\t<th>Price</th>\n";
    echo "</tr>\n";
     
    $customer_prices_sql = "SELECT a.accounts_number AS AcctNo, a.accounts_id as aId, 
    a.accounts_company_name AS CoName, c.categories_id as cId, c.categories_size_id as sId, 
    c.categories_code AS Code, cs.cat_sizes_name AS Size, pc.customer_price AS Price
	FROM products_customer_prices pc, accounts a, categories c, cat_sizes cs
	WHERE pc.accounts_id=a.accounts_id AND pc.categories_id=c.categories_id AND 
	c.categories_size_id=cs.cat_sizes_id ORDER BY a.accounts_id, cs.cat_sizes_sort";
    
    $customer_prices_query = my_db_query($customer_prices_sql);
    $i=0;
    while($cp = my_db_fetch_array($customer_prices_query) ){
    	$bgcolor = "#FFFFFF";
        $bgcolor = ( fmod($i,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link('products_customer_prices.php',
        'action=mod_start&aId='.$cp['aId'].'&cId='.$cp['cId'].'&sId='.$cp['sId'])."\">" . my_image(DIR_WS_IMAGES.'btnModify.gif','Modify Category') ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link($tableName.'.php','action=del&aId='.$cp['aId'].'&cId='.
        $cp['cId'].'&sId='.$cp['sId']).'" onClick="return confirmDelete()">' . my_image(DIR_WS_IMAGES.'btnDelete.gif',
        'Delete Category') ."</a></td>";
        
        echo "<td align=center>". $cp['AcctNo'] ."</td>";
        echo "<td align=center>". $cp['CoName'] ."</td>";
        echo "<td align=center>". $cp['Code'] . " - ". $cp['Size'] ."</td>";
        echo "<td align=center colspan=2 width=200>". $cp['Price'] ."</td>";
        echo "</tr>\n";
        $i++;
    }
    echo "</table>\n";
}
?>




</form>
</body>
</html>
