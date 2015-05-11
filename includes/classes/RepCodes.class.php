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

    /**
     * Gets an array of a rep codes for a specific Account
     *
     * @param array accountNumber - Unique account number used for where clause.
     * @return array record - all the rep codes for one account
     */
    public function getAccountReps ($accountNumber)
    {

        $database =  $this->getDatabaseUtils();

        try {
            $database->query("SELECT * FROM reps WHERE accounts_number = :account_number");
            $database->bind(':account_number', $accountNumber);
            $row = $database->single();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $row;
    }

    public function getResultCount()
    {
        $database =  $this->getDatabaseUtils();

        return $database->rowCount();
    }

}

?>
