

function getAllSizes($pCode, $mode, $formName){
    //alert($formName);

    var url = "ajax_controller.php?returnAll=1&action=get_allsizes&pCode="+$pCode;

    var parms = Form.serialize(document.forms[$formName]);

    var ajaxRes = new Ajax.Request(
			url,
			{
				method : 'post',
				parameters : parms,
				onComplete : onSuccessMsg
			});
}

function getSizes($pCode, $mode, $formName){
    //alert($formName);

    var url = "ajax_controller.php?returnAll=1&action=get_sizes&pCode="+$pCode;

    var parms = Form.serialize(document.forms[$formName]);

    var ajaxRes = new Ajax.Request(
			url,
			{
				method : 'post',
				parameters : parms,
				onComplete : onSuccessMsg
			});
}


function onSuccessMsg(results){
  showSizes(results.responseText);
}

function onErrorMsg(){
   alert("You order was NOT submitted because of an error.  Contact the administrator.");
}



