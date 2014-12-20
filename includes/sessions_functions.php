<?php

  if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
      $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name) {
      return true;
    }

    function _sess_close() {
      return true;
    }

    function _sess_read($key) {
      $value_query = db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . db_input($key) . "' and expiry > '" . time() . "'");
      $value = db_fetch_array($value_query);

      if (isset($value['value'])) {
        return $value['value'];
      }

      return false;
    }

    function _sess_write($key, $val) {
      global $SESS_LIFE;

      $expiry = time() + $SESS_LIFE;
      $value = $val;

      $check_query = db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . db_input($key) . "'");
      $check = db_fetch_array($check_query);

      if ($check['total'] > 0) {
        return db_query("update " . TABLE_SESSIONS . " set expiry = '" . db_input($expiry) . "', value = '" . db_input($value) . "' where sesskey = '" . db_input($key) . "'");
      } else {
        return db_query("insert into " . TABLE_SESSIONS . " values ('" . db_input($key) . "', '" . db_input($expiry) . "', '" . db_input($value) . "')");
      }
    }

    function _sess_destroy($key) {
      return db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . db_input($key) . "'");
    }

    function _sess_gc($maxlifetime) {
      db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

      return true;
    }

    session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
  }

  function my_session_start() {
      return session_start();
  }

  function my_session_register($variable) {
    global $session_started;

    if ($session_started == true) {
      return session_register($variable);
    } else {
      return false;
    }
  }

  function my_session_is_registered($variable) {
    return session_is_registered($variable);
  }

  function my_session_unregister($variable) {
    return session_unregister($variable);
  }

  function my_session_id($sessid = '') {
    if (!empty($sessid)) {
      return session_id($sessid);
    } else {
      return session_id();
    }
  }

  function my_session_name($name = '') {
    if (!empty($name)) {
      return session_name($name);
    } else {
      return session_name();
    }
  }

  function my_session_close() {
    if (PHP_VERSION >= '4.0.4') {
      return session_write_close();
    } elseif (function_exists('session_close')) {
      return session_close();
    }
  }

  function my_session_destroy() {
    return session_destroy();
  }

  function my_session_save_path($path = '') {
    if (!empty($path)) {
      return session_save_path($path);
    } else {
      return session_save_path();
    }
  }

  function my_session_recreate() {
    if (PHP_VERSION >= 4.1) {
      $session_backup = $_SESSION;

      unset($_COOKIE[session_name()]);

      session_destroy();

      if (STORE_SESSIONS == 'mysql') {
        session_set_save_handler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
      }

      if(!isset($GLOBALS['session_started'])) {
        session_start();
      }

      $_SESSION = $session_backup;
      unset($session_backup);
    }
  }

?>
