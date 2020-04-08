<?php
include_once  'config.php';
// Create connection
$conn = new mysqli($host, $user, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function secondsToDHMS($seconds) {
    $s = (int)$seconds;
    if($s >0)
        return sprintf('%d:%02d:%02d:%02d', $s/86400, $s/3600%24, $s/60%60, $s%60);
        else
            return "0:00:00:00";
            
}
$day= $_GET['date'];


$day = $link->real_escape_string($day);
$tme_string ="`Time` BETWEEN '$day 00:00:00.000000' AND '$day 23:59:00.000000'";

$sql_active ="SELECT sum(UNIX_TIMESTAMP(`Time`)), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' AND  `Active` ='1' AND $tme_string group by  `Talkgroup`";

$sql_nonactive ="SELECT sum(UNIX_TIMESTAMP(`Time`)), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' AND `Active` ='0' AND $tme_string group by  `Talkgroup` ";

echo $sql_active;
echo "<br>";

$sqlref = $link->query($sql_active);
$sqla = $link->query($sql_nonactive);
$timesum =array();

while($row = $sqla->fetch_assoc()) {
    $timesum[$row["Talkgroup"]] =$row["sum(UNIX_TIMESTAMP(`Time`))"];
}
while($row = $sqlref->fetch_assoc()) {
    $timesum[$row["Talkgroup"]] =$timesum[$row["Talkgroup"]] -$row["sum(UNIX_TIMESTAMP(`Time`))"];
    
}

$result = mysqli_query($link, "SELECT `Talkgroup` FROM `RefletorNodeLOG` GROUP BY `Talkgroup` ");



// Numeric array
echo "<br />";
// Associative array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
    
    echo $row["Talkgroup"]." ->";
    echo secondsToDHMS($timesum[ $row["Talkgroup"]]);
    echo "<br />";
}


?>