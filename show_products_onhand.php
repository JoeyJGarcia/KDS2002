<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Kerusso Drop Ship - Show Products On-Hand</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>

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
        <td colspan=3 align="center" class="largeBoldText">P R O D U C T S &nbsp;&nbsp; O N - H A N D</td>
    </tr>
</table>


<br />
<br />


<table width=600 border=0 align=center cellspacing=0 class="thinOutline">
<tr class="tableHeader">
<th>Product</th>
<th>Name</th>
<th>Size</th>
<th>Quantity</th>
</tr>

<?php 


$show_onhand_sql = "SELECT po.product_model AS model, p.product_name AS name, 
					po.product_size AS size, po.product_quantity AS quantity  
					FROM products_onhand po, products p, cat_sizes cs
					WHERE po.product_model=p.product_model AND 
                    cs.cat_sizes_name=po.product_size 
					ORDER BY po.product_model, cs.cat_sizes_sort";

    $show_onhand_query = my_db_query($show_onhand_sql);
    $count = 0;
    while($onhand = my_db_fetch_array($show_onhand_query)){

		$bgcolor = ( fmod($count,2)==0 )? "tableRowColorEven" : "tableRowColorOdd";
		
	    echo "<tr class=$bgcolor>";
	    echo "<td align=\"center\" class=\"largeText\">".$onhand['model']."</td>";
	    echo "<td align=\"center\" class=\"largeText\">".stripslashes($onhand['name'])."</td>";
	    echo "<td align=\"center\" class=\"largeText\">".$onhand['size']."</td>";
	    $styleClass = ( $onhand['quantity'] < 20)? "boldRedLargeText" : "largeText";
	    echo "<td align=\"center\" class=\"$styleClass\">".$onhand['quantity']."</td>";
	    echo "</tr>";
        $count++;
    }
    echo "</table>";
?>