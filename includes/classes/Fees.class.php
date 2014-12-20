<?php
//require('includes/classes/DatabaseUtils.class.php');

/**
 * Fees Class
 *
 * Returns an array of name value pairs for all fees in the fees table.
 *
 */

class Fees {

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
	 * Gets an array of Fees
	 *
	 * @return array fees - name values of all the fee values in the fees table.
	 */
	public function getFees ()
	{
		
		$database =  $this->getDatabaseUtils();

		$database->query('SELECT * FROM fees');
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