<?php


// This function validates a plain text password with an
// encrpyted password
  function validate_password($plain, $encrypted) {

    if (my_not_null($plain) && my_not_null($encrypted)) {
// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    return false;
  }


// This function makes a new password from a plaintext password. 
  function encrypt_password($plain) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= my_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }
?>
