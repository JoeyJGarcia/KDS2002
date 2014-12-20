<?php
//require('includes/classes/DatabaseUtils.class.php');

/**
 * Shipping Class
 *
 * Returns an array of name value pairs for all shipping in the shipping table.
 *
 */

class Shipping {
	private $database;

	public function __construct($database = null)
	{
		if(!isset($database)) {
			$this->database = new DatabaseUtils();
		} else {
			$this->database = $database;
		}
	}

	private function getDatabaseUtils()
	{
		if(!isset($this->database)) {
			$this->database = new DatabaseUtils();
		}

		return $this->database;
	}

	/**
	 * Gets an array of Shipping
	 *
	 * @return array shipping - name values of all the shipping values in the shipping table.
	 */
	public function getShipping ()
	{
		
		$database =  $this->getDatabaseUtils();

		$database->query('SELECT * FROM shipping');
		$rows = $database->resultset();

		return $rows;
	}

	public function getResultCount()
	{
		$database =  $this->getDatabaseUtils();

		return $database->rowCount();
	}
}
?>