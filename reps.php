<?php
require('includes/application_top.php');

$tableName = "reps";
$displayName = "Rep Groups";

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
    function getReps (el) {
        var selIndex = el.selectedIndex,
            accounts_number = el.options[selIndex].value,
            url = "http://www.kerussods.com/ajax_controller.php?action=get_reps&accounts_number="+accounts_number+"&kdssid=<?php echo $_GET['kdssid'];?>";
            $('#message')[0].innerText = '';

        if (accounts_number !== "0") {
            $.ajax(url)
            .done(function(result){
                var x,
                result = $.parseJSON(result),
                $el;

                if (result.status === 'failure') {
                    $('#message')[0].innerText = 'Company Information Not Found';
                    clearFields();
                    return;
                }

                for (x in result.results) {
                    $el = $($('#'+x));
                    if (typeof $el[0].tagName !== 'undefined' && $el[0].tagName == 'TD') {
                        $el[0].innerText = result.results[x];
                    } else if ($el[0].tagName == 'INPUT') {
                        $el[0].value = result.results[x];
                    }
                }
            })
            .fail(function(error){
                console.log(error)
            });
        } else {
            console.log('do nothing');
            clearFields();
        }
    }

    function registerAccountChange () {
        $('#accountsList').change(function(e) {
            getReps(e.currentTarget);
        });
    }

    function clearFields () {
        $('#accounts_number').text('');
        $('#accounts_company_name').text('');
        $('#field_rep')[0].value = '';
        $('#inside_rep')[0].value = '';
        $('#field_group')[0].value = '';
        $('#national_group')[0].value = '';
        $('#national_rep')[0].value = '';
        $('#sales_mgr')[0].value = '';

    }

    registerAccountChange();
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
        <td colspan=3 align="center" class="largeBoldText">R E P  &nbsp;&nbsp; G R O U P S</td>
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
    $reps_sql = "SELECT * FROM reps ORDER BY accounts_company_name";
    $reps_query = my_db_query($reps_sql);
    $arrAccounts[] = array('id'=>0, 'text'=>'Choose Customer');

    while( $reps = my_db_fetch_array($reps_query) ){
        $arrAccounts[] = array('id'=>$reps['accounts_number'], 'text'=>$reps['accounts_company_name']);
    }

    $bgcolor = "#FFFFFF";
    $styles = "style='padding: 10px 0 10px 20px;'";


    echo my_draw_form('add_'.$tableName,my_href_link($tableName.'.php', 'action=add'));

    echo "<table width=600 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=2 align=center style='padding-top: 20px'>". my_draw_pull_down_menu('accounts_number', $arrAccounts, '', 'id="accountsList"') ."</td></tr>\n";
    echo "<tr class=\"tableHeader\"><td colspan=2>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=$bgcolor>";
    echo "<td $styles>Customer ID</td><td id=accounts_number></td>\n";
    echo "</tr>\n";

    echo "<tr class=$bgcolor>";
    echo "<td $styles>Customer Name</td><td id=accounts_company_name></td>\n";
    echo "</tr>\n";

    echo "<tr class=$bgcolor>";
    echo "<td $styles>Field Rep</td><td>" . my_draw_input_field('field_rep','','size=50 id=field_rep ') . "</td>\n";
    echo "</tr>\n";

    echo "<tr class=$bgcolor>";
    echo "<td $styles>Inside Rep</td><td>" . my_draw_input_field('inside_rep','','size=50 id=inside_rep ') . "</td>\n";
    echo "</tr>\n";

    echo "<tr class=$bgcolor>";
    echo "<td $styles>Field Group</td><td>" . my_draw_input_field('field_group','','size=50 id=field_group ') . "</td>\n";
    echo "</tr>\n";

    echo "<tr class=$bgcolor>";
    echo "<td $styles>National Group</td><td>" . my_draw_input_field('national_group','','size=50 id=national_group ') . "</td>\n";
    echo "</tr>\n";

    echo "<tr class=$bgcolor>";
    echo "<td $styles>National Rep</td><td>" . my_draw_input_field('national_rep','','size=50 id=national_rep ') . "</td>\n";
    echo "</tr>\n";

    echo "<tr class=$bgcolor>";
    echo "<td $styles>Sales Manager</td><td>" . my_draw_input_field('sales_mgr','','size=50 id=sales_mgr ') . "</td>\n";
    echo "</tr>\n";

    echo "</table>\n";
?>

</form>
</body>
</html>
