<?php
require('includes/application_top.php');
require('includes/classes/KDSUtils.class.php');
?>


<?php
/*
 * Global Variables Initialized
 */
$output = "";
$hasHeaderRow = true;
$startRow = ($hasHeaderRow)? 1 : 0;
$newOrderStatus = 10;
$orderCount = 0;
$csvfile = null;
$accountNumber = null;
$accountPriceLvl = null;
$accountReps = null;
$product_size_adjusted = null;
$arrBlackList = array();

$ku = new KDSUtils();

$shipping = $ku->getShipping();
$arrShippingName = $ku->createNameValuePair($shipping, 'shipping_alias', 'shipping_name');
$arrShipping = $ku->createNameValuePair($shipping, 'shipping_alias', 'shipping_id');
$countries = $ku->getCountries();
$arrCountries = $ku->createNameValuePair($countries, 'countries_name', 'countries_number');
$fees = $ku->getFees();
$arrFees = $ku->createNameValuePair($fees, 'fees_name', 'fees_value');
$repcodes = $ku->getRepCodes();
$arrRepCodes = $ku->createNameValuePair($repcodes, 'rep_name', 'rep_code');
$ftpAccounts = $ku->getFtpAccounts();
$arrFtpFolders = $ku->createNameValuePair($ftpAccounts, 'account_id', 'account_folder_name');
$ftpAccountsCount = count($ftpAccounts);

echo $ftpAccountsCount . " FTP Account(s) found: <br>";

/**
 * Starting point ....
 *
 * Step 0) Get all the accounts that have a value for account_folder_name
 * Step 1) loop through all the accounts found in step 0
 * Step 2) Set all globals
 * Step 3) Each folder is scanned, for each file found it is read, saved to an array
 * Step 4) Delete the csv file
 * Step 5) Submit the order from the single line in the csv file.
 *
 */

for ($i = 0; $i < $ftpAccountsCount; $i++) {

    $accountUsername = $ftpAccounts[$i]['accounts_username'];
    $accountNumber = $ftpAccounts[$i]['accounts_number'];
    $clientPrefix = $ftpAccounts[$i]['accounts_prefix'];
    $accountPriceLvl = $ftpAccounts[$i]['accounts_price_level'];
    $accountFTPFolder = $ftpAccounts[$i]['accounts_folder_name'];
    $dir = 'orderpull/'.$accountFTPFolder;
    $accountReps = $ku->getAccountReps($accountNumber);
    $repsRowCount = $ku->getRepsRowCount();

    echo "------------------------------------------------------<br>";
    echo "Processing order(s) for " . $accountUsername . "<br>";


    if (!file_exists($dir)) {
        echo "Folder was not found for " . $accountUsername . ", continuing to next account. <br><br>";
        continue;
    }

    /*
     * Look in each folder to see if files exist in the clients's folders.
     *
     */
    $arrFilesFound = array_diff(scandir($dir), array('.','..'));
    $arrFilesFoundCount = count($arrFilesFound);

    /*
     * For each folder found, pull out its contents and deleted the file.
     *
     */
    foreach($arrFilesFound as $file) {
        $output = "";
        $csvfile = $dir.'/'.$file;
        if (getType($file) == NULL || !is_file($csvfile)) {
            continue;
        }

        if (($handle = fopen($csvfile, "r")) !== FALSE) {
            if ($handle) {
                while (($orderData = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $arrFileContents[] = $orderData;
                }
            }
        }
        fclose($handle);// Remove file now that we copied its contents.
        unlink($csvfile);

        saveOutput("Processing file: " . $csvfile . "   at   ". date("y-m-d h:i:s") ."<br>");
        saveOutput("CSV File has ".count($arrFileContents)." rows (including the header row)");

        printMessage($output, "**** OUTPUT ****");

        submitOrder($arrFileContents);
        mailWebServicesAboutBadProducts();

        $arrFileContents = null;
        $arrBlackList  = array();
    }

    echo "<br>" . $orderCount . " Orders Processed for " . $accountUsername . "<br>";
    $orderCount = 0;
}

/**
 * Function: submitOrder
 *
 * This function parses each row that was found in csv file and submits the order until the file's
 * contents are completely parsed.
 *
 * Upon completion an email is sent when the order is added to the database;
 *
 * @param (array) An array of all the lines found in the csv file
 *
 */
function submitOrder ($arrFileContents) {
    global $startRow, $accountNumber, $ku, $arrBlackList;
echo "<pre>";
echo count($arrFileContents);
echo "</pre><br>";
    for($i = $startRow; $i < count($arrFileContents); $i++) {
echo "JOEY";
        $arrOrder = createOrderArray($arrFileContents[$i]);
        $doesProductExist = $ku->productModelExist($arrOrder['product_model']);
        $doesOrderExist = orderExist($arrOrder);
if ($doesProductExist) {
    echo "product does exist BAD<br>";
} else {
    echo "product does exist GOOD<br>";
}

if ($doesOrderExist) {
    echo "order does exist BAD<br>";
} else {
    echo "order does exist GOOD<br>";
}
        if (!$doesProductExist) {
            if (!array_key_exists($arrOrder['customer_invoice_number'], $arrBlackList)) {
                $arrBlackList[$arrOrder['customer_invoice_number']] = $arrOrder['product_model'];
            } elseif(in_array($arrOrder['product_model'], $arrBlackList)) {
                $arrBlackList[$arrOrder['customer_invoice_number']] = $arrBlackList[$arrOrder['customer_invoice_number']] . ', ' .$arrOrder['product_model'];
            }

            echo $arrOrder['product_model'] . " Product does not exist. <br>";
            continue;
        }

        if (getType($doesOrderExist) === 'boolean' && !$doesOrderExist) {
            /*
             * If the order does NOT exist, then you want to call insertOrder
             */
             $orderId = insertOrder($arrOrder);
        } elseif (getType($doesOrderExist) === 'string' || getType($doesOrderExist) === 'integer') {
            /*
             * If the order does exist, then check to see if the product needs to be added still but
             * you need to get the orderId from the order check so it can be used to join the order
             */
            $orderId = $doesOrderExist;//If an order exists, the return value is the order_id;
            printMessage("No need to add this order, it already exist.", "Order Already Exist");
        }
        echo "****************<br>";
        echo "orderId type: " . getType($orderId) . "<br>";
        echo "orderId: " . $orderId . "<br>";
        echo "****************<br>";

        if (intVal($orderId) > 0 && !productExist($orderId, $arrOrder)) {
            /*
             * If the product is not there, then add it.
             */
            insertOrderProduct($orderId, $arrOrder);
        }
    }


    if (intVal($orderId) > 0 && getType($accountNumber) === 'string' && strlen($accountNumber) > 0) {
        mailOrderMessage($orderId, $accountNumber);
    } else {
        echo "***************************************************<br>";
        echo "Error trying to send order email summary.<br>";
        echo "orderId: " . $orderId . "<br>";
        echo "accountNumber: " . $accountNumber . "<br>";
        echo "***************************************************<br>";
    }
}


/**
 * Function: createOrderArray
 *
 * This function parses the single line found in the csv file and puts the
 * data into an array.
 *
 * @param (array) An array of all the data found in one line of the csv file
 * @return (array) An array of the line's order data
 */
function createOrderArray ($arrOrderLine) {
    global $accountNumber, $newOrderStatus, $reps, $arrFees, $accountReps, $clientPrefix;
    global $arrCountries, $arrShippingName, $arrShipping, $arrRepCodes, $product_size_adjusted;

    $product_size_adjusted = ( strlen(str_replace('"','',$arrOrderLine[13])) > 0 ) ? str_replace('"','',$arrOrderLine[13]) : "NA";

    $arrOrder = array(
        'customer_name'	=> str_replace('"','',$arrOrderLine[4]),
        'customer_address1'	=> str_replace('"','',$arrOrderLine[5]),
        'customer_address2'	=> str_replace('"','',$arrOrderLine[6]),
        'customer_intl_phone' => '',
        'customer_city'	=> str_replace('"','',$arrOrderLine[7]),
        'customer_state'	=> str_replace('"','',$arrOrderLine[8]),
        'customer_zip' => str_replace('"','',$arrOrderLine[9]),
        'customer_country' => str_replace('"','',$arrOrderLine[10]),
        'customer_country_number' => $arrCountries[strtoupper(str_replace('"','',$arrOrderLine[10]))],
        'customer_shipping_method' => $arrShippingName[str_replace('"','',$arrOrderLine[17])],
        'customer_shipping_id' => $arrShipping[str_replace('"','',$arrOrderLine[17])],
        'customer_invoice_number' => str_replace('"','',$arrOrderLine[1]),
        'purchase_date'	=>  date("y-m-d h:i:s"),
        'accounts_number' => $accountNumber,
        'purchase_order_number'	=> strtoupper($clientPrefix).date("mdyHis"),
        'order_comments' => 'FTP Order',
        'order_status' => $newOrderStatus,
        'dropship_fee' => str_replace('"','',$arrFees['Drop Ship']),
        'handling_fee' => str_replace('"','',$arrFees['Handling']),
        'isRush' => number_format(0, 2),
        'rush_fee'	=> number_format(0, 2),
        'misc_desc'	=> '',
        'rep1_name'	=> $accountReps['field_rep'],
        'rep1_code'	=> $arrRepCodes[$accountReps['field_rep']],
        'rep2_name'	=> $accountReps['inside_rep'],
        'rep2_code'	=> $arrRepCodes[$accountReps['inside_rep']],
        'rep3_name'	=> $accountReps['field_group'],
        'rep3_code'	=> $arrRepCodes[$accountReps['field_group']],
        'rep4_name'	=> $accountReps['national_group'],
        'rep4_code'	=> $arrRepCodes[$accountReps['national_group']],
        'rep5_name'	=> $accountReps['national_rep'],
        'rep5_code'	=> $arrRepCodes[$reps['national_rep']],
        'rep6_name'	=> $accountReps['sales_mgr'],
        'rep6_code'	=> $arrRepCodes[$accountReps['sales_mgr']],
        'product_model' => str_replace('"','',$arrOrderLine[12]),
        'design_description' =>  str_replace('"','',$arrOrderLine[11]),
        'product_name' => '[ ' . str_replace('"','',$arrOrderLine[12]) . ' ] ' .  str_replace('"','',$arrOrderLine[11]),
        'product_category' => substr(str_replace('"','',$arrOrderLine[12]), 0, 3),
        'product_size' => substr(str_replace('"','',$arrOrderLine[12]), 0, 3) . ' - ' . $product_size_adjusted,
        'product_quantity' => str_replace('"','',$arrOrderLine[14])
    );

    return $arrOrder;
}


/**
 * Function: orderExist
 *
 * This function checked for the existence of the order information in the
 * database.
 *
 * If the order is found, the order_id is returned
 * If the order is not found, it returns false
 *
 * @param (array) An array of all the data found in one line of the csv file (order information)
 * @return (boolean | integer) returns the result of the check.
 */
function orderExist ($arrOrder) {
    global $ku;
    $rv = false;

    $orderId = null;
    $rv = null;

    if ( getType($arrOrder['accounts_number']) === 'string' &&
         strlen($arrOrder['accounts_number']) > 0 &&
         getType($arrOrder['customer_invoice_number']) === 'string' &&
         strlen($arrOrder['customer_invoice_number']) > 0 ) {
        $rv =  $ku->orderExist($arrOrder);
    } else {
        $rv = false;
    }

    return $rv;
}


/**
 * Function: productExist
 *
 * This function checked for the existence of the product information in the
 * database.
 *
 * If the product is found, boolean true is returned
 * If the product is not found, boolean false is returned
 *
 * @param (integer) Order_id of the order that is joined to this product
 * @param (array) An array of all the data found in one line of the csv file (order information)
 * @return (boolean) returns the result of the check.
 */
function productExist ($orderId, $arrOrder) {
    global $ku;
    $rv = null;

    $rv = $ku->productExist($orderId, $arrOrder);

    return $rv;
}


/**
 * Function: insertOrder
 *
 * This function inserts the order information from the order array and returns the
 * order_id.
 *
 * @param (array) An array of all the data found in one line of the csv file (order information)
 * @return (integer) An integer representing the order_id
 */
function insertOrder ($arrOrder) {
    global $ku, $accountNumber, $accountPriceLvl, $product_size_adjusted, $orderCount;
    $ord_add_insert_id = null;

    $ord_add_sql = sprintf("INSERT INTO orders (
        customer_name, customer_address1, customer_address2, customer_intl_phone, customer_city,
        customer_state, customer_zip, customer_country, customer_country_number, customer_shipping_method,
        customer_shipping_id, customer_invoice_number, purchase_date, accounts_number, purchase_order_number,
        order_comments, order_status, dropship_fee, handling_fee, isRush,
        rush_fee, misc_desc, rep1_name, rep1_code, rep2_name,
        rep2_code, rep3_name, rep3_code, rep4_name, rep4_code,
        rep5_name, rep5_code, rep6_name, rep6_code
        ) VALUES (
          '%s', '%s', '%s', '%s', '%s',
          '%s', '%s', '%s', %d, '%s',
          '%s', '%s', '%s', '%s', '%s',
          '%s', '%s', %01.2f, %01.2f, %d,
          %01.2f, '%s', '%s', '%s', '%s',
          '%s', '%s', '%s', '%s', '%s',
          '%s', '%s', '%s', '%s' )",

        mysql_real_escape_string($arrOrder['customer_name']),
        mysql_real_escape_string($arrOrder['customer_address1']),
        mysql_real_escape_string($arrOrder['customer_address2']),
        mysql_real_escape_string($arrOrder['customer_intl_phone']),
        mysql_real_escape_string($arrOrder['customer_city']),

        mysql_real_escape_string($arrOrder['customer_state']),
        mysql_real_escape_string($arrOrder['customer_zip']),
        mysql_real_escape_string($arrOrder['customer_country']),
        intval($arrOrder['customer_country_number']),
        mysql_real_escape_string($arrOrder['customer_shipping_method']),

        mysql_real_escape_string($arrOrder['customer_shipping_id']),
        mysql_real_escape_string($arrOrder['customer_invoice_number']),
        mysql_real_escape_string($arrOrder['purchase_date']),
        mysql_real_escape_string($arrOrder['accounts_number']),
        mysql_real_escape_string($arrOrder['purchase_order_number']),

        mysql_real_escape_string($arrOrder['order_comments']),
        mysql_real_escape_string($arrOrder['order_status']),
        floatval($arrOrder['dropship_fee']),
        floatval($arrOrder['handling_fee']),
        intval($arrOrder['isRush']),

        floatval($arrOrder['rush_fee']),
        mysql_real_escape_string($arrOrder['misc_desc']),
        mysql_real_escape_string($arrOrder['rep1_name']),
        mysql_real_escape_string($arrOrder['rep1_code']),
        mysql_real_escape_string($arrOrder['rep2_name']),

        mysql_real_escape_string($arrOrder['rep2_code']),
        mysql_real_escape_string($arrOrder['rep3_name']),
        mysql_real_escape_string($arrOrder['rep3_code']),
        mysql_real_escape_string($arrOrder['rep4_name']),
        mysql_real_escape_string($arrOrder['rep4_code']),

        mysql_real_escape_string($arrOrder['rep5_name']),
        mysql_real_escape_string($arrOrder['rep5_code']),
        mysql_real_escape_string($arrOrder['rep6_name']),
        mysql_real_escape_string($arrOrder['rep6_code']) );

        printMessage($ord_add_sql, "Adding Order");

    try {
        $ord_add_insert_id = $ku->insert($ord_add_sql);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    echo "order_id: " . $ord_add_insert_id . "<br>";
    $orderCount++;

    return $ord_add_insert_id;
}


/**
 * Function: insertOrderProduct
 *
 * This function uses the order_id and the order data to insert the product
 * information from it.
 *
 * @param (integer) Order_id of the order that is joined to this product
 * @param (array) An array of all the data found in one line of the csv file (order information)
 */
function insertOrderProduct ($orderId, $arrOrder) {
    global $ku, $accountNumber, $accountPriceLvl, $product_size_adjusted;
    $ord_product_add_insert_id = null;

    $arrPriceRequest = array();
    $arrPriceRequest["price"] = getPriceBySize($accountNumber, $arrOrder['product_model'], $product_size_adjusted);
    $arrPriceRequest["product_size"] = $product_size_adjusted;
    $arrPriceRequest["productModel"] = $arrOrder['product_model'];
    $arrPriceRequest["priceLvl"] = $accountPriceLvl;
    $arrPriceRequest["accounts_number"] = $accountNumber;
    $arrPriceRequest["discount"] = checkForDiscount($arrPriceRequest);

    if ( is_array($arrPriceRequest["discount"]) && isset($arrPriceRequest["discount"]["price"]) &&
        (floatval($arrPriceRequest["discount"]["price"]) < floatval($arrPriceRequest["price"])) ) {
        $productPrice = $arrPriceRequest["discount"]["price"];
    } else {
        $productPrice = $arrPriceRequest["price"];
    }

    $ord_product_add_sql = sprintf("INSERT INTO orders_products (
        order_id, order_product_quantity, order_product_size,
        order_product_name, order_product_model, order_product_charge
        ) VALUES (%d, %d, '%s', '%s', '%s', %01.2f)",
        $orderId,
        intval($arrOrder['product_quantity']),
        mysql_real_escape_string($arrOrder['product_size']),
        mysql_real_escape_string($arrOrder['product_name']),
        mysql_real_escape_string($arrOrder['product_model']),
        number_format($productPrice, 2));

        printMessage($ord_product_add_sql, "Adding Order Product");

    try {
        $ord_product_add_insert_id = $ku->insert($ord_product_add_sql);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    /*
     * Get current order's comments
     */
    $order_comments_sql = sprintf("SELECT order_comments FROM orders WHERE order_id=%d", $orderId);
    $arrOrderComments = $ku->querySingle($order_comments_sql);

    echo "=====================================<br>";
    echo "<pre>";
    print_r($arrOrderComments);
    echo "Order Comments are: " . $arrOrderComments['order_comments'] . "<br>";
    echo "</pre>";
    echo "=====================================<br>";

    $productDescription = "<br>" . $arrOrder['product_quantity'] . ' X ' . 'Product Model: ' .$arrOrder['product_model'] . ' / Size: ' . $arrOrder['product_size'];
    $newOrderComments = $arrOrderComments['order_comments'] . $productDescription;
    $new_order_comments_sql = sprintf("UPDATE orders set order_comments = '%s' WHERE order_id = %d", $newOrderComments, $orderId);

    printMessage($new_order_comments_sql, "Order Comments");

    if (getType($new_order_comments_sql) == "string" && strlen($new_order_comments_sql) > 0 ) {
        try {
            $updateCount = $ku->update($new_order_comments_sql);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        if ($updateCount != 1) {
            echo "Update comments count was not one! Count = " . $updateCount ."<br>";
        }
    } else {
        printMessage($new_order_comments_sql, "Order Comments are NULL");
    }

}


function saveOutput ($moreOutput) {
    global $output;

    $output = $output . $moreOutput;
}


function getOutput () {
    global $output;

    echo $output;
}


function printMessage($message, $title='') {
    if (strlen($title) > 0) {
        echo "<h2>" . $title . "</h2>";
    }
    echo $message . "<br><br>";
}


function mailOrderMessage($orderId, $accountNumber) {
    global $output;

    if (intval($orderId) > 0) {
        my_mail_order($orderId, $accountNumber);
    }

    if ($accountNumber == "7777") {//Joey's account number
        sendJoeyEmail("FTP Order Processed", $output);
    }
}

function mailWebServicesAboutBadProducts () {
    global $arrBlackList, $accountNumber;
print_r($arrBlackList);
    $title = 'Bad Product Model Value(s)';
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: Kerusso Drop Shipping <kds@kerusso.com>' . "\r\n";
    $message = '';
    for($i = 0; $i < count($arrBlackList); $i++) {
        $message .= 'Found a problem with customer invoice: ' . $arrBlackList[$i] . '<br>';
        $message .= 'Account Number: ' .$accountNumber . '<br>';
        $message .= 'Product Model: ' . $arrBlackList[$i]['product_model'] . '<br>';
        $message .= '---------------------------------------------------------------<br><br>';
    }

    if (strlen($message) > 0) {
        mail("haciendadad@yahoo.com",$title,$message,$headers);
    }

}

function sendJoeyEmail($title, $message) {
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: Kerusso Drop Shipping <kds@kerusso.com>' . "\r\n";
    mail("haciendadad@yahoo.com",$title,$message,$headers);
}
?>
