
<?php
echo my_draw_form('validation',my_href_link('index.php', 'action=process'));
?>
<br><br>
<div align="center">
<div style="font-size:12px;color:red;">Please update your bookmarks</div>
<br>
<div style="font-size:12px;color:red;">http://www.kerussods.com/</div>
</div>
<br><br>
<table align="CENTER" border="0" cellpadding="10" cellspacing="0" class="thinOutline">
<tr class="tableHeader">
    <th colspan="2" align="CENTER"><img src="images/kerussoDropshipping.gif"></th>
</tr>
<tr class="tableRowColor">
    <td align="RIGHT" class="mediumBoldText">User Name</td>
    <td>

<?php

echo my_draw_input_field('user_name','','size=20');

?>

    </td>
</tr>
<tr class="tableRowColor">
    <td align="RIGHT" class="mediumBoldText">Password</td>
    <td>

<?php

echo my_draw_password_field('pass_word','','size=20');

?>

    </td>
</tr>
<tr class="tableFooter">
    <td colspan="2" align="CENTER">
        <?php echo my_image_submit('btnLogin.gif','Sign In'); ?>
    </td>
</tr>
</table>
<div class="smallText" align=center><a href="<?php echo my_href_link('index.php','action=send_pass');?>">[forgot password]</a></div>
</form>

