<?php
class DatabaseUtils
{
    private $_user = 'kerussod_chillie';
    private $_pass = '123456q';
    private $_dbname = 'kerussod_kdsdb';
    private $_host = 'localhost';
    private $_dsn;
    private $_error;
    private $_stmt;
    public $dbh;
    public static $PDO_TYPE_INT = PDO::PARAM_INT;
    public static $PDO_TYPE_BOOL = PDO::PARAM_BOOL;
    public static $PDO_TYPE_NULL = PDO::PARAM_NULL;
    public static $PDO_TYPE_STR = PDO::PARAM_STR;


    public function __construct()
    {
        $this->_dsn = 'mysql:host=' . $this->_host . ';dbname=' . $this->_dbname . ';charset=utf8';
        $options = array(
            PDO::ATTR_PERSISTENT	=> true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new PDO($this->_dsn, $this->_user, $this->_pass, $options);
        } catch (PDOException $e) {
            $this->_error = $e->getMessage();
        }
    }

    public function query($query)
    {
        $this->_stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {

        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }

            $this->_stmt->bindValue($param, $value, $type);
        }

    }

    public function execute()
    {
        return $this->_stmt->execute();
    }

    public function resultset()
    {
        $this->execute();
        return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->_stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    public function prepare($sql)
    {
        return $this->dbh->prepare($sql);
    }

    public function insert($insertSQL)
    {
        $this->_stmt = $this->prepare($insertSQL);
        $this->execute();
        return $this->lastInsertId();
    }

    public function queryMany($querySQL)
    {
        $this->_stmt = $this->prepare($querySQL);
        $this->execute();
        return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function querySingle($querySingleSQL)
    {
        $this->_stmt = $this->prepare($querySingleSQL);
        $this->execute();
        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($updateSQL)
    {
        $this->_stmt = $this->prepare($updateSQL);
        $this->execute();
        return $this->_stmt->rowCount();
    }

}
