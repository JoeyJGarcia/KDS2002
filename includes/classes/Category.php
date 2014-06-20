<?php

class Category{
	private $id;
	private $name;
	private $prefix;
	private $sizeCharge;
		
	
	function getName(){
		return $this->name;
	}
	
	function setName($nameValue){
		$this->name = $nameValue;
	}
	
	function getPrefix(){
		return $this->prefix;
	}
	
	function setPrefix($prefixValue){
		$this->prefix = $prefixValue;
	}
	
	function getId(){
		return $this->id;
	}
	
	function setId($idValue){
		$this->id = $idValue;
	}
	
	function getSizeCharge(){
		return substr( $this->sizeCharge, 0, strlen($this->sizeCharge)-2);
	}
	
	function appendSizeCharge($sizeCharge){
		$this->sizeCharge .= $sizeCharge .", ";
	}
}




?>