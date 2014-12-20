<?php
ini_set("memory_limit","120M");

class Form{


	var $arrSizes,$arrShipping,$arrInventory,$arrProducts;
	var $order,$productRows,$product_sizes,$product_names;


	function Form(){
		$this->getShippingOptions();
	}


	function writeAddForm($orderNum, $objOrder){
		global $order,$productRows;

		$this->order = $objOrder;
		$this->arrProducts = $objOrder->getProducts();
		$this->getProductRows();

		//echo "product count: ".count($objOrder->getProducts())."<br>";

		$orderNum;
		$acctNumber = trim($_SESSION['client_account_number']);

		$client_prefix_sql = "SELECT accounts_prefix, accounts_price_level FROM accounts WHERE accounts_number = '$acctNumber' LIMIT 1";
		$client_prefix_query = my_db_query($client_prefix_sql);
		$client_prefix_text = my_db_fetch_array($client_prefix_query);
		$client_prefix = trim($client_prefix_text['accounts_prefix']);
		$acctPriceLvl = $client_prefix_text['accounts_price_level'];


		$po_number = strtoupper($client_prefix).date("mdyhis")."-".$orderNum;
		$formTag = my_draw_form("frmAdd_$orderNum",my_href_link("bulk_orders_process.php",
		"action=add_order"));

	    $arrRepCodes = array();
	    $rep_codes_sql = "SELECT * FROM rep_codes";
	    $rep_codes_query = my_db_query($rep_codes_sql);
	    while( $rep_codes = my_db_fetch_array($rep_codes_query) ){
	        $arrRepCodes[$rep_codes['rep_name']] = $rep_codes['rep_code'];
	    }

	    $arrReps = array();
	    $reps_sql = "SELECT * FROM reps r WHERE r.accounts_number = " . $_SESSION['client_account_number'];
	    $reps_query = my_db_query($reps_sql);
	    $reps = my_db_fetch_array($reps_query);

		$customerName = $this->order->getCustomerName();
		$address1 = $this->order->getAddress1();
		$address2 = $this->order->getAddress2();
		$city = $this->order->getCity();
		$state = $this->order->getState();
		$zipcode = $this->order->getZipcode();
		$country = $this->order->getCountry();
		$orderNumber = $this->order->getOrderNumber();
		$shippingText = $this->order->getShippingMethod();
		$shippingID = $this->order->getShippingMethodValue();
		$productCount = count($this->arrProducts);
		$isRushOrder = ( $this->order->isRush() == "yes" )?" CHECKED ":"";
		$comments = $this->order->getComments();
		$intl_phone = $this->order->getIntlPhone();

		$rep1_name = $reps['field_rep'];
		$rep1_code = $arrRepCodes[$reps['field_rep']];
		$rep2_name = $reps['inside_rep'];
		$rep2_code = $arrRepCodes[$reps['inside_rep']];
		$rep3_name = $reps['field_group'];
		$rep3_code = $arrRepCodes[$reps['field_group']];
		$rep4_name = $reps['national_group'];
		$rep4_code = $arrRepCodes[$reps['national_group']];
		$rep5_name = $reps['national_rep'];
		$rep5_code = $arrRepCodes[$reps['national_rep']];
		$rep6_name = $reps['sales_mgr'];
		$rep6_code = $arrRepCodes[$reps['sales_mgr']];


    if( strlen($shippingID) == 0){
      $defaultShippingQuery = my_db_query("SELECT * FROM `shipping` WHERE `shipping_default` = 1");
      if(my_db_num_rows($defaultShippingQuery) > 0 ){
        $arrDefaultShipping = my_db_fetch_array($defaultShippingQuery);
  		  $defaultShipping = $arrDefaultShipping['shipping_id'];
      }
    }else{
       $defaultShipping = $shippingID;
    }

		$shippingMethods = my_draw_pull_down_menu("shipping_method",$this->arrShipping,$defaultShipping);


		echo <<<ADD_FORM
		$formTag
		<input type="hidden" name="doAction" value="addOrder"/>
		<input type="hidden" name="order_size" value="$productCount"/>
		<input type="hidden" name="accounts_number" value="$acctNumber"/>
		<input type="hidden" name="accounts_price_level" value="$acctPriceLvl"/>
		<input type="hidden" name="purchase_order_number" value="$po_number"/>

		<input type="hidden" name="rep1_name" value="$rep1_name"/>
		<input type="hidden" name="rep1_code" value="$rep1_code"/>
		<input type="hidden" name="rep2_name" value="$rep2_name"/>
		<input type="hidden" name="rep2_code" value="$rep2_code"/>
		<input type="hidden" name="rep3_name" value="$rep3_name"/>
		<input type="hidden" name="rep3_code" value="$rep3_code"/>
		<input type="hidden" name="rep4_name" value="$rep4_name"/>
		<input type="hidden" name="rep4_code" value="$rep4_code"/>
		<input type="hidden" name="rep5_name" value="$rep5_name"/>
		<input type="hidden" name="rep5_code" value="$rep5_code"/>
		<input type="hidden" name="rep6_name" value="$rep6_name"/>
		<input type="hidden" name="rep6_code" value="$rep6_code"/>


		<table align="center">
			<tr><td valign=top>

		<table width=500px align="center" border=0  class="thinOutline" cellspacing=0>
		  <tr class="tableHeader">
			<td class="mediumBoldText" colspan=2 align=center>
			  S H I P P I N G  &nbsp;&nbsp; I N F O R M A T A T I O N
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  Customer Name:&nbsp;<span style="color:red;font-weight:bold">*</span>
			</td>
			<td align=left class="mediumBoldText" >
			  <input type="text" name="customer_name" value="$customerName" size=30/>
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  Address Information:&nbsp;<span style="color:red;font-weight:bold">*</span>
			</td>
			<td align=left class="mediumBoldText" >
			  <input type="text" name="customer_address1" value="$address1" size=30 maxlength=30/>
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  Add'l Address Information:&nbsp;&nbsp;&nbsp;
			</td>
			<td align=left class="mediumBoldText" >
			  <input type="text" name="customer_address2" value="$address2" size=30 maxlength=30/>
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  City:&nbsp;<span style="color:red;font-weight:bold">*</span>
			</td>
			<td align=left class="mediumBoldText" >
			  <input type="text" name="customer_city" value="$city" size=30/>
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  State:&nbsp;<span style="color:red;font-weight:bold">*</span>
			</td>
			<td align=left class="mediumBoldText" >
			  <input type="text" name="customer_state" value="$state" size=30/>
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  Zip/Postal Code:&nbsp;<span style="color:red;font-weight:bold">*</span>
			</td>
			<td align=left class="mediumBoldText" >
			  <input type="text" name="customer_zip" value="$zipcode" size=30/>
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  Country:&nbsp;<span style="color:red;font-weight:bold">*</span>
			</td>
			<td align=left class="mediumBoldText" >
			  <input type="text" name="customer_country" value="$country" size=30/>
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  International Phone:&nbsp;<span style="color:red;font-weight:bold"></span>
			</td>
			<td align=left class="mediumBoldText" >
			  <input type="text" name="customer_intl_phone" value="$intlPhone" size=30/>
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
      Submitted Shipping:
			</td>
			<td align=left class="mediumBoldText" >
			  &nbsp;$shippingText
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  Shipping Method:
			</td>
			<td align=left class="mediumBoldText" >
			  $shippingMethods
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=right class="mediumBoldText" >
			  Your Order Number:
			</td>
			<td align=left class="mediumBoldText" >
			  <input type="text" name="customer_order_no" value="$orderNumber" size=30/>
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=center class="mediumBoldText" colspan=2 >
			  <input type="checkbox" name="isRush" $isRushOrder > Check to <font color="red">
			  <strong><em>RUSH</em></strong></font> this Order (fees will be applied)
			</td>
		  </tr>
		</table>


		<br />


		<div align="center" class="smallText">
		<font color="red"><strong>Note: Use size "NA" for items where size doesn't
		apply, such as gifts, hats, etc...</strong></font>
		</div>

		<div align="center" class="smallText">
		<table width=500px align="center" border=0  class="thinOutline" cellspacing=0>
		<tr class="tableRowColorLtYellow">
			<td align=center class="mediumBoldText" colspan=2>
				Yellow rows show your order information from the uploaded file. <BR>
				<font color="red"><u>PLEASE VERIFY ORDER INFORMATION BEFORE SUBMITTING!</u></font>
			</td>
		</table>
		</div>

		<table width=500px align="center" border=0  class="thinOutline" cellspacing=0>
		  <tr class="tableHeader">
			<td colspan=3 class="mediumBoldText" align="center">
			  P R O D U C T S
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td align=center class="mediumBoldText" >
			  Quantity
			</td>
			<td align=left class="mediumBoldText" >
			  Size
			</td>
			<td align=left class="mediumBoldText" >
			  Product Code / Name
			</td>
		  </tr>
		  $this->productRows
		</table>


		<br />


		<table width=500px align="center" border=0  class="thinOutline" cellspacing=0>
		  <tr class="tableHeader">
			<td colspan=3 class="mediumBoldText" align="center">
			  C O M M E N T S
			</td>
		  </tr>
		  <tr class="tableRowColor">
			<td colspan=3 align="center">
			  <textarea name="order_comments" wrap="soft" cols="40" rows="5">$comments</textarea>
			</td>
		  </tr>
		  <tr  class="tableFooter">
			<td colspan="3" align="CENTER">
	        <img src="images/btnSubmit.gif" border="0" alt="Submit Order" title=" Submit Order " onclick="submitOrder($orderNum)"/>
			</td>
		  </tr>
		</table>

		</td>
		<td valign=top>
		<!-- place holder for submit button -->

		</td>
		</table>

		</form>

ADD_FORM;


	}//end of function/method



	function getProductRows(){
		global $order, $productRows, $arrProducts;

		$this->productRows = "";
		for($i=0; $i<count($this->arrProducts);$i++){
			$this->getInventoryOptions($this->arrProducts[$i]->getModel());
			//echo " '" .$this->arrProducts[$i]->getModel()  . "', ";
			$this->getSizeOptions($this->arrProducts[$i]->getSize(), $this->arrProducts[$i]->getModel());

			$sizeDefault = $this->arrProducts[$i]->getSize();
            //echo "DEBUG:: '".  $sizeDefault. "'<BR>";

			$product_sizes = my_draw_bulk_orders_pull_down_menu("product_size_".$i,$this->arrSizes,$sizeDefault);

			$modelDefault = $this->arrProducts[$i]->getModel();
            //echo "DEBUG:: '".  $modelDefault. "'<BR>";
			if( strlen($modelDefault) == 6 ){
                $firstThreeModelDefault = substr($modelDefault,0,3);
			  	if( $firstThreeModelDefault == "APT")
					$modelDefault = str_replace("APT","APTA", $modelDefault);
			  	if( $firstThreeModelDefault == "BCC")
					$modelDefault = str_replace("BCC","BCCA", $modelDefault);
			  	if( $firstThreeModelDefault == "BNY")
					$modelDefault = str_replace("BNY","BNYA", $modelDefault);
			  	if( $firstThreeModelDefault == "FGW")
					$modelDefault = str_replace("FGW","FGWC", $modelDefault);
			  	if( $firstThreeModelDefault == "GSM")
					$modelDefault = str_replace("GSM","GSMW", $modelDefault);
			  	if( $firstThreeModelDefault == "HPS")
					$modelDefault = str_replace("HPS","HPSA", $modelDefault);
			  	if( $firstThreeModelDefault == "ZHD")
					$modelDefault = str_replace("ZHD","ZHDA", $modelDefault);
			  	if( $firstThreeModelDefault == "LST")
					$modelDefault = str_replace("LST","LSTA", $modelDefault);
			  	if( $firstThreeModelDefault == "YTC")
					$modelDefault = str_replace("YTC","YTCA", $modelDefault);
			  	if( $firstThreeModelDefault == "TSS")
					$modelDefault = str_replace("TSS","TSSA", $modelDefault);
			  	if( $firstThreeModelDefault == "SJT")
					$modelDefault = str_replace("SJT","SJTA", $modelDefault);
			  	if( $firstThreeModelDefault == "BTA")
					$modelDefault = str_replace("BTA","BTAA", $modelDefault);
			  	if( $firstThreeModelDefault == "SWC")
					$modelDefault = str_replace("SWC","SWCA", $modelDefault);
			  	if( $firstThreeModelDefault == "YHS")
					$modelDefault = str_replace("YHS","YHSA", $modelDefault);
			  	if( $firstThreeModelDefault == "YLS")
					$modelDefault = str_replace("YLS","YLSA", $modelDefault);
			}

				$product_name = my_draw_bulk_orders_pull_down_menu_min("product_name_".$i,$this->arrInventory,$modelDefault);
//			if( count($this->arrProducts) > 30){
//				$product_name = my_draw_bulk_orders_pull_down_menu_min("product_name_".$i,$this->arrInventory,$modelDefault);
//			}else{
//				$product_name = my_draw_bulk_orders_pull_down_menu("product_name_".$i,$this->arrInventory,$modelDefault);
//			}

			 $productRow .=
			    sprintf("\t<tr class=\"tableRowColorLtYellow\">\n".
				" \t\t<td align=left class=\"mediumBoldText\">&nbsp;&nbsp;%d</td>\n".
				" \t\t<td>%s</td>\n".
				" \t\t<td>%s &nbsp;&nbsp;&nbsp;</td>\n\t</tr>\n".
				" \t<tr class=\"tableRowColor\">\n".
				" \t\t<td align=center class=\"mediumBoldText\">\n".
				" \t\t\t<input type=\"text\" name=\"product_quantity_%d\" size=2 ".
				" value=\"%d\"/></td>\n".
				" \t\t<td>%s</td>\n".
				" \t\t<td>%s</td>\n\t</tr>\n",$this->arrProducts[$i]->getQuantity(),
				$this->arrProducts[$i]->getSize(),
				$this->arrProducts[$i]->getModel(),$i,
				$this->arrProducts[$i]->getQuantity(),
				$product_sizes, $product_name);

		}//end of for loop
		$this->productRows = $productRow;
	  	//echo "Row Count:  ".count($this->order->getProducts())."<br>";
	}


	function getInventoryOptions(){

	    $ord_inventory_sql = "SELECT * FROM products WHERE product_enabled = 1 ORDER BY product_model";
	    $ord_inventory_query = my_db_query($ord_inventory_sql);
	    $this->arrInventory[] = array('id' => 0,'text' => "");//blank first selection
	    while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
	        $productText =$ord_inventory['product_model']." / ".
	        substr($ord_inventory['product_name'], 0, 20)." / ".
	        substr($ord_inventory['product_desc'], 0, 15);
	        $this->arrInventory[] = array('id' => $ord_inventory['product_id'],
	                          'text' => $productText);
	    }

	}//end of function/method


	function getShippingOptions(){
	    $ord_shipping_sql = "SELECT * FROM shipping WHERE 1 ORDER BY shipping_name";
	    $ord_shipping_query = my_db_query($ord_shipping_sql);
	    while($ord_shipping = my_db_fetch_array($ord_shipping_query)){
	        $this->arrShipping[] = array('id' => $ord_shipping['shipping_id'],
	                          'text' => $ord_shipping['shipping_name']);
	    }
	}

	function getSizeOptions($currSize, $currProdModel){
		$this->arrSizes = "";

	    $this->arrSizes[] = array('id' => 0,'text' => "");//blank first selection
		$tmpArrSizes = getProductSizeArray($currProdModel, false);
		for($i=0;  $i<count($tmpArrSizes); $i++){
			$this->arrSizes[] = $tmpArrSizes[$i];
		}
	}


}//end of class
?>