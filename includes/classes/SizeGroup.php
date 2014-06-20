<?php

class SizeGroup{
	
	private $sizeCode;
	private $arrSizeArray = array();
	private $arrSizeToPrice = array();
	
	public function __construct( $sizeCode ) {
		$this->sizeCode = $sizeCode;
	}
	
	
	function addSizePrice($size, $price){
		$this->arrSizeArray[] = array('id' => $this->sizeCode . " - " . $size,
									  'text' => $this->sizeCode . " - " . $size);
		$this->arrSizeToPrice[$size] = $price;
	}
	
	function getSizeArray(){
		return $this->$arrSizeArray;
	}
	
	
	function getSizeCode(){
		return $this->sizeCode;
	}
	
	function getPrice($size){
		return $this->arrSizeToPrice[$size];	
	}
	
}

?>