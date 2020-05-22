<?php
include "../config.php";
include '../function.php';
define_settings();
mysqli_set_charset($link,"utf8");

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
    

    if($_POST['color_change_station'] == 1)
    {
        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $id= $link->real_escape_string($_POST['color_id']);
        $color= $link->real_escape_string($_POST['color']);

        
        
        
        $link->query("UPDATE `RefletorStations` SET `Collor` = '$color' WHERE `RefletorStations`.`ID` = $id; ");
        
        $link->commit();
        $link->close();
        
    }
    if($_POST['color_change_tg'] == 1)
    {
        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $id= $link->real_escape_string($_POST['color_id']);
        $color= $link->real_escape_string($_POST['color']);
        
        
        
        
        $link->query("UPDATE `Talkgroup` SET `Collor` = '$color' WHERE `Talkgroup`.`ID` = $id; ");
        
        $link->commit();
        $link->close();
        
    }
    if($_POST['tgdel'] == 1)
    {
        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $id= $link->real_escape_string($_POST['id']);
       
        
          
        
        $link->query(" DELETE FROM `Talkgroup` WHERE `Talkgroup`.`ID` = $id; ");        
        $link->commit();
        $link->close();
        
    }
    if($_POST['newtg'] == 1)
    {
        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $tgid= $link->real_escape_string($_POST['tgid']);
        $Description= $link->real_escape_string($_POST['Description']);
        $Color= $link->real_escape_string($_POST['Color']);
        
        
        
        
        
        $link->query(" INSERT INTO `Talkgroup` (`ID`, `TG`, `TXT`, `Collor`) VALUES (NULL, '$tgid', '$Description', '$Color');");
        $link->commit();
        $link->close();
        
    }
    
    
    
    
   
}
?>
