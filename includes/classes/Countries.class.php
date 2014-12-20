<?php
//require('includes/classes/DatabaseUtils.class.php');

/**
 * Countries Class
 *
 * Returns an array of name value pairs for all countries in the countries table.
 *
 */

class Countries {
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
	 * Gets an array of Countries
	 *
	 * @return array countries - name values of all the countries values in the countries table.
	 */
	public function getCountries ()
	{
		
		$database =  $this->getDatabaseUtils();

		$database->query('SELECT * FROM countries');
		
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