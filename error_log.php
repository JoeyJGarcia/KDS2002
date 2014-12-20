<?php
require('includes/application_top.php');
?>

<html>
<head>


</head>
<body style="font-size: 12px;">

<?php

if(isset($GLOBALS['session_started'])) {
      echo 'session_started: TRUE';

} else {
	   echo 'session_started: FALSE';

}
      echo '<br>';

$arrLines = file('error_log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

for($i = 0; $i < count($arrLines); $i++) {
	echo $arrLines[$i] ."<br>";
}


?>
</body>
</html>