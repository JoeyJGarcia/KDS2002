
function submitOrder($orderNum){


var orderDiv = document.getElementById('orderNum_'+$orderNum);
var userMsgDiv = document.getElementById('processingOrderMsg');

var country = document.forms['frmAdd_'+$orderNum][23].value;
var intlPhone = document.forms['frmAdd_'+$orderNum][24].value;

if(country != "United States" && intlPhone.length == 0){
        alert("An international phone number is required for international orders");
        return;
}


//new Rico.Effect.FadeTo(orderDiv, .25, 1500, 15 );
orderDiv.style.display = "none";

document.location.href ="#top";

oldMsg = userMsgDiv.innerHTML;
userMsgDiv.style.display = "";
userMsgDiv.innerHTML = "Submitting order #"+$orderNum+" ....";

var url = "bulk_orders_process.php";
var parms = Form.serialize(document.forms['frmAdd_'+$orderNum]);

var ajaxRes = new Ajax.Request(
                    url,
                    {
                        method : 'post',
                        parameters : parms,
                        onComplete : onSuccessMsg
                    });


}

function onSuccessMsg(results){
 var userMsgDiv = document.getElementById('processingOrderMsg');

 userMsgDiv.innerHTML = oldMsg + "<BR>" + results.responseText;

}

function onErrorMsg(){
var userMsgDiv = document.getElementById('processingOrderMsg');

 userMsgDiv.innerHTML = "You order was NOT submitted because of an error.  Contact the administrator.";

}
