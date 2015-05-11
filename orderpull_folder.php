<?php
require('includes/application_top.php');

$tableName = "accounts";
$displayName = "Accounts Order Pull Folder Manager";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Manage <?php echo $displayName; ?></title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
    function confirmDelete(){
        if(confirm("You are about to make a deletion, continue?")){
            return true;
        }else{
            return false;
        }
    }

jQuery(document).ready(function(){
(function($){
    function accountFolderUtil (accountNumber, action) {

        if (typeof accountNumber !== 'string' && accountNumber.length > 0 &&
            accountNumber !== "0" &&
            typeof action !== 'string' && action.length > 0) {
            return;
        }

        var url = "http://www.kerussods.com/ajax_controller.php?action=acct_folder_" + action + "&accounts_number="+accountNumber+"&kdssid=<?php echo $_GET['kdssid'];?>";

        $.ajax(url)
        .done(function(result){
            var result = $.parseJSON(result);

            if (result.status === 'success') {
                location.reload();
            } else {
                console.log('ERROR: Attempt to add a FTP folder value was unsuccessful')
            }
        })
        .fail(function(error){
            console.log(error)
        });
    }

    function showCreateMessage() {
        var $button = $(document.getElementById('createFTPFolder'));
        $button.show();
    }

    function registerAccountChange () {
        $('#accountsList').change(function(e) {
            showCreateMessage(e.currentTarget);
        });
    }

    function registerCreateAccountFolder () {
        var $button = $(document.getElementById('createFTPFolder'));
        $button.on('click', function(e){
            var $acctList = $(document.getElementById('accountsList')),
                action = 'create';

            accountFolderUtil($acctList[0].value, action);
        })
    }

    function registerDeleteAccountFolder () {
        var $delButtons = $('.btnDelete');

        $delButtons.on('click', function(el){
            var acctNumber = el.currentTarget.id,
                action = 'delete';

            accountFolderUtil(acctNumber, action);
        });
    }

    registerAccountChange();
    registerCreateAccountFolder();
    registerDeleteAccountFolder();
})(jQuery)
});
</script>
<script type="text/javascript" src="debugInfo.js"></script>
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
        <td colspan=3 align="center" class="largeBoldText">A C C O U N T &nbsp;&nbsp; F O L D E R &nbsp;&nbsp; N A M E</td>
    </tr>
</table>

<div id=message style="margin: auto; color: #f00; text-align: center;"></div>
<br />
<br />

<?php
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************

    $arrAccounts = array();
    $accounts_sql = "SELECT *
                    FROM accounts
                    ORDER BY accounts_company_name";

    $accounts_query = my_db_query($accounts_sql);
    $arrAccounts[] = array('id'=>0, 'text'=>'Select Company');

    while( $accounts = my_db_fetch_array($accounts_query) ){
        $arrAccounts[] = array('id'=>$accounts['accounts_number'], 'text'=>$accounts['accounts_company_name']);
        if (strlen($accounts['accounts_folder_name']) > 0) {
            $arrUsernames[] = array(
                            'company' => $accounts['accounts_company_name'],
                            'username' => $accounts['accounts_username'],
                            'acct_number' => $accounts['accounts_number'],
                            'id' => $accounts['accounts_id']
                            );
        }
    }

    $bgcolor = "#FFFFFF";
    $styles = "style='padding: 10px 0 10px 20px;'";

    echo my_draw_form('add_'.$tableName,my_href_link('orderpull_folder.php', 'action=add'));

    echo "<table width=500 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";
    echo "<tr class=\"tableHeader\"><td align=center colspan=2 style='padding: 10px'>". my_draw_pull_down_menu('accounts_number', $arrAccounts, '', 'id="accountsList"') ."</td><td colspan=2 ><button id='createFTPFolder' style='display: none;'>Click to Create FTP Folder</button></td></tr>\n";
    echo "<tr class='mediumLargeBoldText tableRowColor'>";
    echo "  <td colspan=2 $styles>Company: </td>\n";
    echo "  <td colspan=2 $styles>Folder Name</td>\n";
    echo "</tr>\n";

for ($i = 0; $i < count($arrUsernames); $i++) {

    echo "<tr style='background-color: $bgcolor;' id='" . $arrUsernames[$i]['id'] . "'>";
    echo "  <td style=''><img src='images/btnDelete.gif' id=" . $arrUsernames[$i]['acct_number'] . " class='btnDelete'></td>\n";
    echo "  <td style=''>" . $arrUsernames[$i]['company'] . "</td>\n";
    echo "  <td style='padding-left: 20px;' colspan=2>" . $arrUsernames[$i]['username'] . "</td>\n";
    echo "</tr>\n";
}
    echo "</table>\n";
?>

</form>
</body>
</html>
