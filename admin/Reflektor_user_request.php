<?php
include "../config.php";
include '../function.php';
define_settings();


    
    $Reflektor_link =  mysqli_connect($reflektor_db_host, $reflektor_db_user, $reflektor_db_password , $reflektor_db_db);
    mysqli_set_charset($Reflektor_link,"utf8");
    if($_POST['newuser'] == 1)
    {
        
        
        $Reflektor_link->begin_transaction();
        $Reflektor_link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $username =   $Reflektor_link->real_escape_string($_POST['Callsign']);
        $passwd   =   $Reflektor_link->real_escape_string($_POST['Password']);
        $mail   =      $Reflektor_link->real_escape_string($_POST['email']);
        $description   =   $Reflektor_link->real_escape_string($_POST['Description']);
   
        $active=0;
        
        $result = mysqli_query($Reflektor_link, "SELECT user FROM `users` where user ='$username'  ");
        $user ="";
        while($row = $result->fetch_assoc())
        {
            $user =  $row['user'];
            
        }
        var_dump($user);
        
        
        
        if(!$user)
        {
        
            $Reflektor_link->query("INSERT INTO `users` (`id`, `user`, `password`, `active`,`description`,`e-mail`) VALUES (NULL, '$username', '$passwd', '$active', '$description', '$mail'); ");
            
            $Reflektor_link->commit();
            $Reflektor_link->close();
        }
    }
    if($_POST['uservalidate'] == 1)
    {
        $username =   $Reflektor_link->real_escape_string($_POST['user']);
       

        $result = mysqli_query($Reflektor_link, "SELECT user FROM `users` where user ='$username'  limit 1");
        
        while($row = $result->fetch_assoc())
        {
            echo $row['user'];
            
        }
        
        
        
    }


    
    
    
    



?>
