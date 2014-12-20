<?php
//require('includes/classes/KDSUtils.class.php');
require('includes/application_top.php');

?>

<!doctype html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<head>
	<title>Kerusso Drop Ship - Test Page</title>
	<link rel="stylesheet" href="styles.css"/>
	<script src="debugInfo.js"></script>
</head>
<body>
Result Count: 
<?php

// $kdsUtils = new KDSUtils();
// $junk = $kdsUtils->getFees();

// //$kdsJunk = $database->createNameValuePair($obj->getAccounts(), 'fees_name', 'fees_value');
// //$kdsFees = $database->createDropDownArray($obj->getAccounts(), 'fees_id', 'fees_name', array('id'=>0, 'text'=>'Select Fee'));

	$arrPriceRequest = array();
	$arrPriceRequest["price"] = getPriceBySize("51927", "LST1585 ","MD");
	$arrPriceRequest["product_size"] = "MD";
	$arrPriceRequest["productModel"] = "LST1585 ";
	$arrPriceRequest["priceLvl"] = "1";
	$arrPriceRequest["accounts_number"] = "51927"; 
	$arrPriceRequest["discount"] = checkForDiscount($arrPriceRequest);

echo "<pre>";
echo isset($arrPriceRequest["discount"]["price"]) ? "is set" : "is not set";
echo "<br>";
echo is_array($arrPriceRequest["discount"]) ? "is an array" : "is not an array";
echo "<br>";
echo $arrPriceRequest["discount"]["price"];
echo "<br>";
print_r($arrPriceRequest);
echo "</pre>";

?>
</body>
<style>
</style>
</html>