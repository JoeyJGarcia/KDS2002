<?php

require('includes/application_top.php');

?>


<!doctype html>
<html>
<head>
  <title>Kerusso Drop Ship</title>
  <link rel="stylesheet" href="styles.css" type="text/css"/>
  <script language="JavaScript" src="debugInfo.js"></script>

</head>
<body>

<?php

if( !isset($_SESSION['isValidated']) || $_SESSION['isValidated'] != 'yes'){

     if( $_GET['action'] == 'process' ){
        $process_user_sql = sprintf("SELECT login_username, login_password, login_reset_password,
        login_userlevel, login_to_accounts_id FROM login WHERE login_username='%s' 
        GROUP BY login_reset_password", mysql_real_escape_string($_POST['user_name']),
        mysql_real_escape_string($_POST['pass_word']));

        $process_user_query = my_db_query($process_user_sql);
        $process_user = my_db_fetch_array($process_user_query);
        $numRows = my_db_num_rows($process_user_query);

/*
        $temp = explode(':', $process_user['login_password']);
        echo "userDB: ". $process_user['login_username']."<br>";
		echo "epDB: ". $process_user['login_password']."<br>";
		echo "salt: ". $temp[1]."<br>";
		echo "pass: ". $_POST['pass_word']."<br>";
		$p = $temp[1] . $_POST['pass_word'];
		echo "encryptPass: ". $p."<br>";
		echo "epMD5: ".md5($p)."<br>";
 */       
        if( validate_password($_POST['pass_word'], $process_user['login_password']) ){
        	
        	$passwordMatches = true;

            //Get company name, make it a session variable
            $account_company_name_sql = "SELECT * FROM `accounts` WHERE  `accounts_id`=" .
            $process_user['login_to_accounts_id'];
            $account_company_name_query = my_db_query($account_company_name_sql);
            $account_company_name = my_db_fetch_array($account_company_name_query);
            my_session_register('company_name');
            $_SESSION['company_name'] = $account_company_name['accounts_company_name'];
            my_session_register('client_account_number');
            $_SESSION['client_account_number'] = $account_company_name['accounts_number'];
            my_session_register('client_prefix');
            $_SESSION['client_prefix'] = $account_company_name['accounts_prefix'];
            my_session_register('rep_group');
            $_SESSION['rep_group'] = $account_company_name['accounts_rep_group'];
            my_session_register('price_level');
            $_SESSION['price_level'] = $account_company_name['accounts_price_level'];


            //Set User Level
            my_session_register('userlevel');
            if( $process_user['login_userlevel'] == 0 ){
                $_SESSION['userlevel'] = 'client';
            }elseif( $process_user['login_userlevel'] == 1 ){
                $_SESSION['userlevel'] = 'admin';
            }elseif( $process_user['login_userlevel'] == 2 ){
                $_SESSION['userlevel'] = 'super';
            }


            if( $passwordMatches && $process_user['login_reset_password'] == 0 ){
                   $_SESSION['isValidated'] = 'yes';
                   my_session_register('isValidated');
            }elseif( $passwordMatches && $process_user['login_reset_password'] == 1 ){
                   $_SESSION['isValidated'] = 'reset';
                   my_session_register('isValidated');
            }else{
                   $_SESSION['isValidated'] = 'no';
                   if( my_session_is_registered('isValidated') ){
                       my_session_unregister('isValidated');
                   }
                   if( my_session_is_registered('userlevel') ){
                       my_session_unregister('userlevel');
                   }
		           if( my_session_is_registered('rep_group') ){
		               my_session_unregister('rep_group');
		           }
            }
        }else{
           $_SESSION['isValidated'] = 'no';
           $_SESSION['userlevel'] = 'client';
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
           if( my_session_is_registered('rep_group') ){
               my_session_unregister('rep_group');
           }
           if( my_session_is_registered('price_level') ){
               my_session_unregister('price_level');
           }
           echo "<div class=\"failHeader\" align=center>User Information Not Validated!</div>";
        }

     } //end of  action == 'process' check
     elseif(  $_GET['action'] == 'logout'  ){
           $_SESSION['isValidated'] = 'no';
           $_SESSION['userlevel'] = 'client';

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
           if( my_session_is_registered('rep_group') ){
               my_session_unregister('rep_group');
           }
            if( my_session_is_registered('price_level') ){
               my_session_unregister('price_level');
           }
     //end of  action == 'logout' check
     }elseif(   $_GET['action'] == 'reset'  ){

        $reset_sql = sprintf("SELECT login_password FROM login WHERE login_username='%s' and login_reset_password=1", 
        mysql_real_escape_string($_POST['user_name']) );
        
        $reset_query = my_db_query($reset_sql);
        $reset_user = my_db_fetch_array($reset_query);
		
		if( !validate_password($_POST['pass_word_old'], $reset_user['login_password']) ){
            echo "<div class=\"failHeader\" align=center>Original Information Does
            Not Validate.  Try Again.</div><br><br>";
        }else {
            if( strcasecmp($pass_word_new,$pass_word_confirm) == 0 ){
                $update_sql = sprintf("UPDATE login SET login_password ='%s', login_reset_password = 0
                WHERE login_username ='%s'",  encrypt_password(mysql_real_escape_string($_POST['pass_word_confirm'])),
                mysql_real_escape_string($_POST['user_name']) );
                $update_query = my_db_query($update_sql);
                if($update_query){
                    echo "<div class=\"successHeader\" align=center>Password Reset!</div>";
                    $_SESSION['isValidated'] = 'yes';
                    my_session_register('isValidated');
                    $account_company_name_sql = "SELECT * FROM `accounts` WHERE
                    `accounts_username`='".$_POST['user_name']."'";
                    $account_company_name_query = my_db_query($account_company_name_sql);
                    $account_company_name = my_db_fetch_array($account_company_name_query);

                    my_session_register('company_name');
                    $_SESSION['company_name'] = $account_company_name['accounts_company_name'];
                    my_session_register('client_account_number');
                    $_SESSION['client_account_number'] = $account_company_name['accounts_number'];
                    my_session_register('client_prefix');
                    $_SESSION['client_prefix'] = $account_company_name['accounts_prefix'];
                }
            }else{
                echo "<div class=\"failHeader\" align=center>New Passwords Don't Match.
                Try Again.</div><br><br>";
            }

        }

     }

}

    if($_GET['action'] == "sent_pass"){
        $verify_username_sql = sprintf("SELECT COUNT(*) AS Count FROM login WHERE
        login_username='%s'", mysql_real_escape_string($_POST['user_name']) );
        $verify_username_query = my_db_query($verify_username_sql);
        $verify_username = my_db_fetch_array($verify_username_query);

        if( $verify_username['Count'] == 1  ){
            echo "<div class=\"successHeader\" align=center>Password was sent to
            email address on record.</div>";
            $get_pass_sql = sprintf("SELECT  a.accounts_email, l.login_password
            FROM accounts a, login l WHERE l.login_username='%s' and a.accounts_username='%s'",
            mysql_real_escape_string($_POST['user_name']),mysql_real_escape_string($_POST['user_name']) );
            $get_pass_query = my_db_query($get_pass_sql);
            $get_pass = my_db_fetch_array($get_pass_query);

            sendPassword($get_pass['accounts_email'], $get_pass['login_password']);
        }else{
            echo "<div class=\"failHeader\" align=center>No Match for Username
            Submitted. Try Again.</div><br><br>";
        }
    }


    if(  $_GET['action'] == 'logout'  ){
           $_SESSION['isValidated'] = 'no';
           $_SESSION['userlevel'] = '';
            my_unregister_kdsvars();
            my_redirect("http://www.kerussods.com");
     }//end of  action == 'logout' check





?>
<table align=right><tr><td><div onClick="showDebugInfo('debugInfo')">[X]</div></td></tr></table>

<?php
    if( my_session_is_registered('isValidated') && $_SESSION['isValidated'] == 'yes' ){
        include('navigation.php');
     }
    include('debug_info.php');
?>

<?php
  if( my_session_is_registered('isValidated') && $_SESSION['isValidated'] == 'yes'   ){

      if( $_SESSION['userlevel'] == 'admin' ||  $_SESSION['userlevel'] == 'super'){
          include($HTTP_SERVER.$DIR_WS_HTTP_HOMEDIR.$DIR_WS_INCLUDES.'admindashboard.php');
      }else{
          include('clientdashboard.php');
      }

  }elseif( my_session_is_registered('isValidated') && $_SESSION['isValidated'] == 'reset'   ){
          include('login_reset.php');
  }elseif($_GET['action'] == 'send_pass'){
          include('send_pass.php');
  }else{
          include('login.php');
  }
?>





</body>
</html>
