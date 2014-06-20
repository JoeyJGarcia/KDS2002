<?php

  class Product {
    var $model,$quantity,$size, $lineNumber, $orderID, $unitCost;


    function Product($model, $quantity, $size, $lineNumber = 0, $orderID = 0, $unitCost = 0) {
      $this->setModel(trim($model));
      $this->setQuantity($quantity);
      $this->setSize(trim($size));
      $this->setLineNumber($lineNumber);
      $this->setOrderID($orderID);
      $this->setUnitCost($unitCost);
    }


	function setUnitCost($value){
		$this->unitCost = my_unescape_string($value);
	}

	function getUnitCost(){
		return $this->unitCost;
	}

	function setOrderID($value){
		$this->orderID = my_unescape_string($value);
	}

	function getOrderID(){
		return $this->orderID;
	}

	function setModel($value){
		$this->model = my_unescape_string(trim($value));
	}

	function getModel(){
		return $this->model;
	}

	function setLineNumber($value){
		$this->lineNumber = my_unescape_string($value);
	}

	function getLineNumber(){
		return $this->lineNumber;
	}

	function setQuantity($value){
		$this->quantity = my_unescape_string($value);
	}

	function getQuantity(){
		return $this->quantity;
	}

	function setSize($value){
		$this->size = my_unescape_string(trim($value));
	}

	function getSize(){
		return $this->size;
	}

	function toString(){
		echo  "&nbsp;&nbsp;--------------------------------------<br>";
		echo  "&nbsp;&nbsp;Model: ".$this->getModel()."<br>";
		echo  "&nbsp;&nbsp;Quantity: ".$this->getQuantity()."<br>";
		echo  "&nbsp;&nbsp;Size: ".$this->getSize()."<br>";
	}

	function toXML(){
   $outString =  "\t\t<ITEM>\n".
    "\t\t\t<LINE_NUMBER>".$this->getLineNumber()."</LINE_NUMBER>\n".
    "\t\t\t<QUANTITY>".$this->getQuantity()."</QUANTITY>\n".
    "\t\t\t<UNIT_COST>".$this->getUnitCost()."</UNIT_COST>\n".
    "\t\t\t<PRODUCT_MODEL>".$this->getModel()."</PRODUCT_MODEL>\n".
    "\t\t</ITEM>\n";
    
    return $outString;
	}


  }
?>
