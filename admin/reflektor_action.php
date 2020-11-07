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
        
        $username =   $Reflektor_link->real_escape_string($_POST['usern']);
        $passwd   =   $Reflektor_link->real_escape_string($_POST['password']);
        $active   =   $Reflektor_link->real_escape_string($_POST['Enable']);
        $mail   =      $Reflektor_link->real_escape_string($_POST['mail']);
        $description   =   $Reflektor_link->real_escape_string($_POST['description']);
        if($active !="1")
        {
            $active=0;
        }

        
        
        
        $Reflektor_link->query("INSERT INTO `users` (`id`, `user`, `password`, `active`,`description`,`e-mail`) VALUES (NULL, '$username', '$passwd', '$active', '$description', '$mail'); ");

        $Reflektor_link->commit();
        $Reflektor_link->close();
    }
    if($_POST['userdel'] == 1)
    {
        if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
            $Reflektor_link->begin_transaction();
            $Reflektor_link->autocommit(FALSE);
            
            // prepare statement (for multiple inserts) only once
            
            $urid= $link->real_escape_string($_POST['userid']);
    
            
            
            
            $Reflektor_link->query("DELETE FROM `users` WHERE `users`.`id` = '$urid'");
            
            $Reflektor_link->commit();
            $Reflektor_link->close();
        }
        
    }
    if($_POST['change_password'] == 1)
    {
        if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
        
            $Reflektor_link->begin_transaction();
            $Reflektor_link->autocommit(FALSE);
            
            // prepare statement (for multiple inserts) only once
            
            $urid= $Reflektor_link->real_escape_string($_POST['user_id']);
            $passwd= $Reflektor_link->real_escape_string($_POST['password']);
    
           
            
            $Reflektor_link->query("UPDATE `users` SET `password` = '$passwd' WHERE `id` = '$urid'; ");
            
            $Reflektor_link->commit();
            $Reflektor_link->close();
        }
        
    }
    if($_POST['change_active'] == 1)
    {
        if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
            
      
            $Reflektor_link->begin_transaction();
            $Reflektor_link->autocommit(FALSE);
            
            // prepare statement (for multiple inserts) only once
            
            $urid= $Reflektor_link->real_escape_string($_POST['user_id']);
            $active   =   $Reflektor_link->real_escape_string($_POST['Enable']);
            echo $active;
    
            if($active !="1")
            {
                $active=0;
            }
            
            
            
            
            $Reflektor_link->query("UPDATE `users` SET `active` = '$active' WHERE `id` = '$urid'; ");
            
            $Reflektor_link->commit();
            $Reflektor_link->close();
        
        }
        
    }

    if($_POST['send_msg'] == "1")
    {
        if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 )
        {
            
        
            $userid =   $Reflektor_link->real_escape_string($_POST['user_id']);
            
            $email_adress ="";
            
            $result = mysqli_query($Reflektor_link, "SELECT `e-mail` FROM `users` WHERE `id` = $userid ");
            
            while($row = $result->fetch_assoc())
            {
    
                $email_adress = $row['e-mail'];
                
            }
            
            
            
            $subject = 'Mesage from svxportal Admin';
            $message = $_POST['msg'];
            $headers = 'From: '.SYSTEM_MAIL.'' . "\r\n" .
                'Reply-To: '.SYSTEM_MAIL.'' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            
            mail($email_adress, $subject, $message, $headers);
            echo "sucsess";
        }
        
        
    }
    
    if($_POST['Send_password'] == 1)
    {
        if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 )
        {
            
            
            $userid =   $Reflektor_link->real_escape_string($_POST['user_id']);
            
            
            $result = mysqli_query($Reflektor_link, "SELECT * FROM `users` WHERE `id` = $userid ");
            
            while($row = $result->fetch_assoc())
            {
                $server =REFLEKTOR_SERVER_ADRESS;
                $user =$row['user'];
                $password = $row['password'];
                $port =REFLEKTOR_SERVER_PORT;
                
                $msg = "This mail contain login credntials for the svxreflektor \r\n";
                $msg.="Server adress : $server       \r\n";
                $msg.="Server port   : $port       \r\n";
                
                $msg.="Username: $user \r\n";
                $msg.="Password: $password \r\n\r\n";
         
    
    
    
    
    
    
    
    
                EOT;
                
                
                $subject = 'Login credentials from svxportal Admin';
                $message = $msg;
                $headers = 'From: '.SYSTEM_MAIL.'' . "\r\n" .
                    'Reply-To: '.SYSTEM_MAIL.'' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                
                mail($row['e-mail'], $subject, $message, $headers);
                echo "sucsess";
                
                
                
                
                
            }
        }
        
        
    }
    
    
    


?>
