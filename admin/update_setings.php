<?php
include "../config.php";
include '../function.php';
define_settings();
mysqli_set_charset($link,"utf8");

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
    
    
    $link->begin_transaction();
    $link->autocommit(FALSE);
    
    // prepare statement (for multiple inserts) only once
    
    
    foreach ($_POST as $define => $value ) {
    
    
    
        $define = $link->real_escape_string($define);
        $value = $link->real_escape_string($value);
        $link->query("UPDATE `Settings` SET `value` = '$value' WHERE `Settings`.`Define` = '$define'; ");
    
    }
    
    $link->commit();
    $link->close();
 }
 ?>
