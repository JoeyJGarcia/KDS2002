<?php



?>
<br>
<h1 align=center><?php echo $_SESSION['company_name'];   ?></h1>
<br>
<table border=0 width=50% align="center" cellpadding=10>
<tr>
     <td align=center class="mediumBoldText" valign="top">
        <a href="<?php echo my_href_link('orders2.php'); ?>">Enter Order</a>
     </td>
     <td align=center class="mediumBoldText" valign="top">
        <a href="<?php echo my_href_link('bulk_orders.php'); ?>">Enter Bulk Orders</a>
     </td>
     <td align=center class="mediumBoldText" valign="top">
         <a href="<?php echo my_href_link('show_orders.php'); ?>">Show My Orders</a>
    </td>
     <td align=center class="mediumBoldText" valign="top">
     <a href="<?php echo my_href_link('show_products_onhand.php'); ?>">Show Products On-Hand</a>
    </td>
</tr>
<tr>
 	<td  colspan=4 align=center class="mediumBoldText" valign="top">
		<a href="<?php echo my_href_link('faqs.php'); ?>">Frequently Asked Questions</a>
	</td>
</tr>
</table>
<br><br>
<table border=0 width=50% align="center">

<tr>
<td align=center class="mediumBoldText" valign="top"><br><br>
<a href="http://www.kerusso.com/dealer/dropshipper-resources/" target="_blank">Product Graphics</a><br>
</td>
<td align=center class="mediumBoldText" valign="top"><br><br>
<a href="http://www.kerusso.com/dealer/kerussos-upc-codes/" target="_blank">Product Spreadsheet</a><br>
</td>
</tr>
</table>
<br><br>

<div align=center class="Headerline">

<?php

    $news_view_sql = "SELECT * FROM news WHERE 1 ORDER BY news_id DESC";

    $news_view_query = my_db_query($news_view_sql);

    if( mysql_num_rows($news_view_query) > 0){
        echo "Today's News.";
    }
    while($news_view = my_db_fetch_array($news_view_query)){

    echo "<table width=600 border=0 align=center cellspacing=0 class=\"thinOutline\">\n";
        //NEWS Title Row
        echo "<tr>";
        echo "<th align=center width=80% class=\"newsTitle\">&nbsp;&nbsp;&nbsp;&nbsp;".
        stripslashes($news_view['news_title']) ."</th>";
        echo "<th class=\"newsDate\" align=right>posted:&nbsp;".$news_view['news_postdate']."</th>";
        echo "</tr>\n";

        //NEWS Text Row
        echo "<tr >";
        echo "<th align=left width=80% colspan=2 class=\"newsText\"><p>".
        stripslashes($news_view['news_text']) ."</th>";
        echo "</tr>\n";
    echo "</table>\n";


    echo "<br><br>\n";

    }
?>
</div>

