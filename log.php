<?php
include_once 'config.php';

$last_id = $_GET['id'];

$conn = new mysqli($host, $user, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn,"utf8");
$sql ="SELECT * FROM `RefletorNodeLOG` where Type ='1' OR (Type = '2' AND Active = '1')  ORDER BY `RefletorNodeLOG`.`Id` DESC limit 500 ";
$result = $conn->query($sql);
$i=1;
?>
<table class="table">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Station</th>
      <th scope="col">Event</th>

    </tr>
  </thead>
  <tbody>

<?php 




while($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>'.$row["Time"]."</td>";
    echo '<td>'.$row["Callsign"]."</td>";
    echo '<td>';
    if($row['Type'] ==1) 
    {
    if($row['Active'] == 1)
         echo "is Talker on tg ".$row['Talkgroup'];
        else
         echo "Stoped Talkon tg ".$row['Talkgroup'];
    }
    else
    {
        echo "Reciver ".$row['Nodename'];
        if($row['Active'] == 1)
            echo " is active signal level ".$row['Siglev'];
        else
            echo " has signal level ".$row['Siglev'];
        
        
    }
    echo '</td>';
     echo "</tr> ";
       
}

?>
  </tbody>
</table>