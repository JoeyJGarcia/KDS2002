<?php

if( isset($_SESSION['isValidated']) ){
	$isValidated = $_SESSION['isValidated'];
}


if( !($isValidated == 'yes')  ){
    $url = "http://".$_SERVER["HTTP_HOST"]."/index.php";
    echo "<META http-equiv=\"refresh\" content=\"0;URL=$url\">";
}

     echo '<div class="NavHeader" align=left>';
     echo '<a href="' . my_href_link('index.php', 'action=logout') . '">[LOGOUT]</a>';
     echo '&nbsp;&nbsp;&nbsp;';
     echo '<a href="' . my_href_link('index.php') . '">[MAIN MENU]</a>';
     if($_SESSION['userlevel'] == "super" ){
     echo '&nbsp;&nbsp;&nbsp;'.my_image('/images/super.gif','You Are A Super Admin','30','23','style=\'position:relative; top:5px;\'');
     }
     echo '</div>';


?>
