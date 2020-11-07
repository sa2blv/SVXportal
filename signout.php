<?php
include "config.php";
include 'function.php';
define_settings();
if($_SESSION['languge'])
{
    
    $lang = $_SESSION['languge'];
}
set_laguage();
// destroy the session
session_destroy(); 
session_start();
$_SESSION['languge'] = $lang;

header("Location: index.php"); 