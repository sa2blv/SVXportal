<?php
include_once  'config.php';
include_once 'redis.php';





/* ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
*/
// Create connection
$conn = new mysqli($host, $user, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
include 'function.php';
set_laguage();


function check_key_config_bool($key,$array)
{
    
    foreach($array as $x => $row)
    {
        
        if (strpos($key,$x ) === 0) {
            return false;
        }
        
        
        
    }
    
    
    return true ;
      
}




function detect_working_date()
{
    global $conn;
    $date = $conn->query("SELECT Timestamp as c FROM `trafic_day_statistics` ORDER BY `Timestamp` DESC LIMIT 1 ")->fetch_object()->c;
    
    return date("Y-m-d",strtotime($date));
    
}

function detect_empty()
{
    global $conn;
    $nummber = $conn->query("SELECT COUNT(*) as c FROM trafic_day_statistics ")->fetch_object()->c;
    
    
    return $nummber;
    
}
function if_day_in_cache($date)
{
   
    global $conn;
    
    $year = date("Y",strtotime($date));
    $Mounth = (int)date("m",strtotime($date));
    $day = (int)date("d",strtotime($date));
    
    $nummber = $conn->query("SELECT COUNT(*) as c FROM `trafic_day_statistics` WHERE `Year` = '$year' AND `Mounth` = '$Mounth' AND `Day` = '$day' ORDER BY `id` DESC  ")->fetch_object()->c;
    
       
    return $nummber;
    
}




$json_array=array();
function secondsToDHMS($seconds) {
    $s = (int)$seconds;
    if($s >0)
        return sprintf('%d:%02d:%02d:%02d', $s/86400, $s/3600%24, $s/60%60, $s%60);
        else
            return "0:00:00:00";
            
}
function get_time($station,$day)
{
    global $link;

    $station_qvery =" AND Callsign ='".$station."' ";
    
    
    $tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:59.000000'";
    
    //$sql_active ="SELECT sum(UNIX_TIMESTAMP(`Time`)),Callsign FROM RefletorNodeLOG WHERE `Type` = '1' $station_qvery AND  `Active` ='1' AND $tme_string group by  `Callsign`";
    
    $sql_nonactive ="SELECT sum(Talktime), Callsign FROM RefletorNodeLOG WHERE `Type` = '1'$station_qvery AND `Active` ='0' AND $tme_string group by  `Callsign` ";
    
//     echo $sql_active;
//      echo "<br>";
     
    //$sqlref = $link->query($sql_active);
    $sqla = $link->query($sql_nonactive);
    $timesum =array();
    
    while($row = $sqla->fetch_assoc())
    {
        $timesum[$row["Callsign"]] =$timesum[$row["Callsign"]]+$row["sum(Talktime)"];
    }

    return $timesum[$station];
    
}

$moste_used_station = array();
function get_most_use_reciver($day)
{
    global $link;
    global $moste_used_station;

    $tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:00.000000'";
    $quvery ="
    SELECT MAX(`Nodename`),`Callsign` FROM `RefletorNodeLOG`  WHERE  $tme_string  AND`Type` = 2 AND `Active` = 1  GROUP BY Callsign";
    //echo $quvery;

    
    $key = "svxportal_cahce_station_day_".$day."_1";  
    $sql_data =sql_to_array_redis_cahce($key,$quvery,500);

    //$sqla = $link->query($quvery);
    
    $count_array= array();
 
    //while($row2 = $sqla->fetch_assoc())

    

    foreach ($sql_data as $j => $row2) 
    {
        //echo '<pre>';
        //var_dump($row2);
        $count_array[$row2['Callsign']][$row2['MAX(`Nodename`)']]++;
        
        $moste_used_station[$row2['Callsign']] = $row2["MAX(`Nodename`)"];
    }
    
    

 
    //echo '<pre>';
    arsort($count_array);
    //while($row2 = $sqla->fetch_assoc())
    foreach ($sql_data as $j => $row2)       
    {
        
        if($count_array[$row2['Callsign']][0])
          $moste_used_station[$row2['Callsign']] =$count_array[$row2['Callsign']][0];
    }
    
    
   
    $quvery_optimizer =  "SELECT Max_reciver , `Station_id` , RefletorStations.Callsign as callsing FROM `Station_day_statistic` LEFT JOIN RefletorStations ON `Station_id` = RefletorStations.ID WHERE `Date` = '$day'"; 
    //$sqla = $link->query($quvery_optimizer);
    
    
    $key = "svxportal_cahce_station_day_".$day."_optimizer";
    $sql_data1 =sql_to_array_redis_cahce($key,$quvery_optimizer,3600);
    
    
    
    //while($row2 = $sqla->fetch_assoc())
    foreach ($sql_data1 as $j => $row2) 
    {
        $moste_used_station[$row2['callsing']] = $row2['Max_reciver'];
        
    }
        
        
    
    
    


}


$day= $_GET['date'];
$station = $_GET['st'];
$qrv = $_GET['qrv'];
$mouth_s = $_GET['mouth'];
$link->set_charset("utf8");
/*
 * Detect if station data
 * 
 */
if($station != "")
{
    $station = $link->real_escape_string($station);
    $station_qvery =" AND Callsign ='".$station."' ";
    
}
else
{
    $station_qvery ="";
}
if($mouth_s != "")
{
    $day = $link->real_escape_string($day);
    $current_year= date("Y",strtotime($day));
    

    
    
    // if system has new chache funkutcin use this 
    
    if(detect_working_date() != "1970-01-01"  && detect_empty() > 0 )
    {
        
        $talkgroup_quvery="";
        $station_quvery="";
        
        
        if($_GET['filterpicker_repeater_year'] != "")
        {
            
            $station = $link->real_escape_string($_GET['filterpicker_repeater_year']);
            $station_quvery = "AND Node ='$station'";
            $station_key =" $station";
            
        }
        if($_GET['filterpicker_talgroup_year'] != "")
        {
            
            $talkgroupid = $link->real_escape_string($_GET['filterpicker_talgroup_year']);
            $talkgroup_quvery = "AND TG ='$talkgroupid'";
        }
        
        
                
        $chace_sql = "SELECT sum(`12_T`) as a12, sum(`11_T`) as a11, sum(`10_T`) as a10, sum(`9_T`) as a9, sum(`8_T`) as a8, sum(`7_T`) as a7, sum(`6_T`) as a6, sum(`5_T`) as a5, sum(`4_T`) as a4, sum(`3_T`) as a3, sum(`2_T`) as a2, sum(`1_T`) as a1 FROM `trafic_mounth_statistics` WHERE Year = '$current_year' $station_quvery  $talkgroup_quvery GROUP by `Year` ";
        
        
        //$sql_pre = $link->query($chace_sql);
        
        $key = "svxportal_cahce_year_station_".$day."_".$station_key."_".$station."_".$talkgroupid; 
        
        
        $sql_data =sql_to_array_redis_cahce($key,$chace_sql);
        
        
        //while($row = $sql_pre->fetch_assoc()) 
        foreach ($sql_data as $j => $row) 
        {
            
            for ($i = 0; $i <= 12; $i++) 
            {
                if( $row['a'.$i] >0 )
                {
                    // 'a'.$i se quvery to understand mapping 
                    $json_array[$i] ["Secound"] = secondsToDHMS($row['a'.$i]);
                    $json_array[$i] ["unixtime"] = $row['a'.$i];
                }
            }
            
        }
        
        
        $sql_toplist = "SELECT `Year`,`Mounth`,`Day` , SUM(`Total_T`) as total_time FROM `trafic_day_statistics` WHERE Year = $current_year $station_quvery  $talkgroup_quvery  GROUP BY `Year`,`Mounth`,`Day` ORDER BY total_time DESC LIMIT 10 ";
        
        $id=0;
        //$sql_toplist = $link->query($sql_toplist);
        
        $key = "svxportal_cahce_year_toplist_".$current_year."_".$station."_".$talkgroupid; 
        $sql_toplist =sql_to_array_redis_cahce($key,$sql_toplist);
        

        
        
        //while($row = $sql_toplist->fetch_assoc())
        foreach ($sql_toplist as $j => $row) 
        {
            $daystring=$row['Year']."-".$row['Mounth']."-".$row['Day'];
            
            $json_array["Toplist"][$id]["Secound"] = secondsToDHMS($row['total_time']);
            $json_array["Toplist"][$id]["unixtime"] = $row['total_time'];
            $json_array["Toplist"][$id]["day"] =date("Y-m-d",strtotime($daystring)) ;
            $id++;
        }
        
        
        
    }
    else
    {
        // old non chace date request 
     
        if($_GET['filterpicker_repeater_year'] != "")
        {
            
            $station = $link->real_escape_string($_GET['filterpicker_repeater_year']);
            $station_quvery = "AND Callsign ='$station'";
        }
        if($_GET['filterpicker_talgroup_year'] != "")
        {
            
            $talkgroupid = $link->real_escape_string($_GET['filterpicker_talgroup_year']);
            $talkgroup_quvery = "AND Talkgroup ='$talkgroupid'";
        }
        
        
        $sql_stations ="SELECT SUM(`Talktime`),MONTH(`Time`)  FROM   RefletorNodeLOG
        WHERE YEAR(`Time`) = $current_year  AND Type='1' and Active='0' $station_quvery $talkgroup_quvery  GROUP BY MONTH(`Time`)";
    
       
        $sqla = $link->query($sql_stations);; 
        while($row = $sqla->fetch_assoc()) 
        {
    
           $json_array[$row["MONTH(`Time`)"]] ["Secound"] = secondsToDHMS($row['SUM(`Talktime`)']);
           $json_array[$row["MONTH(`Time`)"]] ["unixtime"] = $row['SUM(`Talktime`)'];
            
        }
    
        $sql_activity ="SELECT SUM(`Talktime`), MONTH(`Time`) ,DAY(`Time`) FROM RefletorNodeLOG WHERE YEAR(`Time`) = $current_year AND Type='1' and Active='0' $station_quvery $talkgroup_quvery GROUP BY MONTH(`Time`), DAY(`Time`) ORDER BY SUM(`Talktime`) DESC  limit 10";
    
        $sqla = $link->query($sql_activity);;
        $id=0;
        while($row = $sqla->fetch_assoc())
        {
            $daystring=$current_year."-".$row['MONTH(`Time`)']."-".$row['DAY(`Time`)'];
            
            $json_array["Toplist"][$id]["Secound"] = secondsToDHMS($row['SUM(`Talktime`)']);
            $json_array["Toplist"][$id]["unixtime"] = $row['SUM(`Talktime`)'];
            $json_array["Toplist"][$id]["day"] =date("Y-m-d",strtotime($daystring)) ;
            $id++;
        }
        
    }
    
    //
  
    echo json_encode ($json_array);
    
}
// node graph on front page
else if($qrv != "")
{
    $qrv = $link->real_escape_string($qrv);
    $day = $link->real_escape_string($day);
    
    if(if_day_in_cache($day) >0 )
    {
        
        $year = date("Y",strtotime($day));
        $Mounth = (int)date("m",strtotime($day));
        $days = (int)date("d",strtotime($day));
        
        
        $sql = "SELECT `Node`, sum(Total_T) as total_talked  FROM `trafic_day_statistics` WHERE Year = '$year' AND Mounth ='$Mounth' and Day ='$days' GROUP BY `Node` order by Node";
         
        $key = "svxportal_cahce_node_day_".$day;
        $sql_data =sql_to_array_redis_cahce($key,$sql);
        



   //     $sqla = $link->query($sql);
        
        
        $time_total_usage=0;
        $outarray = array();
        $i =0;
        
        get_most_use_reciver($day);
        
        
        foreach ($sql_data as $j => $row) 
        {
            $outarray['data'][$i]['call']=$row["Node"];
            $outarray['data'][$i]['time'] = $row['total_talked'];
            $time_total_usage = $time_total_usage+$outarray['data'][$i]['time'] ;
            $outarray['data'][$i]["Secound"] = secondsToDHMS($row['total_talked']);
          
            $outarray['data'][$i]["reciver"] = $moste_used_station[$row["Node"]];
            if($outarray['data'][$i]["reciver"]  ==  null || !$outarray['data'][$i]["reciver"] )
            {
                $outarray['data'][$i]["reciver"] = _("No RX");
            }
                        
            $i++;
        }
        $outarray['total_secounds'] = $time_total_usage;
        
        
        
        
        
    }
    else
    {
       
         // old code for use when no chache 
        $tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:59.000000'";
        
        $sql_stations ="SELECT Callsign , sum(Talktime)  FROM RefletorNodeLOG WHERE `Type` = '1' and Active='0'  AND $tme_string group by  `Callsign` ORDER BY `Callsign`";
    
        
        //echo $sql_stations;
        
        $key = "svxportal_cahce_node_day_".$day;
        $sql_data =sql_to_array_redis_cahce($key,$sql_stations,60);
     
        

        
        
        $outarray = array();
        $i =0;
        
        
        get_most_use_reciver($day);
        

        
        
        $time_total_usage=0;
        foreach ($sql_data as $j => $row) 
        {
            $outarray['data'][$i]['call']=$row["Callsign"];
            //$outarray['data'][$i]['time'] =get_time($row["Callsign"],$day);
            $outarray['data'][$i]['time'] = $row['sum(Talktime)'];
            $time_total_usage = $time_total_usage+$outarray['data'][$i]['time'] ;
            //$outarray['data'][$i]["Secound"] = secondsToDHMS(get_time($row["Callsign"],$day));
            $outarray['data'][$i]["Secound"] = secondsToDHMS($row['sum(Talktime)']);
            
            $outarray['data'][$i]["reciver"] = $moste_used_station[$row["Callsign"]];
            
            if($outarray['data'][$i]["reciver"]  ==  null || !$outarray['data'][$i]["reciver"] )
            {
                $outarray['data'][$i]["reciver"] = _("No RX");
            }
            
            
            
            
            $i++;
        }
        $outarray['total_secounds'] = $time_total_usage;
    }
    
    echo json_encode ($outarray);
    
    
    
}
else if( $_GET['time'] == "true")
{
    $dates =$day;
    $day = $link->real_escape_string($day);
    
    if(if_day_in_cache($day) > 0)
    {
     
        $station_key  = "";
        if ($_GET['station'] !="")
        {
            $station =$link->real_escape_string($_GET['station']);
            $station_string = "AND `Node`  ='$station'";
            $station_key  = "_st_".$station;
        }
        
        
        
        for($timel =0; $timel <= 24;$timel++)
        {

            
            
            
            $json_array[$timel] ["Secound"] = secondsToDHMS(0);
            $json_array[$timel] ["unixtime"] = 0;
            $json_array[$timel] ["TG"]= [];
            
            
            
            
        }
        
        
        
        
        
        
    $year = date("Y",strtotime($day));
    $Mounth = (int)date("m",strtotime($day));
    $day = (int)date("d",strtotime($day));
        

        
        
    $sql ="SELECT * FROM `trafic_day_statistics` WHERE `Year`='$year' AND `Mounth` ='$Mounth' AND `Day`= '$day' $station_string ";

    
    $dates = $link->real_escape_string($_GET['date']);
    
    $key = "svxportal_cahce_day_tg_".$dates."_".$station_key;
    
   // echo $key;

    $sql_data =sql_to_array_redis_cahce($key,$sql);
    
   // echo $key;
    

    

    
    
    $timesum =array();
    $timiesum  =array();
    $timiesum = array_fill(0, 24, 0);
    $timiesum_tmp1  =array();

    
    
    
    
  
    foreach ($sql_data as $j => $row)
    {

        
        for($timel =0; $timel <= 23;$timel++)
        {

            $exta ="";
            if( $timel <10 )
            {
             
                $exta="0";
            }
            
     
            $timiesum_tmp1[$timel][$row['TG']] += $row[$exta.$timel."_T"];
   
        if($timiesum_tmp1[$timel][$row['TG']] >0)
        {
            $timesum[$timel][$row['TG']] = $timiesum_tmp1[$timel][$row['TG']];
            $det_prev = $timel-1;
            if($det_prev >0)
               if(!$timesum[$det_prev][$row['TG']])
               {
                     $timesum[$det_prev][$row['TG']] =0;
               }
          
            if(!$timesum[$timel+1][$row['TG']])
            {
                $timesum[$timel+1][$row['TG']] =0;
            }
            
            
        }
            
            
      
      
        
        
        $timiesum[$timel] += $row[$exta.$timel."_T"];
        
        
        $json_array[$timel] ["Secound"] = secondsToDHMS($timiesum[$timel]);
        $json_array[$timel] ["unixtime"] = $timiesum[$timel];
        $json_array[$timel] ["TG"]= $timesum[$timel];
        

            
            

        }
        
        
      }
      

            
            
              
            
            
            
        
        
        
    
    

    }
   else {
    
    
        
        //$tme_string ="`Time` BETWEEN '$day $timel:00:00.000000' AND '$day $timel:59:59.000000'";
        $tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:59.000000'";
        
        //$sql_active ="SELECT sum(UNIX_TIMESTAMP(`Time`)), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' $station_qvery AND  `Active` ='1' AND $tme_string group by  `Talkgroup`";
        $station_string= "";
        $station_key="";
        if ($_GET['station'] !="")
        {
            $station =$link->real_escape_string($_GET['station']);
            $station_string = "AND Callsign ='$station'";
            $station_key  = "_st_".$station;
        }
        
        $sql_nonactive ="SELECT UNIX_TIMESTAMP(Time), Talktime, `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' $station_qvery AND `Active` ='0' AND $tme_string $station_string  ";
        $sqla = $link->query($sql_nonactive);
        
        $key = "svxportal_cahce_day_tg_".$day."_".$station_key; 
        $sql_data =sql_to_array_redis_cahce($key,$sql_nonactive,60);
        
        
        $data = array();
        foreach ($sql_data as $j => $row) 
        {
            $data[] = $row;
            
        }
        
    
        
    
    
        for($timel =0; $timel <= 24;$timel++)
        {
            $filterdate = strtotime($day);
            $low_time =$filterdate+ ($timel*3600);
            $high_time =$filterdate+ ($timel*3600)+(59*60)+59;
            
            $timesum =array();
            $timiesums  =array();
            
            if($json_array[$timel] ["TG"])
            {
                $timesum = $json_array[$timel] ["TG"];
            }
            $timiesums[$timel] =0;
            
            // loop throu remove row if match is found 
            foreach ($data as & $row) 
            {
        
           
                if(  $row['UNIX_TIMESTAMP(Time)'] >=   $low_time    &&    $row['UNIX_TIMESTAMP(Time)']  <=$high_time  )
                {
                
                    $timesum[$row['Talkgroup']] = $timesum[$row['Talkgroup']]+$row["Talktime"];
                    $timiesums[$timel] =$timiesums[$timel] +$row["Talktime"];
                    
                    // Detect if not exist and set to 0
                    
                    
                    if( $timesum[$row['Talkgroup']]  >0 )
                    {
                        $time_date = $timel-1;
                        if ($time_date >0)
                        {         
                            if(!  $json_array[$time_date] ["TG"][$row['Talkgroup']])
                              {
                                  $json_array[$time_date] ["TG"][$row['Talkgroup']] =0;
                              }

                        }
                        
                        if(!$json_array[$timel+1] ["TG"][$row['Talkgroup']])
                        {
                   
                            $json_array[$timel+1] ["TG"][$row['Talkgroup']] =0;
                            
                            
                        }
                      
                      
                        
                    }
                    unset($row);
                    
                    
                }
                
                
                
            }
            
    
    
                
            $json_array[$timel] ["Secound"] = secondsToDHMS($timiesums[$timel]);
            $json_array[$timel] ["unixtime"] = $timiesums[$timel];
            $json_array[$timel] ["TG"]= $timesum;
            
            
            
    
        }
    }


    
    echo json_encode ($json_array);

    


    
}
else if( $_GET['totalmount'] != "")
{
    /*
     *
     * SELECT count(id), YEAR(`Time`), MONTH(`Time`), DAYOFMONTH(`Time`), sum(`Talktime`) as total_talktime FROM `RefletorNodeLOG` WHERE Type = 1 AND Active = 0 AND YEAR(`Time`) =2020 GROUP BY YEAR(`Time`), MONTH(`Time`) , DAYOFMONTH(`Time`) ORDER BY YEAR(`Time`) DESC, MONTH(`Time`) DESC, DAYOFMONTH(`Time`) DESC
     *
     *
     */
    $day= $_GET['date_m']."" ;
    $day = $link->real_escape_string($day);
    
   // echo $day;
    $last_day = date("t", strtotime($day));
    //echo "- ".$last_day;
    $json_array = array();
    
    // mounth cache function.
    if(detect_working_date() != "1970-01-01"  && detect_empty() > 0 )
    {

        $station_key="";
        
        $station_string ="";
        if ($_GET['station'] !="")
        {
            $station =$link->real_escape_string($_GET['station']);
            $station_string = "AND Node ='$station'";
            
            $station_key  = "_st_".$station;
        }
        
        $year = date("Y",strtotime($day));
        $Mounth = date("m",strtotime($day));
        
        
        
        $sql="SELECT Year, Mounth, `Day`, SUM(Total_T) as  total_talktime  FROM `trafic_day_statistics` WHERE `Year` = $year and `Mounth` = $Mounth $station_string  GROUP by `Year`, `Mounth` , `Day` ";
        
        
        
        $key = "svxportal_cahce_mouth_tg_".$year."_".$Mounth."_".$station_key;  
        
         $sql_data =sql_to_array_redis_cahce($key,$sql);
        
        
        $i =0;
        foreach ($sql_data as $j => $row) 
        {
            
            $json_array[$i] = array();
            $json_array[$i]["secounds"]  = secondsToDHMS($row["total_talktime"]);
            $json_array[$i]["unixtime"]  = $row["total_talktime"];
            $json_array[$i] ["day"] =_(date("D",strtotime( $row["Year"].'-'.$row["Mounth"].'-'.$row["Day"]))). date(" Y-m-d",strtotime( $row["Year"].'-'.$row["Mounth"].'-'.$row["Day"]));
            $i++;
            
            
            
            
            
            
        }
        
        
        
        
    }
    else
    {
              
        $station_string= "";
        
        if ($_GET['station'] !="")
        {
            $station =$link->real_escape_string($_GET['station']);
            $station_string = "AND Callsign ='$station'";
        }
        
        
        
        $tme_string ="`Time` BETWEEN '$day-01 00:00:00.000000' AND '$day-$last_day 23:59:59.000000'";
        
    
        
    
        
        $sql="SELECT count(id), YEAR(`Time`), MONTH(`Time`), DAYOFMONTH(`Time`), sum(`Talktime`) as total_talktime FROM `RefletorNodeLOG` WHERE $tme_string AND Type = 1 AND Active = 0  $station_string GROUP BY YEAR(`Time`), MONTH(`Time`) , DAYOFMONTH(`Time`)  ORDER BY YEAR(`Time`) ASC, MONTH(`Time`) ASC, DAYOFMONTH(`Time`) ASC";
        
       // echo $sql;
        
  
        
        
        $sqla = $link->query($sql);
        
        $i =0;
        while($row = $sqla->fetch_assoc())
        {
    
            $json_array[$i] = array();
            $json_array[$i]["secounds"]  = secondsToDHMS($row["total_talktime"]);
            $json_array[$i]["unixtime"]  = $row["total_talktime"];
            $json_array[$i] ["day"] =_(date("D",strtotime( $row["YEAR(`Time`)"].'-'.$row["MONTH(`Time`)"].'-'.$row["DAYOFMONTH(`Time`)"]))). date(" Y-m-d",strtotime( $row["YEAR(`Time`)"].'-'.$row["MONTH(`Time`)"].'-'.$row["DAYOFMONTH(`Time`)"]));
            $i++;
            
    
            
            
    
            
        }
        
    
    }
    
    
    
    echo json_encode ($json_array);
    
    
    
    
}
else if ($_GET['cahce_year_tg'] == "1")
{
    
    $year = date("Y",strtotime($day));
    $Mounth = (int)date("m",strtotime($day));
    
    
    $sql = "SELECT sum(Total_T) as total_talked , TG FROM `trafic_day_statistics` WHERE Year = '$year'   GROUP BY TG";
    
    
    $key = "svxportal_cahce_mouth_tg_".$year."_1";
    $sql_data =sql_to_array_redis_cahce($key,$sql);
    
    
    //   $sqla = $link->query($sql);
    $timesum =array();
    
    $i =0;
    foreach ($sql_data as $j => $row)
    {
        if(check_key_config_bool($row["TG"],$definde_qsy_tg) == true)
            $timesum[$row["TG"]] =$row["total_talked"];
    }
    
    
    $sql1 =  "SELECT `TG` FROM `trafic_day_statistics` WHERE   Year = '$year'   GROUP BY `TG` ORDER BY TG ";
    
    
    $key = "svxportal_cahce_mouth_tg_".$year."_2";
    $sql_data =sql_to_array_redis_cahce($key,$sql1);
    
    
    $json_array = array();
    
    //while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    foreach ($sql_data as $j => $row)
    {
        if(check_key_config_bool($row["TG"],$definde_qsy_tg) == true &&  $timesum[ $row["TG"]] >= 500)
        {
            @$json_array[$row["TG"]] ["Secound"] = secondsToDHMS($timesum[ $row["TG"]]);
            @$json_array[$row["TG"]] ["unixtime"] = $timesum[ $row["TG"]];
        }
    }
    
    
    
    echo json_encode ($json_array);
    
    
    
    
    
    
}



else if ($_GET['cahce_mouth_tg'] == "1")
{

    $year = date("Y",strtotime($day));
    $Mounth = (int)date("m",strtotime($day));
   
    
    $sql = "SELECT sum(Total_T) as total_talked , TG FROM `trafic_day_statistics` WHERE Year = '$year' AND Mounth ='$Mounth'   GROUP BY TG";
    
    
    $key = "svxportal_cahce_mouth_tg_".$year."_".$Mounth;  
    $sql_data =sql_to_array_redis_cahce($key,$sql);
    
 
 //   $sqla = $link->query($sql);
    $timesum =array();
    
    $i =0;
    foreach ($sql_data as $j => $row) 
    {
        if(check_key_config_bool($row["TG"],$definde_qsy_tg) == true)
          $timesum[$row["TG"]] =$row["total_talked"];
    }
    
 
    $sql1 =  "SELECT `TG` FROM `trafic_day_statistics` WHERE   Year = '$year' AND Mounth ='$Mounth'  GROUP BY `TG` ORDER BY TG ";
    
    
    $key = "svxportal_cahce_mouth_tg_".$year."_".$Mounth."_2";
    $sql_data =sql_to_array_redis_cahce($key,$sql1);
    
    
    $json_array = array();
    
    //while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    foreach ($sql_data as $j => $row)
    {
        if(check_key_config_bool($row["TG"],$definde_qsy_tg) == true &&  $timesum[ $row["TG"]] >= 60)
        {
        @$json_array[$row["TG"]] ["Secound"] = secondsToDHMS($timesum[ $row["TG"]]);
        @$json_array[$row["TG"]] ["unixtime"] = $timesum[ $row["TG"]];
        }
    }
            

    
    echo json_encode ($json_array);
    
 
    
    
    
    
}
else if ($_GET['cahce_mouth'] == "1")
{
    

    
    
    
    $qrv = $link->real_escape_string($qrv);
    $day = $link->real_escape_string($day);
    

        
        $year = date("Y",strtotime($day));
        $Mounth = (int)date("m",strtotime($day));
        
        
        


        
        $sql = "SELECT `Node`, sum(Total_T) as total_talked  FROM `trafic_day_statistics` WHERE Year = '$year' AND Mounth ='$Mounth'  GROUP BY `Node` order by Node";

        

        
        $key = "svxportal_cahce_mouth_".$year."_".$Mounth;
        
        
        $sql_data =sql_to_array_redis_cahce($key,$sql);
        
        
        
              
        
        $time_total_usage=0;
        $outarray = array();
        $i =0;
        
        //get_most_use_reciver($day);
        
        
        foreach ($sql_data as $j => $row) 
        {
           
            
            $outarray['data'][$i]['call']=$row["Node"];
            $outarray['data'][$i]['time'] = $row['total_talked'];
            $time_total_usage = $time_total_usage+$outarray['data'][$i]['time'] ;
            $outarray['data'][$i]["Secound"] = secondsToDHMS($row['total_talked']);
            
            $outarray['data'][$i]["reciver"] = $moste_used_station[$row["Node"]];
            
            
            $i++;
        }
        $outarray['total_secounds'] = $time_total_usage;
        
        
        echo json_encode ($outarray);
        
        
    
    
    
    
    
    
    
}
else if ($_GET['cahce_year'] == "1")
{
    
    
    
    
    
    $qrv = $link->real_escape_string($qrv);
    $day = $link->real_escape_string($day);
    
    
    
    $year = date("Y",strtotime($day));
    $Mounth = (int)date("m",strtotime($day));
    
    


    
    //
    //$sql = "SELECT `Node`, sum(Total_T) as total_talked  FROM `trafic_day_statistics` WHERE Year = '$year' GROUP BY `Node` order by Node";
    $sql = "SELECT `Node`, sum(`TOT_T`) as total_talked  FROM `trafic_mounth_statistics` WHERE Year = '$year' GROUP BY `Node` order by Node"; 
   
    
    

    
    $key = "svxportal_cahce_year_".$year;
    $sql_data =sql_to_array_redis_cahce($key,$sql,500);

    
    $time_total_usage=0;
    $outarray = array();
    $i =0;
    
    //get_most_use_reciver($day);
    

    
    
    foreach ($sql_data as $j => $row) 
    {
        $outarray['data'][$i]['call']=$row["Node"];
        $outarray['data'][$i]['time'] = $row['total_talked'];
        $time_total_usage += $row['total_talked'];
        $outarray['data'][$i]["Secound"] = secondsToDHMS($row['total_talked']);
        
        $outarray['data'][$i]["reciver"] = $moste_used_station[$row["Node"]];
        
        
        $i++;
    }
    $outarray['total_secounds'] = $time_total_usage;
    //var_dump($time_total_usage);
    
    echo json_encode ($outarray);
    
    
    
    
    
    
    
    
    
}
// Data for talkgroup on fist page on statistics 
else 
{
    
// chace on front

    
    if(if_day_in_cache($day) >0 )
    {
        // same but from pre calucated_table
        {
            
            
            
            $year = date("Y",strtotime($day));
            $Mounth = (int)date("m",strtotime($day));
            $day = (int)date("d",strtotime($day));
            
            
            $sql = "SELECT sum(Total_T) as total_talked , TG FROM `trafic_day_statistics` WHERE Year = '$year' AND Mounth ='$Mounth' and Day ='$day' GROUP BY TG";
            
            //$sqla = $link->query($sql);
            
            
            $key = "svxportal_cahce_day".$year."_".$Mounth."_".$day."_1";
            $sql_data =sql_to_array_redis_cahce($key,$sql);
            
            
            
            $timesum =array();
            
            $i =0;
            foreach ($sql_data as $j => $row) 
            {
                
                $timesum[$row["TG"]] =$row["total_talked"];
            }
            
            
            
            
            //$result = mysqli_query($link, );
            $sql1 = "SELECT `TG` FROM `trafic_day_statistics` WHERE   Year = '$year' AND Mounth ='$Mounth' and Day ='$day'  GROUP BY `TG` ORDER BY TG ";
            
            $key = "svxportal_cahce_day".$year."_".$Mounth."_".$day."_2";
            $sql_data =sql_to_array_redis_cahce($key,$sql1);
            
            
            
            
            
            // Numeric array
            //echo "<br />";
            // Associative array
            $json_array = array();
            
            //while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            foreach ($sql_data as $j => $row) 
            {
                @$json_array[$row["TG"]] ["Secound"] = secondsToDHMS($timesum[ $row["TG"]]);
                @$json_array[$row["TG"]] ["unixtime"] = $timesum[ $row["TG"]];
                
            }
            
            
            
            
            
            
            
        }
        
        
        
    }
    else
    {
        
    
    
        $json_array= array();
        $day = $link->real_escape_string($day);
        //$day ="2021-05-17";
        
        $tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:59.000000'";
        
        //$sql_active ="SELECT sum(Talktime), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' $station_qvery AND  `Active` ='1' AND $tme_string group by  `Talkgroup`";
        
        $sql_nonactive ="SELECT sum(Talktime), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1'$station_qvery AND `Active` ='0' AND $tme_string group by  `Talkgroup` ORDER BY 'Talkgroup'";
        
        /*
        echo $sql_active;
        echo "<br>";
        */
        
        //echo $sql_nonactive;
        
        
        
    
        $sqla = $link->query($sql_nonactive);
        $timesum =array();
      
        $i =0;   
        while($row = $sqla->fetch_assoc()) {
            
            //echo $i++;
            $timesum[$row["Talkgroup"]] =$row["sum(Talktime)"];
        }
    
    
        $result = mysqli_query($link, "SELECT `Talkgroup` FROM `RefletorNodeLOG` GROUP BY `Talkgroup` ");
        
        
        
        // Numeric array
        //echo "<br />";
        // Associative array
        $json_array = array();
        
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            @$json_array[$row["Talkgroup"]] ["Secound"] = secondsToDHMS($timesum[ $row["Talkgroup"]]);
            @$json_array[$row["Talkgroup"]] ["unixtime"] = $timesum[ $row["Talkgroup"]];
        
        }
        
        
    }
 
    echo json_encode ($json_array);
}



?>
