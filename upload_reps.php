<?php
require('includes/application_top.php');

set_time_limit (0);
ini_set('upload_max_filesize', '1M');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Kerusso Drop Ship - Upload Rep List</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
  <script language="JavaScript" src="debugInfo.js"></script>
  <script>
  function submitCSV (frm) {
    console.log(frm)
  }
  </script>
</head>

<body <?php if( $_GET['action'] == 'validateXML' ) echo "onload='hideLoadingMsg()'"; ?>>

<a name="top"></a>
<?php

require('navigation.php');

?>

<table align=right><tr><td><div onClick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    include('debug_info.php');
?>

<table align="center" width="500">
    <tr>
        <td colspan=3 align="center" class="largeBoldText"U P L O A D &nbsp;&nbsp; R E P &nbsp;&nbsp; L I S T.</td>
    </tr>
</table>


<br />
<br />
<?php


if ($_GET['action'] == 'upload_customer_reps_list') {


    if( strlen($_FILES['WebCustomerRepLists']['name']) > 0){
        $fileName1 = $_FILES['WebCustomerRepLists']['name']."_".date("mdY_his");
        $file1 = "/home/kerussod/public_html/uploads/".$fileName1.".csv";

        if(move_uploaded_file($_FILES['WebCustomerRepLists']['tmp_name'], $file1)) {
            echo "The file ".  basename( $_FILES['WebCustomerRepLists']['name'])." has been uploaded successfully. <br><br>";
        } else{
            echo "<span class='largeBoldErrorText' align=center> There was an error uploading " . $_FILES['WebCustomerRepLists']['name'] .", please try again!</span>";
            exit;
        }

        $arrFile1 = array();
        $row = 1;
        if (($handle = fopen($file1, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);

                if ($row == 1) {
                    $truncateReps_sql = "truncate table reps";
                    $truncateReps_query = my_db_query($truncateReps_sql);
                    $fieldCount = $num;
                }

                if(strlen($data[0]) == 0) {
                  break;
                }

                if ($num == $fieldCount) {
                    $tmpl = "INSERT INTO `reps`(`accounts_number`, `accounts_company_name`, `field_rep`, `inside_rep`, `field_group`, `national_group`, `national_rep`, `sales_mgr`) VALUES (%d,'%s','%s','%s','%s','%s','%s','%s')";
                    if ($row != 1) {
                        $insert_sql = sprintf($tmpl, $data[0],  addslashes($data[1]), addslashes($data[2]), addslashes($data[3]), addslashes($data[4]), addslashes($data[5]), addslashes($data[6]), addslashes($data[7]));
                        $insert_query = my_db_query($insert_sql);
                    }
                } else {
                    echo "Skipped row $row because of wrong number of fields.<br>";
                }

                $row++;
            }
            fclose($handle);
            //echo $rows - 1 ."inserted rows <br>";
            //echo $rows ." rows found<br>";
        }
    }

    if( strlen($_FILES['WebRepList']['name']) > 0){
        $fileName2 = $_FILES['WebRepList']['name']."_".date("mdY_his");
        $file2 = "/home/kerussod/public_html/uploads/".$fileName2.".csv";

        if(move_uploaded_file($_FILES['WebRepList']['tmp_name'], $file2)) {
            echo "The file ".  basename( $_FILES['WebRepList']['name'])." has been uploaded successfully. <br><br>";
        } else{
            echo "<span class='largeBoldErrorText' align=center> There was an error uploading " . $_FILES['WebRepList']['name'] .", please try again!</span>";
            exit;
        }


        $arrFile2 = array();
        $row = 1;
        if (($handle = fopen($file2, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);

                if ($row == 1) {
                    $truncateRepsCode_sql = "truncate table rep_codes";
                    $truncateRepsCode_query = my_db_query($truncateRepsCode_sql);
                    $fieldCount = $num;
                }

                if(strlen($data[0]) == 0) {
                  break;
                }

                if ($num == $fieldCount) {
                    $tmpl = "INSERT INTO `rep_codes`(`rep_name`, `rep_code`) VALUES ('%s','%s')";
                    if ($row != 1) {
                        $insert_sql = sprintf($tmpl, addslashes($data[0]), addslashes($data[1]));
                        $insert_query = my_db_query($insert_sql);
                    }
                } else {
                    echo "Skipped row $row because of wrong number of fields.<br>";
                }

                $row++;
            }
            fclose($handle);
            //echo $rows - 1 ."inserted rows <br>";
            //echo $rows ." rows found<br>";
        }
    }
    echo "<a href='upload_reps.php?kdssid=".$_GET['kdssid']."'><-- Back to Upload Rep List Page</a>";

} else {
//*************************************************************************
//*************************** MAIN MENU ******************************
//*************************************************************************
?>

<span align=center ID="errMsg" class="largeBoldErrorText" style="display:none">Please submit a XML file <em>or</em> paste some XML text</span>

<?php 
echo my_draw_form('customer_rep_list',my_href_link('upload_reps.php', 'action=upload_customer_reps_list'),"POST", "enctype='multipart/form-data' ");
?>


<div style="width: 300px; display: table; margin: auto; background-color: #ddd; padding: 10px;" class="">

  <div style="display: table-row; width:100%" class="mediumBoldText">
    Browse&nbsp;to&nbsp;upload&nbsp;your&nbsp;WebCustomerRepLists.csv&nbsp;file.
  </div>
  <div style="display: table-row;" class="largeBoldText">
    <div style="display:table-cell; width: 20%;">
      <input type="file" name="WebCustomerRepLists"/>
    </div>
  </div>

  <div style="display: table-row; height: 20px;"></div>

  <div style="display: table-row; width:100%;" class="mediumBoldText">
    Browse&nbsp;to&nbsp;upload&nbsp;your&nbsp;WebRepList.csv&nbsp;file.
  </div>
  <div style="display: table-row;" class="largeBoldText">
    <div style="display:table-cell; width: 20%;">
      <input type="file" name="WebRepList"/>
    </div>
  </div>


  <div style="display: table-row;" class="largeBoldText">
    <div style="display:table-cell; width: 20%; text-align: center;">
      <input type=submit value="Upload"/>
    </div>
  </div>

</form>

</div>


<?php
}
?>

</body>
</html>