


    <table width=500 align="CENTER" border=0 cellspacing="0" cellpadding="3">
    <tr bgcolor="#DADADA">
        <th colspan="2">DEBUG MENU</th>
    </tr>
    <tr bgcolor="#DADADA">
        <th>VARIABLE</th>
        <th>VALUE</th>
    </tr>
    <tr bgcolor="#F0F0F0">
        <td>SESSION_STARTED</td>
        <td> <?php
             if( $session_started == true ){
                   echo "TRUE";
             }else{
                   echo "FALSE";
             }
             ?>
        </td>
    </tr>
    <tr bgcolor="#F0F0F0">
        <td>SID IS NULL</td>
        <td> <?php
             if( my_not_null($SID) ){
                   echo "TRUE";
             }else{
                   echo "FALSE";
             }
             ?>
        </td>
    </tr>
    <tr bgcolor="#F0F0F0">
        <td>SESSION_ID</td>
        <td> <?php echo $kdssid; ?></td>
    </tr>
    <tr bgcolor="#F0F0F0">
        <td>SESSION_FORCE_COOKIE_USE</td>
        <td> <?php echo SESSION_FORCE_COOKIE_USE; ?></td>
    </tr>

    </table>




<?php
