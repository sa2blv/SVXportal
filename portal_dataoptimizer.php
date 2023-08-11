<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include 'svx/config.php';
$link->set_charset("utf8");



function get_stationid($call)
{
    global $link;
    
    if($call == null)
        return -1;
    
    $call = $link->real_escape_string($call);
    
    
    $result = mysqli_query($link, "SELECT `ID` FROM `RefletorStations` WHERE `Callsign` = '$call'  ");
    
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
        return $row['ID'];
    
    }
    
}

function get_time($station,$day)
{
    global $link;
    
    $station_qvery =" AND Callsign ='".$station."' ";
    
    
    $tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:59.000000'";
    
    
    $sql_nonactive ="SELECT sum(Talktime), Callsign FROM RefletorNodeLOG WHERE `Type` = '1'$station_qvery AND `Active` ='0' AND $tme_string  ";
    

    $sqla = $link->query($sql_nonactive);
    $timesum =array();
    
    while($row = $sqla->fetch_assoc()) {
        @$timesum[$row["Callsign"]] =$timesum[$row["Callsign"]]+$row["sum(Talktime)"];
    }
    if( @$timesum[$station])
        
        return $timesum[$station];
    else
        return 0;
    
}

function scan_dir($dir)
{

    // echo $dir."qsorec_RepeaterLogic_".$date."*.ogg";
    $filelist = glob($dir."qsorec_SimplexLogic_*.ogg");
    
    
    return $filelist;
    
}








$files = (scan_dir($dir."/"));



$date_array = array();
$i=0;
foreach($files as $key => $val)
{
    $date_array[$i] =filemtime($val);
    $i++;


}


//the lowest date from QSO FILES 
$lowest_file_date =date("Y-m-d",min($date_array));

// seleting latestt date from db.
$max_data_sql ="SELECT MAX(`Date`) as maxdate FROM `Station_day_statistic`";
$result =  mysqli_query($link, $max_data_sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$max_date_from_db = $row['maxdate'];
$max_date_from_db  =  strtotime($max_date_from_db);

$max_date_from_db = date("Y-m-d",strtotime('+1 day', $max_date_from_db));



if($max_date_from_db == $lowest_file_date)
{
    echo "Nothing is to be done \r\n";
    exit(0);
    
}
// Numeric array
echo "Staring Calulation fo main data  from $max_date_from_db until  $lowest_file_date \r\n";



    
    






echo "Wating for Database \r\n";

$lowest_file_date = date("Y-m-d",strtotime('-1 day', strtotime($lowest_file_date)));


$mysql_qyvery =" SELECT MAX(`Nodename`),`Callsign`,min(time) , avg(`Siglev`) as avrige ,  min(`Siglev`) as minsiglev, max(`Siglev`) as maxsiglev FROM `RefletorNodeLOG` WHERE `time` BETWEEN '$max_date_from_db 00:00:00'  AND '$lowest_file_date 23:59:59' and `Type` = 2 AND `Active` = 1  GROUP BY DATE_FORMAT(`Time`, '%Y%m%d'), Callsign";




echo "---------- calulation start ---------- \r\n";



if(!$result = mysqli_query($link, $mysql_qyvery))
{
    echo "Databe error on data selection \r\n";
    die(-2);
    
}


 $link->begin_transaction();
 $link->autocommit(FALSE);



// Associative array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
    $station_id = get_stationid($row['Callsign']);
   
    $nodename = $row['MAX(`Nodename`)'];
    $min = $row['minsiglev'];
    $avg = $row['avrige'];
    $max = $row['maxsiglev'];
    $time = $row['min(time)'];
    $time = date("Y-m-d", strtotime($time));
    $talktime = get_time($row['Callsign'],$time);
    
    echo $station_id ." - talktime: ".$talktime." ".$time ."\r\n";

    $link->query("INSERT INTO `Station_day_statistic` (`Id`, `Station_id`, `Date`, `Active_secunds`, `Max_reciver`, `minsiglev`, `avrige`, `maxsiglev`) VALUES (NULL, '$station_id', '$time 00:00:00', '$talktime', '$nodename', '$min', '$avg', '$max');");

        
        


    
    
    
    

}
$link->commit();

// starting arcive transction
echo "--- Staring copy transfer data from main table to history table ---- \r\n";
$link->begin_transaction();
$link->autocommit(FALSE);

if(!$res = $link->query(" INSERT INTO ReflectorNodeLOG_History SELECT * FROM RefletorNodeLOG WHERE Type = '2' AND `time` BETWEEN '$max_date_from_db 00:00:00'  AND '$lowest_file_date 23:59:59' "))
{
    echo "Database error, on data copy transfer \r\n";
    die(-2);
    
}
    

$link->commit();
echo "--- Transfer of history data done ----\r\n";

echo "--- Start of cleaning history data  from main table ----\r\n";
$link->begin_transaction();
$link->autocommit(FALSE);


if(!$res = $link->query("DELETE FROM RefletorNodeLOG  WHERE Type = '2' AND `time` BETWEEN '$max_date_from_db 00:00:00'  AND  '$lowest_file_date 23:59:59'"))
{
    echo "Database error, on deliting data from table \r\n";
    die(-2);
}


echo "--- Optimizer finished at ".date("Y-m-d H:i:s")." ----\r\n";






$link->close();
