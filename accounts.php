<?php
require('includes/application_top.php');

$tableName = "accounts";
$displayName = "Accounts";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage Accounts</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script language="javascript">
    $(document).ready(function(){
        $("select.price_level").change(function(el){
            savePriceLevel(el.currentTarget)
        })
    });

    function savePriceLevel(selElement){
        var options, sel = {};

        sel.acct = selElement.name;
        sel.plevel = selElement.value;
        sel.action = "set_price_level";

        options = {
            type: "POST",
            dataType: "json",
            url: "ajax_controller.php",
            data: sel,
            success: function(data, status, jqXHR){
                console.log("Status: " + data.status);
                console.log("Updated Rows: " + data.updated_rows);
                console.log("Origin  Price Level: " + data.accounts_price_level);
                var message = "Updated " + data.accounts_company_name + " price level from " + data.accounts_price_level + " to " + data.new_price_level;                 
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
        /*$("#user_message").fadeOut(5000, "swing", function(){
            $("#user_message").html("");
            $("#user_message").css("display","block");
            $("#user_message").removeClass(status);
        });
        */
    }

    function confirmDelete(){
        if(confirm("You are about to make a deletion, continue?")){
            return true;
        }else{
            return false;
        }

    }

    function saveAccount(){

        $accountIsOk = true;
        $company_name = document.forms[0].accounts_company_name.value;
        var badCompName = "!@#$%^&*()+=-[]\\\';,/{}|\":<>?";
        var badUserName = " !@#$%^&*()+=-[]\\\';,./{}|\":<>?";
        var badEmail = " !#$%^&*()+=[]\\\';,/{}|\":<>?";

        for (var i = 0; i < document.forms[0].accounts_company_name.value.length; i++) {
            if (badCompName.indexOf(document.forms[0].accounts_company_name.value.charAt(i)) != -1) {
            alert ("The Company Name has special characters that are not allowed. \n Please remove them and try again. \n Special characters that are not allowed: !@#$%^&*()+=-[]\\\';,./{}|\":<>?");
            document.forms[0].accounts_company_name.select();
            return false;
        }
       }

        for (var i = 0; i < document.forms[0].accounts_username.value.length; i++) {
            if (badUserName.indexOf(document.forms[0].accounts_username.value.charAt(i)) != -1) {
            alert ("The username has special characters that are not allowed. \n Please remove them and try again. \n Special characters that are not allowed: !@#$%^&*()+=-[]\\\';,./{}|\":<>? and spaces");
            document.forms[0].accounts_username.select();
            return false;
        }
       }

        for (var i = 0; i < document.forms[0].accounts_email.value.length; i++) {
            if (badEmail.indexOf(document.forms[0].accounts_email.value.charAt(i)) != -1) {
            alert ("The Email has special characters that are not allowed. \n Please remove them and try again. \n Special characters that are not allowed: !@#$%^&*()+=-[]\\\';,./{}|\":<>? and spaces");
            document.forms[0].accounts_email.select();
            return false;
        }
       }


    if(document.forms[0].accounts_username.value.length > 29){
        alert("The username is too long. \n Please make it shorter and try again.");
            document.forms[0].accounts_username.select();
            return false;
    }

    if(document.forms[0].accounts_company_name.value.length == 0){
        alert("Company name is required.  Please try again.");
        document.forms[0].accounts_company_name.select();
        return false;
    }

    if(document.forms[0].accounts_address1.value.length == 0){
        alert("Address is required.  Please try again.");
        document.forms[0].accounts_address1.select();
        return false;
    }

    if(document.forms[0].accounts_city.value.length == 0){
        alert("City is required.  Please try again.");
        document.forms[0].accounts_city.select();
        return false;
    }

    if(document.forms[0].accounts_state.value.length == 0){
        alert("State is required.  Please try again.");
        document.forms[0].accounts_state.select();
        return false;
    }

    if(document.forms[0].accounts_zip.value.length == 0){
        alert("Zip is required.  Please try again.");
        document.forms[0].accounts_zip.select();
        return false;
    }

    if(document.forms[0].accounts_phone.value.length == 0){
        alert("Phone is required.  Please try again.");
        document.forms[0].accounts_phone.select();
        return false;
    }

    if(document.forms[0].accounts_email.value.length == 0){
        alert("Email is required.  Please try again.");
        document.forms[0].accounts_email.select();
        return false;
    }

    if(document.forms[0].accounts_prefix.value.length == 0){
        alert("Prefix is required.  Please try again.");
        document.forms[0].accounts_prefix.select();
        return false;
    }

    if(document.forms[0].accounts_number.value.length == 0){
        alert("Account Number is required.  Please try again.");
        document.forms[0].accounts_number.select();
        return false;
    }

    if(document.forms[0].accounts_dropship_fee.value.length == 0){
        alert("A Drop Ship Fee is required.  Please try again.");
        document.forms[0].accounts_dropship_fee.select();
        return false;
    }

    if(document.forms[0].accounts_handling_fee.value.length == 0){
        alert("A Handling Fee is required.  Please try again.");
        document.forms[0].accounts_drop_ship_fee.select();
        return false;
    }

    if(document.forms[0].accounts_username.value.length == 0){
        alert("Username is required.  Please try again.");
        document.forms[0].accounts_username.select();
        return false;
    }

    if(document.forms[0].accounts_password.value.length == 0){
        alert("Password is required.  Please try again.");
        document.forms[0].accounts_password.select();
        return false;
    }

    document.forms[0].submit();

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
        <td colspan=3 align="center" class="largeBoldText">A C C O U N T S</td>
    </tr>
    <tr>
        <td colspan=3 align="center" style="height:30px;"><div  class="user_message" id="user_message"></div></td>
    </tr>
</table>


<br />
<br />



<?php
//*************************************************************************
//****************************** ADD FORM *********************************
//*************************************************************************
if( $_GET['action'] == 'add_start' ){
    echo my_draw_form('add_'.$tableName,my_href_link($tableName.'.php', 'action=add'));
?>


<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Add <?php echo $displayName; ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Company Name:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_company_name','','size=30'); ?>*
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Address:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_address1','','size=30'); ?>*
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Add'l Address:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_address2','','size=30'); ?>
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">City:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_city','','size=30'); ?>*
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">State:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_state','','size=15'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Zip:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_zip','','size=15'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Country:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_country ','','size=15'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Phone:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_phone','','size=15'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Fax:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_fax','','size=15'); ?>
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Email:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_email','','size=30'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">URL:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_url','','size=30'); ?>
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Contact Name:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_poc','','size=30'); ?>
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Username:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_username','','size=30'); ?>
<font color=red><strong>*</strong>Unique!</font>
</td>
</tr>



<tr class="tableRowColor">
<td align=right class="mediumBoldText">Order Folder Name:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_folder_name','','size=30'); ?>
<font color=red>Unique!</font>
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Prefix:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_prefix','','size=5'); ?>*
</td>
</tr>




<tr class="tableRowColor">
<td align=right class="mediumBoldText">Ship ID:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_ship_id','','size=5'); ?>
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Account Number:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_number','','size=15');
?><font color=red><strong>*</strong>Unique!</font>
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Initial Password:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_password','','size=15'); ?>*
<br />User will have to change on initial login
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">User Level:</td>
<td class="smallText"><?php
$arrUserLevels = array();
$arrUserLevels[] = array('id'=>'0', 'text'=>'Client');
$arrUserLevels[] = array('id'=>'1', 'text'=>'Admin');
if( $userlevel == 'super' ){
    $arrUserLevels[] = array('id'=>'2', 'text'=>'Super');
}

echo my_draw_pull_down_menu('user_level', $arrUserLevels, '0');

?>*
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Rep. Group:</td>
<td class="smallText">
<?php
	$arrRepGroups = array();
	$arrRepGroupNames = array();
	$rep_groups_sql = "SELECT * FROM rep_groups";
	$rep_groups_query = my_db_query($rep_groups_sql);
	while( $rep_groups = my_db_fetch_array($rep_groups_query) ){
	    $arrRepGroups[] = array('id'=>$rep_groups['rep_groups_id'], 'text'=>$rep_groups['rep_groups_name']);
	    $arrRepGroupNames[$rep_groups['rep_groups_id']] = $rep_groups['rep_groups_name'];
	}
	
	$rep_groups_default_sql = "SELECT rep_groups_id FROM rep_groups WHERE rep_groups_default=1";
	$rep_groups_default_query = my_db_query($rep_groups_default_sql);
	$rep_groups_default = my_db_fetch_array($rep_groups_default_query);
	
	if($_SESSION['userlevel'] == 'super' ){
		// echo my_draw_pull_down_menu('accounts_rep_group', $arrRepGroups, $rep_groups_default['rep_groups_id'])."*";	
        echo $arrRepGroupNames[$rep_groups_default['rep_groups_id']];
        echo my_draw_hidden_field('accounts_rep_group', $rep_groups_default['rep_groups_id']);  
	}else{
		echo $arrRepGroupNames[$rep_group];
		echo my_draw_hidden_field('accounts_rep_group', $rep_group);  
	}  
?>
</td>
</tr>



<tr class="tableRowColor">
<td align=right class="mediumBoldText">Terms Method:</td>
<td class="smallText"><?php

    $arrTermCodes = array();
    $defaultTerm = "";
    $term_codes_sql = "SELECT * FROM term_codes ";
    $term_codes_query = my_db_query($term_codes_sql);
    while( $term_codes = my_db_fetch_array($term_codes_query) ){
        $arrTermCodes[] = array('id'=>$term_codes['term_code'], 'text'=>$term_codes['term_name']);
        if ($term_codes['term_codes_default'] == 1){
            $defaultTerm = $term_codes['term_code'];
        }
    }

echo my_draw_pull_down_menu('accounts_term_code', $arrTermCodes, $defaultTerm);

?>*
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Drop Ship Fee:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_dropship_fee','','size=5'); ?>*
<br />
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Handling Fee:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_handling_fee','','size=5'); ?>*
<br />
</td>
</tr>


<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
         <?php echo "<a href=\"#\" onClick='saveAccount();return true'>" .
        my_image(DIR_WS_IMAGES.'btnSubmit.gif','Save Account'); ?></a>
    </td>
</tr>

</table>

<?php
//*************************************************************************
//*************************** MODIFY FORM ******************************
//*************************************************************************
}elseif( $_GET['action'] == 'mod_start' ){

    $accounts_mod_sql = "SELECT * FROM ". $tableName ." WHERE ". $tableName ."_id=".$_GET['aId'];
    $accounts_mod_query = my_db_query($accounts_mod_sql);
    $accounts_mod = my_db_fetch_array($accounts_mod_query);
?>


<?php echo my_draw_form('mod_'.$tableName,my_href_link($tableName.'.php', 'action=mod'));?>
<?php echo my_draw_hidden_field('accounts_id',$_GET['aId']);?>


<table width=400px align="center" border=0  class="thinOutline" cellspacing=0>
<tr class="tableHeader">
<th align=center colspan=2>Modify <?php echo $displayName; ?> Form</th>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Company&nbsp;Name:</td>
<td valign=top class="smallText"><?php echo my_draw_input_field($tableName.'_company_name',$accounts_mod['accounts_company_name'],'size=30'); ?>*
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Address:</td>
<td valign=top class="smallText"><?php echo my_draw_input_field($tableName.'_address1',$accounts_mod['accounts_address1'],'size=30'); ?>*
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Add'l&nbsp;Address:</td>
<td valign=top class="smallText"><?php echo my_draw_input_field($tableName.'_address2',$accounts_mod['accounts_address2'],'size=30'); ?>
</td>
</tr>

<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">City:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_city',$accounts_mod['accounts_city'],'size=30'); ?>*
</td>
</tr>

<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">State:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_state',$accounts_mod['accounts_state'],'size=15'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">Zip:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_zip',$accounts_mod['accounts_zip'],'size=15'); ?>*
</td>
</tr>



<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">Country:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_country',$accounts_mod['accounts_country'],'size=15'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">Phone:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_phone',$accounts_mod['accounts_phone'],'size=15'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">Fax:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_fax',$accounts_mod['accounts_fax'],'size=15'); ?>
</td>
</tr>

<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">Email:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_email',$accounts_mod['accounts_email'],'size=30'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">URL:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_url',$accounts_mod['accounts_url'],'size=30'); ?>
</td>
</tr>


<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">P.O.C.:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_poc',$accounts_mod['accounts_poc'],'size=30'); ?>
</td>
</tr>


<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">Username:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_username',$accounts_mod['accounts_username'],'size=15'); ?>*
</td>
</tr>



<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">Order Folder Name:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_folder_name',$accounts_mod['accounts_folder_name'],'size=15'); ?>
</td>
</tr>


<tr class="tableRowColor">
<td valign=top align=right class="mediumBoldText">Prefix:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_prefix',$accounts_mod['accounts_prefix'],'size=5'); ?>*
</td>
</tr>




<tr class="tableRowColor">
<td align=right class="mediumBoldText">Ship&nbsp;ID:</td>
<td valign=top class="smallText"><?php echo my_draw_input_field($tableName.'_ship_id',my_null_replace($accounts_mod['accounts_ship_id']),'size=5'); ?>*
</td>
</tr>


<tr class="tableRowColor">
<td align=right valign=top class="mediumBoldText">Account&nbsp;Number:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_number',$accounts_mod['accounts_number'],'size=15'); ?>*
</td>
</tr>

<tr class="tableRowColor">
<td align=right  valign=top class="mediumBoldText">User&nbsp;Level:</td>
<td class="smallText"><?php

$arrUserLevels = array();
$arrUserLevels[] = array('id'=>'0', 'text'=>'Client');
$arrUserLevels[] = array('id'=>'1', 'text'=>'Admin');
if($_SESSION['userlevel'] == 'super' ){
    $arrUserLevels[] = array('id'=>'2', 'text'=>'Super');
}

$login_mod_sql = "SELECT * FROM login WHERE login_to_accounts_id=".$_GET['aId'];
$login_mod_query = my_db_query($login_mod_sql);
$login_mod = my_db_fetch_array($login_mod_query);


echo my_draw_pull_down_menu('user_level', $arrUserLevels, $login_mod['login_userlevel']);
?>*
</td>
</tr>


<tr class="tableRowColor">
<td align=right valign=top class="mediumBoldText">Rep.&nbsp;Group:</td>
<td class="smallText">
<?php
	$arrRepGroups = array();
	$arrRepGroupNames = array();
	$rep_groups_sql = "SELECT * FROM rep_groups ";
	$rep_groups_query = my_db_query($rep_groups_sql);
	while( $rep_groups = my_db_fetch_array($rep_groups_query) ){
	    $arrRepGroups[] = array('id'=>$rep_groups['rep_groups_id'], 'text'=>$rep_groups['rep_groups_name']);
	    $arrRepGroupNames[$rep_groups['rep_groups_id']] = $rep_groups['rep_groups_name'];
	}
	
	$rep_groups_mod_sql = "SELECT accounts_rep_group FROM ".$tableName." WHERE ".$tableName."_id=".$_GET['aId'];
	$rep_groups_mod_query = my_db_query($rep_groups_mod_sql);
	$rep_groups_mod = my_db_fetch_array($rep_groups_mod_query);
	
	
if($userlevel == 'super' ){
	echo my_draw_pull_down_menu('accounts_rep_group', $arrRepGroups, $rep_groups_mod['accounts_rep_group'])."* <br>Rep Group changes require this user to logout and login to take affect.";	
}else{
	echo $arrRepGroupNames[$rep_groups_mod['accounts_rep_group']];
	echo my_draw_hidden_field('accounts_rep_group', $rep_groups_mod['accounts_rep_group']);  
}  
?>
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Terms Method:</td>
<td class="smallText"><?php

    $arrTermCodes = array();
    $defaultTerm = "";
    $term_codes_sql = "SELECT * FROM term_codes ";
    $term_codes_query = my_db_query($term_codes_sql);
    while( $term_codes = my_db_fetch_array($term_codes_query) ){
        $arrTermCodes[] = array('id'=>$term_codes['term_code'], 'text'=>$term_codes['term_name']);
    }

    $term_codes_mod_sql = "SELECT * FROM accounts WHERE accounts_id = ".$_GET['aId'];
    $term_codes_mod_query = my_db_query($term_codes_mod_sql);
    $term_codes_mod = my_db_fetch_array($term_codes_mod_query);
    $defaultTerm = $term_codes_mod['accounts_term_code'];



echo my_draw_pull_down_menu('accounts_term_code', $arrTermCodes, $defaultTerm);

?>*
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Drop Ship Fee:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_dropship_fee',$accounts_mod['accounts_dropship_fee'],'size=5'); ?>*
<br />
</td>
</tr>


<tr class="tableRowColor">
<td align=right class="mediumBoldText">Handling Fee:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_handling_fee',$accounts_mod['accounts_handling_fee'],'size=5'); ?>*
<br />
</td>
</tr>



<tr class="tableRowColor">
<td align=center valign=top colspan=2><hr width=100% />
<span class="smallText">To reset passwords, provide new password and check the box below to force a password change.</span>
</td>
</tr>

<tr class="tableRowColor">
<td align=right class="mediumBoldText">Reset Password:</td>
<td class="smallText"><?php echo my_draw_checkbox_field($tableName.'_reset_password'); ?>
</td>
</tr>


<tr class="tableRowColor">
<td align=right valign=top class="mediumBoldText">New Password:</td>
<td class="smallText"><?php echo my_draw_input_field($tableName.'_new_password','','size=15'); ?><br/>
</td>
</tr>


<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <a href="<?php echo my_href_link($tableName.'.php'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
        <?php echo my_image_submit('spacer.gif','','10','1'); ?>
        <?php echo my_image_submit('btnSubmit.gif','Submit Addition'); ?>
    </td>
</tr>

</table>


<?php
}else{

//*************************************************************************
//****************************** COMMIT ADD *******************************
//*************************************************************************
    if( $_GET['action'] == 'add'  ){


        $_POST['accounts_company_name'] =
        ( strlen($_POST['accounts_company_name']) > 0 )? "'".htmlspecialchars($_POST['accounts_company_name'])."'" : "NULL" ;
        $_POST['accounts_address1'] =
        ( strlen($_POST['accounts_address1']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_address1'])."'" : "NULL" ;
        $_POST['accounts_address2'] =
        ( strlen($_POST['accounts_address2']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_address2'])."'" : "NULL" ;
        $_POST['accounts_city'] =
        ( strlen($_POST['accounts_city']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_city'])."'" : "NULL" ;
        $_POST['accounts_state'] =
        ( strlen($_POST['accounts_state']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_state'])."'" : "NULL" ;
        $_POST['accounts_zip'] =
        ( strlen($_POST['accounts_zip']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_zip'])."'" : "NULL" ;
        $_POST['accounts_country'] =
        ( strlen($_POST['accounts_country']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_country'])."'" : "NULL" ;
        $_POST['accounts_phone'] =
        ( strlen($_POST['accounts_phone']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_phone'])."'" : "NULL" ;
        $_POST['accounts_fax'] =
        ( strlen($_POST['accounts_fax']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_fax'])."'" : "NULL" ;
        $_POST['accounts_email'] =
        ( strlen($_POST['accounts_email']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_email'])."'" : "NULL" ;
        $_POST['accounts_url'] =
        ( strlen($_POST['accounts_url']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_url'])."'" : "NULL" ;
        $_POST['accounts_poc'] =
        ( strlen($_POST['accounts_poc']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_poc'])."'" : "NULL" ;
        $_POST['accounts_username'] =
        ( strlen($_POST['accounts_username']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_username'])."'" : "NULL" ;
        $_POST['accounts_folder_name'] =
        ( strlen($_POST['accounts_folder_name']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_folder_name'])."'" : "NULL" ;
        $_POST['accounts_prefix'] =
        ( strlen($_POST['accounts_prefix']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_prefix'])."'" : "NULL" ;
        $_POST['accounts_ship_id'] =
        ( strlen($_POST['accounts_ship_id']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_ship_id'])."'" : "NULL" ;
        $_POST['accounts_number'] =
        ( strlen($_POST['accounts_number']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_number'])."'" : "NULL" ;
        $_POST['accounts_term_code'] =
        ( strlen($_POST['accounts_term_code']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_term_code'])."'" : "NULL" ;
//        $_POST['accounts_password'] =
//        ( strlen($_POST['accounts_password']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_password'])."'" : "NULL" ;

        //Non-Nullable fields Check
        $account_has_required_data = true;
        $account_has_required_data = ( strlen($_POST['accounts_company_name']) != 0 ) &&
                                     ( strlen($_POST['accounts_address1']) != 0 ) &&
                                     ( strlen($_POST['accounts_city']) != 0 )  &&
                                     ( strlen($_POST['accounts_zip']) != 0 ) &&
                                     ( strlen($_POST['accounts_country']) != 0 ) &&
                                     ( strlen($_POST['accounts_email']) != 0 ) &&
                                     ( strlen($_POST['accounts_username']) != 0 ) &&
                                     ( strlen($_POST['accounts_prefix']) != 0 ) &&
                                     ( strlen($_POST['accounts_password']) != 0 ) &&
                                     ( strlen($_POST['accounts_dropship_fee']) != 0 ) &&
                                     ( strlen($_POST['accounts_handling_fee']) != 0 ) &&
                                     ( strlen($_POST['accounts_number']) != 0 );

        $accountNum_check_sql = sprintf("SELECT * from accounts where `accounts_number` LIKE %s", $_POST['accounts_number']);
        $accountNum_check_query = my_db_query($accountNum_check_sql);
        $accountNumRows = mysql_num_rows($accountNum_check_query);

        $accountUsername_check_sql = sprintf("SELECT * from accounts where `accounts_username` LIKE %s", $_POST['accounts_username']);
        $accountUsername_check_query = my_db_query($accountNum_check_sql);
        $accountUsernameRows = mysql_num_rows($accountNum_check_query);

        $dupe_check = $accountNumRows + $accountUsernameRows;
        if( $dupe_check > 0){
            $account_has_required_data = false;
            echo "<div align=center class=\"fail\">Problem with either a duplicate account number or a username. </div>";
        }

        $accounts_sql = sprintf("INSERT INTO `accounts` ( `accounts_company_name` , `accounts_address1` , 
        `accounts_address2` , `accounts_city` , `accounts_state` , `accounts_zip`, `accounts_country` , 
        `accounts_phone` , `accounts_fax` , `accounts_email` , `accounts_url` , `accounts_poc` , `accounts_folder_name` ,
        `accounts_username` , `accounts_prefix` , `accounts_number`,`accounts_term_code`, `accounts_ship_id`, 
        `accounts_rep_group`, `accounts_dropship_fee`, `accounts_handling_fee` ) VALUES 
        (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %f, %f)", 
        $_POST['accounts_company_name'], $_POST['accounts_address1'], $_POST['accounts_address2'], 
        $_POST['accounts_city'], $_POST['accounts_state'], $_POST['accounts_zip'], $_POST['accounts_country'], 
        $_POST['accounts_phone'], $_POST['accounts_fax'], $_POST['accounts_email'], $_POST['accounts_url'], $_POST['accounts_poc'],
        $_POST['accounts_folder_name'], $_POST['accounts_username'], $_POST['accounts_prefix'], $_POST['accounts_number'], 
        $_POST['accounts_term_code'], $_POST['accounts_ship_id'], $_POST['accounts_rep_group'], 
        $_POST['accounts_dropship_fee'], $_POST['accounts_handling_fee']);


        if($account_has_required_data){
            $accounts_query = my_db_query($accounts_sql);

            $_POST['accounts_id'] = my_db_insert_id();
            $login_sql = sprintf("INSERT INTO `login` ( `login_username` ,`login_password`,
            `login_to_accounts_id`,`login_reset_password`,`login_userlevel`) VALUES (%s, %s, %d, 1, %d)",
            $_POST['accounts_username'],"'".encrypt_password($_POST['accounts_password'])."'",$_POST['accounts_id'], $_POST['user_level'] );

        }

        if( $accounts_query == 1){
            //Makes sense to only do this if the accounts insert worked
            $login_query = my_db_query($login_sql);
            echo "<div align=center class=\"success\">".$displayName." Added Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\"> Account Not Added</div>";

            if(!$account_has_required_data && $dupe_check = 0){
                echo "Empty Required Field(s) Found!";

                if( strlen($_POST['accounts_company_name']) == 0 ) echo "<br>Company Name";
                if( strlen($_POST['accounts_address1']) == 0 ) echo "<br>Company Address";
                if( strlen($_POST['accounts_city']) == 0 ) echo "<br>Company City";
                if( strlen($_POST['accounts_zip']) == 0 ) echo "<br>Company Zip";
                if( strlen($_POST['accounts_country']) == 0 ) echo "<br>Company Country";
                if( strlen($_POST['accounts_email']) == 0 ) echo "<br>Company Email";
                if( strlen($_POST['accounts_username']) == 0 ) echo "<br>Account Username";
                if( strlen($_POST['accounts_prefix']) == 0 ) echo "<br>Account Prefix";
                if( strlen($_POST['accounts_password']) == 0 ) echo "<br>Account Password";
                if( strlen($_POST['accounts_number']) == 0 ) echo "<br>Account Number";
                echo "</div>";
            }

        }
    }
//*************************************************************************
//*************************** COMMIT DELETE *******************************
//*************************************************************************
    if( $_GET['action'] == 'del'  ){
        $accounts_del_sql ="delete from ".$tableName." where ". $tableName."_id=".$_GET['aId'];

        $accounts_del_query = my_db_query($accounts_del_sql);

        $login_del_sql ="delete from login where login_to_accounts_id=".$_GET['aId'];
        $login_del_query = my_db_query($login_del_sql);

        if( $accounts_del_query == 1){
            echo "<div align=center class=\"success\">Account Deleted Successfully</div>";
        }else{
            echo "<div align=center class=\"fail\">Account Not Deleted</div>";
        }
    }
//*************************************************************************
//*************************** COMMIT MODIFY *******************************
//*************************************************************************
    if( $_GET['action'] == 'mod'  ){
        $_POST['accounts_company_name'] =
            ( strlen($_POST['accounts_company_name']) > 0 )? "'".htmlspecialchars($_POST['accounts_company_name'])."'" : "NULL" ;
        $_POST['accounts_address1'] =
            ( strlen($_POST['accounts_address1']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_address1'])."'" : "NULL" ;
        $_POST['accounts_address2'] =
            ( strlen($_POST['accounts_address2']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_address2'])."'" : "NULL" ;
        $_POST['accounts_city'] =
            ( strlen($_POST['accounts_city']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_city'])."'" : "NULL" ;
        $_POST['accounts_state'] =
            ( strlen($_POST['accounts_state']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_state'])."'" : "NULL" ;
        $_POST['accounts_zip'] =
            ( strlen($_POST['accounts_zip']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_zip'])."'" : "NULL" ;
        $_POST['accounts_country'] =
            ( strlen($_POST['accounts_country']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_country'])."'" : "NULL" ;
        $_POST['accounts_phone'] =
            ( strlen($_POST['accounts_phone']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_phone'])."'" : "NULL" ;
        $_POST['accounts_fax'] =
            ( strlen($_POST['accounts_fax']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_fax'])."'" : "NULL" ;
        $_POST['accounts_email'] =
            ( strlen($_POST['accounts_email']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_email'])."'" : "NULL" ;
        $_POST['accounts_url'] =
            ( strlen($_POST['accounts_url']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_url'])."'" : "NULL" ;
        $_POST['accounts_poc'] =
            ( strlen($_POST['accounts_poc']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_poc'])."'" : "NULL" ;
        $_POST['accounts_username'] =
            ( strlen($_POST['accounts_username']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_username'])."'" : "NULL" ;
        $_POST['accounts_folder_name'] =
            ( strlen($_POST['accounts_folder_name']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_folder_name'])."'" : "NULL" ;
        $_POST['accounts_prefix'] =
            ( strlen($_POST['accounts_prefix']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_prefix'])."'" : "NULL" ;
        $_POST['accounts_ship_id'] =
            ( strlen($_POST['accounts_ship_id']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_ship_id'])."'" : "NULL" ;
        $_POST['accounts_number'] =
            ( strlen($_POST['accounts_number']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_number'])."'" : "NULL" ;
        $_POST['accounts_term_code'] =
            ( strlen($_POST['accounts_term_code']) > 0 )? "'".mysql_real_escape_string($_POST['accounts_term_code'])."'" : "NULL" ;
        $_POST['accounts_reset_password'] = ( $_POST['accounts_reset_password'] == "on" )? 1 : 0 ;



        //Non-Nullable fields Check
        $account_has_required_data = true;
        $account_has_required_data = ( strlen($_POST['accounts_company_name']) != 0 ) &&
                                     ( strlen($_POST['accounts_address1']) != 0 ) &&
                                     ( strlen($_POST['accounts_city']) != 0 )  &&
                                     ( strlen($_POST['accounts_zip']) != 0 ) &&
                                     ( strlen($_POST['accounts_country']) != 0 ) &&
                                     ( strlen($_POST['accounts_email']) != 0 ) &&
                                     ( strlen($_POST['accounts_username']) != 0 ) &&
                                     ( strlen($_POST['accounts_dropship_fee']) != 0 ) &&
                                     ( strlen($_POST['accounts_handling_fee']) != 0 ) &&
                                     ( strlen($_POST['accounts_prefix']) != 0 ) &&
                                     ( strlen($_POST['accounts_number']) != 0 );



        $accounts_mod_sql =sprintf("UPDATE ".$tableName." SET `accounts_company_name` = %s,
                                `accounts_address1` = %s ,
                                `accounts_address2` = %s,
                                `accounts_city` = %s,
                                `accounts_state` = %s,
                                `accounts_zip` = %s,
                                `accounts_country` = %s,
                                `accounts_phone` = %s,
                                `accounts_fax` = %s,
                                `accounts_email` = %s,
                                `accounts_url` = %s,
                                `accounts_poc` = %s,
                                `accounts_folder_name` = %s,
                                `accounts_username` = %s,
                                `accounts_prefix` = %s,
                                `accounts_number` = %s,
                                `accounts_term_code` = %s,
                                `accounts_ship_id` = %s,
                                `accounts_rep_group` = %d,
                                `accounts_dropship_fee` = %f,
                                `accounts_handling_fee` = %f
                                 WHERE `accounts_id` = ".$_POST['accounts_id'],
                                 $_POST['accounts_company_name'],$_POST['accounts_address1'],$_POST['accounts_address2'],$_POST['accounts_city'],
                                 $_POST['accounts_state'],$_POST['accounts_zip'],$_POST['accounts_country'],$_POST['accounts_phone'],$_POST['accounts_fax'],$_POST['accounts_email'],
                                 $_POST['accounts_url'],$_POST['accounts_poc'],$_POST['accounts_folder_name'],$_POST['accounts_username'],$_POST['accounts_prefix'],$_POST['accounts_number'],
                                 $_POST['accounts_term_code'],$_POST['accounts_ship_id'],$_POST['accounts_rep_group'],$_POST['accounts_dropship_fee'],$_POST['accounts_handling_fee']);

        if($account_has_required_data){
            $accounts_mod_query = my_db_query($accounts_mod_sql);

            $login_sql =sprintf("UPDATE `login` SET `login_userlevel` = %d,
                                 `login_username` = %s
                                 WHERE `login_to_accounts_id` =".$_POST['accounts_id'],
                                 $_POST['user_level'], $_POST['accounts_username']);
            $login_query = my_db_query($login_sql);


            if( $_POST['accounts_reset_password'] == 1 && strlen($_POST['accounts_new_password']) > 0){
            $reset_password_sql =sprintf("UPDATE `login` SET `login_reset_password` = %d,`login_password` = '%s', 
            							`login_userlevel` = %d WHERE `login_to_accounts_id` =%d",
                                          $_POST['accounts_reset_password'], 
                                          encrypt_password($_POST['accounts_new_password']), 
                                          $_POST['user_level'],
                                          $_POST['accounts_id']);
            $reset_password_query = my_db_query($reset_password_sql);
            }
        }


        if( $accounts_mod_query ){
            echo "<div align=center class=\"success\">Account Information Modified Successfully</div>";

            if($_POST['accounts_reset_password'] == 1 && strlen($_POST['accounts_new_password']) == 0){
                echo "<br/> <div class=\"fail\" align=center>New Password was not provided!!</div>";
                echo "<br/> <div class=\"fail\" align=center>Password will not be reset.  Try again.</div>";
            }
        }else{
            echo "<div align=center class=\"fail\">Account Not Modified <br/><br/>";

            if(!$account_has_required_data){
                echo "Empty Required Field(s) Found!";

                if( strlen($_POST['accounts_company_name']) == 0 ) echo "<br>Company Name";
                if( strlen($_POST['accounts_address1']) == 0 ) echo "<br>Company Address";
                if( strlen($_POST['accounts_city']) == 0 ) echo "<br>Company City";
                if( strlen($_POST['accounts_zip']) == 0 ) echo "<br>Company Zip";
                if( strlen($_POST['accounts_country']) == 0 ) echo "<br>Company Country";
                if( strlen($_POST['accounts_email']) == 0 ) echo "<br>Company Email";
                if( strlen($_POST['accounts_username']) == 0 ) echo "<br>Account Username";
                if( strlen($_POST['accounts_prefix']) == 0 ) echo "<br>Account Prefix";
                if( strlen($_POST['accounts_number']) == 0 ) echo "<br>Account Number";
                if( strlen($_POST['accounts_dropship_fee']) == 0 ) echo "<br>Account Drop Ship Fee";
                if( strlen($_POST['accounts_handling_fee']) == 0 ) echo "<br>Account Handling Fee";
                echo "</div>";
            }

        }


    }
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>
    <table width=800 align="center" border=0 cellspacing=0>
    <tr><td align=right>
    <a href="<?php echo my_href_link($tableName.'.php', 'action=add_start'); ?>"><?php echo my_image(DIR_WS_IMAGES.'btnAdd.gif','Add New '.$displayName); ?></a>
    </td></tr>
    </table>



<?php

    echo "<table width=900 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=7>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th width=10 colspan=2 valign=bottom>Actions</th>\n";
    echo "\t<th>Company Name</th>\n";
    echo "\t<th>Username</th>\n";
    echo "\t<th>Price Level</th>\n";
    echo "\t<th>User Level</th>\n";
    echo "\t<th>Account No.</th>\n";
    echo "</tr>\n";


    $accounts_view_sql = "SELECT a.accounts_id, a.accounts_company_name,
    a.accounts_username, a.accounts_number, l.login_userlevel, a.accounts_price_level
    FROM ". $tableName ." a, login l
    WHERE a.accounts_id=l.login_to_accounts_id
    ORDER BY ". $tableName ."_company_name";
    $accounts_view_query = my_db_query($accounts_view_sql);
    $count = 0;
    $bgcolor = "#FFFFFF";
    $arrUserLevel[0]= "Client";
    $arrUserLevel[1]= "Admin";
    $arrUserLevel[2]= "Super";
    $arrUserLevelColor[0]= "green";
    $arrUserLevelColor[1]= "orange";
    $arrUserLevelColor[2]= "red";
    while($accounts_view = my_db_fetch_array($accounts_view_query)){
        $is_default = ($accounts_view[$tableName.'_default'] == 1)?"*":"";
        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";
        $userLevelColor = $arrUserLevelColor[$accounts_view['login_userlevel']];

        echo "<tr class=$bgcolor>";
        echo "<td align=center><a href=\"". my_href_link($tableName.'.php',
        'action=mod_start&aId='. $accounts_view[$tableName.'_id']). '">' .
        my_image(DIR_WS_IMAGES.'btnModify.gif','Modify '.$displayName) ."</a></td>";

        echo "<td align=center><a href=\"". my_href_link($tableName.'.php',
        'action=del&aId='. $accounts_view[$tableName.'_id']).
        '" onClick="return confirmDelete()">' .
        my_image(DIR_WS_IMAGES.'btnDelete.gif','Delete '.$displayName) ."</a></td>";

        echo "<td align=center>". $accounts_view[$tableName.'_company_name'] ."</td>";
        echo "<td align=center>". $accounts_view[$tableName.'_username'] ."</td>";
        echo "<td align=center>". getPriceLevelsSelectionOptions($accounts_view['accounts_number'], $accounts_view['accounts_price_level']) ."</td>";        
        echo "<td align=center style='color: $userLevelColor'>". $arrUserLevel[$accounts_view['login_userlevel']] ."</td>";
        echo "<td align=center>". $accounts_view[$tableName.'_number'] ."</td>";
        echo "</tr>\n";
        $count++;
    }
    echo "</table>\n";
}
?>




</form>
</body>
</html>
