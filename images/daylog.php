<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include "config.php";



$id  = mysqli_real_escape_string($link,$_GET['id']);



$sql  = 'INSERT INTO `Daylog` (`ID`, `Repeater`, `Date`) VALUES (NULL, \''.$id.'\', CURRENT_TIMESTAMP)';
mysqli_query($link,$sql) 
or die(mysqli_error($link));

$sql  = "UPDATE repeater SET Openings= Openings+ 1 WHERE id= '".$id."'";


mysqli_query($link,$sql) 
or die(mysqli_error($link));

?>