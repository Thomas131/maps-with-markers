<?php
	 /**
	  * @var bool MAP-SCRIPT To block direct access to config.php
	  */
	define('MAP-SCRIPT',1);
	require("config.php");

	if($IS_ADMIN) {
		if(isset($_REQUEST["data"])) {
			if(json_decode($_REQUEST["data"],true) != NULL) { //Is the JSON-Object Parsable with PHP without errors?
				//Make a Backup of the data
				copy("data.json","backup/data_".date("d.m.Y_H:i:s:u").".json");

				//Write the data
				echo file_put_contents("data.json", $_REQUEST["data"]);
			} else {
				header("HTTP/1.0 400 Bad Request");
				die("Daten fehlerhaft!");
			}
		} else {
			header("HTTP/1.0 400 Bad Request");
			die("Keine Daten mitgeschickt!");
		}
	} else {
		header("HTTP/1.0 403 Forbidden");
		die("<h1>403 Forbidden!</h1>");
	}
?>