<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Inventory Populate</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
  <script language="JavaScript" src="debugInfo.js"></script>
</head>

<body>
<?php
require('navigation.php');

$delimiter = ",";
?>

<table align=right><tr><td><div onClick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');
?>


<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText">I N V E N T O R Y &nbsp;&nbsp; P O P U L A T E</td>
    </tr>
</table>


<br />
<br />
<br />
<br />

<?php
if($action != "process"){
 ?>

<?php echo my_draw_form('populate_inventory','inventory_populate.php?action=process','post','enctype="multipart/form-data"'); ?>

<?= my_draw_hidden_field('MAX_FILE_SIZE','10000000'); ?>

<div align=center>
Choose a file to upload: <?= my_draw_input_field('inventory','','','file'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="sample_inventory.txt" class="smallText">Sample Upload File</a><br /><br />
<?= my_draw_input_field('submit','Upload File','','submit'); ?>


<br /><br />


<?php echo my_draw_checkbox_field("testFormat") ;?> Only Test Inventory File Format

<br /><br />

<br /><br /><br />
Delimiter is set to: <?php echo $delimiter ;?>
</div>



<?php
}else{

$isTest = ($testFormat == "on")? true : false ;

if($_SERVER["HTTP_HOST"] == "jjgdesktop"){
    $uploadDir = 'c:temp/';

}else{
    $uploadDir = '/tmp/';
}
$uploadFile = $uploadDir . $_FILES['inventory']['name'];
$lineFormat_is_ok = true;
if(  is_uploaded_file($_FILES['inventory']['tmp_name']) ){
    if (move_uploaded_file($_FILES['inventory']['tmp_name'], $uploadFile)){

        $fileContents = file_get_contents($uploadFile);
        $endOfRow = "\n";

        $arrInventory = split($endOfRow,$fileContents);
        if(!$isTest){
            my_db_query("TRUNCATE TABLE `products`");
        }
        for($i=0; $i< count($arrInventory); $i++){

            $arrProduct = split($delimiter,$arrInventory[$i]);

            if( strlen(trim($arrProduct[0])) == 0 || strlen($arrInventory[$i]) == 0 )continue;
            if(count($arrProduct) != 4)$lineFormat_is_ok = false;

            if($isTest && count($arrProduct) != 4 ){
                echo "<div class=\"fail\">Format is bad!</div>";
                echo "<div class=\"fail\">Instead of 4 items in this line there was ".
                count($arrProduct).".</div>";
                echo "<div class=\"fail\">Where Product Name is: ".$arrProduct[0]."</div>";
                echo "<hr width=100%><br>";
            }


            if(!$isTest){
                $inventory_sql = sprintf("INSERT into products (product_name,
                product_model, product_sizes, product_desc, product_mod_date)
                VALUES ('%s','%s','%s','%s','".date("y-m-d h:i:s")."')",
                mysql_real_escape_string(trim($arrProduct[0])),
                mysql_real_escape_string(trim($arrProduct[1])),
                mysql_real_escape_string(trim($arrProduct[2])),
                mysql_real_escape_string(trim($arrProduct[3])) );
                //echo $inventory_sql ."<br><br>";
                $inventory_query = my_db_query($inventory_sql);
            }

        }


        //unset($uploadFile);
        unlink($uploadFile);//clean up
        if(!$isTest){
            echo "<div align=center class=\"success\">Inventory Uploaded Successfully</div><br>";
        }
        if(!$lineFormat_is_ok ){
            echo "<div align=center class=\"fail\">!! File Format Had Problems;
            Check Delimiters Per Line !!</div><br>";
            echo "<div align=center ><a href=\"".my_href_link("inventory_populate.php")."\">Try Again?</a></div><br>";
        }elseif($isTest && $lineFormat_is_ok ){
            echo "<div align=center class=\"success\">File Format is OK!!</div><br>";
            echo "<div align=center ><a href=\"".my_href_link("inventory_populate.php")."\">
            ".my_image("btnBack.gif","Back")."</a></div><br>";
        }

    }
}else{
        echo "<div align=center class=\"fail\">Inventory NOT Uploaded</div>";
}


}
?>
</form>
</body>
</html>
