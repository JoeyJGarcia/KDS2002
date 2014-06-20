<?php
require('includes/application_top.php');
?>
<?php
$hasHeaderRow = true;
$startRow = ($hasHeaderRow)? 1 : 0;
$numOfColumns = 3;
//Column Variables
$productColum = 0;
$descColumn = 1;
$quantityColumn = 2;

$validSizes = " SM MD LG XL 2X 3X 4X 5X 6X 3T 4T 5T";
$sql = array();
$productFeed = "http://www.kerusso.com/discontinued-inventory/file/Discon_List.csv";

$arrLines = file($productFeed, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//echo "Lines Count: " . count($arrLines);

for($i=$startRow; $i<count($arrLines); $i++){

	$arrLine = split(",", $arrLines[$i]);
    $product = trim(str_replace("\"","",$arrLine[$productColum]));

    $size = substr($product, strlen($product)-2, 2);

    if( strpos($validSizes, $size) ){
    	$product = substr($product, 0, strlen($product)-2);
    }else{
    	$size = "NA";
    }

	// Sanity check on the rows data
	$isOK = false;
    $isOK = ( strpos($product, "-B") )? false : true;
    if ( !$isOK ){
    	echo "Found duplicate row ($product), skipping this row. <br/>";
    	continue;
    }


    $products2_sql = sprintf("DELETE FROM PRODUCTS2 WHERE product_model = '%s' AND product_size = '%s'", $product, $size);
    $rowNum = $i + 1;
echo $rowNum . ") " . $products2_sql . "<br>";

	$sql[] = $products2_sql;
}// End of For Loop

$aff_rows = 0;
foreach($sql as $current_sql) {
	$query = mysql_query($current_sql); 
	$aff_rows = $aff_rows + my_db_affected_rows($query);
}

echo "Feed file used:  " . $productFeed . "<br>";
echo "Done removing discontinued products (removed $aff_rows) on ". date("D M j Y g:i:s A T");
	
?>