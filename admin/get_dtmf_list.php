<?php
include "../config.php";
include '../function.php';
define_settings();
mysqli_set_charset($link,"utf8");
$command_group= DTMF_Catgory();

if( $_SESSION['loginid'] >0 )
{
    if($_GET['Station_Name'])
    {
        $callsign =$_GET['Station_Name'];
        $callsign= $link->real_escape_string($callsign);
        $result = mysqli_query($link, "SELECT * FROM `Dtmf_command` where Station_Name = '$callsign' order by Category,id");
    }
    if($_GET['Station_id'])
    {
        $callsign =$_GET['Station_id'];
        $callsign= $link->real_escape_string($callsign);
        $result = mysqli_query($link, "SELECT * FROM `Dtmf_command` where Station_id = '$callsign' order by Category,id");
        
    }
    
    $premission_rw=0;
    // Associative array
    $once_lock =0;
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if($once_lock ==0)
        {
            $premission_rw =check_premission_station_RW($row['Station_id'],$_SESSION['loginid']);
            $once_lock++;
        }
        
       ?>
       
       <tr>
       <td>
        <?php echo $row['Command'];?>
       </td>
       <td>
       <?php echo $row['Description'];?>
       
       
       </td>
       
       <td>
       <?php echo $command_group[$row['Category']]?>
       </td>
       
       
       <td>
       
       
       <?php if($premission_rw >0 ){?>
     <a class="navbar-brand" href="#" onclick="Remove_command('<?php echo $row['id']?>')"><i class="fas fa-trash-alt"></i></a>
       
       <a href="#" data-toggle="modal" data-target="#Update_command" onclick="update_commade(<?php echo $row['id']?>,'<?php echo htmlentities($row['Command'], ENT_QUOTES, "UTF-8");?>','<?php echo htmlentities($row['Description'], ENT_QUOTES, "UTF-8");?>','<?php echo ($row['Category']);?>')">
   		<i class="fas fa-pen"></i>   </a> 
    
    <?php }?>
       </td>
    
       
       
    
       
       
       </tr>
        
<?php   
    }

}
?>	