<?php
require('includes/application_top.php');
require('includes/clsOrders.php');
require('includes/clsProducts.php');
require('includes/clsForm.php');
set_time_limit (0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Bulk Orders Entry</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
  <script language="JavaScript" src="debugInfo.js"></script>
</head>


<script type="text/javascript" src="includes/js/jquery-min.js"></script>
<script type="text/javascript" src="orderValidation.js"></script>
<script type="text/javascript" src="orderSubmit.js"></script>
<script type="text/javascript" src="./prototype.js"></script>
<script type="text/javascript" src="./rico.js"></script>




<body <?php if( $_GET['action'] == 'validateXML' ) echo "onload='hideLoadingMsg()'"; ?>>
<script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('select[name*="product_name_"]').click(function (){
      selObj = this;
	  selObj.style.cursor = 'wait';
      var pId = selObj.options[selObj.selectedIndex].value;
      var productCode = selObj.options[selObj.selectedIndex].text.substring(0,3);
     	jQuery.ajax({url:'ajax_controller.php?action=get_products&kdssid=<?php echo $_GET['kdssid'];?>',
     		     	success: function(responseText){
     		     	     var result = responseText;
						 var selectedValue = selObj.options[selObj.selectedIndex].value;
						 selObj.options.length = result.arrProducts.length;
						 for(var i=0; i<result.arrProducts.length; i++){
							selObj.options[i].value=result.arrProducts[i].id;
							selObj.options[i].text=result.arrProducts[i].text;
							if( selObj.options[i].value == selectedValue){
									selObj.options.selectedIndex = i;
							}
						 }
						selObj.style.cursor = 'default';
	     		},
	     		error: function (XMLHttpRequest, textStatus, errorThrown){
	     		var one = textStatus;
	     		var two = errorThrown;
	     		var three =  XMLHttpRequest;
	     		var four = one.length;
	     			alert(textStatus);
	     		},
	     		dataType: 'json'
	     	});
      });
    });

function hideLoadingMsg(){
 document.getElementById('loadingMsg').style.display = "none";
}
</script>
<a name="top"></a>
<?php

require('navigation.php');

?>

<table align=right><tr><td><div onClick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');
?>

<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText">B U L K &nbsp;&nbsp;O R D E R S&nbsp;&nbsp; E N T R Y.</td>
    </tr>
</table>


<br />
<br />


<?php
//*************************************************************************
//************************ VALIDATE XML *********************************
//*************************************************************************
if( $_GET['action'] == 'validateXML' ){
//echo "Action: ".$_GET['action']."<br>";
//echo "Action Is Set?: ".isset($_GET['action'])."<br>";

$isValidXML = false;
if( strlen($_FILES['ordersXMLFile']['name']) > 0){
    $isUploaded = false;    
  //ordersXMLFile
        $company_name = $_SESSION['company_name'];
	$company_name = str_replace(" ","_",$company_name);
	$company_name = str_replace("/","_",$company_name);
	$fileName = $company_name."_".date("mdY_his");
	$orderFile = "/home/kerussod/public_html/order_files/".$fileName.".xml";


	if(move_uploaded_file($_FILES['ordersXMLFile']['tmp_name'], $orderFile)) {
	    echo "The file ".  basename( $_FILES['ordersXMLFile']['name']).
	    " has been uploaded successfully. <br><br>";
	    echo "<div id=\"loadingMsg\"><strong><font color=blue>Loading orders and writing order forms ... please wait.</font></strong></div>";
            $isUploaded = true;
        } else{
            $isUploaded = false;
	    echo "<span class='largeBoldErrorText' align=center> There was an error uploading the file, please try again!</span>";
	    exit;
	}
//end of if test for an file upload
}

//New Code - Begin
$handle = fopen($orderFile, "r");

if ($handle) {
    while (($buffer = fgets($handle)) !== false) {
                
        $isBigCommerceOrder = (stripos($buffer,"kerussoOrders")== false) ? TRUE : FALSE;
            
        if( !$isBigCommerceOrder ){
            break;
        }

           $xp = new XsltProcessor();

            $xsl = new DomDocument;
            $xsl->load("xsl/bigCommerceOrders.xsl");

            $xp->importStylesheet($xsl);

            $xml_doc = new DomDocument;
            $xml_doc->load($orderFile);
            
            if ($html = $xp->transformToXML($xml_doc)) {
                //echo $html;
                $handle2 = fopen($orderFile, "w");
                $fwrite_status = fwrite($handle2, $html);
                fclose($handle2);
            } else {
                trigger_error('XSL transformation failed.', E_USER_ERROR);
            } // if
            break;
        }
    
    fclose($handle);
}



//New Code - End

require('includes/OrderParser.inc');
echo "<BR><BR>";
$isValidXML = true;



//*************************************************************************
//************************ ORDER ADD FORM *********************************
//*************************************************************************
if( $isValidXML ){
    intval($order_size);

}//end of if (isValidXML) test
?>

<div style="display:none" id="processingOrderMsg" class="userMsg" align="center" >
</div>


<?php
//**********************************************************************
//******************** Loop through all orders *************************
//**********************************************************************


$form = new Form;
$orderCount = 1;
for($i=0; $i<count($arrOrders); $i++){
$orderCount = $i + 1;
	echo "<br>";
	echo "<div id=\"orderNum_$orderCount\">\n";
	echo "<div align=center class=\"largeBoldTextShaded\">Order #$orderCount</div>\n";
	echo "<br>";
	echo $form->writeAddForm($orderCount,$arrOrders[$i]);
	echo "</div>";

}//end of loop for all the orders
?>
<?php
}else{
//*************************************************************************
//*************************** MAIN MENU ******************************
//*************************************************************************


if( $_GET['action'] != 'validateXML'  ){
?>
<?php echo my_draw_form('order_xml',my_href_link('bulk_orders.php', 'action=validateXML'),"POST", "enctype='multipart/form-data' onsubmit='submitXML(); return false'");?>
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />

<span align=center ID="errMsg" class="largeBoldErrorText" style="display:none">Please submit a XML file <em>or</em> paste some XML text</span>
<table width="500" align="center" border=0>
<tr>
<td align="left" class="mediumBoldText">
Browse to upload your orders XML file from your machine.<br>

<dl>
<dt><dd><span class="mediumBoldText" align=right>Note:
<BR><font color=red>Your file&#39;s format must match the sample&#39;s format!</font>
<p>Need to see an example of the XML orders format? [See <a href="orders_sample.xml">XML Sample</a>]. It is very 
important to follow this format or there could be errors.  The format can contain one or more orders with 
unlimited number of products in each order.  Orders will be stored in your account for your review so you 
can make any changes or add comments and then you do the a final order submission.
</span>
</dl>
<br><br>
</td>
</tr>




<tr>
<td align="center" valign="top" class="mediumBoldText">
<input type="file" name="ordersXMLFile"/>&nbsp;&nbsp;
<input type=submit value="Upload XML File"/>
</td>
</tr>



<tr>
<td align=center>

</td>
</tr>

</table>

<?php
}//end of if $_GET['action'] != 'ord_add'
}
?>
</form>
</body>
</html>