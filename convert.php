<?php

include_once  'config.php';
$file = 'tmp/cache_json.txt';
// Create connection
$conn = new mysqli($host, $user, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function check_last_time($call,$id) {
    global $conn;
    $sql ="SELECT UNIX_TIMESTAMP(Time),id FROM RefletorNodeLOG where Type =1 AND Active='1'  and Callsign='".$call."' AND `Id` < '$id' ORDER BY `RefletorNodeLOG`.`Id` DESC LIMIT 1  ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    
    return $row['UNIX_TIMESTAMP(Time)'];
    
}
$call ="SK2LY";

$seartch_sql= "SELECT UNIX_TIMESTAMP(Time) as time,`Id` FROM `RefletorNodeLOG` WHERE `Callsign` LIKE '$call' AND `Type` = 1 AND `Active` = 0 AND `Talktime` = 0 ORDER BY `Id` DESC ";



$result = $conn->query($seartch_sql);

while($row = $result->fetch_assoc()) {
    
    
    
    $old_time = check_last_time($call,$row['Id']);
    $current_time = $row['time'];
    $idnr =$row['Id'];
    echo ($current_time- $old_time). " ";
    $time_diffrense = ($current_time- $old_time);
    
    $sql = "UPDATE RefletorNodeLOG SET Talktime='$time_diffrense' WHERE id=$idnr";

    
    if ($conn->query($sql) === TRUE) {
        echo "Record ".$idnr." updated successfully\n";
    } else {
        echo "Error updating record: " . $conn->error."\n";
    }
    
    
}

