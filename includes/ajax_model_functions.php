<?php
function getAccountInfoByAcctNumber($acct){
    $companyName_sql = "SELECT * FROM accounts a WHERE a.accounts_number=".$acct;
    $companyName_query = my_db_query($companyName_sql);
    $companyName_view = my_db_fetch_array($companyName_query);

    return $companyName_view;
}

function setPriceLevel($acct, $pLevel){
    $sql = "UPDATE  `kerussod_kdsdb`.`accounts` SET  `accounts_price_level` =  '". $pLevel ."' WHERE  `accounts`.`accounts_number` =" . $acct;
    $query = my_db_query($sql);

    return  my_db_affected_rows($query);
}

function getGroupIds(){
    $sql = "select distinct group_id from price_level_prices order by group_id asc";
    $query = my_db_query($sql);


    while($view = my_db_fetch_array($query) ){
        $arrGroupIds[] = $view["groupid"];
    }

    return $arrGroupIds;
}

function getPriceLevels(){
    $sql = "select distinct price_level_id from price_level_prices order by price_level_id asc";
    $query = my_db_query($sql);


    while($view = my_db_fetch_array($query) ){
        $arrPriceLevels[] = $view["price_level_id"];
    }

    return $arrPriceLevels;
}

function getPriceLevelsByGroupId($priceLevelId, $grpId){
    $sql = "select * from price_level_prices where price_level_id = '" . $priceLevelId ."' AND group_id = '" . $grpId ."' order by sort asc";
    $query = my_db_query($sql);

    $arrPriceLevelGroup["categories"] = my_db_num_rows($query);
    while($view = my_db_fetch_array($query) ){
        $groupTitle = $view["group_title"];
        $temp["category"] = $view["category"];
        $temp["price"] = $view["price"];
        $temp["description"] = $view["description"];
        $temp["table_id"] = $view["price_level_prices_id"];

        $arrPriceLevelGroup["sort_" . $view["sort"]] = $temp;
    }
     $arrPriceLevelGroup["group_title"] = $groupTitle;

    return $arrPriceLevelGroup;
}

function updatePriceLevelPrice($table_id, $column_name, $value){
    $sql = "UPDATE  `kerussod_kdsdb`.`price_level_prices` SET  $column_name =  '". $value ."' WHERE  `price_level_prices`.`price_level_prices_id` =" . $table_id;
    $query = my_db_query($sql);

    return  my_db_affected_rows($query);
}

function updateProductGroupId($table_id, $column_name, $value){
    $sql = "UPDATE  `kerussod_kdsdb`.`products` SET  $column_name =  '". $value ."' WHERE  `products`.`product_model` = '" . $table_id . "'";
    $query = my_db_query($sql);

    return  $sql;
}

function updatePriceLevelDiscount($table_id, $column_name, $value){
    $sql = "UPDATE  `kerussod_kdsdb`.`price_levels` SET  $column_name =  '". $value ."' WHERE  `price_levels`.`price_level_id` =" . $table_id;
    $query = my_db_query($sql);

    return  my_db_affected_rows($query);
}

function getReps($accounts_number) {
    $sql = "SELECT * FROM `kerussod_kdsdb`.`reps` WHERE accounts_number = '" . $accounts_number . "' ORDER BY accounts_company_name";
    $query = my_db_query($sql);

    $temp = array();
    while($reps = my_db_fetch_array($query) ){
        $temp['field_rep'] = $reps["field_rep"];
        $temp['inside_rep'] = $reps["inside_rep"];
        $temp['field_group'] = $reps["field_group"];
        $temp['national_group'] = $reps["national_group"];
        $temp['national_rep'] = $reps["national_rep"];
        $temp['sales_mgr'] = $reps["sales_mgr"];
        $temp['accounts_company_name'] = $reps["accounts_company_name"];
        $temp['accounts_number'] = $reps["accounts_number"];
    }
    return  $temp;
}

function createAccountFolder ($accounts_number) {
    $sql = sprintf("SELECT accounts_username FROM `kerussod_kdsdb`.`accounts` WHERE accounts_number = '%s'", $accounts_number);
    $query = my_db_query($sql);
    $username = my_db_fetch_array($query);
    $rv = false;

    if (isset($username)) {
        $sql = sprintf("UPDATE `kerussod_kdsdb`.`accounts` SET accounts_folder_name = '%s' WHERE accounts_number = '%s'", strtolower($username['accounts_username']), $accounts_number);
        $query = my_db_query($sql);
        $rv = true;
    }

    return $rv;
}

function deleteAccountFolder ($accounts_number) {
    $rv = false;

    $sql = sprintf("UPDATE `kerussod_kdsdb`.`accounts` SET accounts_folder_name = '' WHERE accounts_number = '%s'", $accounts_number);
    $query = my_db_query($sql);
    $rv = true;

    return $rv;
}

?>
