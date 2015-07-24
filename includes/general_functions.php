<?php

  function prepareDLOrdersText($value){
   $value = strtoupper(trim(str_replace(",","",$value)));
   $value = htmlspecialchars($value);
   $value = stripslashes($value);
   $value = str_replace("\\","",$value);
   $value = my_null_replace($value);
   return $value;
  }



  function my_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }


  function justDate($dateTime){
    $arrDate = split(" ", $dateTime);
    return $arrDate[0];
  }

  function formatDate($date){//formats the date for a SQL query
      $arrDate = split("/",$date); //input date example 03/01/2005
      return $arrDate[2] ."-" .$arrDate[0] ."-" .$arrDate[1];
  }


  function my_null_replace($value){
        if( $value == "NULL" ){
            return "";
        }else{
            return $value;
        }
  }

    function my_unescape_string($value){
        $value = stripslashes($value);
        return $value;
    }



  function my_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string);
    } else {
      if ($translate == false) {
        return my_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return my_parse_input_field_data($string, $translate);
      }
    }
  }


// Parse the data used in the html tags to ensure the tags will not break
  function my_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }



  function my_redirect($url) {
    //header('Location: ' . $url);
    echo "<META http-equiv=\"refresh\" content=\"0;URL=$url\">";
    //my_exit();
  }


  function my_exit() {
   my_session_close();
   exit();
  }


    function my_unregister_kdsvars(){
       if( my_session_is_registered('isValidated') ){
           my_session_unregister('isValidated');
       }
       if( my_session_is_registered('userlevel') ){
           my_session_unregister('userlevel');
       }
       if( my_session_is_registered('client_account_number') ){
           my_session_unregister('client_account_number');
       }
       if( my_session_is_registered('client_prefix') ){
           my_session_unregister('client_prefix');
       }
    }



    function sendPassword($emailAddress, $password){
        $to = $emailAddress;
        $message = "You requested this information: ".$password;
        $subject = "Information Request from Kerusso Drop Shipping";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: Kerusso Drop Shipping <kds@kerusso.com>' . "\r\n";
        //$headers .= 'Bcc: dropship@kerusso.com' . "\r\n";

        mail($to,$subject,$message,$headers);
    }


    function my_mail_order($order_id, $client_account_number){


        $order_info_sql = "SELECT * FROM orders o WHERE order_id=".$order_id;
        $order_info_query = my_db_query($order_info_sql);
        $order_info = my_db_fetch_array($order_info_query);

        if($order_info['isRush'] == 1){
            $rushMsg = "<div><font color=red><strong>NOTE: This is a <em>RUSH</em> order</strong></font></div>";
            $rushSubject = " (RUSH)";
        }else{
            $rushMsg = "";
            $rushSubject = "";
        }

        $order_status_sql = "SELECT os.order_status_name AS Name
        FROM order_status os WHERE os.order_status_id=".$order_info['order_status'];
        $order_status_query = my_db_query($order_status_sql);
        $order_status = my_db_fetch_array($order_status_query);


        $ordered_products_sql = "SELECT order_product_quantity,
                                        order_product_size,
                                        order_product_name
                                FROM orders_products
                                WHERE order_id=".$order_id;
        $ordered_products_query = my_db_query($ordered_products_sql);

        $products = "";
        while( $ordered_products = my_db_fetch_array($ordered_products_query) ){
            $products .= "\n<tr bgcolor=#FFFFFF><td>".$ordered_products['order_product_quantity']
            ." x ".$ordered_products['order_product_size']." </td><td>".
            $ordered_products['order_product_name']."</td></tr>";
        }

        $order_notes_sql = "SELECT o.purchase_date, os.order_status_name, o.order_comments
        FROM order_status os, orders o WHERE o.order_id =".$order_id." AND
        o.order_status = os. order_status_id ";
        $order_notes_query = my_db_query($order_notes_sql);
        $order_notes = my_db_fetch_array($order_notes_query);

        $order_history_sql = "SELECT oh.order_history_date, os.order_status_name,
        oh.order_history_comments FROM orders_history oh, order_status os, orders o
        WHERE o.order_id=oh.order_id AND oh.order_id=".$order_id." AND
        oh.order_history_status=os.order_status_id ORDER BY oh.order_history_id ASC";
        $order_history_query = my_db_query($order_history_sql);


        $orderNotes = "";
        $notesCount = 0;

        $orderNotes .= "\n<tr bgcolor=#FFFFFF><td><font
        style=&quot;font-size:10pt&quot;>".$order_notes['purchase_date']
        ."</font></td><td><font style=&quot;font-size:10pt&quot;>".
        $order_notes['order_status_name']."</font></td><td><font
        style=&quot;font-size:10pt&quot;>".my_null_replace(my_unescape_string($order_notes['order_comments'])).
        "</font></td></tr><tr bgcolor=#999966><td colspan=3 height=2></td></tr>";

        while( $order_history = my_db_fetch_array($order_history_query) ){
              if($notesCount == 0){
                  $notesCount++;
                  continue;
              }
            $orderNotes .= "\n<tr bgcolor=#FFFFFF><td><font
            style=&quot;font-size:10pt&quot;>".$order_history['order_history_date']
            ."</font></td><td><font style=&quot;font-size:10pt&quot;>".
            $order_history['order_status_name']."</font></td><td><font
            style=&quot;font-size:10pt&quot;>".my_unescape_string($order_history['order_history_comments']).
            "</font></td></tr><tr bgcolor=#999966><td colspan=3 height=2></td></tr>";
            $notesCount++;
        }

        $arrShipDate = split(" ",$order_info['ship_date']);

        $order_ship_date = ($order_info['ship_date'] == "0000-00-00 00:00:00")?"":$arrShipDate[0];
        $notesHeader = ($order_status['Name'] == "New Order")?"Received":"Updated";

        $message = sprintf("<html>
        <body>


        <h3 align=center>Kerusso Drop Ship Order $notesHeader</h3>
        <hr width=70&#37; align=center>

        <div align=center><strong>Order Status: %s</strong></div>
        <br>
        $rushMsg
        <br>
        <table width=100&#37; cellpadding=5 cellspacing=0 style='border: solid black 1px'>

        <tr bgcolor=#EDEEEF>
        <th align=right>PO No.:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>Invoice No.:</th>
        <td>%s</td>
        </tr>


        <tr bgcolor=#EDEEEF>
        <th align=right>Customer Name:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>Shipping Address:</th>
        <td>%s, %s, %s, %s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>Shipping Method:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>Ship Date:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>Tracking Number:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#000000>
        <td colspan=2 valign=top height=1></td>
        </tr>

        <tr bgcolor=#FFFFFF>
        <td colspan=2 valign=top><br></td>
        </tr>

        <tr bgcolor=#FFFFFF>
        <th align=left>Quantity/Size</th><th align=left>Description</th>
        </tr>

        %s

        </table>

        <br>
        <table width=100&#37; cellpadding=5 cellspacing=0 style='border: solid black 1px'>
        <tr><th colspan=3 ALIGN=center bgcolor=#EDEEEF>O R D E R &nbsp;&nbsp; N O T E S</th></tr>
        <tr bgcolor=#EDEEEF><td>Date</td><td>Status</td><td>Comments</td></tr>

        ".stripslashes($orderNotes)."

        </table>

        </body>
        </html>",$order_notes['order_status_name'],
                 $order_info['purchase_order_number'],
                 my_null_replace($order_info['customer_invoice_number']),
                 $order_info['customer_name'],
                 $order_info['customer_address1'],
                 $order_info['customer_city'],
                 $order_info['customer_state'],
                 $order_info['customer_zip'],
                 $order_info['customer_shipping_method'],
                 $order_ship_date,
                 $order_info['order_tracking_number'],
                 $products);


        $accounts_info_sql = "SELECT * FROM accounts
                           WHERE accounts_number='".$client_account_number."'";
        $accounts_info_query = my_db_query($accounts_info_sql);
        $accounts_info = my_db_fetch_array($accounts_info_query);

        $to = $accounts_info['accounts_email'];
        $subject = "[".$order_info["accounts_number"]."] Order # ".my_null_replace($order_info['customer_invoice_number']).
        " ".$notesHeader.$rushSubject;
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Kerusso Drop Shipping <kds@kerusso.com>' . "\r\n";
        //$headers .= 'Bcc: kds@kerusso.com' . "\r\n";
        if($_SERVER["HTTP_HOST"] != 'localhost'){
            mail($to,$subject,$message,$headers);
            mail("kds@kerusso.com",$subject,$message,$headers);
        }else{
            echo "Email sent now.";
        }
    }

    function my_mail_request($customer_name, $customer_invoice_number,
                             $accounts_company_name, $request_details,
                             $accounts_number, $purchase_order_number,
                             $order_status_id, $order_id, $accounts_email,
                             $email_type){

        $rep_group_sql = "SELECT rep_groups_email FROM accounts a, rep_groups r WHERE a.accounts_number=".$accounts_number." AND a.accounts_rep_group=r.rep_groups_id";
        $rep_group_query = my_db_query($rep_group_sql);
        $rep_group = my_db_fetch_array($rep_group_query);



        if($email_type == 'rma'){
            $title_type = "RMA Request";
            $to = 'webcustomerservice@kerusso.com' . ', ';
        }else if($email_type == 'question'){
            $title_type = "Question about Order";
            $to = 'webcustomerservice@kerusso.com' . ', ';
        }else if($email_type == 'discrepancy'){
            $title_type = "Discrepancy with Order";
            $to = 'webcustomerservice@kerusso.com' . ', ';
        }

        $to .= $accounts_email;

        $subject = $title_type." (".$accounts_number."/".$purchase_order_number.")";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: Kerusso Drop Shipping <kds@kerusso.com>' . "\r\n";
        $headers .= 'Cc: '.$rep_group['rep_groups_email']. "\r\n";


        $message = sprintf("<html>
        <body>


        <h3 align=center>Kerusso Drop Ship - $title_type</h3>
        <hr width=70&#37; align=center>

        <br>
        <table width=500 cellpadding=5 cellspacing=0 style='border: solid black 1px'>

        <tr bgcolor=#EDEEEF>
        <th align=right>Company Name:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>Account No:</th>
        <td>%s</td>
        </tr>


        <tr bgcolor=#EDEEEF>
        <th align=right>Customer Name:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>Customer's Invoice:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>Purchase Order No.:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#000000>
        <td colspan=2 valign=top height=1></td>
        </tr>

        <tr bgcolor=#FFFFFF>
        <td colspan=2 valign=top>Provided Details</td>
        </tr>

        <tr bgcolor=#FFFFFF>
        <td colspan=2>
        %s
        </td>

        </tr>

        </table>

        </body>
        </html>",$accounts_company_name,
                 $accounts_number,
                 $customer_name,
                 $customer_invoice_number,
                 $purchase_order_number,
                 my_unescape_string($request_details));

//********* TEST MESAGE START ******************
        $testMsg = sprintf("<html>
        <body>
        <br>
        <table width=500 cellpadding=5 cellspacing=0 style='border: solid black 1px'>

        <tr bgcolor=#EDEEEF>
        <th align=right>Email&nbsp;Type:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>Company&nbsp;Name:</th>
        <td>%s</td>
        </tr>

        <tr bgcolor=#EDEEEF>
        <th align=right>To&nbsp;Addresses:</th>
        <td>%s</td>
        </tr>


        <tr bgcolor=#EDEEEF>
        <th align=right>CC&nbsp;Address(rep&nbsp;email):</th>
        <td>%s</td>
        </tr>

        </table>

        </body>
        </html>",$title_type,
                 $accounts_company_name,
                 $to,
                 $rep_group['rep_groups_email']);
//********* TEST MESAGE END ******************

        try
        {
            $mail_sent = mail($to,$subject,$message,$headers);			

            $order_history_sql = "INSERT into orders_history (order_id,
            order_history_date, order_history_status, order_history_is_notified,
            order_history_comments)	VALUES (".$order_id.",'".date("y-m-d h:i:s")."',
            $order_status_id, 0, '".$title_type." sent <br> Details: ".mysql_real_escape_string($request_details)."')";

            $order_history_query = my_db_query($order_history_sql);

            return $mail_sent ? $title_type." Sent Successfully" : "Email Failure";
        }
        catch(Exception $e){
            return "Error Sending Email";
        }

    }


////
// Return a random value
  function my_rand($min = null, $max = null) {
    static $seeded;

    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

  function getCountryCode($countryName){
      $country_sql = "SELECT countries_iso_code_3 FROM countries WHERE countries_name = '".$countryName."'";
      $country_query = my_db_query($country_sql);
      $country = my_db_fetch_array($country_query);
      return $country['countries_iso_code_3'];
  }

    ?>
