<?php
require('includes/application_top.php');
set_time_limit (0);


if( $_POST['doAction'] == "addOrder" ){

/*
		echo "Name: ".$_POST['customer_name']."<BR>";
		echo "address1: ".$_POST['customer_address1']."<BR>";
		echo "address2: ".$_POST['customer_address2']."<BR>";
		echo "city: ".$_POST['customer_city']."<BR>";
		echo "state: ".$_POST['customer_state']."<BR>";
		echo "zipcode: ".$_POST['customer_zip']."<BR>";
		echo "country: ".$_POST['customer_country']."<BR>";
		echo "orderNumber: ".$_POST['customer_order_no']."<BR>";
		echo "shippingText: ".$_POST['shipping_method']."<BR>";
		echo "shippingID: ".$_POST['shipping_method']."<BR>";
		echo "order_size: ".$_POST['order_size']."<BR>";
		echo "accounts_number: ".$_POST['accounts_number']."<BR>";
		echo "purchase_order_number: ".$_POST['purchase_order_number']."<BR>";
		$isRush = ($_POST['isRush'] == "on")? 1 : 0 ;
		echo "isRush: ".$isRush."<BR>";
		echo "order_comments: ".$_POST['order_comments']."<BR>";
*/

		for($i=0; $i<$order_size; $i++){
			echo "product_quantity: ".$_POST['product_quantity'.$i]."<br>";
			echo "product_size: ".$_POST['product_size'.$i]."<br>";
			echo "product_name: ".$_POST['product_name'.$i]."<br>";
		}


        $accountNumber = $_POST['accounts_number'];
        $accountPriceLvl = $_POST['accounts_price_level'];




        //Nullable Fields
        $customer_address2_checked =
            ( strlen($_POST['customer_address2']) > 0 )? mysql_real_escape_string($_POST['customer_address2']) : "NULL" ;
        $order_comments_checked =
            ( strlen($_POST['order_comments']) > 0 )? mysql_real_escape_string($_POST['order_comments']) : "NULL" ;
        $customer_order_no_checked =
            ( strlen($_POST['customer_order_no']) > 0 )? mysql_real_escape_string($_POST['customer_order_no']) : "NULL" ;


        //Non-Nullable fields Check
        $order_has_required_data = true;
        $order_has_required_data = ( strlen($_POST['customer_address1']) != 0 ) &&
                                    ( strlen($_POST['customer_city']) != 0 ) &&
                                    ( strlen($_POST['customer_state']) != 0 )  &&
                                    ( strlen($_POST['customer_zip']) != 0 ) &&
                                    ( strlen($_POST['customer_country']) != 0 ) &&
                                    ( strlen($_POST['customer_name']) != 0 );


        for($i=0; $i<$_POST['order_size']; $i++){
            $order_has_required_data =
            strlen($_POST['product_quantity_'.$i]) != 0 && $order_has_required_data;
        }



        if($order_has_required_data){
                $ord_sizes_sql = "SELECT * FROM sizes WHERE 1 ORDER BY sizes_sort";
                $ord_sizes_query = my_db_query($ord_sizes_sql);
                while($ord_sizes = my_db_fetch_array($ord_sizes_query)){
                    $arrSizes[$ord_sizes['sizes_id']] =  $ord_sizes['sizes_name'];
                    $arrPrices[$ord_sizes['sizes_name']] =  $ord_sizes['sizes_fee'];
                }


                $arrFees = array();
                $fees_sql = "SELECT * FROM fees ";
                $fees_query = my_db_query($fees_sql);
                while($fees = my_db_fetch_array($fees_query)){
                    $arrFees[$fees['fees_name']]= $fees['fees_value'];
                }

                $new_order_id_sql = "SELECT order_status_id FROM order_status
                WHERE order_status_name = 'New Order'";
                $new_order_id_query = my_db_query($new_order_id_sql);
                $fees = my_db_fetch_array($new_order_id_query);

                $arrShipping = array();
                $shipping_sql = "SELECT * FROM shipping ";
                $shipping_query = my_db_query($shipping_sql);
                while($shipping = my_db_fetch_array($shipping_query)){
                    $arrShipping[$shipping['shipping_id']]= $shipping['shipping_name'];
                }


				$arrStateNames = array();
				$states_query = my_db_query("SELECT state_mapping_shortName AS ShortName,state_mapping_longName
				AS LongName FROM `state_mapping` WHERE 1");
				while($states = my_db_fetch_array($states_query) ){
					$arrStateNames[ strtoupper($states['LongName']) ] = strtoupper($states['ShortName']);
				}


                $customer_city = str_replace(",","",$customer_city);

                $isRush = ($_POST['isRush'] == "on")? 1 : 0 ;
                $rushFee = ($isRush > 0)?"5":"0";

				if($shortShippingState = array_key_exists(strtoupper(trim($_POST['customer_state'])), $arrStateNames)){
					$shortShippingState = $arrStateNames[strtoupper(trim($_POST['customer_state']))];
				}else{
					$shortShippingState = $_POST['customer_state'];
				}


                $ord_add_sql = sprintf("INSERT INTO `orders` (`customer_name` ,
                `customer_address1` , `customer_address2` ,`customer_city` ,
                 `customer_state` ,
                `customer_zip` , `customer_country` , `customer_shipping_method` , `customer_shipping_id` ,
                `customer_invoice_number` , `purchase_date` , `accounts_number`,
                `purchase_order_number` , `order_comments`, `order_status`,
                `dropship_fee`,
                `handling_fee`, `isRush`, `rush_fee`, `misc_desc`, 
                `rep1_name`, 
                `rep1_code`, 
                `rep2_name`, 
                `rep2_code`, 
                `rep3_name`, 
                `rep3_code`, 
                `rep4_name`, 
                `rep4_code`, 
                `rep5_name`, 
                `rep5_code`, 
                `rep6_name`, 
                `rep6_code` ) VALUES
                (%s, %s, %s, %s, %s, %s, %s, %s, %d, %s, '".date("y-m-d h:i:s")."', %s, %s, %s, %d, %01.2f,%01.2f,%d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                "'".str_replace(",","",mysql_real_escape_string($_POST['customer_name']))."'",
                "'".str_replace(",","",mysql_real_escape_string($_POST['customer_address1']))."'",
                "'".str_replace(",","",mysql_real_escape_string($customer_address2_checked))."'",
                "'".str_replace(",","",mysql_real_escape_string($_POST['customer_city']))."'",
                "'".str_replace(",","",mysql_real_escape_string($shortShippingState))."'",
                "'".str_replace(",","",mysql_real_escape_string($_POST['customer_zip']))."'",
                "'".str_replace(",","",mysql_real_escape_string($_POST['customer_country']))."'",
                "'".str_replace(",","",mysql_real_escape_string($arrShipping[$_POST['shipping_method']]))."'",
                $_POST['shipping_method'],
                "'".str_replace(",","",$customer_order_no_checked)."'",
                "'".mysql_real_escape_string($_POST['accounts_number'])."'",
                "'".mysql_real_escape_string($_POST['purchase_order_number'])."'",
                "'".str_replace(",","",mysql_real_escape_string($order_comments_checked))."'",
                $fees['order_status_id'],
                $arrFees['Drop Ship'],$arrFees['Handling'], $isRush,$rushFee,"''",
                "'".mysql_real_escape_string($_POST['rep1_name'])."'",
                "'".mysql_real_escape_string($_POST['rep1_code'])."'",
                "'".mysql_real_escape_string($_POST['rep2_name'])."'",
                "'".mysql_real_escape_string($_POST['rep2_code'])."'",
                "'".mysql_real_escape_string($_POST['rep3_name'])."'",
                "'".mysql_real_escape_string($_POST['rep3_code'])."'",
                "'".mysql_real_escape_string($_POST['rep4_name'])."'",
                "'".mysql_real_escape_string($_POST['rep4_code'])."'",
                "'".mysql_real_escape_string($_POST['rep5_name'])."'",
                "'".mysql_real_escape_string($_POST['rep5_code'])."'",
                "'".mysql_real_escape_string($_POST['rep6_name'])."'",
                "'".mysql_real_escape_string($_POST['rep6_code'])."'");

                  my_db_query($ord_add_sql);

                  $ord_add_insert_id = my_db_insert_id( );

                  $ord_inventory_sql = "SELECT * FROM products WHERE product_enabled = 1
                  ORDER BY product_model";
                  $ord_inventory_query = my_db_query($ord_inventory_sql);
                  while($ord_inventory = my_db_fetch_array($ord_inventory_query)){
                    $productText = " [ ".$ord_inventory['product_model']." ] ".
                    substr($ord_inventory['product_name'], 0, 20);
                    $arrInventory[ $ord_inventory['product_id'] ] = $productText;
                  }

                $newOrderStatusID = 10;
                $order_history_sql = "INSERT into orders_history (order_id,
                order_history_date, order_history_status)
                VALUES ('$ord_add_insert_id','".date("y-m-d h:i:s")."',
                $newOrderStatusID)";
                $order_history_query = my_db_query($order_history_sql);


                for($i=0; $i<$_POST['order_size']; $i++){


		            $start = strpos($updateModel['order_product_name'],"[") + 2;
		            $length = strpos($arrInventory[$_POST['product_name_'.$i]],"]")-$start;
		            $productModel = trim(substr($arrInventory[$_POST['product_name_'.$i]],$start,$length));

					if(strpos($_POST['product_size_'.$i], '-')){
						$arrTemp = explode("-",$_POST['product_size_'.$i]);
						$generic_size = trim( $arrTemp[1]) ;
					}else{
						$generic_size = $arrSizes[$_POST['product_size_'.$i]];
					}
					

                    $arrPriceRequest = array();
                    $arrPriceRequest["price"] = getPriceBySize($accountNumber, $productModel, $generic_size);
                    $arrPriceRequest["product_size"] = $generic_size;
                    $arrPriceRequest["productModel"] = $productModel;
                    $arrPriceRequest["priceLvl"] = $accountPriceLvl;
                    $arrPriceRequest["accounts_number"] = $accountNumber; 
                    $arrPriceRequest["discount"] = checkForDiscount($arrPriceRequest);

                    if ( is_array($arrPriceRequest["discount"]) && isset($arrPriceRequest["discount"]["price"]) && 
                        (floatval($arrPriceRequest["discount"]["price"]) < floatval($arrPriceRequest["price"])) ) {
                        $productPrice = $arrPriceRequest["discount"]["price"];
                    } else {
                        $productPrice = $arrPriceRequest["price"];
                    }


                    $ord_add_product_sql = sprintf("INSERT INTO
                    `orders_products` (`order_id` ,`order_product_quantity` ,
                    `order_product_size` , `order_product_name`, `order_product_model`,
					`order_product_charge`
                     )VALUES (%d, %d, %s, %s, %s, %f)", $ord_add_insert_id,
                     $_POST['product_quantity_'.$i],
                    "'".strtoupper(mysql_real_escape_string($_POST['product_size_'.$i]))."'",
                    "'".mysql_real_escape_string(trim($arrInventory[$_POST['product_name_'.$i]]))."'",
                    "'".$productModel."'", $productPrice);

echo 'Product Price: '.getPriceBySize($_POST['accounts_number'],$productModel, $generic_size) ."<br>";

                    my_db_query($ord_add_product_sql);
                }


                if( $ord_add_insert_id != 0 ){
                    $orderNum = (strlen($customer_order_no_checked) > 0)?"<small>(Order No.: ".
                    $customer_order_no_checked.")</small>":"";
                    echo "<div align=center class=\"success\">Order
                    Submitted Successfully for ".$_POST['customer_name']." ".$orderNum."</div>";
                    echo "<div align=center class=\"smallText\">A copy will be
                    emailed to you for your records</div>";

                    my_mail_order($ord_add_insert_id,$client_account_number);

                }else{
                    echo "<div align=center class=\"fail\">Order Not Submitted</div>";
                }
        }else{
            echo "<div align=center class=\"fail\">Order Not Submitted!<br/>
            Empty Required Field(s) Found!</div>";
            echo "<div align=center class=\"fail\">";
            if( strlen($_POST['customer_address1']) == 0 ) echo "<br>Customer Address";
            if( strlen($_POST['customer_city']) == 0 ) echo "<br>Customer City";
            if( strlen($_POST['customer_state']) == 0 ) echo "<br>Customer State";
            if( strlen($_POST['customer_zip']) == 0 ) echo "<br>Customer Zip";
            if( strlen($_POST['customer_country']) == 0 ) echo "<br>Customer Country";
            if( strlen($_POST['customer_name']) == 0 ) echo "<br>Customer Name";

            for($i=0; $i<$order_size; $i++){
                $temp = $i + 1;
                if( strlen($_POST['product_quantity_'.$i]) == 0 )
                    echo "<br>Product Quantity #$temp was empty";
            }
            echo "</div>";
        }


}else{

 echo "no action taken!";

}




?>