<?php
header('Access-Control-Allow-Origin: <?php echo $serveradress ?>');
include "config.php";
include 'function.php';

if($_SESSION['loginid'])
{
$user_id= $_SESSION['loginid'];

$result = mysqli_query($link, "SELECT * FROM User_Premission LEFT JOIN RefletorStations ON RefletorStations.ID = User_Premission.Station_id WHERE User_Premission.User_id ='$user_id' ");

$calsign_array = array();


    // Associative array
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
        $calsign_array[] = $row['Callsign'];

    }
    
    $in_string = join("','",$calsign_array);
    
    $time_now = strtotime("Now");
    $time_min_sex = strtotime("-20 sec");
    

    
    $result = mysqli_query($link, "SELECT * FROM `RefletorNodeLOG` WHERE `Type` = 3  AND Callsign in ('$in_string') and `Time` BETWEEN FROM_UNIXTIME($time_min_sex) AND FROM_UNIXTIME($time_now) ORDER BY `Time` ASC");
    
    
    $json_data = array();
    $i =0;
     while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
     {

         
         $json_data[$i]['Callsign'] =$row['Callsign'];
         $json_data[$i]['Active'] =$row['Active'];
         $json_data[$i]['Time'] =$row['Time'];
           $i++;
     }
     
     echo json_encode($json_data);

}

?>
    
    
    