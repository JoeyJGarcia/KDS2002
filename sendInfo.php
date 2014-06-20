<html>
<head>
<title>Simple Test</title>
<script language="javascript1.2">



function sendMsg(num){
	var msgDiv = document.getElementById('msgNum_'+num);
	var userMsgDiv = document.getElementById('progressMsg');


	msgDiv.style.display = "none";

	userMsgDiv.style.display = "";
	userMsgDiv.innerHTML = "Sending message #"+num+" ....";

	var url = "sendBack.php";
	//var parms = "doAction=sendNamesBack&msgNum="+num;
	var parms = Form.serialize(document.forms['jjgForm']);

	//var loader = new net.ContentLoader(url,onSuccessMsg,onErrorMsg,"POST",strParms);
	
	var ajaxRes = new Ajax.Request(
						url,
						{
							method : 'post',
							parameters : parms,
							onComplete : showResultsMsg
						});

}

function showResultsMsg(results){
	var userMsgDiv = document.getElementById('progressMsg');

	//userMsgDiv.innerHTML = results.responseText;
	$('progressMsg').innerHTML  = results.responseText;
}




function onSuccessMsg(request){
 var userMsgDiv = document.getElementById('progressMsg');

 userMsgDiv.innerHTML = "You message was successfully processed.";
 
 userMsgDiv.innerHTML = request.responseXML;


}

function onErrorMsg(){
	var userMsgDiv = document.getElementById('progressMsg');

	userMsgDiv.innerHTML = "Your message was NOT completed because of an error.  Contact the administrator.";
}

function stillThinkingMsg(){
	var userMsgDiv = document.getElementById('progressMsg');

	userMsgDiv.innerHTML = "Still waiting ...";
}



</script>

<script language="javascript1.2" src="./prototype.js"/>

</head>
<body>
<div id="progressMsg">

</div>

<div id="msgNum_1">
<form name="jjgForm">

<input type=text name="fname" value="Joey" >
<input type=text name="lname" value="Garcia" >
<input type=button value="Send" onclick="sendMsg(1)" >

</form>
</div>
</body>

</html>