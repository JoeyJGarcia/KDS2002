<?php
require('includes/application_top.php');

$action = NULL;
	if ( isset($_POST['action']) ){
		$action = $_POST['action'];
	}elseif( isset($_GET['action']) ){
		$action = $_GET['action'];
	}


if($action == "send_email"){
	
	$returnValue = my_mail_request($_POST['customer_name'],
					$_POST['customer_invoice_number'],
					$_POST['accounts_company_name'],
					$_POST['request_details'],
					$_POST['accounts_number'],
					$_POST['purchase_order_number'],
					$_POST['order_status_id'],
					$_POST['order_id'],
					$_POST['accounts_email'],
					$_POST['email_type']
					);
					
	echo $returnValue;
}elseif($action == "get_allsizes"){

	$returnValue = getAllProductSizesArray($_GET['pCode'], true);
	
	echo $returnValue;
	
}elseif($action == "get_sizes"){
	$returnAll = ($_GET['returnAll'] == 1)? true : false;
	$returnValue = getProductSizeArray($_GET['pCode'], true, $returnAll);
	
	echo $returnValue;
	
}elseif($action == "get_sizes2"){
	$returnValue = getProductSizeArray2($_GET['pCode']);
	
	echo $returnValue;
	
}elseif($action == "get_cat_sizes"){
	echo getCategorySizesJson($_POST['categoryName']);
}elseif($action == "get_onhand"){
	echo getQuantityOnHand($_GET['size'], $_GET['pCode']);
}elseif($action == "get_onhand2"){
	echo getQuantityOnHand2($_GET['size'], $_GET['pCode']);
}elseif($action == "get_products"){
		echo getProductsJson();
}elseif($action == "set_price_level"){
	$arrAcctInfo = getAccountInfoByAcctNumber($_POST['acct']);
	$arrAcctInfo["status"] = "success";
	$arrAcctInfo["new_price_level"] =  $_POST['plevel'];
	$arrAcctInfo["updated_rows"] =  setPriceLevel($_POST['acct'], $_POST['plevel']);

	echo json_encode($arrAcctInfo);
}elseif($action == "get_price_levels"){
	$arrGroupIds = getGroupIds();
	$arrPriceLevels = getPriceLevels();
	$results["price_levels"] = count($arrPriceLevels);

	for( $p=1; $p <= count($arrPriceLevels); $p++){
		$results["PL_".$p]["groups"] = count($arrGroupIds);
		for( $g=1; $g <= count($arrGroupIds); $g++){
			$results["PL_".$p]["GRP_".$g] = getPriceLevelsByGroupId($p, $g);
		}

	}

	echo json_encode($results);
}elseif($action == "update_price_level_price"){
	$arrUpdatePrice["status"] = "success";
	$arrUpdatePrice["return_status"] =  updatePriceLevelPrice($_POST['table_id'], $_POST['column_name'], $_POST['value']);

	echo json_encode($arrUpdatePrice);
}elseif( $action == "update_product_group"){
	$arrResponse["status"] = "success";
	$arrResponse["return_status"] =  updateProductGroupId($_POST['table_id'], $_POST['column_name'], $_POST['value']);
	echo json_encode($arrResponse);
}elseif( $action == "update_price_level_discount"){
	$arrResponse["status"] = "success";
	$arrResponse["return_status"] =  updatePriceLevelDiscount($_POST['table_id'], $_POST['column_name'], $_POST['value']);
	echo json_encode($arrResponse);
}elseif( $action == "get_product_price"){
	$arrResponse["status"] = "success";
	$percent_discount = getPriceLevelDiscount($_POST['accountNumber']);
	if( $_SESSION['userlevel'] == 'super' ){
		$arrResponse["custom_price"] =  getPriceBySize($_SESSION['client_account_number'], $_POST['model'],$_POST['size']);
		$arrResponse["pl_price"] =  getPriceFromModelSize($_POST['model'], $_POST['size'], $percent_discount);
	} elseif ( $_SESSION['price_level'] == 0 ) {
		$arrResponse["custom_price"] =  getPriceBySize($_SESSION['client_account_number'], $_POST['model'],$_POST['size']);
	} else {
		$arrResponse["pl_price"] =  getPriceFromModelSize($_POST['model'], $_POST['size'], $percent_discount);
	}
	$arrResponse["onhand"] =   getQuantityOnHand2($_POST['size'], $_POST['model']);
	echo json_encode($arrResponse);
} elseif ($action == 'get_reps') { 
	
	$results = getReps($_GET['accounts_number']);

	if ($results == null) {
		$arrResponse["status"] = 'failure';
		$arrResponse["results"] =  '{}';
	} else {
		$arrResponse["status"] = 'success';
		$arrResponse["results"] =  $results;
	}
	
	echo json_encode($arrResponse);
} else {
	echo "Unknown Action";
}

?>