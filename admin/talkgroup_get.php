<?php
include_once '../config.php';
include_once '../function.php';
set_laguage();
mysqli_set_charset($link,"utf8");

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){


$result = mysqli_query($link, "SELECT * FROM `Talkgroup` ");



    while($row = $result->fetch_assoc()) {
        
        ?>
          
            <tr>
    
          <td><?php echo $row['TG']?></td>
          <td><?php echo $row['TXT']?></td>
         <?php if($reflektor_db == 1){ ?>
     	 <td ><a class="btn btn-primary btn-sm" href="#" onclick="tg_acl_admin('<?php echo $row['TG'] ?>')" role="button"><?php echo  _('Reflector ACL')?></a></td>
      	<?php }else{?>
      	<td ><button type="button" class="btn btn-secondary  btn-sm" disabled><?php echo  _('Reflector ACL')?></button></td>
   	   <?php }?>
      
      
    
                <td><i class="fas fa-trash" onclick="Delete_tg(<?php echo $row['ID']?>)"></i></td>
          
          <?php     echo "<td>".'<div onclick="update_color_tg('. $row['ID'].',\''.$row["Collor"].'\')" style="border:2px solid black; width: 25px; height :25px;  background-color:'.$row["Collor"].' ">'."</td>"; ?>
          </tr>
    <?php }?>
      
<?php }?>