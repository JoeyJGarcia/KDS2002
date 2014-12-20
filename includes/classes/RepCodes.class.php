<?php
//require('includes/classes/DatabaseUtils.class.php');

/**
 * RepCodes Class
 *
 * Returns an array of name value pairs for all rep_codes in the rep_codes table.
 *
 */

class RepCodes {
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
	 * Gets an array of RepCodes
	 *
	 * @return array rep_codes - name values of all the rep_codes values in the rep_codes table.
	 */
	public function getRepCodes ()
	{
		
		$database =  $this->getDatabaseUtils();

		$database->query('SELECT * FROM rep_codes');
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