<?php
	/**
	 * This file is only needed for the default login algorythm
	 */
	 
	 /**
	  * @var bool MAP-SCRIPT To block direct access to config.php
	  */
	define('MAP-SCRIPT',1);
	include("config.php");
	

	if(isset($_REQUEST["pw"]) && $_REQUEST["pw"] != "") {
		setcookie("pw",sha1($_REQUEST["pw"]),time()+60*60*24*1000); // stay logged in for 1000 days
		if(!$IS_ADMIN)
			echo 'You haven\'t inserted<b></b><br>$PW = "'.sha1($_REQUEST["pw"]).'";<br></b>into the config.php.<br>';
		else {
			header("Location: index.php"); exit;
		}
	}
?>

Generate SHA128-Version of your password (for config.php) and login at once:
<form action="shagen.php" method="POST">
	<input name="pw" type="password" value="Passwort">
	<input type="submit" value="Absenden">
</form><br><br><br>

<b>DEBUG:<br>
======</b><br>$IS_ADMIN :
<?php
	var_dump($IS_ADMIN);
?>