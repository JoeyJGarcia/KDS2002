<?php
require('includes/application_top.php');


$db = new PDO('mysql:host=localhost;dbname=kerussod_kdsdb;charset=utf8', 'kerussod_chillie', '123456q');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$junk = array(
	"product_name" => "test1", 
	"product_model" => "test2", 
	"product_group_id" => "test3"
	);


// $product_name = "test1";
// $product_model = "test2";
// $product_group_id = "test3";


	$stmt = $db->query("INSERT  into temp  (product_name, product_model, product_group_id) values (:product_name, :product_model, :product_group_id)");

	$results = $stmt->execute($junk);



print_r($results);
echo '<br> Done <br>';

?>
<!doctype html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<head>
  <title>Kerusso Drop Ship - Test Page</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
  <script language="JavaScript" src="debugInfo.js"></script>
</head>

<style type="text/css">
</style>
<body>


</body>
</html>