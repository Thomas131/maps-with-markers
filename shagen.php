<?php
	/**
	 * This file is only needed for the default login algorythm
	 */

	if(isset($_REQUEST["pw"]) && $_REQUEST["pw"] != "") {
		echo 'If not done yet, you will have to insert <br>$PW = "'.sha1($_REQUEST["pw"]).'";<br>into the config.php.<br>';
		setcookie("pw",sha1($_REQUEST["pw"]),time()+60*60*24*1000); // stay logged in for 1000 days
	}
?>

SHA-Version von einem Passwort generieren & gleichzeitig einloggen:
<form action="shagen.php" method="POST">
	<input name="pw" type="password" value="Passwort">
	<input type="submit" value="Absenden">
</form>