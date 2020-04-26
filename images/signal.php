<?php
include_once  'config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create connection
$conn = new mysqli($host, $user, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn,"utf8");
$splitdata = $_POST['file'];
$time = $_POST['time'];
$folder = explode('/',$splitdata);


$filename= $folder[(sizeof($folder)-1)];

$filetime_nr = filemtime($dir."/".$filename);
$filetime_nr = ($filetime_nr -$time)-2;
$upper = date("Y-m-d H:i:s",$filetime_nr);
$lower = date("Y-m-d H:i:s",$filetime_nr-1);

//$sql ="SELECT * FROM `RefletorNodeLOG` WHERE `Type` = 2 AND  `Talkgroup` = 240 AND `Active` = 1 AND `Time` BETWEEN  '".$lower."' AND  '".$upper."' ORDER BY `Id` DESC LIMIT 1";
$sql ="SELECT * FROM `RefletorNodeLOG` WHERE `Type` = 2 AND  `Talkgroup` = 240 AND  `Time` BETWEEN  '".$lower."' AND  '".$upper."' ORDER BY `Active` DESC ";

/*
$sql_count ="SELECT count(*) FROM `RefletorNodeLOG` WHERE `Type` = 2 AND  `Talkgroup` = 240 AND `Active` = 0 AND `Time` BETWEEN  '".$lower."' AND  '".$upper."' ORDER BY `Id` DESC LIMIT 1";
$sql_Data = $conn->query($sql_count);
$count = $sql_Data->fetch_assoc();
*/
//echo $row["count(*)"];
//$sql ="SELECT * FROM `RefletorNodeLOG` WHERE `Type` = 2 AND  `Talkgroup` = 240  AND `Time` BETWEEN  '".$lower."' AND  '".$upper."' ORDER BY `Active` DESC";

//$sql_nonactive ="SELECT * FROM `RefletorNodeLOG` WHERE `Type` = 2 AND Callsign='".$row["Callsign"]."' AND `Talkgroup` = 240 AND `Active` = 0 AND `Time` ='".$row["Time"]."' ORDER BY `Id` DESC ";






$result = $conn->query($sql);
$data = array();
$Stations = array();
$array_nummer =0;
$time = 0;
$call ="";
while ($row = $result->fetch_assoc()) {

    $data["Callsign"] =$row["Callsign"];
    $data["Siglev"] =$row["Siglev"];
    $data["Nodename"] =$row["Nodename"];
    $Stations[$array_nummer] = $row["Callsign"];
    $call = $data["Callsign"];
    $array_nummer++;
    
    
    break;
    
    /*
    $sql_nonactive ="SELECT * FROM `RefletorNodeLOG` WHERE `Type` = 2 AND Callsign='".$row["Callsign"]."' AND `Talkgroup` = 240 AND `Active` = 0 AND `Time` ='".$row["Time"]."' ORDER BY `Id` DESC ";
    $result1 = $conn->query($sql_nonactive);
    
    */

    
}

$j=0;
while ($row1 = $result->fetch_assoc()) {
    
    
    
    $data["Subreciver"][$j]["Callsign"] =   $row1["Callsign"];
    $data["Subreciver"][$j]["Siglev"]   =   $row1["Siglev"];
    $data["Subreciver"][$j]["Nodename"] =   $row1["Nodename"];
    
    $j++;
    
}
    
    

echo json_encode($data);





?>