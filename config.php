<?php
$host = "localhost";
$user ="svx";
$password ="!";
$db="svx";

$link =  mysqli_connect($host, $user, $password , $db);
// Site name
//$sitename = "2.2";

// Live link
$livelink="http://svxportal.sm2ampr.net:8000/svxcard96";

// Svx recording folder
$dir="/var/www/svxrecording";
 
// svxreflektor server adress;
$serveradress ="http://sk3w.se/svxreflektor/reflektor%20proxy/";

// login form recording
//$use_logein= true;
// Iframe under system description
//$iframe_documentation_url  ="http://sk3w.se/dokuwiki/doku.php?id=svxreflector&do=export_xhtml";

// Default Position
$default_lat ="62.676160";
$default_long ="17.633479";
$default_zoom =5;

// default tg player
$default_tg_player = 240;

// Default date
$start_date_defined ="2020-01-12";

// Mysql_Reflektor
$reflektor_db = 0 ;
/*
$reflektor_db_host =	 	"127.0.0.1";
$reflektor_db_user = 	"svxreflektor";
$reflektor_db_password = 	"94iXGNP09MttIJhp!a!";
$reflektor_db_db = 		"svxreflektor";
*/


$use_mqtt=false;
$mqtt_host = "";
$mqtt_port = "";
$mqtt_TLS = "";



?>
