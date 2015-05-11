<?php

/**
 * Accounts Class
 *
 * Returns an array of name value pairs for all accounts in the accounts table.
 *
 */

class Accounts {

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
     * Gets an array of Accounts
     *
     * @return array accounts - name values of all the accounts values in the accounts table.
     */
    public function getAccounts ()
    {

        $database =  $this->getDatabaseUtils();

        $database->query('SELECT * FROM accounts');
        $rows = $database->resultset();

        return $rows;
    }


    /**
     * Gets an array of a specific Account
     *
     * @param array accountNumber - Unique account number used for where clause.
     * @return array record - all the information for one account
     */
    public function getAccount ($accountNumber)
    {

        $database =  $this->getDatabaseUtils();

        try {
            $database->query("SELECT * FROM accounts WHERE accounts_number = :account_number");
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

    public function getFtpAccounts()
    {
        $database = $this->getDatabaseUtils();

        try {
            $database->query("SELECT * FROM accounts WHERE accounts_folder_name REGEXP  '^[a-z0-9]+'");
            $rows = $database->resultset();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $rows;
    }
}

?>
