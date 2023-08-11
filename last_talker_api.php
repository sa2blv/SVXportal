<?php
include 'config.php';
$link->set_charset("utf8");
include 'function.php';
include_once 'redis.php';
define_settings();
set_laguage();


function secondsToMS($seconds) {
    $s = (int)$seconds;
    if($s >0)
        return sprintf('%02d:%02d', $s/60%60, $s%60);
        else
            return "0:00:00:00";
            
}

function getContrastColor($hexColor)
{
    // hexColor RGB
    $R1 = hexdec(substr($hexColor, 1, 2));
    $G1 = hexdec(substr($hexColor, 3, 2));
    $B1 = hexdec(substr($hexColor, 5, 2));
    
    // Black RGB
    $blackColor = "#000000";
    $R2BlackColor = hexdec(substr($blackColor, 1, 2));
    $G2BlackColor = hexdec(substr($blackColor, 3, 2));
    $B2BlackColor = hexdec(substr($blackColor, 5, 2));
    
    // Calc contrast ratio
    $L1 = 0.2126 * pow($R1 / 255, 2.2) +
    0.7152 * pow($G1 / 255, 2.2) +
    0.0722 * pow($B1 / 255, 2.2);
    
    $L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
    0.7152 * pow($G2BlackColor / 255, 2.2) +
    0.0722 * pow($B2BlackColor / 255, 2.2);
    
    $contrastRatio = 0;
    if ($L1 > $L2) {
        $contrastRatio = (int)(($L1 + 0.05) / ($L2 + 0.05));
    } else {
        $contrastRatio = (int)(($L2 + 0.05) / ($L1 + 0.05));
    }
    
    // If contrast is more than 5, return black color
    if ($contrastRatio > 5) {
        return '#000000';
    } else {
        // if not, return white color.
        return '#FFFFFF';
    }
}





$curent_tg_sql="";

if($_GET['TG'])
{
    $curent_tg = $_GET['TG'];
    $curent_tg_sql = " and `Talkgroup` = $curent_tg";
}


$curent_tg = $link->real_escape_string($curent_tg);


$sql =  "SELECT * FROM `RefletorNodeLOG` WHERE `Type` = 1 and `Active` = 0 $curent_tg_sql order by `Id`  DESC  LIMIT 50 ";

//echo $sql;


$key= "svxportal_chace_latest_tg_".$curent_tg;

$data = sql_to_array_redis_cahce($key,$sql,3);

$reflektor_sql = "SELECT * FROM `RefletorStations`";

$key= "svxportal_chace_latest_reflektor_station";

$reflektor = sql_to_array_redis_cahce($key,$reflektor_sql,60);


$key= "svxportal_chace_latest_talkgroup";
$sql2 = "SELECT * FROM `Talkgroup`" ;

$talkgroup = sql_to_array_redis_cahce($key,$sql2,60);

function read_node_information($node,$node_data)
{
    foreach($node_data as $x => $row)
    {
        if($row["Callsign"] ==$node  )
        {
            return $row;
        }
     
    }
    $row["Callsign"] ="";
    return $row;
}

function read_node_tg($tg,$tg_data)
{
    foreach($tg_data as $x => $row)
    {
        if($row["TG"] == $tg  )
        {
            return $row;
        }
        
    }
    
    $tg_array["Collor"] ="";
    $tg_array["TXT"] ="";
    
    
    return $tg_array;
    
}
function check_key_config($key,$array)
{
    
    foreach($array as $x => $row)
    {
        
        if (strpos($key,$x ) === 0) {
            return $row;
        }
        
        
        
    }
    

    
    
    
    
}




// clean array of duplicates
$previus_talker ="";
$json_data = array();
$val =0;

if(!$_GET['filter'] == "false")
{

    foreach($data as $x => $row) 
    {
      //  if($previus_talker != $row["Callsign"])
      //  {
            
            $station_array = read_node_information($row["Callsign"],$reflektor);
            $tg_array = read_node_tg($row["Talkgroup"],$talkgroup);
            
            $previus_talker = $row["Callsign"];
            $json_data[$val]["Node"] = $row["Callsign"];
            $json_data[$val]["Time"] = $row["Time"];
            $json_data[$val]["TG"] = $row["Talkgroup"];
            $json_data[$val]["Duration"] = secondsToMS($row["Talktime"]);
            $json_data[$val]["Duration_ut"] = $row["Talktime"];
            $json_data[$val]["Location"] = $station_array["Location"];
            $json_data[$val]["TG_collor"] = $tg_array["Collor"]; 
            $json_data[$val]["TG_Name"] = $tg_array["TXT"];
            $json_data[$val]["TG_Name"] = $tg_array["TXT"];
            $json_data[$val]["Text_color"] = getContrastColor($tg_array["Collor"]);
            
            if( $tg_array["TXT"] == "")
            {
                $json_data[$val]["TG_Name"] = check_key_config($row["Talkgroup"],$definde_qsy_tg );
                
            }
            if(!$json_data[$val]["TG_Name"])
            {
                
                $json_data[$val]["TG_Name"] ="";
            }
             
            
            $val++;
       
    /*
    }
        else
        {
            unset($data[$x]); 
        }
   */
    }

}

echo json_encode($json_data);
$link->close();



