<?php

/**
 * OrderUtils Class
 *
 * Returns an array of name value pairs for all rep_codes in the rep_codes table.
 *
 */

class OrderUtils {
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

    public function orderExist ($invoice_number, $account_number)
    {
        $database =  $this->getDatabaseUtils();
        $orderId = null;
        $rv = null;
        $rows = null;

        $sql = sprintf("SELECT *
            FROM orders
            WHERE accounts_number = '%s' AND
                customer_invoice_number = '%s'", $account_number, $invoice_number);
        try {
            $database->query($sql);
            $rows = $database->resultset();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        if ($database->rowCount() > 0) {
            $rv = $rows[0]['order_id'];
        } else {
            $rv = false;
        }

        return $rv;
    }

    public function productExist ($orderId, $product_model, $product_size, $product_quantity)
    {
        $database =  $this->getDatabaseUtils();
        $rv = null;
        $rows = null;

        $sql = sprintf("SELECT *
            FROM orders_products
            WHERE order_id = %d AND
                order_product_model = '%s' AND
                order_product_size = '%s' AND
                order_product_quantity = %d",
                $orderId,
                $product_model,
                $product_size,
                $product_quantity);
        try {
            $database->query($sql);
            $rows = $database->resultset();
            $rv = ($database->rowCount() > 0) ? true : false ;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $rv;
    }


    public function getResultCount()
    {
        $database =  $this->getDatabaseUtils();

        return $database->rowCount();
    }

    public function productModelExist($product_model)
    {
        $database =  $this->getDatabaseUtils();
        $isFound = null;
        $rows = null;

        $sql = sprintf("SELECT *
            FROM products2
            WHERE products2_model = '%s'",
                $product_model);
        try {
            $database->query($sql);
            $rows = $database->resultset();
            $isFound = ($database->rowCount() > 0) ? true : false ;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $isFound;
    }

}

?>
