<?php

  class Order {
    var $customerName,$address1,$address2,$city,$state,$zipcode,$country;
    var $shippingText,$shippingValue,$orderNumber,$comments,$isRush,$clientPrefix;
    var $clientEmail, $orderDate, $paymentMethod, $repID, $PONumber;
    var $dropShipID, $dropShipFee, $rushFee, $accountNumber, $rep2Name, $rep2Rate;
    var $arrProducts = array();

    function Order() {
    }


	function setRep2Rate($value){
		$this->rep2Rate = my_unescape_string($value);
	}

	function getRep2Rate(){
		return $this->rep2Rate;
	}

	function setRep2Name($value){
		$this->rep2Name = my_unescape_string($value);
	}

	function getRep2Name(){
		return $this->rep2Name;
	}

	function setAccountNumber($value){
		$this->accountNumber = my_unescape_string($value);
	}

	function getAccountNumber(){
		return $this->accountNumber;
	}

	function setRushFee($value){
		$this->rushFee = my_unescape_string($value);
	}

	function getRushFee(){
		return $this->rushFee;
	}

	function setDropShipFee($value){
		$this->dropShipFee = my_unescape_string($value);
	}

	function getDropShipFee(){
		return $this->dropShipFee;
	}

	function setDropShipID($value){
		$this->dropShipID = my_unescape_string($value);
	}

	function getDropShipID(){
		return $this->dropShipID;
	}

	function setPONumber($value){
		$this->PONumber = my_unescape_string($value);
	}

	function getPONumber(){
		return $this->PONumber;
	}

	function setRepID($value){
		$this->repID = my_unescape_string($value);
	}

	function getRepID(){
		return $this->repID;
	}

	function setPaymentMethod($value){
		$this->paymentMethod = my_unescape_string($value);
	}

	function getPaymentMethod(){
		return $this->paymentMethod;
	}

	function setOrderDate($value){
		$this->orderDate = my_unescape_string($value);
	}

	function getOrderDate(){
		return $this->orderDate;
	}

	function setClientEmail($value){
		$this->clientEmail = my_unescape_string($value);
	}

	function getClientEmail(){
		return $this->clientEmail;
	}

	function setCustomerName($value){
		$this->customerName = my_unescape_string($value);
	}

	function getCustomerName(){
		return $this->customerName;
	}

	function setAddress1($value){
		$this->address1 = my_unescape_string($value);
	}

	function getAddress1(){
		return $this->address1;
	}

	function setAddress2($value){
		$this->address2 = my_unescape_string($value);
	}

	function getAddress2(){
		return $this->address2;
	}

	function setCity($value){
		$this->city = my_unescape_string($value);
	}

	function getCity(){
		return $this->city;
	}

	function setState($value){
		$this->state = my_unescape_string($value);
	}

	function getState(){
		return $this->state;
	}

	function setZipcode($value){
		$this->zipcode = my_unescape_string($value);
	}

	function getZipcode(){
		return $this->zipcode;
	}

	function setCountry($value){
		$this->country = my_unescape_string($value);
	}

	function getCountry(){
		return $this->country;
	}

	function setProducts($objProduct){
	  if(is_array($this->arrProducts)){
		array_push($this->arrProducts, $objProduct);
	  }
	}

	function getProducts(){
		return $this->arrProducts;
	}

	function setShippingMethod($value){
		$this->shippingMethod = my_unescape_string($value);
	}

	function getShippingMethod(){
		return $this->shippingMethod;
	}

	function setShippingMethodValue($value){
		$this->shippingMethodValue = my_unescape_string($value);
	}

	function getShippingMethodValue(){
		return $this->shippingMethodValue;
	}

	function setOrderNumber($value){
		$this->orderNumber = my_unescape_string($value);
	}

	function getOrderNumber(){
		return $this->orderNumber;
	}

	function setComments($value){
		$this->comments = my_unescape_string($value);
	}

	function getComments(){
		return $this->comments;
	}

	function setRush($yesOrNo){
		$this->isRush = my_unescape_string($yesOrNo);
	}

	function isRush(){
		return $this->isRush;
	}

	function setClientPrefix($clientPrefix){
		$this->clientPrefix = my_unescape_string($clientPrefix);
	}

	function getClientPrefix(){
		return $this->clientPrefix;
	}


	function toString(){
		echo  "====================================================<br>";
		echo  "CustomerName: ".$this->getCustomerName()."<br>";
		echo  "Address1: ".$this->getAddress1()."<br>";
		echo  "Address2: ".$this->getAddress2()."<br>";
		echo  "City: ".$this->getCity()."<br>";
		echo  "State: ".$this->getState()."<br>";
		echo  "ZipCode: ".$this->getZipCode()."<br>";
		echo  "Country: ".$this->getCountry()."<br>";
		echo  "Shipping Method: ".$this->getShippingMethod()."<br>";
		echo  "Shipping Method Value: ".$this->getShippingMethodValue()."<br>";
		echo  "Is A Rush Order: ".$this->isRush()."<br>";
		echo  "Comments: ".$this->getComments()."<br>";
		echo  "---------------------------------------------------<br>";

		for($i=0; $i< count($this->arrProducts); $i++){
			echo  $this->arrProducts[$i]->toString();
		}
		echo  "---------------------------------------------------<br>";
	}//end of toString

	function toXML(){
    $outString = "<ORDER>\n".
    "\t<ORDER_ID>". $this->getOrderNumber() ."</ORDER_ID>\n".
    "\t<REQUIRED_DATE>". $this->getOrderDate() ."</REQUIRED_DATE>\n".
    "\t<SHIP_NAME>". $this->getCustomerName() ."</SHIP_NAME>\n".
    "\t<SHIP_ADDRESS1>". $this->getAddress1() ."</SHIP_ADDRESS1>\n".
    "\t<SHIP_ADDRESS2>". $this->getAddress2() ."</SHIP_ADDRESS2>\n".
    "\t<SHIP_CITY>". $this->getCity() ."</SHIP_CITY>\n".
    "\t<SHIP_STATE>". $this->getState() ."</SHIP_STATE>\n".
    "\t<SHIP_COUNTRY>". $this->getCountry() ."</SHIP_COUNTRY>\n".
    "\t<SHIP_ZIP>". $this->getZipcode() ."</SHIP_ZIP>\n".
    "\t<EMAIL>". $this->getClientEmail() ."</EMAIL>\n".
    "\t<ORDER_DATE>". $this->getOrderDate() ."</ORDER_DATE>\n".
    "\t<CANCEL_DATE>". $this->getOrderDate() ."</CANCEL_DATE>\n".
    "\t<SHIPPING>". $this->getShippingMethod() ."</SHIPPING>\n".
    "\t<PAYMENT_METHOD>". $this->getPaymentMethod() ."</PAYMENT_METHOD>\n".
    "\t<COMMENTS>". $this->getComments() ."</COMMENTS>\n".
    "\t<REP_ID>". $this->getRepID() ."</REP_ID>\n".
    "\t<PO_NUMBER>". $this->getPONumber() ."</PO_NUMBER>\n".
    "\t<DROP_SHIP_ID>". $this->getDropShipID() ."</DROP_SHIP_ID>\n".
    "\t<SHIP_ID>". $this->getAccountNumber() ."</SHIP_ID>\n".
    "\t<CONSUMER></CONSUMER>\n".
    "\t<REP2>". $this->getRep2Name() ."</REP2>\n".
    "\t<REP2RATE>". $this->getRep2Rate() ."</REP2RATE>\n".
    "\t<ITEMS>\n";

		for($i=0; $i< count($this->arrProducts); $i++){
			$outString .=  $this->arrProducts[$i]->toXML();
		}
    $outString .= "\t</ITEMS>\n".
    "\t<MISC_FEES>\n".
	    "\t<MISC_FEE>\n".
    "\t\t<DROP_SHIP_FEE>". $this->getDropShipFee() ."</DROP_SHIP_FEE>\n".
		    "\t</MISC_FEE>\n".
			"\t<MISC_FEE>\n".
    "\t\t<RUSH_FEE>". $this->getRushFee() ."</RUSH_FEE>\n".
	"\t</MISC_FEE>\n".
    "\t</MISC_FEES>\n".
    "</ORDER>\n";

    return $outString; 
	}//end of toXML

  }
?>
