<?php


  function getAllProductSizesArray($product_code, $boolReturnJSON=false){

	$category = substr(trim($product_code), 0, 3);
	
	$available_sizes_sql = "SELECT product_avail_sizes FROM products p where product_model = '".$product_code."'";
	$available_sizes_query = my_db_query($available_sizes_sql);	
	$available_sizes_rs = my_db_fetch_array($available_sizes_query);
	$subject = $available_sizes_rs['product_avail_sizes'];
	$search = array('[',']');
	$replace = array('','');
	$temp = str_replace($search, $replace, $subject);
	$availSizesArray = explode("#", $temp);
	$junk = array();
	$junk2 = array();
	for($i=0; $i<count($availSizesArray); $i++){
		$junk = explode(",",$availSizesArray[$i]);
		$junk2[] = $junk[0];
	}

	
	
	$sizes_sql ="SELECT s.cat_sizes_name as name, 
	s.cat_sizes_id as id,
	c.categories_id AS cat_id,
	c.categories_size_price as price
	FROM categories c, cat_sizes s 
	WHERE c.categories_size_id=s.cat_sizes_id AND c.categories_code = '".$category."' 
	ORDER BY s.cat_sizes_sort";
	$sizes_query = my_db_query($sizes_sql);	
   	
	$results = null;

  	if($boolReturnJSON){
  		//Used for the Order page and the Ajax call
  		$jsonResults = "{arrSizes:[";
		while($sizes_rs = my_db_fetch_array($sizes_query)){
			$isChecked = ( in_array($sizes_rs['id'], $junk2) )? 1 : 0 ;
			$jsonResults .= "{id:\"".$sizes_rs['id']."\",isChecked:\"".$isChecked."\",name:\"".$sizes_rs['name']."\",price:\"".$sizes_rs['price']."\", cat_id:\"".$sizes_rs['cat_id']."\"},";
		}	
		$jsonResults = substr($jsonResults, 0, strlen($jsonResults)-1);
	  	$jsonResults .= "]}";
	  	$results = $jsonResults;
  	}else{
	  	$arrResults = array();
		while($sizes_rs = my_db_fetch_array($sizes_query)){
			$varSize = $category . " - " . $sizes_rs['name'];
			$arrResults[] = array( 'id' => $varSize,
								   'text' => $varSize);			
		}	
	  	$results = $arrResults;
  	}
//echo $sizes_sql ."<br><br>";
  	return $results;
   }

   
   
  function getProductSizeArray($product_code, $boolReturnJSON=false, $returnAll=false){

	$category = substr(trim($product_code), 0, 3);
  	$sizes_sql = "SELECT pc.customized_price as price, cs.cat_sizes_name as name, 
				cs.cat_sizes_id as id, c.categories_id AS cat_id
				FROM `products_customized` pc, `categories` c, `products` p, `cat_sizes` cs
				WHERE pc.categories_id = c.categories_id
				AND pc.product_id = p.product_id
				AND c.categories_size_id = cs.cat_sizes_id
				AND p.product_model = '".strtoupper($product_code)."' 
				ORDER BY cs.cat_sizes_sort";
  	  	
	$sizes_query = my_db_query($sizes_sql);	
	
  	if(my_db_num_rows($sizes_query) == 0){
		$limitToStandardSizes = ($returnAll)? " " : " AND c.categories_std_size=1 "; 
	  	$sizes_sql ="SELECT s.cat_sizes_name as name, 
	  	s.cat_sizes_id as id,
		c.categories_id AS cat_id,
	  	c.categories_size_price as price
	  	FROM categories c, cat_sizes s 
	  	WHERE c.categories_size_id=s.cat_sizes_id AND c.categories_code = '".$category."' "
		. $limitToStandardSizes . "ORDER BY s.cat_sizes_sort";
		$sizes_query = my_db_query($sizes_sql);	
  	}
  	
	$results = null;

  	if($boolReturnJSON){
  		//Used for the Order page and the Ajax call
  		$jsonResults = "{arrSizes:[";
		while($sizes_rs = my_db_fetch_array($sizes_query)){
			$jsonResults .= "{id:\"".$sizes_rs['id']."\",name:\"".$sizes_rs['name']."\",price:\"".$sizes_rs['price']."\", cat_id:\"".$sizes_rs['cat_id']."\"},";
		}	
		$jsonResults = substr($jsonResults, 0, strlen($jsonResults)-1);
	  	$jsonResults .= "]}";
	  	$results = $jsonResults;
  	}else{
	  	$arrResults = array();
		while($sizes_rs = my_db_fetch_array($sizes_query)){
			$varSize = $category . " - " . $sizes_rs['name'];
			$arrResults[] = array( 'id' => $varSize,
								   'text' => $varSize);			
		}	
	  	$results = $arrResults;
  	}
//echo $sizes_sql ."<br><br>";
  	return $results;
   }
  
  function getProductSizeArray2($product_code){


	$arrSizesNameToID = array(
		"NA"=>12,
		"3T"=>2,
		"4T"=>3,
		"5T"=>4,
		"SM"=>5,
		"MD"=>6,
		"LG"=>7,
		"XL"=>8,
		"2X"=>9,
		"3X"=>10,
		"4X"=>11
		);


	$product_code = trim($product_code);
  	$sizes_sql = "SELECT products2_size FROM `products2` WHERE `products2_model` = '".$product_code."' ORDER BY products2_size_sort";
  	$sizes_query = my_db_query($sizes_sql);

	$jsonResults = "{arrSizes:[";
	while($sizes_rs = my_db_fetch_array($sizes_query)){
		$size_index = $arrSizesNameToID[$sizes_rs['products2_size']];
		$jsonResults .= "{id:\"".$size_index."\",name:\"".$sizes_rs['products2_size']."\"},";
	}	
	$jsonResults = substr($jsonResults, 0, strlen($jsonResults)-1);
  	$jsonResults .= "]}";

  	return $jsonResults;
  	
   }
  
  //Used on the inventory page to display the sizes and prices associated with each product
  function getSizeAndPrices($product_code){
	global $displaySizesSubset;
  	$sizes_sql = "SELECT pc.customized_price as price, cs.cat_sizes_name as name, c.categories_std_size as std 
				FROM `products_customized` pc, `categories` c, `products` p, `cat_sizes` cs
				WHERE pc.categories_id = c.categories_id
				AND pc.product_id = p.product_id
				AND c.categories_size_id = cs.cat_sizes_id
				AND p.product_model = '".strtoupper($product_code)."' 
				ORDER BY cs.cat_sizes_sort";
/*	
	//If the product has specific sizes, then get them from the table	
 	$product_sizes_sql = "SELECT `product_avail_sizes` FROM `products` p
				WHERE p.product_model = '".strtoupper($product_code)."' and  `product_avail_sizes` != ''";
	$product_sizes_query = my_db_query($product_sizes_sql);
	$product_sizes_arr = array();
	
	
	if( my_db_num_rows($product_sizes_query) > 0 ){
		$product_sizes_rs = my_db_fetch_array($product_sizes_query);
		$product_sizes_arr = explode(",", $product_sizes_rs['product_avail_sizes']) ;
		$psCount = 0;

		foreach ($product_sizes_arr as $ps) {
				$tempArr = explode(" - ", $ps);
				$product_sizes_arr[$psCount] = $tempArr[1];
				$psCount++;				
		}
	}
*/				
  	$results = "";
	$sizes_query = my_db_query($sizes_sql);
//	$displaySizesSubset = ( count($product_sizes_arr) )? 1 : 0;
	
	
	if(my_db_num_rows($sizes_query) > 0){
//	print(count($product_sizes_arr));
//	print("<br>");
		
		while($sizes_rs = my_db_fetch_array($sizes_query)){			
			if( $displaySizesSubset ){
				if( in_array($sizes_rs['name'], $product_sizes_arr) ){
					$results .= $sizes_rs['name']."=".$sizes_rs['price'].", ";
				}
			}else{
				$results .= $sizes_rs['name']."=".$sizes_rs['price'].", ";
			}			
		}
		$results = "<span style=\"color:#FF0000\">".substr($results, 0, strlen($results)-2)."</span>";
	}else{
	  	$category = trim(substr($product_code, 0, 3));
	  	
		$sizes_sql = "SELECT c.categories_size_price AS price, cs.cat_sizes_name AS name, c.categories_std_size as std 
					  FROM `categories` c, `cat_sizes` cs
					  WHERE c.categories_size_id = cs.cat_sizes_id
					  AND c.categories_code = '".strtoupper($category)."'
					  ORDER BY cs.cat_sizes_sort";
	
	  	$sizes_query = my_db_query($sizes_sql);
	
	  	while($sizes_rs = my_db_fetch_array($sizes_query)){
	    	$isStdBegin = ($sizes_rs['std'] == 0)? "<span class=\"nonStdSize\">" : "" ;
	    	$isStdEnd = ($sizes_rs['std'] == 0)? "</span>" : "" ;
	    	
	  		$results .= $isStdBegin.$sizes_rs['name']."=".$sizes_rs['price'].$isStdEnd.", ";
		}
		$results = substr($results, 0, strlen($results)-2);
	}	
	
  	return $results;
  }
  
  	//Used on the Process Orders page to pre-fill the price field on the Show Order page
    function getPriceBySize($custNo, $product_code, $generic_size){	  	
		    	
    	$price_query = _getAbsolutePrice($product_code, $generic_size);
//echo "Generic Size: ".$generic_size."<br>";
    	if(my_db_num_rows($price_query) > 0 ){
			$price_rs = my_db_fetch_array($price_query);
       	} else{
//       		echo "_getAbsolutePrice: ".my_db_num_rows($price_query)."<br>";
	       	$price_query = _getCustomerPrice($custNo, $product_code, $generic_size);
	       	if(my_db_num_rows($price_query) > 0 ){
//       			echo "_getCustomerPrice: ".my_db_num_rows($price_query)."<br>";
	       		$price_rs = my_db_fetch_array($price_query);
	       	}else{
//       			echo "_getCustomerPrice: ".my_db_num_rows($price_query)."<br>";
	    		$price_query = _getPriceBySizeFromCustomizedProds($product_code, $generic_size);
		    	if(my_db_num_rows($price_query) > 0){
		    		$price_rs = my_db_fetch_array($price_query);
		    	}else{
//       				echo "_getPriceBySizeFromCustomizedProds: ".my_db_num_rows($price_query)."<br>";
		    		$price_query = _getPriceBySizeFromCategories($product_code, $generic_size);
			    	if(my_db_num_rows($price_query) > 0){
			    		$price_rs = my_db_fetch_array($price_query);
			    	}else{
//       					echo "_getPriceBySizeFromCategories: ".my_db_num_rows($price_query)."<br>";
			    	}
		    	}
    		}
       	}
	$results = $price_rs['price'];
	return $results;
       	
	}
  
	function _getCustomerPrice($custNo, $productCode, $generic_size){
		
		$catCode = trim(substr($productCode, 0, 3));
		
//    echo "<br> In _getCustomerPrice <br>";
		$cust_price_sql = "select pc.customer_price as price
		from products_customer_prices pc, categories c, accounts a, cat_sizes cs  
		where pc.categories_id=c.categories_id and  
		pc.accounts_id=a.accounts_id and 
		c.categories_size_id = cs.cat_sizes_id and  
		cs.cat_sizes_name = '".$generic_size."' and 
		c.categories_code = '".$catCode."' and 
		a.accounts_number = '".$custNo."'";
		
//		echo $cust_price_sql ."<br>";
		
		return my_db_query($cust_price_sql);
	}
	
	function _getAbsolutePrice($product_code, $generic_size){			
//    echo "<br> In _getAbsolutePrice <br>";
		return _getPriceBySizeFromCustomizedProds($product_code, $generic_size, 1);
	}
  
  
	function _getPriceBySizeFromCustomizedProds($product_code, $generic_size, $isAbsolute = 0){

//    echo "<br> In _getPriceBySizeFromCustomizedProds <br>";
		$price_sql = "SELECT pc.customized_price AS price
					FROM `products_customized` pc, `categories` c, `products` p, `cat_sizes` cs
					WHERE pc.categories_id = c.categories_id
					AND pc.product_id = p.product_id
					AND c.categories_size_id = cs.cat_sizes_id
					AND p.product_model = '".strtoupper(trim($product_code))."' 
					AND cs.cat_sizes_name = '".strtoupper(trim($generic_size))."' 
					AND pc.absolute_price = ".$isAbsolute;
		$price_query = my_db_query($price_sql);
//    	echo $price_sql;						
		
		return $price_query;
	}
  
  
	function _getPriceBySizeFromCategories($product_code, $generic_size){
//    echo "<br> In _getPriceBySizeFromCategories <br>";
		$category = trim(substr($product_code, 0, 3));
		  	
			$price_sql = "SELECT c.categories_size_price AS price, cs.cat_sizes_name AS name
						  FROM `categories` c, `cat_sizes` cs
						  WHERE c.categories_size_id = cs.cat_sizes_id
						  AND c.categories_code = '".strtoupper($category)."' 
						  AND cs.cat_sizes_name = '".strtoupper(trim($generic_size))."'";
		
			$price_query = my_db_query($price_sql);
				
		return $price_query;
	}
  

	function getProductsArray(){
		$ord_inventory_sql = "SELECT * FROM products WHERE product_enabled = 1 ORDER BY product_model";
		$ord_inventory_query = my_db_query($ord_inventory_sql);
		$arrInventory[] = array('id' => '0','text' => 'Select Product');
		while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
			$productText =$ord_inventory['product_model']." / ".
			stripslashes(substr($ord_inventory['product_name'], 0, 25))." / ".
			stripslashes(substr($ord_inventory['product_desc'], 0, 15));
			$arrInventory[] = array('id' => $ord_inventory['product_id'],
								    'text' => $productText);
		}
		
		return $arrInventory;
	}
	
	function getProductsJson(){
		$ord_inventory_sql = "SELECT * FROM products WHERE product_enabled = 1 ORDER BY product_model";
		$ord_inventory_query = my_db_query($ord_inventory_sql);
		$jsonResults = "{\"arrProducts\":[";
		while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
			$productText =$ord_inventory['product_model']." / ".
			stripslashes(str_replace("\"","",substr($ord_inventory['product_name'], 0, 25)))." / ".
			stripslashes(substr($ord_inventory['product_desc'], 0, 15));
			$jsonResults .= "{\"id\":\"".$ord_inventory['product_id']."\",\"text\":\"".$productText."\"},";
		}
		$jsonResults = substr($jsonResults, 0,strlen($jsonResults)-1);//strip off last comma
		$jsonResults .= "]}";
		
		return $jsonResults;
	}
	
	
  
  
	function getCustomizedProductsQuery(){
		$cust_products_sql = "SELECT * FROM products_customized WHERE 1 ";
		$cust_products_query = my_db_query($cust_products_sql);
		
		return $cust_products_query;
	}
  
	function getCustomizedProductsDistinctQuery(){
		$cust_products_sql = "SELECT DISTINCT pc.product_id FROM products_customized pc, products p WHERE pc.product_id = p.product_id";
		$cust_products_query = my_db_query($cust_products_sql);
		
		return $cust_products_query;
	}
  
	function getCustomizedSizesAndPrices($pId) {
		$cust_prods_sql = "SELECT pc.customized_price AS price, 
					  cs.cat_sizes_name AS name, pc.absolute_price AS isAbsolute  
					  FROM `categories` c, `cat_sizes` cs, `products_customized` pc 
					  WHERE pc.categories_id=c.categories_id 
					  AND pc.product_id =$pId 
					  AND  c.categories_size_id = cs.cat_sizes_id
					  ORDER BY cs.cat_sizes_sort";
	
	  	$cust_prods_query = my_db_query($cust_prods_sql);
		$results = "";
		$boldTextStart = "";
		$boldTextEnd = ""; 
	  	while($cust_prods_rs = my_db_fetch_array($cust_prods_query)){
	  		if( $cust_prods_rs['isAbsolute'] == 1 ){
				$boldTextStart = "<strong>";
				$boldTextEnd = "</strong>"; 
	  		}else{
				$boldTextStart = "";
				$boldTextEnd = ""; 
	  		}
	    	
	  		$results .= $boldTextStart.$cust_prods_rs['name']."=".$cust_prods_rs['price'].$boldTextEnd.", ";
		}
		$results = substr($results, 0, strlen($results)-2);
		
		return $results;
	}
	
	function getCustomizedProductSizesPricesQuery($pId){
		$category_sql = "SELECT product_model FROM products where product_id = ".$pId;
		$category_query = my_db_query($category_sql);
		$category_rs = my_db_fetch_array($category_query);
		$category = substr($category_rs['product_model'], 0, 3);
		
		$cat_sizes_sql = "SELECT c.categories_id AS cat_id, 
		cs.cat_sizes_name as size_name 
		FROM `categories` c, `cat_sizes` cs 
		WHERE c.categories_code = '$category' 
		AND c.categories_size_id=cs.cat_sizes_id 
		ORDER BY cs.cat_sizes_sort";
		
		$cat_sizes_query = my_db_query($cat_sizes_sql);
		
		return 	$cat_sizes_query;	
	}
	
	function getProductNameById($pId, $includeProductCode = false){

		$prods_sql = "SELECT product_model AS code, product_name AS name FROM products WHERE product_id =$pId";
		$prods_query = my_db_query($prods_sql);
		$prods = my_db_fetch_array($prods_query);
		
		return ($includeProductCode)? "[".$prods['code']."] ".$prods['name'] : $prods['name'];
	}
	
	function getProductsArrayLessCustomized(){
		$customized_products_sql = "SELECT DISTINCT product_id FROM products_customized";
		
		$ord_inventory_sql = "SELECT * FROM products WHERE product_enabled = 1 
		AND product_id not in (".$customized_products_sql.") 
		ORDER BY product_model";
		$ord_inventory_query = my_db_query($ord_inventory_sql);
		$arrInventory[] = array('id' => '0','text' => 'Select Product');
		while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
			$productText =$ord_inventory['product_model']." / ".
			stripslashes(substr($ord_inventory['product_name'], 0, 25))." / ".
			stripslashes(substr($ord_inventory['product_desc'], 0, 15));
			$arrInventory[] = array('id' => $ord_inventory['product_id'],
								    'text' => $productText);
		}
		
		return $arrInventory;
	}
	
	function getCustomizedProductArray($pId){
		$cust_prod_sql = "SELECT categories_id, customized_price FROM products_customized WHERE product_id =".$pId;
		$cust_prod_query = my_db_query($cust_prod_sql);
		$arrCustProd = array();
		while($cust_prod = my_db_fetch_array($cust_prod_query)){
			$arrCustProd[$cust_prod['categories_id']] = $cust_prod['customized_price'];
		}
		
		return $arrCustProd;
	}
  
	function getAbsolutePricesArray($pId){
		$cust_prod_sql = "SELECT categories_id, absolute_price FROM products_customized WHERE product_id =".$pId;
		$cust_prod_query = my_db_query($cust_prod_sql);
		$arrCustProd = array();
		while($cust_prod = my_db_fetch_array($cust_prod_query)){
			$arrCustProd[$cust_prod['categories_id']] = $cust_prod['absolute_price'];
		}
		
		return $arrCustProd;
	}
	
	function getAccountsArray(){
		
		$accounts_sql = "SELECT accounts_id, accounts_company_name FROM accounts WHERE 1 ORDER BY accounts_id";
		$accounts_query = my_db_query($accounts_sql);
		$arrAccounts[] = array('id' => '0','text' => 'Select Customer');
		while($accounts = my_db_fetch_array($accounts_query)){
			$arrAccounts[] = array('id' => $accounts['accounts_id'],
								    'text' => stripslashes($accounts['accounts_company_name']));
		}
		
		return $arrAccounts;
	}
	
	
	function getCategoriesArray(){
		
		$accounts_sql = "SELECT DISTINCT categories_name FROM categories WHERE 1 ORDER BY categories_name";
		$accounts_query = my_db_query($accounts_sql);
		$arrAccounts[] = array('id' => '0','text' => 'Select Category');
		while($accounts = my_db_fetch_array($accounts_query)){
			$arrAccounts[] = array('id' => $accounts['categories_name'],
								    'text' => stripslashes($accounts['categories_name']));
		}
		
		return $arrAccounts;
	}
	
	function getCategorySizesArray($categoryName){
		
		$arrSizes[] = array('id' => 0,'text' => "");//blank first selection
		$sizes_query = _getCategorySizesSQL($categoryName);
		
		while($sizes = my_db_fetch_array($sizes_query)){

		}
		
		return $arrSizes;
	}
	
	function getCategorySizesJson($categoryName){
				
		$sizes_query = _getCategorySizesSQL($categoryName);
		
		$jsonResults = "{arrSizes:[";
		while($sizes = my_db_fetch_array($sizes_query)){
			$jsonResults .= "{id:\"".$sizes['id']."\",size:\"".$sizes['Name']."\",sizeId:\"".$sizes['sId']."\",code:\"".$sizes['Code']."\",price:\"".$sizes['Price']."\"},";
		}
		$jsonResults .= "]}";
		
		return $jsonResults;
	}
	
	function _getCategorySizesSQL($categoryName){
		
		$sizes_sql = "SELECT c.categories_id AS id, c.categories_size_price AS Price, 
					c.categories_code AS Code, cs.cat_sizes_name AS Name, c.categories_size_id AS sId  
					FROM categories c, cat_sizes cs
					WHERE c.categories_size_id=cs.cat_sizes_id AND c.categories_name = '".$categoryName."'
					ORDER BY cs.cat_sizes_sort";
		
		return my_db_query($sizes_sql);
	}
	
	function getQuantityOnHand($size, $product_model){
		
		$onhand_sql = "SELECT product_quantity FROM products_onhand WHERE product_model='".$product_model."' AND product_size='".$size."'";

		$onhand_query = my_db_query($onhand_sql);
		
		if( my_db_num_rows($onhand_query) == 1){
			$onhand = my_db_fetch_array($onhand_query);
			$quantity_onhand = $onhand['product_quantity'];	
		}else{
			$quantity_onhand = 0;	
		}

		return $quantity_onhand;
	}
	
	function getQuantityOnHand2($size, $product_model){
		
		$onhand_sql = "SELECT products2_onhand FROM products2 WHERE products2_model='".$product_model."' AND products2_size='".$size."'";
		
		$onhand_query = my_db_query($onhand_sql);
		
		if( my_db_num_rows($onhand_query) == 1){
			$onhand = my_db_fetch_array($onhand_query);
			$quantity_onhand = $onhand['products2_onhand'];	
		}else{
			$quantity_onhand = 0;	
		}

		return $quantity_onhand;
	}
	
	function getPriceLevelsSelectionOptions( $selectName, $defaultPriceLevel = "" ){

		$price_levels_sql = "SELECT price_level_id, price_level FROM price_levels ORDER BY price_level ASC";
	    $price_levels_query = my_db_query($price_levels_sql);

	    $htmlOutput = "<select name='$selectName' class='price_level'><option value='0'>Custom</option>";
	    while($price_levels_view = my_db_fetch_array($price_levels_query)){
    		$default = ( $defaultPriceLevel == $price_levels_view['price_level'] )? "SELECTED" : "";
        	$htmlOutput = $htmlOutput . "<option value=" . $price_levels_view['price_level_id'] . " $default>" . $price_levels_view['price_level'] . "</option>";
    	}
    	$htmlOutput = $htmlOutput . "</select>";

    	return $htmlOutput;
	}

/*
	function getCustomerPricesByPriceLevel($priceLevel){
		$sql = "SELECT * FROM price_level_prices plp, price_levels pl WHERE plp.price_level_id = " . $priceLevel;
		//$sql = "SELECT * FROM price_level_prices plp, price_levels pl WHERE plp.price_level_id = pl.price_level_id AND pl.price_level = " . $priceLevel;

		$query = my_db_query($sql);
		$priceMapping = array();
		while( $resultSet = my_db_fetch_array($query) ){
			$priceMapping[$resultSet["category"] . "_" . $resultSet["group_id"] ]  = $resultSet["price"];
		}

		return $priceMapping;
	}
*/

	function getProductGroupsSelectionOptions( $selectName, $defaultProductGroup = "" ){

		$sql = "SELECT DISTINCT group_title, group_id FROM price_level_prices ORDER BY group_id";
	    $query = my_db_query($sql);

	    $htmlOutput = "<select name='$selectName' class='product_groups'>";
	    while($resultSet = my_db_fetch_array($query)){
    		$default = ( $defaultProductGroup == $resultSet['group_id'] )? "SELECTED" : "";
        	$htmlOutput = $htmlOutput . "<option value=" . $resultSet['group_id'] . " $default>" . $resultSet['group_title'] . "</option>";
    	}
    	$htmlOutput = $htmlOutput . "</select>";

    	return $htmlOutput;
	}

	function getPriceLevelDiscount($accountNumber){
		$arrAccountInfo = getAccountInfoByAcctNumber($accountNumber); 
		$priceLevel = $arrAccountInfo["accounts_price_level"];		
		$sql = "SELECT *  FROM `price_levels` WHERE `price_level` = " . $priceLevel;
		$query = my_db_query($sql);
		$resultSet = my_db_fetch_array($query);
		return $resultSet["price_level_discount"]/100;		
	}

	function getProductMSRP( $productModel ){
		$sql = "SELECT products2_price  FROM `products2` WHERE `products2_model` LIKE  '" . $productModel . "'";
		$query = my_db_query($sql);
		$resultSet = my_db_fetch_array($query);
		return $resultSet["products2_price"];		
	}
/*
	function getCustomerPrices( $accountNumber, $productModel ){
		//$arrCustomerPrices = getPriceFromPriceLevelDiscount( $accountNumber );
		
		//Price Mapping works like this: "category_groupId" -> price
		//$category = substr($productModel, 0, 3); 
		//$price = $arrCustomerPrices[$category . "_" . $group_id];
		$MSRP = getProductMSRP( $productModel );
		$discount = getPriceLevelDiscount($accountNumber)/100;
		$price = $MSRP * $discount;

		//Upcharges for big sizes
		if( $category == "APT" && ($size == "2X" || $size == "3X" || $size == "4X" ) ){
			if( $size == "2X" ){
				$price = $price + 1;
			}elseif( $size == "3X"){
				$price = $price + 1.50;
			}elseif( $size == "4X"){
				$price = $price + 2;				
			}
		}elseif( $category == "CGA" && $size == "2X" ){
			$price = $price + 1;
		}elseif( $category == "HPS" && $size == "2X" ){
			$price = $price + 2;
		}

		return  $price;
	}
*/
	function getPriceFromModelSize( $model, $size, $percent_discount ){
		$sql = "SELECT  products2_price FROM products2 where products2_model = '" . $model . "' AND products2_size = '". $size ."' ";
	    $query = my_db_query($sql);
	    $resultSet = my_db_fetch_array($query);
	    $newPrice = $resultSet["products2_price"] * (1 - $percent_discount);
	    $newPrice = ceil($newPrice * 100)/100;//ceil function only acts on whole numbers
	    
	    return number_format($newPrice, 2);
	}


	function getMSRPPriceFromModelSize( $model, $size, $percent_discount ){
		$sql = "SELECT  products2_price FROM products2 where products2_model = '" . $model . "' AND products2_size = '". $size ."' ";
	    $query = my_db_query($sql);
	    $resultSet = my_db_fetch_array($query);

	    return  $resultSet["products2_price"];
	}


	function updateIfBulkOrder( $ord_add_insert_id ){
		$isBulkOrder = false;
		$sql = "SELECT * FROM orders_products WHERE order_id = " . $ord_add_insert_id;
		$query = my_db_query($sql);
		$quantity = 0;
		while($rs = my_db_fetch_array($query)){
			$quantity = $quantity + $rs["order_product_quantity"];
		}
		$shirtCount = 0;
		$arrShirtCategories = array("APT", "YTC", "TTS", "KDZ", "PMT", "SJT", "CGA", "CGK");
		$arrShirtCategoriesPremium = array("SJT", "CGA", "CGK");
		$arr2XShirts = array();
		$arr3XShirts = array();
		$arr4XShirts = array();
		$arrCGAShirts = array();
		$arrCGKShirts = array();
		$arrSJTShirts = array();

		if( $quantity < 24 ){

			$isBulkOrder = false;
		}else{
			$sql = "SELECT * FROM orders_products WHERE order_id = " . $ord_add_insert_id;
			$query = my_db_query($sql);
			while($rs = my_db_fetch_array($query)){
			
				if( $rs["order_product_size"] != "NA" ){
					$arrCategorySize = explode(" - ", $rs["order_product_size"]);
					$category = $arrCategorySize[0];
					$size = $arrCategorySize[1];

					if( in_array($category, $arrShirtCategories) ){
						$shirtCount =  $shirtCount + $rs["order_product_quantity"];
					}

					if( $size == "2X" ){
						$arr2XShirts[] =  $rs["order_product_id"];
					}

					if( $size == "3X" ){
						$arr3XShirts[] =  $rs["order_product_id"];
					}

					if( $size == "4X" ){
						$arr4XShirts[] =  $rs["order_product_id"];
					}

					if( $category == "CGA" ){
						$arrCGAShirts[] =  $rs["order_product_id"];
					}

					if( $category == "CGK" ){
						$arrCGKShirts[] =  $rs["order_product_id"];
					}

					if( $category == "SJT" ){
						$arrSJTShirts[] =  $rs["order_product_id"];
					}
				}
			}		
		}
	
		$isBulkOrder = ($shirtCount >= 24)? true : false;

		if ( $isBulkOrder ){
			$sql = "UPDATE orders_products SET order_product_charge = 8.00";
			my_db_query($sql);

			for($i=0; $i < count($arrSJTShirts); $i++){
				$sql = "SELECT * FROM orders_products where order_product_id =".$arrSJTShirts[$i];
				$query = my_db_query($sql);
				$price = 8;
				while($rs = my_db_fetch_array($query)){
					$price = $rs["order_product_charge"];
					$price = number_format($price + 1, 2);
					$sql = "UPDATE orders_products SET order_product_charge = $price WHERE order_product_id =".$arrSJTShirts[$i];
					my_db_query($sql);
				}
			}

			for($i=0; $i < count($arrCGAShirts); $i++){
				$sql = "SELECT * FROM orders_products where order_product_id =".$arrCGAShirts[$i];
				$query = my_db_query($sql);
				$price = 8;
				while($rs = my_db_fetch_array($query)){
					$price = $rs["order_product_charge"];
					$price = number_format($price + 1, 2);
					$sql = "UPDATE orders_products SET order_product_charge = $price WHERE order_product_id =".$arrCGAShirts[$i];
					my_db_query($sql);
				}
			}

			for($i=0; $i < count($arrCGKShirts); $i++){
				$sql = "SELECT * FROM orders_products where order_product_id =".$arrCGKShirts[$i];
				$query = my_db_query($sql);
				$price = 8;
				while($rs = my_db_fetch_array($query)){
					$price = $rs["order_product_charge"];
					$price = number_format($price + 1, 2);
					$sql = "UPDATE orders_products SET order_product_charge = $price WHERE order_product_id =".$arrCGKShirts[$i];
					my_db_query($sql);
				}
			}

			for($i=0; $i < count($arr2XShirts); $i++){
				$sql = "SELECT * FROM orders_products where order_product_id =".$arr2XShirts[$i];
				$query = my_db_query($sql);
				$price = 8;
				while($rs = my_db_fetch_array($query)){
					$price = $rs["order_product_charge"];
					$arrTemp  = explode(" - ", $rs["order_product_size"]);
					$category = $arrTemp[0];
					if( in_array($category, $arrShirtCategoriesPremium )){
						$price = $price + 1;
					}
					$price = number_format($price + 1, 2);
					$sql = "UPDATE orders_products SET order_product_charge = $price WHERE order_product_id =".$arr2XShirts[$i];
					my_db_query($sql);
				}
			}

			for($i=0; $i < count($arr3XShirts); $i++){
				$sql = "SELECT * FROM orders_products where order_product_id =".$arr3XShirts[$i];
				$query = my_db_query($sql);
				$price = 10;
				while($rs = my_db_fetch_array($query)){
					$price = $rs["order_product_charge"];
					$arrTemp  = explode(" - ", $rs["order_product_size"]);
					$category = $arrTemp[0];
					if( in_array($category, $arrShirtCategoriesPremium )){
						$price = $price + 1;
					}
					$price = number_format($price + 1.50, 2);
					$sql = "UPDATE orders_products SET order_product_charge = $price WHERE order_product_id =".$arr3XShirts[$i];
					my_db_query($sql);
				}
			}

			for($i=0; $i < count($arr4XShirts); $i++){
				$sql = "SELECT * FROM orders_products where order_product_id =".$arr4XShirts[$i];
				$query = my_db_query($sql);
				while($rs = my_db_fetch_array($query)){
					$price = $rs["order_product_charge"];
					$arrTemp  = explode(" - ", $rs["order_product_size"]);
					$category = $arrTemp[0];
					if( in_array($category, $arrShirtCategoriesPremium )){
						$price = $price + 1;
					}
					$price = number_format($price + 3, 2);
					$sql = "UPDATE orders_products SET order_product_charge = $price WHERE order_product_id =".$arr4XShirts[$i];
					my_db_query($sql);
				}
			}
			echo "Updated order for bulk pricing. <br>";
		}
		return;
	}

	function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (strtolower(substr($haystack, 0, $length)) === strtolower($needle));
	}

	function exactMatch($haystack, $needle) {
		return strtolower($haystack) === strtolower($needle);
	}

	function applyFixedPrice($rs, $price) {
		$arrRetVal["price"] = $rs["discount_value"];
		$arrRetVal["msg"] = "Discounted Price";

		return $arrRetVal;
	}

	function applyPercentOff($rs, $price) {
		$temp = (100 - intval($rs["discount_value"]))/100;
		$arrRetVal["price"] = money_format('%i', intval($price) * $temp);
		$arrRetVal["msg"] = intval($rs["discount_value"]) . "% off";

		return $arrRetVal;
	}


	function applyDiscount($rs, $price, $productModel) {
		$arrRetVal["price"] = $price;

		if ($rs["apply_method"] === "STARTS-WITH") {
			if (startsWith($productModel, $rs["discount_pattern"])) {
				if ($rs["discount_type"] === "AMOUNT") {
					$arrRetVal = applyFixedPrice($rs, $price);
				} elseif ($rs["discount_type"] === "PERCENT") {
					$arrRetVal = applyPercentOff($rs, $price);
				}
			}
		} elseif ($rs["apply_method"] === "EXACT-MATCH") {
			if (exactMatch($productModel, $rs["discount_pattern"])) {
				if ($rs["discount_type"] === "AMOUNT") {
					$arrRetVal = applyFixedPrice($rs, $price);
				} elseif ($rs["discount_type"] === "PERCENT") {
					$arrRetVal = applyPercentOff($rs, $price);
				}
			}
		}

		return $arrRetVal;
	}

	function checkForDiscount($arrRequest) {
		$arrRetVal = array();
		$arrRetVal["price"] = $arrRequest["price"];
		$junk = "";

		$sql = "SELECT * FROM discounts WHERE 1 ORDER BY rules_order";
		$query = my_db_query($sql);
		while ($rs = my_db_fetch_array($query)) {
			$arrRetVal["price"] = $arrRequest["price"];

			if ($rs["limit_by"] == "ACCOUNT" && $rs["by_account_number"] == $arrRequest["accounts_number"]) {
				$arrRetVal = applyDiscount($rs, $arrRequest["price"], $arrRequest["productModel"]);
			} elseif ($rs["limit_by"] == "PRICE_LEVEL" && $rs["by_price_level"] == $arrRequest["priceLvl"]) {
				$arrRetVal = applyDiscount($rs, $arrRequest["price"], $arrRequest["productModel"]);
			} elseif ($rs["limit_by"] == "NONE") {
				$arrRetVal = applyDiscount($rs, $arrRequest["price"], $arrRequest["productModel"]);
			}

			if ($arrRetVal["price"] != $arrRequest["price"]) {
				break;
			} else {
				$arrRetVal = array();
			}
		}

		return $arrRetVal;
	}

	function updateRulesOrder($discId, $rulesOrder) {
		$sql = "UPDATE discounts SET rules_order = $rulesOrder where discount_id=".$discId;
		return my_db_query($sql);
	}
	?>