<?php
include_once '../config.php';
include_once '../function.php';
set_laguage();
mysqli_set_charset($link,"utf8");

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
    
    
    $svxlink_versonfile =  file_get_contents("https://raw.githubusercontent.com/sm0svx/svxlink/master/src/versions", 0, stream_context_create(["http"=>["timeout"=>1]]));
    $svxlink_verson_array =parse_ini_string($svxlink_versonfile);
    
    function station_class($station,$latest_version)
    {
        $station = trim($station);
        $latest_version = trim($latest_version);
        
        if($station == $latest_version && $station != "")
        {
            return "table-success";
        }
        elseif($station != $latest_version && $station != "")
        {
            
            return "table-warning";
        }
        else
        {
            return "table-info";
            
        }
        return "";
    }
    
    
    
    
    $result = mysqli_query($link, "SELECT * FROM `RefletorStations` where Callsign != '' ");
    
    
    while($row = $result->fetch_assoc()) {
        
        if($row['Station_Down'] == 1)
        {
            $class ="table-danger";
            $count_down++;
        }
        else
        {
            $class ="table-success";
            $count_up++;
            
        }
        if($row['Monitor'] == 0)
        {
            $class ="table-info";
        }
        
        
        ?>
      
      <tr >
      <td  style="text-transform: uppercase;"><b><?php echo $row['ID']?></b></td>
      <td  style="text-transform: uppercase;"><?php echo $row['Callsign']?></td>
      <td><?php echo $row['Location']?></td>
      <td class="<?php echo station_class($row['Version'],$svxlink_verson_array['SVXLINK'])?> !important"><?php echo $row['Version'] ?></td>
    
      
      <td style="text-transform: uppercase;"><b><?php echo $row['Sysop']?></b></td>
        <td><?php echo $row['Last_Seen']?></td>
      
    <?php   if($row['Station_Down'] == 1){?>
      <td class="<?php echo  $class?>"><?php echo _('Yes')?></td>
    <?php }else{?>
      <td class="<?php echo  $class?>"><?php echo _('No')?></td>
    <?php }?>
    
    
  	 <?php if($use_mqtt == True){?>
        <td><button onclick="mqtt_page('<?php echo $row['Callsign']?>')" type="button" class="btn btn-primary btn-sm"><?php echo _('Mqtt')?></button></td>
        <?php }else{?>
        <td></td>
        <?php } ?>
        
        
    
    
    <?php     echo "<td class=\"\" >".'<div onclick="update_color('. $row['ID'].',\''.$row["Collor"].'\')" style="border:2px solid black; width: 25px; height :25px;  background-color:'.$row["Collor"].' ">'."</td>"; ?>
      </tr>
      
      
      <?php }?>
      
<?php }?>