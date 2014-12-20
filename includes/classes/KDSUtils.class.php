<?php
require('includes/classes/DatabaseUtils.class.php');
require('includes/classes/Accounts.class.php');
require('includes/classes/Countries.class.php');
require('includes/classes/Fees.class.php');
require('includes/classes/RepCodes.class.php');
require('includes/classes/Shipping.class.php');

class KDSUtils {

	private $database;
	private $accounts;
	private $fees;
	private $repcodes;
	private $shipping;
	private $countries;

	public function __construct()
	{
		$this->database = new DatabaseUtils();
	}

	public function createNameValuePair($resultSet, $name, $value)
	{
		return $this->database->createNameValuePair($resultSet, $name, $value);
	}


	public function createDropDownArray($resultSet, $idName, $textName, $default)
	{
		return $this->database->createDropDownArray($resultSet, $idName, $textName, $default);
	}


	public function getAccount($accountNumber)
	{
		if (!isset($this->accounts)) {
			$this->accounts = new Accounts($this->database);
		}

		return $this->accounts->getAccount($accountNumber);
	}


	public function getAccounts()
	{
		if (!isset($this->accounts)) {
			$this->accounts = new Accounts($this->database);
		}

		return $this->accounts->getAccounts();
	} 

	public function getFees()
	{
		if (!isset($this->fees)) {
			$this->fees = new Fees($this->database);
		}

		return $this->fees->getFees();
	}

	public function getRepCodes()
	{
		if (!isset($this->repcodes)) {
			$this->repcodes = new RepCodes($this->database);
		}

		return $this->repcodes->getRepCodes();
	}

	public function getShipping()
	{
		if (!isset($this->shipping)) {
			$this->shipping = new Shipping($this->database);
		}

		return $this->shipping->getShipping();
	}

	public function getCountries()
	{
		if (!isset($this->countries)) {
			$this->countries = new Countries($this->database);
		}

		return $this->countries->getCountries();
	}

}

?>