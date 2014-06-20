<?php


class OrderParser{


var $arrOrders = array();
var $orderCount = -1;
var $orderFile;


	function OrderParser($orderFile){
	  $this->orderFile = $orderFile;
	  $this->readOrderFile();
	}



	function readOrderFile(){
		$xml_parser = xml_parser_create();
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "characterData");
		if (!($fp = fopen($this->orderFile, "r"))) {
		    die("could not open XML input.");
			unlink($this->orderFile);
		}

		while ($data = fread($fp, 4096)) {
		    if (!xml_parse($xml_parser, $data, feof($fp))) {
		        die(sprintf("<span class='largeBoldErrorText' align=center>XML error: %s at line %d</span>",
		                    xml_error_string(xml_get_error_code($xml_parser)),
		                    xml_get_current_line_number($xml_parser)));
				unlink($this->orderFile);
		    }
		}
		xml_parser_free($xml_parser);
		unlink($this->orderFile);
	}


	function startElement($parser, $name, $attrs){
	  global $tag,$inOrderElement,$inShippinInfo,$inProductInfo,$inCustomerName;
	  global $inAddressInfo1,$inAddressInfo2,$inCity,$inState,$inZipCode,$inCountry;
	  global $inShippingMethod,$inShippingMethodValue,$inOrderNumber,$inRushOrder,$inComments;

	  $tag = $name;

	  if($name == "ORDER"){
		$inOrderElement = true;
		$this->orderCount++;
		$this->arrOrders[$this->orderCount] = new Order;
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
	  	$this->arrOrders[$this->orderCount]->setProducts(new Product($attrs['MODEL'],$attrs['QUANTITY'],"DDDD"));
	  }
	}



	function endElement($parser, $name){
	  global $inOrderElement,$inShippinInfo,$inProductInfo,$inCustomerName;
	  global $inAddressInfo1,$inAddressInfo2,$inCity,$inState,$inZipCode,$inCountry;
	  global $inShippingMethod,$inShippingMethodValue,$inOrderNumber,$inRushOrder,$inComments;

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
	  global $inShippingMethod,$inShippingMethodValue,$inOrderNumber,$inRushOrder,$inComments;


	 $data = trim(htmlspecialchars($data));

	  if($tag == "CUSTOMERNAME" && $inCustomerName){
		$this->arrOrders[$this->orderCount]->setCustomerName($data);
	  }
	  if($tag == "ADDRESSINFO1" && $inAddressInfo1){
		$this->arrOrders[$this->orderCount]->setAddress1($data);
	  }
	  if($tag == "ADDRESSINFO2" && $inAddressInfo2){
		$this->arrOrders[$this->orderCount]->setAddress2($data);
	  }
	  if($tag == "CITY" && $inCity){
		$this->arrOrders[$this->orderCount]->setCity($data);
	  }
	  if($tag == "STATE" && $inState){
		$this->arrOrders[$this->orderCount]->setState($data);
	  }
	  if($tag == "ZIPCODE" && $inZipCode){
		$this->arrOrders[$this->orderCount]->setZipcode($data);
	  }
	  if($tag == "COUNTRY" && $inCountry){
		$this->arrOrders[$this->orderCount]->setCountry($data);
	  }
	  if($tag == "SHIPPINGMETHOD" && $inShippingMethod){
		$this->arrOrders[$this->orderCount]->setShippingMethod($data);
	  }
	  if($tag == "SHIPPINGMETHODVALUE" && $inShippingMethodValue){
		$this->arrOrders[$this->orderCount]->setShippingMethodValue($data);
	  }
	  if($tag == "RUSHORDER" && $inRushOrder){
	    if(strtolower($data) == "yes" || strtolower($data) == "no"){
		$this->arrOrders[$this->orderCount]->setRush(strtolower($data));
		}
	  }
	  if($tag == "COMMENTS" && $inComments){
		$this->arrOrders[$this->orderCount]->setComments($data);
	  }

	}



	function getOrders(){
	  echo "Hi";
		return $this->arrOrders;
	}

}
?>