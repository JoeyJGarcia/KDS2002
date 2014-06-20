<?php
require('includes/application_top.php');
?>
<?php
$hasHeaderRow = true;
$startRow = ($hasHeaderRow)? 1 : 0;
$numOfColumns = 7;
$productColum = 0;
$quantityColumn = 6;
$updatedItems = 0;
$validSizes = " SM MD LG XL 2X 3X 4X 5X 6X 3T 4T 5T";

//Old File
//$productFeed = "http://www.kerussosales.com/deepershopping/deepershopping.txt";

//New File
$productFeed = "http://www.kerusso.com/inventory/WebSaleableInventory.csv";

$arrLines = file($productFeed, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//echo "Lines Count: " . count($arrLines) . "<BR>";

$insert_sql = "INSERT INTO `products_onhand` (`product_model`, `product_size`, `product_quantity`) VALUES ";

for($i=$startRow; $i<count($arrLines); $i++){
	 $arrLine = split(",", $arrLines[$i]);
    $product = trim(str_replace("\"","",$arrLine[$productColum]));
    $size = substr($product, strlen($product)-2, 2);
    $quantity = sprintf("%d",trim($arrLine[$quantityColumn]));
	 //echo "p: ".$product ."<br>";

	// Sanity check on the rows data
	$isOK = false;
	$isOK = ( count($arrLine) == $numOfColumns )? true : false;
	if( !$isOK ) echo "Found wrong number of columns, skipping this row. <br/>";

    if ( !$isOK ){
    	continue;
    }

    if(strpos($validSizes, $size)){
    	$product = substr($product, 0, strlen($product)-2);
    }else{
    	$size = "NA";
    }

	$insert_sql .= "('$product','$size', $quantity),";

	$updatedItems++;
echo "product: ". $product .", size: " . $size . ", quantity: " . $quantity . "<br>";
}
	$insert_sql = substr($insert_sql, 0, strlen($insert_sql)-1);
	if( strlen($insert_sql) > 200){
		my_db_query("TRUNCATE TABLE `products_onhand`");
		$insert_query = my_db_query($insert_sql);
	}
	echo "Feed file used:  " . $productFeed . "<br>";
	echo "Done updating products on-hand ($updatedItems) at ". date("D M j Y g:i:s A T");
?>