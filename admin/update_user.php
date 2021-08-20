<?php

include "../config.php";
include '../function.php';
define_settings();
mysqli_set_charset($link,"utf8");

if(!$_SESSION["loginid"])
{
    die(-1);
    
}

// get user data
$urid = $_SESSION["loginid"];

var_dump($_POST);



if($_POST['password1'] != "")
{
    

    // prepare statement (for multiple inserts) only once
    
    $passwd= $link->real_escape_string($_POST['password1']);
    $passwd2= $link->real_escape_string($_POST['password2']);
  
    
    
    
    if($passwd == $passwd2)
    {
        $link->begin_transaction();
        $link->autocommit(FALSE);
        $passwd= md5($passwd);
        
        
        
        $link->query("UPDATE `users` SET `Password` = '$passwd' WHERE `users`.`id` = '$urid'; ");
        $link->commit();

    }
    else
    {
        
        header("Location: ../user_settings.php?error=2");
        exit();
        
        
    }
    
}

if($_POST['password1'] != "username")
{
    $link->begin_transaction();
    $link->autocommit(FALSE);
    
    $Firstname= $link->real_escape_string($_POST['Firstname']);
    $lastname= $link->real_escape_string($_POST['lastname']);
    $email= $link->real_escape_string($_POST['email']);
    $image_url= $link->real_escape_string($_POST['image_url']);
    
    
    
    
    
    $link->query("UPDATE `users` SET `Firstname` = '$Firstname' , lastname ='$lastname' , email ='$email', image_url ='$image_url' WHERE `users`.`id` = '$urid'; ");
    
    $link->commit();
    
    header("Location: ../user_settings.php?sucess=1");
    

    
    
    
    
}

$link->close();


    



