<?php
include_once '../config.php';
include_once '../function.php';
set_laguage();



if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
    $Reflektor_link =  mysqli_connect($reflektor_db_host, $reflektor_db_user, $reflektor_db_password , $reflektor_db_db);
    mysqli_set_charset($Reflektor_link,"utf8");
    $result = mysqli_query($Reflektor_link, "SELECT * FROM `users` ");
    
    
    
    while($row = $result->fetch_assoc()) 
    {
     if($row['active'] == 0)
      {
          $class_tr =  'table-warning';
      
      }
      else
      {
          $class_tr =  'table-light';
      }
      ?>
     
        <tr class="<?php echo $class_tr; ?>">
          <th scope="row"><?php echo $row['id']?></th>
          <td><?php echo $row['user']?></td>
          <td>********</td>
          <td><?php echo $row['description']?></td>
          <td><?php echo $row['e-mail']?></td>
        <?php   if($row['active'] == 1){?>
          <td><?php echo _('Yes')?></td>
        <?php }else{?>
          <td><?php echo _('No')?></td>
        <?php }?>
        
        <?php   
        if($row['active'] == 1)
        {
            $enable_str="enable_reflektor_user(".$row['id'].",0)";
        }
        else
        {
            $enable_str="enable_reflektor_user(".$row['id'].",1)";
        }
        if($row['Monitor'] == 0)
        {
            $class ="table-info";
        }
        

        ?>
       <td> 
           <select class="form-control" onchange="refector_action(this.value,<?php echo $row['id'] ?>,this)">
          	  <option value ="">- <?php echo _("Select action")?> -</option>
          	  <?php   if($row['active'] == 0){?>
              <option value ="1"><?php echo _("Enable user")?></option>
              <?php }else{?>
              <option value ="2"><?php echo _("Disable user")?></option>
              <?php }?>
              <option value ="3" ><?php echo _("Send message to Sysadmin")?></option>
              <option value ="4" ><?php echo _("Send login credentaial to sysadmin")?></option>
              <option value ="5" ><?php echo _("Change node password")?></option>
              
             
    
          
          </select>
   	  </td>
        
   
       
          <td><i class="fas fa-trash" onclick="Delete_reflektor_user(<?php echo $row['id']?>)"></i> <i onclick="chahge_reflektor_password_r(<?php echo $row['id']?>)" class="fas fa-key"></i> <i onclick="<?php echo $enable_str?>" class="fa fa-certificate" aria-hidden="true"></i> </td>
      </tr>
          
          
    <?php }?>
      
   <?php }?>