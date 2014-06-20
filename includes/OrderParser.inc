<?php



$arrOrders = array();

$orderCount = -1;

$orderFile;









	function startElement($parser, $name, $attrs){

	  global $tag,$inOrderElement,$inShippinInfo,$inProductInfo,$inCustomerName;

	  global $inAddressInfo1,$inAddressInfo2,$inCity,$inState,$inZipCode,$inCountry;

	  global $inShippingMethod,$inShippingMethodValue,$inOrderNumber,$arrOrders,$orderCount;

	  global $inRushOrder,$inComments;

	  

	  $tag = $name;

	  

	  if($name == "ORDER"){

		$inOrderElement = true;

		$orderCount++;

		$arrOrders[$orderCount] = new Order;

	  }

	  if($name == "SHIPPINGINFO"){

		$inShippinInfo = true;

	  }

	  if($name == "PRODUCTSINFO"){

		$inProductInfo = true;

	  }

	  if($name == "CUSTOMERNAME"){

		$inCustomerName = true;

	  }

	  if($name == "ADDRESSINFO1"){

		$inAddressInfo1 = true;

	  }

	  if($name == "ADDRESSINFO2"){

		$inAddressInfo2 = true;

	  }

	  if($name == "CITY"){

		$inCity = true;

	  }

	  if($name == "STATE"){

		$inState = true;

	  }

	  if($name == "ZIPCODE"){

		$inZipCode = true;

	  }

	  if($name == "COUNTRY"){

		$inCountry = true;

	  }

	  if($name == "SHIPPINGMETHOD"){

		$inShippingMethod = true;

	  }

	  if($name == "SHIPPINGMETHODVALUE"){

		$inShippingMethodValue = true;

	  }

	  if($name == "ORDERNUMBER"){

		$inOrderNumber = true;

	  }

	  if($name == "PRODUCTSINFO"){

		$inProductInfo = true;

	  }

	  if($name == "COMMENTS"){

		$inComments = true;

	  }

	  if($name == "RUSHORDER"){

		$inRushOrder = true;

	  }

	  

	  if($inProductInfo && $name == "PRODUCT"){

	  	$arrOrders[$orderCount]->setProducts(new Product($attrs['MODEL'],$attrs['QUANTITY'],$attrs['SIZE']));

	  }

	}







	function endElement($parser, $name){

	  global $inOrderElement,$inShippinInfo,$inProductInfo,$inCustomerName;

	  global $inAddressInfo1,$inAddressInfo2,$inCity,$inState,$inZipCode,$inCountry;

	  global $inShippingMethod,$inShippingMethodValue,$inOrderNumber,$arrOrders,$orderCount;

	  global $inRushOrder,$inComments;

	

	  if($name == "ORDER"){

		$inOrderElement = false;

		

	  }

	  if($name == "SHIPPINGINFO"){

		$inShippinInfo = false;

	  }

	  if($name == "PRODUCTSINFO"){

		$inProductInfo = false;

	  }

	  if($name == "CUSTOMERNAME"){

		$inCustomerName = false;

	  }

	  if($name == "ADDRESSINFO1"){

		$inAddressInfo1 = false;

	  }

	  if($name == "ADDRESSINFO2"){

		$inAddressInfo2 = false;

	  }

	  if($name == "CITY"){

		$inCity = false;

	  }

	  if($name == "STATE"){

		$inState = false;

	  }

	  if($name == "ZIPCODE"){

		$inZipCode = false;

	  }

	  if($name == "COUNTRY"){

		$inCountry = false;

	  }

	  if($name == "SHIPPINGMETHOD"){

		$inShippingMethod = false;

	  }

	  if($name == "SHIPPINGMETHODVALUE"){

		$inShippingMethodValue = false;

	  }

	  if($name == "ORDERNUMBER"){

		$inOrderNumber = false;

	  }

	  if($name == "PRODUCTSINFO"){

		$inProductInfo = false;

	  }

	  if($name == "COMMENTS"){

		$inComments = false;

	  }

	  if($name == "RUSHORDER"){

		$inRushOrder = false;

	  }

	  

	  if($name == "KERUSSOORDERS"){

	    

	  }

	}









	function characterData($parser, $data) {

	  global $tag,$inOrderElement,$inShippinInfo,$inProductInfo,$inCustomerName;

	  global $inAddressInfo1,$inAddressInfo2,$inCity,$inState,$inZipCode,$inCountry;

	  global $inShippingMethod,$inShippingMethodValue,$inOrderNumber,$arrOrders,$orderCount;

	  global $inRushOrder,$inComments;

	

	

	 $data = trim(htmlentities($data));

	    

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

	  if($tag == "RUSHORDER" && $inRushOrder){

	    if(strtolower($data) == "yes" || strtolower($data) == "no"){

		$arrOrders[$orderCount]->setRush(strtolower($data));

		}

	  }

	  if($tag == "COMMENTS" && $inComments){

		$arrOrders[$orderCount]->setComments($data);

	  }

	

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