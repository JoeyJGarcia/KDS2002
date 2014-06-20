<?php

$arrTags = array();
$orderCount = -1;
$orderFile;

/*
$arrTags[]['KerussoOrders'] 			= '-1';
$arrTags[]['Order'] 					= '-1';
$arrTags[]['ShippingInfo'] 			= '-1';
$arrTags[]['CustomerName'] 			= '-1';
$arrTags[]['AddressInfo1'] 			= '-1';
$arrTags[]['AddressInfo2'] 			= '-1';
$arrTags[]['City'] 					= '-1';
$arrTags[]['State'] 					= '-1';
$arrTags[]['Zipcode'] 				= '-1';
$arrTags[]['Country'] 				= '-1';
$arrTags[]['ShippingMethod'] 			= '-1';
$arrTags[]['ShippingMethodValue'] 	= '-1';
$arrTags[]['OrderNumber'] 			= '-1';
$arrTags[]['ProductsInfo'] 			= '-1';
$arrTags[]['Product'] 				= '-1';
$arrTags[]['ProductModel'] 			= '-1';
$arrTags[]['ProductQuantity'] 		= '-1';
$arrTags[]['ProductSize'] 			= '-1';
*/


	function startElement($parser, $name, $attrs){
	  global $tag,$inOrderElement,$inShippinInfo,$inProductInfo,$inCustomerName;
	  global $inAddressInfo1,$inAddressInfo2,$inCity,$inState,$inZipCode,$inCountry;
	  global $inShippingMethod,$inShippingMethodValue,$inOrderNumber,$arrTags,$orderCount;
	  
	  $tag = $name;
	  
	  if($name == "KERUSSOORDERS"){
		$arrTags[0]['KerussoOrders'] = '0';
	  }
	  if($name == "ORDER"){
		$inOrderElement = true;
		$orderCount++;
		$arrTags[$orderCount]['KerussoOrders'] 			= '-1';
		$arrTags[$orderCount]['Order'] 					= '-1';
		$arrTags[$orderCount]['ShippingInfo'] 			= '-1';
		$arrTags[$orderCount]['CustomerName'] 			= '-1';
		$arrTags[$orderCount]['AddressInfo1'] 			= '-1';
		$arrTags[$orderCount]['AddressInfo2'] 			= '-1';
		$arrTags[$orderCount]['City'] 					= '-1';
		$arrTags[$orderCount]['State'] 					= '-1';
		$arrTags[$orderCount]['Zipcode'] 				= '-1';
		$arrTags[$orderCount]['Country'] 				= '-1';
		$arrTags[$orderCount]['ShippingMethod'] 			= '-1';
		$arrTags[$orderCount]['ShippingMethodValue'] 	= '-1';
		$arrTags[$orderCount]['OrderNumber'] 			= '-1';
		$arrTags[$orderCount]['ProductsInfo'] 			= '-1';
		$arrTags[$orderCount]['Product'] 				= '-1';
		$arrTags[$orderCount]['ProductModel'] 			= '-1';
		$arrTags[$orderCount]['ProductQuantity'] 		= '-1';
		$arrTags[$orderCount]['ProductSize'] 			= '-1';
		
		$arrTags[$orderCount]['Order'] = '0';
	  }
	  if($name == "SHIPPINGINFO"){
		$inShippinInfo = true;
		$arrTags[$orderCount]['ShippingInfo'] = '0';
	  }
	  if($name == "PRODUCTSINFO"){
		$inProductInfo = true;
		$arrTags[$orderCount]['ProductsInfo'] = '0';
	  }
	  if($name == "CUSTOMERNAME"){
		$inCustomerName = true;
		$arrTags[$orderCount]['CustomerName'] = '0';
	  }
	  if($name == "ADDRESSINFO1"){
		$inAddressInfo1 = true;
		$arrTags[$orderCount]['AddressInfo1'] = '0';
	  }
	  if($name == "ADDRESSINFO2"){
		$inAddressInfo2 = true;
		$arrTags[$orderCount]['AddressInfo2'] = '0';
	  }
	  if($name == "CITY"){
		$inCity = true;
		$arrTags[$orderCount]['City'] = '0';
	  }
	  if($name == "STATE"){
		$inState = true;
		$arrTags[$orderCount]['State'] = '0';
	  }
	  if($name == "ZIPCODE"){
		$inZipCode = true;
		$arrTags[$orderCount]['Zipcode'] = '0';
	  }
	  if($name == "COUNTRY"){
		$inCountry = true;
		$arrTags[$orderCount]['Country'] = '0';
	  }
	  if($name == "SHIPPINGMETHOD"){
		$inShippingMethod = true;
		$arrTags[$orderCount]['ShippingMethod'] = '0';
	  }
	  if($name == "SHIPPINGMETHODVALUE"){
		$inShippingMethodValue = true;
		$arrTags[$orderCount]['ShippingMethodValue'] = '0';
	  }
	  if($name == "ORDERNUMBER"){
		$inOrderNumber = true;
		$arrTags[$orderCount]['OrderNumber'] = '0';
	  }
	  if($name == "PRODUCTSINFO"){
		$inProductInfo = true;
		$arrTags[$orderCount]['ProductsInfo'] = '0';
	  }
	  
	  if($inProductInfo && $name == "PRODUCT"){
	  	//$arrOrders[$orderCount]->setProducts(new Product($attrs['MODEL'],$attrs['QUANTITY'],$attrs['SIZE']));
	  	$arrTags[$orderCount]['Product'] = '1';
 		if(strlen($attrs['MODEL']) > 0) $arrTags[$orderCount]['ProductModel'] = '1';
 		if(strlen($attrs['QUANTITY']) > 0) $arrTags[$orderCount]['ProductQuantity'] = '1';
 		if(strlen($attrs['SIZE']) > 0) $arrTags[$orderCount]['ProductSize'] = '1';
	  }
	}



	function endElement($parser, $name){
	  global $inOrderElement,$inShippinInfo,$inProductInfo,$inCustomerName;
	  global $inAddressInfo1,$inAddressInfo2,$inCity,$inState,$inZipCode,$inCountry;
	  global $inShippingMethod,$inShippingMethodValue,$inOrderNumber,$arrTags,$orderCount;
	
	  if($name == "ORDER"){
		$inOrderElement = false;
		$arrTags[$orderCount]['Order'] = '1';
	  }
	  if($name == "SHIPPINGINFO"){
		$inShippinInfo = false;
		$arrTags[$orderCount]['ShippingInfo'] = '1';
	  }
	  if($name == "PRODUCTSINFO"){
		$inProductInfo = false;
		$arrTags[$orderCount]['ProductsInfo'] = '1';
	  }
	  if($name == "CUSTOMERNAME"){
		$inCustomerName = false;
		$arrTags[$orderCount]['CustomerName'] = '1';
	  }
	  if($name == "ADDRESSINFO1"){
		$inAddressInfo1 = false;
		$arrTags[$orderCount]['AddressInfo1'] = '1';
	  }
	  if($name == "ADDRESSINFO2"){
		$inAddressInfo2 = false;
		$arrTags[$orderCount]['AddressInfo2'] = '1';
	  }
	  if($name == "CITY"){
		$inCity = false;
		$arrTags[$orderCount]['City'] = '1';
	  }
	  if($name == "STATE"){
		$inState = false;
		$arrTags[$orderCount]['State'] = '1';
	  }
	  if($name == "ZIPCODE"){
		$inZipCode = false;
		$arrTags[$orderCount]['Zipcode'] = '1';
	  }
	  if($name == "COUNTRY"){
		$inCountry = false;
		$arrTags[$orderCount]['Country'] = '1';
	  }
	  if($name == "SHIPPINGMETHOD"){
		$inShippingMethod = false;
		$arrTags[$orderCount]['ShippingMethod'] = '1';
	  }
	  if($name == "SHIPPINGMETHODVALUE"){
		$inShippingMethodValue = false;
		$arrTags[$orderCount]['ShippingMethodValue'] = '1';
	  }
	  if($name == "ORDERNUMBER"){
		$inOrderNumber = false;
		$arrTags[$orderCount]['OrderNumber'] = '1';
	  }
	  if($name == "PRODUCTSINFO"){
		$inProductInfo = false;
		$arrTags[$orderCount]['ProductsInfo'] = '1';
	  }
	  
	  if($name == "KERUSSOORDERS"){
		$arrTags[0]['KerussoOrders'] = '1';
	  }
	}




	function characterData($parser, $data) {
	  global $tag,$inOrderElement,$inShippinInfo,$inProductInfo,$inCustomerName;
	  global $inAddressInfo1,$inAddressInfo2,$inCity,$inState,$inZipCode,$inCountry;
	  global $inShippingMethod,$inShippingMethodValue,$inOrderNumber,$arrTags,$orderCount;
	
	/*
	 $data = trim(htmlspecialchars($data));
	    
	  if($tag == "CUSTOMERNAME" && $inCustomerName){
		$arrOrders[$orderCount]->setCustomerName($data);
	  }
	  if($tag == "ADDRESSINFO1" && $inAddressInfo1){
		$arrOrders[$orderCount]->setAddress1($data);
	  }
	  if($tag == "ADDRESSINFO2" && $inAddressInfo2){
		$arrOrders[$orderCount]->setAddress2($data);
	  }
	  if($tag == "CITY" && $inCity){
		$arrOrders[$orderCount]->setCity($data);
	  }
	  if($tag == "STATE" && $inState){
		$arrOrders[$orderCount]->setState($data);
	  }
	  if($tag == "ZIPCODE" && $inZipCode){
		$arrOrders[$orderCount]->setZipcode($data);
	  }
	  if($tag == "COUNTRY" && $inCountry){
		$arrOrders[$orderCount]->setCountry($data);
	  }
	  if($tag == "ORDERNUMBER" && $inOrderNumber){
		$arrOrders[$orderCount]->setOrderNumber($data);
	  }
	  if($tag == "SHIPPINGMETHOD" && $inShippingMethod){
		$arrOrders[$orderCount]->setShippingMethod($data);
	  }
	  if($tag == "SHIPPINGMETHODVALUE" && $inShippingMethodValue){
		$arrOrders[$orderCount]->setShippingMethodValue($data);
	  }
	*/
	}





	$xml_parser = xml_parser_create();
	xml_set_element_handler($xml_parser, "startElement", "endElement");
	xml_set_character_data_handler($xml_parser, "characterData");
	if (!($fp = fopen($orderFile, "r"))) {
	    die("could not open XML input.");
		unlink($orderFile);
	}
	
	while ($data = fread($fp, 4096)) {
	    if (!xml_parse($xml_parser, $data, feof($fp))) {
	        die(sprintf("<span class='largeBoldErrorText' align=center>XML error: %s at line %d</span>",
	                    xml_error_string(xml_get_error_code($xml_parser)),
	                    xml_get_current_line_number($xml_parser)));
			unlink($orderFile);
	    }
	}
	xml_parser_free($xml_parser);
	unlink($orderFile);

?>