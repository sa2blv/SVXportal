<?php
include_once '../config.php';
include_once '../function.php';
set_laguage();
mysqli_set_charset($link,"utf8");

if(  $_SESSION['loginid'] >0 )
{
    
    if($_POST['Station_id'] >= 0 && check_premission_station_RW($_POST['Station_id'],$_SESSION['loginid']) >0 )
    {
        
        $link->begin_transaction();
        $link->autocommit(FALSE);
        
        // prepare statement (for multiple inserts) only once
        $stid= $link->real_escape_string($_POST['Station_id']);
        $commnd= $link->real_escape_string($_POST['command']);
        $date= $link->real_escape_string($_POST['date']);

        $sql ="SELECT `Callsign` FROM RefletorStations where ID='$stid'";
        $result = $link->query($sql);
        $station_name ="";

        while($row = $result->fetch_assoc())
        {
            $station_name= $row["Callsign"];
     
        }
        
        
        
        $link->query("INSERT INTO `Commads_to_node` (`Node`, `Command`, `action`, `Execute time`) VALUES ( '$station_name', '$commnd', '1', '$date');");
        
        $link->commit();
        $link->close();
        
    }
    
    
}
      
