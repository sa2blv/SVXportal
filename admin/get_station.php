<?php
include_once '../config.php';
include_once '../function.php';
set_laguage();
mysqli_set_charset($link,"utf8");

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
    

    
    $result = mysqli_query($link, "SELECT * FROM `RefletorStations` where Callsign != '' ");
    
    
    
    while($row = $result->fetch_assoc()) {
        
        if($row['Station_Down'] == 1)
        {
            $class ="table-danger";
        }
        else
        {
            $class ="table-success";
        }
        
        
        ?>
      
      <tr class="<?php echo  $class?>">
      <td><?php echo $row['Callsign']?></td>
      <td><?php echo $row['Location']?></td>
      <td><?php echo $row['Last_Seen']?></td>
    <?php   if($row['Station_Down'] == 1){?>
      <td><?php echo _('Yes')?></td>
    <?php }else{?>
      <td><?php echo _('No')?></td>
    <?php }?>
    
    <?php     echo "<td>".'<div onclick="update_color('. $row['ID'].',\''.$row["Collor"].'\')" style="border:2px solid black; width: 25px; height :25px;  background-color:'.$row["Collor"].' ">'."</td>"; ?>
      </tr>
      
      
      <?php }?>
      
<?php }?>