<?php
require('includes/application_top.php');
require('includes/classes/KDSUtils.class.php');
?>


<?php
/*
 * Global Variables Initialized
 */
 $updateCount = 0;


$arrRepUpdates[] = "update orders set rep1_code = 'NON' where rep1_name = 'None'";
$arrRepUpdates[] = "update orders set rep2_code = 'KOO' where rep2_name = 'Eric Kooymans'";
$arrRepUpdates[] = "update orders set rep2_code = 'WEB' where rep2_name = 'Web Specialist'";
$arrRepUpdates[] = "update orders set rep3_code = 'NON' where rep3_name = 'None'";
$arrRepUpdates[] = "update orders set rep4_code = 'KRO' where rep4_name = 'Kerusso National Group'";
$arrRepUpdates[] = "update orders set rep5_code = 'KOO' where rep5_name = 'Eric Kooymans'";
$arrRepUpdates[] = "update orders set rep6_code = 'KOO' where rep6_name = 'Eric Kooymans'";
$arrRepUpdates[] = "update orders set rep6_code = 'TRA' where rep6_name = 'Tracy Holmes'";



$ku = new KDSUtils();



for($i=0; $i < count($arrRepUpdates); $i++) {
    $updateCount = $ku->update($arrRepUpdates[$i]);

    echo "Updated " . $updateCount . " row.  sql = " .  $arrRepUpdates[$i] . "<br>";
}

?>
