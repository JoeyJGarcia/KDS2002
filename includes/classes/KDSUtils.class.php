<?php
require('includes/classes/DatabaseUtils.class.php');
require('includes/classes/Accounts.class.php');
require('includes/classes/Countries.class.php');
require('includes/classes/Fees.class.php');
require('includes/classes/RepCodes.class.php');
require('includes/classes/Shipping.class.php');
require('includes/classes/OrderUtils.class.php');

class KDSUtils {

    private $database;
    private $accounts;
    private $fees;
    private $repcodes;
    private $shipping;
    private $countries;
    private $orderUtils;

    public function __construct()
    {
        $this->database = new DatabaseUtils();
    }

    public function getDatabaseUtils() {
        return $this->database;
    }

    public function createNameValuePair($resultSet, $name, $value)
    {
        $rv = array();
        for ($i = 0; $i < count($resultSet); $i++) {
            $temp = $resultSet[$i];
            $rv[$temp[$name]] = $temp[$value];
        }

        return $rv;
    }


    public function createDropDownArray($resultSet, $idName, $textName, $default)
    {
        $rv = array();
        $rv[] = $default;
        for ($i = 0; $i < count($resultSet); $i++) {
            $temp = $resultSet[$i];
            $rv[] = array( 'id'=>$temp[$idName], 'text'=>$temp[$textName]);
        }

        return $rv;
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

    public function getFtpAccounts()
    {
        if (!isset($this->accounts)) {
            $this->accounts = new Accounts($this->database);
        }

        return $this->accounts->getFtpAccounts();
    }

    public function getAccountReps($accountNumber) {
        if (!isset($this->repcodes)) {
            $this->repcodes = new RepCodes($this->database);
        }

        return $this->repcodes->getAccountReps($accountNumber);
    }

    public function getRepsRowCount()
    {
        if (!isset($this->repcodes)) {
            $this->repcodes = new RepCodes($this->database);
        }

        return $this->repcodes->getResultCount();
    }

    public function orderExist($order)
    {
        if (!isset($this->orderUtils)) {
            $this->orderUtils = new OrderUtils($this->database);
        }

        return $this->orderUtils->orderExist($order['customer_invoice_number'], $order['accounts_number']);
    }

    public function productExist($orderId, $order)
    {
        if (!isset($this->orderUtils)) {
            $this->orderUtils = new OrderUtils($this->database);
        }

        return $this->orderUtils->productExist($orderId, $order['product_model'], $order['product_size'], $order['product_quantity']);
    }

    public function insert($insertSQL)
    {
        return $this->database->insert($insertSQL);
    }

    public function queryMany($querySQL)
    {
        return $this->database->queryMany($insertSQL);
    }

    public function querySingle($querySingleSQL)
    {
        return $this->database->querySingle($querySingleSQL);
    }

    public function update($updateSQL)
    {
        return $this->database->update($updateSQL);
    }

    public function productModelExist($productModel) {
        if (!isset($this->orderUtils)) {
            $this->orderUtils = new OrderUtils($this->database);
        }

        return $this->orderUtils->productModelExist($productModel);
    }

}

?>
