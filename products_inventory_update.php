<?php
require('includes/application_top.php');
?>
<?php
$hasHeaderRow = true;
$startRow = ($hasHeaderRow)? 1 : 0;
$numOfColumns = 7;
$updatedItems = 0;
//Column Variables
$productColum = 0;
$descColumn = 1;
$colorColumn = 3;
$priceColumn = 5;
$quantityColumn = 6;
$validSizes = " SM MD LG XL 2X 3X 4X 5X 6X 3T 4T 5T";
$arrSizesSort = array(
	"NA"=>0,
	"3T"=>1,
	"4T"=>2,
	"5T"=>3,
	"SM"=>4,
	"MD"=>5,
	"LG"=>6,
	"XL"=>7,
	"2X"=>8,
	"3X"=>9,
	"4X"=>10
	);

$arrSizesNameToID = array(
	"NA"=>12,
	"3T"=>2,
	"4T"=>3,
	"5T"=>4,
	"SM"=>5,
	"MD"=>6,
	"LG"=>7,
	"XL"=>8,
	"2X"=>9,
	"3X"=>10,
	"4X"=>11
	);

$overridingCategories = "# APT KDZ CGA CGK SJT ";//added space and has so strpos doesn't come back with a zero value

//Old File
//$productFeed = "http://www.kerussosales.com/deepershopping/deepershopping.txt";

//New File
$productFeed = "http://www.kerusso.com/inventory/WebSaleableInventory.csv";

$arrLines = file($productFeed, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//echo "Lines Count: " . count($arrLines);

$product_groups_sql = "SELECT product_model, product_group_id FROM products";
$query = my_db_query($product_groups_sql);
$arrProductGroups = array();
while($rs = my_db_fetch_array($query)){
	$arrProductGroups[$rs["product_model"]] = $rs["product_group_id"];
}

$insert_sql = "INSERT INTO `products_onhand` (`product_model`, `product_size`, `product_quantity`) VALUES ";
$products2_sql = "INSERT INTO `products2` (`products2_model`, `products2_size`, `products2_size_id`, `products2_size_sort`, `products2_price`, 
    		`products2_price_group_id`, `products2_description`, `products2_color`, `products2_onhand`) VALUES ";

$previousProduct = "";

$maxSize = -1;
$minSize = 20;

for($i=$startRow; $i<count($arrLines); $i++){
	$arrLine = split(",", $arrLines[$i]);
    $product = trim(str_replace("\"","",$arrLine[$productColum]));
    $size = substr($product, strlen($product)-2, 2);
    $size_id = $arrSizesNameToID[$size];
    $size_sort = $arrSizesSort[$size];
    $quantity = sprintf("%d",trim($arrLine[$quantityColumn]));
    $desc = trim(str_replace("\"","",$arrLine[$descColumn]));
	$desc =str_replace("'","\'",$desc);
	$price = trim(str_replace("\"","",$arrLine[$priceColumn]));
	$color = trim(str_replace("\"","",$arrLine[$colorColumn]));


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
    	$size_id = "12";
    }

    $products2_sql .= sprintf("('%s','%s','%d','%d','%01.2f','%d','%s','%s','%d'),", $product, $size, $size_id, $size_sort, $price, $arrProductGroups[$product], $desc, $color, $quantity);

    //Overriding Quantities for certain categories
    $firstThree =  substr($product, 0, 3);
	if( strpos($overridingCategories, $firstThree)){
		$quantity = 2000;
	}

	$insert_sql .= "('$product','$size', $quantity),";

	$updatedItems++;

}// End of For Loop
	
$insert_sql = substr($insert_sql, 0, strlen($insert_sql)-1);
if( strlen($insert_sql) > 200){
	my_db_query("TRUNCATE TABLE `products_onhand`");
	$insert_query = my_db_query($insert_sql);
}


$products2_sql = substr($products2_sql, 0, strlen($products2_sql)-1);
if(  strlen($products2_sql) > 200 ){
	my_db_query("TRUNCATE TABLE `products2`");
	$products2_query = my_db_query($products2_sql);
}

echo "Feed file used:  " . $productFeed . "<br>";
echo "Done updating products on-hand ($updatedItems) at ". date("D M j Y g:i:s A T");
	
?>