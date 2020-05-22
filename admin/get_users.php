<?php
include_once '../config.php';
include_once '../function.php';
set_laguage();
mysqli_set_charset($link,"utf8");

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
    
    $result = mysqli_query($link, "SELECT * FROM `users` ");
    
    
    
    while($row = $result->fetch_assoc()) {
        
        ?>
          
          <tr>
          <th scope="row"><?php echo $row['id']?></th>
          <td><?php echo $row['Firstname']?></td>
          <td><?php echo $row['lastname']?></td>
          <td><?php echo $row['Username']?></td>
        <?php   if($row['Is_admin'] == 1){?>
          <td><?php echo _('Yes')?></td>
        <?php }else{?>
          <td><?php echo _('No')?></td>
        <?php }?>
          <td><i class="fas fa-trash" onclick="Delete_user(<?php echo $row['id']?>)"></i> <i onclick="chahge_password(<?php echo $row['id']?>)" class="fas fa-key"></i> </td>
          </tr>
          
          
    <?php }?>
      
   <?php }?>