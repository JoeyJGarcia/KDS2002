<?php
require('includes/application_top.php');

$dir = 'uploads/';

        $arrFilesFound = scandir($dir);

        for ( $i = 0; $i < count($arrFilesFound); $i++) {
            //echo "File Found: " . $arrFilesFound[$i] . "<br>";
            if (strpos($arrFilesFound[$i], "csv")) {
//                echo "CSV File Found: " . $arrFilesFound[$i] . "<br><br>";

                if (strpos($arrFilesFound[$i], "ebRepList")) {
                    $arrRepCodesCSVFiles[] = $arrFilesFound[$i];
                    // echo "Rep Code File Found: " . $arrFilesFound[$i] . "<br><br>";
                }
                if (strpos($arrFilesFound[$i], "CustomerRepList")) {
                    $arrCustomerRepCSVFiles[] = $arrFilesFound[$i];
                    // echo "Rep File Found: " . $arrFilesFound[$i] . "<br><br>";
                }
            }
        }

echo "Found these RepCodes Files: ";
print_r($arrRepCodesCSVFiles);

echo "<br><br>Found these Customer Reps Files: ";
print_r($arrCustomerRepCSVFiles);
echo "<br><br>";

        rsort($arrCustomerRepCSVFiles);
        $mostRecentCustomerRep = array_shift($arrCustomerRepCSVFiles);

        echo "Processing the most recent Customers Reps CSV file: <a href=\"uploads\\". $mostRecentCustomerRep . "\">".$mostRecentCustomerRep."</a><br><br>";

        $repCustomersFile = "/home/kerussod/public_html/uploads/".$mostRecentCustomerRep;
        $row = 1;
        if ((strlen($mostRecentCustomerRep) > 0) && (($handle = fopen($repCustomersFile, "r")) !== FALSE)) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);

                if ($row == 1) {
                    $truncateRepCustomers_sql = "truncate table reps";
                    $truncateRepCustomers_query = my_db_query($truncateRepCustomers_sql);
                    $fieldCount = $num;
                }

                if(strlen($data[0]) == 0) {
                  break;
                  echo " No data found in file:  " . $mostRecentCustomerRep . "<br>";
                }

                if ($num == $fieldCount) {
                    $tmpl = "INSERT INTO `reps`(`accounts_number`, `accounts_company_name`, `field_rep`, `inside_rep`, `field_group`, `national_group`, `national_rep`, `sales_mgr`) VALUES (%d,'%s','%s','%s','%s','%s','%s','%s')";
                    if ($row != 1) {
                        $insert_sql = sprintf($tmpl, $data[0],  addslashes($data[1]), addslashes($data[2]), addslashes($data[3]), addslashes($data[4]), addslashes($data[5]), addslashes($data[6]), addslashes($data[7]));
                        $insert_query = my_db_query($insert_sql);
//echo $insert_sql . "<br><br>";
                    }
                } else {
                    $tmpl = "INSERT INTO `reps`(`accounts_number`, `accounts_company_name`, `field_rep`, `inside_rep`, `field_group`, `national_group`, `national_rep`, `sales_mgr`) VALUES (%d,'%s','%s','%s','%s','%s','%s','%s')";
                    $insert_sql = sprintf($tmpl, $data[0],  addslashes($data[1]), addslashes($data[2]), addslashes($data[3]), addslashes($data[4]), addslashes($data[5]), addslashes($data[6]), addslashes($data[7]));
                    echo "Skipped row $row because of wrong number of fields. <br>";
                    echo "Should be $fieldCount, found $num.  Typical number of columns in the CSV are about 8.<br>";
                    echo "Query: " . $insert_sql . "<br>";
                    echo "---------------------------------------------------------------------<br>";
                }

                $row++;
            }
            fclose($handle);
            //echo $rows - 1 ."inserted rows <br>";
            //echo $rows ." rows found<br>";
        }



        rsort($arrRepCodesCSVFiles);
        $mostRecentRepCodes = array_shift($arrRepCodesCSVFiles);

        echo "Processing the most recent RepCodes CSV file: <a href=\"uploads\\". $mostRecentRepCodes . "\">".$mostRecentRepCodes."</a><br><br>";

        $repcodesFile = "/home/kerussod/public_html/uploads/".$mostRecentRepCodes;
        $row = 1;
        if ((strlen($mostRecentRepCodes) > 0) && (($handle2 = fopen($repcodesFile, "r")) !== FALSE)) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);

                if ($row == 1) {
                    $truncateRepCodes_sql = "truncate table rep_codes";
                    $truncateRepCodess_query = my_db_query($truncateRepCodes_sql);
                    $fieldCount = $num;
                }

                if(strlen($data[0]) == 0) {
                  break;
                  echo " No data found in file:  " . $mostRecentRepCodes . "<br>";
                }

                if ($num == $fieldCount) {
                    $tmpl2 = "INSERT INTO `reps_codes`(`rep_name`, `rep_code`) VALUES ('%s','%s')";
                    if ($row != 1) {
                        $insert_sql2 = sprintf($tmpl2, addslashes($data[0]),  addslashes($data[1]));
                        $insert_query2 = my_db_query($insert_sql2);
echo $insert_sql2 . "<br><br>";
                    }
                } else {
                    $tmpl2 = "INSERT INTO `reps_codes`(`rep_name`, `rep_code`) VALUES ('%s','%s')";
                    $insert_sql2 = sprintf($tmpl2, addslashes($data[0]),  addslashes($data[1]));
                    echo "Skipped row $row because of wrong number of fields. <br>";
                    echo "Should be $fieldCount, found $num.  Typical number of columns in the Rep Codes CSV are about 2.<br>";
                    echo "Query: " . $insert_sql2 . "<br>";
                    echo "---------------------------------------------------------------------<br>";
                }

                $row++;
            }
            fclose($handle2);
            //echo $rows - 1 ."inserted rows <br>";
            //echo $rows ." rows found<br>";
        }
