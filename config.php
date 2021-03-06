<?php
	if(!defined('MAP-SCRIPT')) {
		exit;
	}

	/**
	 * @var string $API_KEY The Google Maps Javascript API-Key, obtainable on https://console.developers.google.com/.
	 * @var string $PW sha1-encrypted version of your password (open shagen.php in the webbrowser to see get it)
	 * @var double|float|int $CENTER_X The X-Cordinate of the center
	 * @var double|float|int $CENTER_Y The Y-Cordinate of the center
	 * @var int $DEFAULT_ZOOM The default Zoom
	 * @var int $DEFAULT_ZOOM_ON_SEARCH The default Zoom on searching a place
	 */
	$API_KEY = "";
	$PW = "";
	$CENTER_X = 11.5;
	$CENTER_Y = 50;
	$DEFAULT_ZOOM = 6;
	$DEFAULT_ZOOM_ON_SEARCH = 12;

	/**
	 * @var string $CATEGORIES The categories as JSON-array. Be shure to have pictures 0.[Fileextension], 1.[Fileextension], 2.[Fileextension],... in ./images
	 * @var string $FILE_EXT The File-Extension for the marker-icons, located in ./images. DON'T USE SVG, because they can't be read by a lot of browsers
	 */
	$CATEGORIES = '["Tankstelle", "Pizzaria"]';
	$FILE_EXT = ".png";

	/**
	 * loginalgorythm; Change to your Login-Argorythm
	 * @var bool $IS_ADMIN true, if the visitor should have admin-rights
	 */

	if(sha1($_GET["pw"]) == $PW || sha1($_POST["pw"]) == $PW)
		setcookie("pw",$PW,time()+60*60*24*1000); // stay logged in for 1000 days


	if((sha1($_REQUEST["pw"]) == $PW || $_COOKIE["pw"] == $PW) && !isset($_REQUEST["see_public"]))
		$IS_ADMIN = true;
	else
		$IS_ADMIN = false;

?>
