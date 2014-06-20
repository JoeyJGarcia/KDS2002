function submitXML(){

	if(document.forms[0].ordersXMLFile.value.length < 10 && 
		document.forms[0].ordersXMLText.value.length < 10 ){
		document.getElementById('errMsg').style.display = "";

  	}else{
		document.getElementById('errMsg').style.display = "none";
		document.forms[0].submit();
  	}
}

function submitAddOrder(){
$orderIsReady = true;

    //Customer Name Validation
    if( document.forms[0].customer_name.value.length == 0 ){
        alert("Missing value found for a Customer Name.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_name.focus();
        $orderIsReady = false;
        return;
    }



    //Customer Address 1 Validation
    if( document.forms[0].customer_address1.value.length == 0 ){
        alert("Missing value found for a Customer Address Information.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_address1.focus();
        $orderIsReady = false;
        return;
    }


    //Customer City Validation
    if( document.forms[0].customer_city.value.length == 0 ){
        alert("Missing value found for a Customer City.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_city.focus();
        $orderIsReady = false;
        return;
    }

    //Customer City State
    if( document.forms[0].customer_state.value.length == 0 ){
        alert("Missing value found for a Customer State.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_state.focus();
        $orderIsReady = false;
        return;
    }


    //Customer City Zip
    if( document.forms[0].customer_zip.value.length == 0 ){
        alert("Missing value found for a Customer Zip.  \n Please fix field value and then re-submit.\nReminder: An asterisk (*) denotes required fields.");
        document.forms[0].customer_zip.focus();
        $orderIsReady = false;
        return;
    }


    $orderSize = parseInt(document.forms[0].order_size.value);
    for($i=0; $i<$orderSize; $i++){

        //validate quantities are not empty fields
        $quantity = eval("document.forms[0].product_quantity_"+$i+".value");
        if( $quantity.length == 0 ){
            alert("Empty quantities are not allowed.  Please check the quantity fields.");
            eval("document.forms[0].product_quantity_"+$i+".focus()");
            $orderIsReady = false;
            return;
        }

        //validate sizes match the corresponding products
        $sIndex = parseInt(eval("document.forms[0].product_size_"+$i+".selectedIndex"));
        $productSize = eval("document.forms[0].product_size_"+$i+".options["+$sIndex+"].text").replace(" ","");

        $sIndex = parseInt(eval("document.forms[0].product_name_"+$i+".selectedIndex"));
        $productName = eval("document.forms[0].product_name_"+$i+".options["+$sIndex+"].text");

        $arrProductSizeCode = $productSize.split("-");


        if( !($productName.indexOf($arrProductSizeCode[0]) >= 0) &&
                $arrProductSizeCode[0].indexOf("NA") == -1){
            $msg = "Size: " + $productSize + " doesn't go with Product: "+$productName + "\nYou need to match the size with the appropriate product or use NA for hats, gifts, etc....\nExample: Match a youth size with youth product, not an adult product."
            alert($msg);
            $orderIsReady = false;
            return;
        }

    }

    if(  document.forms[0].customer_order_no.value.length == 0 ){
        var orderOK = confirm("You don't have a value for the Order Number.  \nIf this is correct click OK, otherwise click Cancel.");
        if(orderOK){
            $orderIsReady = true;
        }else{
            $orderIsReady = false;
            document.forms[0].customer_order_no.focus();
            return;
        }
    }


    if($orderIsReady){
        document.forms[0].submit();
    }

}//end of submitAddOrder function
