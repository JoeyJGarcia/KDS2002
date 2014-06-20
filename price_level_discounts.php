<?php
require('includes/application_top.php');
?>
<!DOCTYPE html >

<head>
  <title>Kerusso Drop Ship - Price Level Discounts</title>
	<link rel="stylesheet" href="styles.css" type="text/css"/>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script language="JavaScript" src="includes/js/mustache.js"></script>
	<script type="text/JavaScript">
		$(document).ready(function(){
			$("input[type=text]").change(function(el){
				if(  parseInt(el.target.value) ){
					updatePriceLevelDiscount (el);
				}
			});
		});

	function updatePriceLevelDiscount(priceElement){
	    var options, priceData = {};

	    priceData.table_id = priceElement.target.dataset.table_id;
	    priceData.column_name = priceElement.target.dataset.column_name;
	    priceData.value = priceElement.target.value;
	    priceData.action = "update_price_level_discount";
	    options = {
	        type: "POST",
	        dataType: "json",
	        url: "ajax_controller_dev.php",
	        data: priceData,
	        success: function(data, status, jqXHR){
	            console.log("Status: " + data.status);
	            console.log("Return Status: " + data.return_status);
	            var message = "Updated Price Level field successfully. ";                 
	            setUserMessage(message, "ajax_success");
	        },
	        error: function(jqXHR, textStatus, errorThrown ){
	            console.log("Status: Error: " + jqXHR.responseText);
	            console.log(jqXHR);
	            console.log(textStatus);
	            console.log(errorThrown);
	            var message = "Update attempt failed ";
	            setUserMessage(message, "ajax_fail");
	        }
	    };

	    $.ajax(options);
	}	

	function setUserMessage(msg, status){
	    $("#user_message").removeClass(status);
	    $("#user_message").html(msg);
	    $("#user_message").css("display","block");
	    $("#user_message").addClass(status);
	    $("#user_message").fadeOut(5000, "swing", function(){
	        $("#user_message").html("");
	        $("#user_message").css("display","block");
	        $("#user_message").removeClass(status);
	    });
	}	
	</script>
	<script type="text/JavaScript" src="debugInfo.js"></script>
</head>

<body>
<?php
require('navigation.php');
?>
<table align=right><tr><td><div onClick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');
?>


<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText">P R I C E &nbsp;&nbsp; L E V E L &nbsp; &nbsp; D I S C O U N T S</td>
    </tr>
    <tr>
        <td colspan=3 align="center" style="height:30px;"><div  class="user_message" id="user_message"></div></td>
    </tr>
</table>


<br />
<br />
<form>


<?php
    echo "<table width=400 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=2>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "\t<th>Price Level</th>\n";
    echo "\t<th>Discount</th>\n";
	echo "</tr>\n";

    $sql = "SELECT * FROM price_levels WHERE 1 ";
    $query = my_db_query($sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    while($resultset = my_db_fetch_array($query)){

        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";

        echo "<tr class=$bgcolor>";
        echo "<td align=center>".$resultset['price_level']."</td>";
        echo "<td align=center><input type=\"text\" size=5 value=\"".$resultset['price_level_discount']."\"
         data-table_id=\"".$resultset['price_level_id']."\" data-column_name=\"price_level_discount\" >%</td>";
        echo "</tr>\n";
        $count++;
    }
    echo "</table>\n";

?>



</form>
</body>
</html>
