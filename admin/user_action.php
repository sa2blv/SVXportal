<?php
include "../config.php";
include '../function.php';
define_settings();
mysqli_set_charset($link,"utf8");

if($_POST['registernewuser'] == 1)
{
    
    $username = $link->real_escape_string($_POST['username']);
    
    $result = mysqli_query($link, "SELECT * FROM `users` WHERE `Username` = '$username' ORDER BY `Username` ASC  ");
    
    
    while($row = $result->fetch_assoc()) {
        
        if($row["Username"])
        {
            echo -1;
            exit(-1);
        }
    }
    
    
    $link->begin_transaction();
    $link->autocommit(FALSE);
    
    // prepare statement (for multiple inserts) only once
    
    
    $passwd = $link->real_escape_string($_POST['password']);
    $firstname = $link->real_escape_string($_POST['Firstname']);
    $lastname = $link->real_escape_string($_POST['lastname']);
    $email = $link->real_escape_string($_POST['Email']);
    
    
    $isadmin = "0";
    $passwd= md5($passwd);
    
    
    
    $link->query("INSERT INTO `users` (`id`, `Username`, `Password`, `level`, `Is_admin`, `Firstname`, `lastname`, `email`) VALUES (NULL, '$username', '$passwd', '1', '$isadmin', '$firstname', '$lastname','$email'); ");
    
    $link->commit();
    $link->close();
}






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
        
        
        $link->query("INSERT INTO `users` (`id`, `Username`, `Password`, `level`, `Is_admin`, `Firstname`, `lastname`, `email`) VALUES (NULL, '$username', '$passwd', '1', '$isadmin', '$firstname', '$lastname',''); ");

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
   
    if($_POST['Assignpermission'] == "1")
    {

        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $urid= $link->real_escape_string(trim($_POST['user_id']));
        $page_id = $link->real_escape_string(trim($_POST['station_page_id']));

        
        
        $result = mysqli_query($link, "SELECT * FROM `User_Premission` where  `Station_id` = '$page_id' AND User_id = '$urid' ORDER BY `id` ASC  ");
        

     
        $stage =0;
        $update_id=0;
        while($row = $result->fetch_assoc()) {
            
   
                $stage =1;
                $update_id=$row["id"];           
        }
        


    
        
    
        
        if($stage == 1)
        {
            if($_POST['writeuser'] == "1")
            {
                $link->query("UPDATE `User_Premission` SET `RW` = '1' WHERE `User_Premission`.`id` = '$update_id'; ");
            }
            elseif($_POST['readuser'] == "1")
            {
                $link->query("UPDATE `User_Premission` SET `RW` = '0' WHERE `User_Premission`.`id` = '$update_id'; ");
            }
            else
            {
                $link->query("DELETE FROM `User_Premission` WHERE `User_Premission`.`id` = '$update_id' ");
            }
        }
        else 
        {
            
            // 
            $read_val =-1;
            if($_POST['writeuser'] == "1")
            {
                $read_val =1;
            }
            elseif($_POST['readuser'] == "1")
            {
                
                $read_val =0;
            }
            else
            {
                
            }
       
            if($read_val > -1)
            {
                $link->query("INSERT INTO `User_Premission` (`id`, `Station_id`, `User_id`, `RW`) VALUES (NULL, '$page_id', '$urid', '$read_val');");
            }
        }
        
        
        
        
        
        $link->commit();
        $link->close();
        
    }
    
    
    
    
}
?>
