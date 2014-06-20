<?php
require('includes/application_top.php');

$tableName = "sizes";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - View Contacts</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

<script type="text/javascript">

</script>
<script language="JavaScript" src="debugInfo.js"></script>
</head>

<body>
<?php
require('navigation.php');
?>
<table align=right><tr><td><div onClick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');

    $rep_name_sql = "SELECT * FROM rep_groups r WHERE  r.rep_groups_id= ". $_SESSION['rep_group'] ;
    $rep_name_query = my_db_query($rep_name_sql);
    $rep_name = my_db_fetch_array($rep_name_query);

	if( isset($_SESSION['rep_group']) && $_SESSION['rep_group'] != 0 ){
		$whereClause = "a.accounts_rep_group = r.rep_groups_id  AND r.rep_groups_id= ". $_SESSION['rep_group'];
		$fromClause = "accounts a, rep_groups r";
		$repName = "&nbsp;&nbsp; F O R &nbsp;&nbsp; " .strtoupper ($rep_name['rep_groups_name'] );
	}else {
		$whereClause = " 1 ";
		$fromClause = "accounts a";
		$repName = "";
	}
?>


<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText">V I E W &nbsp;&nbsp; C O N T A C T S <?php echo $repName;  ?></td>
    </tr>
</table>


<br />
<br />


<?php
//*************************************************************************
//****************************** MAIN PAGE ********************************
//*************************************************************************
?>


<?php

    echo "<table width='90%' border=0 align=center cellspacing=0 class=\"thinOutline\">\n";

    echo "<tr class=\"tableHeader\"><td colspan=6>". my_image(DIR_WS_IMAGES.'spacer.gif','','300','1') ."</td></tr>\n";

    echo "<tr class=\"tableHeader\">\n";
    echo "<th></th>\n";
    echo "<th align=\"left\">Acct. # /&nbsp;Prefix&nbsp;/&nbsp;Company&nbsp;Name</th>\n";
    echo "\t<th align=\"left\">Contact&nbsp;Name</th>\n";
    echo "\t<th align=\"left\">Email&nbsp;Address</th>\n";
    echo "\t<th align=\"left\">Phone</th>\n";
    echo "</tr>\n";


    $view_contacts_sql = "SELECT * FROM ". $fromClause ." WHERE  " . $whereClause ." ORDER BY  a.accounts_company_name";
    $view_contacts_query = my_db_query($view_contacts_sql);
    $count = 1;
    $bgcolor = "#FFFFFF";
    while($view_contacts = my_db_fetch_array($view_contacts_query)){

        $bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven_10" : "tableRowColorOdd_10";

        echo "<tr class=$bgcolor>";
        echo "<td align=right>". $count .")&nbsp;</td>";
        echo "<td align=left>&nbsp;". $view_contacts['accounts_number']." / ".$view_contacts['accounts_prefix']." / ".$view_contacts['accounts_company_name'] ."</td>";
        echo "<td align=left>&nbsp;". $view_contacts['accounts_poc'] ."</td>";
        echo "<td align=left>&nbsp;<A HREF=\"mailto:".$view_contacts['accounts_email']."\">".$view_contacts['accounts_email']."</a></td>";
        echo "<td align=left>&nbsp;". $view_contacts['accounts_phone'] ."</td>";
        echo "</tr>\n";
        $count++;
    }
    echo "</table>\n";
?>

<br/><br/><br/><br/>




</form>
</body>
</html>
