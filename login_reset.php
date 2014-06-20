
<?php
echo my_draw_form('validation',my_href_link('index.php', 'action=reset'));
?>
<br>
<?php echo my_draw_hidden_field('user_name',$_POST['user_name']); ?>
<?php echo my_draw_hidden_field('userlevel',$_SESSION['userlevel']); ?>
<?php echo my_draw_hidden_field('company_name',$_SESSION['company_name']); ?>
<table align="CENTER" border="0" cellpadding="10" cellspacing="0" class="thinOutline">
<tr class="tableHeader">
    <th colspan="2" align="CENTER">P A S S W O R D &nbsp;&nbsp; R E S E T</th>
</tr>
<tr class="tableRowColor">
    <td align="RIGHT" class="mediumBoldText">Old Password</td>
    <td>

<?php echo my_draw_password_field('pass_word_old'); ?>

    </td>
</tr>
<tr class="tableRowColor">
    <td align="RIGHT" class="mediumBoldText">New Password</td>
    <td>

<?php echo my_draw_password_field('pass_word_new'); ?>

    </td>
</tr>
<tr class="tableRowColor">
    <td align="RIGHT" class="mediumBoldText">Confirm New Password</td>
    <td>

<?php echo my_draw_password_field('pass_word_confirm'); ?>

    </td>
</tr>
<tr class="tableFooter">
    <td align="CENTER"><a href="<?php echo my_href_link('index.php', 'action=logout') ;?>"
        ><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
    </td>
    <td align="CENTER">
        <?php echo my_image_submit('btnSubmit.gif','Sign In'); ?>
    </td>
</tr>
</table>
</form>

