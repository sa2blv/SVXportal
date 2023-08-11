<?php

include_once "../config.php";
include_once '../function.php';

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
    
   
   $Reflektor_link =  mysqli_connect($reflektor_db_host, $reflektor_db_user, $reflektor_db_password , $reflektor_db_db);
   mysqli_set_charset($Reflektor_link,"utf8");
    
?>


<script type="text/javascript">
function create_reflektor_user()
{
	var usern = $('#usern2').val();
	var pass = $('#password2').val();

	if(usern != "" && pass != "")
	{
    	$.post( "admin/reflektor_action.php", $( "#create_reflektoruser_form" ).serialize() )
    	.done(function( data ) {
    		alert("<?php echo _('User created sucsessfully!')?>")
    		$("#create_reflektoruser_div").hide();
    		$('#create_reflektoruser_form').trigger("reset");
    		reaload_reflektor_table();
    		
    
    	});
	}
	

	return false;
}

function Delete_reflektor_user(id)
{

	if( confirm("<?php echo _('Please confirm user removal')?>") == true )
    	$.post( "admin/reflektor_action.php",  { userdel: "1", userid: id } )
    	.done(function( data ) {
    		reaload_reflektor_table();
    
    	});
	
	

	return false;
}


function enable_reflektor_user(id,type)
{

	var str="<?php echo _('Please confirm user enable')?>"
	if(type == 0)
	{
		 str="<?php echo _('Please confirm user disable')?>"
	}

	if( confirm(str) == true )
    	$.post( "admin/reflektor_action.php",  { change_active: "1", user_id: id, Enable: type } )
    	.done(function( data ) {
    		reaload_reflektor_table();
    
    	});
	
	

	return false;
}


function chahge_reflektor_password_r(id)
{


	$("#password_id_a").val(id);
	 $("#myModa_passl").modal() 
	return false;
}



function update_reflektor_password()
{
	var pass = $('#password_id_r').val();
	console.log(pass);
	if(pass)
	{
    	$.post( "admin/reflektor_action.php", $( "#update_password_r" ).serialize() )
    	.done(function( data ) {
    		alert("<?php echo _('Password is updated!')?>");
    		$('#update_password_r').trigger("reset");
    		$('#myModa_passl').modal('toggle');

    		reaload_reflektor_table();
    		

    
    	});
	}
	return false;
}
function user_d()
{
	$("#create_reflektoruser_div").toggle()
	
}
function reaload_reflektor_table()
{
	$.get( "admin/get_reflector.php", function( data ) {


		  $("#reflektoruser_table_one_p tbody").html(data);
		});
}
function generate_password(field)
{
	var pwdChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	var pwdLen = 12;
	var randPassword = Array(pwdLen).fill(pwdChars).map(function(x) { return x[Math.floor(Math.random() * x.length)] }).join('');


	$('#'+field).prop('type', 'text');
	$("#"+field).val(randPassword);
}

function generate_user(field)
{
	var number = Math.random() // 0.9394456857981651
	number.toString(36); // '0.xtis06h6'
	var id = number.toString(36).substr(2, 9); // 'xtis06h6'
	id.length >= 9; // false


	$("#"+field).val(id);
}
function refector_action(value,id,element)
{
	console.log(value);
	switch(value) {
	  case "1":
		  enable_reflektor_user(id,1)
	    // code block
	    break;
	  case "2":
		  enable_reflektor_user(id,0)
	    // code block
	    break;
	  case "3":
		  Send_message_to_admin(id);
	    // code block
	    break;

	  case "4":
		  Send_password(id);
	    // code block
	    break;

	  case "5":
		  chahge_reflektor_password_r(id);
	    // code block
	    break;
	  default:
	    // code block
	} 

	element.selectedIndex = null;
	 
	
}

function Send_message_to_admin(id)
{

	var message = prompt("<?php  echo _("Please enter your message")?>", "");

	if (message != null) 
	{

    	$.post( "admin/reflektor_action.php",  { send_msg: "1", user_id: id, msg: message } )
    	.done(function( data ) {
    		reaload_reflektor_table();
    		alert("<?php echo  _("Message sent")?>");
    
    	});


	}
	 


	
}

function Send_password(id)
{



    	$.post( "admin/reflektor_action.php",  { Send_password: "1", user_id: id } )
    	.done(function( data ) {
    		reaload_reflektor_table();
    		alert("<?php echo  _("Message sent")?>");
    
    	});


	
	 


	
}










</script>





  <nav class="navbar navbar-expand-lg navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		<a class="navbar-brand" href="#"><?php echo _('Reflector users')?></a>
		
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">


      
             <li class="nav-item">
        
             
        <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="PrintElem('reflektoruser_table_one_p','<?php echo _('Print Users')?>')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-print"></i>
          <?php echo _('Print')?>
        </a>
             
        </li>
         <li class="nav-item">
                <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="fnExcelexport('reflektoruser_table_one')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="far fa-file-excel"></i>
          <?php echo _('Export xls')?>
        </a>
        </li>
        
        <li class="nav-item">
                <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="user_d()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fas fa-user"></i>
          <?php echo _('Create user')?>
        </a>
        </li>
        

      
    </ul>




      </div>

      
    </nav>
    
    <div id="create_reflektoruser_div" style="display: none">
    


        
    <form id="create_reflektoruser_form"  onsubmit="return create_reflektor_user()">
        <h2><?php echo _('Create user')?>:</h2>
          <fieldset class="form-group">
      <div class="form-group ">
      	<div class="col-sm-12">
       	 	<label for="usern2"><?php echo _('Username')?></label>
       	</div>
   	   <div class="row">
           <div class="col-sm-8">
            	<input type="text" name="usern" class="form-control" id="usern2" placeholder="">
           </div>
           
           <div class="col-sm-4">
              	 <button type="button" onclick="generate_user('usern2')" class="btn btn-primary"><?php echo _('Generate Username')?></button>
           
            </div>
       </div>
      </div>
      <div class="form-group">
      	<div class="col-sm-12">
        	<label for="password2"><?php echo _('Password')?></label>
        </div>
        <div class="row">

            <div class="col-sm-8">
            	<input type="password" name="password" class="form-control" id="password2" placeholder="">
            </div>
            <div class="col-sm-4">
              	 <button type="button" onclick="generate_password('password2')" class="btn btn-primary"><?php echo _('Generate password')?></button>
           
            </div>
 		</div>
        
      </div>
      
      
      

      <div class="form-group ">
      	<div class="col-sm-12">
       	 	<label for="description"><?php echo _('Description')?></label>
       	</div>
   	   <div class="row">
           <div class="col-sm-8">
            	<input type="text" name="description" class="form-control" id="description" placeholder="Sk0RK Stockholm">
           </div>
       </div>
      </div>
      
    <div class="form-group ">
      	<div class="col-sm-12">
       	 	<label for="mail"><?php echo _('E-mail')?></label>
       	</div>
   	   <div class="row">
           <div class="col-sm-8">
            	<input type="email" name="mail" class="form-control" id="mail" placeholder="test@test.com">
           </div>
       </div>
   </div>

      
      
      
        <div class="form-group row">
        <div class="col-sm-8">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="gridCheck1" value="1"  name="Enable" checked="checked">
                <label class="form-check-label" for="gridCheck1">
                  <?php echo _('Enable')?>
                </label>
              </div>
         </div>
          <div class="col-sm-4">
          	 <button type="submit" class="btn btn-primary"><?php echo _('Create user')?></button>
       
        </div>
        
        </div>
        </fieldset>
            	<input type="hidden" name="newuser" value="1" >
        
    </form>

</div>
<br />
<div id="reflektoruser_table_one_p">
    <table class="table table-sm " id="reflektoruser_table_one">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col"><?php echo _('Station login')?></th>
          <th scope="col"><?php echo _('Station password')?></th>
          <th scope="col"><?php echo _('Description')?></th>
          <th scope="col"><?php echo _('E-mail')?></th>
          <th scope="col"><?php echo _('Enable')?></th>
          <th scope="col" colspan="2"><?php echo _('Action')?></th>
        </tr>
      </thead>
      <tbody>
      
      <?php 
      
      
      $result = mysqli_query($Reflektor_link, "SELECT * FROM `users` ");
 
      
      
      while($row = $result->fetch_assoc()) {
          
      ?>
      <?php if($row['active'] == 0)
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
          <option value ="5" ><?php echo _("Change user password")?></option>
          
         

      
      </select>
      
       </td>
       
             <td><i class="fas fa-trash" onclick="Delete_reflektor_user(<?php echo $row['id']?>)"></i> <i onclick="chahge_reflektor_password_r(<?php echo $row['id']?>)" class="fas fa-key"></i> <i onclick="<?php echo $enable_str?>" class="fa fa-certificate" aria-hidden="true"></i>
      </td>
       
      </tr>
      
      
      <?php }?>
  
 <?php }?>
    

  </tbody>
</table>
</div>

<!-- Trigger the modal with a button -->

<!-- Modal -->
<div id="myModa_passl" class="modal fade" role="dialog">
  <div class="modal-dialog">
 <form id="update_password_r" onsubmit="return update_reflektor_password()">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title"><?php echo _('Change user password') ?></h4>
      </div>
      
      <div class="modal-body">
      
      
          <label for="password_id_r" class="sr-only"><?php echo _('Password') ?></label>
          
    		<input type="password" name="password" class="form-control" id="password_id_r" placeholder="Password">
    		<input type="hidden" name="user_id" id="password_id_a" value=""> 
    		<input type="hidden" name="change_password"  value="1"> 
    		
    	  <button type="button" onclick="generate_password('password_id_r')" class="btn btn-primary"><?php echo _('Generate password')?></button>
      
     
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close')?></button>
        <button type="submit" class="btn btn-default" ><?php echo _('Update')?></button>
      </div>
       </form>
    </div>

  </div>
</div>

<?php 

$Reflektor_link -> close();
?>


