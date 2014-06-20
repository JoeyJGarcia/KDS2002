<?php

$temp = (my_session_is_registered('isValidated'))?'yes':'no';

echo "<br>
<span ID=\"debugInfo\" style=\"display:none\">
<table cellspacing=0 cellpadding=3 >
    <tr bgcolor=#E7E7E7>
        <th colspan=2><b>D E B U G &nbsp;&nbsp; I N F O</b>
    </tr>
    <tr bgcolor=#F4F4F4>
        <th align=right>User Level =</th><td>  ".$_SESSION['userlevel']."</td>
    </tr>
    <tr bgcolor=#F4F4F4>
        <th align=right>Company Name =</th><td> ".$_SESSION['company_name']."</td>
    </tr>
    <tr bgcolor=#F4F4F4>
        <th align=right>Account Number =</th><td>  ".$_SESSION['client_account_number']." </td>
    </tr>
    <tr bgcolor=#F4F4F4>
        <th align=right>Authenticated =</th><td>  ".$_SESSION['isValidated']." </td>
    </tr>
    <tr bgcolor=#F4F4F4>
        <th align=right>Price Level =</th><td>  ".$_SESSION['price_level']." </td>
    </tr>
    <tr bgcolor=#F4F4F4>
        <th align=right>Session Registered =</th><td> $temp</td>
    </tr>
</table>
<br><br>
</span>
";
?>
