<?php
require('includes/application_top.php');


if($_GET['mode'] == "id"){
    $sizes_sql = "SELECT product_avail_sizes FROM products WHERE `product_id` = ".$_GET['val'];
    $sizes_query = my_db_query($sizes_sql);
    $sizes = my_db_fetch_array($sizes_query);
    if( mysql_num_rows($sizes_query) > 0 ){
      $results = trim($sizes['product_avail_sizes']);
    }else{
      $results = "";
    }
}else{
    $model = substr($_GET['val'],0,2);
    $sizes_sql = "SELECT sizes_id, sizes_name FROM sizes WHERE `sizes_name` LIKE '".$model."%' ORDER BY sizes_sort";
    $sizes_query = my_db_query($sizes_sql);
    $results = '{"arrSizes" : [';
    while($sizes = my_db_fetch_array($sizes_query)){
         $results .=  '{ "id" : "'. $sizes['sizes_id'] . '", "name" : "'. $sizes['sizes_name'] .'"},';
    }
    $results = substr($results,0,strlen($results)-1);
    $results .= "]}";
}
echo $results;
?>