<?php
include "../config.php";
include '../function.php';
define_settings();
mysqli_set_charset($link,"utf8");

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
    
    if($_POST['newuser'] == 1)
    {
        
        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $username = $link->real_escape_string($_POST['usern']);
        $passwd = $link->real_escape_string($_POST['password']);
        $firstname = $link->real_escape_string($_POST['name']);
        $lastname = $link->real_escape_string($_POST['lastname']);
        $isadmin = $link->real_escape_string($_POST['isadmin']);
        $passwd= md5($passwd);
        
        
        
        $link->query("INSERT INTO `users` (`id`, `Username`, `Password`, `level`, `Is_admin`, `Firstname`, `lastname`) VALUES (NULL, '$username', '$passwd', '1', '$isadmin', '$firstname', '$lastname'); ");

        $link->commit();
        $link->close();
    }
    if($_POST['userdel'] == 1)
    {
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $urid= $link->real_escape_string($_POST['userid']);

        
        
        
        $link->query("DELETE FROM `users` WHERE `users`.`id` = '$urid'");
        
        $link->commit();
        $link->close();

        
    }
    if($_POST['change_password'] == 1)
    {
        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $urid= $link->real_escape_string($_POST['user_id']);
        $passwd= $link->real_escape_string($_POST['password']);
        $passwd= md5($passwd);
        
        
        
        
        $link->query("UPDATE `users` SET `Password` = '$passwd' WHERE `users`.`id` = '$urid'; ");
        
        $link->commit();
        $link->close();
        
    }
}
?>
