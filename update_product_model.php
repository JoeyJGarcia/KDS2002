<?php
require('includes/application_top.php');


$updateModel_sql = "SELECT * FROM orders_products";
        $updateModel_query = my_db_query($updateModel_sql);
        while($updateModel = my_db_fetch_array($updateModel_query)){
 		    $start = strpos($updateModel['order_product_name'],"[") + 1;
            $length = strpos($updateModel['order_product_name'],"]")-$start;
            $productModel = trim(substr($updateModel['order_product_name'],$start,$length));

			my_db_query("UPDATE orders_products set order_product_model = '".$productModel."' WHERE 
order_product_id = ".$updateModel['order_product_id']." ");

			//echo "Update ".mysql_num_rows($updateModel_query)." rows<BR>";
		}

	echo "Done.";
?>
