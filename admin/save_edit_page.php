<?php
include "../config.php";
include '../function.php';
define_settings();
mysqli_set_charset($link,"utf8");

if( $_SESSION['loginid'] >0 )
{
   
    
    
    if($_POST['Information_edit'] == "1")
    {
    
        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        
        $station =htmlentities($_POST['station']);
//        $station= htmlspecialchars($station, ENT_QUOTES, "UTF-8");
        $station= $link->real_escape_string($station);
        

        
        
        
        $id= $link->real_escape_string($_POST['id']);
        
        $station_id =page_id_to_staion_id($id);

        $premission_rw =check_premission_station_RW($station_id,$_SESSION['loginid']);

        
        if($premission_rw >0)
        {
            $link->query(" UPDATE `Infotmation_page` SET `Html` = '$station' where id ='$id' ");
            echo " UPDATE `Infotmation_page` SET `Html` = '$station' where id ='$id' ";
        }

        
        $link->commit();
        $link->close();
        
    }
    
    if($_POST['Hardware_edit'] == "1")
    {
        
        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        

        $station =htmlentities($_POST['hardware']);
        //        $station= htmlspecialchars($station, ENT_QUOTES, "UTF-8");
        $station= $link->real_escape_string($station);
        

        
        

        $id= $link->real_escape_string($_POST['id']);
        
        
        $station_id =page_id_to_staion_id($id);
        $premission_rw =check_premission_station_RW($station_id,$_SESSION['loginid']);
        
        if($premission_rw >0)
            $link->query(" UPDATE `Infotmation_page` SET `Hardware_page` = '$station' where id ='$id' ");
        
        
        
        $link->commit();
        $link->close();
        
    }
    if($_POST['Mew_DTNF'] == "1")
    {
        

        $stid= $link->real_escape_string($_POST['Station_id']);
        $Station_Name = $link->real_escape_string($_POST['Station_name']);
        $command= $link->real_escape_string($_POST['command']);
        $Desciption= $link->real_escape_string($_POST['Desciption']);
        $Category= $link->real_escape_string($_POST['Category']);


        $premission_rw =check_premission_station_RW($stid,$_SESSION['loginid']);
        
        if($premission_rw >0)
            $link->query("INSERT INTO `Dtmf_command` (`id`, `Station_Name`, `Station_id`, `Command`, `Description`,`Category`) VALUES (NULL, '$Station_Name', '$stid', '$command', '$Desciption','$Category'); ");
        
    }
    if($_POST['Remove_DTMF'] == "1")
    {
        
        
        $stid= $link->real_escape_string($_POST['Station_id']);
        $DMF_ID = $link->real_escape_string($_POST['DMF_ID']);
   
        
        

        $station_id =DTMF_ID_TO_STATION($DMF_ID);
        echo $station_id;
        $premission_rw =check_premission_station_RW($station_id,$_SESSION['loginid']);
        
        if($premission_rw >0)
            $link->query("DELETE FROM `Dtmf_command` WHERE `Dtmf_command`.`id` = '$DMF_ID'");
        
        
    }
    
    if($_POST['Update_DTMF'] == "1")
    {
        
        $command= $link->real_escape_string($_POST['command']);
        $Desciption= $link->real_escape_string($_POST['Desciption']);
        $Category= $link->real_escape_string($_POST['Category']);
       
        $DMF_ID = $link->real_escape_string($_POST['dtmf_id']);
        
        
        


        $link->query("UPDATE `Dtmf_command` SET `Command` = '$command', `Description` = '$Desciption' ,`Category` ='$Category' WHERE `Dtmf_command`.`id` = '$DMF_ID'; ");
        
        
    }
    
    if($_POST['update_setings'] == "1")
    {
        
        $Driver= $link->real_escape_string($_POST['Driver']);
        $radio_image= $link->real_escape_string($_POST['radio_image']);

        
        $Update_id = $link->real_escape_string($_POST['Update_id']);
        $station_id =page_id_to_staion_id($Update_id);
        $premission_rw =check_premission_station_RW($station_id,$_SESSION['loginid']);
        
        if($premission_rw >0)
        
           $link->query("UPDATE `Infotmation_page` SET `Module` = '$Driver', `Image` = '$radio_image' WHERE `Infotmation_page`.`id` = '$Update_id'; ");
        
        
    }
    if($_POST['Insert_station_status'] == "1")
    {
        
        echo "3";
        $stid= $link->real_escape_string($_POST['Station_id']);
        $Type = $link->real_escape_string($_POST['Type']);
        $date= $link->real_escape_string($_POST['date']);
        $command= $link->real_escape_string($_POST['command']);
        
        $phptime = strtotime($date);
        
        $mysqltime = date ('Y-m-d H:i:s', $phptime); 



        $premission_rw =check_premission_station_RW($stid,$_SESSION['loginid']);
        
        if($premission_rw >0)
         $link->query("INSERT INTO `Operation_log` (`id`, `Station_id`, `Type`, `Date`, `Message`) VALUES (NULL, '$stid', '$Type', '$mysqltime', '$command'); ");
        
    }
    
    
    
    
    
    
    
    
}
?>