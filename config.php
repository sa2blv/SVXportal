<?php
$host = "localhost";
$user ="svx";
$password ="!";
$db="svx";

$link =  mysqli_connect($host, $user, $password , $db);
// Site name
$sitename = "2.2";

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


?>
