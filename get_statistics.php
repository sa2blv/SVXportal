<?php
include_once  'config.php';
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
    
    while($row = $sqla->fetch_assoc()) {
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


    $sqla = $link->query($quvery);
    
    $count_array= array();
 
    while($row2 = $sqla->fetch_assoc())
    {
        //echo '<pre>';
        //var_dump($row2);
        $count_array[$row2['Callsign']][$row2['MAX(`Nodename`)']]++;
        
        $moste_used_station[$row2['Callsign']] = $row2['MAX(`Nodename`)'];
    }
    
    //echo '<pre>';
    arsort($count_array);
    while($row2 = $sqla->fetch_assoc())
    {

        
        $moste_used_station[$row2['Callsign']] =$count_array[$row2['Callsign']][0];
    }
    
    $quvery_optimizer =  "SELECT Max_reciver , `Station_id` , RefletorStations.Callsign as callsing FROM `Station_day_statistic` LEFT JOIN RefletorStations ON `Station_id` = RefletorStations.ID WHERE `Date` = '$day'"; 
    $sqla = $link->query($quvery_optimizer);
    while($row2 = $sqla->fetch_assoc())
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
    
    //
  
    echo json_encode ($json_array);
    
}
else if($qrv != "")
{
    $qrv = $link->real_escape_string($qrv);
    $day = $link->real_escape_string($day);
    $tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:59.000000'";
    
    $sql_stations ="SELECT Callsign , sum(Talktime)  FROM RefletorNodeLOG WHERE `Type` = '1' and Active='0'  AND $tme_string group by  `Callsign`";

    
    $sqlstat = $link->query($sql_stations);
    $outarray = array();
    $i =0;
    
    
    get_most_use_reciver($day);
    
    
    $time_total_usage=0;
    while($row = $sqlstat->fetch_assoc()) {
        $outarray['data'][$i]['call']=$row["Callsign"];
        //$outarray['data'][$i]['time'] =get_time($row["Callsign"],$day);
        $outarray['data'][$i]['time'] = $row['sum(Talktime)'];
        $time_total_usage = $time_total_usage+$outarray['data'][$i]['time'] ;
        //$outarray['data'][$i]["Secound"] = secondsToDHMS(get_time($row["Callsign"],$day));
        $outarray['data'][$i]["Secound"] = secondsToDHMS($row['sum(Talktime)']);
        
        $outarray['data'][$i]["reciver"] = $moste_used_station[$row["Callsign"]];
        
        
        $i++;
    }
    $outarray['total_secounds'] = $time_total_usage;
    
    
    echo json_encode ($outarray);
    
    
    
}
else if( $_GET['time'] == "true")
{
    
    $day = $link->real_escape_string($day);
    
    
    //$tme_string ="`Time` BETWEEN '$day $timel:00:00.000000' AND '$day $timel:59:59.000000'";
    $tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:59.000000'";
    
    //$sql_active ="SELECT sum(UNIX_TIMESTAMP(`Time`)), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' $station_qvery AND  `Active` ='1' AND $tme_string group by  `Talkgroup`";
    $station_string= "";
    
    if ($_GET['station'] !="")
    {
        $station =$link->real_escape_string($_GET['station']);
        $station_string = "AND Callsign ='$station'";
    }
    
    $sql_nonactive ="SELECT UNIX_TIMESTAMP(Time), Talktime, `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' $station_qvery AND `Active` ='0' AND $tme_string $station_string  ";
    $sqla = $link->query($sql_nonactive);
    $data = array();
    while($row = $sqla->fetch_assoc()) {
        $data[] = $row;
        
    }
    

    


    for($timel =0; $timel <= 24;$timel++)
    {
        $filterdate = strtotime($day);
        $low_time =$filterdate+ ($timel*3600);
        $high_time =$filterdate+ ($timel*3600)+(59*60)+59;
        
        $timesum =array();
        $timiesums  =array();
        $timiesums[$timel] =0;
        
        // loop throu remove row if match is found 
        foreach ($data as & $row) {
       
            if(  $row['UNIX_TIMESTAMP(Time)'] >=   $low_time    &&    $row['UNIX_TIMESTAMP(Time)']  <=$high_time  )
            {
            
                $timesum[$row['Talkgroup']] = $timesum[$row['Talkgroup']]+$row["Talktime"];
                $timiesums[$timel] =$timiesums[$timel] +$row["Talktime"];
                unset($row);
            }
            
            
            
        }
        
        

        
       
        
//               echo $sql_active;
//          echo "<br>";
         
       // $sqlref = $link->query($sql_active);
       

        
      
        
     

        
        
        /*
        while($row = $sqlref->fetch_assoc()) 
        {
            
            $timesum[$row['Talkgroup']] =$timesum[$row['Talkgroup']] -$row["sum(UNIX_TIMESTAMP(`Time`))"];
            
//             echo $row['Talkgroup'];
//             echo "<pre>";
//             var_dump($timesum[$row['Talkgroup']] );
            
            
            
            if ($timesum[$row['Talkgroup']] > 3600)
            {
                $correction =  strtotime($day);
                $correction = $correction+ mktime(($timel),0,0,01,01,1970);
                
                $timesum[$timel] = $timesum[$timel]- $correction;
                
            }
            
            if($timesum[$row['Talkgroup']] <0 )
            {
                $correction =  strtotime($day);
                
                $correction = $correction+ mktime(($timel),0,0,01,01,1970);
                $timesum[$row['Talkgroup']] = $timesum[$row['Talkgroup']] + $correction;
                // check if time is db erroer correct          
            }
           
            
            
            

            if($timesum[$row['Talkgroup']] >= 0 && $timesum[$row['Talkgroup']] <= 3600)
            {
                $timiesums[$timel] =$timiesums[$timel] + $timesum[$row['Talkgroup']];
                
            }
            else
            {
                // clear incorect data disable if debuging
                $timesum[$row['Talkgroup']]=0;
            }
            
          

            
            
        }
         */

            
        $json_array[$timel] ["Secound"] = secondsToDHMS($timiesums[$timel]);
        $json_array[$timel] ["unixtime"] = $timiesums[$timel];
        $json_array[$timel] ["TG"]= $timesum;
        
        
        

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
    
    $station_string= "";
    
    if ($_GET['station'] !="")
    {
        $station =$link->real_escape_string($_GET['station']);
        $station_string = "AND Callsign ='$station'";
    }
    
    
    
    $tme_string ="`Time` BETWEEN '$day-01 00:00:00.000000' AND '$day-$last_day 23:59:59.000000'";
    

    

    
    $sql="SELECT count(id), YEAR(`Time`), MONTH(`Time`), DAYOFMONTH(`Time`), sum(`Talktime`) as total_talktime FROM `RefletorNodeLOG` WHERE $tme_string AND Type = 1 AND Active = 0  $station_string GROUP BY YEAR(`Time`), MONTH(`Time`) , DAYOFMONTH(`Time`)  ORDER BY YEAR(`Time`) ASC, MONTH(`Time`) ASC, DAYOFMONTH(`Time`) ASC";
    
   // echo $sql;
    
    $json_array = array();
    
    
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
    
    
    
    
    
    
    echo json_encode ($json_array);
    
    
    
    
}
else 
{
        
    
    
    $json_array= array();
    $day = $link->real_escape_string($day);
    //$day ="2021-05-17";
    
    $tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:59.000000'";
    
    //$sql_active ="SELECT sum(Talktime), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' $station_qvery AND  `Active` ='1' AND $tme_string group by  `Talkgroup`";
    
    $sql_nonactive ="SELECT sum(Talktime), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1'$station_qvery AND `Active` ='0' AND $tme_string group by  `Talkgroup` ";
    
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
    echo "";
    echo json_encode ($json_array);
}



?>
