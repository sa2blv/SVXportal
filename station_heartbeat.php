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
include_once 'function.php';
define_settings();



$file = 'tmp/cache_json.txt';
// Create connection
$conn = new mysqli($host, $user, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// this is a multplier that detect if noode is down and how many times it is down 20sec * $downtime_time
$downtime_time = 4;



function check_if_dead($call) {
    global $conn;
    global $counter_down;
    global $downtime_time;
 
    $sql ="SELECT `Station_Down` , `Station_Down_timmer_count` FROM RefletorStations where Callsign='$call'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
  
    
    return $row['Station_Down'];
    
}
function check_if_dead_time($call) {
    global $conn;
    global $counter_down;
    global $downtime_time;
    
    $sql ="SELECT `Station_Down` , `Station_Down_timmer_count` FROM RefletorStations where Callsign='$call'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    

    
    if($row['Station_Down'] == 1)
    {
        
        if($row['Station_Down_timmer_count'] >= $downtime_time)
        {
            $count1 = $downtime_time+1;
            $sql1 ="UPDATE RefletorStations SET Station_Down_timmer_count = $count1  WHERE Callsign = '".$call."'";
            
        }
        else
        {
            $sql1 ="UPDATE RefletorStations SET Station_Down_timmer_count = Station_Down_timmer_count + 1  WHERE Callsign = '".$call."'";
        }

        
    }
    
    
    return $row['Station_Down_timmer_count'];
    
}
function check_if_sendmail($call) {
    global $conn;

    global $downtime_time;
    
    $sql ="SELECT `Station_Down` , `Station_Down_timmer_count` FROM RefletorStations where Callsign='$call'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    
    
    if($row['Station_Down_timmer_count'] == $downtime_time)
    {
        return 1;   
    }

    return 0;
    
}










function send_mail($email,$node,$type) 
{

    if($type == 1)
    {
        $subject = "Node $node down";
        $message = "The node $node has lost the connection to reflector !";
    
    }
    else
    {
        $subject = "Node $node up";
        $message = "the node $node has connected to reflector !";
    }

    
    $to      = SYSMATER_MAIL;
    $headers = 'From: '.SYSTEM_MAIL.'' . "\r\n" .
        'Reply-To: Systemguard@svxportal.sm2ampr.net' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    
    if($email !="")
    {
        mail($email, $subject, $message, $headers);
    }
    
    mail($to, $subject, $message, $headers);
    
    
    

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


    echo "check data to write!";
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
    
    $find_array =$Station_callsings_fromdb;
    
    
    
    
    //var_dump($json->nodes);
    
    foreach ($json->nodes as $key => $value) {
        // $arr[3] will be updated with each value from $arr...
        //var_dump($key);
        //var_dump($value);
        //echo "{$key} => {$value} ";
        //print_r($arr);

        // instert station to db if note exist
        $array_key = array_search($key,$find_array);
        unset($find_array[$array_key]);
        
        if($array_key)
        {
 
            
            if(check_if_dead($key) == 1)
            {
                echo $key ." is has joned  reflektor ";
                
                // set station is restored
                $sql = "INSERT INTO `RefletorNodeLOG` (`Id`, `Callsign`, `Type`, `Active`, `Talkgroup`, `NODE`, `Siglev`, `Duration`, `IsTalker`,`Nodename`,`Talktime`) VALUES (NULL, '".$key."', '3', '0', '0', '', '0', '0', '0', '','0');";
                
                $conn->query($sql);
                

                
      
                
                if(check_if_sendmail($key) == 1 )
                {
                    if(SEND_MAIL_TO_SYSOP ==1)
                    {
                        send_mail("",$key,0);
                    }
                    
                }
                
                $sql1 ="UPDATE RefletorStations SET Station_Down_timmer_count = 0  WHERE Callsign = '".$key."'";
                $conn->query($sql1);
                
            }
            
            $sql_insert = "UPDATE `RefletorStations` SET Last_Seen=now(), Station_Down='0' WHERE Callsign='$key'";
            $conn->query($sql_insert);
           
        }
        
        
           
    }
    
    foreach ($find_array as $key => $value) 
    {
        if(check_if_dead($value) == 0)
        {
            

                echo $value ." is has dropt from reflektor ";
                
                $sql = "INSERT INTO `RefletorNodeLOG` (`Id`, `Callsign`, `Type`, `Active`, `Talkgroup`, `NODE`, `Siglev`, `Duration`, `IsTalker`,`Nodename`,`Talktime`) VALUES (NULL, '".$value."', '3', '1', '0', '', '0', '0', '0', '','0');";
    
                $conn->query($sql);
                
  
   
       }
            

        
        $sql_insert = "UPDATE `RefletorStations` SET Station_Down='1' WHERE Callsign='$value'";
        $conn->query($sql_insert);
        
        
        if(check_if_dead_time($value) == 4)
        {
            echo "test";
            
        
            if(SEND_MAIL_TO_SYSOP ==1)
            {
                send_mail("",$value,1);
            }
            
            
            
            
        }
        
        
        

        
     
    }
   

    
    
    $conn->close();





?>