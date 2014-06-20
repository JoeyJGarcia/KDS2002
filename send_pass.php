
<?php
echo my_draw_form('send_pass',my_href_link('index.php', 'action=sent_pass'));
?>
<br>
<table align="CENTER" border="0" cellpadding="10" cellspacing="0" class="thinOutline">
<tr class="tableHeader">
    <th colspan="2" align="CENTER">S E N D &nbsp;&nbsp; P A S S W O R D </th>
</tr>
<tr class="tableRowColor">
    <td align="RIGHT" class="mediumBoldText">User Name</td>
    <td>

<?php echo my_draw_input_field('user_name'); ?>

    </td>
</tr>
<tr class="tableFooter">
    <td align="CENTER"><a href="<?php echo my_href_link('index.php') ;?>"
        ><?php echo my_image(DIR_WS_IMAGES.'btnCancel.gif','Cancel'); ?></a>
    </td>
    <td align="right">
        <?php echo my_image_submit('btnSubmit.gif','Sign In'); ?>
    </td>
</tr>
</table>
</form>

