<?php
/*This script is executed on the server 
 * with watch -n 1 logdeamon.php
 * 
 */
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include_once  'config.php';
$file = 'tmp/cache_json.txt';
// Create connection
$conn = new mysqli($host, $user, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function check_if_active($call) {
    global $conn;
    $sql ="SELECT `Active` FROM RefletorNodeLOG where Type =1 AND  Callsign='".$call."'  ORDER BY `RefletorNodeLOG`.`Id` DESC limit 1 ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    
    return $row['Active'];
   
}
function check_if_HasBeenActive($call) {
    global $conn;
    $sql ="SELECT count(Id) FROM RefletorNodeLOG where Type =1 and Callsign='".$call."' ORDER BY `RefletorNodeLOG`.`Id` ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    
    return $row['count(Id)'];
    
}
function check_last_tg($call) {
    global $conn;
    $sql ="SELECT Talkgroup,id FROM RefletorNodeLOG where Type =1 AND Active='1'  and Callsign='".$call."' ORDER BY `RefletorNodeLOG`.`Id` DESC LIMIT 1  ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    
    return $row['Talkgroup'];
    
}
function check_last_time($call) {
    global $conn;
    $sql ="SELECT UNIX_TIMESTAMP(Time),id FROM RefletorNodeLOG where Type =1 AND Active='1'  and Callsign='".$call."' ORDER BY `RefletorNodeLOG`.`Id` DESC LIMIT 1  ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    
    return $row['UNIX_TIMESTAMP(Time)'];
    
}


function wrie_to_cache($json) {
    global $file;
    $myfile = fopen($file, "w");
    fwrite($myfile, $json);
    fclose($myfile);   
}
function read_cache()
{
    global $file;
    

    
    if(!is_file($file)){
        $contents = 'This is a test!';           // Some simple example content.
        file_put_contents($file, $contents);     // Save our content to the file.
    }

    
    $myfile = fopen($file, "r") ;
    $json =  fread($myfile,filesize("cache_json.txt"));
    fclose($myfile);
    return $json;
    
}

// Get data from server
$json_data = file_get_contents($serveradress);
$json = json_decode($json_data);

if(read_cache() != $json_data)
{
    echo "write data!";
    wrie_to_cache($json_data);
    mysqli_set_charset($conn,"utf8");
    
    $sql ="SELECT `Callsign` FROM RefletorStations";
    $result = $conn->query($sql);
    $i=1;
    while($row = $result->fetch_assoc()) 
    {
        $Station_callsings_fromdb[$i]= $row["Callsign"];
        $i++;
    }
    
    
    
    
    //var_dump($json->nodes);
    
    foreach ($json->nodes as $key => $value) {
        // $arr[3] will be updated with each value from $arr...
        //var_dump($key);
        //var_dump($value);
        //echo "{$key} => {$value} ";
        //print_r($arr);
        
        // instert station to db if note exist
        if(array_search($key,$Station_callsings_fromdb) == NULL)
        {

            $NodeLocation = @$value->NodeLocation;
      
            
            
            $sql_insert = 'INSERT IGNORE RefletorStations(`Callsign`,`Location`)VALUES( "'.$key.'","'.$NodeLocation.'")';
            if ($conn->query($sql_insert) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        
        if($value->isTalker =="true" || $value->isTalker =="True" || $value->isTalker == true)
        {
            //echo $key." talker is active " .check_if_active($key);
            if(check_if_active($key) == 0  )
            {
                $sql_insert = "INSERT INTO `RefletorNodeLOG` (`Id`, `Callsign`, `Type`, `Active`, `Talkgroup`, `NODE`, `Siglev`, `Duration`, `IsTalker`,`Nodename`,`Talktime`)
                 VALUES (NULL, '".$key."', '1', '1', '".$value->tg."', '', '0', '0', '0', '','0');";
                $conn->query($sql_insert);
                

            }
        }
        else
        {
       
            if(check_if_active($key) == 1  && check_if_HasBeenActive($key) >0)
            {
                $last_active_id =check_last_tg($key);
                $last_active_timestamp =check_last_time($key);
                $curcent_diftime= time() -$last_active_timestamp;
                echo "difftime: " . $curcent_diftime ;
                $sql_insert = "INSERT INTO `RefletorNodeLOG` (`Id`, `Callsign`, `Type`, `Active`, `Talkgroup`, `NODE`, `Siglev`, `Duration`, `IsTalker`,`Nodename`,`Talktime`)
                 VALUES (NULL, '".$key."', '1', '0', '".$last_active_id."', '', '0', '0', '0', '','$curcent_diftime');";
                
                //echo $sql_insert;
             
                $conn->query($sql_insert);
            }
            
        
        }
        
        if (@is_array($value->qth) || @is_object($value->qth))
        {
            
            foreach (@$value->qth as $qth_id => $qth_value) 
            {
                if (@is_array($qth_value->rx) || @is_object($qth_value->rx))
                foreach (@$qth_value->rx as $station => $Rx)
                {
                    if($value->isTalker)
                    {
                        if(@$Rx->active == null)
                            $Rx->active=0;
                            if(@$Rx->siglev == null)
                                $Rx->siglev =0;
        
                        echo $key." Node ". $station."-> is Talker". $Rx->siglev;
                        
                        $sql_insert = "INSERT INTO `RefletorNodeLOG` (`Id`, `Callsign`, `Type`, `Active`, `Talkgroup`, `NODE`, `Siglev`, `Duration`, `IsTalker`, `Time`,`Nodename`,`Talktime`) 
                                       VALUES (NULL, '$key', '2', '".$Rx->active."', '$value->tg', '$station', '".$Rx->siglev."', '0', '1', CURRENT_TIMESTAMP,'".$qth_value->name."','0');";
        
                        $conn->query($sql_insert);
                    }
                    
                    
                }
                
               // 
               // if ($conn->query($sql_insert);
                
                
                
                
            }
    }
        
        
        
        
        
       
    }
    
    

    $conn->close();
}
else
{
    echo "No new data";
}





?>