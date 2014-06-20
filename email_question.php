<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Email Kerusso</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<script type="text/JavaScript" src="debugInfo.js"></script>

</head>

<body >
<?php
require('navigation.php');
?>

<table align=right><tr><td><div onclick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');
?>




<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText">E M A I L &nbsp;&nbsp; Q U E S T I O N </td>
    </tr>
</table>


<br />
<br />


<?php
//*************************************************************************
//************************ PROCESS SINGLE ORDER ***************************
//*************************************************************************
if( $action == 'sq' ){
    $order_info_sql = "SELECT * FROM orders WHERE order_id=".$_POST['oId'];
    $order_info_query = my_db_query($order_info_sql);
    $order_info = my_db_fetch_array($order_info_query);

    $email_sql = "SELECT * FROM accounts WHERE accounts_number='".$_SESSION['client_account_number']."'";
    $email_query = my_db_query($email_sql);
    $email = my_db_fetch_array($email_query);

    $orderInfoText = "";
    $orderInfoText .= "** QUESTION REGARDING FOLLOWING ORDER **\n<br>";
    $orderInfoText .= "*******************************************************\n<br>";
    $orderInfoText .= "Customer Name: ".$order_info['customer_name']."\n<br>";
    $orderInfoText .= "Purchase Date: ".$order_info['purchase_date']."\n<br>";
    $orderInfoText .= "Client Invoice #: ".$order_info['customer_invoice_number']."\n<br>";
    $orderInfoText .= "*******************************************************\n\n<br><br>";

    $to = "dropship@kerussosales.com";
    //$to = "jnc@goodnewsclothing.com";
    $subject = "(".my_null_replace($order_info['accounts_number']).
    ") ".$subject;
    $headers  = "MIME-Version: 1.0 \r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1 \r\n";
    $headers .= "From: ".$email['accounts_company_name']." <".$email['accounts_email']."> \r\n";
    if($_SERVER["HTTP_HOST"] != 'jjgdesktop'){
        mail($to,$subject,$orderInfoText.$question,$headers,$parameters);
    }else{
        echo "Email sent now.";
    }
    echo "<div class=\"success\" align=\"center\">Email Sent To Kerusso</div>";

}//end of "send question, or sq"
else{

//*************************************************************************
//**************************** MAIN MENU **********************************
//*************************************************************************
?>

<?php echo my_draw_form('send_email',my_href_link('email_question.php', 'action=sq'));?>
<br /><br /><br />
<?php echo my_draw_hidden_field("orderId",$_GET['oId']); ?>
<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Subject:</td>
<td class="smallText"><?php echo my_draw_input_field('subject','','size=100'); ?></td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Message:</td>
<td>
<?php echo my_draw_textarea_field('question','soft','75','5'); ?>
</td>
</tr>


<tr class="tableFooter">
    <td colspan="2" align="center">
        <?php echo my_image_submit('btnSubmit.gif','Send Email'); ?>
    </td>
</tr>
</table>

<?php
}//end of Main Menu
?>


</form>

</body>
</html>
