<?php

// set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// include server parameters
  require('includes/configure.php');


// set php_self in the local scope
  if (!isset($PHP_SELF)) $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// include the database functions
  require(DIR_WS_INCLUDES . 'database_functions.php');

// make a connection to the database... now
  my_db_connect() or die('Unable to connect to database server!');

// define general functions used application-wide
  require(DIR_WS_INCLUDES . 'general_functions.php');
  require(DIR_WS_INCLUDES . 'UIHelpers_functions.php');
  require(DIR_WS_INCLUDES . 'html_output_functions.php');
  require(DIR_WS_INCLUDES . 'ajax_model_functions.php'); 

// define how the session functions will be used
  require(DIR_WS_INCLUDES . 'sessions_functions.php');

//verify user is validated
//if( !isset($isValidated) && $isValidated != 'yes'  ){
if( $isValidated != 'yes'  ){
    //$url = "http://".$_SERVER["HTTP_HOST"]."/kds/index.php";
    //echo "<META http-equiv=\"refresh\" content=\"10;URL=$url\">";
}

// set the session name and save path
  session_name('kdssid');
  session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, $cookie_path, $cookie_domain);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }

// set the session ID if it exists
   if (isset($HTTP_POST_VARS[my_session_name()])) {
     my_session_id($HTTP_POST_VARS[my_session_name()]);
   } elseif ( ($request_type == 'SSL') && isset($HTTP_GET_VARS[my_session_name()]) ) {
     my_session_id($HTTP_GET_VARS[my_session_name()]);
   }

// set the session ID if it exists
   if (isset($HTTP_POST_VARS[my_session_name()])) {
     my_session_id($HTTP_POST_VARS[my_session_name()]);
   }

// start the session
  $session_started = false;
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    my_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

    if (isset($HTTP_COOKIE_VARS['cookie_test'])) {
      my_session_start();
      $session_started = true;
    }
  } else {
    my_session_start();
    $session_started = true;
  }

// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');

// include the password crypto functions
  require(DIR_WS_INCLUDES . 'password_functions.php');
?>

