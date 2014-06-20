<?php



////

// The HTML href link wrapper function

  function my_href_link($page = '', $parameters = '' ) {

    global $session_started, $SID;



    if (!my_not_null($page)) {

      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');

    }



    $link = HTTP_SERVER . DIR_WS_HTTP_HOMEDIR;

    $_sid = my_session_name() . '=' . my_session_id();



    if (my_not_null($parameters)) {

      $link .= $page . '?' . my_output_string($parameters);

      $separator = '&';

    } else {

      $link .= $page;

      $separator = '?';

    }



    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);



// Add the session ID when SID is defined

    if ( ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {

      if (my_not_null($SID)) {

        $_sid = $SID;

      } 

    }



    if (isset($_sid)) {

      $link .= $separator . $_sid;

    }



    return $link;

  }



////

// The HTML image wrapper function

  function my_image($src, $alt = '', $width = '', $height = '', $parameters = '') {

    if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {

      return false;

    }



// alt is added to the img tag even if it is null to prevent browsers from outputting

// the image filename as default

    $image = '<img src="' . my_output_string($src) . '" border="0" alt="' . my_output_string($alt) . '"';



    if (my_not_null($alt)) {

      $image .= ' title=" ' . my_output_string($alt) . ' "';

    }



    if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {

      if ($image_size = @getimagesize($src)) {

        if (empty($width) && my_not_null($height)) {

          $ratio = $height / $image_size[1];

          $width = $image_size[0] * $ratio;

        } elseif (my_not_null($width) && empty($height)) {

          $ratio = $width / $image_size[0];

          $height = $image_size[1] * $ratio;

        } elseif (empty($width) && empty($height)) {

          $width = $image_size[0];

          $height = $image_size[1];

        }

      } elseif (IMAGE_REQUIRED == 'false') {

        return false;

      }

    }



    if (my_not_null($width) && my_not_null($height)) {

      $image .= ' width="' . my_output_string($width) . '" height="' . my_output_string($height) . '"';

    }



    if (my_not_null($parameters)) $image .= ' ' . $parameters;



    $image .= '/>';



    return $image;

  }



////

// The HTML form submit button wrapper function

// Outputs a button in the selected language

  function my_image_submit($image, $alt = '', $parameters = '') {


    $image_submit = '<input type="image" src="' . my_output_string( 'images/' . $image) . '" border="0" alt="' . my_output_string($alt) . '"';

    if (my_not_null($alt)) $image_submit .= ' title=" ' . my_output_string($alt) . ' "';

    if (my_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= '/>';

    return $image_submit;

  }

////

// Output a function button in the selected language

  function my_button( $text = '', $parameters = '') {

    $the_button = '<input type="button"  value="' . my_output_string($text) .
    '" title="' . my_output_string($text) . '"';


    if (my_not_null($parameters)) $the_button .= ' ' . $parameters;

    $the_button .= '/>';

    return $the_button;

  }



////

// Output a separator either through whitespace, or with an image

  function my_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {

    return my_image(DIR_WS_IMAGES . $image, '', $width, $height);

  }



////

// Output a separator either through whitespace, or with an image

  function my_draw_spacer($image = 'spacer.gif', $width = '100%', $height = '1') {

    return my_image(DIR_WS_IMAGES . $image, '', $width, $height);

  }



////

// Output a form

  function my_draw_form($name, $action, $method = 'post', $parameters = '') {

    $form = '<form name="' . my_output_string($name) . '" action="' . my_output_string($action) . '" method="' . my_output_string($method) . '"';



    if (my_not_null($parameters)) $form .= ' ' . $parameters;



    $form .= '>';



    return $form;

  }



////

// Output a form input field

  function my_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true) {

    $field = '<input type="' . my_output_string($type) . '" name="' . my_output_string($name) . '"';



    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {

      $field .= ' value="' . my_output_string(stripslashes($GLOBALS[$name])) . '"';

    } elseif (my_not_null($value)) {

      $field .= ' value="' . my_output_string($value) . '"';

    }



    if (my_not_null($parameters)) $field .= ' ' . $parameters;



    $field .= '/>';



    return $field;

  }



////

// Output a form password field

  function my_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {

    return my_draw_input_field($name, $value, $parameters, 'password', false);

  }



////

// Output a selection field - alias function for my_draw_checkbox_field() and my_draw_radio_field()

  function my_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {

    $selection = '<input type="' . my_output_string($type) . '" name="' . my_output_string($name) . '"';



    if (my_not_null($value)) $selection .= ' value="' . my_output_string($value) . '"';



    if ( ($checked == true) || ( isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ( ($GLOBALS[$name] == 'on') || (isset($value) && (stripslashes($GLOBALS[$name]) == $value)) ) ) ) {

      $selection .= ' CHECKED';

    }



    if (my_not_null($parameters)) $selection .= ' ' . $parameters;



    $selection .= '>';



    return $selection;

  }



////

// Output a form checkbox field

  function my_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {

    return my_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);

  }



////

// Output a form radio field

  function my_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {

    return my_draw_selection_field($name, 'radio', $value, $checked, $parameters);

  }



////

// Output a form textarea field

  function my_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {

    $field = '<textarea name="' . my_output_string($name) . '" wrap="' . my_output_string($wrap) . '" cols="' . my_output_string($width) . '" rows="' . my_output_string($height) . '"';



    if (my_not_null($parameters)) $field .= ' ' . $parameters;



    $field .= '>';



    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {

      $field .= stripslashes($GLOBALS[$name]);

    } elseif (my_not_null($text)) {

      $field .= $text;

    }



    $field .= '</textarea>';



    return $field;

  }



////

// Output a form hidden field

  function my_draw_hidden_field($name, $value = '', $parameters = '') {

    $field = '<input type="hidden" name="' . my_output_string($name) . '"';



    if (my_not_null($value)) {

      $field .= ' value="' . my_output_string($value) . '"';

    } elseif (isset($GLOBALS[$name])) {

      $field .= ' value="' . my_output_string(stripslashes($GLOBALS[$name])) . '"';

    }



    if (my_not_null($parameters)) $field .= ' ' . $parameters;



    $field .= '/>';



    return $field;

  }



////

// Hide form elements

  function my_hide_session_id() {

    global $session_started, $SID;



    if (($session_started == true) && my_not_null($SID)) {

      return my_draw_hidden_field(my_session_name(), my_session_id());

    }

  }



////
// Output a form pull down menu
  function my_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {

    $field = '<select name="' . my_output_string($name) . '"';

    if (my_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . my_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }


      $field .= '>' . my_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }



////
// Output a form pull down menu
  function my_draw_bulk_orders_pull_down_menu_min($name, $values, $default = '', $parameters = '', $required = false) {
    
    $field = '<select name="' . my_output_string($name) . '"';

    if (my_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

$options = '';
    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
    
    	$slashPOS=   strpos($values[$i]['text'], '/');
    	$prodId=trim(substr($values[$i]['text'], 0, $slashPOS));
      if ($default != $prodId) 
    	continue;
    
      $options = '<option value="' . my_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }
      
      $offset =  ( strpos($values[$i]['text'], '/') )?strpos($values[$i]['text'], '/'): strlen($default);
      if ($default == trim(substr($values[$i]['text'], 0, $offset)) ) {
        $options .= ' SELECTED';
      }


      $options .= '>'. my_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= $options .'</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Output a form pull down menu
  function my_draw_bulk_orders_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    
    $field = '<select name="' . my_output_string($name) . '"';

    if (my_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . my_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' SELECTED';
      }
      
      $offset =  ( strpos($values[$i]['text'], '/') )?strpos($values[$i]['text'], '/'): strlen($default);
      if ($default == trim(substr($values[$i]['text'], 0, $offset)) ) {
        $field .= ' SELECTED';
      }


      $field .= '>'. my_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }




////

// Creates a pull-down list of countries

  function my_get_country_list($name, $selected = '', $parameters = '') {

    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));

    $countries = my_get_countries();



    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {

      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);

    }



    return my_draw_pull_down_menu($name, $countries_array, $selected, $parameters);

  }

?>