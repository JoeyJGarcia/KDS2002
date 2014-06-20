<?php
require('includes/application_top.php');
?>
<html>
<head>

</head>
<body>
<?php
$selectPasswords_sql = "select login_id, login_username, login_password from login";

$selectPasswords_query = my_db_query($selectPasswords_sql);

echo "Queried ".my_db_num_rows($selectPasswords_query)." rows.<br>";
	while($selectPasswords = my_db_fetch_array($selectPasswords_query)){
		$u = $selectPasswords['login_username'];
		$p= $selectPasswords['login_password'];
		
		echo "Username ".$u."<br>";
		echo "Password ".$p."<br>";
		
		$loginId = $selectPasswords['login_id'];
		$encPassword = encrypt_password($selectPasswords['login_password']);
		
		$updatePassword_sql = "Update login set login_password ='".
		$encPassword."' where login_id=".$loginId;
		
		echo "SQL: ".$updatePassword_sql."<BR>";
		
		my_db_query($updatePassword_sql);
	}



?>

</body></html>